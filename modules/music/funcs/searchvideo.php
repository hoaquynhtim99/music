<?php
/**
 * @Project NUKEVIET 3.0
 * @Author Phan Tan Dung (phantandung@gmail.com)
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$category = get_videocategory() ;
$allsinger = getallsinger();
$data = '';

// xu li
$type = isset( $array_op[1] ) ?  $array_op[1]  : 'name';
$key = isset( $array_op[2] ) ?  $array_op[2]  : '-';
if ( ($type == "id") || ($type == "view") )
{
	$now_page = isset( $array_op[2] ) ?  $array_op[2]  : 1;
	$order = $type;
	$data = 'WHERE';
	$link = $mainURL . "=searchvideo/" . $type ;
}
else
{
	$now_page = isset( $array_op[3] ) ?  $array_op[3]  : 1;
	$order = "id";
	$link = $mainURL . "=searchvideo/" . $type . "/" . $key ;
}

if ( $type == "name" )
{
	$data = "WHERE `name` LIKE '%". $db->dblikeescape( $key ) ."%' AND";
}
elseif ( $type == "singer" )
{
	$data = "WHERE `casi` LIKE '%". $db->dblikeescape( $key ) ."%' AND";
}
elseif ( $type == "category" )
{
	$data = "WHERE `theloai` =". $key . " AND";
}

// xu li du lieu
if ( $now_page == 1) 
{
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*20;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video ".$data." `active` = 1 ORDER BY `" . $order . "` DESC LIMIT ".$first_page.",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video ".$data." `active` = 1 ";

// tinh so trang
$num = $db->sql_query( $sqlnum );
$output = $db->sql_numrows( $num );
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = $db->sql_query( $sql );

$g_array = array();
$g_array['num'] = $output;

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"name" => $row['tname'],  //
		"singer" => $allsinger[$row['casi']],  //
		"view" => $row['view'],  //
		"thumb" => $row['thumb'],  //
		"creat" => $row['dt'],  //
		"url_listen" => $mainURL . "=viewvideo/".$row['id'] . "/" . $row['name'],  //
		"url_search_singer" => $mainURL . "=searchvideo/singer/" . $row['casi']  //
	);
}

$contents = nv_music_searchvideo( $g_array, $array );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>