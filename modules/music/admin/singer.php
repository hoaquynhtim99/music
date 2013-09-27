<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Tim kiem va them mot ca si
if( $nv_Request->isset_request( 'findOneAndReturn', 'get' ) )
{
	$singers = filter_text_input( 'singers', 'get', '', 1, 255 );
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );

	$page_title = $classMusic->lang('getsingerid_title');
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 15;
	$array = array();

	// SQL va LINK co ban
	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id`!=0";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;singers=" . $singers;

	// Du lieu tim kiem
	$data_search = array( "q" => filter_text_input( 'q', 'get', '', 1, 255 ) );

	if( ! empty( $singers ) ) $sql .= " AND `id` NOT IN(" . $singers . ")";

	if( ! empty( $data_search['q'] ) )
	{
		$base_url .= "&amp;q=" . $data_search['q'];
		$sql .= " AND ( `tenthat` LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
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
		"title" => $classMusic->lang('filter_singer'),
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

	$array = array();

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->sql_query( $sql1 );
	list( $all_page ) = $db->sql_fetchrow( $result1 );

	$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$result = $db->sql_query( $sql );

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";
	
		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"thumb" => $row['thumb'],
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

	$xtpl = new XTemplate( "find_one_singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'SINGERS', $singers );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
	$xtpl->assign( 'DATA_ORDER', $order );
	$xtpl->assign( 'SEARCH', $data_search );
	$xtpl->assign( 'URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&singers=" . $singers );

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

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Tim kiem va them nhieu ca si
if( $nv_Request->isset_request( 'findListAndReturn', 'get' ) )
{
	$singers = filter_text_input( 'singers', 'get', '', 1, 255 );
	
	$returnArea = filter_text_input( 'area', 'get', '', 1, 255 );
	$returnInput = filter_text_input( 'input', 'get', '', 1, 255 );
	
	if( $nv_Request->isset_request( 'loadname', 'get' ) )
	{		
		$sql = "SELECT `id`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id` IN(" . $singers . ")";
		$result = $db->sql_query( $sql );

		$list_singer = array();
		$_tmp = array();
		while( list( $singerid, $singername ) = $db->sql_fetchrow( $result ) )
		{
			$_tmp[$singerid] = $singername;
		}
		
		$singers = $classMusic->string2array( $singers );
		foreach( $singers as $_sid )
		{
			if( isset( $_tmp[$_sid] ) ) $list_singer[$_sid] = $_tmp[$_sid];
		}

		$return = "";
		foreach( $list_singer as $_id => $_name )
		{
			$return .= "<li class=\"" . $_id . "\">" . $_name . "<span onclick=\"nv_del_item_on_list(" . $_id . ", '" . $returnArea . "', '" . $classMusic->lang('author_del_confirm') . "', '" . $returnInput . "')\" class=\"delete-icon\">&nbsp;</span></li>";
		}

		include ( NV_ROOTDIR . "/includes/header.php" );
		echo ( $return );
		include ( NV_ROOTDIR . "/includes/footer.php" );
		die();
	}
	
	$singers = $classMusic->string2array( $singers );

	$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer`";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findListAndReturn=1";

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->sql_query( $sql1 );
	list( $all_page ) = $db->sql_fetchrow( $result1 );

	$sql .= " ORDER BY `id` DESC";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 5;

	$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$query2 = $db->sql_query( $sql2 );

	$array = array();
	while( $row = $db->sql_fetchrow( $query2 ) )
	{
		$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"thumb" => $row['thumb'],
			"checked" => in_array( $row['id'], $singers ) ? " checked=\"checked\"" : ""
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, "nv_load_page", "data" );

	$xtpl = new XTemplate( "find_list_singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
	$xtpl->assign( 'SINGERS', implode( ",", $singers ) );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );

	if( ! empty( $array ) )
	{
		$a = 0;
		foreach( $array as $row )
		{
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

$page_title = $lang_module['singer_list'];

if( ! defined( 'SHADOWBOX' ) )
{
	$my_head = "<link type=\"text/css\" rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
	define( 'SHADOWBOX', true );
}

// Lay du lieu
$contents = '';

$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$q = filter_text_input( 'q', 'get', '' );

$order = filter_text_input( 'order', 'get', 'id' );
if( $order == 'id' ) $sort = "ORDER BY " . $order . " DESC";
else  $sort = "ORDER BY " . $order . " ASC";

// Xu li du lieu
if( ! $now_page )
{
	$now_page = 1;
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * $numshow;
}

$where = "`tenthat` LIKE '%" . $db->dblikeescape( $q ) . "%'";

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=singer&numshow=" . $numshow . "&q=" . $q . "&order=" . $order;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE " . $where . " " . $sort . " LIMIT " . $first_page . "," . $numshow;
$sqlnum = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE " . $where;

// Tinh so trang
$num = $db->sql_query( $sqlnum );
list( $output ) = $db->sql_fetchrow( $num );
$ts = ceil( $output / $numshow );

// Form tim kiem
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?\" method=\"get\"><table class=\"tab1 fixbottomtable\"><tbody><tr><td>";
$contents .= "<input type=\"hidden\" name=\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\" />\n";
$contents .= "<input type=\"hidden\" name=\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\" />\n";
$contents .= $lang_module['search_singer'] . ": ";

// So ket qua hien thi tim kiem
$i = 5;
$contents .= "&nbsp;" . $lang_module['search_per_page'] . ":&nbsp;";
$contents .= "<select class=\"music-input\" name=\"numshow\">\n";
while( $i <= 1000 )
{
	$a = '';
	if( $i == $numshow ) $a = "selected=\"selected\"";
	$contents .= "<option " . $a . " value=\"" . $i . "\" >" . $i . "</option>\n";
	$i = $i + 10;
}
$contents .= "</select>\n";

// Tu khoa tim kiem
$contents .= $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" class=\"music-input\" style=\"width: 265px\">\n";
$contents .= "<input class=\"music-button\" type=\"submit\" value=\"" . $lang_module['search'] . "\">\n";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</td></tr></tbody></table></form>\n";

// Ket qua
$xtpl = new XTemplate( "singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsinger" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_singer" );
$xtpl->assign( 'ORDER_NAME', $link . "&order=ten" );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsinger";

$result = $db->sql_query( $sql );
while( $row = $db->sql_fetchrow( $result ) )
{
	if( empty( $row['thumb'] ) )
	{
		$row['thumb'] = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";
	}

	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'thumb', $row['thumb'] );
	$xtpl->assign( 'ten', $row['tenthat'] );
	$xtpl->assign( 'numsong', $row['numsong'] );
	$xtpl->assign( 'numalbum', $row['numalbum'] );
	$xtpl->assign( 'numvideo', $row['numvideo'] );
	$xtpl->assign( 'URL_SONG', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&type_search=casi&q=" . $row['tenthat'] );
	$xtpl->assign( 'URL_VIDEO', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip&type_search=casi&q=" . $row['tenthat'] );
	$xtpl->assign( 'URL_ALBUM', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album&type=singer&q=" . $row['tenthat'] );
	$xtpl->assign( 'url_add_song', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content-song&casi=" . $row['id'] );
	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_singer&id=" . $row['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $row['id'] );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n";
$contents .= new_page_admin( $ts, $now_page, $link );
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>