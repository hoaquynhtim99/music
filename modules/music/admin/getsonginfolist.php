<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.php' );
require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.functions.php' ); // Function library

//$mp3info = GetAllMP3info(<filename>);
//$mp3info = GetAllMP3info('/home/mp3s/song.mp3');
//$mp3info = GetAllMP3info('Troi-Anh-Yeu.mp3');
//$mp3info = GetAllMP3info('http://s1.chacha.vn/audio/mp3/0/0/144/hai_ba_nam-147887.mp3');

$data = $nv_Request->get_string( 'link', 'post', '' );
$id = $nv_Request->get_int( 'id', 'post', 0 );
if( ! preg_match( '/^(ht|f)tp:\/\//', $data ) )
{
	$lu = strlen( NV_BASE_SITEURL );
	$duongdan = substr( $data, $lu );
	$data = NV_ROOTDIR . "/" . $duongdan;
}

$au = GetAllMP3info( $data );

echo $id . "_" . $au['fileformat'] . "_" . $au['bitrate'] . "_" . $au['filesize'] . "_" . $au['playtime_seconds'] . "_" . $lang_module['error_file_type'];

?>