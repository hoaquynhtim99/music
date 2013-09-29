<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['album'] = $lang_module['sub_album'];
$submenu['videoclip'] = $lang_module['video'];
$submenu['singer'] = $lang_module['sub_singer'];
$submenu['addsinger'] = $lang_module['singer_add'];
$submenu['author'] = $lang_module['sub_author'];
$submenu['addauthor'] = $lang_module['author_add'];
$submenu['error'] = $lang_module['sub_error'];
$submenu['ads'] = $lang_module['sub_ads'];
$submenu['lyric'] = $lang_module['sub_lyric'];
$submenu['gift'] = $lang_module['sub_gift'];
$submenu['comment'] = $lang_module['sub_comment'];
$submenu['userplaylist'] = $lang_module['userplaylist'];
$submenu['category'] = $lang_module['sub_category'];
$submenu['video_category'] = $lang_module['sub_videocategory'];
$submenu['globalsetting'] = $lang_module['set_global'];

$allow_func = array( 'main', 'content-song', 'category', 'del', 'delall', 'album', 'content-album', 'alias', 'hotalbum', 'fourcategory', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'sort', 'sortmainalbum', 'ads', 'delads', 'error', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'editlyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'content-videoclip', 'videoclip', 'checklink', 'checksonglist', 'singer', 'addsinger', 'commentvideo', 'comment', 'globalsetting', 'author', 'addauthor', 'listactive', 'ftpsetting', 'getalbumid', 'ex', 'addFromOtherSite' );

define( 'NV_IS_MUSIC_ADMIN', true );

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
$classMusic = new nv_mod_music();

?>