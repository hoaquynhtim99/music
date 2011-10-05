<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$contents = "";

$id = $nv_Request->get_int( 'id', 'post', 0 );
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

if( empty( $id ) ) die( "Error Access!!!" );

if ( $num >= 20 ) $contents = $lang_module['playlist_full'] ;

// Kiem tra bai hat da them vao chua
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$song = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
	if ( $song == $id ) $contents = $lang_module['playlist_added'];
}

// Neu chua thi them vao
if ( $contents == "" )
{
	$numnext = $num + 1 ;
	$nv_Request->set_Cookie( $module_name . '_song' . $numnext, $id );
	$nv_Request->set_Cookie( $module_name . '_numlist', $numnext );
	$contents = "OK_" . $lang_module['playlist_add_success'] ;
} 

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>