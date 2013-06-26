<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $op, $module_name, $array_op;

$xtpl = new XTemplate( "block_video_same_singer.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( $op == "viewvideo" )
{
	$videoid = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE `active` = 1 AND `id`!=" . $videoid . " AND `casi` =( SELECT `casi` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`=" . $videoid . " LIMIT 1 ) ORDER BY `id` DESC LIMIT 0,5";

	$list = nv_db_cache( $sql, 'id', $module_name );

	if( ! empty( $list ) )
	{
		foreach( $list as $row )
		{
			$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'] );
			$xtpl->assign( 'video_name', $row['tname'] );
			$xtpl->assign( 'thumb', $row['thumb'] );
			$xtpl->assign( 'view', $row['view'] );
			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		$content = $xtpl->text( 'main' );
	}
}

?>