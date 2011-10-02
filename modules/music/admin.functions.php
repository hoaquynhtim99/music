<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011
 * @createdate 26/01/2011 10:08 AM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' ); 

$submenu['addsong'] = $lang_module['sub_addsong']; 
$submenu['getnhaccuatui'] = $lang_module['nhaccuatui_get']; 
$submenu['getzing'] = $lang_module['zing_get']; 
$submenu['getnhacvui'] = $lang_module['nhacvui_get']; 
$submenu['getnhacso'] = $lang_module['nhacso_get']; 
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

$allow_func = array('main', 'addsong', 'category', 'del', 'delall', 'album', 'addalbum', 'alias', 'hotalbum', 'addhotalbum', 'fourcategory', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'sort', 'sortmainalbum', 'ads', 'delads', 'error', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'editlyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'addvideo', 'videoclip', 'checklink', 'checksonglist', 'delsr', 'delallsr', 'singer', 'addsinger', 'commentvideo', 'comment', 'globalsetting', 'author', 'addauthor', 'listactive', 'ftpsetting', 'getnhaccuatui', 'getzing', 'getnhacvui', 'findsongtoalbum', 'getalbumid', 'getnhacso', 'findasongtoalbum');

define( 'NV_IS_MUSIC_ADMIN', true );

// Sap xep
function changeorder( $old, $new, $wherechange )
{
	global $module_data ;
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_".$wherechange."`
	SET `order` = " . $new . "
	WHERE `order` = " . $old . ";";
	return mysql_query($sql);
}

// Hien thi cac trang
function new_page_admin ( $ts, $now_page, $link )
{
	$page = '' ;
	if( $ts > 1 )
	{
		$page = "<div id=\"numpage\"><p>";
		if( $ts > 5 && $now_page > 3 )
		{
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=1\" class=\"next\">&lt;&lt;</a> ... ";
		}
		if( $now_page > 1 )
		{
			$now_page_min = $now_page -1 ;
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=".$now_page_min."\" class=\"next\">&lt;</a> ";
		}
		if( $ts <= 5 )
		{
			$i=1;
			while( $i <= $ts )
			{
				if($i==$now_page){$page .= "<b> ".$i." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "&now_page=".$i."\">".$i."</a> ";
				}
				$i++;	
			}
		}
		elseif( $now_page <= 2 )
		{
			$i=1;
			while($i <= 5){
			if($now_page==$i){$page .= "<b> ".$i." </b>";}
			else
			{
				$page .= "<a href=\"" . $link . "";
				$page .= "&now_page=".$i."\">".$i."</a> ";
			}
			$i++;	
			}
		}
		else if($now_page < ($ts - 2))
		{
			$i = 1;
			$j = $now_page -2;
			while($i<=5)
			{
				if($now_page==$j){$page .= "<b> ".$j." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "&now_page=".$j."\">".$j."</a> ";
				}
				$i++;
				$j++;
			}
		}
		else
		{
			$i=1;
			$j= $ts-4;
			while($i<=5)
			{
				if($now_page==$j){$page .= "<b> ".$j." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "&now_page=".$j."\">".$j."</a> ";
				}
				$i++;
				$j++;
			}
		}
		if($now_page<$ts)
		{
			$now_page_max = $now_page +1 ;
			$page .= " <a href=\"" . $link . "";
			$page .= "&now_page=".$now_page_max."\" class=\"next\">&gt;</a>";
		}
		if(($ts > 5)&&($now_page<($ts -2)))
		{
			$page .= " ... <a href=\"" . $link . "";
			$page .= "&now_page=".$ts."\" class=\"next\">&gt;&gt;</a>";
		}
		$page .= "</p></div>";
	}
	return $page;
}

// Cap nhat bai hat khi xoa, sua album
function updateSwhendelA( $albumname, $newname )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album` = '" . $newname . "' WHERE `album` = '" . $albumname . "'");
	return;
}

// Cap nhat bai hat, album ,video khi xoa, sua ca si
function updatewhendelS( $singername, $newname )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	return;
}
// Cap nhat bai hat, album ,video khi xoa, sua nhac si
function updatewhendelA( $singername, $newname )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `nhacsi` = '" . $newname . "' WHERE `nhacsi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `nhacsi` = '" . $newname . "' WHERE `nhacsi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `nhacsi` = '" . $newname . "' WHERE `nhacsi` = '" . $singername . "'");
	return;
}
// Lay nhac si tu id
function getauthorbyID( $id )
{
	global $module_data, $db;

	$author = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_author WHERE id=" . $id);
	$author = $db->sql_fetchrow($result);

	return $author ;
}
// Cap nhat bai hat, video khi xoa, sua host nhac
function updatewhendelFTP( $server, $active )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `active` = " . $active . " WHERE `server` = " . $server );
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `active` = " . $active . " WHERE `server` = " . $server );
	return;
}

// Xuat duong dan nguoc lai
function admin_outputURL ( $server, $inputurl )
{
	global $module_name, $setting;
	$output = "";
	if( $server == 0 )
	{
		$output = $inputurl;
	}
	elseif( $server == 1 )
	{
		$output = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $inputurl;
	}
	else
	{
		$ftpdata = getFTP();
		foreach ( $ftpdata as $id => $data )
		{
			if ( $id == $server )
			{
				$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
				break;
			}
		}
	}
	return $output;
}

require_once NV_ROOTDIR . "/modules/" . $module_name . '/global.functions.php';

?>