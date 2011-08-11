<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['album_creat'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$key_words = $module_info['keywords'];

$allsinger = getallsinger();

if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}
else
{
	$username = "";
	$userid = 0;
}

$g_array = array(
	"username" => $username,  //
	"userid" => $userid  //
);

if( $userid )
{
	$array = array( "song" => array(), "playlist" => array() );
	
	// Get song
	$numsong = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );
	if ( $numsong > 0 )
	{
		$list_song = array();
		for ( $i = 1; $i <= $numsong; $i ++ )
		{
			$list_song[] = $nv_Request->get_int( $module_name . '_song'. $i , 'cookie', 0 );
		}
		$list_song = implode( ",", $list_song );
		
		if( ! empty( $list_song ) )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `active` = 1 AND `id` IN(" . $list_song . ") ORDER BY `ten` ASC";
			$result = $db->sql_query( $sql );
			$i = 1;
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array['song'][] = array(
					"stt" => $i,  //
					"songname" => $row['tenthat'],  //
					"singer" => $allsinger[$row['casi']],  //
					"url_view" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'],  //
					"url_search_singer" => $mainURL . "=search/singer/" . $row['casi']  //
				);
				$i ++;
			}
		}
	}
	
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active` = 1 AND `userid` = " . $userid . " ORDER BY `id` DESC";
	$result = $db->sql_query( $sql );
	$numlist = $db->sql_numrows( $result );
	$g_array['num'] = $numlist;
	$g_array['playlist_max'] = $setting['playlist_max'];
	
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		$array['playlist'][] = array(
			"playlist_img" => "",  //
			"name" => $row['name'],  //
			"singer" => $row['singer'],  //
			"date" => $row['time'],  //
			"id" => $row['id'],  //
			"view" => $row['view'],  //
			"url_view" => $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'],  //
			"url_edit" => $mainURL . "=editplaylist/" . $row['id']  //
		);
	}
}

$contents = nv_music_creatalbum( $g_array, $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>