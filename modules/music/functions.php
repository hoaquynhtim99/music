<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_MUSIC', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

use NukeViet\Music\Config;

$array_mod_title = array();
$is_embed_mode = ($nv_Request->get_int('embed', 'get', 0) == 1 ? true : false);

// Điều khiển các OP
if ($op == 'main' and isset($array_op[0])) {
    unset($m);
    $codePrefix = Config::getCodePrefix();
    if (isset($array_op[1]) or !preg_match('/^([a-zA-Z0-9\-]+)\-(' . $codePrefix->getAlbum() . '|' . $codePrefix->getVideo() . '|' . $codePrefix->getSong() . ')([a-zA-Z0-9\-]+)$/', $array_op[0], $m)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $ms_detail_op_alias = $m[1];
    $ms_detail_prefix = $m[2];
    $ms_detail_code = $m[3];
    $ms_detail_data = array();

    if ($ms_detail_prefix == $codePrefix->getSong()) {
        $array_select_fields = nv_get_song_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=:song_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':song_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-song';
        define('NV_IS_DETAIL_SONG', true);
    } elseif ($ms_detail_prefix == $codePrefix->getVideo()) {
        $array_select_fields = nv_get_video_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE status=1 AND video_code=:video_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':video_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-video';
        define('NV_IS_DETAIL_VIDEO', true);
    } elseif ($ms_detail_prefix == $codePrefix->getAlbum()) {
        $array_select_fields = nv_get_album_select_fields(true);

        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_albums WHERE status=1 AND album_code=:album_code";
        $sth = $db->prepare($sql);
        $sth->bindParam(':album_code', $ms_detail_code, PDO::PARAM_STR);
        $sth->execute();

        if ($sth->rowCount() != 1) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }

        $ms_detail_data = $sth->fetch();
        $op = 'detail-album';
        define('NV_IS_DETAIL_ALBUM', true);
    } else {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    unset($codePrefix);
}

/**
 * nv_get_fb_share_image()
 *
 * @param mixed $data
 * @return
 */
function nv_get_fb_share_image($data = array())
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
