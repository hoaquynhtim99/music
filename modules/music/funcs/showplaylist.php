<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
$contents = "";

$xtpl = new XTemplate( "playlistshow.tpl", NV_ROOTDIR . "/themes/" . $module_info ['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$id = $nv_Request->get_int( $module_name . '_song'.$i.'' , 'cookie', 0 );
	$song = getsongbyID( $id );
	
	$xtpl->assign( 'name', $song['tenthat'] );
	$xtpl->assign( 'url', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );
	
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
?>