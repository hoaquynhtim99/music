<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
if ( ! defined( 'NV_IS_USER' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$plid = $nv_Request->get_int( 'plid', 'post', 0 );

if( empty( $plid ) ) die( 'Error !!!!!' );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active` = 1 AND id = " . $plid . " AND `userid`=" . $user_info['userid'];
$result = $db->sql_query( $sql );
$check = $db->sql_numrows( $result );
if( $check != 1 ) die( 'Error !!!!!' );

$row = $db->sql_fetchrow( $result );

$songdatanew = '';
$songdata = explode ( '/', $row['songdata'] );
foreach ( $songdata as $value )
{
	if ( ( intval($value) == $id ) || ( $value == '' ) ) continue;
	$songdatanew .= '/' . $value;
}
$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `songdata` = " . $db->dbescape( $songdatanew ) . " WHERE `id` =" . $plid );

echo "OK_" . $id;

?>