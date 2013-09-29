<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Xoa videoclip
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $list_levelid = filter_text_input( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $list_levelid ) ) die( "NO" );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$list_levelid = explode ( ",", $list_levelid );
		$list_levelid = array_map ( "trim", $list_levelid );
		$list_levelid = array_filter ( $list_levelid );

		$listid = $list_levelid;
		$num = sizeof( $list_levelid );
	}
	
	$videoclips = $classMusic->getsongbyID( $listid );
	
	if( sizeof( $videoclips ) != $num ) die( 'NO' );
	
	foreach( $videoclips as $id => $video )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		
		$classMusic->fix_singer( $classMusic->string2array( $video['casi'] ) );
		$classMusic->fix_author( $classMusic->string2array( $video['nhacsi'] ) );
		$classMusic->delcomment( 'video', $video['id'] );
		$classMusic->fix_cat_video( array_unique( array_filter( array_merge_recursive( $video['listcat'], array( $video['theloai'] ) ) ) ) );
		$classMusic->unlinkSV( $video['server'], $video['duongdan'] );
	}
    
    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $classMusic->lang('delete_video'), implode( ", ", array_keys( $videoclips ) ), $admin_info['userid'] );
	
    die( "OK" );
}

// Thay doi hoat dong videoclip
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $controlstatus = $nv_Request->get_int( 'status', 'post', 0 );
    $array_id = filter_text_input( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $array_id ) ) die( "NO" );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$array_id = explode ( ",", $array_id );
		$array_id = array_map ( "trim", $array_id );
		$array_id = array_filter ( $array_id );

		$listid = $array_id;
		$num = count( $array_id );
	}
	
	// Lay thong tin
	$sql = "SELECT `id`, `active` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id` IN (" . implode ( ",", $listid ) . ")";
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
	
	if ( $check != $num ) die( "NO" );
	
	$array_status = array();
	$array_title = array();
	while ( list( $id, $active ) = $db->sql_fetchrow( $result ) )
	{		
		if ( empty ( $controlstatus ) )
		{
			$array_status[$id] = $active ? 0 : 1;
		}
		else
		{
			$array_status[$id] = ( $controlstatus == 1 ) ? 1 : 0;
		}
	}
	
	foreach( $array_status as $id => $active )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `active`=" . $active . " WHERE `id`=" . $id;
		$db->sql_query( $sql );	
	}	
    
    nv_del_moduleCache( $module_name );
	
    die( "OK" );
}

// Tieu de trang
$page_title = $classMusic->lang('video_list');

// Goi Shadowbox
$classMusic->callJqueryPlugin('shadowbox');

// Thong tin phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

// Query, url co so
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
	"q" => filter_text_input( 'q', 'get', '', 1, 100 ),
	"singer" => filter_text_input( 'singer', 'get', '', 1, 100 ),
	"author" => filter_text_input( 'author', 'get', '', 1, 100 ),
	"theloai" => $nv_Request->get_int( 'theloai', 'get', -1 ),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty ( $data_search['q'] ) or ! empty ( $data_search['singer'] ) or ! empty ( $data_search['author'] ) or $data_search['theloai'] > -1 )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	
	// Tim theo ten videoclip
	$sql .= " AND ( `tname` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
}

if( ! empty ( $data_search['singer'] ) )
{
	$base_url .= "&amp;singer=" . urlencode( $data_search['singer'] );
	$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_singer_id( $data_search['singer'], 5 ), 'casi' ) . " )";
}

if( ! empty ( $data_search['author'] ) )
{
	$base_url .= "&amp;author=" . urlencode( $data_search['author'] );
	$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_author_id( $data_search['author'], 5 ), 'nhacsi' ) . " )";
}

if( $data_search['theloai'] > -1 )
{
	$base_url .= "&amp;theloai=" . $data_search['theloai'];
	$sql .= " AND (`theloai`=" . $data_search['theloai'] . " OR " . $classMusic->build_query_search_id( $data_search['theloai'], 'listcat' ) . " )";
}

// Du lieu sap xep
$order = array();
$check_order = array( "ASC", "DESC", "NO" );
$opposite_order = array(
	"NO" => "ASC",
	"DESC" => "ASC",
	"ASC" => "DESC"
);
$lang_order_1 = array(
	"NO" => $classMusic->lang('filter_lang_asc'),
	"DESC" => $classMusic->lang('filter_lang_asc'),
	"ASC" => $classMusic->lang('filter_lang_desc')
);
$lang_order_2 = array(
	"title" => $classMusic->lang('video_name'),
	"numview" => $classMusic->lang('song_numvew'),
	"dt" => $classMusic->lang('playlist_time')
);

$order['title']['order'] = filter_text_input( 'order_title', 'get', 'NO' );
$order['numview']['order'] = filter_text_input( 'order_numview', 'get', 'NO' );
$order['dt']['order'] = filter_text_input( 'order_dt', 'get', 'NO' );

foreach ( $order as $key => $check )
{
	$order[$key]['data'] = array(
		"class" => "order" . strtolower ( $order[$key]['order'] ),
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
		"title" => sprintf ( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
	);
	
	if ( ! in_array ( $check['order'], $check_order ) )
	{
		$order[$key]['order'] = "NO";
	}
	else
	{
		$base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
	}
}

if( $order['title']['order'] != "NO" )
{
	$sql .= " ORDER BY `tname` " . $order['title']['order'];
}
elseif( $order['numview']['order'] != "NO" )
{
	$sql .= " ORDER BY `view` " . $order['numview']['order'];
}
elseif( $order['dt']['order'] != "NO" )
{
	$sql .= " ORDER BY `dt` " . $order['dt']['order'];
}
else
{
	$sql .= " ORDER BY `id` DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

// Xay dung du lieu videoclip
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

$array = $array_singers = $array_authors =  array();
$array_singer_ids = $array_author_ids = '';
while( $row = $db->sql_fetchrow( $result ) )
{
	$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];
	$array_author_ids = $array_author_ids == '' ? $row['nhacsi'] : $array_author_ids . "," . $row['nhacsi'];

	$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-videoclip.png";
	
	$array[] = array(
		"id" => $row['id'],
		"theloai" => $row['theloai'] . "," . $row['listcat'],
		"title" => $row['tname'],
		"thumb" => $row['thumb'],
		"singers" => $row['casi'],
		"authors" => $row['nhacsi'],
		"numview" => $row['view'],
		"addtime" => nv_date( "H:i d/m/Y", $row['dt'] ),
		"status" => $row['active'] ? " checked=\"checked\"" : "",
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-videoclip&amp;id=" . $row['id'],
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""
	);
	$i ++;
}

// Cac thao tac
$list_action = array(
	0 => array(
		"key" => 1,
		"class" => "delete",
		"title" => $classMusic->glang('delete')
	),
	1 => array(
		"key" => 2,
		"class" => "status-ok",
		"title" => $classMusic->lang('action_status_ok')
	),
	2 => array(
		"key" => 3,
		"class" => "status-no",
		"title" => $classMusic->lang('action_status_no')
	)
);

// Phan trang
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "videoclip.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA_SEARCH', $data_search );
$xtpl->assign( 'DATA_ORDER', $order );
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content-videoclip" );

$global_array_cat_videoclip = $classMusic->get_videocategory();
foreach( $global_array_cat_videoclip as $cat )
{
	$cat['selected'] = $cat['id'] == $data_search['theloai'] ? " selected=\"selected\"" : "";
	
	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

foreach( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

// Lay thong tin ca si, nhac si
$array_singer_ids = $classMusic->string2array( $array_singer_ids );
$array_author_ids = $classMusic->string2array( $array_author_ids );

if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
if( ! empty( $array_author_ids ) ) $array_authors = $classMusic->getauthorbyID( $array_author_ids );

foreach( $array as $row )
{
	$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );
	$row['authors'] = $classMusic->build_author_singer_2string( $array_authors, $row['authors'] );
	$row['theloai'] = $classMusic->build_categories_2tring( $global_array_cat_videoclip, $row['theloai'] );
	
	$xtpl->assign( 'ROW', $row );
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