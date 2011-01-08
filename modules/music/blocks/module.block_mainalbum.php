<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL;


$xtpl = new XTemplate( "block_mainalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'title', $lang_module['topics'] );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_main_album ORDER BY `order` ASC";
$query = mysql_query( $sql );
while($song =  mysql_fetch_array( $query ))
{
	$sqla = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id=".$song['albumid']."";
	$querya = mysql_query( $sqla );
	$albumname = mysql_fetch_array( $querya );
	$xtpl->assign( 'name', $albumname['tname'] );
	$xtpl->assign( 'url', $mainURL . "=listenlist/" .$song['albumid']);
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>