<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

// Xác định các ngôn ngữ đã cài đặt
$_sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1";
$_result = $db->query($_sql);
$array_lang_setup = [];
while ($_row = $_result->fetch()) {
    $array_lang_setup[$_row['lang']] = $_row['lang'];
}

// Xác định các ngôn ngữ đã cài module
$array_lang_module_setup = [];
foreach ($array_lang_setup as $_lang) {
    $is_setup = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $_lang . "_modules WHERE module_data=" . $db->quote($module_data))->fetchColumn();
    if ($is_setup) {
        $array_lang_module_setup[$_lang] = $_lang;
    }
}

$array_quality_song = [
    1 => ['quality_name' => '128kbps', 'quality_alias' => '128kbps'],
    2 => ['quality_name' => '320kbps', 'quality_alias' => '320kbps'],
    3 => ['quality_name' => 'Lossless', 'quality_alias' => 'Lossless']
];
$array_quality_video = [
    1 => ['quality_name' => '360p', 'quality_alias' => '360p'],
    2 => ['quality_name' => '480p', 'quality_alias' => '480p'],
    3 => ['quality_name' => '720p', 'quality_alias' => '720p'],
    4 => ['quality_name' => '1080p', 'quality_alias' => '1080p']
];

$array_fields_song_name = '';
$array_fields_song_value = [];
$array_fields_video_name = '';
$array_fields_video_value = [];

$array_fname_cat = $array_fvalue_cat = [];
$array_fname_cat_chart = $array_fvalue_cat_chart = [];

foreach ($array_lang_module_setup as $_lang) {
    $array_fields_song_name .= ', ' . $_lang . '_' . implode(', ' . $_lang . '_', array_keys($array_quality_song[1]));
    foreach ($array_quality_song as $_id => $quality_song) {
        if (!isset($array_fields_song_value[$_id])) {
            $array_fields_song_value[$_id] = '';
        }
        $array_fields_song_value[$_id] .= ", '" . implode("', '", $quality_song) . "'";
    }

    $array_fields_video_name .= ', ' . $_lang . '_' . implode(', ' . $_lang . '_', array_keys($array_quality_video[1]));
    foreach ($array_quality_video as $_id => $quality_video) {
        if (!isset($array_fields_video_value[$_id])) {
            $array_fields_video_value[$_id] = '';
        }
        $array_fields_video_value[$_id] .= ", '" . implode("', '", $quality_video) . "'";
    }

    if ($_lang != $lang) {
        $array_fname_cat[] = $_lang . '_cat_abintrotext';
        $array_fname_cat[] = $_lang . '_cat_abkeywords';
        $array_fname_cat[] = $_lang . '_cat_mvintrotext';
        $array_fname_cat[] = $_lang . '_cat_mvkeywords';
        $array_fvalue_cat[] = '';
        $array_fvalue_cat[] = '';
        $array_fvalue_cat[] = '';
        $array_fvalue_cat[] = '';

        $array_fname_cat_chart[] = $lang . '_cat_abintrotext';
        $array_fname_cat_chart[] = $lang . '_cat_abkeywords';
        $array_fname_cat_chart[] = $lang . '_cat_abbodytext';
        $array_fname_cat_chart[] = $lang . '_cat_mvintrotext';
        $array_fname_cat_chart[] = $lang . '_cat_mvkeywords';
        $array_fname_cat_chart[] = $lang . '_cat_mvbodytext';
        $array_fname_cat_chart[] = $lang . '_cat_sointrotext';
        $array_fname_cat_chart[] = $lang . '_cat_sokeywords';
        $array_fname_cat_chart[] = $lang . '_cat_sobodytext';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
        $array_fvalue_cat_chart[] = '';
    }
}

// Chất lượng bài hát
$db->query("INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_quality_song (quality_id, time_add, time_update, online_supported, is_default, weight, status" . $array_fields_song_name . ") VALUES
(1, 1489686232, 0, 1, 1, 1, 1" . $array_fields_song_value[1] . "),
(2, 1489686415, 0, 1, 0, 2, 1" . $array_fields_song_value[2] . "),
(3, 1489686569, 0, 0, 0, 3, 1" . $array_fields_song_value[3] . ");");

// Chất lượng video
$db->query("INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_quality_video (quality_id, time_add, time_update, online_supported, is_default, weight, status" . $array_fields_video_name . ") VALUES
(1, 1489687374, 0, 1, 0, 1, 1" . $array_fields_video_value[1] . "),
(2, 1489687418, 0, 1, 1, 2, 1" . $array_fields_video_value[2] . "),
(3, 1489687419, 0, 1, 0, 3, 1" . $array_fields_video_value[3] . "),
(4, 1489687420, 0, 1, 0, 4, 1" . $array_fields_video_value[4] . ");");

// Thể loại
$array_fname_cat = $array_fname_cat ? (', ' . implode(', ', $array_fname_cat)) : '';
$array_fvalue_cat = $array_fvalue_cat ? (', \'' . implode('\', \'', $array_fvalue_cat) . '\'') : '';

$db->query("INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_categories (
    cat_id, cat_code, resource_avatar, resource_cover, resource_video, stat_albums, stat_songs, stat_videos,
    time_add, time_update, show_inalbum, show_invideo, weight, status,
    " . $lang . "_cat_name, " . $lang . "_cat_alias, " . $lang . "_cat_absitetitle, " . $lang . "_cat_abintrotext,
    " . $lang . "_cat_abkeywords, " . $lang . "_cat_mvsitetitle, " . $lang . "_cat_mvintrotext, " . $lang . "_cat_mvkeywords" . $array_fname_cat . "
) VALUES
    (2, 'nj', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 1, 1, 'Trữ Tình', 'nhac-tru-tinh', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (3, 'bR', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 2, 1, 'Nhạc Trẻ', 'nhac-nhac-tre', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (4, 'P5', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 3, 1, 'Nhạc Trịnh', 'nhac-nhac-trinh', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (5, 'k1', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 4, 1, 'Quê Hương', 'nhac-que-huong', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (6, 'wq', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 5, 1, 'Thiếu Nhi', 'nhac-thieu-nhi', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (7, 'aYQ', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 6, 1, 'Nhạc Hàn', 'nhac-nhac-han', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (8, 'jA', '', '', '', 0, 0, 0, 1494836392, 0, 1, 1, 7, 1, 'Âu Mỹ', 'nhac-au-my', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (9, 'dE', '', '', '', 0, 0, 0, 1494836392, 0, 1, 1, 8, 1, 'Việt Nam', 'nhac-viet-nam', 'Album nhạc Việt Nam', 'Các album nhạc Việt Nam đầy đủ nhất và được cập nhật liên tục', 'album viet, album viet nam, album, viet nam', '', '', ''" . $array_fvalue_cat . "),
    (10, 'KDn', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 9, 1, 'Nhạc Nhật', 'nhac-nhac-nhat', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (11, '1NM', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 10, 1, 'Không Lời', 'nhac-khong-loi', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (12, 'z4o', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 11, 1, 'Nhạc Hoa', 'nhac-nhac-hoa', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (13, 'LE', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 12, 1, 'Rock Việt', 'nhac-rock-viet', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (14, 'VY', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 13, 1, 'Pop', 'nhac-pop', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (15, 'JK', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 14, 1, 'Cách Mạng', 'nhac-cach-mang', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (16, 'qe5', '', '', '', 0, 0, 0, 1494836392, 0, 1, 1, 15, 1, 'Châu Á', 'nhac-chau-a', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (17, 'Bn', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 16, 1, 'Dân ca/Nhạc cổ', 'nhac-dan-ca-nhac-co', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (18, 'QDj', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 17, 1, 'Thể Loại Khác', 'nhac-the-loai-khac', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (19, 'yO', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 18, 1, 'Việt Remix', 'nhac-viet-remix', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (20, 'mkA', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 19, 1, 'Chế - Hài hước', 'nhac-che-hai-huoc', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (21, 'obD', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 20, 1, 'Blue/Jazz', 'nhac-blue-jazz', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (22, 'rR', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 21, 1, 'Country', 'nhac-country', '', '', '', '', '', ''" . $array_fvalue_cat . "),
    (23, '9w', '', '', '', 0, 0, 0, 1494836392, 0, 0, 0, 22, 1, 'Rap/Hiphop Việt', 'nhac-rap-hiphop-viet', '', '', '', '', '', ''" . $array_fvalue_cat . ")
");

// Thể loại bảng xếp hạng
$array_fname_cat_chart = $array_fname_cat_chart ? (', ' . implode(', ', $array_fname_cat_chart)) : '';
$array_fvalue_cat_chart = $array_fvalue_cat_chart ? (', \'' . implode('\', \'', $array_fvalue_cat_chart) . '\'') : '';

$db->query("INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_chart_categories (
    cat_id, cat_code, resource_cover, time_add, time_update, cat_ids, weight, status,
    " . $lang . "_cat_name, " . $lang . "_cat_alias,
    " . $lang . "_cat_absitetitle, " . $lang . "_cat_abintrotext, " . $lang . "_cat_abkeywords, " . $lang . "_cat_abbodytext,
    " . $lang . "_cat_mvsitetitle, " . $lang . "_cat_mvintrotext, " . $lang . "_cat_mvkeywords, " . $lang . "_cat_mvbodytext,
    " . $lang . "_cat_sositetitle, " . $lang . "_cat_sointrotext, " . $lang . "_cat_sokeywords, " . $lang . "_cat_sobodytext" . $array_fname_cat_chart . "
) VALUES
    (1, 'z0', '', 1566143180, 0, '9', 1, 1, 'Việt Nam', 'Viet-Nam', '', '', '', '', '', '', '', '', '', '', '', ''" . $array_fvalue_cat_chart . "),
    (2, 'f9', '', 1566143206, 0, '8', 2, 1, 'Âu Mỹ', 'Au-My', '', '', '', '', '', '', '', '', '', '', '', ''" . $array_fvalue_cat_chart . "),
    (3, 'm5', '', 1566143222, 0, '7', 3, 1, 'Hàn Quốc', 'Han-Quoc', '', '', '', '', '', '', '', '', '', '', '', ''" . $array_fvalue_cat_chart . ");
");
