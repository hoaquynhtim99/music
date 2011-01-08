<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

// lay du lieu
$stt = $nv_Request->get_int( 'stt', 'post', 0 );
$contents = $stt;
// lay so bai hat
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

for ( $i = $stt; $i <= $num; $i ++ )
{
	$j = $i +1;
	$tmp = $nv_Request->get_int( $module_name . '_song'. $j , 'cookie', 0 );
	$nv_Request->set_Cookie( $module_name . '_song' . $i , $tmp );
}

$numprev = $num - 1 ;
$nv_Request->set_Cookie( $module_name . '_numlist' , $numprev );


include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>