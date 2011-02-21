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

$xtpl = new XTemplate( "searchvideo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if ( $type == "name" )
{
	$data = "WHERE name LIKE '%". $key ."%' AND";
}
elseif ( $type == "singer" )
{
	$data = "WHERE casi LIKE '%". $key ."%' AND";
}
elseif ( $type == "category" )
{
	$data = "WHERE theloai =". $key . " AND";
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
$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = $db->sql_query( $sql );
$xtpl->assign( 'num', $output);

while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign( 'name', $rs['tname']);
	$xtpl->assign( 'singer', $allsinger[$rs['casi']]);
	$xtpl->assign( 'view', $rs['view']);
	$xtpl->assign( 'thumb', $rs['thumb']);
	$xtpl->assign( 'creat', nv_date( "H:i d/m/Y", $rs['dt'] ) );

	$xtpl->assign( 'url_listen', $mainURL . "=viewvideo/".$rs['id'] . "/" . $rs['name'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $rs['casi']);
		
	$xtpl->parse( 'main.loop' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>