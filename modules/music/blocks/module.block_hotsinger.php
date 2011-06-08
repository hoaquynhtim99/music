<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;

$xtpl = new XTemplate( "block_hotsinger.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_hot_singer ORDER BY `stt` ASC";
$query = $db->sql_query( $sql );

$i = 1 ;
while ( $row = $db->sql_fetchrow( $query ) )
{

	$sqlalbum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE `casi`=\"" . $row['name'] ."\" ORDER BY `id` DESC LIMIT 0,1";
	$queryalbum = $db->sql_query( $sqlalbum );
	$rowalbum =  $db->sql_fetchrow( $queryalbum );
	$xtpl->assign( 'url_album', $mainURL . "=listenlist/" .$rowalbum['id']. "/" . $rowalbum['name']);

	$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `album`=\"".$rowalbum['name']."\" ORDER BY `id` DESC LIMIT 0,10";
	$querysong = $db->sql_query( $sqlsong );
	
	$j = 1 ;
	while ( $rowsong =  $db->sql_fetchrow( $querysong ) )
	{
		if ( ( $j % 2 ) != 0 ) $xtpl->assign( 'left', 'left' );
		else $xtpl->assign( 'left', '' );
		
		$xtpl->assign( 'songname', $rowsong['tenthat'] );
		$xtpl->assign( 'url_song', $mainURL . "=listenone/" .$rowsong['id']. "/" . $rowsong['ten'] );
		
		if ( $j >= 9 ) $xtpl->assign( 'end', 'end' );
		
		$xtpl->parse( 'main.top.song' );
		$j ++ ;
	}

	$xtpl->assign( 'TOPSTT', $i );
	$xtpl->assign( 'topname', $row['fullname'] );
	$xtpl->assign( 'large_thumb', $row['large_thumb'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['name']);

	$xtpl->parse( 'main.top' );
	$i ++ ;	
}

$i = 1 ;
while( $row = $db->sql_fetchrow( $query ) )
{
	$xtpl->assign( 'STT', $i );
	$xtpl->assign( 'name', $row['fullname'] );
	$xtpl->assign( 'thumb', $row['thumb'] );
	$xtpl->parse( 'main.bottom' );
	
	$i ++ ;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>