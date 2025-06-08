<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_CONSOLE_DIR')) {
    echo "You must define NV_CONSOLE_DIR before using this script.\n";
    exit(1);
}

require NV_CONSOLE_DIR . '/config.php';
require NV_CONSOLE_DIR . '/vendor/autoload.php';

$audio_extensions = [
    'mp3',   // MPEG Layer 3
    'aac',   // Advanced Audio Coding
    'm4a',   // MPEG-4 Audio (thường dùng cho AAC hoặc ALAC)
    'ogg',   // Ogg Vorbis
    'oga',   // Ogg Audio
    'flac',  // Free Lossless Audio Codec
    'alac',  // Apple Lossless Audio Codec (ít dùng đuôi riêng, thường là .m4a)
    'ape',   // Monkey's Audio
    'wav',   // Waveform Audio File Format
    'aiff',  // Audio Interchange File Format
    'wma',   // Windows Media Audio
    'opus',  // Opus codec, tối ưu hóa cho streaming
    'amr',   // Adaptive Multi-Rate, thường dùng trong ghi âm điện thoại
];
$html5_audio_extensions = [
    'mp3',   // Hỗ trợ rộng rãi trên tất cả trình duyệt
    'm4a',   // Dùng cho AAC hoặc ALAC, hỗ trợ tốt (Safari, Chrome, Edge)
    'aac',   // Thường nằm trong container m4a
    'ogg',   // Ogg Vorbis, được Firefox và Chrome hỗ trợ
    'oga',   // Tương tự .ogg, rõ ràng là file âm thanh
    'wav',   // Hỗ trợ đầy đủ, nhưng dung lượng lớn
];

/**
 * Liệt kê tất cả file trong thư mục và các thư mục con
 *
 * @param mixed $dir
 * @return array
 */
function listFilesRecursive($dir)
{
    $result = [];

    if (!is_dir($dir)) {
        return $result;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $result[] = $file->getPathname();
        }
    }

    return $result;
}

/**
 * Gọi API của NukeViet Music
 *
 * @param mixed $action
 * @param mixed $data
 */
function callAPI($action, $data = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, MUSIC_API_URL);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $safe_mode = (ini_get('safe_mode') == '1' || strtolower(ini_get('safe_mode')) == 'on') ? 1 : 0;
    $open_basedir = ini_get('open_basedir') ? true : false;
    if (!$safe_mode and !$open_basedir) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    }

    $timestamp = time();
    $request = [
        'apikey' => MUSIC_API_KEY,
        'timestamp' => $timestamp,
        'hashsecret' => password_hash(MUSIC_API_SECRET . '_' . $timestamp, PASSWORD_DEFAULT),
        'language' => 'vi',
        'action' => $action,
        'module' => 'music'
    ];
    $request = array_merge($request, $data);

    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'NukeViet Remote API Lib');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $res = curl_exec($ch);
    $error = curl_errno($ch);
    if ($error != 0) {
        echo "CURL Error: " . $error . "\n";
        curl_close($ch);
        exit(1);
    }

    curl_close($ch);
    $responsive = json_decode($res, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON Error: " . json_last_error_msg() . "\n";
        echo "Res: " . $res . "\n";
        exit(1);
    }
    if (!isset($responsive['status']) or $responsive['status'] != 'success') {
        print_r(json_encode($responsive, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        exit(1);
    }

    return $responsive;
}

/**
 * Chuẩn hoá tên bài hát, thể loại, nghệ sĩ
 *
 * @param mixed $title
 * @return string
 */
function normalizeTitle($title)
{
    // Thay thế - và _ thành khoảng trắng
    $title = str_replace(['-', '_'], ' ', $title);

    // Nén nhiều khoảng trắng liên tiếp thành 1 khoảng trắng
    $title = preg_replace('/\s+/', ' ', $title);

    // Cắt khoảng trắng đầu/cuối và chuyển thành chữ thường (UTF-8)
    $title = mb_strtolower(trim($title), 'UTF-8');

    // Viết hoa chữ cái đầu mỗi từ (UTF-8)
    $title = implode(' ', array_map(function($word) {
        $firstChar = mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8');
        $rest = mb_substr($word, 1, null, 'UTF-8');
        return $firstChar . $rest;
    }, explode(' ', $title)));

    return $title;
}
