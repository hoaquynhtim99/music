<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if(!defined('NV_IS_MUSIC_ADMIN'))
{
	die('Stop!!!');
}
// tao gia tri
$result = false;
$id = $nv_Request->get_int('id', 'post,get');
$where = filter_text_input( 'where', 'get', '' );
$setting = setting_music();

// xoa
if($id > 0)
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
		if ( $video['server'] == 1 )
		{
			unlink( NV_DOCUMENT_ROOT . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/video/" . $video['duongdan'] );
		}
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
		if ( $song['server'] == 1 )
		{
			unlink( NV_DOCUMENT_ROOT . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'] );
		}
	}
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where ."` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
}

// tra ve gia tri
if($result)
{
	echo $lang_module['del_success'];
}
else
{
	echo $lang_module['del_error'];
}
?>