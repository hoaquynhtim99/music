<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 08:39 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['check_song'];

$xtpl = new XTemplate( "check_link.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delallsr" );
$xtpl->assign( 'URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=error" );

$setting = setting_music();
$listall = $nv_Request->get_string( 'listall', 'post,get' );

$array_id = explode( ',', $listall );
$array_id = array_map( "intval", $array_id );
$array_id = array_filter( $array_id );

$result = false;
$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit_album = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addalbum";
$link_edit_song = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong";

// Lay du lieu
$sql = "SELECT `id`, `sid`, `where` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `id` IN(" . implode( ",", $array_id ) . ")";
$result = $db->sql_query( $sql );

$i = 1;
while( $row = $db->sql_fetchrow( $result ) )
{
	if( $row['where'] == "album" )
	{
		$album = getalbumbyID( $row['sid'] );

		$xtpl->assign( 'id', $row['id'] );
		$xtpl->assign( 'songname', $lang_module['album'] . ": " . $album['tname'] );
		$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_album&id=" . $album['id'] );
		$xtpl->assign( 'URL_EDIT', $link_edit_album . "&id=" . $album['id'] );
		$xtpl->assign( 'URL', $mainURL . "=listenlist/" . $album['id'] . "/" . $album['name'] );
		$xtpl->assign( 'result', $lang_module['check_link_manual'] );
	}
	else
	{
		$song = getsongbyID( $row['sid'] );

		$xtpl->assign( 'id', $song['id'] );
		$xtpl->assign( 'songname', $lang_module['song'] . ": " . $song['tenthat'] );
		$xtpl->assign( 'URL_DEL_ONE', $link_del . "&id=" . $song['id'] );
		$xtpl->assign( 'URL_EDIT', $link_edit_song . "&id=" . $song['id'] );
		$xtpl->assign( 'URL', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );

		$url = outputURL( $song['server'], $song['duongdan'] );
		if( $song['server'] == 1 )
		{
			$url = NV_MY_DOMAIN . $url;
		}
		if( nv_check_url( $url ) )
		{
			$xtpl->assign( 'result', $lang_module['check_link_suc'] );
		}
		else
		{
			$xtpl->assign( 'result', $lang_module['check_link_err1'] );
		}
	}

	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );

	$xtpl->parse( 'main.loop' );
	++$i;
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>