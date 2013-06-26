<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = ! empty( $module_info['description'] ) ? $module_info['description'] : $global_config['site_description'];

// Global data
$category = get_category();
$video_category = get_videocategory();

// Get lang
$path_lang_ini = NV_ROOTDIR . "/modules/" . $module_file . "/language/rss.ini";
$xml = simplexml_load_file( $path_lang_ini );

if( $xml !== false and $module_info['rss'] )
{
	$xmllanguage_tmp = $xml->xpath( 'language' );
	$language_tmp = ( array )$xmllanguage_tmp[0];
	$lang_rss = ( array )$language_tmp[NV_LANG_INTERFACE];

	if( isset( $array_op[1] ) )
	{
		$type = $array_op[1];

		if( $type == change_alias( $lang_rss['rss_gift'] ) )
		{
			$sql = "SELECT a.who_send AS who_send, a.who_receive AS who_receive, a.time AS time, a.body AS body, b.id AS songid, b.ten AS song_alias, b.tenthat AS song_title, b.casi AS casi, c.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.songid=b.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS c ON b.casi=c.id WHERE a.active=1 ORDER BY a.id DESC LIMIT 30";

			if( ( $result = $db->sql_query( $sql ) ) !== false )
			{
				while( list( $who_send, $who_receive, $time, $body, $songid, $song_alias, $song_title, $casi, $singername ) = $db->sql_fetchrow( $result ) )
				{
					$items[] = array( //
						'title' => $song_title . " - " . ( $singername ? $singername : $lang_module['unknow'] ), //
						'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=listenone/" . $songid . "/" . $song_alias, //
						'guid' => $module_name . '_' . $songid, //
						'description' => $body . "<strong><br />" . $who_send . " - " . $who_receive . "</strong>", //
						'pubdate' => $time //
					);
				}
			}
		}
		elseif( $type == change_alias( $lang_rss['rss_play_list'] ) )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active`=1 ORDER BY `time` DESC LIMIT 30";

			if( ( $result = $db->sql_query( $sql ) ) !== false )
			{
				while( $row = $db->sql_fetchrow( $result ) )
				{
					$rimages = "<img src=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg\" width=\"100\" align=\"left\" border=\"0\">";

					$items[] = array( //
						'title' => $row['name'] . " - " . $row['singer'] . " | " . $row['username'], //
						'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'], //
						'guid' => $module_name . '_' . $row['id'], //
						'description' => $rimages . $row['message'], //
						'pubdate' => $row['time'] //
					);
				}
			}
		}
		elseif( $type == change_alias( $lang_rss['rss_music'] ) )
		{
			$cat_in = ( isset( $array_op[2] ) ) ? $array_op[2] : "";

			$sql_cat = "";
			if( ! empty( $cat_in ) )
			{
				foreach( $category as $catid => $cat )
				{
					if( change_alias( $cat['title'] ) == $cat_in )
					{
						$sql_cat .= " AND a.theloai=" . $catid;
						break;
					}
				}
			}

			$sql = "SELECT a.id AS id, a.ten AS ten, a.tenthat AS tenthat, b.tenthat AS casithat, a.dt AS add_time FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten WHERE a.active=1" . $sql_cat . " ORDER BY a.id DESC LIMIT 30";

			if( ( $result = $db->sql_query( $sql ) ) !== false )
			{
				while( list( $id, $ten, $tenthat, $casithat, $add_time ) = $db->sql_fetchrow( $result ) )
				{
					$items[] = array( //
						'title' => $tenthat . " - " . ( $casithat ? $casithat : $lang_module['unknow'] ), //
						'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=listenone/" . $id . "/" . $ten, //
						'guid' => $module_name . '_' . $id, //
						'description' => $tenthat . " - " . ( $casithat ? $casithat : $lang_module['unknow'] ), //
						'pubdate' => $add_time //
					);
				}
			}
		}
		elseif( $type == change_alias( $lang_rss['rss_video'] ) )
		{
			$cat_in = ( isset( $array_op[2] ) ) ? $array_op[2] : "";

			$sql_cat = "";
			if( ! empty( $cat_in ) )
			{
				foreach( $video_category as $catid => $cat )
				{
					if( change_alias( $cat ) == $cat_in )
					{
						$sql_cat .= " AND a.theloai=" . $catid;
						break;
					}
				}
			}

			$sql = "SELECT a.id AS id, a.name AS ten, a.tname AS tenthat, b.tenthat AS casithat, a.dt AS add_time, a.thumb AS thumb FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten WHERE a.active=1" . $sql_cat . " ORDER BY a.id DESC LIMIT 30";

			if( ( $result = $db->sql_query( $sql ) ) !== false )
			{
				while( list( $id, $ten, $tenthat, $casithat, $add_time, $thumb ) = $db->sql_fetchrow( $result ) )
				{
					$rimages = "<img src=\"" . NV_MY_DOMAIN . $thumb . "\" width=\"100\" align=\"left\" border=\"0\">";

					$items[] = array( //
						'title' => $tenthat . " - " . ( $casithat ? $casithat : $lang_module['unknow'] ), //
						'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=viewvideo/" . $id . "/" . $ten, //
						'guid' => $module_name . '_' . $id, //
						'description' => $rimages, //
						'pubdate' => $add_time //
					);
				}
			}
		}
	}
	else
	{
		$sql = "SELECT a.id AS id, a.ten AS ten, a.tenthat AS tenthat, b.tenthat AS casithat, a.dt AS add_time FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten ORDER BY a.id DESC LIMIT 30";

		if( ( $result = $db->sql_query( $sql ) ) !== false )
		{
			while( list( $id, $ten, $tenthat, $casithat, $add_time ) = $db->sql_fetchrow( $result ) )
			{
				$items[] = array( //
					'title' => $tenthat . " - " . ( $casithat ? $casithat : $lang_module['unknow'] ), //
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=listenone/" . $id . "/" . $ten, //
					'guid' => $module_name . '_' . $id, //
					'description' => $tenthat . " - " . ( $casithat ? $casithat : $lang_module['unknow'] ), //
					'pubdate' => $add_time //
				);
			}
		}
	}
}

nv_rss_generate( $channel, $items );
die();

?>