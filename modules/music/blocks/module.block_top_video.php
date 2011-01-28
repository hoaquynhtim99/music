<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;
$allsinger = getallsinger();

$xtpl = new XTemplate( "block_video_same_category.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE `active` = 1 ORDER BY view DESC LIMIT 0,8";
$query = $db->sql_query( $sql );
while( $video =  $db->sql_fetchrow( $query ) )
{
	$xtpl->assign( 'url_view', $mainURL . "=viewvideo/" . $video['id'] . "/" . $video['name'] );
	$xtpl->assign( 'video_name', $video['tname'] );
	$xtpl->assign( 'thumb', $video['thumb'] );
	$xtpl->assign( 'view', $video['view'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $video['casi'] );
	$xtpl->assign( 'singer', $allsinger[$video['casi']] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>