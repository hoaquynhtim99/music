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

$user_login = $mainURL . "=login" ;
$user_register = $mainURL . "=register" ;

$name = '';		
if ( defined( 'NV_IS_USER' ) )
{ 
	$name = $user_info['username'];
}

$g_array = array();
$g_array['name'] = $name;

// lay video
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

if( empty( $id ) ) module_info_die();

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE id=" . $id . " AND `active`=1";
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

if( empty( $row ) ) module_info_die();

updateHIT_VIEW( $id, '_video' );

$row['listcat'] = empty( $row['listcat'] ) ? array() : array_diff(array_filter(array_unique(explode(",", $row['listcat']))),array($row['theloai']));
if( ! empty( $row['listcat'] ) )
{
	$list_cat = $row['listcat'];
	$row['listcat'] = array();
	foreach( $list_cat as $cat )
	{
		$row['listcat'][] = array(
			"name" => $category[$cat]['title'],  //
			"url" => $mainURL . "=searchvideo/category/" . $cat  //
		);
	}
}

$array = array(
	"URL_SENDMAIL" => $mainURL . "=videosendmail&amp;id=" . $id,  //
	"TITLE" => $lang_module['sendtomail'],  //
	"ID" => $id,  //
	"name" => $row['tname'],  //
	"thumb" => $row['thumb'],  //
	"listcat" => $row['listcat'],  //
	"sname" => $row['name'],  //
	"singer" => $allsinger[$row['casi']],  //
	"category" => $category[$row['theloai']]['title'],  //
	"view" => $row['view'],  //
	"creat_link_url" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . '=creatlinksong/video/' . $row['id'] . '/' . $row['name'], true ),  //
	"url_search_singer" => $mainURL . "=searchvideo/singer/" . $row['casi'],  //
	"url_search_category" => $mainURL . "=searchvideo/category/" . $row['theloai'],  //
	"link" => nv_url_rewrite( $main_header_URL . "=creatlinksong/video/" . $row['id'] . "/" . $row['name'], true ),  //
	"URL_SONG" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . '=viewvideo/' . $row['id'] . '/' . $row['name'], true )  //
);

// tieu de trang
$page_title = $row['tname'] . " - " . $allsinger[$row['casi']] ;
$key_words =  $row['tname'] . " - " . $allsinger[$row['casi']] ;

$contents = nv_music_viewvideo( $g_array, $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>