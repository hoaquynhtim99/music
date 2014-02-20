<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Tim kiem va them mot album
if( $nv_Request->isset_request( 'findOneAndReturn', 'get' ) )
{
	$listalbum = filter_text_input( 'listalbum', 'get', '', 1, 255 );
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );

	$page_title = $classMusic->lang('getalbumid_title');
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 15;
	$array = array();

	// SQL va LINK co ban
	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`!=0";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;listalbum=" . $listalbum;

	// Du lieu tim kiem
	$data_search = array(
		"q" => filter_text_input( 'q', 'get', '', 1, 255 ),
		"singer" => filter_text_input( 'singer', 'get', '', 1, 255 ),
	);

	if( ! empty( $listalbum ) ) $sql .= " AND `id` NOT IN(" . $listalbum . ")";

	// Tim ten video
	if( ! empty( $data_search['q'] ) )
	{
		$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
		$sql .= " AND ( `tname` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
	}

	// Tim theo ca si
	if( ! empty ( $data_search['singer'] ) )
	{
		$base_url .= "&amp;singer=" . urlencode( $data_search['singer'] );
		$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_singer_id( $data_search['singer'], 5 ), 'casi' ) . " )";
	}

	// Order data
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
		"ASC" => $classMusic->lang('filter_lang_desc'),
	);
	$lang_order_2 = array(
		"title" => $classMusic->lang('album_name'),
	);

	$order['title']['order'] = filter_text_input( 'order_title', 'get', 'NO' );

	foreach( $order as $key => $check )
	{
		if( ! in_array( $check['order'], $check_order ) )
		{
			$order[$key]['order'] = "NO";
		}

		$order[$key]['data'] = array(
			"class" => "order" . strtolower( $order[$key]['order'] ),
			"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
			"title" => sprintf( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
		);
	}

	if( $order['title']['order'] != "NO" )
	{
		$sql .= " ORDER BY `tname` " . $order['title']['order'];
	}
	else
	{
		$sql .= " ORDER BY `id` DESC";
	}

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->sql_query( $sql1 );
	list( $all_page ) = $db->sql_fetchrow( $result1 );

	$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$result = $db->sql_query( $sql );

	$array = $array_singers = $array_authors =  array();
	$array_singer_ids = '';
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tname'],
			"singers" => $row['casi'],
			"thumb" => $row['thumb'],
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

	$xtpl = new XTemplate( "find_one_album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'LISTALBUM', $listalbum );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
	$xtpl->assign( 'DATA_ORDER', $order );
	$xtpl->assign( 'SEARCH', $data_search );
	$xtpl->assign( 'URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&listalbum=" . $listalbum );

	// Lay thong tin ca si
	$array_singer_ids = $classMusic->string2array( $array_singer_ids );

	if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
	
	$a = 0;
	foreach( $array as $row )
	{
		$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );

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

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Tim kiem va them nhieu album
if( $nv_Request->isset_request( 'findListAndReturn', 'get' ) )
{
	$listalbum = filter_text_input( 'listalbum', 'get', '', 1, 255 );
	
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );
	
	if( $nv_Request->isset_request( 'loadname', 'get' ) )
	{		
		$sql = "SELECT `id`, `tname` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id` IN(" . $listalbum . ")";
		$result = $db->sql_query( $sql );

		$list_album = array();
		$_tmp = array();
		while( list( $albumid, $albumname ) = $db->sql_fetchrow( $result ) )
		{
			$_tmp[$albumid] = $albumname;
		}
		
		$listalbum = $classMusic->string2array( $listalbum );
		foreach( $listalbum as $_sid )
		{
			if( isset( $_tmp[$_sid] ) ) $list_album[$_sid] = $_tmp[$_sid];
		}

		$return = "";
		foreach( $list_album as $_id => $_name )
		{
			$return .= "<li class=\"" . $_id . "\">" . $_name . "<span onclick=\"nv_del_item_on_list(" . $_id . ", '" . $returnArea . "', '" . $classMusic->lang('author_del_confirm') . "', '" . $returnInput . "')\" class=\"delete-icon\">&nbsp;</span></li>";
		}

		include ( NV_ROOTDIR . "/includes/header.php" );
		echo ( $return );
		include ( NV_ROOTDIR . "/includes/footer.php" );
		die();
	}
	
	$listalbum = $classMusic->string2array( $listalbum );

	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album`";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findListAndReturn=1";

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->sql_query( $sql1 );
	list( $all_page ) = $db->sql_fetchrow( $result1 );

	$sql .= " ORDER BY `id` DESC";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 5;

	$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$query2 = $db->sql_query( $sql2 );

	$array = $array_singers = $array_authors =  array();
	$array_singer_ids = '';
	while( $row = $db->sql_fetchrow( $query2 ) )
	{
		$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tname'],
			"singers" => $row['casi'],
			"thumb" => $row['thumb'],
			"checked" => in_array( $row['id'], $listalbum ) ? " checked=\"checked\"" : ""
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, "nv_load_page", "data" );

	$xtpl = new XTemplate( "find_list_album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'LISTALBUM', implode( ",", $listalbum ) );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );

	if( ! empty( $array ) )
	{
		// Lay thong tin ca si, nhac si
		$array_singer_ids = $classMusic->string2array( $array_singer_ids );

		if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
		
		$a = 0;
		foreach( $array as $row )
		{
			$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );

			$xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.data.row' );
			$a++;
		}

		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.data.generate_page' );
		}

		$xtpl->parse( 'main.data' );
	}

	if( $nv_Request->isset_request( 'getdata', 'get' ) )
	{
		$contents = $xtpl->text( 'main.data' );
	}
	else
	{
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo ( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Xoa album
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
	
	$albums = $classMusic->getalbumbyID( $listid );
	
	if( sizeof( $albums ) != $num ) die( 'NO' );
	
	foreach( $albums as $id => $album )
	{
		// Xoa trong album trang chu
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` WHERE `albumid`=" . $id;
		$db->sql_query( $sql );

		// Xoa album HOT
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` WHERE `albumid`=" . $id;
		$db->sql_query( $sql );

		// Xoa album
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id;
		$db->sql_query( $sql );

		$classMusic->fix_singer( $classMusic->string2array( $album['casi'] ) );
		$classMusic->delcomment( 'album', $album['id'] );
		$classMusic->delerror( 'album', $album['id'] );

		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=0 WHERE `album`=" . $id );
	}	
    
    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $classMusic->lang('delete_album'), implode( ", ", array_keys( $albums ) ), $admin_info['userid'] );
	
    die( "OK" );
}

// Thay doi hoat dong album
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
		$num = sizeof( $array_id );
	}
	
	$albums = $classMusic->getalbumbyID( $listid );
	
	if( sizeof( $albums ) != $num ) die( 'NO' );
	
	$array_status = array();
	
	foreach( $albums as $album )
	{
		$id = $album['id'];
		$active = $album['active'];
		
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
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `active`=" . $active . " WHERE `id`=" . $id;
		$db->sql_query( $sql );	
	}	
    
    nv_del_moduleCache( $module_name );
	
    die( "OK" );
}

// Tieu de trang
$page_title = $classMusic->lang('album');

// Goi Shadowbox
$classMusic->callJqueryPlugin('shadowbox');

// Thong tin phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

// Query, url co so
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
	"q" => filter_text_input( 'q', 'get', '', 1, 100 ),
	"singer" => filter_text_input( 'singer', 'get', '', 1, 100 ),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty ( $data_search['q'] ) or ! empty ( $data_search['singer'] ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND `tenthat` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
}

if( ! empty ( $data_search['singer'] ) )
{
	$base_url .= "&amp;singer=" . urlencode( $data_search['singer'] );
	$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_singer_id( $data_search['singer'], 5 ), 'casi' ) . " )";
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
	"title" => $classMusic->lang('song_name'),
	"numview" => $classMusic->lang('song_numvew'),
	"addtime" => $classMusic->lang('playlist_time')
);

$order['title']['order'] = filter_text_input( 'order_title', 'get', 'NO' );
$order['numview']['order'] = filter_text_input( 'order_numview', 'get', 'NO' );
$order['addtime']['order'] = filter_text_input( 'order_addtime', 'get', 'NO' );

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
	$sql .= " ORDER BY `numview` " . $order['numview']['order'];
}
elseif( $order['addtime']['order'] != "NO" )
{
	$sql .= " ORDER BY `addtime` " . $order['addtime']['order'];
}
else
{
	$sql .= " ORDER BY `id` DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

// Xay dung du lieu album
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

$array = $array_singers =  array();
$array_singer_ids = '';
while( $row = $db->sql_fetchrow( $result ) )
{
	$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];
	$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";
	
	$array[] = array(
		"id" => $row['id'],
		"title" => $row['tname'],
		"singers" => $row['casi'],
		"thumb" => $row['thumb'],
		"numview" => $row['numview'],
		"numsong" => $row['numsong'],
		"addtime" => nv_date( "H:i d/m/Y", $row['addtime'] ),
		"status" => $row['active'] ? " checked=\"checked\"" : "",
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-album&amp;id=" . $row['id'],
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

$xtpl = new XTemplate( "album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-album" );

foreach( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

// Lay thong tin ca si
$array_singer_ids = $classMusic->string2array( $array_singer_ids );
if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );

foreach( $array as $row )
{
	$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );
	
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