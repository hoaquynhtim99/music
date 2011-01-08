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
$userid = 0;
if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$username = $admin_info['username'];
	$userid = $admin_info['userid'];
}

$xtpl = new XTemplate( "creatalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
// khong duoc vao
if($userid == 0)
{
	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" );
    $xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" );		
	$xtpl->parse( 'main.noaccess' );
}
//duoc vao
else
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active` = 1 AND userid = " . $userid . " ORDER BY id DESC";
	$query = $db->sql_query( $sql );
	$numlist = $db->sql_numrows( $query );
	
	$img = rand( 1, 10);
	$xtpl->assign( 'img',  NV_BASE_SITEURL ."modules/" . $module_data . "/data/img(" . $img . ").jpg" );

	$numsong = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );
	if ( $numsong > 0 )
	{
		for ( $i = 1; $i <= $numsong; $i ++ )
		{
			$songid = $nv_Request->get_int( $module_name . '_song'. $i , 'cookie', 0 );
			$song = getsongbyID( $songid );
			
			$xtpl->assign( 'stt', $i );
			$xtpl->assign( 'songname', $song['tenthat'] );
			$xtpl->assign( 'singer', $song['casithat'] );
			
			$xtpl->parse( 'main.access.creatlist.loop' );
		}
		$xtpl->assign( 'num', $numlist );
		$xtpl->parse( 'main.access.creatlist' );
	}
	while ( $row = $db->sql_fetchrow( $query ) )
	{
		$img = rand( 1, 10);
		$xtpl->assign( 'playlist_img',  NV_BASE_SITEURL ."modules/" . $module_data . "/data/img(" . $img . ").jpg" );
		$xtpl->assign( 'name', $row['name'] );
		$xtpl->assign( 'singer', $row['singer'] );
		$xtpl->assign( 'date', nv_date( "d/m/Y H:i", $row['time'] ) );
		$xtpl->assign( 'num', $numlist );
		$xtpl->assign( 'id', $row['id'] );
		$xtpl->assign( 'view', $row['view'] );
		$xtpl->assign( 'url_view', $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'] );
		$xtpl->parse( 'main.access.list' );
	}
	$xtpl->parse( 'main.access' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>