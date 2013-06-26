<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['search_video'];
$key_words = $module_info['keywords'];

$category = get_videocategory();

$data = '';

// Xu li
$type = isset( $array_op[1] ) ? $array_op[1] : 'name';
$key = isset( $array_op[2] ) ? $array_op[2] : '-';
if( ( $type == "id" ) || ( $type == "view" ) )
{
	$now_page = isset( $array_op[2] ) ? $array_op[2] : 1;
	$order = 'a.' . $type;
	$data = 'WHERE';
	$link = $mainURL . "=searchvideo/" . $type;
	$page_title = ( $type == "id" ) ? $lang_module['video_new'] : $lang_module['video_hot'];
}
else
{
	if( ! preg_match( "/^([a-z0-9\-\_\.]+)$/i", $key ) )
	{
		module_info_die();
	}

	$now_page = isset( $array_op[3] ) ? $array_op[3] : 1;
	$order = "a.id";
	$link = $mainURL . "=searchvideo/" . $type . "/" . $key;
}

if( $type == "name" )
{
	$page_title .= " " . $lang_module['video_search_by_name'];
	$data = "WHERE a.name LIKE '%" . $db->dblikeescape( $key ) . "%' AND";
}
elseif( $type == "singer" )
{
	$page_title .= " " . $lang_module['video_search_by_singer'] . " " . $key;
	$data = "WHERE b.ten LIKE '%" . $db->dblikeescape( $key ) . "%' AND";
}
elseif( $type == "category" )
{
	if( ! isset( $category[$key] ) ) module_info_die();
	$page_title .= " " . $lang_module['video_search_by_cat'] . " " . $category[$key]['title'];
	$data = "WHERE a.theloai=" . $key . " AND";
}
elseif( ! in_array( $type, array( "view", "id" ) ) )
{
	module_info_die();
}

// Xu li du lieu
if( $now_page == 1 )
{
	$first_page = 0;
}
else
{
	$page_title .= " " . NV_TITLEBAR_DEFIS . " " . sprintf( $lang_module['page'], $now_page );
	$first_page = ( $now_page - 1 ) * 20;
}

$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id " . $data . " a.active=1 ORDER BY " . $order . " DESC LIMIT " . $first_page . ",20";
$sqlnum = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id " . $data . " a.active = 1 ";

// Tinh so trang
$num = $db->sql_query( $sqlnum );
$output = $db->sql_numrows( $num );
$ts = ceil( $output / 20 );

// Ket qua
$result = $db->sql_query( $sql );

$g_array = array();
$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	// Check HIT
	$checkhit = explode( "-", $row['hit'] );
	$checkhit = $checkhit[0];

	$array[] = array(
		"name" => $row['tname'], //
		"singer" => $row['singername'] ? $row['singername'] : $lang_module['unknow'], //
		"view" => $row['view'], //
		"thumb" => $row['thumb'], //
		"creat" => $row['dt'], //
		"checkhit" => $checkhit, //
		"url_listen" => $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'], //
		"url_search_singer" => $mainURL . "=searchvideo/singer/" . ( $row['singeralias'] ? $row['singeralias'] : '-' ) //
	);
}

$contents = nv_music_searchvideo( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>