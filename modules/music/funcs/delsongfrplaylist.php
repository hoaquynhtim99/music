<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$plid = $nv_Request->get_int( 'plid', 'post', 0 );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active` = 1 AND id = " . $plid;
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

$songdatanew = '';
$songdata = explode ( '/', $row['songdata'] );
foreach ( $songdata as $value )
{
	if ( ( intval($value) == $id ) || ( $value == '' ) ) continue;
	$songdatanew .= '/' . $value;
}
$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `songdata` = " . $db->dbescape( $songdatanew ) . " WHERE `id` =" . $plid );

echo $id;
?>