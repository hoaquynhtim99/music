<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

define('MS_COMMENT_AREA_SONG', 1);
define('MS_COMMENT_AREA_ALBUM', 2);
define('MS_COMMENT_AREA_VIDEO', 3);
define('MS_COMMENT_AREA_PLAYLIST', 4);

require NV_ROOTDIR . '/modules/' . $module_file . '/init.php';

use NukeViet\Music\Nation\DbLoader as NationDbLoader;
use NukeViet\Music\Config;
use NukeViet\Music\Resources;
use NukeViet\Music\Shared\Charts;

// Danh mục
$cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_cat = unserialize($cache);
    $global_array_cat_alias = $global_array_cat[1];
    $global_array_cat = $global_array_cat[0];
} else {
    $array_select_fields = nv_get_cat_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_categories ORDER BY weight ASC";
    $result = $db->query($sql);

    $global_array_cat = [];
    $global_array_cat_alias = [];

    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        if (empty($row['cat_absitetitle'])) {
            $row['cat_absitetitle'] = $row['cat_name'];
        }
        if (empty($row['cat_mvsitetitle'])) {
            $row['cat_mvsitetitle'] = $row['cat_name'];
        }
        $global_array_cat[$row['cat_id']] = $row;
        $global_array_cat_alias[$row['cat_code']] = $row['cat_id'];
    }

    $nv_Cache->setItem($module_name, $cacheFile, serialize(array($global_array_cat, $global_array_cat_alias)), $cacheTTL);
}

// Tất cả các quốc gia trong hệ thống
$cacheFile = NV_LANG_DATA . '_nations_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_nation = unserialize($cache);
    $global_array_nation_alias = $global_array_nation[1];
    $global_array_nation = $global_array_nation[0];
} else {
    $global_array_nation = NationDbLoader::loadAll();
    $global_array_nation_alias = [];

    foreach ($global_array_nation as $row) {
        $global_array_nation_alias[$row->getCode()] = $row->getId();
    }

    $nv_Cache->setItem($module_name, $cacheFile, serialize([$global_array_nation, $global_array_nation_alias]), $cacheTTL);
}

// Parse ngôn ngữ đầy đủ
$cacheFile = NV_LANG_DATA . '_langs_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_languages = unserialize($cache);
} else {
    $global_array_languages = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/langs.ini', true);
    $nv_Cache->setItem($module_name, $cacheFile, serialize($global_array_languages), $cacheTTL);
}

// Chất lượng bài hát
$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_quality_song ORDER BY weight ASC";
$global_array_soquality = $nv_Cache->db($sql, 'quality_id', $module_name);

// Chất lượng video
$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_quality_video ORDER BY weight ASC";
$global_array_mvquality = $nv_Cache->db($sql, 'quality_id', $module_name);

// Loại nghệ sĩ
$global_array_artist_type = [];
$global_array_artist_type[0] = $lang_module['artist_type_singer'];
$global_array_artist_type[1] = $lang_module['artist_type_author'];
$global_array_artist_type[2] = $lang_module['artist_type_all'];

// Các cấu hình fix cứng, sau sẽ thêm vào cấu hình có thể tùy chỉnh được sau
Config::setDetailSongAlbumsNums(12);
Config::setDetailSongVideosNums(12);
Config::setLimitAuthorsDisplayed(3);
Config::setVariousArtistsAuthors('Nhóm tác giả');
Config::setUnknowAuthor('Đang cập nhật');
Config::setUnknowCat('Đang cập nhật');
Config::setShareport('addthis');
Config::setAddthisPubid('addthis');
Config::setUploadsFolder('dataup_default123');
Config::setMsgNolyric('Đang cập nhật');
Config::setGirdSingersNums(36);

Config::setAutoOptimizeAlbumName(true);
Config::setAutoOptimizeArtistName(true);
Config::setAutoOptimizeSongName(true);
Config::setAutoOptimizeVideoName(true);

// FIXME Config cứng BXH tổng lại các mục là 1
// Các thiết lập cứng cho BXH, tương lai cấu hình sau
Config::setChartCommentRate(0.25);
Config::setChartLikeRate(0.25);
Config::setChartShareRate(0.25);
Config::setChartViewRate(0.25);

Config::setChartActive(1);

/**
 * nv_get_album_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_album_select_fields($full_fields = false)
{
    $array_select_fields = ['album_id', 'album_code', 'cat_ids', 'singer_ids', 'release_year', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'time_add', 'time_update', 'status'];
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_album_name default_album_name';
        $array_select_fields[] = $default_language . '_album_alias default_album_alias';
        $array_select_fields[] = $default_language . '_album_description default_album_description';
    }

    $array_lang_fields = ['album_name', 'album_alias', 'album_description'];

    if ($full_fields) {
        $array_select_fields[] = 'uploader_id';
        $array_select_fields[] = 'uploader_name';
        $array_select_fields[] = 'is_official';
        $array_select_fields[] = 'show_inhome';
        $array_select_fields[] = NV_LANG_DATA . '_album_introtext album_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_album_keywords album_keywords';
        if (NV_LANG_DATA != $default_language) {
            $array_select_fields[] = $default_language . '_album_introtext default_album_introtext';
            $array_select_fields[] = $default_language . '_album_keywords default_album_keywords';
        }

        $array_lang_fields[] = 'album_introtext';
        $array_lang_fields[] = 'album_keywords';
    }

    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_video_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_video_select_fields($full_fields = false)
{
    $array_select_fields = array('video_id', 'video_code', 'cat_ids', 'singer_ids', 'author_ids', 'song_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'time_add', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_video_name video_name';
    $array_select_fields[] = NV_LANG_DATA . '_video_alias video_alias';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_video_name default_video_name';
        $array_select_fields[] = $default_language . '_video_alias default_video_alias';
    }

    $array_lang_fields = array('video_name', 'video_alias');

    if ($full_fields) {
        $array_select_fields[] = 'uploader_id';
        $array_select_fields[] = 'uploader_name';
        $array_select_fields[] = 'time_add';
        $array_select_fields[] = 'time_update';
        $array_select_fields[] = 'is_official';
        $array_select_fields[] = 'show_inhome';
        $array_select_fields[] = NV_LANG_DATA . '_video_introtext video_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_video_keywords video_keywords';
        if (NV_LANG_DATA != $default_language) {
            $array_select_fields[] = $default_language . '_video_introtext default_video_introtext';
            $array_select_fields[] = $default_language . '_video_keywords default_video_keywords';
        }

        $array_lang_fields[] = 'video_introtext';
        $array_lang_fields[] = 'video_keywords';
    }

    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_cat_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_cat_select_fields($full_fields = false)
{
    $default_language = Config::getDefaultLang();
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'time_add', 'time_update', 'show_inalbum', 'show_invideo', 'weight', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_cat_name cat_name';
    $array_select_fields[] = NV_LANG_DATA . '_cat_alias cat_alias';
    $array_select_fields[] = NV_LANG_DATA . '_cat_absitetitle cat_absitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abintrotext cat_abintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abkeywords cat_abkeywords';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvsitetitle cat_mvsitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvintrotext cat_mvintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvkeywords cat_mvkeywords';
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_cat_name default_cat_name';
        $array_select_fields[] = $default_language . '_cat_alias default_cat_alias';
        $array_select_fields[] = $default_language . '_cat_absitetitle default_cat_absitetitle';
        $array_select_fields[] = $default_language . '_cat_abintrotext default_cat_abintrotext';
        $array_select_fields[] = $default_language . '_cat_abkeywords default_cat_abkeywords';
        $array_select_fields[] = $default_language . '_cat_mvsitetitle default_cat_mvsitetitle';
        $array_select_fields[] = $default_language . '_cat_mvintrotext default_cat_mvintrotext';
        $array_select_fields[] = $default_language . '_cat_mvkeywords default_cat_mvkeywords';
    }

    $array_lang_fields = array('cat_absitetitle', 'cat_abintrotext', 'cat_abkeywords', 'cat_mvsitetitle', 'cat_mvintrotext', 'cat_mvkeywords');

    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_nation_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_nation_select_fields($full_fields = false)
{
    $default_language = Config::getDefaultLang();
    $array_select_fields = array('nation_id', 'nation_code', 'stat_singers', 'stat_authors', 'time_add', 'time_update', 'status', 'weight');
    $array_select_fields[] = NV_LANG_DATA . '_nation_name nation_name';
    $array_select_fields[] = NV_LANG_DATA . '_nation_alias nation_alias';
    $array_select_fields[] = NV_LANG_DATA . '_nation_introtext nation_introtext';
    $array_select_fields[] = NV_LANG_DATA . '_nation_keywords nation_keywords';
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_nation_name default_nation_name';
        $array_select_fields[] = $default_language . '_nation_alias default_nation_alias';
        $array_select_fields[] = $default_language . '_nation_sitetitle default_nation_sitetitle';
        $array_select_fields[] = $default_language . '_nation_introtext default_nation_introtext';
    }

    $array_lang_fields = array('nation_name', 'nation_alias', 'nation_introtext', 'nation_keywords');

    return array($array_select_fields, $array_lang_fields);
}

/**
 * @param boolean $full_fields
 * @return string[][]
 */
function nv_get_user_playlist_select_fields($full_fields = false)
{
    $array_select_fields = [
        'playlist_id', 'playlist_code', 'resource_avatar', 'resource_cover', 'userid',
        'stat_views', 'stat_likes', 'stat_comments', 'stat_shares',
        'time_add', 'time_update', 'privacy', 'num_songs'
    ];
    $array_select_fields[] = NV_LANG_DATA . '_playlist_name playlist_name';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_playlist_name default_playlist_name';
    }

    $array_lang_fields = ['playlist_name'];

    if ($full_fields) {
        $array_select_fields[] = NV_LANG_DATA . '_playlist_introtext playlist_introtext';
        if (NV_LANG_DATA != $default_language) {
            $array_select_fields[] = $default_language . '_playlist_introtext default_playlist_introtext';
        }

        $array_lang_fields[] = 'playlist_introtext';
    }

    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_detail_album_link()
 *
 * @param mixed $album
 * @param array $singers
 * @param bool $amp
 * @param string $query_string
 * @return
 */
function nv_get_detail_album_link($album, $singers = [], $amp = true, $query_string = '')
{
    global $global_config, $module_info;
    $num_singers = sizeof($singers);
    if ($num_singers > Config::getLimitSingersDisplayed()) {
        $singer_alias = '-' . strtolower(change_alias(Config::getVariousArtists()));
    } elseif ($num_singers > 0) {
        $singer_alias = [];
        foreach ($singers as $singer) {
            $singer_alias[] = $singer['artist_alias'];
        }
        $singer_alias = '-' . implode('-', $singer_alias);
    } else {
        $singer_alias = '';
    }
    return ($amp ? Resources::getModFullLinkEncode() : Resources::getModFullLink()) . Config::getOpAliasPrefix()->getAlbum() . $album['album_alias'] . $singer_alias . '-' . Config::getCodePrefix()->getAlbum() . $album['album_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
}

/**
 * @param array $playlist
 * @param boolean $amp
 * @param string $query_string
 * @return string
 */
function nv_get_detail_playlist_link($playlist, $amp = true, $query_string = '')
{
    global $global_config;
    return ($amp ? Resources::getModFullLinkEncode() : Resources::getModFullLink()) . Config::getOpAliasPrefix()->getPlaylist() . (isset($playlist['playlist_alias']) ? $playlist['playlist_alias'] : change_alias($playlist['playlist_name'])) . '-' . Config::getCodePrefix()->getPlaylist() . $playlist['playlist_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
}

/**
 * nv_get_detail_video_link()
 *
 * @param mixed $video
 * @param array $singers
 * @param bool $amp
 * @param string $query_string
 * @return
 */
function nv_get_detail_video_link($video, $singers = [], $amp = true, $query_string = '')
{
    global $global_config, $module_info;
    $num_singers = sizeof($singers);
    if ($num_singers > Config::getLimitSingersDisplayed()) {
        $singer_alias = '-' . strtolower(change_alias(Config::getVariousArtists()));
    } elseif ($num_singers > 0) {
        $singer_alias = [];
        foreach ($singers as $singer) {
            $singer_alias[] = $singer['artist_alias'];
        }
        $singer_alias = '-' . implode('-', $singer_alias);
    } else {
        $singer_alias = '';
    }
    return ($amp ? Resources::getModFullLinkEncode() : Resources::getModFullLink()) . Config::getOpAliasPrefix()->getVideo() . $video['video_alias'] . $singer_alias . '-' . Config::getCodePrefix()->getVideo() . $video['video_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
}

/**
 * msGetModuleSetupLangs()
 * Danh sách các ngôn ngữ đã cài module
 * @return
 */
function msGetModuleSetupLangs()
{
    global $db_config, $db, $module_data, $nv_Cache;

    $module_name = 'settings';
    $cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
    $cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

    if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
        $array_lang_module_setup = unserialize($cache);
    } else {
        $array_lang_module_setup = [];

        // Xác định các ngôn ngữ đã cài đặt
        $_sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1";
        $_result = $db->query($_sql);
        $array_lang_setup = [];
        while ($_row = $_result->fetch()) {
            $array_lang_setup[$_row['lang']] = $_row['lang'];
        }

        // Xác định các ngôn ngữ đã cài module
        foreach ($array_lang_setup as $_lang) {
            $is_setup = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $_lang . "_modules WHERE module_data=" . $db->quote($module_data))->fetchColumn();
            if ($is_setup) {
                $array_lang_module_setup[$_lang] = $_lang;
            }
        }

        $nv_Cache->setItem($module_name, $cacheFile, serialize($array_lang_module_setup), $cacheTTL);
    }

    return $array_lang_module_setup;
}

/**
 * @param integer $playlist_id
 */
function msUpdatePlaylistSongCountWeight($playlist_id)
{
    global $db;

    $sql = "SELECT song_id FROM " . Resources::getTablePrefix() . "_user_playlists_data WHERE playlist_id=" . $playlist_id . " ORDER BY weight ASC";
    $song_ids = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);

    // Cập nhật lại thứ tự
    $weight = 0;
    foreach ($song_ids as $song_id) {
        $weight++;
        $sql = "UPDATE " . Resources::getTablePrefix() . "_user_playlists_data SET weight=" . $weight . " WHERE playlist_id=" . $playlist_id . " AND song_id=" . $song_id;
        $db->query($sql);
    }

    // Cập nhật thống kê số bài hát
    $sql = "SELECT COUNT(tb1.song_id) FROM " . Resources::getTablePrefix() . "_user_playlists_data tb1
    INNER JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.song_id=tb2.song_id WHERE tb1.playlist_id=" . $playlist_id . " AND tb2.status=1";
    $num_songs = $db->query($sql)->fetchColumn();
    $num_songs = intval($num_songs);

    $db->query("UPDATE " . Resources::getTablePrefix() . "_user_playlists SET num_songs=" . $num_songs . " WHERE playlist_id=" . $playlist_id);
}

/*
 * Xử lý BXH
 * Vào đầu mỗi tuần, khi chưa xử lý BXH thì bắt đầu xử lý.
 */
if (Config::getChartActive()) {
    $chart_current_time = Charts::getCurrentTime();
    if ($chart_current_time != Config::getChartCurrentTime()) {
        // BXH của tuần trước
        $chart_time = $chart_current_time - (7 * 86400);
        $chart_week = date('W', $chart_time);
        $chart_year = date('Y', $chart_time);

        // Lặp 3 loại BXH và từng thể loại của mỗi cái
        $array_chart_object = ['song', 'album', 'video'];
        foreach ($array_chart_object as $chart_object) {
            foreach ($global_array_cat_chart as $_tmp) {
                $array_insert = [];

                // Lấy 40 đối tượng có điểm cao nhất
                $sql = "SELECT * FROM " . Resources::getTablePrefix() . "_chart_tmps WHERE chart_time=" . $chart_time . "
                AND object_name='" . $chart_object . "' AND cat_id=" . $_tmp['cat_id'] . "
                ORDER BY summary_scores DESC LIMIT 0,40";
                $result = $db->query($sql);
                $stt = 0;
                while ($row = $result->fetch()) {
                    $stt++;
                    $array_insert[] = "(
                        " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $_tmp['cat_id'] . ",
                        '" . $chart_object . "', " . $row['object_id'] . ",
                        " . $row['view_hits'] . ", " . Config::getChartViewRate() . ",
                        " . $row['like_hits'] . ", " . Config::getChartLikeRate() . ",
                        " . $row['comment_hits'] . ", " . Config::getChartCommentRate() . ",
                        " . $row['share_hits'] . ", " . Config::getChartShareRate() . ",
                        " . $row['summary_scores'] . ", " . $stt . "
                    )";
                }

                // Lưu vào BXH chính thức
                if (!empty($array_insert)) {
                    $sql = "INSERT INTO " . Resources::getTablePrefix() . "_charts (
                        chart_week, chart_year, chart_time, cat_id, object_name, object_id, view_hits, view_rate, like_hits, like_rate, comment_hits, comment_rate,
                        share_hits, share_rate, summary_scores, summary_order
                    ) VALUES " . implode(', ', $array_insert);
                    $db->query($sql);
                }
            }
        }

        // Xóa hết dữ liệu tạm
        $sql = "TRUNCATE " . Resources::getTablePrefix() . "_chart_tmps";
        $db->query($sql);

        // Cập nhật lại CSDL cấu hình
        $sql = "UPDATE " . Resources::getTablePrefix() . "_config SET config_value_default=" . $db->quote($chart_current_time) . " WHERE config_name='current_chart_time'";
        $db->query($sql);

        $nv_Cache->delMod($module_name);
        unset($array_chart_object, $array_insert);
    }
    unset($chart_current_time);
}
