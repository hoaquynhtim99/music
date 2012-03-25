<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Lay cac gia tri
$type = isset( $array_op[1] ) ? $array_op[1] : 'numview';
$now_page = isset( $array_op[3] ) ? intval( $array_op[3] ) : 1;
$key = isset( $array_op[2] ) ? $array_op[2] : '-';

if( ! in_array( $type, array( 'id', 'numview' ) ) ) module_info_die();

$link = $mainURL . "=album/" . $type . "/" . $key;

$g_array = array(
	"hot" => $mainURL . "=album/numview/" . $key, //
	"new" => $mainURL . "=album/id/" . $key, //
	"type" => $type //
);

$data = '';
if( $key != '-' )
{
	if( $key == '-' ) $key = '';
	$data = "WHERE a.name LIKE '%" . $db->dblikeescape( $key ) . "%' AND a.active=1";
}
else
{
	$data = "WHERE a.active=1";
}

// Xu li du lieu
if( $now_page == 1 )
{
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * 20;
}

$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_album AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id " . $data;
$sqlnum = "SELECT COUNT(*) AS num " . $sql;
$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername " . $sql . " ORDER BY " . $type . " DESC LIMIT " . $first_page . ",20";

// Tinh so trang
$list = nv_db_cache( $sqlnum, 0, $module_name );
$output = empty( $list ) ? 0 : $list[0]['num'];
if( empty( $output ) and ( $now_page > 1 ) ) module_info_die();
$ts = ceil( $output / 20 );

// Ket qua
$result = $db->sql_query( $sql );

$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$singername = empty( $row['singername'] ) ? $lang_module['unknow'] : $row['singername'];
	
	// Check HIT
	$checkhit = explode( "-", $row['hit'] );
	$checkhit = $checkhit[0];

	$array[] = array(
		"name" => $row['tname'], //
		"thumb" => $row['thumb'], //
		"describe" => $row['describe'], //
		"singer" => $singername, //
		"checkhit" => $checkhit, //
		"upload" => $row['upboi'], //
		"view" => $row['numview'], //
		"url_listen" => $mainURL . "=listenlist/" . $row['id'] . "/" . $row['name'], //
		"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
		"url_search_upload" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $row['upboi'] ) . "&amp;type=upload" //
	);
}

// Xu ly tieu de trang
switch( $type )
{
	case 'id':
		$page_title = $lang_module['album_newest'];
		break;
	case 'numview':
		$page_title = $lang_module['album_hotest'];
		break;
	default:
		$page_title = $lang_module['all_album'];
}

if( $now_page > 1 ) $page_title .= " " . NV_TITLEBAR_DEFIS . " " . sprintf( $lang_module['page'], $now_page );

$page_title .= " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $setting['description'];

$contents = nv_music_album( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>