<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
 
if( ! defined('NV_IS_MUSIC_ADMIN') )
{
	die('Stop!!!');
}

// tao gia tri
$result = false;
$id = $nv_Request->get_int('id', 'post,get');
if( empty( $id ) ) die( "Stop!!!" );

// xoa quang cao
if( $id > 0 )
{
	// xoa khoi thu muc upload
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ads WHERE `id` = ". $id;
	$query = $db->sql_query( $sql );
	$ads = $db->sql_fetchrow( $query );
	unlink( NV_DOCUMENT_ROOT . $ads['link'] );
	
	// xoa khoi database
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ads` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
	nv_del_moduleCache( $module_name );
}

// tra ve gia tri
if( $result )
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}

?>