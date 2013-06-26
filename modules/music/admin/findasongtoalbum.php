<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$songlist = filter_text_input( 'songlist', 'get', '' );

$all_auhtor = getallauthor();

$category = get_category();
if( count( $category ) == 0 )
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	die();
}

$page_title = $lang_module['getaid_title'];
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;
$array = array();

// Base data
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten WHERE a.id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;songlist=" . $songlist;

// Search data
$data_search = array(
	"ten" => filter_text_input( 'ten', 'get', '', 1, 255 ), //
	"casi" => filter_text_input( 'casi', 'get', '', 1, 255 ), //
	"nhacsi" => filter_text_input( 'nhacsi', 'get', '', 1, 255 ), //
	"album" => filter_text_input( 'album', 'get', '', 1, 255 ), //
	"upboi" => filter_text_input( 'upboi', 'get', '', 1, 255 ), //
	"from" => filter_text_input( 'from', 'get', '', 1, 255 ), //
	"to" => filter_text_input( 'to', 'get', '', 1, 255 ), //
	"theloai" => $nv_Request->get_int( 'theloai', 'get', 0 ) );

if( ! empty( $songlist ) )
{
	$sql .= " AND a.id NOT IN(" . $songlist . ")";
}

if( ! empty( $data_search['ten'] ) )
{
	$base_url .= "&amp;ten=" . $data_search['ten'];
	$sql .= " AND ( a.tenthat LIKE '%" . $db->dblikeescape( $data_search['ten'] ) . "%' )";
}

if( ! empty( $data_search['casi'] ) )
{
	$base_url .= "&amp;casi=" . $data_search['casi'];
	$sql .= " AND ( b.tenthat LIKE '%" . $db->dblikeescape( $data_search['casi'] ) . "%' )";
}

if( ! empty( $data_search['album'] ) )
{
	$base_url .= "&amp;album=" . $data_search['album'];
	$sql .= " AND ( a.album LIKE '%" . $db->dblikeescape( change_alias( $data_search['album'] ) ) . "%' )";
}

if( ! empty( $data_search['upboi'] ) )
{
	$base_url .= "&amp;upboi=" . $data_search['upboi'];
	$sql .= " AND ( a.upboi LIKE '%" . $db->dblikeescape( $data_search['upboi'] ) . "%' )";
}

if( ! empty( $data_search['nhacsi'] ) )
{
	$base_url .= "&amp;nhacsi=" . $data_search['nhacsi'];
	$sql .= " AND ( a.nhacsi LIKE '%" . $db->dblikeescape( $data_search['nhacsi'] ) . "%' )";
}

if( ! empty( $data_search['theloai'] ) )
{
	$base_url .= "&amp;theloai=" . $data_search['theloai'];
	$sql .= " AND (a.theloai=" . $data_search['theloai'] . ")";
}

if( ! empty( $data_search['from'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match ) )
	{
		$from = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND a.dt >= " . $from;
		$base_url .= "&amp;from=" . $data_search['from'];
	}
}

if( ! empty( $data_search['to'] ) )
{
	unset( $match );
	if( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match ) )
	{
		$to = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND a.dt <= " . $to;
		$base_url .= "&amp;to=" . $data_search['to'];
	}
}

// Order data
$order = array();
$check_order = array(
	"ASC",
	"DESC",
	"NO" );
$opposite_order = array(
	"NO" => "ASC", //
	"DESC" => "ASC", //
	"ASC" => "DESC" //
		);
$lang_order_1 = array(
	"NO" => $lang_module['filter_lang_asc'], //
	"DESC" => $lang_module['filter_lang_asc'], //
	"ASC" => $lang_module['filter_lang_desc'] //
		);
$lang_order_2 = array(
	"ten" => $lang_module['song_name'], //
	"casi" => $lang_module['singer'], //
	"nhacsi" => $lang_module['author'], //
	"album" => $lang_module['album'], //
	"theloai" => $lang_module['category'], //
	"dt" => $lang_module['dt'] //
		);

$order['ten']['order'] = filter_text_input( 'order_ten', 'get', 'NO' );
$order['casi']['order'] = filter_text_input( 'order_casi', 'get', 'NO' );
$order['nhacsi']['order'] = filter_text_input( 'order_nhacsi', 'get', 'NO' );
$order['album']['order'] = filter_text_input( 'order_album', 'get', 'NO' );
$order['theloai']['order'] = filter_text_input( 'order_theloai', 'get', 'NO' );
$order['dt']['order'] = filter_text_input( 'order_dt', 'get', 'NO' );

foreach( $order as $key => $check )
{
	if( ! in_array( $check['order'], $check_order ) )
	{
		$order[$key]['order'] = "NO";
	}

	$order[$key]['data'] = array(
		"class" => "order" . strtolower( $order[$key]['order'] ), //
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']], //
		"title" => sprintf( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']] //
			);
}

if( $order['ten']['order'] != "NO" )
{
	$sql .= " ORDER BY a.tenthat " . $order['ten']['order'];
}
elseif( $order['casi']['order'] != "NO" )
{
	$sql .= " ORDER BY b.tenthat " . $order['casi']['order'];
}
elseif( $order['nhacsi']['order'] != "NO" )
{
	$sql .= " ORDER BY a.nhacsi " . $order['nhacsi']['order'];
}
elseif( $order['album']['order'] != "NO" )
{
	$sql .= " ORDER BY a.album " . $order['album']['order'];
}
elseif( $order['theloai']['order'] != "NO" )
{
	$sql .= " ORDER BY a.theloai " . $order['theloai']['order'];
}
elseif( $order['dt']['order'] != "NO" )
{
	$sql .= " ORDER BY a.dt " . $order['dt']['order'];
}
else
{
	$sql .= " ORDER BY a.dt DESC";
}

$array = array();

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

$sql = "SELECT a.id AS songid, a.tenthat AS song_title, a.upboi AS upboi, b.tenthat AS singer, a.nhacsi AS author, a.theloai AS theloai, a.dt AS dt " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	$array[$row['songid']] = array(
		"id" => $row['songid'], //
		"ten" => $row['song_title'], //
		"upboi" => $row['upboi'], //
		"singer" => $row['singer'], //
		"author" => $all_auhtor[$row['author']], //
		"theloai" => $category[$row['theloai']]['title'], //
		"dt" => nv_date( "d/y/Y h:i:s A", $row['dt'] ) //
			);
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'SONGLIST', $songlist );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
$xtpl->assign( 'DATA_ORDER', $order );
$xtpl->assign( 'SEARCH', $data_search );
$xtpl->assign( 'URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;songlist=" . $songlist );

$a = 0;
foreach( $array as $row )
{
	$xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
	$a++;
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

// Xuat the loai
$xtpl->assign( 'theloai', array(
	"id" => 0,
	"title" => $lang_module['filter_all_cat'],
	"selected" => "" ) );
$xtpl->parse( 'main.theloai' );
foreach( $category as $theloai )
{
	$theloai['selected'] = ( $theloai['id'] == $data_search['theloai'] ) ? " selected=\"selected\"" : "";

	$xtpl->assign( 'theloai', $theloai );
	$xtpl->parse( 'main.theloai' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
exit();

?>