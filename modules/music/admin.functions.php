<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/12/2010 09:47
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' ); 

$submenu['addsong'] = $lang_module['sub_addsong']; 
$submenu['addlistsong'] = $lang_module['sub_addlistsong']; 
$submenu['error'] = $lang_module['sub_error']; 
$submenu['ads'] = $lang_module['sub_ads']; 
$submenu['hotalbum'] = $lang_module['sub_hotalbum']; 
$submenu['category'] = $lang_module['sub_category']; 
$submenu['video_category'] = $lang_module['sub_videocategory']; 
$submenu['album'] = $lang_module['sub_album']; 
$submenu['addalbum'] = $lang_module['sub_add_album']; 
$submenu['singer'] = $lang_module['sub_singer']; 
$submenu['addsinger'] = $lang_module['singer_add']; 
$submenu['videoclip'] = $lang_module['video']; 
$submenu['addvideo'] = $lang_module['video_add']; 
$submenu['addlistvideo'] = $lang_module['video_listadd']; 
$submenu['fourcategory'] = $lang_module['sub_fourcategory']; 
$submenu['commentalbum'] = $lang_module['sub_commentalbum']; 
$submenu['commentsong'] = $lang_module['sub_commentsong']; 
$submenu['commentvideo'] = $lang_module['sub_commentvideo']; 
$submenu['maincategory'] = $lang_module['sub_maincategory']; 
$submenu['mainalbum'] = $lang_module['sub_mainalbum']; 
$submenu['gift'] = $lang_module['sub_gift']; 
$submenu['blockhotsinger'] = $lang_module['sub_blockhotsinger']; 
$submenu['lyric'] = $lang_module['sub_lyric']; 
$submenu['userplaylist'] = $lang_module['userplaylist']; 
$submenu['setting'] = $lang_module['music_setting']; 

$allow_func = array('main', 'addsong', 'category', 'del', 'delall', 'album', 'addalbum', 'alias', 'hotalbum', 'addhotalbum', 'fourcategory', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'sort', 'sortmainalbum', 'ads', 'delads', 'error', 'blockhotsinger', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'editlyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'addvideo', 'videoclip', 'checklink', 'checksonglist', 'delsr', 'delallsr', 'singer', 'addsinger', 'addlistsong', 'commentvideo', 'addlistvideo'); 
define( 'NV_IS_MUSIC_ADMIN', true );

// sap xep
function changeorder($old, $new, $wherechange)
{
	global $module_data ;
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_".$wherechange."`
	SET `order` = " . $new . "
	WHERE `order` = " . $old . ";";
	return mysql_query($sql);
}
// hien thi cac trang
function new_page_admin ( $ts, $now_page, $link)
{
	$page = '' ;
	if($ts>1)
	{
		$page = "<div id=\"numpage\"><p>";
		if( $ts>5 && $now_page>3 )
		{
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=1\" class=\"next\">&lt;&lt;</a> ... ";
		}
		if($now_page > 1)
		{
			$now_page_min = $now_page -1 ;
			$page .= "<a href=\"" . $link . "";
			$page .= "&now_page=".$now_page_min."\" class=\"next\">&lt;</a> ";
		}
		if($ts<=5)
		{
			$i=1;
			while($i <= $ts)
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
		else if($now_page<=2)
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

// cap nhat bai hat khi xoa, sua album
function updateSwhendelA( $albumname, $newname )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album` = '" . $newname . "' WHERE `album` = '" . $albumname . "'");
	return;
}

// cap nhat bai hat, album ,video khi xoa, sua ca si
function updatewhendelS( $singername, $newname )
{
	global $module_data, $db ;

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `casi` = '" . $newname . "' WHERE `casi` = '" . $singername . "'");
	return;
}

require_once NV_ROOTDIR . "/modules/" . $module_name . '/fuc_gobal.php';

?>