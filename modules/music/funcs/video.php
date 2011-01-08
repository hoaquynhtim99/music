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
$category = get_videocategory();
$allsinger = getallsinger();

$xtpl = new XTemplate( "video.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'url_viewhot', $mainURL . "=searchvideo/view" );
$xtpl->assign( 'url_viewnew', $mainURL . "=searchvideo/id" );
// xu li

$sqlhot = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE `active` = 1 ORDER BY view DESC LIMIT 0,12";
$sqlnew = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE `active` = 1 ORDER BY id DESC LIMIT 0,12";
// viet the loai
foreach ( $category as $key => $value )
{
	$xtpl->assign( 'name', $value );
	$xtpl->assign( 'url_view_category', $mainURL . "=searchvideo/category/" . $key );
	
	$xtpl->parse( 'main.cate' );
}

// video moi
$result = $db->sql_query( $sqlnew );
$i = 1;
while( $rs = $db->sql_fetchrow($result) )
{
		$xtpl->assign( 'name', $rs['tname']);
		$xtpl->assign( 'singer', $allsinger[$rs['casi']]);
		$xtpl->assign( 'thumb', $rs['thumb']);
		$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" .$rs['id']. "/" . $rs['name'] );

		$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $rs['casi']);
		
	if ( $i < 5 )
	{
		$xtpl->parse( 'main.new1' );
	}
	elseif ( $i < 9 )
	{
		$xtpl->parse( 'main.new2' );
	}
	else
	{
		$xtpl->parse( 'main.new3' );
	}
	$i ++;
}
// video hot
$result = $db->sql_query( $sqlhot );
$i = 1;
while( $rs = $db->sql_fetchrow($result) )
{
		$xtpl->assign( 'name', $rs['tname']);
		$xtpl->assign( 'singer', $allsinger[$rs['casi']]);
		$xtpl->assign( 'thumb', $rs['thumb']);
		$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" .$rs['id']. "/" . $rs['name'] );

		$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $rs['casi']);
		
	if ( $i < 5 )
	{
		$xtpl->parse( 'main.hot1' );
	}
	elseif ( $i < 9 )
	{
		$xtpl->parse( 'main.hot2' );
	}
	else
	{
		$xtpl->parse( 'main.hot3' );
	}
	$i ++;
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>