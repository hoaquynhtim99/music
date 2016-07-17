<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 05/12/2010 09:47
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    "name" => "NukeViet Music",
    "modfuncs" => "main, listenone, listenlist, search, playlist, album, song, creatalbum, listenuserlist, allplaylist, viewvideo, video, upload, searchvideo, editplaylist, managersong, gift, down",
    "is_sysmod" => 0,
    "virtual" => 1,
    "version" => "3.5.01",
    "date" => "Sun, 08 Apr 2012 00:00:00 GMT",
    "author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
    "note" => "",
    "uploads_dir" => array(
        $module_name,
        $module_name . "/data",
        $module_name . "/clipthumb",
        $module_name . "/thumb",
        $module_name . "/data/video",
        $module_name . "/data/upload",
        $module_name . "/ads",
        $module_name . "/tmp",
        $module_name . "/singerthumb",
        $module_name . "/authorthumb"));
