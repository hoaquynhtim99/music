<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// khoi tao tpl
$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'allalbum', $mainURL . "=album/numview" );
$xtpl->assign( 'allsong', $mainURL . "=song/numview" );
$category = get_category();

// viet cac album hot nhat
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album_hot ORDER BY stt";
$query = $db->sql_query( $sql );
$hot_abid = array();
while ( $row = $db->sql_fetchrow( $query ) )
{
	$hot_abid[$row['stt']] = $row['albumid'];
}
foreach ( $hot_abid as $stt => $albumid)
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE `id` = ". $albumid ." ";
	$query = $db->sql_query( $sql );
	$hot_album = $db->sql_fetchrow( $query );
	$xtpl->assign( 'url_album', $mainURL . "=listenlist/" .$hot_album['id'] . "/" . $hot_album['name'] );
	if ( $stt == 1)
	{
		$xtpl->assign( 'fapic', $hot_album['thumb'] );
		$xtpl->assign( 'faname', $hot_album['tname'] );
		$xtpl->assign( 'fasinger', $hot_album['casithat'] );
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $hot_album['casi']);

		// lay bai hat cua album hot nhat
		$first_album_song = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE album = \"".$hot_album['name']."\" ORDER BY id DESC LIMIT 0,10 ";
		// viet 10 bai hat cua album HOT
		$data = mysql_query($first_album_song);
		$i = 1 ;
		while ( $row = mysql_fetch_array($data) )
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
		$xtpl->assign( 'singer', $hot_album['casithat'] );
		$xtpl->parse( 'main.hotalbum.old' );
	}
}
$xtpl->parse( 'main.hotalbum' );

// Lay album moi nhat
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ORDER BY id DESC LIMIT 0,9 ";
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
		$xtpl->assign( 'singer', $row['casithat'] );
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);
		
		// viet 10 bai hat cua album MOI
		$first_nalbum_song = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE album = \"".$row['name']."\"  ORDER BY id DESC LIMIT 0,10 ";
		$data = mysql_query($first_nalbum_song);
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
		$xtpl->assign( 'singer', $row['casithat'] );		
		$xtpl->parse( 'main.topalbum.old' );
	}
	$a ++ ;
}
$xtpl->parse( 'main.topalbum' );

// -- viet cac bai hat moi cap nhat
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_4category ORDER BY id ";
$query = $db->sql_query( $sql );
$fctegory = array() ;
while ($row = $db->sql_fetchrow( $query ))
{
	$fctegory[$row['id']] = $row['cid'] ;
}
	
$xtpl->assign( 'f1', $category[$fctegory[1]] );
$xtpl->assign( 'f2', $category[$fctegory[2]] );
$xtpl->assign( 'f3', $category[$fctegory[3]] );
$xtpl->assign( 'f4', $category[$fctegory[4]] );
	
$i = 1 ;
foreach ( $fctegory as $this_category )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `theloai` = ".$this_category." ORDER BY id DESC LIMIT 0,10";
	$query = $db->sql_query( $sql );
	while ($row = $db->sql_fetchrow( $query ))
	{
		
		$xtpl->assign( 'ID', $row['id'] );
		$xtpl->assign( 'name', $row['tenthat'] );
		$xtpl->assign( 'singer', $row['casithat'] );
		$xtpl->assign( 'category', $category[$row['theloai']] );
		$xtpl->assign( 'who_upload', $row['upboi'] );
		$xtpl->assign( 'url', $row['duongdan'] );
		$xtpl->assign( 'view', $row['numview'] );
		$xtpl->assign( 'url_view', $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'] );
	
		$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);
		$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $row['theloai']);
		$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $row['upboi']);
	
	
		$xtpl->parse( 'main.oldsong.topsong.loop' );	
	}
	
	$xtpl->assign( 'DIV', $i );
	$xtpl->parse( 'main.oldsong.topsong' );
	$i ++ ;
}
$xtpl->parse( 'main.oldsong' );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );


include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>