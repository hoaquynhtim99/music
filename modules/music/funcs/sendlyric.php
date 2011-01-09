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
$id = $nv_Request->get_int( 'id', 'post', 0 );
$user_lyric = filter_text_input( 'user_lyric', 'post', '' );
$body_lyric = $nv_Request->get_string( 'body_lyric', 'post', '', 1 );
$setting = setting_music() ;

// liem tra thoi gian
$timeout = $nv_Request->get_int( $module_name . '_lyric' , 'cookie', 0 );
if ( $timeout == 0 or NV_CURRENTTIME - $timeout > 360 )
{
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` ( `id`, `songid`, `user`, `body`, `active`, `dt` ) VALUES ( NULL, " . $db->dbescape( $id ) . ", " . $db->dbescape( $user_lyric ) . ", " . $db->dbescape( $body_lyric ) . ", " . $setting['auto_lyric']  . ", UNIX_TIMESTAMP() )"; 
	if ( $db->sql_query_insert_id( $query ) ) 
	{ 
		$nv_Request->set_Cookie( $module_name . '_lyric' , NV_CURRENTTIME );
		$contents = $lang_module['send_lyric_suc'];
	} 
	else
	{
		$contents = $lang_module['send_lyric_error'] ;
	}
}
else
{
	$contents = $lang_module['ready_lyric_gift'] ;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
?>