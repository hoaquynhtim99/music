<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011
 * @Createdate 29/01/2011 02:41 AM
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'send_gift', 'post' ) )
{
	$checksess = filter_text_input( 'checksess', 'post', '' );
	if( $checksess != md5( "gift_" . $global_config['sitekey'] . session_id() ) ) die('Error access !!!');
	
	// Lay du lieu
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$who_send = filter_text_input( 'who_send', 'post', '', 1, 255 );
	$who_receive = filter_text_input( 'who_receive', 'post', '', 1, 255 );
	$email_receive = filter_text_input( 'email_receive', 'post', '', 1, 255 );
	$body = filter_text_input( 'body', 'post', '', 1, 500 );

	// Kiem tra
	if( empty( $id ) ) die( "Error access !!!" );
	if( empty( $who_send ) ) die( $lang_module['error_gift_send'] );
	if( empty( $who_receive ) ) die( $lang_module['error_gift_recieve'] );
	if( empty( $email_receive ) ) die( $lang_module['error_empty_email'] );
	$check_valid_email = nv_check_valid_email( $email_receive );
	if( ! empty( $check_valid_email ) )
	{
		die( str_replace( array( "&rdquo;", "&ldquo;" ), " ", strip_tags( $check_valid_email ) ) );
	}

	// Kiem tra thoi gian
	$timeout = $nv_Request->get_int( $module_name . '_gift' , 'cookie', 0 );
	if ( $timeout == 0 or NV_CURRENTTIME - $timeout > 360 )
	{
		$song = getsongbyID( $id );
		if( empty( $song ) ) die( $lang_module['err_exist_song'] );
		
		$nv_Request->set_Cookie( $module_name . '_gift' , NV_CURRENTTIME );
		
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_gift` VALUES ( 
			NULL, 
			" . $db->dbescape( $who_send ) . ", 
			" . $db->dbescape( $who_receive ) . ", 
			" . $db->dbescape( $id ) . ", 
			UNIX_TIMESTAMP(), 
			" . $db->dbescape( $body ) . ", 
			" . $setting['auto_gift']  . " 
		)"; 
		
		if ( $db->sql_query_insert_id( $sql ) ) 
		{
			if( $setting['auto_gift'] )
			{
				nv_del_moduleCache( $module_name );
			}
			
			$link = "<a href=\"" . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "\">" . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "</a>";
			
			$subject = $lang_module['sendmail_welcome'] . " \"" . $who_send . "\"";
			$message = sprintf( $lang_module['gift_message'], $who_receive, $who_send, NV_MY_DOMAIN, $body, $link );
			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $email_receive, $subject, $message );
			
			if( $setting['auto_gift'] ) die( "OK" );
			else die( "WAIT" );
		}
		else
		{
			die( $lang_module['send_gift_error'] );
		}
	}
	else
	{
		die( $lang_module['ready_send_gift'] );
	}
}

// Them bai hat vao playlist
if ( $nv_Request->isset_request( 'addplaylist', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

	if( empty( $id ) ) die( "Error Access!!!" );

	if ( $num >= 20 ) die( $lang_module['playlist_full'] );

	// Kiem tra bai hat da them vao chua
	for ( $i = 1 ; $i <= $num ; $i ++ )
	{
		$song = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
		if ( $song == $id ) die( $lang_module['playlist_added'] );
	}

	// Neu chua thi them vao
	$numnext = $num + 1 ;
	$nv_Request->set_Cookie( $module_name . '_song' . $numnext, $id );
	$nv_Request->set_Cookie( $module_name . '_numlist', $numnext );
	die( "OK_" . $lang_module['playlist_add_success'] );
}

// Xoa mot playlist chua luu vao CSDL
if ( $nv_Request->isset_request( 'delplaylist', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	// Lay so bai hat
	$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

	if ( $num == 0 ) die( $lang_module['playlist_null'] );

	// Dat cac ban hat lai thanh 0
	for ( $i = 1 ; $i <= $num ; $i ++ )
	{
		$nv_Request->set_Cookie( $module_name . '_song' . $i, 0 );
	}

	$nv_Request->set_Cookie( $module_name . '_numlist' , 0 );
	die( $lang_module['playlist_del_success'] );
}

die( "Error Access !!!" );

?>