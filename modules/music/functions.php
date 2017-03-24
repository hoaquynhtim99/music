<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_MUSIC', true);
define('NV_MOD_TABLE', $db_config['prefix'] . '_' . $module_data);
define('NV_MOD_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_LINK_AMP', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_MOD_FULLLINK', NV_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_MOD_FULLLINK_AMP', NV_MOD_LINK_AMP . '&' . NV_OP_VARIABLE . '=');

$array_mod_title = array();

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
$global_array_nation = array();

//print_r($global_array_config);
//die();

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
 * nv_get_album_select_fields()
 * 
 * @param bool $full_fields
 * @return
 */
function nv_get_album_select_fields($full_fields = false)
{
    global $global_array_config;
    $array_select_fields = array('album_id', 'album_code', 'cat_ids', 'singer_ids', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_hit', 'time_add', 'time_update');
    $array_select_fields[] = NV_LANG_DATA . '_album_name album_name';
    $array_select_fields[] = NV_LANG_DATA . '_album_alias album_alias';
    $array_select_fields[] = NV_LANG_DATA . '_album_description album_description';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_album_name default_album_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_alias default_album_alias';
        $array_select_fields[] = $global_array_config['default_language'] . '_album_description default_album_description';
    }
    
    $array_lang_fields = array('album_name', 'album_alias', 'album_description');
    
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
    $array_select_fields = array('song_id', 'song_code', 'cat_ids', 'singer_ids', 'author_ids', 'album_ids', 'video_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_hit');
    $array_select_fields[] = NV_LANG_DATA . '_song_name song_name';
    $array_select_fields[] = NV_LANG_DATA . '_song_alias song_alias';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_song_name default_song_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_song_alias default_song_alias';
    }
    
    $array_lang_fields = array('song_name', 'song_alias');
    
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
    $array_select_fields = array('video_id', 'video_code', 'cat_ids', 'singer_ids', 'author_ids', 'song_id', 'resource_avatar', 'resource_cover', 'stat_views', 'stat_likes', 'stat_hit');
    $array_select_fields[] = NV_LANG_DATA . '_video_name video_name';
    $array_select_fields[] = NV_LANG_DATA . '_video_alias video_alias';
    if (NV_LANG_DATA != $global_array_config['default_language']) {
        $array_select_fields[] = $global_array_config['default_language'] . '_video_name default_video_name';
        $array_select_fields[] = $global_array_config['default_language'] . '_video_alias default_video_alias';
    }
    
    $array_lang_fields = array('video_name', 'video_alias');
    
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
    $array_select_fields = array('cat_id', 'cat_code', 'resource_avatar', 'resource_cover', 'resource_video', 'stat_albums', 'stat_songs', 'stat_videos', 'show_inalbum', 'show_invideo', 'status');
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
 * nv_get_artist_select_fields()
 * 
 * @param bool $full_fields
 * @return
 */
function nv_get_artist_select_fields($full_fields = false)
{
    global $global_array_config;
    $array_select_fields = array('artist_id', 'artist_code', 'artist_type', 'artist_birthday', 'artist_birthday_lev', 'nation_id', 'resource_avatar', 'resource_cover', 'stat_singer_albums', 'stat_singer_songs', 'stat_singer_videos', 'stat_author_songs', 'stat_author_videos');
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
 * @param array $singer
 * @param bool $amp
 * @return
 */
function nv_get_detail_album_link($album, $singer = array(), $amp = true)
{
    global $global_config, $module_info, $global_array_config;
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['album'] . $album['album_alias'] . (empty($singer['artist_alias']) ? '' : '-' . $singer['artist_alias']) . '-' . $global_array_config['code_prefix']['album'] . $album['album_code'] . $global_config['rewrite_exturl'];
}

/**
 * nv_get_detail_song_link()
 * 
 * @param mixed $song
 * @param array $singer
 * @param bool $amp
 * @return
 */
function nv_get_detail_song_link($song, $singer = array(), $amp = true)
{
    global $global_config, $module_info, $global_array_config;
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['song'] . $song['song_alias'] . '-' . $global_array_config['code_prefix']['song'] . $song['song_code'] . $global_config['rewrite_exturl'];
}

/**
 * nv_get_detail_video_link()
 * 
 * @param mixed $video
 * @param array $singer
 * @param bool $amp
 * @return
 */
function nv_get_detail_video_link($video, $singer = array(), $amp = true)
{
    global $global_config, $module_info, $global_array_config;
    return ($amp ? NV_MOD_FULLLINK_AMP : NV_MOD_FULLLINK) . $global_array_config['op_alias_prefix']['video'] . $video['video_alias'] . '-' . $global_array_config['code_prefix']['video'] . $video['video_code'] . $global_config['rewrite_exturl'];
}

/**
 * nv_get_fb_share_image()
 * 
 * @param mixed $data
 * @return
 */
function nv_get_fb_share_image($data = array())
{
    global $meta_property, $global_array_config, $module_upload;
    
    if (!empty($data['resource_avatar']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['resource_avatar'])) {
        $image_info = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $data['resource_avatar']);
        if (isset($image_info[0]) and isset($image_info[1]) and isset($image_info['mime']) and $image_info[0] >= 600 or $image_info[1] >= 315) {
            $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['resource_avatar'];
            $meta_property['og:image:url'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data['resource_avatar'];
            $meta_property['og:image:width'] = $image_info[0];
            $meta_property['og:image:height'] = $image_info[1];
            $meta_property['og:image:type'] = $image_info['mime'];
            return true;
        }
    }
    
    if (!empty($global_array_config['fb_share_image'])) {
        $meta_property['og:image'] = NV_MY_DOMAIN . $global_array_config['fb_share_image'];
        $meta_property['og:image:url'] = NV_MY_DOMAIN . $global_array_config['fb_share_image'];
        $meta_property['og:image:width'] = $global_array_config['fb_share_image_witdh'];
        $meta_property['og:image:height'] = $global_array_config['fb_share_image_height'];
        $meta_property['og:image:type'] = $global_array_config['fb_share_image_mime'];
    }
}
