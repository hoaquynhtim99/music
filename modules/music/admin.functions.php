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
$submenu['fourcategory'] = $lang_module['sub_fourcategory']; 
$submenu['commentsong'] = $lang_module['sub_commentsong']; 
$submenu['commentalbum'] = $lang_module['sub_commentalbum']; 
$submenu['maincategory'] = $lang_module['sub_maincategory']; 
$submenu['mainalbum'] = $lang_module['sub_mainalbum']; 
$submenu['gift'] = $lang_module['sub_gift']; 
$submenu['blockhotsinger'] = $lang_module['sub_blockhotsinger']; 
$submenu['lyric'] = $lang_module['sub_lyric']; 
$submenu['userplaylist'] = $lang_module['userplaylist']; 
$submenu['setting'] = $lang_module['music_setting']; 

$allow_func = array('main', 'addsong', 'category', 'del', 'delall', 'album', 'addalbum', 'alias', 'hotalbum', 'addhotalbum', 'fourcategory', 'commentsong', 'commentalbum', 'maincategory', 'mainalbum', 'sort', 'sortmainalbum', 'ads', 'delads', 'error', 'blockhotsinger', 'gift', 'lyric', 'setting', 'active', 'editcomment', 'editlyric', 'getsonginfo', 'getsonginfolist', 'editgift', 'userplaylist', 'editplaylist', 'video_category', 'addvideo', 'videoclip', 'checklink', 'checksonglist', 'delsr', 'delallsr', 'singer', 'addsinger', 'addlistsong'); 
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

// lay tat ca album
function getallalbum( )
{
	global $module_data, $lang_module, $db ;

	$allalbum = array() ;
	$result = $db->sql_query( " SELECT `name`, `tname` FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ORDER BY name ASC" );
	$allalbum['na'] = $lang_module['unknow'];
	while ( $row = $db->sql_fetchrow($result) )
	{
		$allalbum[$row['name']] = $row['tname'];
	}
	return $allalbum ;
}

// cap nhat ca si
function updatesinger( $name, $what, $action )
{
	global $module_data, $db ;	
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET " . $what . " = " . $what . $action . " WHERE `ten` = '" . $name . "'" );
	return ;
}

// cap nhat album
function updatealbum( $name, $action )
{
	global $module_data, $db ;	
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET numsong = numsong" . $action . " WHERE `name` = '" . $name . "'" );
	return ;
}

// xoa cac binh luan
function delcomment( $delwwhat, $where )
{
	global $module_data, $db ;	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $delwwhat ."` WHERE `what`=" . $where;
    $result = $db->sql_query( $sql );
	return ;
}

// xoa cac loi bai hat
function dellyric( $songid )
{
	global $module_data, $db ;	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $songid;
    $result = $db->sql_query( $sql );
	return ;
}

// xoa cac bao loi
function delerror( $where, $key )
{
	global $module_data, $db ;	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `where`= '" . $where . "' AND `key` = " . $key;
    $result = $db->sql_query( $sql );
	return ;
}

// xoa cac qua tang am nhac
function delgift( $songid )
{
	global $module_data, $db ;	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` WHERE `songid` =" . $songid;
    $result = $db->sql_query( $sql );
	return ;
}

// Them moi mot ca si
function newsinger( $name, $tname )
{
	$error = '';
	global $module_data, $lang_module, $db ;	
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` ( `id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numalbum`) VALUES ( NULL, " . $db->dbescape( $name ) . ", " . $db->dbescape( $tname ) . ", '', '', 0, 0 )"; 
	if ( $db->sql_query_insert_id( $query ) ) 
	{ 
		$db->sql_freeresult();
	} 
	else 
	{ 
		$error = $lang_module['singer_new_added']; 
	} 
	return $error;
}

require_once NV_ROOTDIR . "/modules/" . $module_name . '/fuc_gobal.php';

?>