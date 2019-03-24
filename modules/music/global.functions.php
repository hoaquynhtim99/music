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

/*
register_shutdown_function("fatal_handler");

function fatal_handler() {
    $error = error_get_last();
    if ($error !== NULL) {
        echo("<pre><code>");
        print_r($error);
        die("</code></pre>");
    }
}
*/

require NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php';

use NukeViet\Music\Nation\DbLoader as NationDbLoader;
use NukeViet\Music\Config;
use NukeViet\Music\Resources;

define('NV_MOD_TABLE', $db_config['prefix'] . '_' . $module_data);

define('NV_MOD_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_LINK_AMP', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_FULLLINK', NV_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_MOD_FULLLINK_AMP', NV_MOD_LINK_AMP . '&amp;' . NV_OP_VARIABLE . '=');

define('MS_COMMENT_AREA_SONG', 1);
define('MS_COMMENT_AREA_ALBUM', 2);
define('MS_COMMENT_AREA_VIDEO', 3);

Resources::setLangData(NV_LANG_DATA);
Resources::setLangInterface(NV_LANG_INTERFACE);
Resources::setDb($db);
Resources::setDbPrefix($db_config['prefix']);
Resources::setTablePrefix(NV_MOD_TABLE);

$array_alphabets = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$global_array_rule = [];
$global_array_rule['nation_code'] = '/^[a-zA-Z0-9]{4}$/';

// Cấu hình module
$cacheFile = NV_LANG_DATA . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    Config::loadConfig(unserialize($cache));
} else {
    $nv_Cache->setItem($module_name, $cacheFile, serialize(Config::getAllConfig()), $cacheTTL);
}

// Danh mục
$cacheFile = NV_LANG_DATA . '_cats_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_cat = unserialize($cache);
    $global_array_cat_alias = $global_array_cat[1];
    $global_array_cat = $global_array_cat[0];
} else {
    $array_select_fields = nv_get_cat_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_categories ORDER BY weight ASC";
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
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_quality_song ORDER BY weight ASC";
$global_array_soquality = $nv_Cache->db($sql, 'quality_id', $module_name);

// Chất lượng video
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_quality_video ORDER BY weight ASC";
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

/**
 * nv_get_album_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_album_select_fields($full_fields = false)
{
    $array_select_fields = array('album_id', 'album_code', 'cat_ids', 'singer_ids', 'release_year', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'time_add', 'time_update', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_album_name default_album_name';
        $array_select_fields[] = $default_language . '_album_alias default_album_alias';
        $array_select_fields[] = $default_language . '_album_description default_album_description';
    }

    $array_lang_fields = array('album_name', 'album_alias', 'album_description');

    if ($full_fields) {
        $array_select_fields[] = 'uploader_id';
        $array_select_fields[] = 'uploader_name';
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
 * nv_get_song_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_song_select_fields($full_fields = false)
{
    $array_select_fields = array('song_id', 'song_code', 'cat_ids', 'singer_ids', 'author_ids', 'album_ids', 'video_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_song_name song_name';
    $array_select_fields[] = NV_LANG_DATA . '_song_alias song_alias';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_song_name default_song_name';
        $array_select_fields[] = $default_language . '_song_alias default_song_alias';
    }

    $array_lang_fields = array('song_name', 'song_alias');

    if ($full_fields) {
        $array_select_fields[] = 'uploader_id';
        $array_select_fields[] = 'uploader_name';
        $array_select_fields[] = 'time_add';
        $array_select_fields[] = 'time_update';
        $array_select_fields[] = 'is_official';
        $array_select_fields[] = NV_LANG_DATA . '_song_introtext song_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_song_keywords song_keywords';
        if (NV_LANG_DATA != $default_language) {
            $array_select_fields[] = $default_language . '_song_introtext default_song_introtext';
            $array_select_fields[] = $default_language . '_song_keywords default_song_keywords';
        }

        $array_lang_fields[] = 'song_introtext';
        $array_lang_fields[] = 'song_keywords';
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
    $array_select_fields = array('video_id', 'video_code', 'cat_ids', 'singer_ids', 'author_ids', 'song_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'status');
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
 * nv_get_artist_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_artist_select_fields($full_fields = false)
{
    $array_select_fields = array('artist_id', 'artist_code', 'artist_type', 'artist_birthday', 'artist_birthday_lev', 'nation_id', 'resource_avatar', 'resource_cover', 'stat_singer_albums', 'stat_singer_songs', 'stat_singer_videos', 'stat_author_songs', 'stat_author_videos', 'time_add', 'time_update', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_artist_name artist_name';
    $array_select_fields[] = NV_LANG_DATA . '_artist_alias artist_alias';
    $array_select_fields[] = NV_LANG_DATA . '_artist_realname artist_realname';
    $array_select_fields[] = NV_LANG_DATA . '_singer_nickname singer_nickname';
    $array_select_fields[] = NV_LANG_DATA . '_author_nickname author_nickname';
    $default_language = Config::getDefaultLang();
    if (NV_LANG_DATA != $default_language) {
        $array_select_fields[] = $default_language . '_artist_name default_artist_name';
        $array_select_fields[] = $default_language . '_artist_alias default_artist_alias';
        $array_select_fields[] = $default_language . '_artist_realname default_artist_realname';
        $array_select_fields[] = $default_language . '_singer_nickname default_singer_nickname';
        $array_select_fields[] = $default_language . '_author_nickname default_author_nickname';
    }

    $array_lang_fields = array('artist_name', 'artist_alias', 'artist_realname', 'singer_nickname', 'author_nickname');

    if ($full_fields) {
        $array_select_fields[] = 'show_inhome';
        $array_select_fields[] = NV_LANG_DATA . '_artist_hometown artist_hometown';
        $array_select_fields[] = NV_LANG_DATA . '_singer_prize singer_prize';
        $array_select_fields[] = NV_LANG_DATA . '_singer_info singer_info';
        $array_select_fields[] = NV_LANG_DATA . '_singer_introtext singer_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_singer_keywords singer_keywords';
        $array_select_fields[] = NV_LANG_DATA . '_author_prize author_prize';
        $array_select_fields[] = NV_LANG_DATA . '_author_info author_info';
        $array_select_fields[] = NV_LANG_DATA . '_author_introtext author_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_author_keywords author_keywords';
        if (NV_LANG_DATA != $default_language) {
            $array_select_fields[] = $default_language . '_artist_hometown default_artist_hometown';
            $array_select_fields[] = $default_language . '_singer_prize default_singer_prize';
            $array_select_fields[] = $default_language . '_singer_info default_singer_info';
            $array_select_fields[] = $default_language . '_singer_introtext default_singer_introtext';
            $array_select_fields[] = $default_language . '_singer_keywords default_singer_keywords';
            $array_select_fields[] = $default_language . '_author_prize default_author_prize';
            $array_select_fields[] = $default_language . '_author_info default_author_info';
            $array_select_fields[] = $default_language . '_author_introtext default_author_introtext';
            $array_select_fields[] = $default_language . '_author_keywords default_author_keywords';
        }

        $array_lang_fields[] = 'artist_hometown';
        $array_lang_fields[] = 'singer_prize';
        $array_lang_fields[] = 'singer_info';
        $array_lang_fields[] = 'singer_introtext';
        $array_lang_fields[] = 'singer_keywords';
        $array_lang_fields[] = 'author_prize';
        $array_lang_fields[] = 'author_info';
        $array_lang_fields[] = 'author_introtext';
        $array_lang_fields[] = 'author_keywords';
    }

    return array($array_select_fields, $array_lang_fields);
}

/**
 * nv_get_artists()
 *
 * @param mixed $array_ids
 * @param bool $full_info
 * @param bool $get_by_code
 * @return
 */
function nv_get_artists($array_ids, $full_info = false, $get_by_code = false)
{
    global $db;

    $array_artists = [];

    if (!is_array($array_ids)) {
        $array_ids = array($array_ids);
        $return_one = true;
    } else {
        $return_one = false;
    }

    $array_ids = array_filter(array_unique($array_ids));

    if (!empty($array_ids)) {
        $array_select_fields = nv_get_artist_select_fields((bool)$full_info);
        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_artists WHERE status=1 AND ";
        if (!$get_by_code) {
            $sql .= "artist_id IN(" . implode(',', $array_ids) . ")";
        } else {
            $sql .= "artist_code IN('" . implode("', '", $array_ids) . "')";
        }
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singer_link'] = nv_get_view_singer_link($row);

            if ($return_one) {
                return $row;
            }

            $array_artists[$row['artist_id']] = $row;
        }
    }

    return $array_artists;
}

/**
 * nv_get_view_singer_link()
 *
 * @param mixed $singer
 * @param bool $amp
 * @param string $tab
 * @return
 */
function nv_get_view_singer_link($singer, $amp = true, $tab = '')
{
    global $global_config, $module_info;
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $module_info['alias']['view-singer'] . '/' . $singer['artist_alias'] . '-' . Config::getCodePrefix()->getSinger() . $singer['artist_code'] . ($tab ? '/' . Config::getSingerTabsAlias()->getTabByKey($tab) : $global_config['rewrite_exturl']);
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
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . Config::getOpAliasPrefix()->getAlbum() . $album['album_alias'] . $singer_alias . '-' . Config::getCodePrefix()->getAlbum() . $album['album_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
}

/**
 * nv_get_detail_song_link()
 *
 * @param mixed $song
 * @param array $singers
 * @param bool $amp
 * @param string $query_string
 * @return
 */
function nv_get_detail_song_link($song, $singers = [], $amp = true, $query_string = '')
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
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . Config::getOpAliasPrefix()->getSong() . $song['song_alias'] . $singer_alias . '-' . Config::getCodePrefix()->getSong() . $song['song_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
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
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . Config::getOpAliasPrefix()->getVideo() . $video['video_alias'] . $singer_alias . '-' . Config::getCodePrefix()->getVideo() . $video['video_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
}

/**
 * nv_get_resource_url()
 *
 * @param mixed $orgSrc
 * @param string $area
 * @param bool $thumb
 * @return
 */
function nv_get_resource_url($orgSrc, $area = 'album', $thumb = false)
{
    global $module_upload, $module_info;

    /**
     * $orgSrc có dạng folder/.../filename sao cho fullpath là NV_UPLOADS_REALDIR/module_name/$orgSrc
     * Trả về đường dẫn có thể hiển thị trực tiếp
     */

    // Kiểm tra file tồn tại
    if ($thumb and is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $orgSrc)) {
        return NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $orgSrc;
    } elseif (is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $orgSrc)) {
        return NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $orgSrc;
    }

    // Lấy từ cấu hình
    $map_cfg_data = [
        'album' => Config::getDefaultAlbumAvatar(),
        'singer' => Config::getDefaultSingerAvatar(),
        'author' => Config::getDefaultAuthorAvatar(),
        'video' => Config::getDefaultVideoAvatar()
    ];

    if (isset($map_cfg_data[$area]) and !empty($map_cfg_data[$area])) {
        if ($thumb and is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $map_cfg_data[$area];
        }
    }

    // Mặc định theo lập trình
    $map_cfg_data = array(
        'album' => 'album-art-cover.jpg',
        'singer' => 'singer-art.jpg',
        'author' => 'singer-art.jpg',
        'video' => 'video-art-cover.jpg'
    );

    if (isset($map_cfg_data[$area])) {
        if (is_file(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        } elseif (is_file(NV_ROOTDIR . '/themes/default/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area])) {
            return NV_BASE_SITEURL . 'themes/default/images/' . $module_info['module_theme'] . '/' . $map_cfg_data[$area];
        }
    }

    return NV_BASE_SITEURL . 'themes/default/images/' . $module_info['module_theme'] . '/pix.gif';
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
