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
$allsinger = getallsinger();

$xtpl = new XTemplate( "listenuserlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );
$img = rand( 1, 10 );
$xtpl->assign( 'playlist_img',  NV_BASE_SITEURL ."modules/" . $module_data . "/data/img(" . $img . ").jpg" );
$xtpl->assign( 'URL_PL', get_URL() );
$xtpl->assign( 'ads',  getADS() );

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `view` = view+1 WHERE `id` =" . $id );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE id = " . $id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
// tieu de trang
$page_title = "Playlist " . $row['name'] . " - " .$row['singer'] . " - " . $row['username'];
$key_words =  "Playlist " . $row['name'] . " - " .$row['singer'] . " - " . $row['username'] ;

$xtpl->assign( 'name',  $row['name'] );
$xtpl->assign( 'singer',  $row['singer'] );
$xtpl->assign( 'who_post',  $row['username'] );
$xtpl->assign( 'numview',  $row['view'] );
$xtpl->assign( 'date',   nv_date( "d/m/Y H:i", $row['time'] ) );
$xtpl->assign( 'message',  $row['message'] );
$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $row['username']);

$song = explode ( "/", $row['songdata'] );
$j = count( $song );
for ( $i = 1; $i < $j; $i ++ )
{
	$datasong = getsongbyID( $song[$i] );
	if ( $datasong['server'] != 0 )
	{
		$xtpl->assign( 'song_url', $songURL . $datasong['duongdan'] );
	}
	else
	{
		$xtpl->assign( 'song_url', $datasong['duongdan'] );
	}
	$xtpl->assign( 'song_name', $datasong['tenthat'] );
	$xtpl->assign( 'song_singer', $allsinger[$datasong['casi']] );
	$xtpl->parse( 'main.song' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>