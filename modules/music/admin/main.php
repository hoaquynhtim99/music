<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Tim kiem va them mot bai hat
if( $nv_Request->isset_request( 'findOneAndReturn', 'get' ) )
{
	$listsong = filter_text_input( 'listsong', 'get', '', 1, 255 );
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );

	$page_title = $classMusic->lang('getsongid_title');
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 15;
	$array = array();

	// SQL va LINK co ban
	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`!=0";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;listsong=" . $listsong;

	// Du lieu tim kiem
	$data_search = array(
		"q" => filter_text_input( 'q', 'get', '', 1, 255 ),
		"singer" => filter_text_input( 'singer', 'get', '', 1, 255 ),
		"author" => filter_text_input( 'author', 'get', '', 1, 255 ),
	);

	if( ! empty( $listsong ) ) $sql .= " AND `id` NOT IN(" . $listsong . ")";

	// Tim ten bai hat
	if( ! empty( $data_search['q'] ) )
	{
		$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
		$sql .= " AND ( `tenthat` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
	}

	// Tim theo ca si
	if( ! empty ( $data_search['singer'] ) )
	{
		$base_url .= "&amp;singer=" . urlencode( $data_search['singer'] );
		$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_singer_id( $data_search['singer'], 5 ), 'casi' ) . " )";
	}

	// Tim theo nhac si
	if( ! empty ( $data_search['author'] ) )
	{
		$base_url .= "&amp;author=" . urlencode( $data_search['author'] );
		$sql .= " AND ( " . $classMusic->build_query_search_id( $classMusic->search_author_id( $data_search['author'], 5 ), 'nhacsi' ) . " )";
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
		"title" => $classMusic->lang('song_name'),
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
		$sql .= " ORDER BY `tenthat` " . $order['title']['order'];
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
	$array_singer_ids = $array_author_ids = '';
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];
		$array_author_ids = $array_author_ids == '' ? $row['nhacsi'] : $array_author_ids . "," . $row['nhacsi'];

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"singers" => $row['casi'],
			"authors" => $row['nhacsi'],
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

	$xtpl = new XTemplate( "find_one_song.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'LISTSONG', $listsong );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
	$xtpl->assign( 'DATA_ORDER', $order );
	$xtpl->assign( 'SEARCH', $data_search );
	$xtpl->assign( 'URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&listsong=" . $listsong );

	// Lay thong tin ca si, nhac si
	$array_singer_ids = $classMusic->string2array( $array_singer_ids );
	$array_author_ids = $classMusic->string2array( $array_author_ids );

	if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
	if( ! empty( $array_author_ids ) ) $array_authors = $classMusic->getauthorbyID( $array_author_ids );
	
	$a = 0;
	foreach( $array as $row )
	{
		$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );
		$row['authors'] = $classMusic->build_author_singer_2string( $array_authors, $row['authors'] );

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

// Tim kiem va them nhieu bai hat
if( $nv_Request->isset_request( 'findListAndReturn', 'get' ) )
{
	$listsong = filter_text_input( 'listsong', 'get', '', 1, 255 );
	
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );
	
	if( $nv_Request->isset_request( 'loadname', 'get' ) )
	{		
		$sql = "SELECT `id`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $listsong . ")";
		$result = $db->sql_query( $sql );

		$list_song = array();
		$_tmp = array();
		while( list( $songid, $songname ) = $db->sql_fetchrow( $result ) )
		{
			$_tmp[$songid] = $songname;
		}
		
		$listsong = $classMusic->string2array( $listsong );
		foreach( $listsong as $_sid )
		{
			if( isset( $_tmp[$_sid] ) ) $list_song[$_sid] = $_tmp[$_sid];
		}

		$return = "";
		foreach( $list_song as $_id => $_name )
		{
			$return .= "<li class=\"" . $_id . "\">" . $_name . "<span onclick=\"nv_del_item_on_list(" . $_id . ", '" . $returnArea . "', '" . $classMusic->lang('author_del_confirm') . "', '" . $returnInput . "')\" class=\"delete-icon\">&nbsp;</span></li>";
		}

		include ( NV_ROOTDIR . "/includes/header.php" );
		echo ( $return );
		include ( NV_ROOTDIR . "/includes/footer.php" );
		die();
	}
	
	$listsong = $classMusic->string2array( $listsong );

	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&findListAndReturn=1";

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->sql_query( $sql1 );
	list( $all_page ) = $db->sql_fetchrow( $result1 );

	$sql .= " ORDER BY `id` DESC";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 5;

	$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$query2 = $db->sql_query( $sql2 );

	$array = $array_singers = $array_authors =  array();
	$array_singer_ids = $array_author_ids = '';
	while( $row = $db->sql_fetchrow( $query2 ) )
	{
		$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];
		$array_author_ids = $array_author_ids == '' ? $row['nhacsi'] : $array_author_ids . "," . $row['nhacsi'];

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"checked" => in_array( $row['id'], $listsong ) ? " checked=\"checked\"" : ""
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, "nv_load_page", "data" );

	$xtpl = new XTemplate( "find_list_song.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	$xtpl->assign( 'LISTSONG', implode( ",", $listsong ) );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );

	if( ! empty( $array ) )
	{
		// Lay thong tin ca si, nhac si
		$array_singer_ids = $classMusic->string2array( $array_singer_ids );
		$array_author_ids = $classMusic->string2array( $array_author_ids );

		if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
		if( ! empty( $array_author_ids ) ) $array_authors = $classMusic->getauthorbyID( $array_author_ids );
		
		$a = 0;
		foreach( $array as $row )
		{
			$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );
			$row['authors'] = $classMusic->build_author_singer_2string( $array_authors, $row['authors'] );

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

// Xoa bai hat
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
	
	$songs = $classMusic->getsongbyID( $listid );
	
	if( sizeof( $songs ) != $num ) die( 'NO' );
	
	foreach( $songs as $id => $song )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		
		if( $song['album'] != 0 ) $classMusic->fix_album( $song['album'] );
		$classMusic->fix_singer( $classMusic->string2array( $song['casi'] ) );
		$classMusic->fix_author( $classMusic->string2array( $song['nhacsi'] ) );
		$classMusic->delcomment( 'song', $song['id'] );
		$classMusic->dellyric( $song['id'] );
		$classMusic->delerror( 'song', $song['id'] );
		$classMusic->delgift( $song['id'] );
		$classMusic->unlinkSV( $song['server'], $song['duongdan'] );
		$classMusic->fix_cat_song( array_unique( array_filter( array_merge_recursive( $song['listcat'], array( $song['theloai'] ) ) ) ) );
	}	
    
    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $classMusic->lang('delete_song'), implode( ", ", array_keys( $songs ) ), $admin_info['userid'] );
	
    die( "OK" );
}

// Thay doi hoat dong bai hat
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
	$sql = "SELECT `id`, `active` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN (" . implode ( ",", $listid ) . ")";
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
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `active`=" . $active . " WHERE `id`=" . $id;
		$db->sql_query( $sql );	
	}	
    
    nv_del_moduleCache( $module_name );
	
    die( "OK" );
}

// Tieu de trang
$page_title = $classMusic->lang('content_list');

// Thong tin phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

// Query, url co so
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

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
	
	// Tim theo ten bai hat
	$sql .= " AND ( `tenthat` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
	
	// Tim loi bai hat
	$_sql = "SELECT `songid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `body` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' ORDER BY `songid` DESC LIMIT 0,50";
	$_result = $db->sql_query( $_sql );
	
	if( $db->sql_numrows( $_result ) )
	{
		$array_search_id = array();
		while( list( $songid ) = $db->sql_fetchrow( $_result ) )
		{
			$array_search_id[] = $songid;
		}
		
		$sql .= " OR `id` IN( " . implode( ",", $array_search_id ) . " )";
		unset( $_sql, $_result, $array_search_id, $songid );
	}
	
	$sql .= ")";
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
	"title" => $classMusic->lang('song_name'),
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
	$sql .= " ORDER BY `tenthat` " . $order['title']['order'];
}
elseif( $order['numview']['order'] != "NO" )
{
	$sql .= " ORDER BY `numview` " . $order['numview']['order'];
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

// Xay dung du lieu bai hat
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

$array = $array_singers = $array_authors = $array_albums = $array_users =  array();
$array_singer_ids = $array_author_ids = $array_album_ids = $array_userid_ids = '';
while( $row = $db->sql_fetchrow( $result ) )
{
	$array_album_ids = $array_album_ids == '' ? $row['album'] : $array_album_ids . "," . $row['album'];
	$array_singer_ids = $array_singer_ids == '' ? $row['casi'] : $array_singer_ids . "," . $row['casi'];
	$array_author_ids = $array_author_ids == '' ? $row['nhacsi'] : $array_author_ids . "," . $row['nhacsi'];
	$array_userid_ids = $array_userid_ids == '' ? $row['userid'] : $array_userid_ids . "," . $row['userid'];
	
	$array[] = array(
		"id" => $row['id'],
		"theloai" => $row['theloai'] . "," . $row['listcat'],
		"title" => $row['tenthat'],
		"link" => $classMusic->getLink( 3, $classMusic->setting['alias_listen_song'] . "/" . $row['ten'] . "-" . $row['id'] ),
		"album" => $row['album'],
		"singers" => $row['casi'],
		"authors" => $row['nhacsi'],
		"upload_name" => $row['upboi'],
		"upload_id" => $row['userid'],
		"numview" => $row['numview'],
		"bitrate" => $classMusic->convert_bitrate( $row['bitrate'] ),
		"size" => $classMusic->convertfromBytes( $row['size'] ),
		"duration" => $classMusic->convert_duration( $row['duration'] ),
		"addtime" => nv_date( "H:i d/m/Y", $row['dt'] ),
		"status" => $row['active'] ? " checked=\"checked\"" : "",
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-song&amp;id=" . $row['id'],
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

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-song" );
$xtpl->assign( 'URL_ADD_OTHER', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addFromOtherSite" );

$global_array_cat_song = $classMusic->get_category();
foreach( $global_array_cat_song as $cat )
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

// Lay thong tin album, ca si, nhac si
$array_album_ids = $classMusic->string2array( $array_album_ids );
$array_singer_ids = $classMusic->string2array( $array_singer_ids );
$array_author_ids = $classMusic->string2array( $array_author_ids );
$array_userid_ids = $classMusic->string2array( $array_userid_ids );

if( ! empty( $array_album_ids ) ) $array_albums = $classMusic->getalbumbyID( $array_album_ids );
if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
if( ! empty( $array_author_ids ) ) $array_authors = $classMusic->getauthorbyID( $array_author_ids );
if( ! empty( $array_userid_ids ) ) $array_users = $classMusic->getuserbyID( $array_userid_ids );

foreach( $array as $row )
{
	$row['album'] = $classMusic->build_album_2tring( $array_albums, $row['album'] );
	$row['singers'] = $classMusic->build_author_singer_2string( $array_singers, $row['singers'] );
	$row['authors'] = $classMusic->build_author_singer_2string( $array_authors, $row['authors'] );
	$row['theloai'] = $classMusic->build_categories_2tring( $global_array_cat_song, $row['theloai'] );
	$row['upload_name'] = $classMusic->build_user_2tring( $array_users, $row['upload_id'], $row['upload_name'] );
	
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