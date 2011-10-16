<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['listen_playlist'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $setting['description'];

$allsinger = getallsinger();

// Lay thong tin playlist
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

$g_array = array();
$g_array['num'] = $num;
$array = array();

if( ! empty( $num ) )
{
	$songid= array();
	for ( $i = 1 ; $i <= $num ; $i ++ )
	{
		$songid[] = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
	}
	
	$songid = implode( ",", $songid );
	
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN (" . $songid . ") AND `active`=1";
	$result = $db->sql_query( $sql );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array[] = array(
			"id" => $row['id'],  //
			"song_name" => $row['tenthat'],  //
			"song_singer" => $allsinger[$row['casi']],  //
			"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'],  //
			"song_url" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true )  //
		);
	}
}

$contents = nv_music_playlist( $g_array, $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>