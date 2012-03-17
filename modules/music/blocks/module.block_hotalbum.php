<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;

$xtpl = new XTemplate( "block_hotalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$allsinger = getallsinger();

$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` ORDER BY `numview` DESC LIMIT 0,8" );
while( $song = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $song['casi'] );
	$xtpl->assign( 'url_listen', $mainURL . "=listenlist/" . $song['id'] . "/" . $song['name'] );
	$xtpl->assign( 'name', $song['tname'] );
	$xtpl->assign( 'singer', $allsinger[$song['casi']] );
	$xtpl->assign( 'view', $song['numview'] );
	$xtpl->assign( 'img', $song['thumb'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>