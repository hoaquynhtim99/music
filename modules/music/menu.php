<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;
use NukeViet\Music\Resources;

/*
 * Sử dụng biến $mod_name để lấy tên
 * Biến có sẵn $mod_file, $mod_data
 */

global $site_mods;

$backup_module_name = $module_name;
$module_name = $mod_name;

require NV_ROOTDIR . '/modules/' . $site_mods[$module_name]['module_file'] . '/init.php';

$mod_info = Resources::getModInfo();
$codePrefix = Config::getCodePrefix();
$array_menu_id = [
    'album' => 9999,
    'video' => 9998,
    'singer' => 9997,
];
$groups_view = '6';

/*
 * Menu của album
 */
$array_item[$array_menu_id['album']] = [
    'parentid' => 0,
    'groups_view' => $groups_view,
    'key' => $array_menu_id['album'],
    'title' => $mod_info['funcs']['list-albums']['func_custom_name'],
    'alias' => $mod_info['alias']['list-albums']
];

/*
 * Menu của video
 */
$array_item[$array_menu_id['video']] = [
    'parentid' => 0,
    'groups_view' => $groups_view,
    'key' => $array_menu_id['video'],
    'title' => $mod_info['funcs']['list-videos']['func_custom_name'],
    'alias' => $mod_info['alias']['list-videos']
];

/*
 * Menu của ca sĩ
 */
$array_item[$array_menu_id['singer']] = [
    'parentid' => 0,
    'groups_view' => $groups_view,
    'key' => $array_menu_id['singer'],
    'title' => $mod_info['funcs']['list-singers']['func_custom_name'],
    'alias' => $mod_info['alias']['list-singers']
];

/*
 * Menu con của các phần trên
 */
foreach ($global_array_cat as $cat) {
    // Hiển thị ở trang album
    if (!empty($cat['show_inalbum'])) {
        $array_item[] = [
            'parentid' => $array_menu_id['album'],
            'groups_view' => $groups_view,
            'key' => $cat['cat_id'],
            'title' => $cat['cat_name'],
            'alias' => $mod_info['alias']['list-albums'] . '/' . $cat['cat_alias'] . '-' . $codePrefix->getCat() . $cat['cat_code']
        ];
    }

    // Hiển thị ở trang video
    if (!empty($cat['show_invideo'])) {
        $array_item[] = [
            'parentid' => $array_menu_id['video'],
            'groups_view' => $groups_view,
            'key' => $cat['cat_id'],
            'title' => $cat['cat_name'],
            'alias' => $mod_info['alias']['list-videos'] . '/' . $cat['cat_alias'] . '-' . $codePrefix->getCat() . $cat['cat_code']
        ];
    }
}

// Trả lại các biến để xử lý khi ra ngoài block
$module_name = $backup_module_name;
