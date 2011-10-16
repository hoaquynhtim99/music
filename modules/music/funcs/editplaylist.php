<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

if ( ! defined ( "NV_IS_USER" ) )
{
	$link_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
	
	Header( "Location: " . $link_redirect );
	exit();
}

$allsinger = getallsinger();

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active` = 1 AND id = " . $id . " AND `userid`=" . $user_info['userid'];
$result = $db->sql_query( $sql );
$check = $db->sql_numrows( $result );
if( $check != 1 )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    exit();
}

$row = $db->sql_fetchrow( $result );

// Thong tin trang
$page_title = $lang_module['playlist_edit'] . NV_TITLEBAR_DEFIS . $row['name'] . NV_TITLEBAR_DEFIS . $user_info['username'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $setting['description'];

$name = $user_info['username'];
$userid = $user_info['userid'];

$g_array = array(
	"username" => $name,  //
	"userid" => $userid,  //
	"id" => $id  //
);

$ok = 0;
if( $nv_Request->get_int( 'ok', 'post', 0 ) == 1 )
{
	$pldata = array();
	$row['name'] = $pldata['name'] = filter_text_input( 'name', 'post', '' );
	$row['keyname'] = $pldata['keyname'] = change_alias ( $pldata['name'] );
	$row['singer'] = $pldata['singer'] = filter_text_input( 'singer', 'post', '' );
	$row['message'] = $pldata['message'] = $nv_Request->get_string( 'message', 'post', '' );
	
	$result = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET
		`name` = " . $db->dbescape( $pldata['name'] ) . ",
		`keyname` = " . $db->dbescape( $pldata['keyname'] ) . ",
		`singer` = " . $db->dbescape( $pldata['singer'] ) . ",
		`message` = " . $db->dbescape( $pldata['message'] ) . "
	WHERE `id` =" . $id );
	
	if ( $result ) 
	{
		$ok = 1;
		nv_del_moduleCache( $module_name );
	}
}

$g_array['ok'] = $ok;

$songdata = explode ( ',', $row['songdata'] );
$songdata = array_filter( $songdata );
$songdata = array_unique( $songdata );
$songdata = implode( ",", $songdata );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $songdata . ") AND `active`=1";
$result = $db->sql_query( $sql );

$array = array();
while( $song = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"songid" => $song['id'],  //
		"songname" => $song['tenthat'],  //
		"songsinger" => $allsinger[$song['casi']],  //
		"url_view" => $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'],  //
		"url_search_singer" => $mainURL . "=search/singer/" . $song['casi']  //
	);
}

$contents = nv_music_editplaylist( $g_array, $array, $row );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>