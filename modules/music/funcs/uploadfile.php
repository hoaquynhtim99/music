<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );



$uploaddir = NV_ROOTDIR . '/uploads/' . $module_data . '/' . $setting['root_contain'] . '/upload/'; 
$file = $uploaddir . basename( $_FILES['uploadfile']['name'] ); 

$size = $_FILES['uploadfile']['size'];
$songname = filter_text_input( 'song', 'get,post', '' );
$singer = filter_text_input( 'singer', 'get,post', '' );
$newsinger = filter_text_input( 'newsinger', 'get,post', '' );
$author = filter_text_input( 'author', 'get,post', '' );
$newauthor = filter_text_input( 'newauthor', 'get,post', '' );
$category = $nv_Request->get_int( 'category', 'get,post', 0 );
if ( defined( 'NV_IS_USER' ) )
{
    $name = $user_info['username'];
    $userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
    $name = $admin_info['username'];
    $userid = $admin_info['userid'];
}
else
{
    $name = $lang_module['upload_visittor'];
	$userid = 0;
}

if ( $newsinger != $lang_module['upload_quicksinger'] )
{
	newsinger( change_alias( $newsinger ), $newsinger );
	$singer = $newsinger;
}

if ( $newauthor != $lang_module['upload_quickauthor'] )
{
	newauthor( change_alias( $newauthor ), $newauthor );
	$author = $newauthor;
}

if ( $size > ( $setting['upload_max'] * ( 1024 * 1024 ) ) )
{
	echo "error file size is too big";
	@unlink($_FILES['uploadfile']['tmp_name']);
	exit;
}
if ( move_uploaded_file ( $_FILES['uploadfile']['tmp_name'], $file ) ) 
{ 
	require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.php' );
	require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.functions.php' );
	$au = GetAllMP3info( $file );
	$bitrate = $au['bitrate'];
	$filesize = $au['filesize'];
	$duration = $au['playtime_seconds'];
	
	$lu = strlen( NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
	$duongdan = substr( $file, $lu );
	
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` ( `id`, `ten`, `tenthat`, `casi`, `nhacsi`, `album`, `theloai`, `duongdan`, `upboi`, `numview`, `active`, `bitrate`, `size`, `duration`, `server`, `userid` ) VALUES ( NULL, " . $db->dbescape( change_alias( $songname ) ) . ", " . $db->dbescape( $songname ) . ", " . $db->dbescape( change_alias( $singer ) ) . ", " . $db->dbescape( change_alias( $author ) ) . ", 'na', " . $db->dbescape( $category ) . ", " . $db->dbescape( $duongdan )  . ", " . $db->dbescape( $name ) . " , 0, " . $setting['auto_upload'] . ", " . $bitrate . " , " . $filesize . " , " . $duration . ", 1, " . $userid . " ) "; 
	if ( $db->sql_query_insert_id( $query ) )
	{
		$db->sql_freeresult();
	}
	else
	{
		@unlink( $file );
		echo '<div id="output">failed</div>';
		echo '<div id="message"></div>';
		exit;
	}
	updatesinger( $singer, 'numsong', '+1' );
	echo '<div id="output">success</div>';
	if ( $setting['auto_upload'] == 1 )
	{
		$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY id DESC LIMIT 0,1" );
		$song = $db->sql_fetchrow($result);
		echo '<div id="message">' . $lang_module['upload_ok4'] . ' <a href="' . $mainURL . '=listenone/' . $song['id'] . '/' . $song['ten'] . '" target="_blank">' . $lang_module['upload_ok1'] . '</a> ' . $lang_module['upload_ok2'] . ' <a href="' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '">' . $lang_module['upload_ok3'] . '</a></div>';
	}else
	{
		echo '<div id="message">' . $lang_module['upload_nook'] . '</div>';
	}
} 
else 
{
	echo '<div id="output">failed</div>';
	echo '<div id="message"></div>';
}
?>