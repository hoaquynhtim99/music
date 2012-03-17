<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$result = false;

$id = $nv_Request->get_int( 'id', 'post,get' );
if( empty( $id ) ) die( "Stop!!!" );

// Xoa khoi thu muc upload
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ads` WHERE `id` = " . $id;
$result = $db->sql_query( $sql );
$ads = $db->sql_fetchrow( $result );
unlink( NV_DOCUMENT_ROOT . $ads['link'] );

// Xoa khoi database
$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ads` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
nv_del_moduleCache( $module_name );

// Tra ve gia tri
if( $result )
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}

?>