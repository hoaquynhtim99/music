<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['sub_comment'];

list( $numsong ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_song`" ) );
list( $numalbum ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_album`" ) );
list( $numvideo ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_video`" ) );

$xtpl = new XTemplate( "gcomment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'MAIN_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE );

$xtpl->assign( 'CSONG', $numsong );
$xtpl->assign( 'CALBUM', $numalbum );
$xtpl->assign( 'CVIDEO', $numvideo );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>