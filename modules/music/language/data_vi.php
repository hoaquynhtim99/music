<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
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
$array_lang_setup = array();
while ($_row = $_result->fetch()) {
    $array_lang_setup[$_row['lang']] = $_row['lang'];
}

// Xác định các ngôn ngữ đã cài module
$array_lang_module_setup = array();
foreach ($array_lang_setup as $_lang) {
    $is_setup = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $_lang . "_modules WHERE module_data=" . $db->quote($module_data))->fetchColumn();
    if ($is_setup) {
        $array_lang_module_setup[$_lang] = $_lang;
    }
}

$array_quality_song = array(
    1 => array('quality_name' => '128kbps', 'quality_alias' => '128kbps'),
    2 => array('quality_name' => '320kbps', 'quality_alias' => '320kbps'),
    3 => array('quality_name' => 'Lossless', 'quality_alias' => 'Lossless')
);
$array_quality_video = array(
    1 => array('quality_name' => '360p', 'quality_alias' => '360p'),
    2 => array('quality_name' => '480p', 'quality_alias' => '480p'),
    3 => array('quality_name' => '720p', 'quality_alias' => '720p'),
    4 => array('quality_name' => '1080p', 'quality_alias' => '1080p')
);

$array_fields_song_name = '';
$array_fields_song_value = array();
$array_fields_video_name = '';
$array_fields_video_value = array();

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
