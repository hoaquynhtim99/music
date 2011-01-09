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

if($id > 0)
{
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data ."_playlist` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
}
if($result)
{
	echo "OK_" . $id;
}
else
{
	echo $lang_module['del_error'];
}

?>