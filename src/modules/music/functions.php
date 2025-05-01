<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_MUSIC', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;

$array_mod_title = [];
$is_embed_mode = ($nv_Request->get_int('embed', 'get', 0) == 1 ? true : false);

// Điều khiển các OP
if ($op == 'main' and isset($array_op[0])) {
    unset($m);
    $codePrefix = Config::getCodePrefix();
    if (isset($array_op[1]) or !preg_match('/^([a-zA-Z0-9\-]+)\-(' . $codePrefix->getAlbum() . '|' . $codePrefix->getVideo() . '|' . $codePrefix->getSong() . '|' . $codePrefix->getPlaylist() . ')([a-zA-Z0-9\-]+)$/', $array_op[0], $m)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
    }

    $ms_detail_op_alias = $m[1];
    $ms_detail_prefix = $m[2];
    $ms_detail_code = $m[3];
    $ms_detail_data = [];

    if ($ms_detail_prefix == $codePrefix->getSong()) {
        $array_select_fields = nv_get_song_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_songs WHERE status=1 AND song_code=:song_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':song_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-song';
        define('NV_IS_DETAIL_SONG', true);
    } elseif ($ms_detail_prefix == $codePrefix->getVideo()) {
        $array_select_fields = nv_get_video_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_videos WHERE status=1 AND video_code=:video_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':video_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-video';
        define('NV_IS_DETAIL_VIDEO', true);
    } elseif ($ms_detail_prefix == $codePrefix->getAlbum()) {
        $array_select_fields = nv_get_album_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_albums WHERE status=1 AND album_code=:album_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':album_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-album';
        define('NV_IS_DETAIL_ALBUM', true);
    } elseif ($ms_detail_prefix == $codePrefix->getPlaylist()) {
        $array_select_fields = nv_get_user_playlist_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_user_playlists WHERE playlist_code=:playlist_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':playlist_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-playlist';
        define('NV_IS_DETAIL_PLAYLIST', true);
    } else {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
    }

    unset($codePrefix);
}

/**
 * nv_get_fb_share_image()
 *
 * @param mixed $data
 * @return
 */
function nv_get_fb_share_image($data = [])
{
    global $meta_property, $module_upload;

    if (!empty($data['resource_avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['resource_avatar'])) {
        $image_info = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['resource_avatar']);
        if (isset($image_info[0]) and isset($image_info[1]) and isset($image_info['mime']) and $image_info[0] >= 600 or $image_info[1] >= 315) {
            $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['resource_avatar'];
            $meta_property['og:image:url'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['resource_avatar'];
            $meta_property['og:image:width'] = $image_info[0];
            $meta_property['og:image:height'] = $image_info[1];
            $meta_property['og:image:type'] = $image_info['mime'];
            return true;
        }
    }

    $fb_share_image = Config::getFbShareImage();

    if (!empty($fb_share_image) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $fb_share_image)) {
        $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $fb_share_image;
        $meta_property['og:image:url'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $fb_share_image;
        $meta_property['og:image:width'] = Config::getFbShareImageWidth();
        $meta_property['og:image:height'] = Config::getFbShareImageHeight();
        $meta_property['og:image:type'] = Config::getFbShareImageMime();
    }
}

/**
 * Cập nhật thống kê lượt nghe
 * @param string $stat_obj
 */
function msUpdateStatistics($stat_obj)
{
    global $global_config, $db;

    // Thống kê tổng quan
    if (NV_SITE_TIMEZONE_NAME == $global_config['statistics_timezone']) {
        $current_year = date('Y', NV_CURRENTTIME);
        $current_month = date('m', NV_CURRENTTIME);
        $current_day = date('d', NV_CURRENTTIME);
        $current_numdays = date('t', NV_CURRENTTIME);
    } else {
        date_default_timezone_set($global_config['statistics_timezone']);
        $current_year = date('Y', NV_CURRENTTIME);
        $current_month = date('m', NV_CURRENTTIME);
        $current_day = date('d', NV_CURRENTTIME);
        $current_numdays = date('t', NV_CURRENTTIME);
        date_default_timezone_set(NV_SITE_TIMEZONE_NAME);
    }

    $current_day_key = intval($current_year . $current_month . $current_day);
    $sql = "UPDATE " . Resources::getTablePrefix() . "_statistics SET time_update=" . NV_CURRENTTIME . ", stat_count=stat_count+1 WHERE
    stat_obj='" . $stat_obj . "' AND stat_type='day' AND stat_val=" . $current_day_key;
    if (!$db->exec($sql)) {
        // Insert toàn bộ dữ liệu ngày của tháng
        for ($i = 1; $i <= $current_numdays; $i++) {
            $day_key_i = intval($current_year . $current_month . str_pad($i, 2, '0', STR_PAD_LEFT));
            $sql = "INSERT IGNORE INTO " . Resources::getTablePrefix() . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES (
                '" . $stat_obj . "', 'day', " . $day_key_i . ", " . NV_CURRENTTIME . ", " . ($day_key_i == $current_day_key ? 1: 0) . "
            )";
            $db->query($sql);
        }

        // Insert tháng trong năm
        for ($i = 1; $i <= 12; $i++) {
            $sql = "INSERT IGNORE INTO " . Resources::getTablePrefix() . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES (
                '" . $stat_obj . "', 'month', " . intval($current_year . str_pad($i, 2, '0', STR_PAD_LEFT)) . ", " . NV_CURRENTTIME . ", 0
            )";
            $db->query($sql);
        }

        // Insert năm
        $sql = "INSERT IGNORE INTO " . Resources::getTablePrefix() . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES (
            '" . $stat_obj . "', 'year', " . $current_year . ", " . NV_CURRENTTIME . ", 0
        )";
        $db->query($sql);
    }

    // Cập nhật các thống kê khác: Tất cả, năm, tháng. Không có ngày vì ngày cập nhật ở trên kia
    $sql = "UPDATE " . Resources::getTablePrefix() . "_statistics SET time_update=" . NV_CURRENTTIME . ", stat_count=stat_count+1 WHERE
    stat_obj='" . $stat_obj . "' AND (
        stat_type='all' OR (stat_type='year' AND stat_val='" . $current_year . "') OR (stat_type='month' AND stat_val='" . $current_year . $current_month . "')
    )";
    $db->query($sql);
}
