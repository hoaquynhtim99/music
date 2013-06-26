<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 05:11 PM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$body = $nv_Request->get_string( 'body', 'post', '' );
$where = filter_text_input( 'where', 'post', '', 1 );

if( ! in_array( $where, array( "song", "album", "video" ) ) )
{
	die( "ERR_" . $lang_module['comment_error'] );
}

if( defined( 'NV_IS_USER' ) )
{
	$name = $user_info['username'];
	$userid = $user_info['userid'];
}
else
{
	$name = filter_text_input( 'name', 'post', '', 1 );
	$userid = 0;
}

$contents = "";

$timeout = $nv_Request->get_int( $module_name . '_' . $op . '_' . $where . '_' . $id, 'cookie', 0 );

if( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` (`id`, `name`, `body`, `dt`, `what`, `userid`, `active`) VALUES (NULL, " . $db->dbescape( $name ) . ", " . $db->dbescape( $body ) . ", " . NV_CURRENTTIME . " , " . $db->dbescape( $id ) . ",  " . $userid . ", " . $setting['auto_comment'] . " )";
	$result = $db->sql_query( $sql );
	if( $result )
	{
		$nv_Request->set_Cookie( $module_name . '_' . $op . '_' . $where . '_' . $id, NV_CURRENTTIME );
		$contents = "OK_" . $id . "_" . $where . "_" . $lang_module['comment_success'];
	}
	else
	{
		$contents = "ERR_" . $lang_module['comment_error'];
	}
}
else
{
	$contents = "ERR_" . $lang_module['comment_timeouts'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>