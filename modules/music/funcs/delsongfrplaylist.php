<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
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