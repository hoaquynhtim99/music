<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:09 AM
 */

if (! defined ( 'NV_IS_MUSIC_ADMIN' )) { die ( 'Stop!!!' ); }

$id = $nv_Request->get_int ( 'id', 'get', 0 );
$where = filter_text_input( 'where', 'get', '' );

$sql = "SELECT active FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id` =" . $id . ";";
$result = $db->sql_query( $sql );
list( $active ) = $db->sql_fetchrow( $result );
$active = ( $active == 1 ) ? 0 : 1;
$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET `active` = " . $db->dbescape( $active ) . " WHERE `id` =" . $id . ";";
$db->sql_query( $sql );

if ( $where == '_ftp' ) { updatewhendelFTP( $id, $active ); }

$str = ($active == 1) ? $lang_module['active_yes'] : $lang_module['active_no'];

nv_del_moduleCache( $module_name );

echo $lang_module['active_succer']. " \"". $str . " \"";

?>