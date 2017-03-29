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

if (!defined('NV_IS_DETAIL_SONG')) {
    header('Location: ' . nv_url_rewrite(NV_MOD_LINK, true));
    die();
}

$array_singer_ids = $array_singers = array();
$array_videos = $array_albums = array();

// Thiết lập lại một số thông tin cho bài hát
$ms_detail_data['singers'] = array();
$ms_detail_data['singer_ids'] = explode(',', $ms_detail_data['singer_ids']);
$ms_detail_data['authors'] = array();
$ms_detail_data['author_ids'] = explode(',', $ms_detail_data['author_ids']);
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['album_link'] = '';
$ms_detail_data['video_link'] = '';
$ms_detail_data['singer_name'] = $global_array_config['unknow_singer'];

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}
if (!empty($ms_detail_data['author_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['author_ids']);
}

if (!empty($ms_detail_data['singer_id'])) {
    // Các album liên quan
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");
    
    $array_select_fields = nv_get_album_select_fields();
    $db->order("album_id DESC")->limit($global_array_config['detail_song_albums_nums']);    
    $db->select(implode(', ', $array_select_fields[0]));
    
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
        
        $array_albums[$row['album_id']] = $row;
    }
    
    // Các video liên quan
    $db->sqlreset()->from(NV_MOD_TABLE . "_videos")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");

    $array_select_fields = nv_get_video_select_fields();
    $db->order("video_id DESC")->limit($global_array_config['detail_song_videos_nums']);    
    $db->select(implode(', ', $array_select_fields[0]));
    
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
        $row['video_link'] = '';
        
        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }
        
        $array_videos[$row['video_id']] = $row;
    }
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

// Xác định lại ảnh, ca sĩ, nhạc sĩ, đường dẫn
foreach ($ms_detail_data['singer_ids'] as $singer_id) {
    if (isset($array_singers[$singer_id])) {
        if (empty($ms_detail_data['resource_avatar']) and !empty($array_singers[$singer_id]['resource_avatar'])) {
            $ms_detail_data['resource_avatar'] = $array_singers[$singer_id]['resource_avatar'];
        }
        if (empty($ms_detail_data['resource_cover']) and !empty($array_singers[$singer_id]['resource_cover'])) {
            $ms_detail_data['resource_cover'] = $array_singers[$singer_id]['resource_cover'];
        }
        $ms_detail_data['singers'][$singer_id] = $array_singers[$singer_id];
    }
}
foreach ($ms_detail_data['author_ids'] as $author_id) {
    if (isset($array_singers[$author_id])) {
        $ms_detail_data['authors'][$author_id] = $array_singers[$author_id];
    }
}
foreach ($array_albums as $id => $row) {
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
    $array_albums[$id] = $row;
}
foreach ($array_videos as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
                if (empty($row['video_link'])) {
                    $row['video_link'] = nv_get_detail_video_link($row, $array_singers[$singer_id]);
                }
            }
        }
    }
    if (empty($row['video_link'])) {
        $row['video_link'] = nv_get_detail_video_link($row);
    }
    $array_videos[$id] = $row;
}
if (isset($array_singers[$ms_detail_data['singer_id']])) {
    $ms_detail_data['album_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'album');
    $ms_detail_data['video_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'video');
    $ms_detail_data['singer_name'] = $array_singers[$ms_detail_data['singer_id']]['artist_name'];
}

//print_r($ms_detail_data);
//die();

$contents = nv_theme_detail_song($ms_detail_data, $array_albums, $array_videos);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
