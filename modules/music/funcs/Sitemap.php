<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$type = isset( $array_op[1] ) ? $array_op[1] : "";

if( $type == "album" )
{
	$url = array();
	$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_data . "_album_Sitemap.cache";
	$pa = NV_CURRENTTIME - 7200;

	if( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
	{
		$url = unserialize( $cache );
	}
	else
	{

		$sql = "SELECT `id`, `name`, `addtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `active`=1 ORDER BY `addtime` DESC LIMIT 1000";
		$result = $db->sql_query( $sql );
		$url = array();

		while( list( $id, $ten, $dt ) = $db->sql_fetchrow( $result ) )
		{
			$url[] = array( //
				'link' => $mainURL . "=listenlist" . '/' . $id . '/' . $ten, //
				'publtime' => $dt //
			);
		}

		$cache = serialize( $url );
		nv_set_cache( $cacheFile, $cache );
	}

	nv_xmlSitemap_generate( $url );
	die();
}
elseif( $type == "video" )
{
	$url = array();
	$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_data . "_video_Sitemap.cache";
	$pa = NV_CURRENTTIME - 7200;

	if( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
	{
		$url = unserialize( $cache );
	}
	else
	{
		$sql = "SELECT `id`, `name`, `dt` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `active`=1 ORDER BY `dt` DESC LIMIT 1000";
		$result = $db->sql_query( $sql );
		$url = array();

		while( list( $id, $ten, $dt ) = $db->sql_fetchrow( $result ) )
		{
			$url[] = array( //
				'link' => $mainURL . "=viewvideo" . '/' . $id . '/' . $ten, //
				'publtime' => $dt //
			);
		}

		$cache = serialize( $url );
		nv_set_cache( $cacheFile, $cache );
	}

	nv_xmlSitemap_generate( $url );
	die();
}

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_data . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
{
	$url = unserialize( $cache );
}
else
{
	$sql = "SELECT `id`, `ten`, `dt` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `active`=1 ORDER BY `dt` DESC LIMIT 1000";
	$result = $db->sql_query( $sql );
	$url = array();

	while( list( $id, $ten, $dt ) = $db->sql_fetchrow( $result ) )
	{
		$url[] = array( //
			'link' => $mainURL . "=listenone" . '/' . $id . '/' . $ten, //
			'publtime' => $dt //
		);
	}

	$cache = serialize( $url );
	nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();

?>