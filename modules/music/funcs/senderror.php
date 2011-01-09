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
$user = filter_text_input( 'user', 'post', '' );
$body = filter_text_input( 'body', 'post', '', 1 );
$where = filter_text_input( 'where', 'post', '' );
$key = $nv_Request->get_int( 'id', 'post', 0 );

// liem tra thoi gian
$timeout = $nv_Request->get_int( $module_name . '_error_' . $where . "_" . $key, 'cookie', 0 );
if ( $timeout == 0 or NV_CURRENTTIME - $timeout > 90 )
{
	$check = 0;
	if ( $where == 'song' )
	{
		$song = getsongbyID( $key );
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
		   $ok = 1;
		   $contents = $lang_module['send_error_not'];
		}
		else
		{
			$ok = 0;
		}
		$check = 1;
	}
	$nv_Request->set_Cookie( $module_name . '_error_' . $where . "_" . $key, NV_CURRENTTIME );
	
	if ( ($check == 0) || ( ($check == 1) && ($ok == 0) ) )
	{
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_error` ( `id`, `user`, `body`, `where`, `key` ) VALUES ( NULL, " . $db->dbescape( $user ) . ", " . $db->dbescape( $body ) . ", " . $db->dbescape( $where ) . ", " . $key . " )"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$contents = $lang_module['send_error_suc'];
		} 
		else
		{
			$contents = $lang_module['send_error_error'] ;
		}
	}
}
else
{
	$contents = $lang_module['ready_send_error'] ;
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>