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

// Danh mục
$cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_cat = unserialize($cache);
} else {
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'status');
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
    }
    
    $nv_Cache->setItem($module_name, $cacheFile, serialize($global_array_cat), $cacheTTL);
}

//print_r($global_array_cat);
//die();
