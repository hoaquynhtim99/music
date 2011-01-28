<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011
 * @Createdate 29/01/2011 02:41 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL, $db;
$xtpl = new XTemplate( "block_gift.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_gift ORDER BY id DESC LIMIT 0,6";
$query = $db->sql_query( $sql );
$i = 1;
while($gift =  $db->sql_fetchrow( $query ))
{
	$song =  getsongbyID( $gift['songid'] );

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