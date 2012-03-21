<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;

$xtpl = new XTemplate( "block_hot_playlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

//
$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active` = 1 ORDER BY `view` DESC LIMIT 0,8" );
while( $song = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'url_search_singer', $mainURL . "=search&amp;where=playlist&amp;q=" . urlencode( $song['singer'] ) . "&amp;type=singer" );
	$xtpl->assign( 'url_listen', $mainURL . "=listenuserlist/" . $song['id'] . "/" . $song['keyname'] );
	$xtpl->assign( 'name', $song['name'] );
	$xtpl->assign( 'singer', $song['singer'] );
	$xtpl->assign( 'view', $song['view'] );

	$img = rand( 1, 10 );
	$xtpl->assign( 'img', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . $img . ").jpg" );

	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>