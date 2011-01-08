<?php
/**
 * @Project NUKEVIET 3.0
 * @Author Phan Tan Dung (phantandung@gmail.com)
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$category = get_category() ;

    
// xu li
$type = isset( $array_op[1] ) ?  $array_op[1]  : 'name';
$now_page = isset( $array_op[3] ) ?  $array_op[3]  : 1;
$key = isset( $array_op[2] ) ?  $array_op[2]  : '-';

// xu li thong tin submit
if ($nv_Request->get_int( 'block_sed', 'post', 0 ) == 1 )
{
	$type = filter_text_input( 'type', 'post', 'name' );
	$key =  ( filter_text_input( 'key', 'post', '' ) == '' ) ? '-' : change_alias( filter_text_input( 'key', 'post', '' ) );
	if ( $type == 'album' )
	{
		Header( "Location: " . $mainURL . "=album/id/" . $key ) ;  die();	
	}
		Header( "Location: " . $mainURL . "=search/" . $type . "/" . $key ) ;  die();
}

$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$link = $mainURL . "=search/" . $type . "/" . $key ;
$data = '';

if ( $type == "name" )
{
	$data = "WHERE ten LIKE '%". $key ."%'";
}
elseif ( $type == "singer" )
{
	$data = "WHERE casi LIKE '%". $key ."%'";
}
elseif ( $type == "category" )
{
	$data = "WHERE theloai =". $key ;
}
elseif ( $type == "upload" )
{
	$data = "WHERE upboi =\"". $key."\"" ;
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

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ".$data." ORDER BY id DESC LIMIT ".$first_page.",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ".$data." ";

// tinh so trang
$num = mysql_query($sqlnum);
$output = mysql_num_rows($num);
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = mysql_query( $sql );
$xtpl->assign( 'num', $output);

$i = 1 ;
while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign( 'ID', $rs['id']);
	$xtpl->assign( 'num', $output);
	$xtpl->assign( 'name', $rs['tenthat']);
	$xtpl->assign( 'link', $rs['duongdan']);
	$xtpl->assign( 'singer', $rs['casithat']);
	$xtpl->assign( 'upload', $rs['upboi']);
	$xtpl->assign( 'category', $category[$rs['theloai']]);
	$xtpl->assign( 'view', $rs['numview']);
	$xtpl->assign( 'url_listen', $mainURL . "=listenone/".$rs['id'] . "/" . $rs['ten'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $rs['casi']);
	$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $rs['theloai']);
	$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $rs['upboi']);
	
	// phan cach cac div chan le
	if ( ($i % 2) == 0 ) $xtpl->assign( 'gray', 'gray'); else $xtpl->assign( 'gray', '');
	
	$xtpl->parse( 'main.loop' );
	$i ++ ;
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>