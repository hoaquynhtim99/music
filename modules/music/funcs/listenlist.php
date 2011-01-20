<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
$allsinger = getallsinger();

$xtpl = new XTemplate( "listenlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL."modules/" . $module_data . "/data/" );
$xtpl->assign( 'ads', getADS() );
$user_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" ;
$user_register = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" ;		
if ( defined( 'NV_IS_USER' ) )
{ 
	$name = $user_info['username'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$name = $admin_info['username'];
}
else $name = '';
$xtpl->assign( 'USER_NAME', $name );
$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );

// xu li
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$xtpl->assign( 'ID',  $id );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id = ".$id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
// update album
$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numview` = numview+1 WHERE `id` =" . $id );
// cac bai hat cua album
$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE album = \"".$row['name'] ."\" AND `active` = 1 ORDER BY id DESC";
$querysong = $db->sql_query( $sqlsong );
$i = 1 ;
while ($rowsong = $db->sql_fetchrow( $querysong ) )
{
	$xtpl->assign( 'song_name', $i.". ".$rowsong['tenthat'] );
	$xtpl->assign( 'song_singer', $allsinger[$rowsong['casi']] );
	
	if ( $rowsong['server'] != 0 )
	{
		$xtpl->assign( 'song_url', $songURL . $rowsong['duongdan'] );
	}
	else
	{
		$xtpl->assign( 'song_url', $rowsong['duongdan'] );
	}
	$xtpl->parse( 'main.song' );
	$i ++ ;
}
$xtpl->assign( 'name', $row['tname'] );
$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $row['upboi']);
$xtpl->assign( 'singer', $allsinger[$row['casi']] );
$xtpl->assign( 'numview', $row['numview'] );
$xtpl->assign( 'who_post', $row['upboi'] );
$xtpl->assign( 'album_thumb', $row['thumb'] );
$xtpl->assign( 'describe', $row['describe'] );
$xtpl->assign( 'URL_ALBUM', get_URL() );
// binh luan
if ( ( $setting['who_comment'] == 0 ) && !defined( 'NV_IS_USER' ) && !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
	$xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.nocomment' );
}
elseif ( $setting['who_comment'] == 2 )
{
	$xtpl->parse( 'main.stopcomment' );	
}
else
{
	require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
	$xtpl->assign( 'EMOTIONS', show_emotions() );
	$xtpl->assign( 'USER_NAME', $name );
	$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );
	$xtpl->parse( 'main.comment' );
}

// tieu de trang
$page_title = "Album ". $row['tname'] . " - " . $allsinger[$row['casi']] ;
$key_words =  $row['tname'] . " - " . $allsinger[$row['casi']] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );



?>