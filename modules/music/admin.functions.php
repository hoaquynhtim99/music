<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['album'] = $lang_module['sub_album'];
$submenu['hotalbum'] = $lang_module['sub_hotalbum'];
$submenu['videoclip'] = $lang_module['video'];
$submenu['singer'] = $lang_module['sub_singer'];
$submenu['author'] = $lang_module['sub_author'];
$submenu['error'] = $lang_module['sub_error'];
$submenu['ads'] = $lang_module['sub_ads'];
$submenu['lyric'] = $lang_module['sub_lyric'];
$submenu['gift'] = $lang_module['sub_gift'];
$submenu['comment'] = $lang_module['sub_comment'];
$submenu['userplaylist'] = $lang_module['userplaylist'];
$submenu['category'] = $lang_module['sub_category'];
$submenu['video_category'] = $lang_module['sub_videocategory'];
$submenu['utilities'] = $lang_module['utilities'];
$submenu['globalsetting'] = $lang_module['set_global'];

$allow_func = array( 'main', 'content-song', 'category', 'album', 'content-album', 'alias', 'hotalbum', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'ads', 'error', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'content-lyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'content-videoclip', 'videoclip', 'checklink', 'checksonglist', 'singer', 'content-singer', 'commentvideo', 'comment', 'globalsetting', 'author', 'content-author', 'ftpsetting', 'getalbumid', 'ex', 'addFromOtherSite', 'utilities', 'setting-alias' );

define( 'NV_IS_MUSIC_ADMIN', true );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
$classMusic = new nv_mod_music();

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

?>