<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_MUSIC', true);
define('NV_MOD_TABLE', $db_config['prefix'] . '_' . $module_data);
define('NV_MOD_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_LINK_AMP', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_FULLLINK', NV_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_MOD_FULLLINK_AMP', NV_MOD_LINK_AMP . '&' . NV_OP_VARIABLE . '=');

$array_mod_title = array();

// Cấu hình module
$cacheFile = NV_LANG_DATA . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_config = unserialize($cache);
} else {
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_config";
    $result = $db->query($sql);
    
    $global_array_config = array();
    while ($row = $result->fetch()) {
        if ($row['config_value_' . NV_LANG_DATA] === null) {
            $global_array_config[$row['config_name']] = $row['config_value_default'];
        } else {
            $global_array_config[$row['config_name']] = $row['config_value_' . NV_LANG_DATA];
        }
    }
    
    $global_array_config['default_language'] = NV_LANG_DATA;
    $sql = "SHOW COLUMNS FROM " . NV_MOD_TABLE . "_albums";
    $result = $db->query($sql);
    
    $start_check = false;
    while ($row = $result->fetch()) {
        if ($row['field'] == 'status') {
            $start_check = true;
        } elseif ($start_check and preg_match("/^([a-z]{2})\_/", $row['field'], $m)) {
            $global_array_config['default_language'] = $m[1];
        }
    }
    
    $nv_Cache->setItem($module_name, $cacheFile, serialize($global_array_config), $cacheTTL);
}

$global_array_config['limit_singers_displayed'] = 2;
$global_array_config['various_artists'] = "Various Artists";
$global_array_config['unknow_singer'] = "Unknow Singer";
$global_array_config['code_prefix'] = array(
    'singer' => 'at',
    'playlist' => 'pl',
    'album' => 'ab',
    'video' => 'mv',
    'cat' => 'gr'
);

$global_array_config['gird_albums_percat_nums'] = 12;
$global_array_config['gird_albums_incat_nums'] = 24;

$global_array_config['funcs_sitetitle'] = array(
    'album' => 'Album mới, album hot nhiều ca sỹ'
);

// Danh mục
$cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_cat = unserialize($cache);
    $global_array_cat_alias = $global_array_cat[1];
    $global_array_cat = $global_array_cat[0];
} else {
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'show_inalbum', 'show_invideo', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_cat_name cat_name';
    $array_select_fields[] = NV_LANG_DATA . '_cat_alias cat_alias';
    $array_select_fields[] = NV_LANG_DATA . '_cat_introtext cat_introtext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_keywords cat_keywords';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_name default_cat_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_alias default_cat_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_introtext default_cat_introtext';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_keywords default_cat_keywords';
    }
    
    $sql = "SELECT " . implode(', ', $array_select_fields) . " FROM " . NV_MOD_TABLE . "_categories ORDER BY weight ASC";
    $result = $db->query($sql);
    
    $global_array_cat = array(); 
    $global_array_cat_alias = array();
       
    while ($row = $result->fetch()) {
        if (empty($row['cat_name']) and !empty($row['default_cat_name'])) {
            $row['cat_name'] = $row['default_cat_name'];
        }
        if (empty($row['cat_alias']) and !empty($row['default_cat_alias'])) {
            $row['cat_alias'] = $row['default_cat_alias'];
        }
        if (empty($row['cat_introtext']) and !empty($row['default_cat_introtext'])) {
            $row['cat_introtext'] = $row['default_cat_introtext'];
        }
        if (empty($row['cat_keywords']) and !empty($row['default_cat_keywords'])) {
            $row['cat_keywords'] = $row['default_cat_keywords'];
        }
        unset($row['default_cat_name'], $row['default_cat_alias'], $row['default_cat_introtext'], $row['default_cat_keywords']);
        $global_array_cat[$row['cat_id']] = $row;
        $global_array_cat_alias[$row['cat_code']] = $row['cat_id'];
    }
    
    $nv_Cache->setItem($module_name, $cacheFile, serialize(array($global_array_cat, $global_array_cat_alias)), $cacheTTL);
}

//print_r($module_info);
//die();

/**
 * nv_get_singers()
 * 
 * @param mixed $array_ids
 * @return
 */
function nv_get_singers($array_ids)
{
    global $global_array_config, $db;
    
    $array_singers = array();
    $array_ids = array_filter(array_unique($array_ids));
    
    if (!empty($array_ids)) {
        $array_select_fields = array('singer_id', 'singer_code', 'singer_birthday', 'singer_birthday_lev', 'nation_id', 'resource_avatar', 'resource_cover', 'stat_albums', 'stat_songs', 'stat_videos');
        $array_select_fields[] = NV_LANG_DATA . '_singer_name singer_name';
        $array_select_fields[] = NV_LANG_DATA . '_singer_alias singer_alias';
        $array_select_fields[] = NV_LANG_DATA . '_singer_nickname singer_nickname';
        $array_select_fields[] = NV_LANG_DATA . '_singer_realname singer_realname';
        if (NV_LANG_DATA != $global_array_config['default_language']) {
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_name default_singer_name';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_alias default_singer_alias';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_nickname default_singer_nickname';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_realname default_singer_realname';
        }
        
        $sql = "SELECT " . implode(', ', $array_select_fields) . " FROM " . NV_MOD_TABLE . "_singers WHERE singer_id IN(" . implode(',', $array_ids) . ")";
        $result = $db->query($sql);
        
        while ($row = $result->fetch()) {
            if (empty($row['singer_name']) and !empty($row['default_singer_name'])) {
                $row['singer_name'] = $row['default_singer_name'];
            }
            if (empty($row['singer_alias']) and !empty($row['default_singer_alias'])) {
                $row['singer_alias'] = $row['default_singer_alias'];
            }
            if (empty($row['singer_nickname']) and !empty($row['default_singer_nickname'])) {
                $row['singer_nickname'] = $row['default_singer_nickname'];
            }
            if (empty($row['singer_realname']) and !empty($row['default_singer_realname'])) {
                $row['singer_realname'] = $row['default_singer_realname'];
            }
            unset($row['default_singer_name'], $row['default_singer_alias'], $row['default_singer_nickname'], $row['default_singer_realname']);
            
            $row['singer_link'] = nv_get_view_singer_link($row);
            
            $array_singers[$row['singer_id']] = $row;
        }
    }
    
    return $array_singers;
}

/**
 * nv_get_album_select_fields()
 * 
 * @param bool $full_fields
 * @return
 */
function nv_get_album_select_fields($full_fields = false)
{
    global $global_array_config;
    $array_select_fields = array('album_id', 'album_code', 'cat_ids', 'singer_ids', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_hit', 'time_add', 'time_update');
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_album_name default_album_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_alias default_album_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_description default_album_description';
    }
    
    $array_lang_fields = array('album_name', 'album_alias', 'album_description');
    
    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_view_singer_link()
 * 
 * @param mixed $singer
 * @return
 */
function nv_get_view_singer_link($singer)
{
    global $global_config, $module_info, $global_array_config;
    return NV_MOD_FULLLINK_AMP . $module_info['alias']['view-singer'] . '/' . $singer['singer_alias'] . '-' . $global_array_config['code_prefix']['singer'] . $singer['singer_code'] . $global_config['rewrite_exturl'];
}

/**
 * nv_get_detail_album_link()
 * 
 * @param mixed $album
 * @return
 */
function nv_get_detail_album_link($album)
{
    global $global_config, $module_info, $global_array_config;
    return NV_MOD_FULLLINK_AMP . $module_info['alias']['detail-album'] . '/' . $album['album_alias'] . '-' . $global_array_config['code_prefix']['album'] . $album['album_code'] . $global_config['rewrite_exturl'];
}