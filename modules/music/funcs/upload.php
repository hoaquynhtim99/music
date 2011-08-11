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

$user_login = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" ;
$user_register = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" ;		

$allsinger = getallsinger();
$allauthor = getallauthor();
$category = get_category();

$g_array = array();
$g_array['user_login'] = $user_login;
$g_array['user_register'] = $user_register;

if( ( $setting['who_upload'] == 1 ) or ( ( $setting['who_upload'] == 0 ) and defined( 'NV_IS_USER' ) ) )
{
	$singerdata = '';
	foreach ( $allsinger as $name => $fullname )
	{
		$singerdata .= "<option value=\"" . $name . "\" >" . $fullname . "</option>";
	}
	$categoryd = '';
	$authordata = '';
	foreach ( $allauthor as $name => $fullname )
	{
		$authordata .= "<option value=\"" . $name . "\" >" . $fullname . "</option>";
	}
	$categoryd = '';
	foreach ( $category as $key => $title )
	{
		$categoryd .= "<option value=\"" . $key . "\" >" . $title . "</option>";
	}
	
	$g_array['singerdata'] = $singerdata . "</select>";
	$g_array['authordata'] = $authordata . "</select>";
	$g_array['category'] = $categoryd . "</select>";
}

$contents = nv_music_upload( $g_array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>