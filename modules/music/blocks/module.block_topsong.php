<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;
 
$xtpl = new XTemplate( "block_top_song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `active`=1 ORDER BY `numview` DESC LIMIT 0,10";
$query = $db->sql_query( $sql );

while( $song =  $db->sql_fetchrow( $query ) )
{	
	$song['url_listen'] = $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'];
	$xtpl->assign( 'ROW', $song );
	
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>