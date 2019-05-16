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

if (!defined('NV_IS_DETAIL_PLAYLIST')) {
    nv_redirect_location(NV_MOD_LINK);
}

$array_singer_ids = $array_singers = [];

// Thiết lập lại một số thông tin cho playlist
$ms_detail_data['playlist_link'] = '';
$ms_detail_data['playlist_link_ember'] = '';
$ms_detail_data['songs'] = [];
$ms_detail_data['tokend'] = md5($ms_detail_data['playlist_code'] . NV_CHECK_SESSION);
$ms_detail_data['creat_by'] = 'N/A';

// Playlist không công khai thì chỉ có một mình nghe
if (empty($ms_detail_data['privacy']) and ((!defined('NV_IS_USER') or $user_info['userid'] != $ms_detail_data['userid'])) and !defined('NV_IS_MODADMIN')) {
    $base_url_rewrite = nv_url_rewrite(NV_MOD_LINK_AMP, true);
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect, 404);
}

// Lấy tên người tạo
$sql = "SELECT username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $ms_detail_data['userid'];
$creat_by = $db->query($sql)->fetch();
if (!empty($creat_by)) {
    $ms_detail_data['creat_by'] = nv_show_name_user($creat_by['first_name'], $creat_by['last_name'], $creat_by['username']);
}

// Lấy các bài hát của playlist này
$array_songids = $array_songs = $array_songs_resources = [];
$array_song_captions = [];
$db->sqlreset()->select('*')->from(NV_MOD_TABLE . "_user_playlists_data")->where("playlist_id=" . $ms_detail_data['playlist_id'] . " AND status=1")->order("weight ASC");
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $array_songids[$row['song_id']] = $row;
}
if (!empty($array_songids)) {
    // Lấy chi tiết bài hát
    $array_select_fields = nv_get_song_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE song_id IN(" . implode(',', array_keys($array_songids)) . ") AND status=1";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if (!empty($row['singer_ids'])) {
            $row['singer_ids'] = explode(',', $row['singer_ids']);
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        } else {
            $row['singer_ids'] = [];
        }
        $row['singers'] = [];
        $row['song_link'] = '';
        $row['song_link_full'] = '';
        $row['resource_avatar_mode'] = 'song';
        $row['resource_cover_mode'] = 'song';
        $row['tokend'] = md5($row['song_code'] . NV_CHECK_SESSION);
        $array_songs[$row['song_id']] = $row;
    }

    // Lấy hết đường dẫn của các bài hát
    if (!empty($array_songs)) {
        $sql = "SELECT * FROM " . NV_MOD_TABLE . "_songs_data WHERE song_id IN(" . implode(',', array_keys($array_songs)) . ") AND status=1";
        $result = $db->query($sql);
        $stt = [];
        while ($row = $result->fetch()) {
            if (!isset($stt[$row['song_id']])) {
                $stt[$row['song_id']] = sizeof($global_array_soquality);
            }
            $stt[$row['song_id']]++;
            $key = isset($global_array_soquality[$row['quality_id']]) ? $global_array_soquality[$row['quality_id']]['weight'] : $stt[$row['song_id']];
            if (!isset($array_songs_resources[$row['song_id']])) {
                $array_songs_resources[$row['song_id']] = [];
            }
            if ($row['resource_server_id'] == 0) {
                $row['resource_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $row['resource_path'];
            }
            $array_songs_resources[$row['song_id']][$key] = [
                'resource_path' => $row['resource_path'],
                'resource_duration' => $row['resource_duration'],
                'quality_name' => isset($global_array_soquality[$row['quality_id']]) ? $global_array_soquality[$row['quality_id']][NV_LANG_DATA . '_quality_name'] : 'N/A'
            ];
        }
    }

    // Lấy lời của các bài hát
    $db->sqlreset()->from(NV_MOD_TABLE . "_songs_caption")->where("song_id IN(" . implode(',', array_keys($array_songids)) . ") AND status=1");
    $db->select("*")->order("weight ASC");
    $result = $db->query($db->sql());

    while ($row = $result->fetch()) {
        if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'])) {
            if (!isset($array_song_captions[$row['song_id']])) {
                $array_song_captions[$row['song_id']] = [];
            }
            $array_song_captions[$row['song_id']][] = [
                'caption_lang' => $row['caption_lang'],
                'caption_name' => isset($global_array_languages[$row['caption_lang']]) ? $global_array_languages[$row['caption_lang']]['name'] : nv_ucfirst($row['caption_lang']),
                'caption_file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'],
                'is_default' => $row['is_default']
            ];
        }
    }
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

// Build lại cho phần chi tiết bài hát
foreach ($array_songids as $song_id => $_tmpdata) {
    if (isset($array_songs[$song_id])) {
        $row = $array_songs[$song_id];

        if (!empty($row['singer_ids'])) {
            foreach ($row['singer_ids'] as $singer_id) {
                if (isset($array_singers[$singer_id])) {
                    $row['singers'][$singer_id] = $array_singers[$singer_id];
                    if (empty($row['resource_avatar']) and !empty($array_singers[$singer_id]['resource_avatar'])) {
                        $row['resource_avatar'] = $array_singers[$singer_id]['resource_avatar'];
                        $row['resource_avatar_mode'] = 'singer';
                    }
                    if (empty($row['resource_cover']) and !empty($array_singers[$singer_id]['resource_cover'])) {
                        $row['resource_cover'] = $array_singers[$singer_id]['resource_cover'];
                        $row['resource_cover_mode'] = 'singer';
                    }
                }
            }
        }
        $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);
        $row['song_link_full'] = NV_MY_DOMAIN . nv_url_rewrite($row['song_link'], true);

        $row['filesdata'] = [];
        if (isset($array_songs_resources[$song_id])) {
            $row['filesdata'] = $array_songs_resources[$song_id];
            ksort($row['filesdata']);
        }
        $ms_detail_data['songs'][$song_id] = $row;
    }
}

// Các phần khác
$ms_detail_data['playlist_link'] = nv_get_detail_playlist_link($ms_detail_data);
$ms_detail_data['playlist_link_ember'] = nv_url_rewrite(nv_get_detail_playlist_link($ms_detail_data, true, 'embed=1'), true);

$true_rewrite_url = nv_url_rewrite(str_replace('&amp;', '&', $is_embed_mode ? $ms_detail_data['playlist_link_ember'] : $ms_detail_data['playlist_link']), true);

// Kiểm tra để chuyển về URL có đuôi .html hoặc tương đương
if ($_SERVER['REQUEST_URI'] != $true_rewrite_url) {
    nv_redirect_location($true_rewrite_url);
}
$canonicalUrl = NV_MAIN_DOMAIN . nv_url_rewrite(str_replace('&amp;', '&', $ms_detail_data['playlist_link']), true);

$ms_detail_data['playlist_link_ember'] = NV_MY_DOMAIN . $ms_detail_data['playlist_link_ember'];

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['playlist_name'] . ' ' . $lang_module['playlist'];
$description = strip_tags(preg_replace('/\<br[^\>]*\>/i', ' ', $ms_detail_data['playlist_introtext']));
if (empty($description)) {
    $description = $page_title;
}

// Bình luận
if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
    $id = $ms_detail_data['playlist_id']; // Chỉ ra ID của đối tượng được bình luận
    $area = MS_COMMENT_AREA_PLAYLIST; // Chỉ ra phạm vi (loại, vị trí...) của đối tượng bình luận
    define('NV_COMM_ID', true);

    // Kiểm tra quyền bình luận
    $allowed = $module_config[$module_name]['allowed_comm'];
    if ($allowed == '-1') {
        // Quyền bình luận theo đối tượng
        // Music chưa lập trình và mặc định thành viên chính thức
        $allowed = '4';
    }
    require_once NV_ROOTDIR . '/modules/comment/comment.php';
    $checkss = md5($module_name . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CACHE_PREFIX);

    $content_comment = nv_comment_module($module_name, $checkss, $area, $id, $allowed, 1);
} else {
    $content_comment = '';
}

// Thống kê lượt nghe (xem)
$cookie_stat = $nv_Request->get_string($module_data . '_stat_playlist', 'cookie', '');
$cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
if (!is_array($cookie_stat)) {
    $cookie_stat = [];
}
$timeout = NV_CURRENTTIME - (5 * 60); // Đếm tăng mỗi 5 phút

if (!isset($cookie_stat[$ms_detail_data['playlist_code']]) or $cookie_stat[$ms_detail_data['playlist_code']] < $timeout) {
    // Cập nhật thống kê
    $sql = "UPDATE " . NV_MOD_TABLE . "_user_playlists SET stat_views=stat_views+1 WHERE playlist_id=" . $ms_detail_data['playlist_id'];
    $db->query($sql);

    // Thêm vào cookie
    $cookie_stat[$ms_detail_data['playlist_code']] = NV_CURRENTTIME;

    // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
    foreach ($cookie_stat as $_key => $_val) {
        if ($_val < $timeout) {
            unset($cookie_stat[$_key]);
        }
    }

    // Ghi lại cookie
    $nv_Request->set_Cookie($module_data . '_stat_playlist', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
}

$contents = nv_theme_detail_playlist($ms_detail_data, $array_song_captions, $content_comment);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$is_embed_mode);
include NV_ROOTDIR . '/includes/footer.php';
