<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_MUSIC_ADMIN', true );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
$classMusic = new nv_mod_music();

$submenu['album'] = $classMusic->lang('sub_album');
$submenu['videoclip'] = $classMusic->lang('video');
$submenu['setting-home'] = $classMusic->lang('home_setting');
$submenu['singer'] = $classMusic->lang('sub_singer');
$submenu['author'] = $classMusic->lang('sub_author');
$submenu['error'] = $classMusic->lang('sub_error');
$submenu['ads'] = $classMusic->lang('sub_ads');
$submenu['lyric'] = $classMusic->lang('sub_lyric');
$submenu['gift'] = $classMusic->lang('sub_gift');
$submenu['comment'] = $classMusic->lang('sub_comment');
$submenu['userplaylist'] = $classMusic->lang('userplaylist');
$submenu['category'] = $classMusic->lang('sub_category');
$submenu['video_category'] = $classMusic->lang('sub_videocategory');
$submenu['utilities'] = $classMusic->lang('utilities');
$submenu['globalsetting'] = $classMusic->lang('set_global');

$allow_func = array( 'main', 'content-song', 'category', 'album', 'content-album', 'alias', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'ads', 'error', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'content-lyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'content-videoclip', 'videoclip', 'checklink', 'checksonglist', 'singer', 'content-singer', 'commentvideo', 'comment', 'globalsetting', 'author', 'content-author', 'ftpsetting', 'getalbumid', 'ex', 'addFromOtherSite', 'utilities', 'setting-alias', 'setting-home' );

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

?>