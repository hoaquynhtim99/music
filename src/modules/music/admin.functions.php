<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;
use NukeViet\Music\Resources;

define('NV_IS_MUSIC_ADMIN', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

define('NV_ADMIN_MOD_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_ADMIN_MOD_LINK_AMP', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_ADMIN_MOD_FULLLINK', NV_ADMIN_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_ADMIN_MOD_FULLLINK_AMP', NV_ADMIN_MOD_LINK_AMP . '&amp;' . NV_OP_VARIABLE . '=');

/**
 * msGetCurrentUploadFolder()
 *
 * @param mixed $area
 * @param string $child
 * @return
 */
function msGetCurrentUploadFolder($area, $child = '')
{
    global $module_upload, $db;

    $folder_lev1 = '';
    $folder_lev2 = date('Y_m');
    if ($area == 'album') {
        if ($child == 'cover') {
            $folder_lev1 = 'albums_cover';
        } else {
            $folder_lev1 = 'albums';
        }
    } elseif ($area == 'artist') {
        if ($child == 'cover') {
            $folder_lev1 = 'artists_cover';
        } else {
            $folder_lev1 = 'artists';
        }
    } elseif ($area == 'song') {
        if ($child == 'cover') {
            $folder_lev1 = 'songs_cover';
        } else {
            $folder_lev1 = 'songs';
        }
    } elseif ($area == 'video') {
        if ($child == 'cover') {
            $folder_lev1 = 'videos_cover';
        } else {
            $folder_lev1 = 'videos';
        }
    } elseif ($area == 'lyric') {
        $folder_lev1 = 'lyric';
    } elseif ($area == 'data') {
        $folder_lev1 = Config::getUploadsFolder();
    }
    $upload_path = $upload_path_current = NV_UPLOADS_DIR . '/' . $module_upload;
    if (!empty($folder_lev1)) {
        $folder_path = [$folder_lev1, $folder_lev2];
        $i = 0;
        foreach ($folder_path as $path) {
            $i++;
            if (!file_exists(NV_ROOTDIR . '/' . $upload_path_current . '/' . $path)) {
                // Tạo thư mục mới
                $mkdir = nv_mkdir(NV_ROOTDIR . '/' . $upload_path_current, $path);
                if ($mkdir[0] > 0) {
                    try {
                        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . $upload_path_current . '/' . $path . "', 0)");
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
                    }
                }
            }
            if (file_exists(NV_ROOTDIR . '/' . $upload_path_current . '/' . $path)) {
                $upload_path_current .= '/' . $path;
                if ($i == 1) {
                    $upload_path .= '/' . $path;
                }
            } else {
                break;
            }
        }
    }

    return array($upload_path, $upload_path_current);
}

/**
 * Cập nhật các thống kê của quốc gia
 *
 * @param mixed $nation_id
 * @return void
 */
function msUpdateNationStat($nation_id)
{
    global $db;

    // Số ca sĩ
    $db->query("UPDATE " . Resources::getTablePrefix() . "_nations SET stat_singers=(SELECT COUNT(artist_id) FROM " . Resources::getTablePrefix() . "_artists WHERE (artist_type=0 OR artist_type=2) AND nation_id=" . $nation_id . " AND status=1) WHERE nation_id=" . $nation_id);

    // Số nhạc sĩ
    $db->query("UPDATE " . Resources::getTablePrefix() . "_nations SET stat_authors=(SELECT COUNT(artist_id) FROM " . Resources::getTablePrefix() . "_artists WHERE (artist_type=1 OR artist_type=2) AND nation_id=" . $nation_id . " AND status=1) WHERE nation_id=" . $nation_id);
}

/**
 * Cập nhật các thống kê của thể loại
 *
 * @param integer $cat_id
 */
function msUpdateCatStat($cat_id)
{
    global $db;

    // Số albums
    $db->query("UPDATE " . Resources::getTablePrefix() . "_categories SET stat_albums=(SELECT COUNT(album_id) FROM " . Resources::getTablePrefix() . "_albums WHERE FIND_IN_SET(" . $cat_id . ", cat_ids) AND status=1) WHERE cat_id=" . $cat_id);

    // Số bài hát
    $db->query("UPDATE " . Resources::getTablePrefix() . "_categories SET stat_songs=(SELECT COUNT(song_id) FROM " . Resources::getTablePrefix() . "_songs WHERE FIND_IN_SET(" . $cat_id . ", cat_ids) AND status=1) WHERE cat_id=" . $cat_id);

    // Số video
    $db->query("UPDATE " . Resources::getTablePrefix() . "_categories SET stat_videos=(SELECT COUNT(video_id) FROM " . Resources::getTablePrefix() . "_videos WHERE FIND_IN_SET(" . $cat_id . ", cat_ids) AND status=1) WHERE cat_id=" . $cat_id);
}

/**
 * Cập nhật các thống kê của nghệ sĩ (ca sĩ, nhạc sĩ)
 *
 * @param integer $artist_id
 * @param boolean $is_singer
 */
function msUpdateArtistStat($artist_id, $is_singer = true)
{
    global $db;

    if ($is_singer) {
        // Số albums
        $db->query("UPDATE " . Resources::getTablePrefix() . "_artists SET stat_singer_albums=(SELECT COUNT(album_id) FROM " . Resources::getTablePrefix() . "_albums WHERE FIND_IN_SET(" . $artist_id . ", singer_ids) AND status=1) WHERE artist_id=" . $artist_id);

        // Số bài hát
        $db->query("UPDATE " . Resources::getTablePrefix() . "_artists SET stat_singer_songs=(SELECT COUNT(song_id) FROM " . Resources::getTablePrefix() . "_songs WHERE FIND_IN_SET(" . $artist_id . ", singer_ids) AND status=1) WHERE artist_id=" . $artist_id);

        // Số video
        $db->query("UPDATE " . Resources::getTablePrefix() . "_artists SET stat_singer_videos=(SELECT COUNT(video_id) FROM " . Resources::getTablePrefix() . "_videos WHERE FIND_IN_SET(" . $artist_id . ", singer_ids) AND status=1) WHERE artist_id=" . $artist_id);
    } else {
        // Số bài hát
        $db->query("UPDATE " . Resources::getTablePrefix() . "_artists SET stat_author_songs=(SELECT COUNT(song_id) FROM " . Resources::getTablePrefix() . "_songs WHERE FIND_IN_SET(" . $artist_id . ", author_ids) AND status=1) WHERE artist_id=" . $artist_id);

        // Số video
        $db->query("UPDATE " . Resources::getTablePrefix() . "_artists SET stat_author_videos=(SELECT COUNT(video_id) FROM " . Resources::getTablePrefix() . "_videos WHERE FIND_IN_SET(" . $artist_id . ", author_ids) AND status=1) WHERE artist_id=" . $artist_id);
    }
}

/**
 * Cập nhật các bài hát để lấy random
 *
 * @param array|integer $cat_ids
 */
function msUpdateRandomSongs($cat_ids)
{
    global $db;

    if (!is_array($cat_ids)) {
        $cat_ids = [$cat_ids];
    }

    foreach ($cat_ids as $cat_id) {
        // Xóa các bài hát trong list của thể loại này mà không còn phù hợp điều kiện (status, cat_ids, is_official) hoặc bài hát bị xóa
        $sql = "DELETE tb1 FROM " . Resources::getTablePrefix() . "_songs_random tb1 LEFT JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.song_id=tb2.song_id WHERE
        tb1.cat_id=" . $cat_id . " AND (
            tb2.song_id IS NULL OR (tb2.status!=1 OR tb2.is_official!=1 OR NOT FIND_IN_SET(" . $cat_id . ", tb2.cat_ids))
        )";
        $check = $db->exec($sql);

        /*
         * Xác định ID bài hát mới nhất thứ 1000 của thể loại này
         * ID nhỏ nhất là bài hát cũ nhất
         */
        $sql = "SELECT MIN(song_id) FROM (SELECT song_id FROM " . Resources::getTablePrefix() . "_songs WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) ORDER BY time_add DESC LIMIT 0, 1000) AS tmptable";
        $oldest_song_id = $db->query($sql)->fetchColumn();

        if ($oldest_song_id) {
            // Xóa hết các bài hát cũ hơn trong list. ID nhỏ hơn tức cũ hơn
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_songs_random WHERE cat_id=" . $cat_id . " AND song_id<" . $oldest_song_id;
            $check = $db->exec($sql);
        }

        // Xác định số bài hát random còn lại trong list
        $sql = "SELECT COUNT(song_id) FROM " . Resources::getTablePrefix() . "_songs_random WHERE cat_id=" . $cat_id;
        $num_songs_left = intval($db->query($sql)->fetchColumn());

        if ($num_songs_left < 1000) {
            $num_songs_add = 1000 - $num_songs_left;

            // Thêm vào cho đủ 1000 bài mới khác vô thể loại này
            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_songs_random (song_id, cat_id) SELECT song_id, " . $cat_id . " FROM
            " . Resources::getTablePrefix() . "_songs WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) AND song_id NOT IN(
                SELECT song_id FROM " . Resources::getTablePrefix() . "_songs_random WHERE cat_id=" . $cat_id . "
            ) ORDER BY time_add DESC LIMIT 0, " . $num_songs_add;
            $check = $db->exec($sql);
        }
    }
}

/**
 * Cập nhật các video để lấy random
 *
 * @param array|integer $cat_ids
 */
function msUpdateRandomVideos($cat_ids)
{
    global $db;

    if (!is_array($cat_ids)) {
        $cat_ids = [$cat_ids];
    }

    foreach ($cat_ids as $cat_id) {
        // Xóa các video trong list của thể loại này mà không còn phù hợp điều kiện (status, cat_ids, is_official) hoặc video bị xóa
        $sql = "DELETE tb1 FROM " . Resources::getTablePrefix() . "_videos_random tb1 LEFT JOIN " . Resources::getTablePrefix() . "_videos tb2 ON tb1.video_id=tb2.video_id WHERE
        tb1.cat_id=" . $cat_id . " AND (
            tb2.video_id IS NULL OR (tb2.status!=1 OR tb2.is_official!=1 OR NOT FIND_IN_SET(" . $cat_id . ", tb2.cat_ids))
        )";
        $check = $db->exec($sql);

        /*
         * Xác định ID video mới nhất thứ 1000 của thể loại này
         * ID nhỏ nhất là video cũ nhất
         */
        $sql = "SELECT MIN(video_id) FROM (SELECT video_id FROM " . Resources::getTablePrefix() . "_videos WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) ORDER BY time_add DESC LIMIT 0, 1000) AS tmptable";
        $oldest_video_id = $db->query($sql)->fetchColumn();

        if ($oldest_video_id) {
            // Xóa hết các video cũ hơn trong list. ID nhỏ hơn tức cũ hơn
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_videos_random WHERE cat_id=" . $cat_id . " AND video_id<" . $oldest_video_id;
            $check = $db->exec($sql);
        }

        // Xác định số video random còn lại trong list
        $sql = "SELECT COUNT(video_id) FROM " . Resources::getTablePrefix() . "_videos_random WHERE cat_id=" . $cat_id;
        $num_videos_left = intval($db->query($sql)->fetchColumn());

        if ($num_videos_left < 1000) {
            $num_videos_add = 1000 - $num_videos_left;

            // Thêm vào cho đủ 1000 bài mới khác vô thể loại này
            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_videos_random (video_id, cat_id) SELECT video_id, " . $cat_id . " FROM
            " . Resources::getTablePrefix() . "_videos WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) AND video_id NOT IN(
                SELECT video_id FROM " . Resources::getTablePrefix() . "_videos_random WHERE cat_id=" . $cat_id . "
            ) ORDER BY time_add DESC LIMIT 0, " . $num_videos_add;
            $check = $db->exec($sql);
        }
    }
}

/**
 * Cập nhật các album để lấy random
 *
 * @param array|integer $cat_ids
 */
function msUpdateRandomAlbums($cat_ids)
{
    global $db;

    if (!is_array($cat_ids)) {
        $cat_ids = [$cat_ids];
    }

    foreach ($cat_ids as $cat_id) {
        // Xóa các album trong list của thể loại này mà không còn phù hợp điều kiện (status, cat_ids, is_official) hoặc album bị xóa
        $sql = "DELETE tb1 FROM " . Resources::getTablePrefix() . "_albums_random tb1 LEFT JOIN " . Resources::getTablePrefix() . "_albums tb2 ON tb1.album_id=tb2.album_id WHERE
        tb1.cat_id=" . $cat_id . " AND (
            tb2.album_id IS NULL OR (tb2.status!=1 OR tb2.is_official!=1 OR NOT FIND_IN_SET(" . $cat_id . ", tb2.cat_ids))
        )";
        $check = $db->exec($sql);

        /*
         * Xác định ID album mới nhất thứ 1000 của thể loại này
         * ID nhỏ nhất là album cũ nhất
         */
        $sql = "SELECT MIN(album_id) FROM (SELECT album_id FROM " . Resources::getTablePrefix() . "_albums WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) ORDER BY time_add DESC LIMIT 0, 1000) AS tmptable";
        $oldest_album_id = $db->query($sql)->fetchColumn();

        if ($oldest_album_id) {
            // Xóa hết các album cũ hơn trong list. ID nhỏ hơn tức cũ hơn
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_albums_random WHERE cat_id=" . $cat_id . " AND album_id<" . $oldest_album_id;
            $check = $db->exec($sql);
        }

        // Xác định số album random còn lại trong list
        $sql = "SELECT COUNT(album_id) FROM " . Resources::getTablePrefix() . "_albums_random WHERE cat_id=" . $cat_id;
        $num_albums_left = intval($db->query($sql)->fetchColumn());

        if ($num_albums_left < 1000) {
            $num_albums_add = 1000 - $num_albums_left;

            // Thêm vào cho đủ 1000 bài mới khác vô thể loại này
            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_albums_random (album_id, cat_id) SELECT album_id, " . $cat_id . " FROM
            " . Resources::getTablePrefix() . "_albums WHERE status=1 AND is_official=1 AND FIND_IN_SET(" . $cat_id . ", cat_ids) AND album_id NOT IN(
                SELECT album_id FROM " . Resources::getTablePrefix() . "_albums_random WHERE cat_id=" . $cat_id . "
            ) ORDER BY time_add DESC LIMIT 0, " . $num_albums_add;
            $check = $db->exec($sql);
        }
    }
}

/**
 * Cập nhật số bài hát của album từ ID các bài hát.
 * Xảy ra khi thay đổi trạng thái bài hát, xóa bài hát
 *
 * @param array|int $song_ids
 */
function msUpdateNumSongOfAlbumFromSongs($song_ids)
{
    if (!is_array($song_ids)) {
        $song_ids = [$song_ids];
    }

    global $db;

    // Xác định danh sách các album từ các bài hát này
    $sql = "SELECT DISTINCT album_id FROM " . Resources::getTablePrefix() . "_albums_data WHERE song_id IN(" . implode(',', $song_ids) . ")";
    $album_ids = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

    // Lấy và cập nhật số bài hát cho từng albums
    foreach ($album_ids as $album_id) {
        $sql = "SELECT COUNT(tb1.song_id) FROM " . Resources::getTablePrefix() . "_albums_data tb1
        INNER JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.song_id=tb2.song_id WHERE tb1.album_id=" . $album_id . " AND tb2.status=1";
        $num_songs = $db->query($sql)->fetchColumn();
        $num_songs = intval($num_songs);

        $db->query("UPDATE " . Resources::getTablePrefix() . "_albums SET num_songs=" . $num_songs . " WHERE album_id=" . $album_id);
    }
}

/**
 * Cập nhật số bài hát của playlist từ ID các bài hát.
 * Xảy ra khi thay đổi trạng thái bài hát, xóa bài hát
 *
 * @param array|int $song_ids
 */
function msUpdateNumSongOfPlaylistFromSongs($song_ids)
{
    if (!is_array($song_ids)) {
        $song_ids = [$song_ids];
    }

    global $db;

    // Xác định danh sách các album từ các bài hát này
    $sql = "SELECT DISTINCT playlist_id FROM " . Resources::getTablePrefix() . "_user_playlists_data WHERE song_id IN(" . implode(',', $song_ids) . ")";
    $playlist_ids = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

    // Lấy và cập nhật số bài hát cho từng playlist
    foreach ($playlist_ids as $playlist_id) {
        $sql = "SELECT COUNT(tb1.song_id) FROM " . Resources::getTablePrefix() . "_user_playlists_data tb1
        INNER JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.song_id=tb2.song_id WHERE tb1.playlist_id=" . $playlist_id . " AND tb2.status=1";
        $num_songs = $db->query($sql)->fetchColumn();
        $num_songs = intval($num_songs);

        $db->query("UPDATE " . Resources::getTablePrefix() . "_user_playlists SET num_songs=" . $num_songs . " WHERE playlist_id=" . $playlist_id);
    }
}
