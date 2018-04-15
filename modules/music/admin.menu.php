<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'main',
    'config',
    'view',
    'nation-manager'
);

$submenu['config'] = $lang_module['config'];
