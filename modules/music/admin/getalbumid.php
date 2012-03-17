<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$area = filter_text_input( 'area', 'get', '' );
if( empty( $area ) )
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

$page_title = $lang_module['getaid_title'];

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
$xtpl->assign( 'AREA', $area );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;
$array = array();

// Base data
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten WHERE a.id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;area=" . $area;

// Search data
$data_search = array(
	"title" => filter_text_input( 'title', 'get', '', 1, 255 ), //
	"casi" => filter_text_input( 'casi', 'get', '', 1, 255 ), //
	"describe" => filter_text_input( 'describe', 'get', '', 1, 255 ), //
	"upboi" => filter_text_input( 'upboi', 'get', '', 1, 255 ), //
	);

$xtpl->assign( 'SEARCH', $data_search );

if( ! empty( $data_search['title'] ) )
{
	$base_url .= "&amp;title=" . $data_search['title'];
	$sql .= " AND ( a.tname LIKE '%" . $db->dblikeescape( $data_search['title'] ) . "%' )";
}

if( ! empty( $data_search['casi'] ) )
{
	$base_url .= "&amp;casi=" . $data_search['casi'];
	$sql .= " AND ( b.tenthat LIKE '%" . $db->dblikeescape( $data_search['casi'] ) . "%' )";
}

if( ! empty( $data_search['describe'] ) )
{
	$base_url .= "&amp;describe=" . $data_search['describe'];
	$sql .= " AND ( a.describe LIKE '%" . $db->dblikeescape( $data_search['describe'] ) . "%' )";
}

if( ! empty( $data_search['upboi'] ) )
{
	$base_url .= "&amp;upboi=" . $data_search['upboi'];
	$sql .= " AND ( a.upboi LIKE '%" . $db->dblikeescape( $data_search['upboi'] ) . "%' )";
}

$array = array();

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;

$sql2 = "SELECT a.id, a.name, a.tname, a.upboi, b.tenthat " . $sql . " ORDER BY a.id DESC LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

while( $row = $db->sql_fetchrow( $query2 ) )
{
	$array[$row['id']] = array(
		"id" => $row['id'], //
		"name" => $row['name'], //
		"tname" => $row['tname'], //
		"upboi" => $row['upboi'], //
		"tenthat" => $row['tenthat'] //
			);
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
exit();

?>