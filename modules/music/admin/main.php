<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN'))
    die('Stop!!!');

/*
use NukeViet\Music\Song\Song;
use NukeViet\Music\Singer\DbLoader as SingerLoader;

$song = new Song();

$song->setName("Điều Em Lo Sợ");
$song->setKeywords("k1", "k2", "k3");

$singer1 = SingerLoader::loadFromId($singer_id1);
$singer2 = SingerLoader::loadFromId($singer_id2);

$song->setSinger($singer1, $singer2);

$check = $song->saveToDB();

if (!$check) {
    throw new Exception('Error!!!');
}
*/

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
