<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $mainURL;
$xtpl = new XTemplate( "block_samesinger.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

// lay id bai hat
$songid = get_URL() ;
$songidarray = explode( '/', $songid );
$num_url = count( $songidarray ) - 3 ;

$songid = $songidarray[$num_url] ;
if ( $num_url <= 3 ) 
{ 
	$num_url = count( $songidarray ) - 2 ;
	$songid = $songidarray[$num_url] ;
}
$source = mysql_query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `id` =".$songid."");
$data = mysql_fetch_array($source);
$casi = $data['casi'];

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE casi =\"".$casi."\" ORDER BY id DESC LIMIT 0,10";
$query = mysql_query( $sql );
while($song =  mysql_fetch_array( $query ))
{
	$xtpl->assign( 'url_listen', $mainURL . "=listenone/" .$song['id']. "/" . $song['ten'] );
	$xtpl->assign( 'song_name', $song['tenthat'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>