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

use NukeViet\Music\Resources;

/*
 * Khu vực tìm kiếm không index, cho follow
 */
$nv_BotManager->setNoIndex()->setFollow();

// Breadcrumb danh sách albums
$array_mod_title[] = [
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => Resources::getModFullLinkEncode() . $module_info['alias'][$op]
];

$page_title = $module_info['funcs'][$op]['func_site_title'];

$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['type'] = $nv_Request->get_title('type', 'get', '');
$array_search['totals'] = 0;
$array_search['total_songs'] = 0;
$array_search['total_videos'] = 0;
$array_search['total_albums'] = 0;
$array_search['total_artists'] = 0;

if (empty($array_search['q'])) {
    nv_redirect_location(Resources::getModLink());
}

$array_queries = [
    'q' => urlencode($array_search['q'])
];

$base_url = Resources::getModFullLinkEncode() . $op;
$per_page = 20;
$dblike = $db->dblikeescape($array_search['q']);
$dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));

$array_songs = $array_videos = $array_albums = $array_artists = [];
$array_singer_ids = $array_singers = [];

/*
 * Tìm kiếm bài hát
 */
if (empty($array_search['type']) or $array_search['type'] == 'song') {
    $where = [];
    $where[] = "(
        " . NV_LANG_DATA . "_song_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_song_searchkey LIKE '%" . $dblikekey . "%'
    )";
    $where[] = "status=1";
    $where[] = "is_official=1";

    $db->sqlreset()->from(Resources::getTablePrefix() . "_songs")->where(implode(' AND ', $where));
    $db->limit(5)->offset(0);
    $db->order("CASE
        WHEN " . NV_LANG_DATA . "_song_name LIKE '" . $dblike . "' THEN 1
        WHEN " . NV_LANG_DATA . "_song_name LIKE '" . $dblike . "%' THEN 2
        WHEN " . NV_LANG_DATA . "_song_name LIKE '%" . $dblike . "' THEN 4
        ELSE 3
    END ASC, song_id DESC");

    $db->select("COUNT(song_id)");
    $array_search['total_songs'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_songs'];

    $array_select_fields = nv_get_song_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array_songs = [];
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

/*
 * Tìm kiếm video
 */
if (empty($array_search['type']) or $array_search['type'] == 'mv') {
    $where = [];
    $where[] = "(
        " . NV_LANG_DATA . "_video_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_video_searchkey LIKE '%" . $dblikekey . "%'
    )";
    $where[] = "status=1";
    $where[] = "is_official=1";

    $db->sqlreset()->from(Resources::getTablePrefix() . "_videos")->where(implode(' AND ', $where));
    $db->limit(8)->offset(0);
    $db->order("CASE
        WHEN " . NV_LANG_DATA . "_video_name LIKE '" . $dblike . "' THEN 1
        WHEN " . NV_LANG_DATA . "_video_name LIKE '" . $dblike . "%' THEN 2
        WHEN " . NV_LANG_DATA . "_video_name LIKE '%" . $dblike . "' THEN 4
        ELSE 3
    END ASC, video_id DESC");

    $db->select("COUNT(video_id)");
    $array_search['total_videos'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_videos'];

    $array_select_fields = nv_get_video_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array_videos = [];
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

/*
 * Tìm kiếm album
 */
if (empty($array_search['type']) or $array_search['type'] == 'album') {
    $where = [];
    $where[] = "(
        " . NV_LANG_DATA . "_album_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_album_searchkey LIKE '%" . $dblikekey . "%'
    )";
    $where[] = "status=1";
    $where[] = "is_official=1";

    $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where(implode(' AND ', $where));
    $db->limit(8)->offset(0);
    $db->order("CASE
        WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "' THEN 1
        WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "%' THEN 2
        WHEN " . NV_LANG_DATA . "_album_name LIKE '%" . $dblike . "' THEN 4
        ELSE 3
    END ASC, album_id DESC");

    $db->select("COUNT(album_id)");
    $array_search['total_albums'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_albums'];

    $array_select_fields = nv_get_album_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $array_albums = [];
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

/*
 * Tìm kiếm nghệ sĩ
 */
if (empty($array_search['type']) or $array_search['type'] == 'artist') {
    $where = [];
    $where[] = "(
        " . NV_LANG_DATA . "_artist_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_artist_searchkey LIKE '%" . $dblikekey . "%'
    )";
    $where[] = "status=1";
    $where[] = "(artist_type=0 OR artist_type=2)";

    $db->sqlreset()->from(Resources::getTablePrefix() . "_artists")->where(implode(' AND ', $where));
    $db->limit(4)->offset(0);
    $db->order("CASE
        WHEN " . NV_LANG_DATA . "_artist_name LIKE '" . $dblike . "' THEN 1
        WHEN " . NV_LANG_DATA . "_artist_name LIKE '" . $dblike . "%' THEN 2
        WHEN " . NV_LANG_DATA . "_artist_name LIKE '%" . $dblike . "' THEN 4
        ELSE 3
    END ASC, artist_id DESC");

    $array_select_fields = nv_get_artist_select_fields();
    $db->select(implode(', ', $array_select_fields[0]));

    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['singer_link'] = nv_get_view_singer_link($row);

        $array_artists[$row['artist_id']] = $row;
    }
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

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

$contents = nv_theme_music_search($array_search, $array_songs, $array_videos, $array_albums, $array_artists, $array_queries);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
