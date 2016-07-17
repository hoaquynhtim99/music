<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$area = $nv_Request->get_title( 'area', 'get', '' );
if( empty( $area ) )
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

$page_title = $classMusic->lang('getaid_title');

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
$xtpl->assign( 'PAGE_TITLE', $page_title );
$xtpl->assign( 'AREA', $area );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;
$array = array();

// Base data
$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;area=" . $area;

// Search data
$data_search = array(
	"title" => nv_substr( $nv_Request->get_title( 'title', 'get', '', 1 ), 0, 255),
	"casi" => nv_substr( $nv_Request->get_title( 'casi', 'get', '', 1 ), 0, 255),
	"describe" => nv_substr( $nv_Request->get_title( 'describe', 'get', '', 1 ), 0, 255),
	"upboi" => nv_substr( $nv_Request->get_title( 'upboi', 'get', '', 1 ), 0, 255),
);

$xtpl->assign( 'SEARCH', $data_search );

// Tim theo ten
if( ! empty( $data_search['title'] ) )
{
	$base_url .= "&amp;title=" . $data_search['title'];
	$sql .= " AND ( tname LIKE '%" . $db->dblikeescape( $data_search['title'] ) . "%' )";
}

// Tim theo ca si
if( ! empty( $data_search['casi'] ) )
{
	$base_url .= "&amp;casi=" . $data_search['casi'];
	
	// Tim kiem ca si
	$singer_id = $classMusic->search_singer_id( $data_search['casi'], 3 );
	if( $singer_id )
	{
		$sql .= " AND ( casi LIKE '%," . implode( ",%' OR casi LIKE '%,", $singer_id ) . ",%' )";
	}
	else
	{
		$sql .= " AND casi=''";
	}
}

if( ! empty( $data_search['describe'] ) )
{
	$base_url .= "&amp;describe=" . $data_search['describe'];
	$sql .= " AND ( describe LIKE '%" . $db->dblikeescape( $data_search['describe'] ) . "%' )";
}

if( ! empty( $data_search['upboi'] ) )
{
	$base_url .= "&amp;upboi=" . $data_search['upboi'];
	$sql .= " AND ( upboi LIKE '%" . $db->dblikeescape( $data_search['upboi'] ) . "%' )";
}

$array = array();

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;

$sql2 = "SELECT id, name, tname, upboi, casi " . $sql . " ORDER BY id DESC LIMIT " . $page . ", " . $per_page;
$query2 = $db->query( $sql2 );

$array_singer_id = array();
while( $row = $query2->fetch() )
{
	$array_singer_id[] = $row['casi'];

	$array[$row['id']] = array(
		"id" => $row['id'],
		"name" => $row['name'],
		"tname" => $row['tname'],
		"upboi" => $row['upboi'],
		"tenthat" => $row['casi'], // Ca si
	);
}

// Lay ca si
$array_singer_id = $classMusic->string2array( implode( ",", $array_singer_id ) );
$array_singer = $classMusic->getsingerbyID( $array_singer_id );

// Build lai ca si
foreach( $array as $_key => $_val )
{
	$array[$_key]['tenthat'] = $classMusic->build_author_singer_2string( $array_singer, $array[$_key]['tenthat'] );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

if( ! empty( $array ) )
{
	$a = 0;
	foreach( $array as $row )
	{
		$xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.resultdata.data.row' );
		$a++;
	}

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.resultdata.data.generate_page' );
	}

	$xtpl->parse( 'main.resultdata.data' );
}
else
{
	$xtpl->parse( 'main.resultdata.nodata' );
}

$xtpl->parse( 'main.resultdata' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
exit();