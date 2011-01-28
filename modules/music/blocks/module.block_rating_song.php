<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:47 PM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $module_data, $mainURL, $db;

$xtpl = new XTemplate( "block_rating_song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$allsinger = getallsinger();
$sql = "SELECT `songid` FROM " . NV_PREFIXLANG . "_" . $module_data . "_topsong ORDER BY hit DESC LIMIT 0,10";
$query = $db->sql_query( $sql );
$i = 1;
while( $data =  $db->sql_fetchrow( $query ) )
{
	$song = getsongbyID ( $data['songid'] );
	
	$xtpl->assign( 'STT', $i );
	$xtpl->assign( 'url_view', $mainURL . "=listenone/" .$song['id']. "/" . $song['ten'] );
	$xtpl->assign( 'songname', $song['tenthat'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $song['casi']);
	$xtpl->assign( 'singer', $allsinger[$song['casi']] );
	$xtpl->parse( 'main.loop' );
	$i++;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>