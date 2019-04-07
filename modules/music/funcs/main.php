<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Các thẻ meta Open Graph
nv_get_fb_share_image();

$content_albums = $content_videos = $content_singers = $content_songs = [];
$array_singer_ids = $array_singers = [];

// Các albums
if (!empty(Config::getHomeAlbumsDisplay())) {
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND show_inhome=1 AND status=1");
    $db->order("album_id DESC")->limit(Config::getHomeAlbumsNums())->offset(0);

    $array_select_fields = nv_get_album_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array = [];
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['singers'] = [];
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['album_link'] = '';

        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array[$row['album_id']] = $row;
    }

    $content_albums = $array;
}

// Các ca sĩ trên trang chủ
if (!empty(Config::getHomeSingersDisplay())) {
    $db->sqlreset()->from(NV_MOD_TABLE . "_artists")->where("show_inhome=1 AND status=1 AND (artist_type=0 OR artist_type=2)");
    $db->order("RAND()")->limit(Config::getHomeSingersNums())->offset(0);

    $array_select_fields = nv_get_artist_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array = [];
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['singer_link'] = nv_get_view_singer_link($row);
        $array[$row['artist_id']] = $row;
    }

    $content_singers = $array;
}

// Các bài hát trang chủ
if (!empty(Config::getHomeSongsDisplay())) {
    $db->sqlreset()->from(NV_MOD_TABLE . "_songs")->where("show_inhome=1 AND status=1 AND is_official=1");
    $db->order("song_id DESC")->limit(Config::getHomeSongsNums())->offset(0);

    $array_select_fields = nv_get_song_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array = [];
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['singers'] = [];
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['song_link'] = '';
        $row['resource_mode'] = 'song';

        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array[$row['song_id']] = $row;
    }

    $content_songs = $array;
}

// Các video trang chủ
if (!empty(Config::getHomeVideosDisplay())) {
    $db->sqlreset()->from(NV_MOD_TABLE . "_videos")->where("show_inhome=1 AND status=1 AND is_official=1");
    $db->order("video_id DESC")->limit(Config::getHomeVideosNums())->offset(0);

    $array_select_fields = nv_get_video_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array = [];
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['singers'] = [];
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['video_link'] = '';

        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array[$row['video_id']] = $row;
    }

    $content_videos = $array;
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

foreach ($content_albums as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $content_albums[$id] = $row;
}

foreach ($content_songs as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
                if (empty($row['resource_avatar']) and !empty($array_singers[$singer_id]['resource_avatar'])) {
                    $row['resource_avatar'] = $array_singers[$singer_id]['resource_avatar'];
                    $row['resource_mode'] = 'singer';
                }
            }
        }
    }
    $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);
    $content_songs[$id] = $row;
}

foreach ($content_videos as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['video_link'] = nv_get_detail_video_link($row, $row['singers']);
    $content_videos[$id] = $row;
}

$contents = nv_theme_main($content_albums, $content_videos, $content_singers, $content_songs);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
