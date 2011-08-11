<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
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