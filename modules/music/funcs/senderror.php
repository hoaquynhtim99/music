<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

// Lay du lieu
$user = filter_text_input( 'user', 'post', '' ); // Ten nguoi gui bao loi
$body = filter_text_input( 'body', 'post', '', 1 );
$where = filter_text_input( 'where', 'post', '' );
$root_error = filter_text_input( 'root_error', 'post', '' );
$key = $nv_Request->get_int( 'id', 'post', 0 );

// Neu da dang nhap thi khong duoc phep doi ten dang nhap
$username = ! empty( $user_info['username'] ) ? $user_info['username'] : $user;
$userid = ! empty( $user_info['userid'] ) ? $user_info['userid'] : 0;

// Kiem tra thoi gian
$timeout = $nv_Request->get_int( $module_name . '_error_' . $where . "_" . $key, 'cookie', 0 );
if ( $timeout == 0 or NV_CURRENTTIME - $timeout > 90 )
{
	$check = 0;
	
	// Neu day la ba hat va kiem tra loi khong ton tai
	if ( ( $where == 'song' ) and ( $root_error == "check" ) )
	{
		$song = getsongbyID( $key );
		$url = outputURL ( $song['server'], $song['duongdan'] ) ;
		if ( $song['server'] == 1 ) { $url = NV_MY_DOMAIN . $url ; }
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
	
	if ( ( $check == 0 ) or ( ( $check == 1 ) and ( $ok == 0 ) ) )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_error` VALUES (
			NULL, 
			" . $key . ", 
			" . $userid . ", 
			" . $db->dbescape( $username ) . ", 
			" . $db->dbescape( $root_error . " | " . $body ) . ", 
			" . $db->dbescape( $where ) . ", 
			" . NV_CURRENTTIME . ", 
			" . $db->dbescape( $client_info['ip'] ) . ", 1
		)"; 
		
		if ( $db->sql_query_insert_id( $sql ) ) 
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