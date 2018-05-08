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

$allow_func = array(
    'main',
    'config',
    'view',
    'nation-manager',
    'quality-song-manager',
    'quality-video-manager',
    'artist-list',
    'cat-list',
    'song-list',
    'album-list',
    'video-list',
);

$submenu['song-list'] = $lang_module['song_manager'];
$submenu['album-list'] = $lang_module['album_manager'];
$submenu['video-list'] = $lang_module['video_manager'];
$submenu['artist-list'] = $lang_module['artist_manager'];
$submenu['cat-list'] = $lang_module['cat_manager'];
$submenu['config'] = $lang_module['config'];
