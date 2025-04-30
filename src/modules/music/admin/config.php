<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Resources;

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('config');
$uploadPrefixDir = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/';
$subStrResource = strlen($uploadPrefixDir);

$array = [];
if ($nv_Request->isset_request('submitform', 'post')) {
    $array['home_albums_display'] = ($nv_Request->get_int('home_albums_display', 'post', 0) == 1 ? 1 : 0);
    $array['home_singers_display'] = ($nv_Request->get_int('home_singers_display', 'post', 0) == 1 ? 1 : 0);
    $array['home_songs_display'] = ($nv_Request->get_int('home_songs_display', 'post', 0) == 1 ? 1 : 0);
    $array['home_videos_display'] = ($nv_Request->get_int('home_videos_display', 'post', 0) == 1 ? 1 : 0);
    $array['home_albums_weight'] = $nv_Request->get_int('home_albums_weight', 'post', 0);
    $array['home_singers_weight'] = $nv_Request->get_int('home_singers_weight', 'post', 0);
    $array['home_songs_weight'] = $nv_Request->get_int('home_songs_weight', 'post', 0);
    $array['home_videos_weight'] = $nv_Request->get_int('home_videos_weight', 'post', 0);
    $array['home_albums_nums'] = $nv_Request->get_int('home_albums_nums', 'post', 0);
    $array['home_singers_nums'] = $nv_Request->get_int('home_singers_nums', 'post', 0);
    $array['home_songs_nums'] = $nv_Request->get_int('home_songs_nums', 'post', 0);
    $array['home_videos_nums'] = $nv_Request->get_int('home_videos_nums', 'post', 0);

    $array['limit_singers_displayed'] = $nv_Request->get_int('limit_singers_displayed', 'post', 0);
    $array['various_artists'] = $nv_Request->get_title('various_artists', 'post', '');
    $array['unknow_singer'] = $nv_Request->get_title('unknow_singer', 'post', '');

    $array['arr_code_prefix_singer'] = $nv_Request->get_title('arr_code_prefix_singer', 'post', '');
    $array['arr_code_prefix_playlist'] = $nv_Request->get_title('arr_code_prefix_playlist', 'post', '');
    $array['arr_code_prefix_album'] = $nv_Request->get_title('arr_code_prefix_album', 'post', '');
    $array['arr_code_prefix_video'] = $nv_Request->get_title('arr_code_prefix_video', 'post', '');
    $array['arr_code_prefix_cat'] = $nv_Request->get_title('arr_code_prefix_cat', 'post', '');
    $array['arr_code_prefix_song'] = $nv_Request->get_title('arr_code_prefix_song', 'post', '');

    if (sizeof(array_unique(array($array['arr_code_prefix_singer'], $array['arr_code_prefix_playlist'], $array['arr_code_prefix_album'], $array['arr_code_prefix_video'], $array['arr_code_prefix_cat'], $array['arr_code_prefix_song']))) != 6) {
        AjaxRespon::reset()->setError()->setInput('arr_code_prefix_singer')->setMessage($nv_Lang->getModule('arr_code_prefix_error'))->respon();
    }

    $array['arr_op_alias_prefix_song'] = $nv_Request->get_title('arr_op_alias_prefix_song', 'post', '');
    $array['arr_op_alias_prefix_album'] = $nv_Request->get_title('arr_op_alias_prefix_album', 'post', '');
    $array['arr_op_alias_prefix_video'] = $nv_Request->get_title('arr_op_alias_prefix_video', 'post', '');
    $array['arr_op_alias_prefix_playlist'] = $nv_Request->get_title('arr_op_alias_prefix_playlist', 'post', '');

    $array['gird_albums_percat_nums'] = $nv_Request->get_int('gird_albums_percat_nums', 'post', 0);
    $array['gird_albums_incat_nums'] = $nv_Request->get_int('gird_albums_incat_nums', 'post', 0);
    $array['gird_videos_percat_nums'] = $nv_Request->get_int('gird_videos_percat_nums', 'post', 0);
    $array['gird_videos_incat_nums'] = $nv_Request->get_int('gird_videos_incat_nums', 'post', 0);
    $array['view_singer_show_header'] = $nv_Request->get_int('view_singer_show_header', 'post', 0);
    $array['view_singer_headtext_length'] = $nv_Request->get_int('view_singer_headtext_length', 'post', 0);

    $array['arr_view_singer_tabs_alias_song'] = $nv_Request->get_title('arr_view_singer_tabs_alias_song', 'post', '');
    $array['arr_view_singer_tabs_alias_album'] = $nv_Request->get_title('arr_view_singer_tabs_alias_album', 'post', '');
    $array['arr_view_singer_tabs_alias_video'] = $nv_Request->get_title('arr_view_singer_tabs_alias_video', 'post', '');
    $array['arr_view_singer_tabs_alias_profile'] = $nv_Request->get_title('arr_view_singer_tabs_alias_profile', 'post', '');

    if (sizeof(array_unique(array($array['arr_view_singer_tabs_alias_song'], $array['arr_view_singer_tabs_alias_album'], $array['arr_view_singer_tabs_alias_video'], $array['arr_view_singer_tabs_alias_profile']))) != 4) {
        AjaxRespon::reset()->setError()->setInput('arr_view_singer_tabs_alias_song')->setMessage($nv_Lang->getModule('arr_view_singer_tabs_alias_error'))->respon();
    }

    $array['view_singer_main_num_songs'] = $nv_Request->get_int('view_singer_main_num_songs', 'post', 0);
    $array['view_singer_main_num_videos'] = $nv_Request->get_int('view_singer_main_num_videos', 'post', 0);
    $array['view_singer_main_num_albums'] = $nv_Request->get_int('view_singer_main_num_albums', 'post', 0);
    $array['view_singer_detail_num_songs'] = $nv_Request->get_int('view_singer_detail_num_songs', 'post', 0);
    $array['view_singer_detail_num_videos'] = $nv_Request->get_int('view_singer_detail_num_videos', 'post', 0);
    $array['view_singer_detail_num_albums'] = $nv_Request->get_int('view_singer_detail_num_albums', 'post', 0);

    $array['arr_funcs_sitetitle_album'] = $nv_Request->get_title('arr_funcs_sitetitle_album', 'post', '');
    $array['arr_funcs_keywords_album'] = $nv_Request->get_title('arr_funcs_keywords_album', 'post', '');
    $array['arr_funcs_description_album'] = $nv_Request->get_title('arr_funcs_description_album', 'post', '');
    $array['arr_funcs_sitetitle_video'] = $nv_Request->get_title('arr_funcs_sitetitle_video', 'post', '');
    $array['arr_funcs_keywords_video'] = $nv_Request->get_title('arr_funcs_keywords_video', 'post', '');
    $array['arr_funcs_description_video'] = $nv_Request->get_title('arr_funcs_description_video', 'post', '');
    $array['arr_funcs_sitetitle_singer'] = $nv_Request->get_title('arr_funcs_sitetitle_singer', 'post', '');
    $array['arr_funcs_keywords_singer'] = $nv_Request->get_title('arr_funcs_keywords_singer', 'post', '');
    $array['arr_funcs_description_singer'] = $nv_Request->get_title('arr_funcs_description_singer', 'post', '');

    // Ảnh chia sẻ facebook
    $array['fb_share_image'] = $nv_Request->get_title('fb_share_image', 'post', '');
    $default_fb_share_image = $db->query("SELECT config_value_default FROM " . Resources::getTablePrefix() . "_config WHERE config_name='fb_share_image'")->fetchColumn();

    if (!empty($array['fb_share_image']) and $array['fb_share_image'] != $default_fb_share_image and !nv_is_file($array['fb_share_image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        AjaxRespon::reset()->setError()->setInput('fb_share_image')->setMessage($nv_Lang->getModule('fb_share_image_error'))->respon();
    }

    $array['fb_share_image_witdh'] = 0;
    $array['fb_share_image_height'] = 0;
    $array['fb_share_image_mime'] = '';
    if (!empty($array['fb_share_image'])) {
        $image_info = @getimagesize(NV_DOCUMENT_ROOT . $array['fb_share_image']);
        if (!isset($image_info[0]) or !isset($image_info[1]) or !isset($image_info['mime'])) {
            AjaxRespon::reset()->setError()->setInput('fb_share_image')->setMessage($nv_Lang->getModule('fb_share_image_error1'))->respon();
        }
        if ($image_info[0] < 600 or $image_info[1] < 315) {
            AjaxRespon::reset()->setError()->setInput('fb_share_image')->setMessage($nv_Lang->getModule('fb_share_image_error2'))->respon();
        }
        $array['fb_share_image_witdh'] = $image_info[0];
        $array['fb_share_image_height'] = $image_info[1];
        $array['fb_share_image_mime'] = $image_info['mime'];
        $array['fb_share_image'] = substr($array['fb_share_image'], $subStrResource);
    }

    // Ảnh mặc định
    $_keys = array(
        'res_default_album_avatar',
        'res_default_singer_avatar',
        'res_default_author_avatar',
        'res_default_video_avatar'
    );
    foreach ($_keys as $_key) {
        $_val = $nv_Request->get_title($_key, 'post', '');
        if (!empty($_val) and !nv_is_file($_val, NV_UPLOADS_DIR . '/' . $module_upload)) {
            AjaxRespon::reset()->setError()->setInput($_key)->setMessage($nv_Lang->getModule('fb_share_image_error'))->respon();
        }
        if (!empty($_val)) {
            $_val = substr($_val, $subStrResource);
        }
        $array[$_key] = $_val;
    }

    $sth = $db->prepare("UPDATE " . Resources::getTablePrefix() . "_config SET config_value_" . NV_LANG_DATA . "=:config_value WHERE config_name=:config_name");
    foreach ($array as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_CONFIG', '', $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    AjaxRespon::reset()->setSuccess()->setMessage($nv_Lang->getModule('successfully_saved'))->respon();
}

$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_config";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    if ($row['config_value_' . NV_LANG_DATA] === null) {
        $array[$row['config_name']] = $row['config_value_default'];
    } else {
        $array[$row['config_name']] = $row['config_value_' . NV_LANG_DATA];
    }
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('UPLOAD_DIR', NV_UPLOADS_DIR . '/' . $module_upload);

$array['home_albums_display'] = empty($array['home_albums_display']) ? '' : ' checked="checked"';
$array['home_singers_display'] = empty($array['home_singers_display']) ? '' : ' checked="checked"';
$array['home_songs_display'] = empty($array['home_songs_display']) ? '' : ' checked="checked"';
$array['home_videos_display'] = empty($array['home_videos_display']) ? '' : ' checked="checked"';

// Trả lại đường dẫn ảnh
$_keys = array(
    'res_default_album_avatar',
    'res_default_singer_avatar',
    'res_default_author_avatar',
    'res_default_video_avatar',
    'fb_share_image'
);
foreach ($_keys as $_key) {
    if (!empty($array[$_key])) {
        $array[$_key] = $uploadPrefixDir . $array[$_key];
    }
}

$xtpl->assign('DATA', $array);

$xtpl->assign('OPEN_BRACKET', '{');
$xtpl->assign('CLOSE_BRACKET', '}');

$xtpl->assign('CONFIG_NOTE', sprintf($nv_Lang->getModule('config_note'), $language_array[NV_LANG_DATA]['name']));

for ($i = 1; $i <= 4; $i++) {
    $xtpl->assign('WEIGHT', $i);
    $xtpl->assign('HOME_ALBUMS_WEIGHT', $array['home_albums_weight'] == $i ? ' selected="selected"' : '');
    $xtpl->assign('HOME_SINGERS_WEIGHT', $array['home_singers_weight'] == $i ? ' selected="selected"' : '');
    $xtpl->assign('HOME_SONGS_WEIGHT', $array['home_songs_weight'] == $i ? ' selected="selected"' : '');
    $xtpl->assign('HOME_VIDEOS_WEIGHT', $array['home_videos_weight'] == $i ? ' selected="selected"' : '');
    $xtpl->parse('main.home_albums_weight');
    $xtpl->parse('main.home_singers_weight');
    $xtpl->parse('main.home_songs_weight');
    $xtpl->parse('main.home_videos_weight');
}

for ($i = 1; $i <= 10; $i++) {
    $limit_singers_displayed = array(
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array['limit_singers_displayed'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('LIMIT_SINGERS_DISPLAYED', $limit_singers_displayed);
    $xtpl->parse('main.limit_singers_displayed');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
