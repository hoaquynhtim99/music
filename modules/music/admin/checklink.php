<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$setting = setting_music();

$id = $nv_Request->get_int( 'id', 'post', 0 );
if( empty( $id ) ) die( "Error access!!!" );

$song = getsongbyID( $id );
$url = outputURL( $song['server'], $song['duongdan'] );

if( $song['server'] == 1 )
{
	$url = NV_MY_DOMAIN . $url;
}

if( nv_check_url( $url ) )
{
	$a = $lang_module['check_link_suc'];
}
else
{
	$a = $lang_module['check_link_err'];
}

echo $a;

?>