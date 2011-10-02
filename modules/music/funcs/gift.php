<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:26 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Set page title, keywords, description
$page_title = $mod_title = $lang_module['gift_list'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$description = $setting['description'];
$key_words = $module_info['keywords'];

$allsinger = getallsinger();

//
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 10;
$base_url = $mainURL . "=" . $op;
	
$sql = "SELECT SQL_CALC_FOUND_ROWS a.who_send, a.who_receive, a.time, a.body, b.id AS `songid`, b.ten AS `songname`, b.tenthat AS `songtitle`, b.casi AS `songsinger` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.songid=b.id WHERE a.active=1 ORDER BY a.id DESC LIMIT " . $page . "," . $per_page;

$result = $db->sql_query( $sql );
$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

if ( ! $all_page or $page >= $all_page )
{
	if ( $nv_Request->isset_request( 'page', 'get' ) )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		exit();
	}
}
	
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$array = array();
while ( $row = $db->sql_fetchrow( $result ) )
{
	$row['singer'] = $allsinger[$row['songsinger']];
	$row['url_listen'] = $mainURL . "=listenone/" . $row['songid'] . "/" . $row['songname'];
	$row['url_search_singer'] = $mainURL . "=search/singer/" . $row['songsinger'];
	$array[] = $row;
}

$contents = nv_music_gift( $array, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>