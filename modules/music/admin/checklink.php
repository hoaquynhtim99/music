<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if(!defined('NV_IS_MUSIC_ADMIN'))
{
	die('Stop!!!');
}
$setting = setting_music();
$id = $nv_Request->get_int('id', 'post', 0);

$song = getsongbyID( $id );
if ( $song['server'] == 0 )
{
	$url = $song['duongdan'] ;
}
elseif ( $song['server'] == 1 )
{
	$url = NV_MY_DOMAIN . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'] ;
}
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