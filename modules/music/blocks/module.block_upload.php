<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $module_file, $module_info, $mainURL, $lang_module;

$xtpl = new XTemplate( "block_upload.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'URL_UPLOAD', $mainURL . "=upload" );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'MODULE_FILE', $module_file );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>