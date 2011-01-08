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

$xtpl = new XTemplate( "song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'URL_DOWN', $downURL );
// xu li

$type = isset( $array_op[1] ) ?  $array_op[1]  : 'numview';
$now_page = isset( $array_op[2] ) ?  intval( $array_op[2] ) : 1;
$allsinger = getallsinger();
$link = $mainURL . "=song/". $type ;
$xtpl->assign( 'hot', $mainURL . "=song/numview");
$xtpl->assign( 'new', $mainURL . "=song/id" );

// active span
if ( $type == 'id' )
{
	$xtpl->assign( 'active_1', 'class="active"' );
	$xtpl->assign( 'active_2', '' );
}
else
{
	$xtpl->assign( 'active_1', '' );
	$xtpl->assign( 'active_2', 'class="active"' );
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

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 ORDER BY " . $type . " DESC LIMIT ".$first_page.",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 ";

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
	$xtpl->assign( 'ID', $rs['id']);
	$xtpl->assign( 'num', $output);
	$xtpl->assign( 'name', $rs['tenthat']);
	$xtpl->assign( 'category', $category[$rs['theloai']]);
	$xtpl->assign( 'singer', $allsinger[$rs['casi']]);
	$xtpl->assign( 'upload', $rs['upboi']);
	$xtpl->assign( 'view', $rs['numview']);
	$xtpl->assign( 'url_view', $mainURL . "=listenone/" .$rs['id']. "/" . $rs['ten'] );
		
	$xtpl->assign( 'bitrate', $rs['bitrate']/1000);
	$xtpl->assign( 'size', round ( ( $rs['size']/1024/1024 ), 2 ) );
	$xtpl->assign( 'duration', (int)($rs['duration']/60) . ":" . $rs['duration']%60 );
	
	$xtpl->assign( 'url_listen', $mainURL . "=listenlist/" . $rs['id'] . "/" . $rs['ten'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $rs['casi']);
	$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $rs['upboi']);
	$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $rs['theloai']);
	
	$xtpl->parse( 'main.loop' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>