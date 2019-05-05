<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$area = $nv_Request->get_int('area', 'get', 0);

if ($area == MS_COMMENT_AREA_SONG) {
    $array_select_fields = nv_get_song_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_id=:song_id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':song_id', $id, PDO::PARAM_STR);
    $sth->execute();
    $ms_detail_data = $sth->fetch();
} elseif ($area == MS_COMMENT_AREA_VIDEO) {
    $array_select_fields = nv_get_video_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE status=1 AND video_id=:video_id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':video_id', $id, PDO::PARAM_STR);
    $sth->execute();
    $ms_detail_data = $sth->fetch();
} elseif ($area == MS_COMMENT_AREA_ALBUM) {
    $array_select_fields = nv_get_album_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_albums WHERE status=1 AND album_id=:album_id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':album_id', $id, PDO::PARAM_STR);
    $sth->execute();
    $ms_detail_data = $sth->fetch();
} elseif ($area == MS_COMMENT_AREA_PLAYLIST) {
    $array_select_fields = nv_get_user_playlist_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_user_playlists WHERE playlist_id=:playlist_id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':playlist_id', $id, PDO::PARAM_STR);
    $sth->execute();
    $ms_detail_data = $sth->fetch();
}

if (empty($ms_detail_data)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

$array_singer_ids = $array_singers = array();
$ms_detail_data['singers'] = array();
$ms_detail_data['singer_ids'] = isset($ms_detail_data['singer_ids']) ? explode(',', $ms_detail_data['singer_ids']) : [];

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

// Xác định lại ảnh, ca sĩ, nhạc sĩ, đường dẫn
foreach ($ms_detail_data['singer_ids'] as $singer_id) {
    if (isset($array_singers[$singer_id])) {
        $ms_detail_data['singers'][$singer_id] = $array_singers[$singer_id];
    }
}

$url = '';
if ($area == MS_COMMENT_AREA_SONG) {
    $url = nv_get_detail_song_link($ms_detail_data, $ms_detail_data['singers']);
} elseif ($area == MS_COMMENT_AREA_VIDEO) {
    $url = nv_get_detail_video_link($ms_detail_data, $ms_detail_data['singers']);
} elseif ($area == MS_COMMENT_AREA_ALBUM) {
    $url = nv_get_detail_album_link($ms_detail_data, $ms_detail_data['singers']);
} elseif ($area == MS_COMMENT_AREA_PLAYLIST) {
    $url = nv_get_detail_playlist_link($ms_detail_data);
}

nv_redirect_location($url);
