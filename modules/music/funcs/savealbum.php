<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
$contents = "";
$difftimeout = 180;

// lay du lieu
$name = filter_text_input( 'name', 'post', '' );
$keyname = change_alias($name);
$singer = filter_text_input( 'singer', 'post', '' );
$message = $nv_Request->get_string( 'message', 'post', '' );
if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$username = $admin_info['username'];
	$userid = $admin_info['userid'];
}

$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );
$songdata = '';
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$tmp = $nv_Request->get_int( $module_name . '_song'.$i.'' , 'cookie', 0 );
	$songdata .= "/" . $tmp;
}
	
$timeout = $nv_Request->get_int( $module_name . '_' . $userid, 'cookie', 0 );

if ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` (`id`, `userid`, `username`, `name`, `keyname`, `singer`, `message`, `songdata`, `time`, `view`, `active`) VALUES ( NULL, " . $userid . ", \"" . $username  . "\", \"" .  $name  . "\", \"" .  $keyname  . "\", \"" .  $singer  . "\", \"" .  $message  . "\", \"" . $songdata . "\", UNIX_TIMESTAMP() , 0, " . $setting['auto_album'] . ")"; 
			
	$aaaa = $db->sql_query( $query ) ;
	$nv_Request->set_Cookie( $module_name . '_' . $userid, NV_CURRENTTIME );
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