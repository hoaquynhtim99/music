<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_file, $module_info, $module_name, $global_config;

$xtpl = new XTemplate( "block_search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . 'index.php?' );

$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'KEY', filter_text_input( 'q', 'get', '', 0, NV_MAX_SEARCH_LENGTH ) );
$xtpl->assign( 'CHECKSESS', md5( $global_config['sitekey'] . session_id() ) );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>