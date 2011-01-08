<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
//if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ( $setting['who_download'] == 0 ) and !defined( 'NV_IS_USER' )  and !defined( 'NV_IS_ADMIN' ))
{
	$contents = $lang_module['err_user_down'];
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
	if ( $id > 0 )
	{
		require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
		$song = getsongbyID( $id ) ;
		$song_name = $song['ten'] . "-" . $song['casi'] . ".mp3" ;
		$song_url = NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'] ;
		$x = explode ( "/", $song['duongdan'] );
		$base = '';
		for( $i = 0; $i < (count($x)-1); $i ++ )
		{
			( $i == 0 )? ( $add = "" ) : ( $add = "/" );
			$base .= $add . $x[$i] ;
		}
		
		$base = NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $base;
		$download = new download( $song_url, $base , $song_name, true);
		$download->download_file();
	}
	exit();
}

?>


