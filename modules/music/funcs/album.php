<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$allsinger = getallsinger();
    
// xu li
$type = isset( $array_op[1] ) ?  $array_op[1]  : 'numview';
$now_page = isset( $array_op[3] ) ?  intval( $array_op[3] ) : 1;
$key = isset( $array_op[2] ) ?  $array_op[2]  : '-';

$link = $mainURL . "=album/". $type ."/" . $key ;

$g_array = array(
	"hot" => $mainURL . "=album/numview/" . $key,  //
	"new" => $mainURL . "=album/id/" . $key,  //
	"type" => $type  //
);

$data = '';
if ( $key != '' )
{
	$data = "WHERE `name` LIKE '%". $db->dblikeescape( $key ) ."%' AND `active` = 1";
}
else
{
	$data = "WHERE `active` = 1";
}
	
// xu li du lieu
if ( $now_page == 1) 
{
	$first_page = 0 ;
}
else 
{
	$first_page = ( $now_page -1 ) * 20;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ".$data." ORDER BY " . $type . " DESC LIMIT ".$first_page.",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ".$data." ";

// tinh so trang
$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = $db->sql_query( $sql );

$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"name" => $row['tname'],  //
		"thumb" => $row['thumb'],  //
		"singer" => $allsinger[$row['casi']],  //
		"upload" => $row['upboi'],  //
		"view" => $row['numview'],  //
		"url_listen" => $mainURL . "=listenlist/" . $row['id'] . "/" . $row['name'],  //
		"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'],  //
		"url_search_upload" => $mainURL . "=search/upload/" . $row['upboi']  //
	);
}

$contents = nv_music_album ( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>