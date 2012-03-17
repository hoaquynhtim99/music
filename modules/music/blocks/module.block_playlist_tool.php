<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_file, $module_info, $mainURL;

$xtpl = new XTemplate( "block_playlisttool.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'img_url', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" );
$xtpl->assign( 'URL', $mainURL . "=playlist" );
$xtpl->assign( 'URL_CREAT', $mainURL . "=creatalbum" );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>