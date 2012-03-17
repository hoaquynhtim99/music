<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:26 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Hien thi bieu tuong cam xuc
if( $nv_Request->isset_request( 'loademotion', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$contents = nv_emotion_theme();

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo ( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

// Thong tin trang
$page_title = $module_info['custom_title'];
$description = $setting['description'];
$key_words = $module_info['keywords'];

// Global data
if( empty( $allsinger ) ) $allsinger = getallsinger();
if( empty( $category ) ) $category = get_category();

// Lay album HOT nhat
$sql = "SELECT b.id, b.name, b.tname, b.casi, b.thumb, b.listsong FROM " . NV_PREFIXLANG . "_" . $module_data . "_album_hot AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS b ON a.albumid=b.id WHERE b.active=1 ORDER BY a.stt ASC";

// Lay 9 album moi nhat khi load tab
if( $nv_Request->isset_request( 'load_main_song', 'get' ) )
{
	$type = $nv_Request->get_int( 'load_main_song', 'get', 0 );
	if( $type == 2 )
	{
		$sql = "SELECT `id`, `name`, `tname`, `casi`, `thumb`, `listsong` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `active`=1 ORDER BY `addtime` DESC LIMIT 0,9";
	}
	elseif( $type != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
}

$list = nv_db_cache( $sql, 0, $module_name );

$first_album_id = array();
$first_album_data = array();
$array_album = array();

foreach( $list as $row )
{
	if( empty( $first_album_id ) ) $first_album_id = array( $row['id'], $row['listsong'] );
	$array_album[] = array(
		"id" => $row['id'], //
		"tname" => $row['tname'], //
		"thumb" => $row['thumb'], //
		"casi" => $allsinger[$row['casi']], //
		"url_search_singer" => nv_url_rewrite( $main_header_URL . "=search/singer/" . $row['casi'], true ), //
		"url_album" => nv_url_rewrite( $main_header_URL . "=listenlist/" . $row['id'] . "/" . $row['name'], true ) //
			);
}

if( ! empty( $first_album_id ) )
{
	$sql = "SELECT `id`, `ten`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $first_album_id[1] . ") AND `active`=1";
	$list = nv_db_cache( $sql, 'id', $module_name );
	$_tmp = array();

	foreach( $list as $row )
	{
		$_tmp[$row['id']] = $row;
	}

	if( ! empty( $_tmp ) )
	{
		$i = 1;
		foreach( explode( ",", $first_album_id[1] ) as $_sid )
		{
			if( isset( $_tmp[$_sid] ) )
			{
				$first_album_data[] = array(
					"stt" => $i, //
					"tenthat" => $_tmp[$_sid]['tenthat'], //
					"url" => nv_url_rewrite( $main_header_URL . "=listenone/" . $_tmp[$_sid]['id'] . "/" . $_tmp[$_sid]['ten'], true ) //
						);

				$i++;
				if( $i > 10 ) break;
			}
		}
	}
}

$array = array( "url_more" => ( $nv_Request->get_int( 'load_main_song', 'get', 0 ) == 2 ) ? nv_url_rewrite( $main_header_URL . "=album/id/-", true ) : nv_url_rewrite( $main_header_URL . "=album/numview", true ) );

$contents = nv_music_main( $array, $array_album, $first_album_data );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>