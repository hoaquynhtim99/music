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

// lay so bai hat
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

if ( $num == 0 ) $contents = $lang_module['playlist_null'] ;

// kiem tra bai hat da them vao chua
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$nv_Request->set_Cookie( $module_name . '_song'.$i.'' , 0 );
}

// neu chua thi them vao
if ( $contents == "" )
{
	$nv_Request->set_Cookie( $module_name . '_numlist' , 0 );
	$contents = $lang_module['playlist_del_success'] ;
} 

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>