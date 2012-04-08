<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$user_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
$user_register = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register";

$name = '';
if( defined( 'NV_IS_USER' ) ) $name = $user_info['username'];

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$row = getalbumbyID( $id );

$row['singeralias'] = '';
$row['singername'] = $lang_module['unknow'];

// Lay ca si
$sql = "SELECT `ten`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id`=" . $row['casi'];
$list = nv_db_cache( $sql );

if( ! empty( $list ) )
{
	$row['singeralias'] = $list[0]['ten'];
	$row['singername'] = $list[0]['tenthat'];
}

$g_array = array(
	"name" => $name, //
	"user_login" => $user_login, //
	"user_register" => $user_register, //
	"id" => $id, //
	"sname" => $row['name'] //
);

// Check HIT
$checkhit = explode( "-", $row['hit'] );
$checkhit = $checkhit[0];

$album_array = array(
	"creat_link_url" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/album/" . $row['id'] . "/" . $row['name'], true ), //
	"playlist" => nv_url_rewrite( $main_header_URL . "=creatlinksong/album/" . $row['id'] . "/" . $row['name'], true ), //
	"name" => $row['tname'], //
	"url_search_upload" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $row['upboi'] ) . "&amp;type=upload", //
	"singer" => $row['singername'], //
	"singerid" => $row['casi'], //
	"numview" => $row['numview'], //
	"who_post" => $row['upboi'], //
	"album_thumb" => $row['thumb'], //
	"checkhit" => $checkhit, //
	"describe" => $row['describe'], //
	"URL_ALBUM" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenlist/" . $row['id'] . "/" . $row['name'], true ), //
	"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $row['singername'] ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" //
);

if( empty( $row ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Cap nhat so luot nghe, HIT
updateHIT_VIEW( $id, '_album', true );

// Cac bai hat cua album
$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.id IN(" . $row['listsong'] . ") AND a.active=1";
$list = nv_db_cache( $sql, 'id' );

$album_array['numsong'] = sizeof( $list );
$song_array = array();

foreach( explode( ",", $row['listsong'] ) as $row )
{
	if( ! empty( $row ) and isset( $list[$row] ) )
	{
		$row = $list[$row];
		$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];
		
		$song_array[] = array(
			"id" => $row['id'], //
			"song_name" => $row['tenthat'], //
			"song_singer" => $singername, //
			"url_listen" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'], //
			"url_search_singer" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
			"song_url" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ) //
		);
	}
}

$array_album = $array_video = $array_singer = array();

if( $album_array['singerid'] != 0 )
{
	// Danh sach album
	$sql = "SELECT `id`, `name`, `tname`, `casi`, `thumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $album_array['singerid'] . " AND `active`=1 ORDER BY `addtime` DESC LIMIT 0,4";
	$list = nv_db_cache( $sql, 'id' );
	
	foreach( $list as $r )
	{
		$array_album[] = array(
			"name" => $r['tname'], //
			"thumb" => $r['thumb'], //
			"url_listen" => $mainURL . "=listenlist/" . $r['id'] . "/" . $r['name'], //
			"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $album_array['singer'] ) . "&amp;id=" . $r['casi'] . "&amp;type=singer", //
		);
	}
	
	// Danh sach video
	$sql = "SELECT `id`, `name`, `tname`, `casi`, `thumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `casi`=" . $album_array['singerid'] . " AND `active`=1 ORDER BY `dt` DESC LIMIT 0,3";
	$list = nv_db_cache( $sql, 'id' );
	
	foreach( $list as $r )
	{
		$array_video[] = array(
			"name" => $r['tname'], //
			"thumb" => $r['thumb'], //
			"url_listen" => $mainURL . "=viewvideo/" . $r['id'] . "/" . $r['name'], //
			"url_search_singer" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $album_array['singer'] ) . "&amp;id=" . $r['casi'] . "&amp;type=singer", //
		);
	}
	
	// Chi tiet ca si
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id`=" . $album_array['singerid'] . " AND `thumb`!='' AND `introduction`!=''";
	$list = nv_db_cache( $sql, 'id' );
	
	foreach( $list as $r )
	{
		$array_singer = $r;
	}
}

// Tieu de trang
$page_title = $lang_module['album'] . " " . $album_array['name'] . NV_TITLEBAR_DEFIS . $album_array['singer'];
$key_words = $album_array['name'] . " - " . $album_array['singer'];
$description = isset( $album_array['describe']{50} ) ? $album_array['describe'] : sprintf( $lang_module['share_descreption_album'], $album_array['name'], $album_array['singer'], NV_MY_DOMAIN );

$contents = nv_music_listenlist( $g_array, $album_array, $song_array, $array_album, $array_video, $array_singer );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>