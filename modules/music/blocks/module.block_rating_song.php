<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:47 PM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $module_data, $mainURL, $db, $lang_module, $module_file;

$xtpl = new XTemplate( "block_rating_song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$allsinger = getallsinger();

$sql = "SELECT a.songid, b.ten, b.tenthat, b.casi  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.songid=b.id WHERE b.active=1 ORDER BY a.hit DESC LIMIT 0,10";
$query = $db->sql_query( $sql );

$i = 1;
while( $data =  $db->sql_fetchrow( $query ) )
{	
	$xtpl->assign( 'STT', $i );
	$xtpl->assign( 'url_view', $mainURL . "=listenone/" . $data['songid'] . "/" . $data['ten'] );
	$xtpl->assign( 'songname', $data['tenthat'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $data['casi'] );
	$xtpl->assign( 'singer', $allsinger[$data['casi']] );
	$xtpl->parse( 'main.loop' );
	$i++;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>