<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$xtpl = new XTemplate( "listenlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL."modules/" . $module_data . "/data/" );

$xtpl->assign( 'ads', getADS() );

// xu li thong tin
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

$xtpl->assign( 'ID',  $id );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id = ".$id ."";
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
// update album
$i = $row['numview'] + 1 ;
$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numview` = " . $db->dbescape( $i ) . " WHERE `id` =" . $id . "");
// cac bai hat cua album
$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE album = \"".$row['name'] ."\" ORDER BY id DESC";
$querysong = $db->sql_query( $sqlsong );
$i = 1 ;
while ($rowsong = $db->sql_fetchrow( $querysong ) )
{
	$xtpl->assign( 'song_name', $i.". ".$rowsong['tenthat'] );
	$xtpl->assign( 'song_singer', $rowsong['casithat'] );
	$xtpl->assign( 'song_url', $rowsong['duongdan'] );
	$xtpl->parse( 'main.song' );
	$i ++ ;
}
$xtpl->assign( 'name', $row['tname'] );
$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $row['upboi']);
$xtpl->assign( 'singer', $row['casithat'] );
$xtpl->assign( 'numview', $row['numview'] );
$xtpl->assign( 'who_post', $row['upboi'] );
$xtpl->assign( 'album_thumb', $row['thumb'] );
$xtpl->assign( 'describe', $row['describe'] );
$xtpl->assign( 'URL_ALBUM', get_URL() );

// tieu de trang
$page_title = "Album ". $row['tname'] . " - " .$row['casithat'] ;
$key_words =  $row['tname'] . " - " .$row['casithat'] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );



?>