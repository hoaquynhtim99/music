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

if (!defined('NV_IS_DETAIL_SONG')) {
    nv_redirect_location(Resources::getModLink());
}

$array_singer_ids = $array_singers = [];
$array_videos = $array_albums = [];

// Thiết lập lại một số thông tin cho bài hát
$ms_detail_data['singers'] = [];
$ms_detail_data['singer_ids'] = array_unique(array_filter(array_map('intval', explode(',', $ms_detail_data['singer_ids']))));
$ms_detail_data['cats'] = [];
$ms_detail_data['cat_ids'] = array_unique(array_filter(array_map('intval', explode(',', $ms_detail_data['cat_ids']))));
$ms_detail_data['authors'] = [];
$ms_detail_data['author_ids'] = array_unique(array_filter(array_map('intval', explode(',', $ms_detail_data['author_ids']))));
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['album_link'] = '';
$ms_detail_data['video'] = [];
$ms_detail_data['video_link'] = '';
$ms_detail_data['song_link'] = '';
$ms_detail_data['song_link_ember'] = '';
$ms_detail_data['song_link_pdf'] = '';
$ms_detail_data['singer_name'] = Config::getUnknowSinger();
$ms_detail_data['filesdata'] = [];
$ms_detail_data['captions'] = [];
$ms_detail_data['caption_text'] = '';
$ms_detail_data['caption_file'] = '';
$ms_detail_data['caption_file_ext'] = '';
$ms_detail_data['tokend'] = md5($ms_detail_data['song_code'] . NV_CHECK_SESSION);

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}
if (!empty($ms_detail_data['author_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['author_ids']);
}

// Video liên quan của bài hát
if (!empty($ms_detail_data['video_id'])) {
    $array_select_fields = nv_get_video_select_fields();

    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_videos WHERE is_official=1 AND status=1 AND video_id=" . $ms_detail_data['video_id'];
    $result = $db->query($sql);
    $video = $result->fetch();

    if (!empty($video)) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($video[$f]) and !empty($video['default_' . $f])) {
                $video[$f] = $video['default_' . $f];
            }
            unset($video['default_' . $f]);
        }

        $video['singers'] = [];
        $video['singer_ids'] = explode(',', $video['singer_ids']);
        $video['video_link'] = '';

        if (!empty($video['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $video['singer_ids']);
        }
        $ms_detail_data['video'] = $video;
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
    $ms_detail_data['video_link'] = nv_get_view_singer_link($array_singers[$ms_detail_data['singer_id']], true, 'video');
    $ms_detail_data['singer_name'] = $array_singers[$ms_detail_data['singer_id']]['artist_name'];
}
if (!empty($ms_detail_data['video'])) {
    if (!empty($ms_detail_data['video']['singer_ids'])) {
        foreach ($ms_detail_data['video']['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $ms_detail_data['video']['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    $ms_detail_data['video']['video_link'] = nv_get_detail_video_link($ms_detail_data['video'], $ms_detail_data['video']['singers']);
}

// Xác định lại chủ đề bài hát
foreach ($ms_detail_data['cat_ids'] as $cid) {
    if (isset($global_array_cat[$cid])) {
        $ms_detail_data['cats'][$cid] = $global_array_cat[$cid];
    }
}

// Xác định đường dẫn nghe nhạc
$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_songs_data WHERE song_id=" . $ms_detail_data['song_id'] . " AND status=1";
$filesdata = $db->query($sql)->fetchAll();
$stt = sizeof($global_array_soquality);
foreach ($filesdata as $_fileinfo) {
    $stt++;
    $key = isset($global_array_soquality[$_fileinfo['quality_id']]) ? $global_array_soquality[$_fileinfo['quality_id']]['weight'] : $stt;
    if ($_fileinfo['resource_server_id'] == 0) {
        $_fileinfo['resource_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $_fileinfo['resource_path'];
    }
    $ms_detail_data['filesdata'][$key] = [
        'resource_path' => $_fileinfo['resource_path'],
        'resource_duration' => $_fileinfo['resource_duration'],
        'quality_name' => isset($global_array_soquality[$_fileinfo['quality_id']]) ? $global_array_soquality[$_fileinfo['quality_id']][NV_LANG_DATA . '_quality_name'] : 'N/A'
    ];
}
ksort($ms_detail_data['filesdata']);

// Lời bài hát
$db->sqlreset()->from(Resources::getTablePrefix() . "_songs_caption")->where("song_id=" . $ms_detail_data['song_id'] . " AND status=1");
$db->select("*")->order("weight ASC");
$result = $db->query($db->sql());

while ($row = $result->fetch()) {
    if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'])) {
        $ms_detail_data['captions'][] = [
            'caption_lang' => $row['caption_lang'],
            'caption_name' => isset($global_array_languages[$row['caption_lang']]) ? $global_array_languages[$row['caption_lang']]['name'] : nv_ucfirst($row['caption_lang']),
            'caption_file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'],
            'is_default' => $row['is_default']
        ];
    }
    if (!empty($row['caption_data'])) {
        $ms_detail_data['caption_text'] = $row['caption_data'];
    }
    if (!empty($row['caption_pdf']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf'])) {
        $ms_detail_data['caption_file'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf'];
        $ms_detail_data['caption_file_ext'] = nv_getextension(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf']);
    } elseif (!empty($row['caption_pdf']) and nv_is_url($row['caption_pdf'])) {
        $ms_detail_data['caption_file'] = $row['caption_pdf'];
        $ms_detail_data['caption_file_ext'] = preg_match('/\.pdf$/i', $row['caption_pdf']) ? 'pdf' : '';
    }
}

// Các phần khác
$ms_detail_data['song_link'] = nv_get_detail_song_link($ms_detail_data, $ms_detail_data['singers']);
$ms_detail_data['song_link_ember'] = nv_url_rewrite(nv_get_detail_song_link($ms_detail_data, $ms_detail_data['singers'], true, 'embed=1'), true);
$ms_detail_data['song_link_pdf'] = nv_url_rewrite(nv_get_detail_song_link($ms_detail_data, $ms_detail_data['singers'], true, 'sheet=1'), true);

// Xuất PDF
$is_pdf_mode = ($nv_Request->get_int('sheet', 'get', 0) == 1 ? true : false);
if ($is_pdf_mode) {
    if (empty($ms_detail_data['caption_file']) or $ms_detail_data['caption_file_ext'] != 'pdf') {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    // Chặn index link sheet nhạc
    @header("X-Robots-Tag: noindex, nofollow", true);

    $contents = nv_theme_viewpdf($ms_detail_data['caption_file']);
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$true_rewrite_url = nv_url_rewrite(str_replace('&amp;', '&', $is_embed_mode ? $ms_detail_data['song_link_ember'] : $ms_detail_data['song_link']), true);

// Kiểm tra để chuyển về URL có đuôi .html hoặc tương đương
if ($_SERVER['REQUEST_URI'] != $true_rewrite_url) {
    nv_redirect_location($true_rewrite_url);
}
$canonicalUrl = NV_MAIN_DOMAIN . nv_url_rewrite(str_replace('&amp;', '&', $ms_detail_data['song_link']), true);

$ms_detail_data['song_link_ember'] = NV_MY_DOMAIN . $ms_detail_data['song_link_ember'];

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['song_name'];
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
$key_words = $ms_detail_data['song_keywords'];
$description = strip_tags(preg_replace('/\<br[^\>]*\>/i', ' ', $ms_detail_data['song_introtext']));

// Bình luận
if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
    $id = $ms_detail_data['song_id']; // Chỉ ra ID của đối tượng được bình luận
    $area = MS_COMMENT_AREA_SONG; // Chỉ ra phạm vi (loại, vị trí...) của đối tượng bình luận
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
$cookie_stat = $nv_Request->get_string($module_data . '_stat_song', 'cookie', '');
$cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
if (!is_array($cookie_stat)) {
    $cookie_stat = [];
}
$timeout = NV_CURRENTTIME - (5 * 60); // Đếm tăng mỗi 5 phút

if (!isset($cookie_stat[$ms_detail_data['song_code']]) or $cookie_stat[$ms_detail_data['song_code']] < $timeout) {
    // Cập nhật thống kê bài hát
    $sql = "UPDATE " . Resources::getTablePrefix() . "_songs SET stat_views=stat_views+1 WHERE song_id=" . $ms_detail_data['song_id'];
    $db->query($sql);

    // Cập nhật thống kê tổng quan
    msUpdateStatistics('song');

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
                    WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='song' AND object_id=" . $ms_detail_data['song_id'];
                    if (!$db->exec($sql)) {
                        // Cập nhật không có thì thêm mới
                        $sql = "INSERT INTO " . Resources::getTablePrefix() . "_chart_tmps (
                            chart_week, chart_year, chart_time, cat_id, object_name, object_id, view_hits, summary_scores
                        ) VALUES (
                            " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                            'song', " . $ms_detail_data['song_id'] . ", 1, " . Config::getChartViewRate() . "
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
    $cookie_stat[$ms_detail_data['song_code']] = NV_CURRENTTIME;

    // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
    foreach ($cookie_stat as $_key => $_val) {
        if ($_val < $timeout) {
            unset($cookie_stat[$_key]);
        }
    }

    // Ghi lại cookie
    $nv_Request->set_Cookie($module_data . '_stat_song', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
}

// Xác định xem đã yêu thích hay chưa nếu là thành viên
$ms_detail_data['favorited'] = false;
$ms_detail_data['require_login'] = true;
$ms_detail_data['url_login'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
if (defined('NV_IS_USER')) {
    $ms_detail_data['require_login'] = false;
    if ($db->query("SELECT time_add FROM " . Resources::getTablePrefix() . "_user_favorite_songs WHERE userid=" . $user_info['userid'] . " AND song_id=" . $ms_detail_data['song_id'] . " AND is_removed=0")->fetchColumn()) {
        $ms_detail_data['favorited'] = true;
    }
}

$contents = nv_theme_detail_song($ms_detail_data, $content_comment, $array_albums, $array_videos);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$is_embed_mode);
include NV_ROOTDIR . '/includes/footer.php';
