<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:05 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
	die( 'Stop!!!' );
}

$result = false;
$id = $nv_Request->get_int( 'id', 'post,get' );
$where = filter_text_input( 'where', 'get', '' );
$setting = setting_music();

if( empty( $id ) ) die( "Stop!!!" );

if( $id > 0 )
{
	if( $where == '_album' )
	{
		// Xoa trong album trang chu
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` WHERE `albumid`=" . $id;
		$db->sql_query( $sql );

		// Xoa album HOT
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` WHERE `albumid`=" . $id;
		$db->sql_query( $sql );

		$album = getalbumbyID( $id );

		// Cap nhat lai so album cua ca si
		updatesinger( $album['casi'], 'numalbum', '-1' );

		// Xoa cac bao loi, binh luan
		delcomment( 'album', $album['id'] );
		delerror( 'album', $album['id'] );

		// Cap nhat lai album thanh chua biet cho cac bai hat
		updateSwhendelA( $id, 0 );
	}
	if( $where == '_video' )
	{
		$video = getvideobyID( $id );
		updatesinger( $video['casi'], 'numvideo', '-1' );
		updateauthor( $video['nhacsi'], 'numvideo', '-1' );
		delcomment( 'video', $video['id'] );
		unlinkSV( $video['server'], $video['duongdan'] );
	}
	if( $where == '_singer' )
	{
		$singer = getsingerbyID( $id );
		updatewhendelS( $singer['id'], 0 );
	}
	if( $where == '_author' )
	{
		$author = getauthorbyID( $id );
		updatewhendelA( $author['id'], 0 );
	}
	if( $where == '_ftp' )
	{
		updatewhendelFTP( $id, 0 );
	}
	if( $where == '' )
	{
		$song = getsongbyID( $id );
		if( $song['album'] != 0 )
		{
			updatealbum( $song['album'], '-1' );
		}

		// Cap nhat so bai hat cua ca si, nhac si
		updatesinger( $song['casi'], 'numsong', '-1' );
		updateauthor( $song['nhacsi'], 'numsong', '-1' );

		// Xoa binh luan, loi bai hat, qua tang am nhac, bao loi
		delcomment( 'song', $song['id'] );
		dellyric( $song['id'] );
		delerror( 'song', $song['id'] );
		delgift( $song['id'] );

		// Xoa file nhac
		unlinkSV( $song['server'], $song['duongdan'] );
	}
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
}

nv_del_moduleCache( $module_name );

if( $result )
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}

?>