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

use NukeViet\Music\Config;
use NukeViet\Music\Resources;

$nv_BotManager->setNoIndex()->setFollow();

// Yêu cầu đăng nhập thành viên
if (!defined('NV_IS_USER')) {
    $url = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($client_info['selfurl']), true);
    nv_redirect_location($url);
}

// Meta: Phần này không cần vì trang này yêu cầu đăng nhập thành viên, không public
$page_title = $lang_module['mymusic'];
$array_mod_title[] = [
    'catid' => 1,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic']
];

$request_tab = '';
$page = 1;

// Các tab
if (isset($array_op[1])) {
    $allTabs = ['song', 'album', 'mv', 'playlist'];
    foreach ($allTabs as $tab_alias) {
        if ($tab_alias == $array_op[1]) {
            $request_tab = $tab_alias;
            break;
        }
    }
    if (empty($request_tab)) {
        nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['mymusic']);
    }
}

// Phân trang
if (isset($array_op[2])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[2], $m)) {
        $page = intval($m[1]);
    }
    if ($page <= 1) {
        nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['mymusic'] . ($request_tab ? ('/' . $request_tab) : ''));
    }
}

// Cấm tùy ý đặt link sai
if (isset($array_op[3])) {
    nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['mymusic'] . ($request_tab ? ('/' . $request_tab) : ''));
}

if ($request_tab == 'song') {
    $array_mod_title[] = [
        'catid' => 2,
        'title' => $lang_module['mymusic_song'],
        'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic'] . '/' . $request_tab
    ];
    $page_title = $lang_module['mymusic_song'] . NV_TITLEBAR_DEFIS . $page_title;
} elseif ($request_tab == 'album') {
    $array_mod_title[] = [
        'catid' => 2,
        'title' => $lang_module['mymusic_album'],
        'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic'] . '/' . $request_tab
    ];
    $page_title = $lang_module['mymusic_album'] . NV_TITLEBAR_DEFIS . $page_title;
} elseif ($request_tab == 'mv') {
    $array_mod_title[] = [
        'catid' => 2,
        'title' => $lang_module['mymusic_video'],
        'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic'] . '/' . $request_tab
    ];
    $page_title = $lang_module['mymusic_video'] . NV_TITLEBAR_DEFIS . $page_title;
} elseif ($request_tab == 'playlist') {
    $array_mod_title[] = [
        'catid' => 2,
        'title' => $lang_module['mymusic_playlist'],
        'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic'] . '/' . $request_tab
    ];
    $page_title = $lang_module['mymusic_playlist'] . NV_TITLEBAR_DEFIS . $page_title;
}

if ($page > 1) {
    $page_text = $lang_global['page'] . ' ' . number_format($page, 0, ',', '.');
    $page_title = $page_text . NV_TITLEBAR_DEFIS . $page_title;
}

$base_url = Resources::getModFullLink() . $module_info['alias']['mymusic'] . ($request_tab ? ('/' . $request_tab) : '');
$all_pages = 0;
$per_page = 1;

$array_songs = $array_videos = $array_albums = $array_playlists = [];
$array_singers = $array_singer_ids = [];

// Lấy các album yêu thích
if (empty($request_tab) or $request_tab == 'album') {
    $per_page = empty($request_tab) ? 5 : Config::getViewSingerDetailNumAlbums();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_user_favorite_albums tb1");
    $db->join("INNER JOIN " . Resources::getTablePrefix() . "_albums tb2 ON tb1.album_id=tb2.album_id");
    $db->where("tb2.status=1 AND tb1.userid=" . $user_info['userid']);

    if (!empty($request_tab)) {
        $db->select("COUNT(tb1.album_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_album_select_fields();
    $db->order("tb1.time_add DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select('tb2.' . implode(', tb2.', $array_select_fields[0]));

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

// Lấy các bài hát yêu thích
if (empty($request_tab) or $request_tab == 'song') {
    $per_page = empty($request_tab) ? 10 : Config::getViewSingerDetailNumSongs();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_user_favorite_songs tb1");
    $db->join("INNER JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.song_id=tb2.song_id");
    $db->where("tb2.status=1 AND tb1.userid=" . $user_info['userid']);

    if (!empty($request_tab)) {
        $db->select("COUNT(tb1.song_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_song_select_fields();
    $db->order("tb1.time_add DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select('tb2.' . implode(', tb2.', $array_select_fields[0]));

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

// Lấy các video yêu thích
if (empty($request_tab) or $request_tab == 'mv') {
    $per_page = empty($request_tab) ? 8 : Config::getViewSingerDetailNumVideos();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_user_favorite_videos tb1");
    $db->join("INNER JOIN " . Resources::getTablePrefix() . "_videos tb2 ON tb1.video_id=tb2.video_id");
    $db->where("tb2.status=1 AND tb1.userid=" . $user_info['userid']);

    if (!empty($request_tab)) {
        $db->select("COUNT(tb1.video_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_video_select_fields();
    $db->order("tb1.time_add DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select('tb2.' . implode(', tb2.', $array_select_fields[0]));

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

// Lấy các playlist đã tạo
if (empty($request_tab) or $request_tab == 'playlist') {
    $per_page = empty($request_tab) ? 5 : Config::getViewSingerDetailNumAlbums();
    $db->sqlreset()->from(Resources::getTablePrefix() . "_user_playlists");
    $db->where("userid=" . $user_info['userid']);

    if (!empty($request_tab)) {
        $db->select("COUNT(playlist_id)");
        $all_pages = $db->query($db->sql())->fetchColumn();
    }

    $array_select_fields = nv_get_user_playlist_select_fields();
    $db->order("time_add DESC")->offset(($page - 1) * $per_page)->limit($per_page);
    $db->select(implode(', ', $array_select_fields[0]));

    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        // Link chỗ này dẫn tới trang sửa
        //$row['playlist_link'] = nv_get_detail_playlist_link($row);
        $row['playlist_link'] = Resources::getModFullLinkEncode() . $module_info['alias']['manager-playlist'] . '&amp;code=' . $row['playlist_code'];

        $array_playlists[$row['playlist_id']] = $row;
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

$contents = nv_theme_mymusic($request_tab, $array_songs, $array_videos, $array_albums, $array_playlists, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
