<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $module_name;

$xtpl = new XTemplate( "block_mainalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'title', $lang_module['topics'] );

$sql = "SELECT a.albumid, b.tname, b.name FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS b ON a.albumid=b.id WHERE b.active=1 ORDER BY a.order ASC";
$list = nv_db_cache( $sql, 0, $module_name );

foreach( $list as $row )
{
	$xtpl->assign( 'name', $row['tname'] );
	$xtpl->assign( 'url', $mainURL . "=listenlist/" . $row['albumid'] . "/" . $row['name'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>