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

$array_mod_title = array();

// Điều khiển các OP
if ($op == 'main' and isset($array_op[0])) {
    unset($m);
    if (isset($array_op[1]) or !preg_match('/^([a-zA-Z0-9\-]+)\-(' . $global_array_config['code_prefix']['album'] . '|' . $global_array_config['code_prefix']['video'] . '|' . $global_array_config['code_prefix']['song'] . ')([a-zA-Z0-9\-]+)$/', $array_op[0], $m)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $ms_detail_op_alias = $m[1];
    $ms_detail_prefix = $m[2];
    $ms_detail_code = $m[3];
    $ms_detail_data = array();

    if ($ms_detail_prefix == $global_array_config['code_prefix']['song']) {
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
    } elseif ($ms_detail_prefix == $global_array_config['code_prefix']['video']) {
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
    } elseif ($ms_detail_prefix == $global_array_config['code_prefix']['album']) {
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
}

/**
 * nv_get_fb_share_image()
 *
 * @param mixed $data
 * @return
 */
function nv_get_fb_share_image($data = array())
{
    global $meta_property, $global_array_config, $module_upload;

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

    if (!empty($global_array_config['fb_share_image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $global_array_config['fb_share_image'])) {
        $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_config['fb_share_image'];
        $meta_property['og:image:url'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_config['fb_share_image'];
        $meta_property['og:image:width'] = $global_array_config['fb_share_image_witdh'];
        $meta_property['og:image:height'] = $global_array_config['fb_share_image_height'];
        $meta_property['og:image:type'] = $global_array_config['fb_share_image_mime'];
    }
}

/**
 * nv_get_resource_url()
 *
 * @param mixed $orgSrc
 * @param string $area
 * @param bool $thumb
 * @return
 */
function nv_get_resource_url($orgSrc, $area = 'album', $thumb = false)
{
    global $module_upload, $global_array_config, $module_info, $global_config;

    /**
     * $orgSrc có dạng folder/.../filename sao cho fullpath là NV_UPLOADS_REALDIR/module_name/$orgSrc
     * Trả về đường dẫn có thể hiển thị trực tiếp
     */

    // Kiểm tra file tồn tại
    if ($thumb and is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $orgSrc)) {
        return NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $orgSrc;
    } elseif (is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $orgSrc)) {
        return NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $orgSrc;
    }

    // Lấy từ cấu hình
    $map_cfg_data = array(
        'album' => $global_array_config['res_default_album_avatar'],
        'singer' => $global_array_config['res_default_singer_avatar'],
        'author' => $global_array_config['res_default_author_avatar'],
        'video' => $global_array_config['res_default_video_avatar']
    );

    if (isset($map_cfg_data[$area]) and !empty($map_cfg_data[$area])) {
        if ($thumb and is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area];
        }
    }

    // Mặc định theo lập trình
    $map_cfg_data = array(
        'album' => 'album-art-cover.jpg',
        'singer' => 'singer-art.jpg',
        'author' => 'singer-art.jpg',
        'video' => 'video-art-cover.jpg'
    );

    if (isset($map_cfg_data[$area])) {
        if (is_file(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/themes/default/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/default/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        }
    }

    return NV_BASE_SITEURL . 'themes/default/images/' . $module_info['module_theme'] . '/pix.gif';
}
