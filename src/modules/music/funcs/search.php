<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Utils;

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
$array_search['sort'] = $nv_Request->get_int('sort', 'get', 1);
$array_search['genre'] = $nv_Request->get_title('genre', 'get', '');
$array_search['totals'] = 0;
$array_search['total_songs'] = 0;
$array_search['total_videos'] = 0;
$array_search['total_albums'] = 0;
$array_search['total_artists'] = 0;

if ($array_search['sort'] < 1 or $array_search['sort'] > 3) {
    $array_search['sort'] = 1;
}
if (!empty($array_search['type']) and !in_array($array_search['type'], ['song', 'mv', 'album', 'artist'])) {
    $array_search['type'] = '';
}
$array_search['genre'] = array_filter(array_unique(array_map('trim', explode('-', $array_search['genre']))));
$genres = $array_search['genre'];
$array_search['genre'] = [];
foreach ($global_array_cat as $cat) {
    if (in_array($cat['cat_code'], $genres)) {
        $array_search['genre'][$cat['cat_id']] = $cat['cat_code'];
    }
}

if (empty($array_search['q'])) {
    nv_redirect_location(Resources::getModLink());
}

$array_queries = [
    'q' => $array_search['q']
];
if (!empty($array_search['type'])) {
    $array_queries['type'] = $array_search['type'];
}
if (!empty($array_search['genre'])) {
    $array_queries['genre'] = $array_search['genre'];
}
if ($array_search['sort'] > 0) {
    $array_queries['sort'] = $array_search['sort'];
}

$base_url = Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries);
$dblike = $db->dblikeescape($array_search['q']);
$dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
$page = $nv_Request->get_int('page', 'get', 1);
if ($page < 1 or $page > 9999999) {
    $page = 1;
}
if ($array_search['type'] == 'song') {
    $per_page = 30;
} elseif ($array_search['type'] == 'mv') {
    $per_page = 40;
} elseif ($array_search['type'] == 'album') {
    $per_page = 40;
} elseif ($array_search['type'] == 'artist') {
    $per_page = 40;
} else {
    $page = $per_page = 1;
}

$array_songs = $array_videos = $array_albums = $array_artists = [];
$array_singer_ids = $array_singers = [];

/*
 * Build query tìm theo thể loại
 */
$sql_where_cat = [];
if (!empty($array_search['genre'])) {
    foreach ($array_search['genre'] as $cat_id => $cat_code) {
        $sql_where_cat[] = "FIND_IN_SET(" . $cat_id . ", cat_ids)";
    }
}
$sql_where_cat = !empty($sql_where_cat) ? ("(" . implode(' OR ', $sql_where_cat) . ")") : '';

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
    if (!empty($sql_where_cat)) {
        $where[] = $sql_where_cat;
    }

    $db->sqlreset()->from(Resources::getTablePrefix() . "_songs")->where(implode(' AND ', $where));

    $db->select("COUNT(song_id)");
    $array_search['total_songs'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_songs'];

    if ($array_search['type'] == 'song') {
        $db->limit($per_page)->offset(($page - 1) * $per_page);
    } else {
        $db->limit(5)->offset(0);
    }
    if ($array_search['sort'] == 1) {
        // Mặc định
        $db->order("CASE
            WHEN " . NV_LANG_DATA . "_song_name LIKE '" . $dblike . "' THEN 1
            WHEN " . NV_LANG_DATA . "_song_name LIKE '" . $dblike . "%' THEN 2
            WHEN " . NV_LANG_DATA . "_song_name LIKE '%" . $dblike . "' THEN 4
            ELSE 3
        END ASC, song_id DESC");
    } elseif ($array_search['sort'] == 2) {
        // Nghe nhiều
        $db->order("stat_views DESC");
    } else {
        // Mới nhất
        $db->order("song_id DESC");
    }

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
    if (!empty($sql_where_cat)) {
        $where[] = $sql_where_cat;
    }

    $db->sqlreset()->from(Resources::getTablePrefix() . "_videos")->where(implode(' AND ', $where));

    $db->select("COUNT(video_id)");
    $array_search['total_videos'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_videos'];

    if ($array_search['type'] == 'mv') {
        $db->limit($per_page)->offset(($page - 1) * $per_page);
    } else {
        $db->limit(8)->offset(0);
    }
    if ($array_search['sort'] == 1) {
        // Mặc định
        $db->order("CASE
            WHEN " . NV_LANG_DATA . "_video_name LIKE '" . $dblike . "' THEN 1
            WHEN " . NV_LANG_DATA . "_video_name LIKE '" . $dblike . "%' THEN 2
            WHEN " . NV_LANG_DATA . "_video_name LIKE '%" . $dblike . "' THEN 4
            ELSE 3
        END ASC, video_id DESC");
    } elseif ($array_search['sort'] == 2) {
        // Nghe nhiều
        $db->order("stat_views DESC");
    } else {
        // Mới nhất
        $db->order("video_id DESC");
    }

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
    if (!empty($sql_where_cat)) {
        $where[] = $sql_where_cat;
    }

    $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where(implode(' AND ', $where));

    $db->select("COUNT(album_id)");
    $array_search['total_albums'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_albums'];

    if ($array_search['type'] == 'album') {
        $db->limit($per_page)->offset(($page - 1) * $per_page);
    } else {
        $db->limit(8)->offset(0);
    }
    if ($array_search['sort'] == 1) {
        // Mặc định
        $db->order("CASE
            WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "' THEN 1
            WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "%' THEN 2
            WHEN " . NV_LANG_DATA . "_album_name LIKE '%" . $dblike . "' THEN 4
            ELSE 3
        END ASC, album_id DESC");
    } elseif ($array_search['sort'] == 2) {
        // Nghe nhiều
        $db->order("stat_views DESC");
    } else {
        // Mới nhất
        $db->order("album_id DESC");
    }

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

    $db->select("COUNT(artist_id)");
    $array_search['total_artists'] = $db->query($db->sql())->fetchColumn();
    $array_search['totals'] += $array_search['total_artists'];

    if ($array_search['type'] == 'artist') {
        $db->limit($per_page)->offset(($page - 1) * $per_page);
    } else {
        $db->limit(4)->offset(0);
    }
    if ($array_search['sort'] == 3) {
        // Mới nhất
        $db->order("artist_id DESC");
    } else {
        // Mặc định
        $db->order("CASE
            WHEN " . NV_LANG_DATA . "_artist_name LIKE '" . $dblike . "' THEN 1
            WHEN " . NV_LANG_DATA . "_artist_name LIKE '" . $dblike . "%' THEN 2
            WHEN " . NV_LANG_DATA . "_artist_name LIKE '%" . $dblike . "' THEN 4
            ELSE 3
        END ASC, artist_id DESC");
    }

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

if (!empty($array_search['type'])) {
    $generate_page = nv_generate_page($base_url, $array_search['totals'], $per_page, $page);
} else {
    $generate_page = '';
}
$contents = nv_theme_music_search($array_search, $array_songs, $array_videos, $array_albums, $array_artists, $array_queries, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
