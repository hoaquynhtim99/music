<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['video'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'];
$key_words = $module_info['keywords'];

$category = get_videocategory();

$sqlhot = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 ORDER BY a.view DESC LIMIT 0,12";
$sqlnew = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 ORDER BY a.id DESC LIMIT 0,12";

$array_new = array();
$array_hot = array();

// Video moi
$result = $db->sql_query( $sqlnew );
while( $row = $db->sql_fetchrow( $result ) )
{
	$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

	$array_new[] = array(
		"name" => $row['tname'], //
		"singer" => $singername, //
		"thumb" => $row['thumb'], //
		"url_view" => $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'], //
		"url_search_singer" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" //
	);
}

// Video hot
$result = $db->sql_query( $sqlhot );
while( $row = $db->sql_fetchrow( $result ) )
{
	$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

	$array_hot[] = array(
		"name" => $row['tname'], //
		"singer" => $row['singername'] ? $row['singername'] : $lang_module['unknow'], //
		"thumb" => $row['thumb'], //
		"url_view" => $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'], //
		"url_search_singer" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" //
	);
}

$contents = nv_music_video( $category, $array_new, $array_hot );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>