<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

global $mainURL, $main_header_URL;
$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

// Lay thong tin the loai
function get_category()
{
	global $module_data, $db, $lang_module;

	$category = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category ORDER BY `weight` ASC";

	$result = nv_db_cache( $sql, 'id' );

	$category[0] = array(
		'id' => 0, //
		'title' => $lang_module['unknow'], //
		'keywords' => '', //
		'description' => '' //
	);
	
	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$category[$row['id']] = array(
				'id' => $row['id'], //
				'title' => $row['title'], //
				'keywords' => $row['keywords'], //
				'description' => $row['description'] //
			);
		}
	}
	return $category;
}

// lay thong tin the loai video
function get_videocategory()
{
	global $module_data, $db, $lang_module;

	$category = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video_category ORDER BY `weight` ASC";
	$result = nv_db_cache( $sql, 'id' );
	
	$category[0] = array(
		'id' => 0, //
		'title' => $lang_module['unknow'], //
		'keywords' => '', //
		'description' => '' //
	);

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$category[$row['id']] = array(
				'id' => $row['id'], //
				'title' => $row['title'], //
				'keywords' => $row['keywords'], //
				'description' => $row['description'] //
			);
		}
	}

	return $category;
}

// cau hinh module
function setting_music()
{
	global $module_data, $db;

	$setting = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_setting";
	$result = nv_db_cache( $sql, 'id' );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( in_array( $row['key'], array( "root_contain", "description" ) ) )
			{
				$setting[$row['key']] = $row['char'];
			}
			else
			{
				$setting[$row['key']] = $row['value'];
			}
		}
	}

	return $setting;
}

// Lay album tu id
function getalbumbyID( $id )
{
	global $module_data, $db;

	$album = array();
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id = " . $id );
	$album = $db->sql_fetchrow( $result );

	return $album;
}

// lay video tu id
function getvideobyID( $id )
{
	global $module_data, $db;

	$video = array();
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id = " . $id );
	$video = $db->sql_fetchrow( $result );

	return $video;
}

// lay song tu id
function getsongbyID( $id )
{
	global $module_data, $db;

	$song = array();
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id );
	$song = $db->sql_fetchrow( $result );

	return $song;
}

// lay album tu ten
function getalbumbyNAME( $name )
{
	global $module_data, $db;

	$album = array();
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE name =\"" . $name . "\"" );
	$album = $db->sql_fetchrow( $result );

	return $album;
}

// lay tat ca ca si
function getallsinger( $reverse = false )
{
	global $module_data, $db, $lang_module;

	$allsinger = array();

	if( $reverse === true )
	{
		$allsinger[$lang_module['unknow']] = 0;
	}
	else
	{
		$allsinger[0] = $lang_module['unknow'];
	}

	$sql = "SELECT `id`, `tenthat` FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer ORDER BY ten ASC";
	$result = nv_db_cache( $sql, 'ten' );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( $reverse === true )
			{
				$allsinger[$row['tenthat']] = $row['id'];
			}
			else
			{
				$allsinger[$row['id']] = $row['tenthat'];
			}
		}
	}

	return $allsinger;
}
// lay tat ca nhac si
function getallauthor( $reverse = false )
{
	global $module_data, $db, $lang_module;

	$allsinger = array();

	if( $reverse === true )
	{
		$allsinger[$lang_module['unknow']] = 0;
	}
	else
	{
		$allsinger[0] = $lang_module['unknow'];
	}

	$sql = "SELECT `id`, `tenthat` FROM " . NV_PREFIXLANG . "_" . $module_data . "_author ORDER BY ten ASC";
	$result = nv_db_cache( $sql, 'ten' );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( $reverse === true )
			{
				$allsinger[$row['tenthat']] = $row['id'];
			}
			else
			{
				$allsinger[$row['id']] = $row['tenthat'];
			}
		}
	}

	return $allsinger;
}
// lay ca si tu id
function getsingerbyID( $id )
{
	global $module_data, $db;

	$singer = array();
	$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id=" . $id );
	$singer = $db->sql_fetchrow( $result );

	return $singer;
}

// Them moi mot ca si
function newsinger( $name, $tname )
{
	$error = '';
	global $module_data, $lang_module, $db, $module_name;
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` ( `id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numalbum`) VALUES ( NULL, " . $db->dbescape( $name ) . ", " . $db->dbescape( $tname ) . ", '', '', 0, 0 )";

	$newid = $db->sql_query_insert_id( $sql );

	if( $newid )
	{
		$db->sql_freeresult();
		nv_del_moduleCache( $module_name );
		return $newid;
	}

	return false;
}

// Them moi mot nhac si
function newauthor( $name, $tname )
{
	global $module_data, $lang_module, $db, $module_name;
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_author` ( `id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numvideo`) VALUES ( NULL, " . $db->dbescape( $name ) . ", " . $db->dbescape( $tname ) . ", '', '', 0, 0 )";

	$newid = $db->sql_query_insert_id( $sql );

	if( $newid )
	{
		$db->sql_freeresult();
		nv_del_moduleCache( $module_name );
		return $newid;
	}

	return false;
}

// cap nhat ca si
function updatesinger( $id, $what, $action )
{
	global $module_data, $db;
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET " . $what . " = " . $what . $action . " WHERE `id` = '" . $id . "'" );
	return;
}

// cap nhat nhac si
function updateauthor( $id, $what, $action )
{
	global $module_data, $db;
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_author` SET " . $what . " = " . $what . $action . " WHERE `id` = '" . $id . "'" );
	return;
}

// cap nhat album
function updatealbum( $id, $action )
{
	global $module_data, $db;
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET numsong = numsong" . $action . " WHERE `id` = '" . $id . "'" );
	return;
}

// Cap nhat so bai hat the loai am nha
function UpdateSongCat( $id, $action )
{
	global $module_data, $db;
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET numsong = numsong" . $action . " WHERE `id`=" . $id );
	return;
}

// Cap nhat so bai hat the loai am nha
function UpdateVideoCat( $id, $action )
{
	global $module_data, $db;
	$result = $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` SET numvideo = numvideo" . $action . " WHERE `id`=" . $id );
	return;
}

// xoa cac binh luan
function delcomment( $delwwhat, $where )
{
	global $module_data, $db;
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $delwwhat . "` WHERE `what`=" . $where;
	$result = $db->sql_query( $sql );
	return;
}

// xoa cac loi bai hat
function dellyric( $songid )
{
	global $module_data, $db;
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $songid;
	$result = $db->sql_query( $sql );
	return;
}

// xoa cac bao loi
function delerror( $where, $key )
{
	global $module_data, $db;
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `where`= '" . $where . "' AND `sid`=" . $key;
	$result = $db->sql_query( $sql );
	return $result;
}

// xoa cac qua tang am nhac
function delgift( $songid )
{
	global $module_data, $db;
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` WHERE `songid` =" . $songid;
	$result = $db->sql_query( $sql );
	return;
}
// Lay thong tin ftp cua host nhac
function getFTP()
{
	global $module_data, $db, $lang_module;
	$ftpdata = array();
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` ORDER BY id ASC";
	$result = nv_db_cache( $sql, 'id' );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$ftpdata[$row['id']] = array(
				"id" => $row['id'],
				"host" => $row['host'],
				"user" => $row['user'],
				"pass" => $row['pass'],
				"fulladdress" => $row['fulladdress'],
				"subpart" => $row['subpart'],
				"ftppart" => $row['ftppart'],
				"active" => ( $row['active'] == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'] );
		}
	}
	return $ftpdata;
}
// tao duong dan tu mot chuoi
function creatURL( $inputurl )
{
	global $module_name, $setting;

	$songdata = array();
	if( preg_match( '/^(ht|f)tp:\/\//', $inputurl ) )
	{
		$ftpdata = getFTP();
		$str_inurl = str_split( $inputurl );
		$no_ftp = true;
		foreach( $ftpdata as $id => $data )
		{
			$this_host = $data['fulladdress'] . $data['subpart'];
			$str_check = str_split( $this_host );
			$check_ok = false;
			foreach( $str_check as $stt => $char )
			{
				if( $char != $str_inurl[$stt] )
				{
					$check_ok = false;
					break;
				}
				$check_ok = true;
			}
			if( $check_ok )
			{
				$lu = strlen( $this_host );
				$songdata['duongdan'] = substr( $inputurl, $lu );
				$songdata['server'] = $id;
				$no_ftp = false;
				break;
			}
		}
		if( $no_ftp )
		{
			$songdata['duongdan'] = $inputurl;
			$songdata['server'] = 0;
		}
	}
	else
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
		$songdata['duongdan'] = substr( $inputurl, $lu );
		$songdata['server'] = 1;
	}
	return $songdata;
}

// xuat duong dan day du
function outputURL( $server, $inputurl )
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
		foreach( $ftpdata as $id => $data )
		{
			if( $id == $server )
			{
				if( $data['host'] == "nhaccuatui" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $setting['del_cache_time_out'] ) and $setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = nv_get_cache( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );
						$output = explode( '&autostart=true&file=', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( '"', $output[1] );
							$output = nv_get_URL_content( $output[0] );
							$output = explode( "<location><![CDATA[", $output );
							if( isset( $output[1] ) )
							{
								$output = explode( "]]></location>", $output[1] );
								$output = $output[0];
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						nv_set_cache( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "zing" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $setting['del_cache_time_out'] ) and $setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = nv_get_cache( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );
						$output = explode( '<input type="hidden" id="_strNoAuto" value="', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( '"', $output[1] );
							$output = nv_get_URL_content( $output[0] );
							$output = explode( "<urlSource>", $output );

							if( isset( $output[1] ) )
							{
								$output = explode( "</urlSource>", $output[1] );
								$output = nv_unhtmlspecialchars( $output[0] );
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						nv_set_cache( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nhacvui" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $setting['del_cache_time_out'] ) and $setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = nv_get_cache( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						$output = explode( '[FLASH]', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( 'playlistfile=', $output[1] );

							if( isset( $output[1] ) )
							{
								$output = explode( '[/FLASH]"', $output[1] );
								$output = rawurldecode( $output[0] );
								$output = str_replace( "nhac.vui.vn", "hcm.nhac.vui.vn", $output );
								$output = nv_get_URL_content( $output );

								$output = explode( "<location><![CDATA[", $output );

								if( isset( $output[1] ) )
								{
									$output = explode( "]]></location>", $output[1] );
									$output = $output[0];
								}
								else
								{
									$output = "";
								}
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						nv_set_cache( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nhacso" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $setting['del_cache_time_out'] ) and $setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = nv_get_cache( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						$output = explode( 'embedPlaylistjs.swf?xmlPath=', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( '&amp;', $output[1] );
							$output = nv_get_URL_content( $output[0] );

							$output = explode( "<mp3link><![CDATA[", $output );

							if( isset( $output[1] ) )
							{
								$output = explode( "]]></mp3link>", $output[1] );
								$output = trim( $output[0] );
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						nv_set_cache( $cache_file, $cache );
					}
				}
				else
				{
					$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
					break;
				}
			}
		}
	}
	return $output;
}

function unlinkSV( $server, $url )
{
	global $module_name, $setting;
	if( $server == 1 )
	{
		@unlink( NV_DOCUMENT_ROOT . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $url );
	}
	elseif( $server != 0 )
	{
		$ftpdata = getFTP();

		if( ! isset( $ftpdata[$server] ) ) return;

		if( in_array( $ftpdata[$server]['host'], array(
			'nhaccuatui',
			'zing',
			'nhacvui',
			'nhacso' ) ) ) return;

		require_once ( NV_ROOTDIR . "/modules/" . $module_name . "/class/ftp.class.php" );
		$ftp = new FTP();
		if( $ftp->connect( $ftpdata[$server]['host'] ) )
		{
			if( $ftp->login( $ftpdata[$server]['user'], $ftpdata[$server]['pass'] ) )
			{
				$ftp->delete( $ftpdata[$server]['ftppart'] . $ftpdata[$server]['subpart'] . $url );
			}
			$ftp->disconnect();
		}
	}
	return;
}

//
function nv_get_URL_content( $target_url )
{
	global $client_info;

	$agent = empty( $client_info['agent'] ) ? 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0' : $client_info['agent'];

	if( function_exists( 'curl_init' ) )
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $target_url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
		$content = curl_exec( $ch );
		$errormsg = curl_error( $ch );
		curl_close( $ch );

		if( $errormsg != "" )
		{
			return "";
		}
	}

	if( ! is_array( $content ) )
	{
		$content = explode( "\n", $content );
	}

	return implode( "", $content );
}

function nvm_new_song( $array )
{
	global $module_data, $db;

	$array['ten'] = ! isset( $array['ten'] ) ? "" : $array['ten'];
	$array['tenthat'] = ! isset( $array['tenthat'] ) ? "" : $array['tenthat'];
	$array['casi'] = ! isset( $array['casi'] ) ? 0 : $array['casi'];
	$array['nhacsi'] = ! isset( $array['nhacsi'] ) ? 0 : $array['nhacsi'];
	$array['album'] = ! isset( $array['album'] ) ? 0 : $array['album'];
	$array['theloai'] = ! isset( $array['theloai'] ) ? 0 : $array['theloai'];
	$array['listcat'] = ! isset( $array['listcat'] ) ? array() : $array['listcat'];
	$array['data'] = ! isset( $array['data'] ) ? "" : $array['data'];
	$array['username'] = ! isset( $array['username'] ) ? "N/A" : $array['username'];
	$array['bitrate'] = ! isset( $array['bitrate'] ) ? "0" : $array['bitrate'];
	$array['size'] = ! isset( $array['size'] ) ? "0" : $array['size'];
	$array['duration'] = ! isset( $array['duration'] ) ? "0" : $array['duration'];
	$array['server'] = ! isset( $array['server'] ) ? "1" : $array['server'];
	$array['userid'] = ! isset( $array['userid'] ) ? "1" : $array['userid'];
	$array['hit'] = ! isset( $array['hit'] ) ? "0-" . NV_CURRENTTIME : $array['hit'];
	$array['lyric'] = ! isset( $array['lyric'] ) ? "" : $array['lyric'];

	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
		NULL, 
		" . $db->dbescape( $array['ten'] ) . ", 
		" . $db->dbescape( $array['tenthat'] ) . ", 
		" . $array['casi'] . ", 
		" . $array['nhacsi'] . ", 
		" . $array['album'] . ", 
		" . $db->dbescape( $array['theloai'] ) . ", 
		" . $db->dbescape( implode( ",", $array['listcat'] ) ) . ", 
		" . $db->dbescape( $array['data'] ) . ", 
		" . $db->dbescape( $array['username'] ) . " ,
		0,
		1,
		" . $db->dbescape( $array['bitrate'] ) . " ,
		" . $db->dbescape( $array['size'] ) . " ,
		" . $db->dbescape( $array['duration'] ) . ",
		" . $array['server'] . ",
		" . $array['userid'] . ",
		" . NV_CURRENTTIME . ",
		0,
		" . $db->dbescape( $array['hit'] ) . "
	)";

	$_songid = $db->sql_query_insert_id( $sql );

	if( $_songid )
	{
		// Cap nhat bai hat cho the loai
		// Xac dinh chu de moi
		$list_new_cat = $array['listcat'];
		$list_new_cat[] = $array['theloai'];
		$list_new_cat = array_unique( $list_new_cat );
		
		foreach( $list_new_cat as $_cid )
		{
			if( $_cid > 0 ) UpdateSongCat( $_cid, '+1' );
		}

		// Cap nhat so bai hat cua ca si, nhac si va album
		updatesinger( $array['casi'], 'numsong', '+1' );
		updateauthor( $array['nhacsi'], 'numsong', '+1' );
		updatealbum( $array['album'], '+1' );

		// Them bai hat vao danh sach nhac cua album moi
		if( ! empty( $array['album'] ) )
		{
			$sql = "SELECT `listsong` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $array['album'];
			$result = $db->sql_query( $sql );
			list( $list_song ) = $db->sql_fetchrow( $result );

			$list_song = explode( ',', $list_song );
			$list_song[] = $_songid;
			$list_song = array_unique( $list_song );
			$list_song = array_filter( $list_song );

			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `listsong`=" . $db->dbescape( implode( ',', $list_song ) ) . " WHERE `id`=" . $array['album'];
			$db->sql_query( $sql );
		}

		// Them loi bai hat moi
		if( ! empty( $array['lyric'] ) )
		{
			$array['lyric'] = nv_nl2br( $array['lyric'], "<br />" );

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` VALUES(
				NULL,
				" . $_songid . ",
				" . $db->dbescape( $array['username'] ) . ",
				" . $db->dbescape( $array['lyric'] ) . ",
				1, 
				" . NV_CURRENTTIME . "
			)";
			$db->sql_query( $sql );
		}

		$db->sql_freeresult();
	}

	return $_songid;
}

function nvm_edit_song( $array_old, $array )
{
	global $module_data, $db;

	$array_old['casi'] = ! isset( $array_old['casi'] ) ? 0 : $array_old['casi'];
	$array_old['nhacsi'] = ! isset( $array_old['nhacsi'] ) ? 0 : $array_old['nhacsi'];
	$array_old['theloai'] = ! isset( $array_old['theloai'] ) ? 0 : $array_old['theloai'];
	$array_old['lyric'] = ! isset( $array_old['lyric'] ) ? "" : $array_old['lyric'];
	$array_old['listcat'] = ! isset( $array_old['listcat'] ) ? array() : ( array ) $array_old['listcat'];

	$array['ten'] = ! isset( $array['ten'] ) ? "" : $array['ten'];
	$array['tenthat'] = ! isset( $array['tenthat'] ) ? "" : $array['tenthat'];
	$array['casi'] = ! isset( $array['casi'] ) ? 0 : $array['casi'];
	$array['nhacsi'] = ! isset( $array['nhacsi'] ) ? 0 : $array['nhacsi'];
	$array['album'] = ! isset( $array['album'] ) ? 0 : $array['album'];
	$array['theloai'] = ! isset( $array['theloai'] ) ? 0 : $array['theloai'];
	$array['listcat'] = ! isset( $array['listcat'] ) ? array() : $array['listcat'];
	$array['duongdan'] = ! isset( $array['duongdan'] ) ? "" : $array['duongdan'];
	$array['bitrate'] = ! isset( $array['bitrate'] ) ? "0" : $array['bitrate'];
	$array['size'] = ! isset( $array['size'] ) ? "0" : $array['size'];
	$array['duration'] = ! isset( $array['duration'] ) ? "0" : $array['duration'];
	$array['server'] = ! isset( $array['server'] ) ? "1" : $array['server'];
	$array['userid'] = ! isset( $array['userid'] ) ? "1" : $array['userid'];
	$array['username'] = ! isset( $array['username'] ) ? "N/A" : $array['username'];
	$array['lyric'] = ! isset( $array['lyric'] ) ? "" : $array['lyric'];
	$array['id'] = ! isset( $array['id'] ) ? "0" : $array['id'];

	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET 
		`ten`=" . $db->dbescape( $array['ten'] ) . ", 
		`tenthat`=" . $db->dbescape( $array['tenthat'] ) . ", 
		`casi`=" . $array['casi'] . ", 
		`nhacsi`=" . $array['nhacsi'] . ", 
		`album`=" . $array['album'] . ", 
		`theloai`=" . $db->dbescape( $array['theloai'] ) . ", 
		`listcat`=" . $db->dbescape( implode( ",", $array['listcat'] ) ) . ", 
		`duongdan`=" . $db->dbescape( $array['duongdan'] ) . ", 
		`bitrate`=" . $db->dbescape( $array['bitrate'] ) . " ,
		`size`=" . $db->dbescape( $array['size'] ) . " ,
		`duration`=" . $db->dbescape( $array['duration'] ) . ",
		`server`=" . $array['server'] . "
	WHERE `id`=" . $array['id'];

	$check_update = $db->sql_query( $sql );

	if( $check_update )
	{
		// Cap nhat bai hat cho the loai
		// Xac dinh cac chu de cu
		$list_old_cat = $array_old['listcat'];
		$list_old_cat[] = $array_old['theloai'];
		$list_old_cat = array_unique( $list_old_cat );
		
		// Xac dinh chu de moi
		$list_new_cat = $array['listcat'];
		$list_new_cat[] = $array['theloai'];
		$list_new_cat = array_unique( $list_new_cat );
		
		$array_mul = array_diff( $list_new_cat, $list_old_cat );
		$array_div = array_diff( $list_old_cat, $list_new_cat );
		
		foreach( $array_mul as $_cid )
		{
			if( $_cid > 0 ) UpdateSongCat( $_cid, '+1' );
		}
		
		foreach( $array_div as $_cid )
		{
			if( $_cid > 0 ) UpdateSongCat( $_cid, '-1' );
		}
		
		// Cap nhat so bai hat cua ca si
		if( $array_old['casi'] != $array['casi'] )
		{
			updatesinger( $array_old['casi'], 'numsong', '-1' );
			updatesinger( $array['casi'], 'numsong', '+1' );
		}

		// Cap nhat so bai hat cua nhac si
		if( $array_old['nhacsi'] != $array['nhacsi'] )
		{
			updateauthor( $array_old['nhacsi'], 'numsong', '-1' );
			updateauthor( $array['nhacsi'], 'numsong', '+1' );
		}

		// Cap nhat so bai hat cua album
		if( $array_old['album'] != $array['album'] )
		{
			updatealbum( $array_old['album'], '-1' );
			updatealbum( $array['album'], '+1' );

			// Them bai hat vao danh sach bai hat cua album moi
			$sql = "SELECT `listsong` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $array['album'];
			$result = $db->sql_query( $sql );
			list( $list_song ) = $db->sql_fetchrow( $result );

			$list_song = explode( ',', $list_song );
			$list_song[] = $array['id'];
			$list_song = array_unique( $list_song );
			$list_song = array_filter( $list_song );

			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `listsong`=" . $db->dbescape( implode( ',', $list_song ) ) . " WHERE `id`=" . $array['album'];
			$db->sql_query( $sql );
		}

		// Xu ly loi bai hat
		if( $array['lyric'] != $array_old['lyric'] )
		{
			$array['lyric'] = nv_nl2br( $array['lyric'], "<br />" );

			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $array['id'] . " AND `user`=" . $db->dbescape( $array['username'] );
			$result = $db->sql_query( $sql );
			list( $lyric_id ) = $db->sql_fetchrow( $result );

			// Cap nhat loi bai hat
			if( $lyric_id )
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` SET `body`=" . $db->dbescape( $array['lyric'] ) . " WHERE `id`=" . $lyric_id;
				$db->sql_query( $sql );
			}
			// Them loi bai hat
			else
			{
				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` VALUES(
					NULL,
					" . $array['id'] . ",
					" . $db->dbescape( $array['username'] ) . ",
					" . $db->dbescape( $array['lyric'] ) . ",
					1, " . NV_CURRENTTIME . "
				)";
				$db->sql_query( $sql );
			}
		}

		$db->sql_freeresult();
	}

	return $check_update;
}

?>