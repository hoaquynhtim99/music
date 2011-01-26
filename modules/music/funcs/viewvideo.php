<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 08:31 PM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$allsinger = getallsinger();
$category = get_videocategory();
$setting = setting_music();

$xtpl = new XTemplate( "viewvideo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );
$xtpl->assign( 'ads', getADS() );

$xtpl->assign( 'playerurl', $global_config['site_url'] ."/modules/" . $module_data . "/data/" );
$xtpl->assign( 'thisurl',  $mainURL ."=video" );

$user_login = $mainURL . "=login" ;
$user_register = $mainURL . "=register" ;		
if ( defined( 'NV_IS_USER' ) )
{ 
	$name = $user_info['username'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$name = $admin_info['username'];
}
else $name = '';

// lay video
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
updateHIT_VIEW( $id, '_video' );
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id = " . $id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

$xtpl->assign( 'URL_SENDMAIL',  $mainURL . "=videosendmail&amp;id=". $id );
$xtpl->assign( 'TITLE',  $lang_module['sendtomail'] );
$xtpl->assign( 'ID',  $id );
$xtpl->assign( 'name', $row['tname'] );
$xtpl->assign( 'singer', $allsinger[$row['casi']] );
$xtpl->assign( 'category', $category[ $row['theloai'] ] );
$xtpl->assign( 'view', $row['view'] );
$xtpl->assign( 'creat_link_url',  $global_config['site_url'] . '/' . $global_config['site_lang'] . '/' . $module_data . '/creatlinksong/video/' . $row['id'] . '/' . $row['name'] . '/' );

$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $row['casi']);
$xtpl->assign( 'url_search_category', $mainURL . "=searchvideo/category/" . $row['theloai']);
$xtpl->assign( 'link', outputURL ( $row['server'], $row['duongdan'] ) );
$xtpl->assign( 'URL_SONG', get_URL() );

// binh luan
if ( ( $setting['who_comment'] == 0 ) && !defined( 'NV_IS_USER' ) && !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
	$xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.nocomment' );
}
elseif ( $setting['who_comment'] == 2 )
{
	$xtpl->parse( 'main.stopcomment' );	
}
else
{
	require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
	$xtpl->assign( 'EMOTIONS', show_emotions() );
	$xtpl->assign( 'USER_NAME', $name );
	$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );
	$xtpl->parse( 'main.comment' );
}

// tieu de trang
$page_title = $row['tname'] . " - " . $allsinger[$row['casi']] ;
$key_words =  $row['tname'] . " - " . $allsinger[$row['casi']] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>