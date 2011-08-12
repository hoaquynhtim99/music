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
$who_send = filter_text_input( 'who_send', 'post', '' );
$who_receive = filter_text_input( 'who_receive', 'post', '' );
$email_receive = filter_text_input( 'email_receive', 'post', '' );
$body = filter_text_input( 'body', 'post', '', 1 );
$setting = setting_music() ;

if( empty( $email_receive ) ) die( $lang_module['email_receive_null'] );
$check_valid_email = nv_check_valid_email( $email_receive );

if( ! empty( $check_valid_email ) )
{
	die( str_replace( array( "&rdquo;", "&ldquo;" ), " ", strip_tags( $check_valid_email ) ) );
}

// liem tra thoi gian
$timeout = $nv_Request->get_int( $module_name . '_gift' , 'cookie', 0 );
if ( $timeout == 0 or NV_CURRENTTIME - $timeout > 360 )
{
	$nv_Request->set_Cookie( $module_name . '_gift' , NV_CURRENTTIME );
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_gift` ( `id`, `who_send`, `who_receive`, `songid`, `time`, `body`, `active` ) VALUES ( NULL, " . $db->dbescape( $who_send ) . ", " . $db->dbescape( $who_receive ) . ", " . $db->dbescape( $id ) . ", UNIX_TIMESTAMP(), " . $db->dbescape( $body ) . ", " . $setting['auto_gift']  . " )"; 
	if ( $db->sql_query_insert_id( $query ) ) 
	{ 
		$song = getsongbyID( $id );
		
		$link = "<a href=\"" . NV_MY_DOMAIN . nv_url_rewrite( $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "\">" . NV_MY_DOMAIN . nv_url_rewrite( $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "</a>";
		
		$subject = $lang_module['sendmail_welcome'] . " <strong>" . $who_send . "</strong>";
		$message = sprintf( $lang_module['gift_message'], $who_receive, $who_send, NV_MY_DOMAIN, $body, $link );
		nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $email_receive, $subject, $message );
		
		$contents = $lang_module['send_gift_suc'];
	} 
	else
	{
		$contents = $lang_module['send_gift_error'] ;
	}
}
else
{
	$contents = $lang_module['ready_send_gift'] ;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>