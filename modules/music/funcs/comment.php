<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
$contents = "";

$id = $nv_Request->get_int( 'id', 'get', 0 );
$where = filter_text_input( 'where', 'get', '', 1 );
$page = $nv_Request->get_int( 'page', 'get', 0 );

$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info ['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ID', $id );
$xtpl->assign( 'WHERE', $where );

// so binh luan
$num = $db->sql_numrows( $db->sql_query( "SELECT *  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` WHERE what = " . $id . " AND `active` = 1 " ) );
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . " WHERE what = ". $id ." AND `active` = 1 ORDER BY id DESC LIMIT " . $page . ",8";
$query = $db->sql_query( $sql );

// phan trang binh luan
if (  $num > ( $page + 8 ) )
{
	$next = $page + 8 ;
	$xtpl->assign( 'next', $next );
		$xtpl->parse( 'main.next' );			
}
if ( $page > 0 )
{
	$prev = $page - 8 ;
	$xtpl->assign( 'prev', $prev );
	$xtpl->parse( 'main.prev' );					
}
// xuat binh luan
require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
while ( $row = $db->sql_fetchrow( $query ) )
{

	$sqla = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid = ". $row['userid'] ;
	$querya = $db->sql_query( $sqla );
	$avatar = $db->sql_fetchrow( $querya )  ;
	if ( $avatar['photo'] != '' )
	{
		$xtpl->assign( 'avatar', NV_BASE_SITEURL . $avatar['photo'] );
	}
	else
	{
		$xtpl->assign( 'avatar', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/default.jpg");
	}
	$data = m_emotions_replace( $row['body'] );
	$xtpl->assign( 'name', $row['name'] );
	$xtpl->assign( 'date', nv_date( "d/m/Y H:i", $row['dt'] ) );
	$xtpl->assign( 'body', $data );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
?>