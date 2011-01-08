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

// lay du lieu
$id = $nv_Request->get_int( 'id', 'post', 0 );
// lay so bai hat
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

if ( $num == 20 ) $contents = $lang_module['playlist_full'] ;

// kiem tra bai hat da them vao chua
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$song = $nv_Request->get_int( $module_name . '_song'.$i.'' , 'cookie', 0 );
	if ( $song == $id ) $contents = $lang_module['playlist_added'] ;
}

// neu chua thi them vao
if ( $contents == "" )
{
	$numnext = $num + 1 ;
	$nv_Request->set_Cookie( $module_name . '_song'.$numnext.'' , $id );
	$nv_Request->set_Cookie( $module_name . '_numlist' , $numnext );
	$contents = "OK_" . $lang_module['playlist_add_success'] ;
} 

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>