<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

global $op;

if ($op == 'view-singer') {
    global $data_singer, $request_tab, $module_info, $nv_Lang;

    $xtpl = new XTemplate('block_view_singer_header.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('SINGER_HEADER_HTML', nv_theme_view_singer_header($data_singer, $request_tab));

    $xtpl->parse('main');
    $content = $xtpl->text('main');
}
