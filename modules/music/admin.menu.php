<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$allow_func = [
    'main',
    'config',
    'view',
    'nation-manager',
    'quality-song-manager',
    'quality-video-manager',
    'artist-list',
    'artist-content',
    'cat-manager',
    'song-list',
    'song-content',
    'album-list',
    'video-list',
];

$submenu['song-list'] = [
    'title' => $lang_module['song_manager'],
    'submenu' => [
        'song-content' => $lang_module['song_add']
    ]
];
$submenu['album-list'] = $lang_module['album_manager'];
$submenu['video-list'] = $lang_module['video_manager'];
$submenu['artist-list'] = [
    'title' => $lang_module['artist_manager'],
    'submenu' => [
        'artist-content' => $lang_module['artist_add']
    ]
];
$submenu['cat-manager'] = $lang_module['cat_manager'];
$submenu['nation-manager'] = $lang_module['nation_manager'];
$submenu['quality-song-manager'] = $lang_module['qso_manager'];
$submenu['quality-video-manager'] = $lang_module['qvd_manager'];
$submenu['config'] = $lang_module['config'];
