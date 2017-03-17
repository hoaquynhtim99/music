<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['config'];

$array = array();
if ($nv_Request->isset_request('submit', 'post')) {
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
    
    $sth = $db->prepare("UPDATE " . NV_MOD_TABLE . "_config SET config_value_" . NV_LANG_DATA . "=:config_value WHERE config_name=:config_name");
    foreach ($array as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->execute();
    }
    
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_CHANGE_CONFIG', '', $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    
    $ajaxRespon->reset()->setSuccess()->setMessage($lang_module['successfully_saved'])->respon();
}

$sql = "SELECT * FROM " . NV_MOD_TABLE . "_config";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    if ($row['config_value_' . NV_LANG_DATA] === null) {
        $array[$row['config_name']] = $row['config_value_default'];
    } else {
        $array[$row['config_name']] = $row['config_value_' . NV_LANG_DATA];
    }
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$array['home_albums_display'] = empty($array['home_albums_display']) ? '' : ' checked="checked"';
$array['home_singers_display'] = empty($array['home_singers_display']) ? '' : ' checked="checked"';
$array['home_songs_display'] = empty($array['home_songs_display']) ? '' : ' checked="checked"';
$array['home_videos_display'] = empty($array['home_videos_display']) ? '' : ' checked="checked"';

$xtpl->assign('DATA', $array);

$xtpl->assign('CONFIG_NOTE', sprintf($lang_module['config_note'], $language_array[NV_LANG_DATA]['name']));

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

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
