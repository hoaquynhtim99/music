<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
	die( 'Stop!!!' );
}

// tao gia tri
$listall = $nv_Request->get_string( 'listall', 'post,get' );
$array_id = explode( ',', $listall );
$array_id = array_map( "intval", $array_id );
$result = false;
$where = filter_text_input( 'where', 'get', '' );
$setting = setting_music();

if( empty( $listall ) ) die( "Stop!!!" );

// thuc hien lenh xoa
foreach( $array_id as $id )
{
	if( $id > 0 )
	{
		if( $where == '_album' )
		{
			$album = getalbumbyID( $id );
			updatesinger( $album['casi'], 'numalbum', '-1' );
			delcomment( 'album', $album['id'] );
			delerror( 'album', $album['id'] );
			updateSwhendelA( $album['id'], 0 );
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
			$singer = getauthorbyID( $id );
			updatewhendelA( $singer['id'], 0 );
		}
		elseif( $where == '' )
		{
			$song = getsongbyID( $id );
			if( $song['album'] != 0 )
			{
				updatealbum( $song['album'], '-1' );
			}
			updatesinger( $song['casi'], 'numsong', '-1' );
			updateauthor( $song['nhacsi'], 'numsong', '-1' );
			delcomment( 'song', $song['id'] );
			dellyric( $song['id'] );
			delerror( 'song', $song['id'] );
			delgift( $song['id'] );
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
	}
}

nv_del_moduleCache( $module_name );

// tra ve gia tri
if( $result )
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}

?>