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

$data = $nv_Request->get_string( 'link', 'post', '' );
$songdata = array();

if( ! preg_match( '/^(ht|f)tp:\/\//', $data ) )
{
	$lu = strlen( NV_BASE_SITEURL );
	$duongdan = substr( $data, $lu );
	$data = NV_ROOTDIR . "/" . $duongdan;
}
else
{
	$inputurl = $data;
	$ftpdata = getFTP();
	$str_inurl = str_split( $inputurl );
	$no_ftp = true;
	foreach( $ftpdata as $id => $data )
	{
		$this_host = $data['fulladdress'] . $data['subpart'];
		$str_check = str_split( $this_host );
		$check_ok = false;
		foreach( $str_check as $stt => $char )
		{
			if( $char != $str_inurl[$stt] )
			{
				$check_ok = false;
				break;
			}
			$check_ok = true;
		}
		if( $check_ok )
		{
			$lu = strlen( $this_host );
			$songdata['duongdan'] = substr( $inputurl, $lu );
			$songdata['server'] = $id;
			$no_ftp = false;

			if( in_array( $data['host'], array(
				"zing",
				"nhaccuatui",
				"nhacvui" ) ) )
			{
				$songdata['duongdan'] = outputURL( $songdata['server'], $songdata['duongdan'] );
			}
			break;
		}
	}

	if( $no_ftp )
	{
		$songdata['duongdan'] = $inputurl;
	}

	$data = $songdata['duongdan'];
}

$au = GetAllMP3info( $data );

echo $au['fileformat'] . "_" . $au['bitrate'] . "_" . $au['filesize'] . "_" . $au['playtime_seconds'] . "_" . $lang_module['error_file_type'];

?>