<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;

$xtpl = new XTemplate( "block_top_video.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT a.id, a.name, a.tname, a.thumb, a.casi, a.view, b.ten, b.tenthat FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 ORDER BY a.view DESC LIMIT 0,8";
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	$singername = empty( $row['tenthat'] ) ? $lang_module['unknow'] : $row['tenthat'];
	
	$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'] );
	$xtpl->assign( 'video_name', $row['tname'] );
	$xtpl->assign( 'thumb', $row['thumb'] );
	$xtpl->assign( 'view', $row['view'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" );
	$xtpl->assign( 'singer', $singername );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>