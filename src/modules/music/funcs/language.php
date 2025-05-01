<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

$nv_BotManager->setNoIndex()->setNoFollow();

unset($sys_info['server_headers']['content-type'], $sys_info['server_headers']['content-length']);

$file_lang = NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';

$headers['Content-Type'] = 'application/javascript';
$headers['Cache-Control'] = 'max-age=2592000, public'; // 1 tháng hết cache
$headers['Accept-Ranges'] = 'bytes';
$headers['Pragma'] = 'cache';
$headers['Last-Modified'] = gmdate('D, d M Y H:i:s', filemtime($file_lang)) . " GMT";

$contents = 'var MSLANG = ' . json_encode($lang_module);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
