<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\Resources;
use NukeViet\Music\Utils;

$page_title = $nv_Lang->getModule('mainpage_title');

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

// Lấy thông tin thống kê, cache lại để truy vấn nhanh
$cacheFile = NV_LANG_DATA . '_admin_stat_basic_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $array_stat_basic = unserialize($cache);
} else {
    $array_stat_basic = [];
    $array_stat_basic['num_songs'] = Utils::getFormatNumberView($db->query("SELECT COUNT(song_id) FROM " . Resources::getTablePrefix() . "_songs")->fetchColumn());
    $array_stat_basic['num_videos'] = Utils::getFormatNumberView($db->query("SELECT COUNT(video_id) FROM " . Resources::getTablePrefix() . "_videos")->fetchColumn());
    $array_stat_basic['num_albums'] = Utils::getFormatNumberView($db->query("SELECT COUNT(album_id) FROM " . Resources::getTablePrefix() . "_albums")->fetchColumn());
    $array_stat_basic['num_artists'] = Utils::getFormatNumberView($db->query("SELECT COUNT(artist_id) FROM " . Resources::getTablePrefix() . "_artists")->fetchColumn());

    $nv_Cache->setItem($module_name, $cacheFile, serialize($array_stat_basic), $cacheTTL);
}

$xtpl->assign('STAT_BASIC', $array_stat_basic);
$xtpl->assign('LINK_SONGS', NV_ADMIN_MOD_FULLLINK_AMP . 'song-list');
$xtpl->assign('LINK_VIDEOS', NV_ADMIN_MOD_FULLLINK_AMP . 'video-list');
$xtpl->assign('LINK_ALBUMS', NV_ADMIN_MOD_FULLLINK_AMP . 'album-list');
$xtpl->assign('LINK_ARTISTS', NV_ADMIN_MOD_FULLLINK_AMP . 'artist-list');

// Thống kê lượt nghe/xem tổng quan
$array_overview = [];
$sql = "SELECT stat_obj, stat_count FROM " . Resources::getTablePrefix() . "_statistics WHERE stat_type='all'";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_overview[$row['stat_obj']] = $row['stat_count'];
}

$array_overview['total'] = $array_overview['song'] + $array_overview['video'] + $array_overview['album'];
$array_overview['song_display'] = Utils::getFormatNumberView($array_overview['song']);
$array_overview['video_display'] = Utils::getFormatNumberView($array_overview['video']);
$array_overview['album_display'] = Utils::getFormatNumberView($array_overview['album']);
$array_overview['total_display'] = Utils::getFormatNumberView($array_overview['total']);

$xtpl->assign('OVERVIEW', $array_overview);

// Theo năm
$current_year = date('Y', NV_CURRENTTIME);
$from_year = $current_year - 4;
$array_byyear = [];
$sql = "SELECT stat_obj, stat_val, stat_count FROM " . Resources::getTablePrefix() . "_statistics WHERE stat_type='year' AND stat_val>=" . $from_year . " AND stat_val<=" . $current_year;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    if (!isset($array_byyear[$row['stat_obj']])) {
        $array_byyear[$row['stat_obj']] = [];
    }
    $array_byyear[$row['stat_obj']][$row['stat_val']] = [
        'value' => $row['stat_count'],
        'text' => Utils::getFormatNumberView($row['stat_count'])
    ];
}

$data_year_value = $data_year_song = $data_year_album = $data_year_video = [];
for ($i = $from_year; $i <= $current_year; $i++) {
    $data_year_value[] = $i;
    $data_year_song[] = isset($array_byyear['song'][$i]) ? $array_byyear['song'][$i]['value'] : 0;
    $data_year_video[] = isset($array_byyear['video'][$i]) ? $array_byyear['video'][$i]['value'] : 0;
    $data_year_album[] = isset($array_byyear['album'][$i]) ? $array_byyear['album'][$i]['value'] : 0;
}

$xtpl->assign('YEAR_VALUE', "'" . implode("', '", $data_year_value) . "'");
$xtpl->assign('YEAR_SONG', "'" . implode("', '", $data_year_song) . "'");
$xtpl->assign('YEAR_VIDEO', "'" . implode("', '", $data_year_video) . "'");
$xtpl->assign('YEAR_ALBUM', "'" . implode("', '", $data_year_album) . "'");

// Theo ngày
$current_month = intval(date('Ym', NV_CURRENTTIME));
$current_month_day = date('t', NV_CURRENTTIME);
$from_day = intval($current_month . '01');
$to_day = intval($current_month . $current_month_day);
$array_byday = [];
$sql = "SELECT stat_obj, stat_val, stat_count FROM " . Resources::getTablePrefix() . "_statistics WHERE stat_type='day' AND stat_val>=" . $from_day . " AND stat_val<=" . $to_day;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    if (!isset($array_byday[$row['stat_obj']])) {
        $array_byday[$row['stat_obj']] = [];
    }
    $row['stat_val'] = intval(substr($row['stat_val'], -2));
    $array_byday[$row['stat_obj']][$row['stat_val']] = [
        'value' => $row['stat_count'],
        'text' => Utils::getFormatNumberView($row['stat_count'])
    ];
}

$data_day_value = $data_day_song = $data_day_album = $data_day_video = [];
$current_day = date('j', NV_CURRENTTIME);
for ($i = 1; $i <= $current_month_day; $i++) {
    $data_day_value[] = str_pad($i, 2, '0', STR_PAD_LEFT);

    $value = isset($array_byday['song'][$i]) ? $array_byday['song'][$i]['value'] : 0;
    $data_day_song[] = $i > $current_day ? 'null' : $value;

    $value = isset($array_byday['video'][$i]) ? $array_byday['video'][$i]['value'] : 0;
    $data_day_video[] = $i > $current_day ? 'null' : $value;

    $value = isset($array_byday['album'][$i]) ? $array_byday['album'][$i]['value'] : 0;
    $data_day_album[] = $i > $current_day ? 'null' : $value;
}

$xtpl->assign('DAY_VALUE', "'" . implode("', '", $data_day_value) . "'");
$xtpl->assign('DAY_SONG', "'" . implode("', '", $data_day_song) . "'");
$xtpl->assign('DAY_VIDEO', "'" . implode("', '", $data_day_video) . "'");
$xtpl->assign('DAY_ALBUM', "'" . implode("', '", $data_day_album) . "'");
$xtpl->assign('DAY_STAT_MONTH', nv_date('m/Y', NV_CURRENTTIME));

// Theo tháng
$from_month = intval($current_year . '01');
$to_month = intval($current_year . '12');
$array_bymonth = [];
$sql = "SELECT stat_obj, stat_val, stat_count FROM " . Resources::getTablePrefix() . "_statistics WHERE stat_type='month' AND stat_val>=" . $from_month . " AND stat_val<=" . $to_month;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    if (!isset($array_bymonth[$row['stat_obj']])) {
        $array_bymonth[$row['stat_obj']] = [];
    }
    $row['stat_val'] = intval(substr($row['stat_val'], -2));
    $array_bymonth[$row['stat_obj']][$row['stat_val']] = [
        'value' => $row['stat_count'],
        'text' => Utils::getFormatNumberView($row['stat_count'])
    ];
}

$data_month_value = $data_month_song = $data_month_album = $data_month_video = [];
$current_month = date('n', NV_CURRENTTIME);
for ($i = 1; $i <= 12; $i++) {
    $data_month_value[] = str_pad($i, 2, '0', STR_PAD_LEFT);

    $value = isset($array_bymonth['song'][$i]) ? $array_bymonth['song'][$i]['value'] : 0;
    $data_month_song[] = $i > $current_month ? 'null' : $value;

    $value = isset($array_bymonth['video'][$i]) ? $array_bymonth['video'][$i]['value'] : 0;
    $data_month_video[] = $i > $current_month ? 'null' : $value;

    $value = isset($array_bymonth['album'][$i]) ? $array_bymonth['album'][$i]['value'] : 0;
    $data_month_album[] = $i > $current_month ? 'null' : $value;
}

$xtpl->assign('MONTH_VALUE', "'" . implode("', '", $data_month_value) . "'");
$xtpl->assign('MONTH_SONG', "'" . implode("', '", $data_month_song) . "'");
$xtpl->assign('MONTH_VIDEO', "'" . implode("', '", $data_month_video) . "'");
$xtpl->assign('MONTH_ALBUM', "'" . implode("', '", $data_month_album) . "'");
$xtpl->assign('MONTH_STAT_YEAR', $current_year);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
