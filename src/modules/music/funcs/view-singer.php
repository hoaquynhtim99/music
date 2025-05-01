<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;

$data_singer = [];
$request_artist_alias = '';
$request_tab = '';
$page = 1;

if (isset($array_op[1])) {
    if (preg_match("/^([a-zA-Z0-9\-]+)\-" . nv_preg_quote(Config::getCodePrefix()->getSinger()) . "([a-zA-Z0-9\-]+)$/", $array_op[1], $m)) {
        $data_singer = nv_get_artists($m[2], true, true);
        $request_artist_alias = $m[1];
    }
}

if (empty($data_singer)) {
    nv_redirect_location(Resources::getModLink());
}

// Các tab
if (isset($array_op[2])) {
    $allTabs = Config::getSingerTabsAlias()->getAllTabs();
    foreach ($allTabs as $tab_key => $tab_alias) {
        if ($tab_alias == $array_op[2]) {
            $request_tab = $tab_key;
            break;
        }
    }
    if (empty($request_tab)) {
        nv_redirect_location(nv_get_view_singer_link($data_singer, false));
    }
}

// Phân trang
if (isset($array_op[3])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[3], $m)) {
        $page = intval($m[1]);
    }
    if ($page <= 1) {
        nv_redirect_location(nv_get_view_singer_link($data_singer, false, $request_tab));
    }
}

// Chỉnh lại đường dẫn nếu Alias thay đổi hoặc đặt page sai
if (isset($array_op[4]) or $data_singer['artist_alias'] != $request_artist_alias or (empty($request_tab) and $_SERVER['REQUEST_URI'] != nv_url_rewrite(nv_get_view_singer_link($data_singer, false), true))) {
    nv_redirect_location(nv_get_view_singer_link($data_singer, false, $request_tab));
}

$base_url = nv_get_view_singer_link($data_singer, true, $request_tab);
$all_pages = 0;
$per_page = 1;

$array_songs = $array_videos = $array_albums = [];
$array_singers = $array_singer_ids = [];

// Lấy các album
if (empty($request_tab) or $request_tab == 'album') {
    $per_page = empty($request_tab) ? Config::getViewSingerMainNumAlbums() : Config::getViewSingerDetailNumAlbums();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $data_singer['artist_id'] . ", singer_ids)");

    if (!empty($request_tab)) {
        $db->select("COUNT(album_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_album_select_fields();
    $db->order("album_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select(implode(', ', $array_select_fields[0]));

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

        $array_albums[$row['album_id']] = $row;
    }
}

// Lấy các bài hát
if (empty($request_tab) or $request_tab == 'song') {
    $per_page = empty($request_tab) ? Config::getViewSingerMainNumSongs() : Config::getViewSingerDetailNumSongs();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_songs")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $data_singer['artist_id'] . ", singer_ids)");

    if (!empty($request_tab)) {
        $db->select("COUNT(song_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_song_select_fields();
    $db->order("song_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select(implode(', ', $array_select_fields[0]));

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
        $row['song_link_full'] = '';
        $row['tokend'] = md5($row['song_code'] . NV_CHECK_SESSION);

        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array_songs[$row['song_id']] = $row;
    }
}

// Lấy các video
if (empty($request_tab) or $request_tab == 'video') {
    $per_page = empty($request_tab) ? Config::getViewSingerMainNumVideos() : Config::getViewSingerDetailNumVideos();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_videos")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $data_singer['artist_id'] . ", singer_ids)");

    if (!empty($request_tab)) {
        $db->select("COUNT(video_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_video_select_fields();
    $db->order("video_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select(implode(', ', $array_select_fields[0]));

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

        $array_videos[$row['video_id']] = $row;
    }
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

foreach ($array_albums as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $array_albums[$id] = $row;
}

foreach ($array_songs as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);
    $row['song_link_full'] = NV_MY_DOMAIN . nv_url_rewrite($row['song_link'], true);
    $array_songs[$id] = $row;
}

foreach ($array_videos as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['video_link'] = nv_get_detail_video_link($row, $row['singers']);
    $array_videos[$id] = $row;
}

// Phân trang
$generate_page = nv_alias_page($page_title, $base_url, $all_pages, $per_page, $page);

// Open Graph
nv_get_fb_share_image($data_singer);

// Thông tin trang, thẻ meta
$key_words = $data_singer['singer_keywords'];
if (!empty($data_singer['singer_introtext'])) {
    $description = $data_singer['singer_introtext'];
} else {
    $description = nv_clean60(strip_tags($data_singer['singer_info'], $global_config['description_length']));
}

if (empty($request_tab)) {
    $page_title = sprintf($nv_Lang->getModule('site_title_singer'), $data_singer['artist_name'], $data_singer['artist_name']);
} elseif ($request_tab == 'song') {
    $page_title = sprintf($nv_Lang->getModule('site_title_singer_song'), $data_singer['artist_name'], $data_singer['artist_name']);
} elseif ($request_tab == 'album') {
    $page_title = sprintf($nv_Lang->getModule('site_title_singer_album'), $data_singer['artist_name'], $data_singer['artist_name']);
} elseif ($request_tab == 'video') {
    $page_title = sprintf($nv_Lang->getModule('site_title_singer_video'), $data_singer['artist_name'], $data_singer['artist_name']);
} else {
    $page_title = sprintf($nv_Lang->getModule('site_title_singer_profile'), $data_singer['artist_name'], $data_singer['artist_name']);
}

if (!empty($description) and !empty($request_tab)) {
    $description = nv_clean60($description, $global_config['description_length'] - 50);
    if ($request_tab == 'song') {
        $description .= $nv_Lang->getModule('des_singer_add_song');
    } elseif ($request_tab == 'album') {
        $description .= $nv_Lang->getModule('des_singer_add_album');
    } elseif ($request_tab == 'video') {
        $description .= $nv_Lang->getModule('des_singer_add_video');
    } elseif ($request_tab == 'profile') {
        $description .= $nv_Lang->getModule('des_singer_add_profile');
    }
}

if ($page > 1) {
    $page_text = $nv_Lang->getGlobal('page') . ' ' . number_format($page, 0, ',', '.');
    $page_title .= NV_TITLEBAR_DEFIS . $page_text;
    if (!empty($description)) {
        $description = nv_clean60($description, $global_config['description_length'] - 20);
        $description .= NV_TITLEBAR_DEFIS . $page_text;
    }
}

$contents = nv_theme_view_singer($data_singer, $request_tab, $array_songs, $array_videos, $array_albums, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
