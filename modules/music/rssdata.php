<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

// Cac bien ho tro $mod_file, $mod_name, $site_mods

$rssarray = array();

// Goi class cua module
require( NV_ROOTDIR . "/modules/" . $mod_file . "/global.class.php" );

$classMusic = new nv_mod_music( $mod_data, $mod_name, $mod_file, NV_LANG_DATA, true );

// Gift RSS
$rssarray[1] = array(
	'catid' => 1,
	'parentid' => 0,
	'title' => $classMusic->lang('rss_gift'),
	'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_gift') ) )
);

// Playlist RSS
$rssarray[2] = array(
	'catid' => 2,
	'parentid' => 0,
	'title' => $classMusic->lang('rss_play_list'),
	'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_play_list') ) )
);

// Music RSS
$rssarray[3] = array(
	'catid' => 3,
	'parentid' => 0,
	'title' => $classMusic->lang('rss_music'),
	'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_music') ) )
);

$list = $classMusic->get_category();
unset( $list[0] );

foreach( $list as $row )
{
	$next_key = sizeof( $rssarray ) + 1;
	$rssarray[$next_key] = array(
		'catid' => $next_key,
		'parentid' => 3,
		'title' => $row['title'],
		'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_music') ) . "/" . change_alias( $row['title'] ) )
	);
}

// Video RSS
$video_key = sizeof( $rssarray ) + 1;

$rssarray[$video_key] = array(
	'catid' => $video_key,
	'parentid' => 0,
	'title' => $classMusic->lang('rss_video'),
	'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_video') ) )
);

$list = $classMusic->get_videocategory();
unset( $list[0] );

foreach( $list as $row )
{
	$next_key = sizeof( $rssarray ) + 1;
	$rssarray[$next_key] = array(
		'catid' => $next_key,
		'parentid' => $video_key,
		'title' => $row['title'],
		'link' => $classMusic->getLink( 3, "rss/" . change_alias( $classMusic->lang('rss_video') ) . "/" . change_alias( $row['title'] ) )
	);
}

unset( $classMusic );

?>