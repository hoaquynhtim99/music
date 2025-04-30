<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'Music',
    'modfuncs' => 'main,detail-song,detail-album,detail-playlist,detail-video,list-albums,list-singers,list-videos,search,view-singer,mymusic,manager-playlist',
    'change_alias' => 'detail-song,detail-album,detail-playlist,detail-video,list-albums,list-singers,list-videos,view-singer,mymusic,manager-playlist',
    'submenu' => '',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '5.0.00',
    'date' => 'Wednesday, April 30, 2025 10:24:20 AM GMT+07:00',
    'author' => 'PHAN TAN DUNG <writeblabla@gmail.com>',
    'note' => '',
    'uploads_dir' => [
        $module_name,
        $module_name . '/others',
        $module_name . '/albums',
        $module_name . '/albums_cover',
        $module_name . '/artists',
        $module_name . '/artists_cover',
        $module_name . '/songs',
        $module_name . '/songs_cover',
        $module_name . '/videos',
        $module_name . '/videos_cover',
        $module_name . '/lyric',
        $module_name . '/dataup_default123'
    ],
    'files_dir' => [
        $module_name,
        $module_name . '/others',
        $module_name . '/albums',
        $module_name . '/albums_cover',
        $module_name . '/artists',
        $module_name . '/artists_cover',
        $module_name . '/songs',
        $module_name . '/songs_cover',
        $module_name . '/videos',
        $module_name . '/videos_cover'
    ],
    'icon' => 'fa-solid fa-headphones-simple'
];
