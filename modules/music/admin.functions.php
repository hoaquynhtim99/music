<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['content-song'] = $lang_module['sub_addsong'];
$submenu['addFromOtherSite'] = $lang_module['addFromOtherSite_title'];
$submenu['album'] = $lang_module['sub_album'];
$submenu['addalbum'] = $lang_module['sub_add_album'];
$submenu['videoclip'] = $lang_module['video'];
$submenu['addvideo'] = $lang_module['video_add'];
$submenu['singer'] = $lang_module['sub_singer'];
$submenu['addsinger'] = $lang_module['singer_add'];
$submenu['author'] = $lang_module['sub_author'];
$submenu['addauthor'] = $lang_module['author_add'];
$submenu['error'] = $lang_module['sub_error'];
$submenu['ads'] = $lang_module['sub_ads'];
$submenu['lyric'] = $lang_module['sub_lyric'];
$submenu['gift'] = $lang_module['sub_gift'];
$submenu['comment'] = $lang_module['sub_comment'];
$submenu['userplaylist'] = $lang_module['userplaylist'];
$submenu['category'] = $lang_module['sub_category'];
$submenu['video_category'] = $lang_module['sub_videocategory'];
$submenu['globalsetting'] = $lang_module['set_global'];

$allow_func = array( 'main', 'content-song', 'category', 'del', 'delall', 'album', 'addalbum', 'alias', 'hotalbum', 'fourcategory', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'sort', 'sortmainalbum', 'ads', 'delads', 'error', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'editlyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'addvideo', 'videoclip', 'checklink', 'checksonglist', 'singer', 'addsinger', 'commentvideo', 'comment', 'globalsetting', 'author', 'addauthor', 'listactive', 'ftpsetting', 'findsongtoalbum', 'getalbumid', 'findasongtoalbum', 'ex', 'addFromOtherSite' );

define( 'NV_IS_MUSIC_ADMIN', true );

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
$classMusic = new nv_mod_music();

// Hien thi cac trang
function new_page_admin( $ts, $now_page, $link )
{
	$page = '';
	if( $ts > 1 )
	{
		$page = "<div id=\"numpage\"><p>";
		if( $ts > 5 and $now_page > 3 )
		{
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=1\" class=\"next\">&lt;&lt;</a> ... ";
		}
		if( $now_page > 1 )
		{
			$now_page_min = $now_page - 1;
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=" . $now_page_min . "\" class=\"next\">&lt;</a> ";
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
					$page .= "&now_page=" . $i . "\">" . $i . "</a> ";
				}
				$i++;
			}
		}
		elseif( $now_page <= 2 )
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
					$page .= "&now_page=" . $i . "\">" . $i . "</a> ";
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
						$page .= "&now_page=" . $j . "\">" . $j . "</a> ";
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
						$page .= "&now_page=" . $j . "\">" . $j . "</a> ";
					}
					$i++;
					$j++;
				}
			}
			if( $now_page < $ts )
			{
				$now_page_max = $now_page + 1;
				$page .= " <a href=\"" . $link . "";
				$page .= "&now_page=" . $now_page_max . "\" class=\"next\">&gt;</a>";
			}
		if( ( $ts > 5 ) and ( $now_page < ( $ts - 2 ) ) )
		{
			$page .= " ... <a href=\"" . $link . "";
			$page .= "&now_page=" . $ts . "\" class=\"next\">&gt;&gt;</a>";
		}
		$page .= "</p></div>";
	}
	return $page;
}

?>