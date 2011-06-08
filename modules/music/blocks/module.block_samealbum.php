<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $array_op, $op;

if ( $op == "listenone" )
{
	$xtpl = new XTemplate( "block_samealbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	$songid = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 AND `id`!=" . $songid . " AND album =( SELECT `album` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $songid . " LIMIT 1 ) ORDER BY id DESC LIMIT 0,10";

	$query = $db->sql_query( $sql );
	while($song =  $db->sql_fetchrow( $query ))
	{
		$xtpl->assign( 'url_listen', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );
		$xtpl->assign( 'song_name', $song['tenthat'] );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	$content = $xtpl->text( 'main' );
}

?>