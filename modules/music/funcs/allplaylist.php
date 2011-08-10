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
    
// xu li
$type = isset( $array_op[1] ) ?  $array_op[1]  : 'view';
$now_page = isset( $array_op[3] ) ?  intval( $array_op[3] ) : 1;
$key = isset( $array_op[2] ) ?  $array_op[2]  : '-';
$link = $mainURL . "=allplaylist/". $type ."/" . $key ;

$g_array = array(
	"hot" => $mainURL . "=allplaylist/view/" . $key,  //
	"new" => $mainURL . "=allplaylist/id/" . $key,  //
	"type" => $type  //
);

$data = '';
if ( $key != '' )
	$data = "WHERE `keyname` LIKE '%". $db->dblikeescape( $key ) ."%' AND `active` = 1";
else
	$data = "WHERE `active` = 1";
	
// xu li du lieu
if ( $now_page == 1) 
{
	$first_page = 0 ;
}
else 
{
	$first_page = ( $now_page -1 ) * 20;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist " . $data . " ORDER BY " . $type . " DESC LIMIT " . $first_page . ",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist " . $data;

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
		"name" => $row['name'],  //
		"thumb" => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg",  //
		"singer" => $row['singer'],  //
		"upload" => $row['username'],  //
		"view" => $row['view'],  //
		"url_listen" => $mainURL . "=listenuserlist/".$row['id'] . "/" . $row['keyname'],  //
		"url_search_upload" => $mainURL . "=search/upload/" . $row['username']  //
	);	
}

$contents = nv_music_allplaylist ( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>