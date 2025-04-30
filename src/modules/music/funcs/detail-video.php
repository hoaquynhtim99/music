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
use NukeViet\Music\Shared\Charts;

if (!defined('NV_IS_DETAIL_VIDEO')) {
    nv_redirect_location(Resources::getModLink());
}

$array_singer_ids = $array_singers = [];
$array_videos = $array_albums = [];

// Thiết lập lại một số thông tin cho video
$ms_detail_data['singers'] = [];
$ms_detail_data['singer_ids'] = array_unique(array_filter(array_map('intval', explode(',', $ms_detail_data['singer_ids']))));
$ms_detail_data['cats'] = [];
$ms_detail_data['cat_ids'] = array_unique(array_filter(array_map('intval', explode(',', (string) $ms_detail_data['cat_ids']))));
$ms_detail_data['cat_id'] = $ms_detail_data['cat_ids'] ? $ms_detail_data['cat_ids'][0] : 0;
$ms_detail_data['authors'] = [];
$ms_detail_data['author_ids'] = array_unique(array_filter(array_map('intval', explode(',', $ms_detail_data['author_ids']))));
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['album_link'] = '';
$ms_detail_data['song'] = [];
$ms_detail_data['song_link'] = '';
$ms_detail_data['video_link'] = '';
$ms_detail_data['video_link_ember'] = '';
$ms_detail_data['singer_name'] = Config::getUnknowSinger();
$ms_detail_data['filesdata'] = [];
$ms_detail_data['tokend'] = md5($ms_detail_data['video_code'] . NV_CHECK_SESSION);

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}
if (!empty($ms_detail_data['author_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['author_ids']);
}

// Breadcrumb danh sách MV
$array_mod_title[] = [
    'catid' => 0,
    'title' => $module_info['funcs']['list-videos']['func_custom_name'],
    'link' => Resources::getModFullLinkEncode() . $module_info['alias']['list-videos']
];

// Bài hát liên quan của video
if (!empty($ms_detail_data['song_id'])) {
    $array_select_fields = nv_get_song_select_fields();

    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_songs WHERE is_official=1 AND status=1 AND song_id=" . $ms_detail_data['song_id'];
    $result = $db->query($sql);
    $song = $result->fetch();

    if (!empty($song)) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($song[$f]) and !empty($song['default_' . $f])) {
                $song[$f] = $song['default_' . $f];
            }
            unset($song['default_' . $f]);
        }

        $song['singers'] = [];
        $song['singer_ids'] = explode(',', $song['singer_ids']);
        $song['song_link'] = '';

        if (!empty($song['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $song['singer_ids']);
        }
        $ms_detail_data['song'] = $song;
    }
}

if (!empty($ms_detail_data['singer_id'])) {
    // Các album liên quan
    $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");

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

        $array_albums[$row['album_id']] = $row;
    }

    // Các video liên quan
    $db->sqlreset()->from(Resources::getTablePrefix() . "_videos")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");

    $array_select_fields = nv_get_video_select_fields();
    $db->order("video_id DESC")->limit(Config::getDetailSongVideosNums());
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

// Xác định lại ảnh và tác giả, ca sĩ
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
            }
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $array_albums[$id] = $row;
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
if (isset($array_singers[$ms_detail_data['singer_id']])) {
    $ms_detail_data['album_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'album');
    $ms_detail_data['other_video_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'video');
    $ms_detail_data['singer_name'] = $array_singers[$ms_detail_data['singer_id']]['artist_name'];
}
if (!empty($ms_detail_data['song'])) {
    if (!empty($ms_detail_data['song']['singer_ids'])) {
        foreach ($ms_detail_data['song']['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $ms_detail_data['song']['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $ms_detail_data['song']['song_link'] = nv_get_detail_song_link($ms_detail_data['song'], $ms_detail_data['song']['singers']);
}

// Xác định lại chủ đề video
foreach ($ms_detail_data['cat_ids'] as $cid) {
    if (isset($global_array_cat[$cid])) {
        $ms_detail_data['cats'][$cid] = $global_array_cat[$cid];
    }
}

// Xác định đường dẫn MV
$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_videos_data WHERE video_id=" . $ms_detail_data['video_id'] . " AND status=1";
$filesdata = $db->query($sql)->fetchAll();
$stt = sizeof($global_array_mvquality);
foreach ($filesdata as $_fileinfo) {
    $stt++;
    $key = isset($global_array_mvquality[$_fileinfo['quality_id']]) ? $global_array_mvquality[$_fileinfo['quality_id']]['weight'] : $stt;
    if ($_fileinfo['resource_server_id'] == 0) {
        $_fileinfo['resource_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $_fileinfo['resource_path'];
    }
    $ms_detail_data['filesdata'][$key] = [
        'resource_path' => $_fileinfo['resource_path'],
        'resource_duration' => $_fileinfo['resource_duration'],
        'quality_name' => isset($global_array_mvquality[$_fileinfo['quality_id']]) ? $global_array_mvquality[$_fileinfo['quality_id']][NV_LANG_DATA . '_quality_name'] : 'N/A'
    ];
}
ksort($ms_detail_data['filesdata']);

if (!empty($ms_detail_data['cat_id']) and isset($global_array_cat[$ms_detail_data['cat_id']])) {
    $ms_detail_data['cat_name'] = $global_array_cat[$ms_detail_data['cat_id']]['cat_name'];
    $ms_detail_data['cat_videos_link'] = Resources::getModFullLinkEncode() . $module_info['alias']['list-videos'] . '/' . $global_array_cat[$ms_detail_data['cat_id']]['cat_alias'] . '-' . Config::getCodePrefix()->getCat() . $global_array_cat[$ms_detail_data['cat_id']]['cat_code'];

    // Breadcrumb cho chuyên mục của danh sách album
    $array_mod_title[] = [
        'catid' => 0,
        'title' => $ms_detail_data['cat_name'],
        'link' => $ms_detail_data['cat_videos_link']
    ];
}

// Các phần khác
$ms_detail_data['video_link'] = nv_get_detail_video_link($ms_detail_data, $ms_detail_data['singers']);
$ms_detail_data['video_link_ember'] = nv_url_rewrite(nv_get_detail_video_link($ms_detail_data, $ms_detail_data['singers'], true, 'embed=1'), true);

$page_url = $ms_detail_data['video_link'];
$canonicalUrl = getCanonicalUrl($page_url);

$ms_detail_data['video_link_ember'] = NV_MY_DOMAIN . $ms_detail_data['video_link_ember'];

// MV có link youtube thì không thể nhúng
if (!empty($ms_detail_data['resource_yt']) and $is_embed_mode) {
    nv_redirect_location($ms_detail_data['video_link']);
}

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['video_name'] . ' ' . $nv_Lang->getModule('video_alias');
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
$key_words = $ms_detail_data['video_keywords'];
$description = strip_tags(preg_replace('/\<br[^\>]*\>/i', ' ', $ms_detail_data['video_introtext']));

// Bình luận
if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
    $id = $ms_detail_data['video_id']; // Chỉ ra ID của đối tượng được bình luận
    $area = MS_COMMENT_AREA_VIDEO; // Chỉ ra phạm vi (loại, vị trí...) của đối tượng bình luận
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
$cookie_stat = $nv_Request->get_string($module_data . '_stat_video', 'cookie', '');
$cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
if (!is_array($cookie_stat)) {
    $cookie_stat = [];
}
$timeout = NV_CURRENTTIME - (5 * 60); // Đếm tăng mỗi 5 phút

if (!isset($cookie_stat[$ms_detail_data['video_code']]) or $cookie_stat[$ms_detail_data['video_code']] < $timeout) {
    // Cập nhật thống kê
    $sql = "UPDATE " . Resources::getTablePrefix() . "_videos SET stat_views=stat_views+1 WHERE video_id=" . $ms_detail_data['video_id'];
    $db->query($sql);

    // Cập nhật thống kê tổng quan
    msUpdateStatistics('video');

    // Cập nhật bảng xếp hạng
    if (Config::getChartActive()) {
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
                    $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_tmps SET view_hits=view_hits+1, summary_scores=summary_scores+" . Config::getChartViewRate() . "
                    WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='video' AND object_id=" . $ms_detail_data['video_id'];
                    if (!$db->exec($sql)) {
                        // Cập nhật không có thì thêm mới
                        $sql = "INSERT INTO " . Resources::getTablePrefix() . "_chart_tmps (
                            chart_week, chart_year, chart_time, cat_id, object_name, object_id, view_hits, summary_scores
                        ) VALUES (
                            " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                            'video', " . $ms_detail_data['video_id'] . ", 1, " . Config::getChartViewRate() . "
                        )";
                        $db->query($sql);
                    }
                } catch (PDOException $e) {
                    trigger_error(print_r($e, true));
                }
            }
        }
    }

    // Thêm vào cookie
    $cookie_stat[$ms_detail_data['video_code']] = NV_CURRENTTIME;

    // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
    foreach ($cookie_stat as $_key => $_val) {
        if ($_val < $timeout) {
            unset($cookie_stat[$_key]);
        }
    }

    // Ghi lại cookie
    $nv_Request->set_Cookie($module_data . '_stat_video', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
}

// Xác định xem đã yêu thích hay chưa nếu là thành viên
$ms_detail_data['favorited'] = false;
$ms_detail_data['require_login'] = true;
$ms_detail_data['url_login'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
if (defined('NV_IS_USER')) {
    $ms_detail_data['require_login'] = false;
    if ($db->query("SELECT time_add FROM " . Resources::getTablePrefix() . "_user_favorite_videos WHERE userid=" . $user_info['userid'] . " AND video_id=" . $ms_detail_data['video_id'] . " AND is_removed=0")->fetchColumn()) {
        $ms_detail_data['favorited'] = true;
    }
}

$contents = nv_theme_detail_video($ms_detail_data, $content_comment, $array_albums, $array_videos);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$is_embed_mode);
include NV_ROOTDIR . '/includes/footer.php';
