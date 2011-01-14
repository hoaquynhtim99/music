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
$category = get_category();

$xtpl = new XTemplate( "upload.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA_URL', NV_BASE_SITEURL ."themes/" . $module_info ['template'] . "/images/" . $module_file . "/" );
$xtpl->assign( 'DATA_ACTION', $mainURL . "=uploadfile" );

if ( ( $setting['who_upload'] == 0 ) && !defined( 'NV_IS_USER' ) && !defined( 'NV_IS_ADMIN' ) )
{
	$xtpl->assign( 'USER_LOGIN', $user_login );
	$xtpl->assign( 'USER_REGISTER', $user_register );		
	$xtpl->parse( 'main.noaccess' );
}
elseif ( $setting['who_upload'] == 2 )
{
	$xtpl->parse( 'main.stopaccess' );	
}
else
{
	$singerdata = '';
	foreach ( $allsinger as $name => $fullname )
	{
		$singerdata .= "<option value=\"" . $name . "\" >" . $fullname . "</option>";
	}
	$categoryd = '';
	foreach ( $category as $key => $title )
	{
		$categoryd .= "<option value=\"" . $key . "\" >" . $title . "</option>";
	}
	$xtpl->assign( 'singerdata', $singerdata . "</select>" );
	$xtpl->assign( 'category', $categoryd . "</select>" );
	$xtpl->parse( 'main.access' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>