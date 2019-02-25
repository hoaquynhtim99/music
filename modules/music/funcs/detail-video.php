<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC'))
    die('Stop!!!');

if (!defined('NV_IS_DETAIL_VIDEO')) {
    nv_redirect_location(NV_MOD_LINK);
}

use NukeViet\Music\Config;

$array_singer_ids = $array_singers = array();
$array_videos = $array_albums = array();

// Thiết lập lại một số thông tin cho video
$ms_detail_data['singers'] = array();
$ms_detail_data['singer_ids'] = explode(',', $ms_detail_data['singer_ids']);
$ms_detail_data['cats'] = array();
$ms_detail_data['cat_ids'] = explode(',', $ms_detail_data['cat_ids']);
$ms_detail_data['authors'] = array();
$ms_detail_data['author_ids'] = explode(',', $ms_detail_data['author_ids']);
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['album_link'] = '';
$ms_detail_data['song'] = array();
$ms_detail_data['song_link'] = '';
$ms_detail_data['video_link'] = '';
$ms_detail_data['video_link_ember'] = '';
$ms_detail_data['singer_name'] = Config::getUnknowSinger();
$ms_detail_data['filesdata'] = array();

if (!empty($ms_detail_data['singer_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['singer_ids']);
}
if (!empty($ms_detail_data['author_ids'])) {
    $array_singer_ids = array_merge_recursive($array_singer_ids, $ms_detail_data['author_ids']);
}

// Bài hát liên quan của video
if (!empty($ms_detail_data['song_id'])) {
    $array_select_fields = nv_get_song_select_fields();

    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE is_official=1 AND status=1 AND song_id=" . $ms_detail_data['song_id'];
    $result = $db->query($sql);
    $song = $result->fetch();

    if (!empty($song)) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($song[$f]) and !empty($song['default_' . $f])) {
                $song[$f] = $song['default_' . $f];
            }
            unset($song['default_' . $f]);
        }

        $song['singers'] = array();
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

        $row['singers'] = array();
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['album_link'] = '';

        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array_albums[$row['album_id']] = $row;
    }

    // Các video liên quan
    $db->sqlreset()->from(NV_MOD_TABLE . "_videos")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['singer_id'] . ", singer_ids)");

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

        $row['singers'] = array();
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
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_videos_data WHERE video_id=" . $ms_detail_data['video_id'] . " AND status=1";
$filesdata = $db->query($sql)->fetchAll();
$stt = sizeof($global_array_mvquality);
foreach ($filesdata as $_fileinfo) {
    $stt++;
    $key = isset($global_array_mvquality[$_fileinfo['quality_id']]) ? $global_array_mvquality[$_fileinfo['quality_id']]['weight'] : $stt;
    $ms_detail_data['filesdata'][$key] = array(
        'resource_path' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $_fileinfo['resource_path'],
        'resource_duration' => $_fileinfo['resource_duration'],
        'quality_name' => isset($global_array_mvquality[$_fileinfo['quality_id']]) ? $global_array_mvquality[$_fileinfo['quality_id']][NV_LANG_DATA . '_quality_name'] : 'N/A'
    );
}
ksort($ms_detail_data['filesdata']);

// Các phần khác
$ms_detail_data['video_link'] = nv_get_detail_video_link($ms_detail_data, $ms_detail_data['singers']);
$ms_detail_data['video_link_ember'] = nv_url_rewrite(nv_get_detail_video_link($ms_detail_data, $ms_detail_data['singers'], true, 'embed=1'), true);

$true_rewrite_url = nv_url_rewrite(str_replace('&amp;', '&', $is_embed_mode ? $ms_detail_data['video_link_ember'] : $ms_detail_data['video_link']), true);

// Kiểm tra để chuyển về URL có đuôi .html hoặc tương đương
if ($_SERVER['REQUEST_URI'] != $true_rewrite_url) {
    nv_redirect_location($true_rewrite_url);
}
$canonicalUrl = NV_MAIN_DOMAIN . nv_url_rewrite(str_replace('&amp;', '&', $ms_detail_data['video_link']), true);

$ms_detail_data['video_link_ember'] = NV_MY_DOMAIN . $ms_detail_data['video_link_ember'];

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['video_name'] . ' ' . $lang_module['video_alias'];
if (!empty($ms_detail_data['singers'])) {
    $page_title .= NV_TITLEBAR_DEFIS;
    if (sizeof($ms_detail_data['singers']) > Config::getLimitSingersDisplayed()) {
        $page_title .= Config::getVariousArtists();
    } else {
        $singers = array();
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

$contents = nv_theme_detail_video($ms_detail_data, $content_comment, $array_albums, $array_videos);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents, !$is_embed_mode);
include NV_ROOTDIR . '/includes/footer.php';
