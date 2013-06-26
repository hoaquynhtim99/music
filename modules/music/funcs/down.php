<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011 Freeware
 * @Createdate 26/01/2011 10:24 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$contents = "";
if( ( $setting['who_download'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
{
	$contents = $lang_module['err_user_down'];
}
elseif( $setting['who_download'] == 2 )
{
	$contents = $lang_module['setting_stop'];
}

if( $contents != "" )
{
	$page_title = $lang_module['down_info3'] . " - " . $id . " - " . $module_info['custom_title'];
	$key_words = $module_info['keywords'];

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
	if( $id > 0 )
	{
		$song = getsongbyID( $id );

		if( empty( $song['active'] ) )
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}

		if( $song['server'] != 1 )
		{
			$duongdan = outputURL( $song['server'], $song['duongdan'] );
			Header( "Location: " . $duongdan );
			exit();
		}

		require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
		$song_name = change_alias( $song['tenthat'] ) . "-" . $song['casi'] . ".mp3";
		$song_url = NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'];
		$x = explode( "/", $song['duongdan'] );
		$base = '';
		for( $i = 0; $i < ( count( $x ) - 1 ); $i++ )
		{
			( $i == 0 ) ? ( $add = "" ) : ( $add = "/" );
			$base .= $add . $x[$i];
		}
		$base = NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $base;
		$download = new download( $song_url, $base, $song_name, true );
		$download->download_file();
	}
	exit();
}

?>


