<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_MUSIC', true );

require_once NV_ROOTDIR . "/modules/" . $module_file . '/global.functions.php';
require_once NV_ROOTDIR . "/modules/" . $module_file . '/data.functions.php';

// Menu site
$nv_vertical_menu = nv_music_global_menu( $module_name, $lang_module );

// Hien thi cac trang
function new_page( $ts, $now_page, $link, $rewrite = true )
{
	$page = '';
	if( $ts > 1 )
	{
		$page = "<div id=\"numpage\"><p>";
		if( $ts > 5 && $now_page > 3 )
		{
			$page .= "<a href=\"" . $link . "";
			$page .= ( $rewrite ? "/1" : "&amp;page=1" ) . "\" class=\"next\">&lt;&lt;</a> ... ";
		}
		if( $now_page > 1 )
		{
			$now_page_min = $now_page - 1;
			$page .= "<a href=\"" . $link . "";
			$page .= ( $rewrite ? ( "/" . $now_page_min ) : ( "&amp;page=" . $now_page_min ) ) . "\" class=\"next\">&lt;</a> ";
		}
		if( $ts <= 5 )
		{
			$i = 1;
			while( $i <= $ts )
			{
				if( $i == $now_page )
				{
					$page .= "<b> " . $i . " </b>";
				}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= ( $rewrite ? ( "/" . $i ) : ( "&amp;page=" . $i ) ) . "\">" . $i . "</a> ";
				}
				$i++;
			}
		}
		else
			if( $now_page <= 2 )
			{
				$i = 1;
				while( $i <= 5 )
				{
					if( $now_page == $i )
					{
						$page .= "<b> " . $i . " </b>";
					}
					else
					{
						$page .= "<a href=\"" . $link . "";
						$page .= ( $rewrite ? ( "/" . $i ) : ( "&amp;page=" . $i ) ) . "\">" . $i . "</a> ";
					}
					$i++;
				}
			}
			else
				if( $now_page < ( $ts - 2 ) )
				{
					$i = 1;
					$j = $now_page - 2;
					while( $i <= 5 )
					{
						if( $now_page == $j )
						{
							$page .= "<b> " . $j . " </b>";
						}
						else
						{
							$page .= "<a href=\"" . $link . "";
							$page .= ( $rewrite ? ( "/" . $j ) : ( "&amp;page=" . $j ) ) . "\">" . $j . "</a> ";
						}
						$i++;
						$j++;
					}
				}
				else
				{
					$i = 1;
					$j = $ts - 4;
					while( $i <= 5 )
					{
						if( $now_page == $j )
						{
							$page .= "<b> " . $j . " </b>";
						}
						else
						{
							$page .= "<a href=\"" . $link . "";
							$page .= ( $rewrite ? ( "/" . $j ) : ( "&amp;page=" . $j ) ) . "\">" . $j . "</a> ";
						}
						$i++;
						$j++;
					}
				}
				if( $now_page < $ts )
				{
					$now_page_max = $now_page + 1;
					$page .= " <a href=\"" . $link . "";
					$page .= ( $rewrite ? ( "/" . $now_page_max ) : ( "&amp;page=" . $now_page_max ) ) . "\" class=\"next\">&gt;</a>";
				}
		if( ( $ts > 5 ) && ( $now_page < ( $ts - 2 ) ) )
		{
			$page .= " ... <a href=\"" . $link . "";
			$page .= ( $rewrite ? ( "/" . $ts ) : ( "&amp;page=" . $ts ) ) . "\" class=\"next\">&gt;&gt;</a>";
		}
		$page .= "</p></div>";
	}
	return $page;
}
// lay quang cao
function getADS()
{
	global $module_data, $global_config, $db, $module_file, $lang_module;

	$ads = array();
	$ads['link'] = array();
	$ads['url'] = array();
	$ads['name'] = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ads ORDER BY RAND()";
	$result = nv_db_cache( $sql, 'id' );

	if( ! empty( $result ) )
	{
		$i = 0;
		foreach( $result as $row )
		{
			$ads['name'][] = $row['name'];
			$ads['link'][] = $row['link'];
			$ads['url'][] = $row['url'];
			$i++;
		}

		$j = rand( 0, $i - 1 );
		$ads['name'] = $ads['name'][$j];
		$ads['link'] = $ads['link'][$j];
		$ads['url'] = $ads['url'][$j];
	}
	else
	{
		$ads['link'] = NV_BASE_SITEURL . "modules/" . $module_file . "/data/default.swf";
		$ads['url'] = $global_config['site_url'];
		$ads['name'] = $lang_module['ads'];
	}

	return $ads;
}

// Update luot nghe, HIT bai hat video, album
function updateHIT_VIEW( $id, $where, $is_numview = true )
{
	global $module_data, $db;
	( $where == "_video" ) ? ( $key = "view" ) : ( $key = "numview" );

	if( $is_numview ) $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET " . $key . " = " . $key . "+1 WHERE `id` =" . $id );

	if( $where == '' )
	{
		$data = getsongbyID( $id );
	}
	elseif( $where == '_video' )
	{
		$data = getvideobyID( $id );
	}
	else
	{
		$data = getalbumbyID( $id );
	}

	$hitdata = explode( "-", $data['hit'] );
	$hittime = $hitdata[1];
	$hitnum = $hitdata[0];
	if( ( NV_CURRENTTIME - $hittime ) > 864000 )
	{
		$hit = "0-" . NV_CURRENTTIME;
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET hit = " . $db->dbescape( $hit ) . " WHERE `id` =" . $id );
	}
	else
	{
		$newhit = $hitnum + 1;
		$hit = $newhit . "-" . $hittime;
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET hit = " . $db->dbescape( $hit ) . " WHERE `id` =" . $id );
	}
	return;
}

/**
 * module_info_die()
 * 
 * @return
 */
function module_info_die()
{
	global $lang_module;

	nv_info_die( $lang_module['err_module_title'], $lang_module['err_module_title'], $lang_module['err_module_content'] );

	return false;
}

global $downURL, $setting;
$setting = setting_music();
$downURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=down&amp;id=";

?>