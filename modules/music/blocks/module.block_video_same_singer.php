<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $op;

$xtpl = new XTemplate( "block_video_same_singer.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if ( $op == "viewvideo" )
{
	$videoid = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE `active` = 1 AND `id`!=" . $videoid . " AND `casi` =( SELECT `casi` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`=" . $videoid . " LIMIT 1 ) ORDER BY id DESC LIMIT 0,5";
	$query = $db->sql_query( $sql );

	while( $video =  $db->sql_fetchrow( $query ) )
	{
		if ( $data['id'] == $video['id'] ) continue;
		$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" .$video['id']. "/" . $video['name'] );
		$xtpl->assign( 'video_name', $video['tname'] );
		$xtpl->assign( 'thumb', $video['thumb'] );
		$xtpl->assign( 'view', $video['view'] );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	$content = $xtpl->text( 'main' );
}

?>