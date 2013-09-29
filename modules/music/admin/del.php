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

if( empty( $id ) ) die( "Stop!!!" );

if( $id > 0 )
{
	if( $where == '_singer' )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
	}
	elseif( $where == '_author' )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
	}
	elseif( $where == '_ftp' )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		$classMusic->updatewhendelFTP( $id, 0 );
	}
	elseif( $where == '' )
	{
		$song = $classMusic->getsongbyID( $id );
		
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		
		if( $song['album'] != 0 ) $classMusic->fix_album( $song['album'] );
		$classMusic->fix_singer( $classMusic->string2array( $song['casi'] ) );
		$classMusic->fix_author( $classMusic->string2array( $song['nhacsi'] ) );
		$classMusic->delcomment( 'song', $song['id'] );
		$classMusic->dellyric( $song['id'] );
		$classMusic->delerror( 'song', $song['id'] );
		$classMusic->delgift( $song['id'] );
		$classMusic->unlinkSV( $song['server'], $song['duongdan'] );
		$classMusic->fix_cat_song( array_unique( array_filter( array_merge_recursive( $song['listcat'], array( $song['theloai'] ) ) ) ) );
	}
	
	if( $where == '_category' )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		
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
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "` WHERE `id`=" . $id;
		$result = $db->sql_query( $sql );
		
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
	echo $classMusic->lang('del_success');
}
else
{
	echo $classMusic->lang('del_error');
}

?>