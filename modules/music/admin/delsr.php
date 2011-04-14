<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if(!defined('NV_IS_MUSIC_ADMIN'))
{
	die('Stop!!!');
}
// tao gia tri
$result = false;
$id = $nv_Request->get_int('id', 'post,get');
$where = filter_text_input( 'where', 'get', '' );

// xoa cau hoi
if($id > 0)
{
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where ."` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
	$sqle = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `where` = 'song' AND `key`=" . $id;
    $db->sql_query( $sqle );
}

nv_del_moduleCache( $module_name );

// tra ve gia tri
if($result)
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}
?>