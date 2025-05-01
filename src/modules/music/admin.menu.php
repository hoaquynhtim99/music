<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
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
    'cat-chart-manager',
    'song-list',
    'song-content',
    'song-caption',
    'album-list',
    'video-list',
    'video-content',
    'album-content',

    'ajax-search-artists',
    'ajax-search-songs',
    'ajax-search-videos'
];

$submenu['song-list'] = [
    'title' => $nv_Lang->getModule('song_manager'),
    'submenu' => [
        'song-content' => $nv_Lang->getModule('song_add')
    ]
];
$submenu['album-list'] = [
    'title' => $nv_Lang->getModule('album_manager'),
    'submenu' => [
        'album-content' => $nv_Lang->getModule('album_add')
    ]
];
$submenu['video-list'] = [
    'title' => $nv_Lang->getModule('video_manager'),
    'submenu' => [
        'video-content' => $nv_Lang->getModule('video_add')
    ]
];
$submenu['artist-list'] = [
    'title' => $nv_Lang->getModule('artist_manager'),
    'submenu' => [
        'artist-content' => $nv_Lang->getModule('artist_add')
    ]
];
$submenu['cat-manager'] = $nv_Lang->getModule('cat_manager');
$submenu['nation-manager'] = $nv_Lang->getModule('nation_manager');
$submenu['quality-song-manager'] = $nv_Lang->getModule('qso_manager');
$submenu['quality-video-manager'] = $nv_Lang->getModule('qvd_manager');
$submenu['cat-chart-manager'] = $nv_Lang->getModule('chart_manager');
$submenu['config'] = $nv_Lang->getModule('config');
