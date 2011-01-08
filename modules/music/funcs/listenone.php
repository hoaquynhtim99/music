<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$category = get_category();
$xtpl = new XTemplate( "listenone.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );

$xtpl->assign( 'playerurl', $global_config['site_url'] ."/modules/" . $module_data . "/data/" );


$xtpl->assign( 'img_url',  NV_BASE_SITEURL ."themes/" . $module_info['template'] ."/images/".$module_file );
$xtpl->assign( 'URL_DOWN', $downURL );

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
$xtpl->assign( 'ads', getADS() );

// lay bai hat
$setting = setting_music();
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = ".$id;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

// update bai hat
$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET numview = numview+1 WHERE `id` =" . $id );

$xtpl->assign( 'creat_link_url',  $global_config['site_url'] . '/' . $global_config['site_lang'] . '/' . $module_data . '/creatlinksong/' . $row['id'] . '/' . $row['ten'] . '/' );

$xtpl->assign( 'URL_SENDMAIL',  $mainURL . "=sendmail&amp;id=". $id );
$xtpl->assign( 'TITLE',  $lang_module['sendtomail'] );
$xtpl->assign( 'ID',  $id );
$xtpl->assign( 'name', $row['tenthat'] );
$xtpl->assign( 'singer', $row['casithat'] );
$xtpl->assign( 'category', $category[ $row['theloai'] ] );
$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);

$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $row['theloai']);

$xtpl->assign( 'url_search_album', $mainURL . "=album/numview/" . $row['album']);

$album_name = getalbumbyNAME( $row['album'] ) ;
$xtpl->assign( 'album', $album_name['tname'] );
$xtpl->assign( 'numview', $row['numview'] );
if ( $row['server'] != 0 )
{
	$xtpl->assign( 'link', $songURL . $row['duongdan'] );
}
else
{
	$xtpl->assign( 'link', $row['duongdan'] );
}
$xtpl->assign( 'URL_SONG', get_URL() );

// loi bai hst
$sqllyric = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE songid = ". $id ." AND `active` = 1 ORDER BY id DESC";
$querylyric = $db->sql_query( $sqllyric );
$num_lyric = $db->sql_numrows($querylyric);

if ( $num_lyric >= 1 )
{
	$i = 1 ;
	while ( $rowlyric = $db->sql_fetchrow( $querylyric ) )
	{	
		if ( ($num_lyric > 1) && ( $i < $num_lyric ))
		{
			$nextdiv = $i + 1 ;
			$xtpl->assign( 'nextdiv', $nextdiv );
			$xtpl->parse( 'main.lyric.next' );			
		}
		if ( $i > 1 )
		{
			$prevdiv = $i - 1 ;
			$xtpl->assign( 'prevdiv', $prevdiv );
			$xtpl->parse( 'main.lyric.prev' );					
		}

		$xtpl->assign( 'uesrlyric', $rowlyric['user'] );		
		$xtpl->assign( 'lyriccontent', $rowlyric['body'] );		
		$xtpl->assign( 'thisdiv', $i );
		$xtpl->parse( 'main.lyric' );
		$i ++ ;
	}
}
else
{
	$xtpl->parse( 'main.nolyric' );
}
//gui qua tang
if ( ( $setting['who_gift'] == 0 ) and !defined( 'NV_IS_USER' ) and !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
    $xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.nogift' );
}
else
{
	$xtpl->assign( 'USER_NAME', ( $name == '' )? ( $lang_module['your_name'] ):( $name ) );
	$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );
	$xtpl->parse( 'main.gift' );
}
//gui loi bai hat
if ( ( $setting['who_lyric'] == 0 ) and !defined( 'NV_IS_USER' ) and !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
    $xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.noaccesslyric' );
}
else
{

	$xtpl->assign( 'USER_NAME', $name );
	$xtpl->assign( 'NO_CHANGE', ( $name == '' )? '':'readonly="readonly"' );
	$xtpl->parse( 'main.accesslyric' );
}

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
$page_title = $row['tenthat'] . " - " .$row['casithat'] ;
$key_words =  $row['tenthat'] . " - " .$row['casithat'] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>