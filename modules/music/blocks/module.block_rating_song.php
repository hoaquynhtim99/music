<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $module_data, $mainURL, $db, $lang_module, $module_file;

$xtpl = new XTemplate( "block_rating_song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$sql = "SELECT a.songid, b.ten, b.tenthat, b.casi, c.ten AS singeralias, c.tenthat AS casithat  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.songid=b.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS c ON b.casi=c.id WHERE b.active=1 ORDER BY a.hit DESC LIMIT 0,10";
$result = $db->sql_query( $sql );

$i = 1;
while( $data = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'STT', $i );
	$xtpl->assign( 'url_view', $mainURL . "=listenone/" . $data['songid'] . "/" . $data['ten'] );
	$xtpl->assign( 'songname', $data['tenthat'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . ( $data['singeralias'] ? $data['singeralias'] : '-' ) );
	$xtpl->assign( 'singer', empty( $data['casithat'] ) ? $lang_module['unknow'] : $data['casithat'] );
	$xtpl->parse( 'main.loop' );
	$i++;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>