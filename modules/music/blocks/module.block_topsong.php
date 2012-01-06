<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;
 
$xtpl = new XTemplate( "block_top_song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT a.id, a.ten, a.tenthat, a.casi, a.numview, b.tenthat AS casithat FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten WHERE a.active=1 ORDER BY a.numview DESC LIMIT 0,10";
$result = $db->sql_query( $sql );

while( $row =  $db->sql_fetchrow( $result ) )
{	
	$row['url_listen'] = $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'];
	$row['url_search_singer'] = $mainURL . "=search/singer/" . $row['casi'];
	$row['casithat'] = empty( $row['casithat'] ) ? $lang_module['unknow'] : $row['casithat'];
	
	$xtpl->assign( 'ROW', $row );
	
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>