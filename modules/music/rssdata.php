<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$rssarray = array();

// Get lang
$path_lang_ini = NV_ROOTDIR . "/modules/" . $mod_file . "/language/rss.ini";
$xml = simplexml_load_file( $path_lang_ini );

if( $xml !== false )
{
	$xmllanguage_tmp = $xml->xpath( 'language' );
	$language_tmp = ( array )$xmllanguage_tmp[0];
	$lang_rss = ( array )$language_tmp[NV_LANG_INTERFACE];

	// Gift RSS
	$rssarray[1] = array(
		'catid' => 1,
		'parentid' => 0,
		'title' => $lang_rss['rss_gift'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_gift'] ) //
			);

	// Playlist RSS
	$rssarray[2] = array(
		'catid' => 2,
		'parentid' => 0,
		'title' => $lang_rss['rss_play_list'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_play_list'] ) //
			);

	// Music RSS
	$result2 = $db->sql_query( "SELECT `id`, `title` FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_category` ORDER BY `title`" );
	$num_this_rss = $db->sql_numrows( $result2 );

	$rssarray[3] = array(
		'catid' => 3,
		'parentid' => 0,
		'title' => $lang_rss['rss_music'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_music'] ) //
			);

	while( list( $catid, $title ) = $db->sql_fetchrow( $result2 ) )
	{
		$next_key = count( $rssarray ) + 1;
		$rssarray[$next_key] = array(
			'catid' => $next_key,
			'parentid' => 3,
			'title' => $title,
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_music'] ) . "/" . change_alias( $title ) //
				);
	}

	// Video RSS
	$result2 = $db->sql_query( "SELECT `id`, `title` FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_video_category` ORDER BY `title`" );
	$num_this_rss = $db->sql_numrows( $result2 );

	$video_key = count( $rssarray ) + 1;

	$rssarray[$video_key] = array(
		'catid' => $video_key,
		'parentid' => 0,
		'title' => $lang_rss['rss_video'],
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_video'] ) //
			);

	while( list( $catid, $title ) = $db->sql_fetchrow( $result2 ) )
	{
		$next_key = count( $rssarray ) + 1;
		$rssarray[$next_key] = array(
			'catid' => $next_key,
			'parentid' => $video_key,
			'title' => $title,
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . change_alias( $lang_rss['rss_video'] ) . "/" . change_alias( $title ) //
				);
	}
}

?>