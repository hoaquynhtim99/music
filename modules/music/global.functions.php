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

define('NV_MOD_TABLE', $db_config['prefix'] . '_' . $module_data);

define('NV_MOD_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_LINK_AMP', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_FULLLINK', NV_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_MOD_FULLLINK_AMP', NV_MOD_LINK_AMP . '&amp;' . NV_OP_VARIABLE . '=');

define('MS_COMMENT_AREA_SONG', 1);
define('MS_COMMENT_AREA_ALBUM', 2);
define('MS_COMMENT_AREA_VIDEO', 3);

$array_alphabets = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$global_array_rule = array();
$global_array_rule['nation_code'] = '/^[a-zA-Z0-9]{4}$/';

// Cấu hình module
$cacheFile = NV_LANG_DATA . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_config = unserialize($cache);
} else {
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_config";
    $result = $db->query($sql);

    $global_array_config = array();
    while ($row = $result->fetch()) {
        if ($row['config_value_' . NV_LANG_DATA] === null) {
            $global_array_config[$row['config_name']] = $row['config_value_default'];
        } else {
            $global_array_config[$row['config_name']] = $row['config_value_' . NV_LANG_DATA];
        }

        if (preg_match('/^arr\_([a-zA-Z0-9\_]+)\_(singer|playlist|album|video|cat|song|profile)$/', $row['config_name'], $m)) {
            if (!isset($global_array_config[$m[1]])) {
                $global_array_config[$m[1]] = array();
            }
            $global_array_config[$m[1]][$m[2]] = $global_array_config[$row['config_name']];
            unset($global_array_config[$row['config_name']]);
        }
    }

    $global_array_config['default_language'] = NV_LANG_DATA;
    $sql = "SHOW COLUMNS FROM " . NV_MOD_TABLE . "_albums";
    $result = $db->query($sql);

    $start_check = false;
    while ($row = $result->fetch()) {
        if ($row['field'] == 'status') {
            $start_check = true;
        } elseif ($start_check and preg_match("/^([a-z]{2})\_/", $row['field'], $m)) {
            $global_array_config['default_language'] = $m[1];
        }
    }

    $nv_Cache->setItem($module_name, $cacheFile, serialize($global_array_config), $cacheTTL);
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

    $global_array_cat = array();
    $global_array_cat_alias = array();

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

// Quốc gia
$cacheFile = NV_LANG_DATA . '_nations_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 0; // Cache vĩnh viễn đến khi xóa

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_array_nation = unserialize($cache);
    $global_array_nation_alias = $global_array_nation[1];
    $global_array_nation = $global_array_nation[0];
} else {
    $array_select_fields = nv_get_nation_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_nations ORDER BY weight ASC";
    $result = $db->query($sql);

    $global_array_nation = array();
    $global_array_nation_alias = array();

    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        $global_array_nation[$row['nation_id']] = $row;
        $global_array_nation_alias[$row['nation_code']] = $row['nation_id'];
    }

    $nv_Cache->setItem($module_name, $cacheFile, serialize(array($global_array_nation, $global_array_nation_alias)), $cacheTTL);
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
$global_array_artist_type = array();
$global_array_artist_type[0] = $lang_module['artist_type_singer'];
$global_array_artist_type[1] = $lang_module['artist_type_author'];
$global_array_artist_type[2] = $lang_module['artist_type_all'];

$global_array_config['detail_song_albums_nums'] = 12;
$global_array_config['detail_song_videos_nums'] = 12;
$global_array_config['limit_authors_displayed'] = 3;
$global_array_config['various_artists_authors'] = 'Nhóm tác giả';
$global_array_config['unknow_author'] = 'Đang cập nhật';
$global_array_config['unknow_cat'] = 'Đang cập nhật';
$global_array_config['shareport'] = 'addthis';
$global_array_config['addthis_pubid'] = 'addthis';
$global_array_config['uploads_folder'] = 'dataup.default123';
$global_array_config['msg_nolyric'] = 'Đang cập nhật';

$global_array_config['auto_optimize_artist_name'] = 1;
$global_array_config['auto_optimize_video_name'] = 1;
$global_array_config['auto_optimize_song_name'] = 1;
$global_array_config['auto_optimize_album_name'] = 1;

/**
 * nv_get_album_select_fields()
 *
 * @param bool $full_fields
 * @return
 */
function nv_get_album_select_fields($full_fields = false)
{
    global $global_array_config;
    $array_select_fields = array('album_id', 'album_code', 'cat_ids', 'singer_ids', 'release_year', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'time_add', 'time_update', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_album_name default_album_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_alias default_album_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_description default_album_description';
    }

    $array_lang_fields = array('album_name', 'album_alias', 'album_description');

    if ($full_fields) {
        $array_select_fields[] = 'uploader_id';
        $array_select_fields[] = 'uploader_name';
        $array_select_fields[] = NV_LANG_DATA . '_album_introtext album_introtext';
        $array_select_fields[] = NV_LANG_DATA . '_album_keywords album_keywords';
        if (NV_LANG_DATA != $global_array_config['default_language']) {
            $array_select_fields[] = $global_array_config['default_language'] . '_album_introtext default_album_introtext';
            $array_select_fields[] = $global_array_config['default_language'] . '_album_keywords default_album_keywords';
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
    global $global_array_config;
    $array_select_fields = array('song_id', 'song_code', 'cat_ids', 'singer_ids', 'author_ids', 'album_ids', 'video_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_song_name song_name';
    $array_select_fields[] = NV_LANG_DATA . '_song_alias song_alias';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_song_name default_song_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_song_alias default_song_alias';
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
        if (NV_LANG_DATA != $global_array_config['default_language']) {
            $array_select_fields[] = $global_array_config['default_language'] . '_song_introtext default_song_introtext';
            $array_select_fields[] = $global_array_config['default_language'] . '_song_keywords default_song_keywords';
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
    global $global_array_config;
    $array_select_fields = array('video_id', 'video_code', 'cat_ids', 'singer_ids', 'author_ids', 'song_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_comments', 'stat_hit', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_video_name video_name';
    $array_select_fields[] = NV_LANG_DATA . '_video_alias video_alias';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_video_name default_video_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_video_alias default_video_alias';
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
        if (NV_LANG_DATA != $global_array_config['default_language']) {
            $array_select_fields[] = $global_array_config['default_language'] . '_video_introtext default_video_introtext';
            $array_select_fields[] = $global_array_config['default_language'] . '_video_keywords default_video_keywords';
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
    global $global_array_config;
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'time_add', 'time_update', 'show_inalbum', 'show_invideo', 'weight', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_cat_name cat_name';
    $array_select_fields[] = NV_LANG_DATA . '_cat_alias cat_alias';
    $array_select_fields[] = NV_LANG_DATA . '_cat_absitetitle cat_absitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abintrotext cat_abintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_abkeywords cat_abkeywords';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvsitetitle cat_mvsitetitle';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvintrotext cat_mvintrotext';
    $array_select_fields[] = NV_LANG_DATA . '_cat_mvkeywords cat_mvkeywords';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_name default_cat_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_alias default_cat_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_absitetitle default_cat_absitetitle';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_abintrotext default_cat_abintrotext';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_abkeywords default_cat_abkeywords';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvsitetitle default_cat_mvsitetitle';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvintrotext default_cat_mvintrotext';
        $array_select_fields[] = $global_array_config['default_language'] . '_cat_mvkeywords default_cat_mvkeywords';
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
    global $global_array_config;
    $array_select_fields = array('nation_id', 'nation_code', 'stat_singers', 'stat_authors', 'time_add', 'time_update', 'status', 'weight');
    $array_select_fields[] = NV_LANG_DATA . '_nation_name nation_name';
    $array_select_fields[] = NV_LANG_DATA . '_nation_alias nation_alias';
    $array_select_fields[] = NV_LANG_DATA . '_nation_introtext nation_introtext';
    $array_select_fields[] = NV_LANG_DATA . '_nation_keywords nation_keywords';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_nation_name default_nation_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_nation_alias default_nation_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_nation_sitetitle default_nation_sitetitle';
        $array_select_fields[] = $global_array_config['default_language'] . '_nation_introtext default_nation_introtext';
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
    global $global_array_config;
    $array_select_fields = array('artist_id', 'artist_code', 'artist_type', 'artist_birthday', 'artist_birthday_lev', 'nation_id', 'resource_avatar', 'resource_cover', 'stat_singer_albums', 'stat_singer_songs', 'stat_singer_videos', 'stat_author_songs', 'stat_author_videos', 'time_add', 'time_update', 'status');
    $array_select_fields[] = NV_LANG_DATA . '_artist_name artist_name';
    $array_select_fields[] = NV_LANG_DATA . '_artist_alias artist_alias';
    $array_select_fields[] = NV_LANG_DATA . '_artist_realname artist_realname';
    $array_select_fields[] = NV_LANG_DATA . '_singer_nickname singer_nickname';
    $array_select_fields[] = NV_LANG_DATA . '_author_nickname author_nickname';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_artist_name default_artist_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_artist_alias default_artist_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_artist_realname default_artist_realname';
        $array_select_fields[] = $global_array_config['default_language'] . '_singer_nickname default_singer_nickname';
        $array_select_fields[] = $global_array_config['default_language'] . '_author_nickname default_author_nickname';
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
        if (NV_LANG_DATA != $global_array_config['default_language']) {
            $array_select_fields[] = $global_array_config['default_language'] . '_artist_hometown default_artist_hometown';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_prize default_singer_prize';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_info default_singer_info';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_introtext default_singer_introtext';
            $array_select_fields[] = $global_array_config['default_language'] . '_singer_keywords default_singer_keywords';
            $array_select_fields[] = $global_array_config['default_language'] . '_author_prize default_author_prize';
            $array_select_fields[] = $global_array_config['default_language'] . '_author_info default_author_info';
            $array_select_fields[] = $global_array_config['default_language'] . '_author_introtext default_author_introtext';
            $array_select_fields[] = $global_array_config['default_language'] . '_author_keywords default_author_keywords';
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
    global $global_array_config, $db;

    $array_artists = array();

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
    global $global_config, $module_info, $global_array_config;
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $module_info['alias']['view-singer'] . '/' . $singer['artist_alias'] . '-' . $global_array_config['code_prefix']['singer'] . $singer['artist_code'] . (($tab and isset($global_array_config['view_singer_tabs_alias'][$tab])) ? '/' . $global_array_config['view_singer_tabs_alias'][$tab] : $global_config['rewrite_exturl']);
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
function nv_get_detail_album_link($album, $singers = array(), $amp = true, $query_string = '')
{
    global $global_config, $module_info, $global_array_config;
    $num_singers = sizeof($singers);
    if ($num_singers > $global_array_config['limit_singers_displayed']) {
        $singer_alias = '-' . strtolower(change_alias($global_array_config['various_artists']));
    } elseif ($num_singers > 0) {
        $singer_alias = array();
        foreach ($singers as $singer) {
            $singer_alias[] = $singer['artist_alias'];
        }
        $singer_alias = '-' . implode('-', $singer_alias);
    } else {
        $singer_alias = '';
    }
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['album'] . $album['album_alias'] . $singer_alias . '-' . $global_array_config['code_prefix']['album'] . $album['album_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
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
function nv_get_detail_song_link($song, $singers = array(), $amp = true, $query_string = '')
{
    global $global_config, $module_info, $global_array_config;
    $num_singers = sizeof($singers);
    if ($num_singers > $global_array_config['limit_singers_displayed']) {
        $singer_alias = '-' . strtolower(change_alias($global_array_config['various_artists']));
    } elseif ($num_singers > 0) {
        $singer_alias = array();
        foreach ($singers as $singer) {
            $singer_alias[] = $singer['artist_alias'];
        }
        $singer_alias = '-' . implode('-', $singer_alias);
    } else {
        $singer_alias = '';
    }
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['song'] . $song['song_alias'] . $singer_alias . '-' . $global_array_config['code_prefix']['song'] . $song['song_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
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
function nv_get_detail_video_link($video, $singers = array(), $amp = true, $query_string = '')
{
    global $global_config, $module_info, $global_array_config;
    $num_singers = sizeof($singers);
    if ($num_singers > $global_array_config['limit_singers_displayed']) {
        $singer_alias = '-' . strtolower(change_alias($global_array_config['various_artists']));
    } elseif ($num_singers > 0) {
        $singer_alias = array();
        foreach ($singers as $singer) {
            $singer_alias[] = $singer['artist_alias'];
        }
        $singer_alias = '-' . implode('-', $singer_alias);
    } else {
        $singer_alias = '';
    }
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['video'] . $video['video_alias'] . $singer_alias . '-' . $global_array_config['code_prefix']['video'] . $video['video_code'] . $global_config['rewrite_exturl'] . ($query_string ? (($amp ? '&amp;' : '&') . $query_string) : '');
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
    global $module_upload, $global_array_config, $module_info, $global_config;

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
    $map_cfg_data = array(
        'album' => $global_array_config['res_default_album_avatar'],
        'singer' => $global_array_config['res_default_singer_avatar'],
        'author' => $global_array_config['res_default_author_avatar'],
        'video' => $global_array_config['res_default_video_avatar']
    );

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
        $array_lang_module_setup = array();

        // Xác định các ngôn ngữ đã cài đặt
        $_sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1";
        $_result = $db->query($_sql);
        $array_lang_setup = array();
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
 * msFormatNumberViews()
 *
 * @param mixed $input
 * @return
 */
function msFormatNumberViews($input)
{
    if (NV_LANG_INTERFACE == 'vi') {
        return number_format($input, 0, ',', '.');
    }
    return number_format($input, 0, '.', ',');
}

/**
 * msFormatDateViews()
 *
 * @param mixed $input
 * @return
 */
function msFormatDateViews($input)
{
    if (NV_LANG_INTERFACE == 'vi') {
        return nv_date('d M, Y', $input);
    }
    return nv_date('M d, Y', $input);
}

/**
 * msGetMaxPage()
 *
 * @param mixed $per_page
 * @return
 */
function msGetMaxPage($per_page)
{
    if ($per_page < 1) {
        return 1;
    }
    return round(2147483647 / $per_page);
}

/**
 * msGetValidPage()
 *
 * @param mixed $page
 * @param mixed $per_page
 * @return
 */
function msGetValidPage($page, $per_page)
{
    if ($page < 1 or $page > msGetMaxPage($per_page)) {
        return 1;
    }
    return $page;
}

/**
 * msCheckPage()
 *
 * @param mixed $page
 * @param mixed $per_page
 * @return
 */
function msCheckPage($page, $per_page)
{
    if ($page < 1 or $page > msGetMaxPage($per_page)) {
        return false;
    }
    return true;
}

/**
 * msGetUniqueCode()
 *
 * @param string $area
 * @return
 */
function msGetUniqueCode($area = '')
{
    global $db;

    $code = '';

    if ($area == 'cat') {
        // Mã thể loại
        while (empty($code) or $db->query("SELECT cat_id FROM " . NV_MOD_TABLE . "_categories WHERE cat_code=" . $db->quote($code))->fetchColumn()) {
            $code = strtolower(nv_genpass(4));
        }
    } elseif ($area == 'artist') {
        // Mã nghệ sĩ
        while (empty($code) or $db->query("SELECT artist_id FROM " . NV_MOD_TABLE . "_artists WHERE artist_code=" . $db->quote($code))->fetchColumn()) {
            $code = strtolower(nv_genpass(5));
        }
    }

    return $code;
}
