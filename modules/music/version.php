<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'NukeViet Music',
    'modfuncs' => 'main,detail-song,detail-album,detail-video,list-albums,list-singers,list-videos,search,view-singer',
    'change_alias' => 'detail-song,detail-album,detail-video,list-albums,list-singers,list-videos,view-singer',
    'submenu' => 'list-albums,list-singers,list-videos',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.1.00',
    'date' => 'Sun, 26 Feb 2017 14:04:32 GMT',
    'author' => 'PHAN TAN DUNG (phantandung92@gmail.com)',
    'note' => '',
    'uploads_dir' => array(
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
    ),
    'files_dir' => array(
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
    )
);
