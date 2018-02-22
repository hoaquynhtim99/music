<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

global $op;

if ($op == 'view-singer') {
    global $data_singer, $request_tab, $lang_module, $lang_global, $module_info;

    $xtpl = new XTemplate('block_view_singer_header.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('SINGER_HEADER_HTML', nv_theme_view_singer_header($data_singer, $request_tab));

    $xtpl->parse('main');
    $content = $xtpl->text('main');
}
