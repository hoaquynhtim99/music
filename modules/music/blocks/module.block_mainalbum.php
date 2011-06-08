<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;

$xtpl = new XTemplate( "block_mainalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'title', $lang_module['topics'] );

$sql = "SELECT a.albumid, b.tname, b.name FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS b ON a.albumid=b.id ORDER BY a.order ASC";
$query = $db->sql_query( $sql );

while( $data =  $db->sql_fetchrow( $query ) )
{
	$xtpl->assign( 'name', $data['tname'] );
	$xtpl->assign( 'url', $mainURL . "=listenlist/" . $data['albumid'] . "/" . $data['name'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>