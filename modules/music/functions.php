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
$global_array_config['funcs_keywords'] = array(
    'album' => 'album, album moi, album hot'
);
$global_array_config['funcs_description'] = array(
    'album' => 'Album mới , album hot tuyển chọn các ca sỹ Việt Nam và quốc tế.'
);

// Danh mục
$cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_cat = unserialize($cache);
    $global_array_cat_alias = $global_array_cat[1];
    $global_array_cat = $global_array_cat[0];
} else {
    $array_select_fields = nv_get_cat_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_categories ORDER BY weight ASC";
    $result = $db->query($sql);
    
    $global_array_cat = array(); 
    $global_array_cat_alias = array();
       
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        if (empty($row['cat_absitetitle'])) {
            $row['cat_absitetitle'] = $row['cat_name'];
        }
        if (empty($row['cat_mvsitetitle'])) {
            $row['cat_mvsitetitle'] = $row['cat_name'];
        }
        $global_array_cat[$row['cat_id']] = $row;
        $global_array_cat_alias[$row['cat_code']] = $row['cat_id'];
    }
    
    $nv_Cache->setItem($module_name, $cacheFile, serialize(array($global_array_cat, $global_array_cat_alias)), $cacheTTL);
}

//print_r($global_array_cat);
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
 * nv_get_cat_select_fields()
 * 
 * @param bool $full_fields
 * @return
 */
function nv_get_cat_select_fields($full_fields = false)
{
    global $global_array_config;
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'show_inalbum', 'show_invideo', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_cat_name cat_name';
    $array_select_fields[] = NV_LANG_DATA . '_cat_alias cat_alias';
    $array_select_fields[] = NV_LANG_DATA . '_cat_absitetitle cat_absitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abintrotext cat_abintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abkeywords cat_abkeywords';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvsitetitle cat_mvsitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvintrotext cat_mvintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvkeywords cat_mvkeywords';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_name default_cat_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_alias default_cat_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_absitetitle default_cat_absitetitle';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_abintrotext default_cat_abintrotext';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_abkeywords default_cat_abkeywords';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvsitetitle default_cat_mvsitetitle';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvintrotext default_cat_mvintrotext';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvkeywords default_cat_mvkeywords';
    }
    
    $array_lang_fields = array('cat_absitetitle', 'cat_abintrotext', 'cat_abkeywords', 'cat_mvsitetitle', 'cat_mvintrotext', 'cat_mvkeywords');
    
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