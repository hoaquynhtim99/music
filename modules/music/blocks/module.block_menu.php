<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_file, $module_info, $mainURL;

$xtpl = new XTemplate( "block_menu.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$menu = array();
$menu[1] = array( 'title' => $lang_module['menu1'], 'link' => ( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name ) );
$menu[2] = array( 'title' => $lang_module['video'], 'link' => ( $mainURL . "=video" ) );
$menu[3] = array( 'title' => $lang_module['menu4'], 'link' => ( $mainURL . "=creatalbum" ) );
$menu[4] = array( 'title' => $lang_module['menu3'], 'link' => ( $mainURL . "=managersong" ) );

foreach( $menu as $value )
{
	$xtpl->assign( 'MENU', $value );
	$xtpl->parse( 'main.item' );
}
$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>