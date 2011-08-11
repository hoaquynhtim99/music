<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$allsinger = getallsinger();

// lay thong tin playlist
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
			"song_name" => $row['tenthat'],  //
			"song_singer" => $allsinger[$row['casi']],  //
			"song_url" => outputURL ( $row['server'], $row['duongdan'] )  //
		);
	}
}

$contents = nv_music_playlist( $g_array, $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>