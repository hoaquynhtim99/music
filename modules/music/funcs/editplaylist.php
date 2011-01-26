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
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active` = 1 AND id = " . $id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
if ( defined( 'NV_IS_USER' ) )
{
    $name = $user_info['username'];
    $userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
    $name = $admin_info['username'];
    $userid = $admin_info['userid'];
}
else $userid = 0;

if ( ( $userid == 0 ) || ( $userid != $row['userid'] ) ) die( 'Stop!!!' );
$allsinger = getallsinger();

$ok = 0;
if( $nv_Request->get_int( 'ok', 'post', 0 ) == 1 )
{
	$pldata = array();
	$row['name'] = $pldata['name'] = filter_text_input( 'name', 'post', '' );
	$row['keyname'] = $pldata['keyname'] = change_alias ( $pldata['name'] );
	$row['singer'] = $pldata['singer'] =filter_text_input( 'singer', 'post', '' );
	$row['message'] = $pldata['message'] = $nv_Request->get_string( 'message', 'post', '' );
	
	foreach ( $pldata as $key => $data  )
	{	
		$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id );
	}
	if ( $query ) 
	{
		$ok = 1;
	}
}

$xtpl = new XTemplate( "editplaylist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ACTION', $mainURL . "=editplaylist/" . $id );
$img = rand( 1, 10);
$xtpl->assign( 'img',  NV_BASE_SITEURL ."modules/" . $module_data . "/data/img(" . $img . ").jpg" );
$xtpl->assign( 'INFO', $row );

if ( $ok == 1 )
{
	$xtpl->assign( 'url_play', $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'] );
	$xtpl->assign( 'url_back', $mainURL . "=creatalbum" );
	$xtpl->parse( 'main.sucess' );
}


$songdata = explode ( '/', $row['songdata'] );
foreach ( $songdata as $songid )
{
	if ( !$songid ) continue;
	$song = getsongbyID( $songid );
	$xtpl->assign( 'songid', $song['id'] );
	$xtpl->assign( 'songname', $song['tenthat'] );
	$xtpl->assign( 'songsinger', $allsinger[$song['casi']] );
	$xtpl->assign( 'url_view', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $song['casi'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>