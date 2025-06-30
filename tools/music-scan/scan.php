<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

define('NV_CONSOLE_DIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '')));

require NV_CONSOLE_DIR . '/functions.php';

$getID3 = new getID3;

// Lấy hết thể loại nhạc có trong CSDL
$array_genres = [];
$categories = callAPI('CategoryList')['data'];
foreach ($categories as $category) {
    $key = md5(mb_strtolower($category['cat_name']));
    $array_genres[$key] = [
        'cat_id' => $category['cat_id'],
        'cat_name' => $category['cat_name'],
    ];
}

// Lấy hết chất lượng nhạc có trong CSDL đổi sang bitrate
$array_qualities = [];
$qualities = callAPI('SongQualityList')['data'];
foreach ($qualities as $quality) {
    if (preg_match('/^(\d+)[\s]*kbps$/', $quality['vi_quality_name'], $matches)) {
        $key = (int) $matches[1];
        if (!isset($array_qualities[$key])) {
            $array_qualities[$key] = $quality['quality_id'];
        }
    }
}

$source_files = listFilesRecursive($music_dir);
foreach ($source_files as $key => $filename) {
    $filename = str_replace('\\', '/', $filename);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($ext, $audio_extensions)) {
        continue;
    }

    $relative_path = str_replace($music_dir . '/', '', $filename);
    echo "[" . str_pad($key, 4, '0', STR_PAD_LEFT) . "] $relative_path\n";

    // Chỗ này xử lý sau
    if (!in_array($ext, $html5_audio_extensions)) {
        echo "\033[1;31m[er]\033[0m Không hỗ trợ HTML5 Audio: $ext\n";
        exit(1);
    }

    $info = $getID3->analyze($filename);

    $bitrate = isset($info['audio']['bitrate']) ? round($info['audio']['bitrate'] / 1000) : 0; // kbps
    if ($bitrate < 64) {
        echo "\033[1;31m[er]\033[0m Bitrate quá thấp: $bitrate kbps\n";
        exit(1);
    }
    $bitrate < 320 && $bitrate = 128;
    if (!in_array($bitrate, [128, 320])) {
        echo "\033[1;31m[er]\033[0m Bitrate không xác định: $bitrate kbps\n";
        exit(1);
    }

    getid3_lib::CopyTagsToComments($info);
    $info = $info['comments'] ?? [];

    // Xử lý nếu có ảnh
    $cover_md5 = '';
    $cover_name = '';
    $cover_data = '';

    if (!empty($info['picture']) and !empty($info['picture'][0])) {
        $cover = $info['picture'][0];
        $cover_mime = $cover['image_mime'];
        $cover_ext = explode('/', $cover_mime)[1];
        $cover_md5 = md5($cover['data']);
        $cover_data = $cover['data'];
        $cover_name = $cover_md5 . '.' . $cover_ext;
    }

    $id3 = [
        'title' => isset($info['title']) ? ($info['title'][0] ?? '') : '',
        'artist' => isset($info['artist']) ? ($info['artist'][0] ?? '') : '',
        'album' => isset($info['album']) ? ($info['album'][0] ?? '') : '',
        'genre' => isset($info['genre']) ? ($info['genre'][0] ?? '') : '',
        'comment' => isset($info['comment']) ? ($info['comment'][0] ?? '') : '',
        'composer' => isset($info['composer']) ? ($info['composer'][0] ?? '') : '',
        'track' => isset($info['track_number']) ? ($info['track_number'][0] ?? '') : '',
        'year' => isset($info['year']) ? ($info['year'][0] ?? '') : '',
        'bitrate' => $bitrate
    ];
    foreach ($id3 as $key => $value) {
        if (stripos($value, 'NhacCuaTui') !== false or stripos($value, 'Zing') !== false) {
            $id3[$key] = '';
        }
    }

    // Một số thông tin bắt buộc
    empty($id3['title']) && $id3['title'] = pathinfo($filename, PATHINFO_FILENAME);
    empty($id3['artist']) && $id3['artist'] = 'Unknown Artist';
    empty($id3['genre']) && $id3['genre'] = 'Unknown Genre';

    $id3['title'] = normalizeTitle($id3['title']);
    $id3['artist'] = normalizeTitle($id3['artist']);
    $id3['genre'] = normalizeTitle($id3['genre']);

    $keyfile = md5(md5_file($filename) . md5($filename));
    $new_name = strtolower($keyfile . '.' . preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($filename, PATHINFO_FILENAME)) . '.' . $ext);
    $new_path = $upload_dir . '/' . $new_name;

    // File có ở thư mục đích rồi thì bỏ qua
    if (file_exists($new_path)) {
        echo "\033[1;34m[in]\033[0m File đã tồn tại: $new_name\n";
        continue;
    }

    // Chép file sang thư mục đích
    if (!copy($filename, $new_path)) {
        echo "\033[1;31m[er]\033[0m Lỗi khi sao chép file: $filename\n";
        exit(1);
    }

    // Một số dạng phân cách thường thấy của ca sĩ
    $id3['artist'] = str_ireplace('Feat.', ',', $id3['artist']);
    $id3['artist'] = str_ireplace('ft.', ',', $id3['artist']);

    $artists = array_filter(array_unique(array_map('trim', explode(',', $id3['artist']))));
    $genres = array_filter(array_unique(array_map('trim', explode(',', $id3['genre']))));
    $resource_relative = $upload_base_dir . '/' . $new_name;

    // Xử lý ca sĩ
    $singer_ids = [];
    foreach ($artists as $artist_name) {
        if (mb_strlen($artist_name) < 3) {
            continue; // Bỏ qua tên ca sĩ quá ngắn
        }
        // Tìm thử ca sĩ có chưa
        $artist = callAPI('ArtistList', [
            'per_page' => 1,
            'is_singer' => 1,
            'artist_name' => $artist_name
        ])['data'];
        $artist = empty($artist) ? [] : array_values($artist)[0];

        if (empty($artist)) {
            echo "Tạo mới ca sĩ: $artist_name\n";
            $singer_ids[] = callAPI('ArtistCreate', ['artist_name' => $artist_name])['id'];
        } elseif (!in_array($artist['artist_id'], $singer_ids)) {
            $singer_ids[] = $artist['artist_id'];
            echo "Đã tìm thấy ca sĩ: " . $artist['artist_name'] . " (ID: " . $artist['artist_id'] . ")\n";
        }
    }
    if (empty($singer_ids)) {
        echo "\033[1;31m[er]\033[0m Không tìm thấy ca sĩ nào phù hợp.\n";
        unlink($new_path);
        exit(1);
    }

    // Xử lý thể loại
    $cat_ids = [];
    foreach ($genres as $genre_name) {
        if (mb_strlen($genre_name) < 3) {
            continue; // Bỏ qua thể loại quá ngắn
        }
        $key = md5(mb_strtolower($genre_name));
        if (isset($array_genres[$key])) {
            $cat_ids[] = $array_genres[$key]['cat_id'];
            echo "Đã tìm thấy thể loại: " . $array_genres[$key]['cat_name'] . " (ID: " . $array_genres[$key]['cat_id'] . ")\n";
        } else {
            echo "Tạo mới thể loại: $genre_name\n";
            $cat = callAPI('CategoryCreate', ['cat_name' => $genre_name]);
            $array_genres[$key] = [
                'cat_id' => $cat['id'],
                'cat_name' => $genre_name
            ];
            $cat_ids[] = $cat['id'];
        }
    }

    // Tìm bài hát trùng. Trùng tên và trùng ca sĩ
    $song = callAPI('SongList', [
        'per_page' => 1,
        'song_name' => $id3['title'],
        'singer_ids' => $singer_ids
    ])['data'];

    if (!empty($song)) {
        $song = array_values($song)[0];
        echo "\033[1;34m[in]\033[0m Đã tìm thấy bài hát trùng: " . $song['song_name'] . " (ID: " . $song['song_id'] . ")\n";
    } else {
        // Tạo mới bài hát
        echo "\033[1;32m[ok]\033[0m Tạo mới bài hát: " . $id3['title'] . "\n";

        // Xử lý chất lượng
        if (!isset($array_qualities[$id3['bitrate']])) {
            echo "\033[1;31m[er]\033[0m Chất lượng không xác định: " . $id3['bitrate'] . " kbps\n";
            unlink($new_path);
            exit(1);
        }
        $resource_path = [];
        $resource_path[$array_qualities[$id3['bitrate']]] = $resource_relative;

        // Xử lý ảnh bìa
        $resource_avatar = '';
        if (!empty($cover_name)) {
            $cover_path = $cover_dir . '/' . $cover_name;
            if (!file_exists($cover_path)) {
                $check = file_put_contents($cover_path, $cover_data, LOCK_EX);
                if (!$check) {
                    echo "\033[1;31m[er]\033[0m Lỗi khi lưu ảnh bìa: $cover_name\n";
                    unlink($new_path);
                    exit(1);
                }
            }
            $resource_avatar = $cover_base_dir . '/' . $cover_name;
        }

        callAPI('SongCreate', [
            'cat_ids' => $cat_ids,
            'singer_ids' => $singer_ids,
            'song_name' => $id3['title'],
            'resource_path' => $resource_path,
            'resource_avatar' => $resource_avatar,
        ]);
    }
}

echo "Xong!\n";
