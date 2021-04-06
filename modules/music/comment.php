<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!defined('MS_COMMENT_AREA_SONG')) {
    define('MS_COMMENT_AREA_SONG', 1);
    define('MS_COMMENT_AREA_ALBUM', 2);
    define('MS_COMMENT_AREA_VIDEO', 3);
    define('MS_COMMENT_AREA_PLAYLIST', 4);
}

if (!function_exists('msGetNumComments')) {
    /**
     * msGetNumComments()
     *
     * @param mixed $id
     * @param mixed $area
     * @param mixed $module
     * @return
     */
    function msGetNumComments($id, $area, $module) {
        global $global_config, $db_config, $db;
        $numComments = 0;
        foreach ($global_config['allow_sitelangs'] as $lang) {
            try {
                $numComments += $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang . '_comment WHERE module= ' . $db->quote($module) . ' AND id=' . $id . ' AND area=' . $area . ' AND status=1')->fetchColumn();
            } catch (Exception $exp) {
                trigger_error($exp->getMessage());
            }
        }
        return $numComments;
    }
}

if (defined('NV_ADMIN')) {
    $id = $row['id'];
    $area = $row['area'];
}

// Xác định số comment
$numComments = msGetNumComments($id, $area, $module);

// Thực hiện cập nhật lại
if ($area == MS_COMMENT_AREA_SONG) {
    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $mod_info['module_data'] . '_songs SET stat_comments=' . $numComments . ' WHERE song_id=' . $id;
        $db->query($sql);
    } catch (Exception $exp) {
        trigger_error($exp->getMessage());
    }
} elseif ($area == MS_COMMENT_AREA_ALBUM) {
    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $mod_info['module_data'] . '_albums SET stat_comments=' . $numComments . ' WHERE album_id=' . $id;
        $db->query($sql);
    } catch (Exception $exp) {
        trigger_error($exp->getMessage());
    }
} elseif ($area == MS_COMMENT_AREA_VIDEO) {
    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $mod_info['module_data'] . '_videos SET stat_comments=' . $numComments . ' WHERE video_id=' . $id;
        $db->query($sql);
    } catch (Exception $exp) {
        trigger_error($exp->getMessage());
    }
} elseif ($area == MS_COMMENT_AREA_PLAYLIST) {
    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $mod_info['module_data'] . '_user_playlists SET stat_comments=' . $numComments . ' WHERE playlist_id=' . $id;
        $db->query($sql);
    } catch (Exception $exp) {
        trigger_error($exp->getMessage());
    }
}
