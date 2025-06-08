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

$source_file = NV_CONSOLE_DIR . '/source.mp3';
$info = $getID3->analyze($source_file);

$cover = $info['comments']['picture'][0];
$cover_mime = $cover['image_mime'];
$cover_ext = explode('/', $cover_mime)[1];
file_put_contents(NV_CONSOLE_DIR . "/source.cover.$cover_ext", $cover['data']);

getid3_lib::CopyTagsToComments($info);

unset($info['comments']['picture']);

file_put_contents(NV_CONSOLE_DIR . '/source.json', json_encode($info['comments'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
$json_error = json_last_error();
if ($json_error !== JSON_ERROR_NONE) {
    echo "  - Lỗi khi chuyển đổi sang JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

$destination_file = NV_CONSOLE_DIR . '/destination.mp3';
if (file_exists($destination_file)) {
    unlink($destination_file);
}
$title = "Thử nghiệm tiêu đề";
$artist = "Thử nghiệm nghệ sĩ";
$album = "Thử nghiệm album";
$genre = "Thử nghiệm thể loại";
$comment = "Thử nghiệm comment";
$copyright = "Thử nghiệm bản quyền";
$lyrics = "Thử nghiệm lời bài hát";

/*
$cmd = sprintf(
    'ffmpeg -i %s -metadata title=%s -metadata artist=%s -metadata album=%s -metadata genre=%s -metadata comment=%s -metadata copyright=%s -metadata lyrics=%s -codec copy %s 2>&1',
    escapeshellarg($source_file),
    escapeshellarg($title),
    escapeshellarg($artist),
    escapeshellarg($album),
    escapeshellarg($genre),
    escapeshellarg($comment),
    escapeshellarg($copyright),
    escapeshellarg($lyrics),
    escapeshellarg($destination_file)
);
*/
$cmd = sprintf(
    'ffmpeg -i %s -i %s -map 0:a -map 1 -metadata:s:v title="Album cover" -metadata:s:v comment="Cover (front)" ' .
    '-metadata title=%s -metadata artist=%s -metadata album=%s -metadata genre=%s -metadata comment=%s -metadata copyright=%s ' .
    '-metadata lyrics=%s -id3v2_version 3 -write_id3v1 1 -codec:a copy %s 2>&1',
    escapeshellarg($source_file),
    escapeshellarg(NV_CONSOLE_DIR . "/source.cover.$cover_ext"),
    escapeshellarg($title),
    escapeshellarg($artist),
    escapeshellarg($album),
    escapeshellarg($genre),
    escapeshellarg($comment),
    escapeshellarg($copyright),
    escapeshellarg($lyrics),
    escapeshellarg($destination_file)
);
echo "$cmd\n";
exec($cmd, $output, $return_var);

if ($return_var === 0) {
    echo "Ghi tag thành công!";
} else {
    echo "Lỗi khi ghi tag: " . implode("\n", $output);
    exit(1);
}

$info = $getID3->analyze($destination_file);

$cover = $info['comments']['picture'][0];
$cover_mime = $cover['image_mime'];
$cover_ext = explode('/', $cover_mime)[1];
file_put_contents(NV_CONSOLE_DIR . "/destination.cover.$cover_ext", $cover['data']);

getid3_lib::CopyTagsToComments($info);
unset($info['comments']['picture']);

file_put_contents(NV_CONSOLE_DIR . '/destination.json', json_encode($info['comments'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
$json_error = json_last_error();
if ($json_error !== JSON_ERROR_NONE) {
    echo "  - Lỗi khi chuyển đổi sang JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "Xong!\n";
