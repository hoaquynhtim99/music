<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL;
$xtpl = new XTemplate( "block_gift.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_gift ORDER BY id DESC LIMIT 0,6";
$query = mysql_query( $sql );
$i = 1;
while($gift =  mysql_fetch_array( $query ))
{
	$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=".$gift['songid']."";
	$querysong = mysql_query( $sqlsong );
	$song =  mysql_fetch_array( $querysong );
	
	$xtpl->assign( 'url_listen', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );
	$xtpl->assign( 'from', $gift['who_send'] );
	$xtpl->assign( 'to', $gift['who_receive'] );
	$xtpl->assign( 'time', nv_date( "d/m/Y H:i", $gift['time'] ) );
	
	$sub = explode ( ' ', $gift['body'] ) ;
	$bodymini = $bodyfull = '';
	foreach ( $sub as $i => $value )
	{
		if ( $i < 25 ) 
		{
			$bodymini .= " " . $value;
		}
		else
		{
			$bodyfull .= " " . $value;
		}
	}
	$xtpl->assign( 'message', $bodymini );
	$xtpl->assign( 'fullmessage', $bodyfull );
	$xtpl->assign( 'DIV', $i );
	$xtpl->assign( 'songname', $song['tenthat'] );

	$i ++ ;
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>