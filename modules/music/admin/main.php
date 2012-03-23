<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_list'];

$category = get_category();
if( count( $category ) == 0 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category" );
	die();
}

$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );

$order = filter_text_input( 'order', 'get', 'id' );

if( $order == 'ten' )
{
	$sort = "ORDER BY a.tenthat ASC";
}
elseif( $order == 'casi' )
{
	$sort = "ORDER BY b.tenthat ASC";
}
elseif( $order == 'album' )
{
	$sort = "ORDER BY d.tname ASC";
}
else
{
	$sort = "ORDER BY a.id DESC";
}

$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$where_search = $nv_Request->get_int( 'where_search', 'get', 0 );
$type_search = filter_text_input( 'type_search', 'get', 'ten' );
$q = filter_text_input( 'q', 'get', '' );

if( $where_search == 0 )
{
	$theloai = "";
}
else
{
	$theloai = "a.theloai = " . $where_search . " AND";
}

$where = empty( $theloai ) ? '' : ' WHERE';
if( ! empty( $q ) )
{
	if( $type_search == 'ten' )
	{
		$where = "WHERE a.tenthat LIKE '%" . $q . "%'";
	}
	elseif( $type_search == 'casi' )
	{
		$where = "WHERE b.tenthat LIKE '%" . $q . "%'";
	}
	elseif( $type_search == 'nhacsi' )
	{
		$where = "WHERE c.tenthat LIKE '%" . $q . "%'";
	}
	elseif( $type_search == 'album' )
	{
		$where = "WHERE d.tname LIKE '%" . $q . "%'";
	}
}

// Xu li du lieu
if( $now_page == 0 )
{
	$now_page = 1;
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * $numshow;
}

$sqlnum = "SELECT a.id AS id, a.ten AS ten, a.tenthat AS tenthat, a.theloai AS theloai, a.casi AS casi, a.album AS album, a.active AS active, d.tname AS albumname, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_author` AS c ON a.nhacsi=c.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS d ON a.album=d.id " . $where . " " . $theloai . " " . $sort;

$sql = $sqlnum . " LIMIT " . $first_page . "," . $numshow;

$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&numshow=" . $numshow . "&where_search=" . $where_search . "&type_search=" . $type_search . "&q=" . $q . "&order=" . $order;

$num = $db->sql_query( $sqlnum );
$output = $db->sql_numrows( $num );
$ts = ceil( $output / $numshow );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall" );
$xtpl->assign( 'URL_ACTIVE_LIST', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=listactive&where=" );
$xtpl->assign( 'ORDER_NAME', $link . "&order=ten" );
$xtpl->assign( 'ORDER_SINGER', $link . "&order=casi" );
$xtpl->assign( 'ORDER_ALBUM', $link . "&order=album" );

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

// Tu khoa tim kiem
$xtpl->assign( 'Q', $q );

// Chon kieu tim
$xtpl->assign( 'TYPE_TEN', ( $type_search == 'ten' ) ? " selected=\"selected\"" : "" );
$xtpl->assign( 'TYPE_CASI', ( $type_search == 'casi' ) ? " selected=\"selected\"" : "" );
$xtpl->assign( 'TYPE_NHACSI', ( $type_search == 'nhacsi' ) ? " selected=\"selected\"" : "" );
$xtpl->assign( 'TYPE_ALBUM', ( $type_search == 'album' ) ? " selected=\"selected\"" : "" );

// Xuat the loai tim kiem
foreach( $category as $id => $title )
{
	$title['selected'] = ( $id == $where_search ) ? " selected=\"selected\"" : "";
	$xtpl->assign( 'CAT', $title );
	$xtpl->parse( 'main.cat' );
}

// So ket qua tim kiem
$i = 5;
while( $i <= 1000 )
{
	$xtpl->assign( 'SELECTED', ( $i == $numshow ) ? " selected=\"selected\"" : "" );
	$xtpl->assign( 'NUM', $i );
	$xtpl->assign( 'TITLE', $i );
	$xtpl->parse( 'main.numshow' );

	$i = $i + 10;
}

$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&id=";

while( $rs = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $rs['id'] );
	$xtpl->assign( 'name', $rs['tenthat'] );
	$xtpl->assign( 'singer', empty( $rs['singername'] ) ? $lang_module['unknow'] : $rs['singername'] );
	$xtpl->assign( 'URL', $mainURL . "=listenone/" . $rs['id'] . "/" . $rs['ten'] );

	$xtpl->assign( 'album', $rs['albumname'] );
	$xtpl->assign( 'category', $category[$rs['theloai']]['title'] );

	$class = ( $i % 2 ) ? " class=\"second\"" : "";
	$xtpl->assign( 'class', $class );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&id=" . $rs['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $rs['id'] );

	$str_ac = ( $rs['active'] == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'];
	$xtpl->assign( 'active', $str_ac );
	$xtpl->assign( 'URL_ACTIVE', $link_active . $rs['id'] );

	$xtpl->parse( 'main.row' );
}

$gen_page = new_page_admin( $ts, $now_page, $link );
if( ! empty( $gen_page ) )
{
	$xtpl->assign( 'genpage', $gen_page );
	$xtpl->parse( 'main.genpage' );
}

// Kiem tra cac ham can thiet de chay
$array_info = array();

$disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
if( extension_loaded( 'suhosin' ) )
{
	$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
}

if( ! ( extension_loaded( 'curl' ) and ( empty( $disable_functions ) or ( ! empty( $disable_functions ) and ! preg_grep( '/^curl\_/', $disable_functions ) ) ) ) )
{
	$array_info[] = $lang_module['info_check_curl'];
}

foreach( $array_info as $info )
{
	$xtpl->assign( 'INFO', $info );
	$xtpl->parse( 'main.info' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>