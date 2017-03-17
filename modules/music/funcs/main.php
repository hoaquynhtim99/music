<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC'))
    die('Stop!!!');

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

$content_albums = $content_videos = $content_singers = $content_songs = array();
$array_singer_ids = $array_singers = array();

if (!empty($global_array_config['home_albums_display'])) {
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND show_inhome=1 AND status=1");
    $db->order("time_add DESC")->limit($global_array_config['home_albums_nums'])->offset(0);
    
    $array_select_fields = array('album_id', 'album_code', 'cat_ids', 'singer_ids', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_hit', 'time_add', 'time_update');
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_album_name default_album_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_alias default_album_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_description default_album_description';
    }
    $db->select(implode(', ', $array_select_fields));

    $array = array();
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        if (empty($row['album_name']) and !empty($row['default_album_name'])) {
            $row['album_name'] = $row['default_album_name'];
        }
        if (empty($row['album_alias']) and !empty($row['default_album_alias'])) {
            $row['album_alias'] = $row['default_album_alias'];
        }
        if (empty($row['album_description']) and !empty($row['default_album_description'])) {
            $row['album_description'] = $row['default_album_description'];
        }
        unset($row['default_album_name'], $row['default_album_alias'], $row['default_album_description']);
        
        $row['singers'] = array();
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }
        
        $array[$row['album_id']] = $row;
    }
    
    $content_albums = $array;
}

if (!empty($global_array_config['home_singers_display'])) {
    
}

if (!empty($global_array_config['home_songs_display'])) {
    
}

if (!empty($global_array_config['home_videos_display'])) {
    
}

// Xác ð?nh ca s?


//print_r($content_albums);
//die();

$contents = nv_theme_main($content_albums, $content_videos, $content_singers, $content_songs);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
