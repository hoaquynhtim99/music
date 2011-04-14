<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:05 AM
 */
 
if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die('Stop!!!'); }

$result = false;
$id = $nv_Request->get_int('id', 'post,get');
$where = filter_text_input( 'where', 'get', '' );
$setting = setting_music();

if( $id > 0 )
{
	if ( $where == '_album' )
	{
		$album = getalbumbyID( $id );
		updatesinger( $album['casi'], 'numalbum', '-1' );
		delcomment('album', $album['id']);
		delerror( 'album', $album['id'] );
		updateSwhendelA( $album['name'], 'na' );
	}
	if ( $where == '_video' )
	{
		$video = getvideobyID( $id );
		updatesinger( $video['casi'], 'numvideo', '-1' );
		updateauthor( $video['nhacsi'], 'numvideo', '-1' );
		delcomment('video', $video['id']);
		unlinkSV ( $video['server'], $video['duongdan'] );
	}
	if ( $where == '_singer' )
	{
		$singer = getsingerbyID( $id );
		updatewhendelS( $singer['ten'], 'ns' );
	}
	if ( $where == '_author' )
	{
		$author = getauthorbyID( $id );
		updatewhendelA( $author['ten'], 'na' );
	}
	if ( $where == '_ftp' )
	{
		updatewhendelFTP( $id, 0 );
	}
	if ( $where == '' )
	{
		$song = getsongbyID( $id );
		if( $song['album'] != 'na' )
		{
			updatealbum( $song['album'], '-1' );
		}
		updatesinger( $song['casi'], 'numsong', '-1' );
		updateauthor( $song['nhacsi'], 'numsong', '-1' );
		delcomment('song', $song['id']);
		dellyric($song['id']);
		delerror( 'song', $song['id'] );
		delgift( $song['id'] );
		unlinkSV ( $song['server'], $song['duongdan'] );
	}
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where ."` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
}

nv_del_moduleCache( $module_name );

if ( $result )
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}

?>