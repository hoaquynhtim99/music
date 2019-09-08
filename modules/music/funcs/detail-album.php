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
use NukeViet\Music\Shared\Charts;

if (!defined('NV_IS_DETAIL_ALBUM')) {
    nv_redirect_location(NV_MOD_LINK);
}

$array_singer_ids = $array_singers = [];
$array_cat_albums = $array_singer_albums = [];

// Thiết lập lại một số thông tin cho album
$ms_detail_data['singers'] = [];
$ms_detail_data['singer_ids'] = explode(',', $ms_detail_data['singer_ids']);
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['cats'] = [];
$ms_detail_data['cat_ids'] = explode(',', $ms_detail_data['cat_ids']);
$ms_detail_data['cat_id'] = $ms_detail_data['cat_ids'] ? $ms_detail_data['cat_ids'][0] : 0;
$ms_detail_data['singer_albums_link'] = '';
$ms_detail_data['cat_albums_link'] = '';
$ms_detail_data['album_link'] = '';
$ms_detail_data['album_link_ember'] = '';
$ms_detail_data['cat_name'] = Config::getUnknowCat();
$ms_detail_data['singer_name'] = Config::getUnknowSinger();
$ms_detail_data['songs'] = [];
$ms_detail_data['tokend'] = md5($ms_detail_data['album_code'] . NV_CHECK_SESSION);

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}
if (empty($ms_detail_data['album_description'])) {
    $ms_detail_data['album_description'] = $ms_detail_data['album_introtext'];
}

if (!empty($ms_detail_data['singer_id'])) {
    // Các album liên quan
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");

    $array_select_fields = nv_get_album_select_fields();
    $db->order("album_id DESC")->limit(Config::getDetailSongAlbumsNums());
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

        $array_singer_albums[$row['album_id']] = $row;
    }
}

if (!empty($ms_detail_data['cat_id']) and isset($global_array_cat[$ms_detail_data['cat_id']])) {
    $ms_detail_data['cat_name'] = $global_array_cat[$ms_detail_data['cat_id']]['cat_name'];
    $ms_detail_data['cat_albums_link'] = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums'] . '/' . $global_array_cat[$ms_detail_data['cat_id']]['cat_alias'] . '-' . Config::getCodePrefix()->getCat() . $global_array_cat[$ms_detail_data['cat_id']]['cat_code'];

    // Album cùng chủ đề
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['cat_id'] . ", cat_ids)");

    $array_select_fields = nv_get_album_select_fields();
    $db->order("album_id DESC")->limit(Config::getDetailSongAlbumsNums());
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

        $array_cat_albums[$row['album_id']] = $row;
    }
}

// Lấy các bài hát của album này
$array_songids = $array_songs = $array_songs_resources = [];
$array_song_captions = [];
$db->sqlreset()->select('*')->from(NV_MOD_TABLE . "_albums_data")->where("album_id=" . $ms_detail_data['album_id'] . " AND status=1")->order("weight ASC");
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
        $row['song_link_pdf'] = '';
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

// Xác định lại ảnh và tác giả, ca sĩ
foreach ($ms_detail_data['singer_ids'] as $singer_id) {
    if (isset($array_singers[$singer_id])) {
        $ms_detail_data['singers'][$singer_id] = $array_singers[$singer_id];
    }
}
foreach ($array_singer_albums as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $array_singer_albums[$id] = $row;
}
foreach ($array_cat_albums as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $array_cat_albums[$id] = $row;
}
if (isset($array_singers[$ms_detail_data['singer_id']])) {
    $ms_detail_data['singer_albums_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'album');
    $ms_detail_data['singer_name'] = $array_singers[$ms_detail_data['singer_id']]['artist_name'];
}
// Xác định lại chủ đề video
foreach ($ms_detail_data['cat_ids'] as $cid) {
    if (isset($global_array_cat[$cid])) {
        $ms_detail_data['cats'][$cid] = $global_array_cat[$cid];
        $ms_detail_data['cats'][$cid]['cat_link'] = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums'] . '/' . $global_array_cat[$cid]['cat_alias'] . '-' . Config::getCodePrefix()->getCat() . $global_array_cat[$cid]['cat_code'];
    }
}

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
        $row['song_link_pdf'] = nv_get_detail_song_link($row, $row['singers'], true, 'sheet=1');
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
$ms_detail_data['album_link'] = nv_get_detail_album_link($ms_detail_data, $ms_detail_data['singers']);
$ms_detail_data['album_link_ember'] = nv_url_rewrite(nv_get_detail_album_link($ms_detail_data, $ms_detail_data['singers'], true, 'embed=1'), true);

$true_rewrite_url = nv_url_rewrite(str_replace('&amp;', '&', $is_embed_mode ? $ms_detail_data['album_link_ember'] : $ms_detail_data['album_link']), true);

// Kiểm tra để chuyển về URL có đuôi .html hoặc tương đương
if ($_SERVER['REQUEST_URI'] != $true_rewrite_url) {
    nv_redirect_location($true_rewrite_url);
}
$canonicalUrl = NV_MAIN_DOMAIN . nv_url_rewrite(str_replace('&amp;', '&', $ms_detail_data['album_link']), true);

$ms_detail_data['album_link_ember'] = NV_MY_DOMAIN . $ms_detail_data['album_link_ember'];

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['album_name'] . ' ' . $lang_module['album'];
if (!empty($ms_detail_data['singers'])) {
    $page_title .= NV_TITLEBAR_DEFIS;
    if (sizeof($ms_detail_data['singers']) > Config::getLimitSingersDisplayed()) {
        $page_title .= Config::getVariousArtists();
    } else {
        $singers = [];
        foreach ($ms_detail_data['singers'] as $singer) {
            $singers[] = $singer['artist_name'];
        }
        $page_title .= implode(', ', $singers);
    }
}
$key_words = $ms_detail_data['album_keywords'];
$description = strip_tags(preg_replace('/\<br[^\>]*\>/i', ' ', $ms_detail_data['album_introtext']));

// Bình luận
if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
    $id = $ms_detail_data['album_id']; // Chỉ ra ID của đối tượng được bình luận
    $area = MS_COMMENT_AREA_ALBUM; // Chỉ ra phạm vi (loại, vị trí...) của đối tượng bình luận
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
$cookie_stat = $nv_Request->get_string($module_data . '_stat_album', 'cookie', '');
$cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
if (!is_array($cookie_stat)) {
    $cookie_stat = [];
}
$timeout = NV_CURRENTTIME - (5 * 60); // Đếm tăng mỗi 5 phút

if (!isset($cookie_stat[$ms_detail_data['album_code']]) or $cookie_stat[$ms_detail_data['album_code']] < $timeout) {
    // Cập nhật thống kê
    $sql = "UPDATE " . NV_MOD_TABLE . "_albums SET stat_views=stat_views+1 WHERE album_id=" . $ms_detail_data['album_id'];
    $db->query($sql);

    // Cập nhật thống kê tổng quan
    msUpdateStatistics('album');

    // Cập nhật bảng xếp hạng
    $is_in_chart = [];
    foreach ($global_array_cat_chart as $_tmp) {
        $check = array_intersect($_tmp['cat_ids'], $ms_detail_data['cat_ids']);
        if (!empty($check)) {
            $is_in_chart[] = $_tmp['cat_id'];
        }
    }
    if (!empty($is_in_chart)) {
        $chart_time = Charts::getCurrentTime();
        $chart_week = Charts::getCurrentWeek();
        $chart_year = Charts::getCurrentYear();

        foreach ($is_in_chart as $id_cat_chart) {
            try {
                $sql = "UPDATE " . NV_MOD_TABLE . "_chart_tmps SET view_hits=view_hits+1, summary_scores=summary_scores+" . Config::getChartViewRate() . "
                WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='album' AND object_id=" . $ms_detail_data['album_id'];
                if (!$db->exec($sql)) {
                    // Cập nhật không có thì thêm mới
                    $sql = "INSERT INTO " . NV_MOD_TABLE . "_chart_tmps (
                        chart_week, chart_year, chart_time, cat_id, object_name, object_id, view_hits, summary_scores
                    ) VALUES (
                        " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                        'album', " . $ms_detail_data['album_id'] . ", 1, " . Config::getChartViewRate() . "
                    )";
                    $db->query($sql);
                }
            } catch (PDOException $e) {
                trigger_error(print_r($e, true));
            }
        }
    }

    // Thêm vào cookie
    $cookie_stat[$ms_detail_data['album_code']] = NV_CURRENTTIME;

    // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
    foreach ($cookie_stat as $_key => $_val) {
        if ($_val < $timeout) {
            unset($cookie_stat[$_key]);
        }
    }

    // Ghi lại cookie
    $nv_Request->set_Cookie($module_data . '_stat_album', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
}

// Xác định xem đã yêu thích hay chưa nếu là thành viên
$ms_detail_data['favorited'] = false;
$ms_detail_data['require_login'] = true;
$ms_detail_data['url_login'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
if (defined('NV_IS_USER')) {
    $ms_detail_data['require_login'] = false;
    if ($db->query("SELECT time_add FROM " . NV_MOD_TABLE . "_user_favorite_albums WHERE userid=" . $user_info['userid'] . " AND album_id=" . $ms_detail_data['album_id'] . " AND is_removed=0")->fetchColumn()) {
        $ms_detail_data['favorited'] = true;
    }
}

$contents = nv_theme_detail_album($ms_detail_data, $array_song_captions, $content_comment, $array_singer_albums, $array_cat_albums);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$is_embed_mode);
include NV_ROOTDIR . '/includes/footer.php';
