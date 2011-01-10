<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db, $array_op;
$xtpl = new XTemplate( "block_samealbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$songid = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

$data = getsongbyID( $songid );
$casi = $data['album'];

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `active` = 1 AND album =\"".$casi."\" ORDER BY id DESC LIMIT 0,10";
$query = $db->sql_query( $sql );
while($song =  $db->sql_fetchrow( $query ))
{
	if ( $data['id'] == $song['id'] ) continue;
	$xtpl->assign( 'url_listen', $mainURL . "=listenone/" .$song['id']. "/" . $song['ten'] );
	$xtpl->assign( 'song_name', $song['tenthat'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>