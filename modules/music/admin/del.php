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
	elseif( $where == '_video' )
	{
		$video = getvideobyID( $id );
		updatesinger( $video['casi'], 'numvideo', '-1' );
		updateauthor( $video['nhacsi'], 'numvideo', '-1' );
		delcomment( 'video', $video['id'] );
		unlinkSV( $video['server'], $video['duongdan'] );
		
		// Cap nhat lai chu de
		$list_cat = $video['listcat'] ? explode( ',', $video['listcat'] ) : array();
		$list_cat[] = $video['theloai'];
		$list_cat = array_filter( array_unique( $list_cat ) );
		
		foreach( $list_cat as $_cid )
		{
			UpdateVideoCat( $_cid, '-1' );
		}
	}
	elseif( $where == '_singer' )
	{
		$singer = getsingerbyID( $id );
		updatewhendelS( $singer['id'], 0 );
	}
	elseif( $where == '_author' )
	{
		$author = getauthorbyID( $id );
		updatewhendelA( $author['id'], 0 );
	}
	elseif( $where == '_ftp' )
	{
		updatewhendelFTP( $id, 0 );
	}
	elseif( $where == '' )
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
		
		// Cap nhat lai chu de
		$list_cat = $song['listcat'] ? explode( ',', $song['listcat'] ) : array();
		$list_cat[] = $song['theloai'];
		$list_cat = array_filter( array_unique( $list_cat ) );
		
		foreach( $list_cat as $_cid )
		{
			UpdateSongCat( $_cid, '-1' );
		}
	}
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	
	if( $where == '_category' )
	{
		// Cap nhat cac bai hat
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `theloai`=0 WHERE `theloai`=" . $id;
		$db->sql_query( $sql );
		
		// Sap xep lai thu tu
		$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		$weight = 0;
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$weight ++;
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
		}
	}
	elseif( $where == '_video_category' )
	{
		// Cap nhat cac video
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `theloai`=0 WHERE `theloai`=" . $id;
		$db->sql_query( $sql );
		
		// Sap xep lai thu tu
		$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		$weight = 0;
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$weight ++;
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
		}
	}
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