<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Cap nhat tinh trang cac bao loi
if( $nv_Request->isset_request( 'setviewed', 'post' ) )
{
	$id = $nv_Request->get_int( 'setviewed', 'post' );
	if( ! $id ) die( "Error Access!!!" );

	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_error` SET `status`=0 WHERE `id`=" . $id;
	$db->sql_query( $sql );
	die( "OK" );
}

$page_title = $lang_module['error_list'];

// Ket qua
$xtpl = new XTemplate( "error.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=error" );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_error" );
$xtpl->assign( 'URL_CHECK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checksonglist" );

// Lay du lieu
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` AS a LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` AS b ON a.userid=b.userid";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 10;

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";

$sql2 = "SELECT a.id, a.userid, a.username AS full_name, a.body, a.sid, a.where, b.username, a.addtime, a.ip, a.status " . $sql . " ORDER BY `status` DESC, `addtime` DESC LIMIT " . $page . ", " . $per_page;
$result2 = $db->sql_query( $sql2 );

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

while( $row = $db->sql_fetchrow( $result2 ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'name', $row['userid'] ? "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['userid'] . "\" title=\"" . $row['username'] . "\">" . $row['username'] . "</a>" : $row['full_name'] . " - " . $lang_module['visittor'] );
	$xtpl->assign( 'body', $row['body'] );
	$xtpl->assign( 'ip', $row['ip'] );
	$xtpl->assign( 'status', $row['status'] ? $lang_module['view_non'] : $lang_module['view_ed'] );
	$xtpl->assign( 'icon', $row['status'] ? "view" : "select" );
	$xtpl->assign( 'atitle', $row['status'] ? $lang_module['error_check_viewed'] : "" );
	$xtpl->assign( 'addtime', nv_date( "d/m/Y H:i", $row['addtime'] ) );

	if( $row['where'] == 'song' )
	{
		$album = getsongbyID( $row['sid'] );
		$xtpl->assign( 'what', $lang_module['song'] . ' ' . $album['tenthat'] );
		$xtpl->assign( 'url_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addsong&amp;id=" . $row['sid'] );

		$xtpl->assign( 'SONG', $row['sid'] );
		$xtpl->parse( 'main.row.check' );
	}
	else
	{
		$song = getalbumbyID( $row['sid'] );
		$xtpl->assign( 'what', $lang_module['album'] . ' ' . $song['tname'] );
		$xtpl->assign( 'url_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addalbum&amp;id=" . $row['sid'] );
	}

	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_error&id=" . $row['id'] );
	$xtpl->parse( 'main.row' );
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>