<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_file, $module_info, $mainURL, $nv_Request;

$xtpl = new XTemplate( "block_search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$key_search = $nv_Request->get_string( 'music_search_key', 'session', '' );
$type_search = $nv_Request->get_string( 'music_search_type', 'session', '' );

$search = array( "name", "singer", "album", "playlist" );

foreach( $search as $type )
{
	$xtpl->assign( 'ID', $type );
	$xtpl->assign( 'TITLE', ( $type != "playlist" ) ? $lang_module['search_with_' . $type] : $lang_module['search_' . $type] );
	$xtpl->assign( 'SELECTED', ( $type == $type_search ) ? " selected=\"selected\"" : "" );
	$xtpl->parse( 'main.type' );
}

$xtpl->assign( 'KEY', $key_search );
$xtpl->assign( 'search_action', $mainURL . "=search" );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>