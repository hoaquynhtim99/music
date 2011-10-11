<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 180;

// Lay du lieu
$name = filter_text_input( 'name', 'post', '' );
$keyname = change_alias( $name );
$singer = filter_text_input( 'singer', 'post', '' );
$message = $nv_Request->get_string( 'message', 'post', '' );

if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}
else
{
	$username = "";
	$userid = 0;
}

$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );
$songdata = array();
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$tmp = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
	$songdata[] = $tmp;
}
	
$timeout = $nv_Request->get_int( $module_name . '_' . $userid, 'cookie', 0 );

if ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` (`id`, `userid`, `username`, `name`, `keyname`, `singer`, `message`, `songdata`, `time`, `view`, `active`) VALUES ( NULL, " . $userid . ", \"" . $username  . "\", \"" .  $name  . "\", \"" .  $keyname  . "\", \"" .  $singer  . "\", \"" .  $message  . "\", \"" . implode( ",", $songdata ) . "\", UNIX_TIMESTAMP() , 0, " . $setting['auto_album'] . ")"; 
			
	$aaaa = $db->sql_query( $query ) ? "1_" : "0_" ;
	$nv_Request->set_Cookie( $module_name . '_' . $userid, NV_CURRENTTIME );
	
	$aaaa .= $setting['auto_album'] ? $lang_module['playlist_add_ok'] : $lang_module['playlist_add_waiting'];
	$aaaa .= "_" . nv_url_rewrite ( $main_header_URL . "=creatalbum", true );
}
else
{
	$aaaa = $lang_module['err_cre_album'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo ( $aaaa ) ;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>