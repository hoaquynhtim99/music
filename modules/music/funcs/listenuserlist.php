<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$playlist_alias = isset( $array_op[2] ) ? $array_op[2] : "";

if ( empty ( $id ) or empty ( $playlist_alias ) )
{
	module_info_die();
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active`=1 AND `id`=" . $id;
$result = $db->sql_query( $sql );
$check_exit = $db->sql_numrows( $result );
$row = $db->sql_fetchrow( $result );

if ( $check_exit != 1 or $row['keyname'] != $playlist_alias )
{
	module_info_die();
}

$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `view`=view+1 WHERE `id`=" . $id );

// Global data
$allsinger = getallsinger();

// Page info
$page_title = "Playlist " . $row['name'] . " - " . $row['singer'] . " - " . $row['username'];
$key_words =  "Playlist " . $row['name'] . " - " . $row['singer'] . " - " . $row['username'] ;
$description =  $row['message'];

// Playlist data
$gdata = array(
	"id" => $id,  // 
	"playlist_alias" => $playlist_alias,  // 
	"base_url" => NV_BASE_SITEURL ."modules/" . $module_data . "/data/",  // 
	"playlist_img" => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg",  // 
	"selfurl_base" =>  $client_info['selfurl'],  //
	"selfurl_encode" =>  rawurlencode ( $client_info['selfurl'] ),  //
	"ads" =>  getADS(),  //
	"pl_name" =>  $row['name'],  //
	"pl_singer" =>  $row['singer'],  //
	"pl_who_post" =>  $row['username'],  //
	"pl_numview" =>  $row['view'],  //
	"pl_date" =>  nv_date( "d/m/Y H:i", $row['time'] ),  //
	"pl_message" =>  $row['message'],  //
	"pl_url_search_upload" =>  $mainURL . "=search/upload/" . $row['username']  //
);

$sdata = array();

$listsong_id = explode ( ",", $row['songdata'] );
$listsong_id = array_filter ( $listsong_id );
$listsong_id = implode ( ",", $listsong_id );

$sql = "SELECT `id`, `ten`, `tenthat`, `casi` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN (" . $listsong_id . ") AND `active`=1";
$result = $db->sql_query( $sql );
$gdata['numsong'] = $db->sql_numrows( $result );

while ( list( $id, $ten, $tenthat, $casi ) = $db->sql_fetchrow( $result ) )
{
	$sdata[] = array(
		"id" => $id,  //
		"song_name" => $tenthat,  //
		"song_singer" => $allsinger[$casi],  //
		"url_listen" => $mainURL . "=listenone/" . $id . "/" . $ten,  //
		"url_search_singer" => $mainURL . "=search/singer/" . $casi,  //
		"song_url" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $id . "/" . $ten, true )  //
	);
}

$contents = nv_music_listen_playlist ( $gdata, $sdata );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>