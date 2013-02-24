<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$where = isset( $array_op[1] ) ? $array_op[1] : 0;
$id = isset( $array_op[2] ) ? intval( $array_op[2] ) : 0;
$name = isset( $array_op[3] ) ? $array_op[3] : 0;

if( empty( $id ) or empty( $name ) )
{
	module_info_die();
}

$globaldata = array();

if( $where == 'song' )
{
	$song = getsongbyID( $id );
	if( $db->unfixdb( $song['ten'] ) != $name )
	{
		module_info_die();
	}

	$song['duongdan'] = outputURL( $song['server'], $song['duongdan'] );
	if( $song['server'] == 1 )
	{
		$song['duongdan'] = NV_MY_DOMAIN . $song['duongdan'];
	}

	$song['casi'] = $song['casi'];
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `numview` = numview+1 WHERE `id` =" . $id );
	$globaldata[] = $song;
}
elseif( $where == 'video' )
{
	$song = getvideobyID( $id );
	if( $db->unfixdb( $song['name'] ) != $name )
	{
		module_info_die();
	}

	$song['duongdan'] = outputURL( $song['server'], $song['duongdan'] );
	if( $song['server'] == 1 )
	{
		$song['duongdan'] = NV_MY_DOMAIN . $song['duongdan'];
	}
	
	$song['casi'] = $song['casi'];
	$song['tenthat'] = $song['tname'];
	
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET view = view+1 WHERE `id` =" . $id );
	$globaldata[] = $song;
}
elseif( $where == 'album' )
{
	$albumdata = getalbumbyID( $id );
	if( $db->unfixdb( $albumdata['name'] ) != $name )
	{
		module_info_die();
	}

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numview` = numview+1 WHERE `id` =" . $id );
	$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `id` IN(" . $albumdata['listsong'] . ") AND `active` = 1 ORDER BY id DESC";
	$querysong = $db->sql_query( $sqlsong );
	while( $song = $db->sql_fetchrow( $querysong ) )
	{
		$song['duongdan'] = outputURL( $song['server'], $song['duongdan'] );
		if( $song['server'] == 1 )
		{
			$song['duongdan'] = NV_MY_DOMAIN . $song['duongdan'];
		}

		$song['casi'] = $song['casi'];
		$globaldata[] = $song;
	}
}
elseif( $where == 'playlist' )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active`=1 AND `id` = " . $id;
	$result = $db->sql_query( $sql );
	$check_exit = $db->sql_numrows( $result );
	$row = $db->sql_fetchrow( $result );

	if( $check_exit != 1 or $db->unfixdb( $row['keyname'] ) != $name )
	{
		module_info_die();
	}

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `view` = view+1 WHERE `id` =" . $id );

	$listsong_id = explode( "/", $row['songdata'] );
	$listsong_id = array_filter( $listsong_id );
	$listsong_id = implode( ",", $listsong_id );

	$sql = "SELECT `tenthat`, `casi`, `server`, `duongdan` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN (" . $listsong_id . ") AND `active`=1";
	$result = $db->sql_query( $sql );

	while( list( $tenthat, $casi, $server, $duongdan ) = $db->sql_fetchrow( $result ) )
	{
		$duongdan = outputURL( $server, $duongdan );
		if( $server == 1 )
		{
			$duongdan = NV_MY_DOMAIN . $duongdan;
		}

		$globaldata[] = array(
			"duongdan" => $duongdan, //
			"casi" => $casi, //
			"tenthat" => $tenthat //
		);
	}
}
else
{
	die( 'Stop!!!' );
}

header( "Content-Type:text/xml" );

$logo_img = $where == "video" ? $global_config['site_url'] . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/share-logo-video.jpg' : $global_config['site_url'] . '/themes/' . $module_info['template'] . '/images/' . $module_file . '/share-logo-song.jpg';

echo "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">";
echo "	<channel>";
echo "		<title>Example playlist</title>";

foreach( $globaldata as $song )
{
	$song['duongdan'] = str_replace( '&amp;', '&', $song['duongdan'] );
	$song['duongdan'] = str_replace( '&', '&amp;', $song['duongdan'] );

	echo "		<item>";
	echo "			<title>" . $song['tenthat'] . "</title>";
	echo "			<link>" . $global_config['site_url'] . "</link>";
	echo "			<description>None</description>";
	echo "			<pubDate>None</pubDate>";
	echo "			<media:content url=\"" . $song['duongdan'] . "\" />";
	echo "			<media:thumbnail url=\"" . $logo_img . "\" />";
	echo "		</item>";
}

echo "	</channel>";
echo "</rss>";

?>