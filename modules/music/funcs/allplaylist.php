<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// Xu li
$type = isset( $array_op[1] ) ? $array_op[1] : 'view';
$now_page = isset( $array_op[3] ) ? intval( $array_op[3] ) : 1;
$key = isset( $array_op[2] ) ? $array_op[2] : '-';
$link = $mainURL . "=allplaylist/" . $type . "/" . $key;

if( ! in_array( $type, array( 'id', 'view' ) ) ) module_info_die();

$g_array = array(
	"hot" => $mainURL . "=allplaylist/view/" . $key, //
	"new" => $mainURL . "=allplaylist/id/" . $key, //
	"type" => $type //
);

$data = '';
if( $key != '-' ) $data = "WHERE `keyname` LIKE '%" . $db->dblikeescape( ( $key == '-' ? '' : $key ) ) . "%' AND `active` = 1";
else  $data = "WHERE `active` = 1";

// Xu li du lieu
if( $now_page == 1 )
{
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * 20;
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist`" . $data . " ORDER BY " . $type . " DESC LIMIT " . $first_page . ",20";
$sqlnum = "SELECT COUNT(*) AS num FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` " . $data;

// Tinh so trang
$list = nv_db_cache( $sqlnum, 0, $module_name );
$output = empty( $list ) ? 0 : $list[0]['num'];
if( empty( $output ) and ( $now_page > 1 ) ) module_info_die();
$ts = ceil( $output / 20 );

$result = $db->sql_query( $sql );
$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"name" => $row['name'], //
		"thumb" => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg", //
		"singer" => $row['singer'], //
		"upload" => $row['username'], //
		"view" => $row['view'], //
		"url_listen" => $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'], //
		"url_search_upload" => $mainURL . "=search&amp;where=playlist&amp;q=" . urlencode( $row['username'] ) . "&amp;id=" . $row['userid'] . "&amp;type=upload" //
	);
}

// Xu ly tieu de trang
switch( $type )
{
	case 'id':
		$page_title = $lang_module['playlist_newest'];
		break;
	case 'view':
		$page_title = $lang_module['playlist_hotest'];
		break;
	default:
		$page_title = $lang_module['playlist_all'];
}

if( $now_page > 1 ) $page_title .= NV_TITLEBAR_DEFIS . sprintf( $lang_module['page'], $now_page );

$page_title .= NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $setting['description'];

$contents = nv_music_allplaylist( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>