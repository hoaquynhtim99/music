<?php

/* *
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['video'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'];
$key_words = $module_info['keywords'];

$category = get_videocategory();
$allsinger = getallsinger();

$sqlhot = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `active` = 1 ORDER BY `view` DESC LIMIT 0,12";
$sqlnew = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `active` = 1 ORDER BY `id` DESC LIMIT 0,12";

$array_new = array();
$array_hot = array();

// Video moi
$result = $db->sql_query( $sqlnew );
while( $row = $db->sql_fetchrow( $result ) )
{
	$array_new[] = array(
		"name" => $row['tname'],  //
		"singer" => $allsinger[$row['casi']],  //
		"thumb" => $row['thumb'],  //
		"url_view" => $mainURL . "=viewvideo/" .$row['id']. "/" . $row['name'],  //
		"url_search_singer" => $mainURL . "=searchvideo/singer/" . $row['casi']  //
	);
}

// Video hot
$result = $db->sql_query( $sqlhot );
while( $row = $db->sql_fetchrow($result) )
{
	$array_hot[] = array(
		"name" => $row['tname'],  //
		"singer" => $allsinger[$row['casi']],  //
		"thumb" => $row['thumb'],  //
		"url_view" => $mainURL . "=viewvideo/" .$row['id']. "/" . $row['name'],  //
		"url_search_singer" => $mainURL . "=searchvideo/singer/" . $row['casi']  //
	);
}

$contents = nv_music_video ( $category, $array_new, $array_hot );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>