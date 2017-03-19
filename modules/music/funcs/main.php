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
    $db->order("album_id DESC")->limit($global_array_config['home_albums_nums'])->offset(0);
    
    $array_select_fields = nv_get_album_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array = array();
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        
        $row['singers'] = array();
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['album_link'] = '';
        
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

// Xác định ca sĩ
$array_singers = nv_get_singers($array_singer_ids);

foreach ($content_albums as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
                if (empty($row['album_link'])) {
                    $row['album_link'] = nv_get_detail_album_link($row, $array_singers[$singer_id]);
                }
            }
        }
    }
    if (empty($row['album_link'])) {
        $row['album_link'] = nv_get_detail_album_link($row);
    }
    $content_albums[$id] = $row;
}

$contents = nv_theme_main($content_albums, $content_videos, $content_singers, $content_songs);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
