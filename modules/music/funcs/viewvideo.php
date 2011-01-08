<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$category = get_videocategory();
$xtpl = new XTemplate( "viewvideo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );

$xtpl->assign( 'playerurl', $global_config['site_url'] ."/modules/" . $module_data . "/data/" );
$xtpl->assign( 'thisurl',  $mainURL ."=video" );

$user_login = $mainURL . "=login" ;
$user_register = $mainURL . "=register" ;		
if ( defined( 'NV_IS_USER' ) )
{ 
	$name = $user_info['username'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$name = $admin_info['username'];
}
else $name = '';
$xtpl->assign( 'ads', getADS() );

// lay bai hat
$setting = setting_music();
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id = ".$id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
// update video
$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET view = view+1 WHERE `id` =" . $id . "");


$xtpl->assign( 'URL_SENDMAIL',  $mainURL . "=videosendmail&amp;id=". $id );
$xtpl->assign( 'TITLE',  $lang_module['sendtomail'] );
$xtpl->assign( 'ID',  $id );
$xtpl->assign( 'name', $row['tname'] );
$xtpl->assign( 'singer', $row['casithat'] );
$xtpl->assign( 'category', $category[ $row['theloai'] ] );
$xtpl->assign( 'view', $row['view'] );

$xtpl->assign( 'url_search_singer', $mainURL . "=searchvideo/singer/" . $row['casi']);
$xtpl->assign( 'url_search_category', $mainURL . "=searchvideo/category/" . $row['theloai']);

$xtpl->assign( 'link', $row['duongdan'] );
$xtpl->assign( 'URL_SONG', get_URL() );



// binh luan
if ( ( $setting['who_comment'] == 0 ) and !defined( 'NV_IS_USER' ) and !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
    $xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.nocomment' );
}
else
{
	$xtpl->assign( 'USER_NAME', $name );
	$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );
	$xtpl->parse( 'main.comment' );
}


// tieu de trang
$page_title = $row['tname'] . " - " .$row['casithat'] ;
$key_words =  $row['tname'] . " - " .$row['casithat'] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>