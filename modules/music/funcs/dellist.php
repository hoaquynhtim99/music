<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

// lay du lieu
$id = $nv_Request->get_int( 'id', 'post', 0 );

if( ! defined( 'NV_IS_USER' ) )
{
	die( $lang_module['del_error'] );
}

if( $id > 0 )
{
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data ."_playlist` WHERE `id`=" . $id . " AND `userid`=" . $user_info['userid'];
    $result = $db->sql_query( $sql );
}

if( ! empty( $result ) )
{
	die( "OK_" . $id );
}

die( $lang_module['del_error'] );

?>