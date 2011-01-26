<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011
 * @Createdate 26/01/2011 08:33 AM
 */
if(!defined('NV_IS_MUSIC_ADMIN'))
{
	die('Stop!!!');
}
$setting = setting_music();
$id = $nv_Request->get_int('id', 'post', 0);

$song = getsongbyID( $id );
$url = outputURL ( $song['server'], $song['duongdan'] ) ;
if ( $song['server'] == 1 ) { $url = NV_MY_DOMAIN . $url ; }
if (  nv_check_url( $url ) )
{
   $a = $lang_module['check_link_suc'];
}
else
{
	$a = $lang_module['check_link_err'];
}

echo $a;
?>