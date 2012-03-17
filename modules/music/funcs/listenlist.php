<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$allsinger = getallsinger();

$user_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
$user_register = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register";

$name = '';
if( defined( 'NV_IS_USER' ) ) $name = $user_info['username'];

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$row = getalbumbyID( $id );

$g_array = array(
	"name" => $name, //
	"user_login" => $user_login, //
	"user_register" => $user_register, //
	"id" => $id, //
	"sname" => $row['name'] //
		);

$album_array = array(
	"creat_link_url" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/album/" . $row['id'] . "/" . $row['name'], true ), //
	"playlist" => nv_url_rewrite( $main_header_URL . "=creatlinksong/album/" . $row['id'] . "/" . $row['name'], true ), //
	"name" => $row['tname'], //
	"url_search_upload" => $mainURL . "=search/upload/" . $row['upboi'], //
	"singer" => $allsinger[$row['casi']], //
	"numview" => $row['numview'], //
	"who_post" => $row['upboi'], //
	"album_thumb" => $row['thumb'], //
	"describe" => $row['describe'], //
	"URL_ALBUM" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenlist/" . $row['id'] . "/" . $row['name'], true ), //
	"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'] //
		);

if( empty( $row ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Cap nhat so luot nghe
$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numview`=`numview`+1 WHERE `id` =" . $id );

// Cac bai hat cua album
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $row['listsong'] . ") AND `active`=1";
$list = nv_db_cache( $sql, 'id' );

$album_array['numsong'] = sizeof( $list );
$song_array = array();

foreach( explode( ",", $row['listsong'] ) as $row )
{
	if( ! empty( $row ) and isset( $list[$row] ) )
	{
		$row = $list[$row];
		$song_array[] = array(
			"id" => $row['id'], //
			"song_name" => $row['tenthat'], //
			"song_singer" => $allsinger[$row['casi']], //
			"url_listen" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'], //
			"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'], //
			"song_url" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ) //
				);
	}
}

// Tieu de trang
$page_title = $lang_module['album'] . " " . $album_array['name'] . NV_TITLEBAR_DEFIS . $album_array['singer'];
$key_words = $album_array['name'] . " - " . $album_array['singer'];
$description = isset( $album_array['describe']
{
	50}
) ? $album_array['describe'] : sprintf( $lang_module['share_descreption_album'], $album_array['name'], $album_array['singer'], NV_MY_DOMAIN );

$contents = nv_music_listenlist( $g_array, $album_array, $song_array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>