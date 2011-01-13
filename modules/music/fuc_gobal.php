<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/12/2010 09:47
 */
 
// lay url
function get_URL()
{
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE ;
// lay thong tin the loai
function get_category()
{
	global $module_data, $db ;
	$category = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category " );
	while($rs = $db->sql_fetchrow($result))
	{
		$category[ $rs['id'] ] = $rs[ 'title' ] ;
	}
	return $category ;
}
// lay thong tin the loai video
function get_videocategory()
{
	global $module_data, $db ;
	$category = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video_category " );
	while($rs = $db->sql_fetchrow($result))
	{
		$category[ $rs['id'] ] = $rs[ 'title' ] ;
	}
	return $category ;
}

// cau hinh module
function setting_music()
{
	global $module_data, $db ;
	$setting = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_setting " );
	while($rs = $db->sql_fetchrow($result))
	{
		if( $rs['key'] == "root_contain" )
			$setting[ $rs['key'] ] = $rs[ 'char' ] ;
		else
			$setting[ $rs['key'] ] = $rs[ 'value' ] ;
	}
	return $setting ;
}

// lay album tu id
function getalbumbyID( $id )
{
	global $module_data, $db ;

	$album = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id = " . $id );
	$album = $db->sql_fetchrow($result);

	return $album ;
}

// lay video tu id
function getvideobyID( $id )
{
	global $module_data, $db ;

	$video = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id = " . $id );
	$video = $db->sql_fetchrow($result);

	return $video ;
}

// lay song tu id
function getsongbyID( $id )
{
	global $module_data, $db ;

	$song = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id );
	$song = $db->sql_fetchrow($result);

	return $song ;
}

// lay album tu ten
function getalbumbyNAME( $name )
{
	global $module_data, $db ;

	$album = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE name =\"" . $name ."\"");
	$album = $db->sql_fetchrow($result);

	return $album ;
}

// lay tat ca ca si
function getallsinger()
{
	global $module_data, $db, $lang_module ;

	$allsinger = array() ;
	$allsinger['ns'] = $lang_module['unknow'];
	$result = $db->sql_query( " SELECT `ten`, `tenthat` FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer ORDER BY ten ASC");
	while ( $singer = $db->sql_fetchrow($result) )
	{
		$allsinger[$singer['ten']] = $singer['tenthat'];
	}

	return $allsinger ;
}

// lay ca si tu id
function getsingerbyID( $id )
{
	global $module_data, $db;

	$singer = array() ;
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id=" . $id);
	$singer = $db->sql_fetchrow($result);

	return $singer ;
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

?>