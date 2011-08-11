<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$category = get_category();

$type = isset( $array_op[1] ) ?  $array_op[1]  : 'numview';
$now_page = isset( $array_op[2] ) ?  intval( $array_op[2] ) : 1;
$allsinger = getallsinger();
$link = $mainURL . "=song/". $type ;

$g_array = array();
$g_array['type'] = $type;

// xu li du lieu
if ( $now_page == 1) 
{
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*20;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 ORDER BY " . $type . " DESC LIMIT ".$first_page.",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 ";

// tinh so trang
$num = $db->sql_query( $sqlnum );
$output = $db->sql_numrows( $num );
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = $db->sql_query( $sql );
$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$checkhit = explode ( "-", $row['hit'] );
	$checkhit = $checkhit[0];
	
	$array[] = array(
		"id" => $row['id'],  //
		"name" => $row['tenthat'],  //
		"category" => $category[$row['theloai']],  //
		"singer" => $allsinger[$row['casi']],  //
		"upload" => $row['upboi'],  //
		"view" => $row['numview'],  //
		"url_view" => $mainURL . "=listenone/" .$row['id']. "/" . $row['ten'],  //
		"bitrate" => $row['bitrate'],  //
		"size" => $row['size'],  //
		"duration" => $row['duration'],  //
		"url_listen" => $mainURL . "=listenlist/" . $row['id'] . "/" . $row['ten'],  //
		"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'],  //
		"url_search_upload" => $mainURL . "=search/upload/" . $row['upboi'],  //
		"url_search_category" => $mainURL . "=search/category/" . $row['theloai'],  //
		"checkhit" => $checkhit  //
	);
}

$contents = nv_music_song ( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>