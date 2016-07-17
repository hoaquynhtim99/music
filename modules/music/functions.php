<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_MUSIC', true);

require_once NV_ROOTDIR . "/modules/" . $module_file . '/global.class.php';
require_once NV_ROOTDIR . "/modules/" . $module_file . '/global.functions.php';

$classMusic = new nv_mod_music();

// lay quang cao
function getADS()
{
    global $module_data, $global_config, $db, $module_file, $lang_module;

    $ads = array();
    $ads['link'] = array();
    $ads['url'] = array();
    $ads['name'] = array();

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ads ORDER BY RAND()";
    $result = $nv_Cache->db($sql, 'id');

    if (!empty($result)) {
        $i = 0;
        foreach ($result as $row) {
            $ads['name'][] = $row['name'];
            $ads['link'][] = $row['link'];
            $ads['url'][] = $row['url'];
            $i++;
        }

        $j = rand(0, $i - 1);
        $ads['name'] = $ads['name'][$j];
        $ads['link'] = $ads['link'][$j];
        $ads['url'] = $ads['url'][$j];
    } else {
        $ads['link'] = NV_BASE_SITEURL . "modules/" . $module_file . "/data/default.swf";
        $ads['url'] = $global_config['site_url'];
        $ads['name'] = $lang_module['ads'];
    }

    return $ads;
}

// Update luot nghe, HIT bai hat video, album
function updateHIT_VIEW($id, $where, $is_numview = true)
{
    global $module_data, $db, $classMusic;
    ($where == "_video") ? ($key = "view") : ($key = "numview");

    if ($is_numview)
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . $where . " SET " . $key . " = " . $key . "+1 WHERE id =" . $id);

    if ($where == '') {
        $data = $classMusic->getsongbyID($id);
    } elseif ($where == '_video') {
        $data = $classMusic->getvideobyID($id);
    } else {
        $data = $classMusic->getalbumbyID($id);
    }

    $hitdata = explode("-", $data['hit']);
    $hittime = $hitdata[1];
    $hitnum = $hitdata[0];
    if ((NV_CURRENTTIME - $hittime) > 864000) {
        $hit = "0-" . NV_CURRENTTIME;
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . $where . " SET hit = " . $db->quote($hit) . " WHERE id =" . $id);
    } else {
        $newhit = $hitnum + 1;
        $hit = $newhit . "-" . $hittime;
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . $where . " SET hit = " . $db->quote($hit) . " WHERE id =" . $id);
    }
    return;
}

function module_info_die()
{
    global $lang_module;

    nv_info_die($lang_module['err_module_title'], $lang_module['err_module_title'], $lang_module['err_module_content']);

    return false;
}

if ($op == 'main') {
    if (isset($array_op[0])) {
        if ($array_op[0] == $classMusic->setting['alias_listen_song']) {
            $op = 'listenone';
        }
    }
}

$downURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=down&amp;id=";
