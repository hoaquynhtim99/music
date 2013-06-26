<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$contents = "";

$id = $nv_Request->get_int( 'id', 'get', 0 );
$where = filter_text_input( 'where', 'get', '', 1 );
$page = $nv_Request->get_int( 'page', 'get', 0 );

// Kiem tra tinh hop le du lieu
if( ! in_array( $where, array( 'song', 'album', 'video' ) ) or empty( $id ) ) die( "Error Access!!!" );

$g_array = array(
	"id" => $id, //
	"where" => $where, //
);

// So binh luan
list( $num ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) AS num FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` WHERE `what`=" . $id . " AND `active`=1" ) );
$sql = "SELECT a.id, a.name, a.body, a.dt, a.what, b.username, b.photo FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` AS a LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` AS b ON a.userid=b.userid WHERE a.what = " . $id . " AND a.active= 1 ORDER BY a.id DESC LIMIT " . $page . ",8";
$result = $db->sql_query( $sql );

$g_array['num'] = $num;
$g_array['page'] = $page;

// Xuat binh luan
$array = array();
require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
while( $row = $db->sql_fetchrow( $result ) )
{
	$row['body'] = m_emotions_replace( $row['body'] );
	$array[] = array(
		"body" => $row['body'], //
		"name" => empty( $row['username'] ) ? $row['name'] : $row['username'], //
		"date" => $row['dt'], //
		"avatar" => $row['photo'] //
	);
}

$contents = nv_music_showcomment( $g_array, $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>