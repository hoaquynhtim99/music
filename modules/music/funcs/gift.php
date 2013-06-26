<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:26 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Set page title, keywords, description
$page_title = $mod_title = $lang_module['gift_list'];
$description = $setting['description'];
$key_words = $module_info['keywords'];

$page = isset( $array_op[1] ) ? intval( end( explode( "-", $array_op[1] ) ) ) : 1;
$per_page = 10;
$base_url = $mainURL . "=" . $op;

$sql = "SELECT SQL_CALC_FOUND_ROWS a.who_send, a.who_receive, a.time, a.body, b.id AS `songid`, b.ten AS `songname`, b.tenthat AS `songtitle`, b.casi AS `songsinger`, c.ten AS singeralias, c.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.songid=b.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS c ON b.casi=c.id WHERE a.active=1 ORDER BY a.id DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;

$result = $db->sql_query( $sql );
$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

if( ( ( $page - 1 ) * $per_page ) > $all_page and $page > 1 )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

if( $page > 1 )
{
	$page_title .= " " . NV_TITLEBAR_DEFIS . "  " . sprintf( $lang_module['page'], $page );
}

$generate_page = nv_alias_page( $lang_module['goto'], $base_url, $all_page, $per_page, $page );

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$row['singer'] = $row['singername'] ? $row['singername'] : $lang_module['unknow'];
	$row['url_listen'] = $mainURL . "=listenone/" . $row['songid'] . "/" . $row['songname'];
	$row['url_search_singer'] = $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $row['singer'] ) . "&amp;id=" . $row['songsinger'] . "&amp;type=singer";
	$array[] = $row;
}

$contents = nv_music_gift( $array, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>