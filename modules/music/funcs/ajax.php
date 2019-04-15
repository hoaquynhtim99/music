<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;

$tokend = $nv_Request->get_title('tokend', 'post,get', '');

// Load lời bài hát
if ($nv_Request->isset_request('getSongLyric', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    $sql = "SELECT " . NV_LANG_DATA . "_song_introtext lyric FROM " . NV_MOD_TABLE . "_songs WHERE song_code=:song_code AND status=1";
    $sth = $db->prepare($sql);
    $sth->bindParam(':song_code', $song_code, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount()) {
        $lyric = $sth->fetchColumn();
        if (empty($lyric)) {
            nv_htmlOutput(Config::getMsgNolyric());
        }
        nv_htmlOutput($lyric);
    }

    nv_htmlOutput('');
}

// Cập nhật số lượt chia sẻ bài hát
if ($nv_Request->isset_request('updateSongShares', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    // Xác định bài hát
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($song_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_htmlOutput('Song not exists!!!');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    $cookie_stat = $nv_Request->get_string($module_data . '_share_song', 'cookie', '');
    $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
    if (!is_array($cookie_stat)) {
        $cookie_stat = [];
    }
    $timeout = mktime(0, 0, 0, date("n", NV_CURRENTTIME), date("j", NV_CURRENTTIME), date("Y", NV_CURRENTTIME)); // Một ngày share một lần, qua ngày reset

    if (!isset($cookie_stat[$row['song_code']]) or $cookie_stat[$row['song_code']] < $timeout) {
        // Cập nhật số lượt chia sẻ
        $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_shares=stat_shares+1 WHERE song_id=" . $row['song_id'];
        $db->query($sql);

        // Thêm vào cookie
        $cookie_stat[$row['song_code']] = NV_CURRENTTIME;

        // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
        foreach ($cookie_stat as $_key => $_val) {
            if ($_val < $timeout) {
                unset($cookie_stat[$_key]);
            }
        }

        // Ghi lại cookie
        $nv_Request->set_Cookie($module_data . '_share_song', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
    }

    nv_htmlOutput("OK");
}

// Chức năng tải bài hát
if ($nv_Request->isset_request('getDownloadSongHtml', 'post,get') or $nv_Request->isset_request('downloadSong', 'get')) {
    $song_code = $nv_Request->get_title('song_code', 'post,get', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    // Xác định bài hát
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($song_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_htmlOutput('Song not exists!!!');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    // Lấy các file của bài hát
    $array_resource = [];
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_songs_data WHERE song_id=" . $row['song_id'];
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $_row['link_download'] = NV_MOD_FULLLINK_AMP . $op . '&amp;downloadSong=1&amp;song_code=' . $row['song_code'] . '&amp;tokend=' . $tokend . '&amp;quality=' . $_row['quality_id'] . '&amp;download=1';
        $array_resource[$_row['quality_id']] = $_row;
    }

    // Thực hiện tải xuống
    if ($nv_Request->isset_request('download', 'get'))  {
        $quality_id = $nv_Request->get_int('quality', 'get', 0);
        if (isset($array_resource[$quality_id])) {
            // Thống kê số lượt tải
            $cookie_stat = $nv_Request->get_string($module_data . '_stat_song_downloads', 'cookie', '');
            $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
            if (!is_array($cookie_stat)) {
                $cookie_stat = [];
            }
            $timeout = NV_CURRENTTIME - (60 * 60); // Đếm tăng mỗi 1 tiếng

            if (!isset($cookie_stat[$row['song_code']]) or $cookie_stat[$row['song_code']] < $timeout) {
                // Cập nhật thống kê bài hát
                $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_downloads=stat_downloads+1 WHERE song_id=" . $row['song_id'];
                $db->query($sql);

                // Thêm vào cookie
                $cookie_stat[$row['song_code']] = NV_CURRENTTIME;

                // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
                foreach ($cookie_stat as $_key => $_val) {
                    if ($_val < $timeout) {
                        unset($cookie_stat[$_key]);
                    }
                }

                // Ghi lại cookie
                $nv_Request->set_Cookie($module_data . '_stat_song_downloads', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
            }

            $resource = $array_resource[$quality_id];

            // Chuyển hướng nếu liên kết ngoài
            if ($resource['resource_server_id'] < 0) {
                nv_redirect_location($resource['resource_path']);
            }

            // Xác định các ca sĩ của bài hát.
            $array_singer_ids = $array_singers = [];
            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            // Xác định ca sĩ
            $array_singers = nv_get_artists($array_singer_ids);

            if (!empty($row['singer_ids'])) {
                foreach ($row['singer_ids'] as $singer_id) {
                    if (isset($array_singers[$singer_id])) {
                        $row['singers'][$singer_id] = $array_singers[$singer_id];
                    }
                }
            }

            // Từ ca sĩ lấy ra tên file đẹp
            $song_full_name = $row['song_name'];
            $song_full_singer = [];

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                foreach ($row['singers'] as $singer) {
                    $song_full_singer[] = $singer['artist_name'];
                }
            } elseif (!empty($row['singers'])) {
                foreach ($row['singers'] as $singer) {
                    $song_full_singer[] = $singer['artist_name'];
                }
            } else {
                $song_full_singer[] = Config::getUnknowSinger();
            }

            $full_file_name = nv_substr(change_alias($song_full_name . '-' . implode('-', $song_full_singer)), 0, 200) . '.' . strtolower(nv_getextension($resource['resource_path']));
            $file_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $resource['resource_path'];;
            $file_folder = dirname($file_path);

            $download = new NukeViet\Files\Download($file_path, $file_folder, $full_file_name, true, 0);
            $download->download_file();
            die();
        }

        nv_redirect_location(NV_MOD_LINK);
    }

    $contents = nv_theme_popover_download_song($row, $array_resource);

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Chức năng tải video
if ($nv_Request->isset_request('getDownloadVideoHtml', 'post,get') or $nv_Request->isset_request('downloadVideo', 'get')) {
    $video_code = $nv_Request->get_title('video_code', 'post,get', '');

    if ($tokend !== md5($video_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    // Xác định video
    $array_select_fields = nv_get_video_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE status=1 AND video_code=" . $db->quote($video_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_htmlOutput('Video not exists!!!');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    // Lấy các file của video
    $array_resource = [];
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_videos_data WHERE video_id=" . $row['video_id'];
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $_row['link_download'] = NV_MOD_FULLLINK_AMP . $op . '&amp;downloadVideo=1&amp;video_code=' . $row['video_code'] . '&amp;tokend=' . $tokend . '&amp;quality=' . $_row['quality_id'] . '&amp;download=1';
        $array_resource[$_row['quality_id']] = $_row;
    }

    // Thực hiện tải xuống
    if ($nv_Request->isset_request('download', 'get'))  {
        $quality_id = $nv_Request->get_int('quality', 'get', 0);
        if (isset($array_resource[$quality_id])) {
            // Thống kê số lượt tải
            $cookie_stat = $nv_Request->get_string($module_data . '_stat_video_downloads', 'cookie', '');
            $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
            if (!is_array($cookie_stat)) {
                $cookie_stat = [];
            }
            $timeout = NV_CURRENTTIME - (60 * 60); // Đếm tăng mỗi 1 tiếng

            if (!isset($cookie_stat[$row['video_code']]) or $cookie_stat[$row['video_code']] < $timeout) {
                // Cập nhật thống kê video
                $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET stat_downloads=stat_downloads+1 WHERE video_id=" . $row['video_id'];
                $db->query($sql);

                // Thêm vào cookie
                $cookie_stat[$row['video_code']] = NV_CURRENTTIME;

                // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
                foreach ($cookie_stat as $_key => $_val) {
                    if ($_val < $timeout) {
                        unset($cookie_stat[$_key]);
                    }
                }

                // Ghi lại cookie
                $nv_Request->set_Cookie($module_data . '_stat_video_downloads', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
            }

            $resource = $array_resource[$quality_id];

            // Chuyển hướng nếu liên kết ngoài
            if ($resource['resource_server_id'] < 0) {
                nv_redirect_location($resource['resource_path']);
            }

            // Xác định các ca sĩ của video.
            $array_singer_ids = $array_singers = [];
            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            // Xác định ca sĩ
            $array_singers = nv_get_artists($array_singer_ids);

            if (!empty($row['singer_ids'])) {
                foreach ($row['singer_ids'] as $singer_id) {
                    if (isset($array_singers[$singer_id])) {
                        $row['singers'][$singer_id] = $array_singers[$singer_id];
                    }
                }
            }

            // Từ ca sĩ lấy ra tên file đẹp
            $video_full_name = $row['video_name'];
            $video_full_singer = [];

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                foreach ($row['singers'] as $singer) {
                    $video_full_singer[] = $singer['artist_name'];
                }
            } elseif (!empty($row['singers'])) {
                foreach ($row['singers'] as $singer) {
                    $video_full_singer[] = $singer['artist_name'];
                }
            } else {
                $video_full_singer[] = Config::getUnknowSinger();
            }

            $full_file_name = nv_substr(change_alias($video_full_name . '-' . implode('-', $video_full_singer)), 0, 200) . '.' . strtolower(nv_getextension($resource['resource_path']));
            $file_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $resource['resource_path'];;
            $file_folder = dirname($file_path);

            $download = new NukeViet\Files\Download($file_path, $file_folder, $full_file_name, true, 0);
            $download->download_file();
            die();
        }

        nv_redirect_location(NV_MOD_LINK);
    }

    $contents = nv_theme_popover_download_video($row, $array_resource);

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_redirect_location(NV_MOD_LINK);
