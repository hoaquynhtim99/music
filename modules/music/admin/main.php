<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\Utils;

$page_title = $lang_module['mainpage_title'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Lấy thông tin thống kê, cache lại để truy vấn nhanh
$cacheFile = NV_LANG_DATA . '_admin_stat_basic_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $array_stat_basic = unserialize($cache);
} else {
    $array_stat_basic = [];
    $array_stat_basic['num_songs'] = Utils::getFormatNumberView($db->query("SELECT COUNT(song_id) FROM " . NV_MOD_TABLE . "_songs")->fetchColumn());
    $array_stat_basic['num_videos'] = Utils::getFormatNumberView($db->query("SELECT COUNT(video_id) FROM " . NV_MOD_TABLE . "_videos")->fetchColumn());
    $array_stat_basic['num_albums'] = Utils::getFormatNumberView($db->query("SELECT COUNT(album_id) FROM " . NV_MOD_TABLE . "_albums")->fetchColumn());
    $array_stat_basic['num_artists'] = Utils::getFormatNumberView($db->query("SELECT COUNT(artist_id) FROM " . NV_MOD_TABLE . "_artists")->fetchColumn());

    $nv_Cache->setItem($module_name, $cacheFile, serialize($array_stat_basic), $cacheTTL);
}

$xtpl->assign('STAT_BASIC', $array_stat_basic);
$xtpl->assign('LINK_SONGS', NV_ADMIN_MOD_FULLLINK_AMP . 'song-list');
$xtpl->assign('LINK_VIDEOS', NV_ADMIN_MOD_FULLLINK_AMP . 'video-list');
$xtpl->assign('LINK_ALBUMS', NV_ADMIN_MOD_FULLLINK_AMP . 'album-list');
$xtpl->assign('LINK_ARTISTS', NV_ADMIN_MOD_FULLLINK_AMP . 'artist-list');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
