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

if (!defined('NV_IS_DETAIL_ALBUM')) {
    nv_redirect_location(NV_MOD_LINK);
}

$array_singer_ids = $array_singers = array();
$array_cat_albums = $array_singer_albums = array();

// Thiết lập lại một số thông tin cho album
$ms_detail_data['singers'] = array();
$ms_detail_data['singer_ids'] = explode(',', $ms_detail_data['singer_ids']);
$ms_detail_data['singer_id'] = $ms_detail_data['singer_ids'] ? $ms_detail_data['singer_ids'][0] : 0;
$ms_detail_data['cats'] = array();
$ms_detail_data['cat_ids'] = explode(',', $ms_detail_data['cat_ids']);
$ms_detail_data['cat_id'] = $ms_detail_data['cat_ids'] ? $ms_detail_data['cat_ids'][0] : 0;
$ms_detail_data['singer_albums_link'] = '';
$ms_detail_data['cat_albums_link'] = '';
$ms_detail_data['album_link_ember'] = '';
$ms_detail_data['cat_name'] = $global_array_config['unknow_cat'];
$ms_detail_data['singer_name'] = $global_array_config['unknow_singer'];
$ms_detail_data['songs'] = array();

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
    $db->order("album_id DESC")->limit($global_array_config['detail_song_albums_nums']);
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

        $array_singer_albums[$row['album_id']] = $row;
    }
}

if (!empty($ms_detail_data['cat_id']) and isset($global_array_cat[$ms_detail_data['cat_id']])) {
    $ms_detail_data['cat_name'] = $global_array_cat[$ms_detail_data['cat_id']]['cat_name'];
    $ms_detail_data['cat_albums_link'] = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums'] . '/' . $global_array_cat[$ms_detail_data['cat_id']]['cat_alias'] . '-' . $global_array_config['code_prefix']['cat'] . $global_array_cat[$ms_detail_data['cat_id']]['cat_code'];

    // Album cùng chủ đề
    $db->sqlreset()->from(NV_MOD_TABLE . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $ms_detail_data['cat_id'] . ", cat_ids)");

    $array_select_fields = nv_get_album_select_fields();
    $db->order("album_id DESC")->limit($global_array_config['detail_song_albums_nums']);
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

        $array_cat_albums[$row['album_id']] = $row;
    }
}

// Lấy các bài hát của album này
$array_songids = $array_songs = $array_songs_resources = array();
$array_song_captions = array();
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
            $row['singer_ids'] = array();
        }
        $row['singers'] = array();
        $row['song_link'] = '';
        $row['resource_avatar_mode'] = 'song';
        $row['resource_cover_mode'] = 'song';
        $array_songs[$row['song_id']] = $row;
    }

    // Lấy hết đường dẫn của các bài hát
    if (!empty($array_songs)) {
        $sql = "SELECT * FROM " . NV_MOD_TABLE . "_songs_data WHERE song_id IN(" . implode(',', array_keys($array_songs)) . ") AND status=1";
        $result = $db->query($sql);
        $stt = array();
        while ($row = $result->fetch()) {
            if (!isset($stt[$row['song_id']])) {
                $stt[$row['song_id']] = sizeof($global_array_soquality);
            }
            $stt[$row['song_id']]++;
            $key = isset($global_array_soquality[$row['quality_id']]) ? $global_array_soquality[$row['quality_id']]['weight'] : $stt[$row['song_id']];
            if (!isset($array_songs_resources[$row['song_id']])) {
                $array_songs_resources[$row['song_id']] = array();
            }
            $array_songs_resources[$row['song_id']][$key] = array(
                'resource_path' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_config['uploads_folder'] . '/' . $row['resource_path'],
                'resource_duration' => $row['resource_duration'],
                'quality_name' => isset($global_array_soquality[$row['quality_id']]) ? $global_array_soquality[$row['quality_id']][NV_LANG_DATA . '_quality_name'] : 'N/A'
            );
        }
    }

    // Lấy lời của các bài hát
    $db->sqlreset()->from(NV_MOD_TABLE . "_songs_caption")->where("song_id IN(" . implode(',', array_keys($array_songids)) . ") AND status=1");
    $db->select("*")->order("weight ASC");
    $result = $db->query($db->sql());

    while ($row = $result->fetch()) {
        if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'])) {
            if (!isset($array_song_captions[$row['song_id']])) {
                $array_song_captions[$row['song_id']] = array();
            }
            $array_song_captions[$row['song_id']][] = array(
                'caption_lang' => $row['caption_lang'],
                'caption_name' => isset($global_array_languages[$row['caption_lang']]) ? $global_array_languages[$row['caption_lang']]['name'] : nv_ucfirst($row['caption_lang']),
                'caption_file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_file'],
                'is_default' => $row['is_default']
            );
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
        $ms_detail_data['cats'][$cid]['cat_link'] = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums'] . '/' . $global_array_cat[$cid]['cat_alias'] . '-' . $global_array_config['code_prefix']['cat'] . $global_array_cat[$cid]['cat_code'];
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

        $row['filesdata'] = array();
        if (isset($array_songs_resources[$song_id])) {
            $row['filesdata'] = $array_songs_resources[$song_id];
            ksort($row['filesdata']);
        }
        $ms_detail_data['songs'][$song_id] = $row;
    }
}

// Open Graph
nv_get_fb_share_image($ms_detail_data);

$page_title = $ms_detail_data['album_name'] . ' ' . $lang_module['album'];
if (!empty($ms_detail_data['singers'])) {
    $page_title .= NV_TITLEBAR_DEFIS;
    if (sizeof($ms_detail_data['singers']) > $global_array_config['limit_singers_displayed']) {
        $page_title .= $global_array_config['various_artists'];
    } else {
        $singers = array();
        foreach ($ms_detail_data['singers'] as $singer) {
            $singers[] = $singer['artist_name'];
        }
        $page_title .= implode(', ', $singers);
    }
}
$key_words = $ms_detail_data['album_keywords'];
$description = strip_tags(preg_replace('/\<br[^\>]*\>/i', ' ', $ms_detail_data['album_introtext']));

$contents = nv_theme_detail_album($ms_detail_data, $array_song_captions, $array_singer_albums, $array_cat_albums);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
