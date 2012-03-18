<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $array_op, $op, $module_name;

if( $op == "listenone" )
{
	$xtpl = new XTemplate( "block_samesinger.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	$songid = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

	$sql = "SELECT `id`, `ten`, `tenthat` FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 AND `id`!=" . $songid . " AND `casi` =( SELECT `casi` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $songid . " LIMIT 1 ) ORDER BY `id` DESC LIMIT 0,10";

	$list = nv_db_cache( $sql, 'id', $module_name );

	if( ! empty( $list ) )
	{
		foreach( $list as $row )
		{
			$xtpl->assign( 'url_listen', $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'] );
			$xtpl->assign( 'song_name', $row['tenthat'] );
			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		$content = $xtpl->text( 'main' );
	}
}

?>