<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$category = get_category();

$type = isset( $array_op[1] ) ? $array_op[1] : 'numview';
$now_page = isset( $array_op[2] ) ? intval( $array_op[2] ) : 1;

$link = $mainURL . "=song/" . $type;

$g_array = array();
$g_array['type'] = $type;

// Xu li du lieu
if( $now_page == 1 )
{
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * 20;
}

$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 ORDER BY a." . $type . " DESC LIMIT " . $first_page . ",20";
$sqlnum = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `active`=1";

// tinh so trang
$num = $db->sql_query( $sqlnum );
list( $output ) = $db->sql_fetchrow( $num );
$ts = ceil( $output / 20 );

// ket qua
$result = $db->sql_query( $sql );
$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$checkhit = explode( "-", $row['hit'] );
	$checkhit = $checkhit[0];

	$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];
	
	$array[] = array(
		"id" => $row['id'], //
		"name" => $row['tenthat'], //
		"category" => $category[$row['theloai']]['title'], //
		"singer" => $singername, //
		"upload" => $row['upboi'], //
		"view" => $row['numview'], //
		"url_view" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'], //
		"bitrate" => $row['bitrate'], //
		"size" => $row['size'], //
		"duration" => $row['duration'], //
		"url_listen" => $mainURL . "=listenlist/" . $row['id'] . "/" . $row['ten'], //
		"url_search_singer" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
		"url_search_upload" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $row['upboi'] ) . "&amp;id=" . $row['userid'] . "&amp;type=upload", //
		"url_search_category" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $category[$row['theloai']]['title'] ) . "&amp;id=" . $row['theloai'] . "&amp;type=category", //
		"checkhit" => $checkhit //
	);
}

// Xu ly tieu de trang
switch( $type )
{
	case 'id':
		$page_title = $lang_module['newset_song'];
		break;
	case 'numview':
		$page_title = $lang_module['hotest_song'];
		break;
	default:
		$page_title = $lang_module['all_song'];
}

if( $now_page > 1 ) $page_title .= NV_TITLEBAR_DEFIS . sprintf( $lang_module['page'], $now_page );

$page_title .= NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $setting['description'];

$contents = nv_music_song( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>