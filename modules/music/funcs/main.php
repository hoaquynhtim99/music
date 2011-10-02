<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:26 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Thong tin trang
$page_title = $module_info['custom_title'];
$description = $setting['description'];
$key_words = $module_info['keywords'];

// Global data
$allsinger = getallsinger();
$category = get_category();

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'allalbum', $mainURL . "=album/numview" );
$xtpl->assign( 'allsong', $mainURL . "=song/numview" );
$xtpl->assign( 'URL_DOWN', $downURL );

// viet cac album hot nhat
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album_hot ORDER BY stt";
$query = $db->sql_query( $sql );
$hot_abid = array();
while ( $row = $db->sql_fetchrow( $query ) )
{
	$hot_abid[$row['stt']] = $row['albumid'];
}
foreach ( $hot_abid as $stt => $albumid )
{
	if ( $albumid == 0 ) continue;
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE `id` = ". $albumid ." AND `active`=1 ";
	$query = $db->sql_query( $sql );
	$hot_album = $db->sql_fetchrow( $query );
	$xtpl->assign( 'url_album', $mainURL . "=listenlist/" .$hot_album['id'] . "/" . $hot_album['name'] );
	if ( $stt == 1)
	{
		$xtpl->assign( 'fapic', $hot_album['thumb'] );
		$xtpl->assign( 'faname', $hot_album['tname'] );
		$xtpl->assign( 'fasinger', $allsinger[$hot_album['casi']] );
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $hot_album['casi']);

		// viet 10 bai hat cua album HOT
		$data = gettopsongbyalbumNAME( $hot_album['name'] );
		$i = 1 ;
		while ( $row = $db->sql_fetchrow($data) )
		{
			$xtpl->assign( 'STT', $i );
			$xtpl->assign( 'name', $row['tenthat'] );
			
			$xtpl->assign( 'url', $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'] );
			$i ++ ;
			$xtpl->parse( 'main.hotalbum.first.song' );
		}
		$xtpl->parse( 'main.hotalbum.first' );
	}
	else
	{
		// viet 8 album HOT
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $hot_album['casi']);
		$xtpl->assign( 'albumpic', $hot_album['thumb'] );
		$xtpl->assign( 'albumtitle', $hot_album['tname'] );
		$xtpl->assign( 'singer', $allsinger[$hot_album['casi']] );
		$xtpl->parse( 'main.hotalbum.old' );
	}
}
$xtpl->parse( 'main.hotalbum' );

// Lay album moi nhat
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE `active` = 1 ORDER BY id DESC LIMIT 0,9 ";
$query = $db->sql_query( $sql );
$a = 1 ;
while ($row = $db->sql_fetchrow( $query ))
{
	$xtpl->assign( 'url_album', $mainURL . "=listenlist/" .$row['id']. "/" .$row['name'] );

	if ($a == 1)
	{
		// viet album moi nhat
		$xtpl->assign( 'pic', $row['thumb'] );
		$xtpl->assign( 'name', $row['tname'] );
		$xtpl->assign( 'singer', $allsinger[$row['casi']] );
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);
		
		// viet 10 bai hat cua album MOI
		$data = gettopsongbyalbumNAME( $row['name'] );
		$i = 1 ;
		while ( $rows = $db->sql_fetchrow( $data ) )
		{
			$xtpl->assign( 'STT', $i );
			$xtpl->assign( 'sname', $rows['tenthat'] );
			$xtpl->assign( 'url', $mainURL . "=listenone/" .$rows['id']. "/" .$rows['ten'] );
			$i ++ ;
			$xtpl->parse( 'main.topalbum.firstn.song' );
		}
		$xtpl->parse( 'main.topalbum.firstn' );
	}
	else
	{
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);
		$xtpl->assign( 'albumpic', $row['thumb'] );
		$xtpl->assign( 'albumtitle', $row['tname'] );
		$xtpl->assign( 'singer', $allsinger[$row['casi']] );		
		$xtpl->parse( 'main.topalbum.old' );
	}
	$a ++ ;
}
$xtpl->parse( 'main.topalbum' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>