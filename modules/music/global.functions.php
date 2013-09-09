<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );


// Lay thong tin the loai video
function get_videocategory()
{
	global $module_data, $db, $lang_module;

	$category = array();

	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` ORDER BY `weight` ASC";
	$result = nv_db_cache( $sql, 'id' );
	
	$category[0] = array(
		'id' => 0,
		'title' => $lang_module['unknow'],
		'keywords' => '',
		'description' => ''
	);

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$category[$row['id']] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'keywords' => $row['keywords'],
				'description' => $row['description']
			);
		}
	}

	return $category;
}

// Lay album tu id
function getalbumbyID( $id )
{
	global $module_data, $db;

	$album = array();
	$result = $db->sql_query( " SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id );
	$album = $db->sql_fetchrow( $result );

	return $album;
}

// Lay video tu id
function getvideobyID( $id )
{
	global $module_data, $db;

	$video = array();
	$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`=" . $id );
	$video = $db->sql_fetchrow( $result );

	return $video;
}

// Lay song tu id
function getsongbyID( $id )
{
	global $module_data, $db;

	$song = array();
	$result = $db->sql_query( " SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id );
	$song = $db->sql_fetchrow( $result );

	return $song;
}

// Lay album tu ten
function getalbumbyNAME( $name )
{
	global $module_data, $db;

	$album = array();
	$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `name`=" . $db->dbescape( $name ) );
	$album = $db->sql_fetchrow( $result );

	return $album;
}

// Xoa cac binh luan
function delcomment( $delwwhat, $where )
{
	global $module_data, $db;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $delwwhat . "` WHERE `what`=" . $where;
	$result = $db->sql_query( $sql );
	
	return $result;
}

// Xoa cac loi bai hat
function dellyric( $songid )
{
	global $module_data, $db;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $songid;
	$result = $db->sql_query( $sql );
	
	return $result;
}

// Xoa cac bao loi
function delerror( $where, $key )
{
	global $module_data, $db;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `where`= '" . $where . "' AND `sid`=" . $key;
	$result = $db->sql_query( $sql );
	
	return $result;
}

// Xoa cac qua tang am nhac
function delgift( $songid )
{
	global $module_data, $db;
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` WHERE `songid` =" . $songid;
	$result = $db->sql_query( $sql );
	
	return;
}

// Xuat duong dan day du
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
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
						$cache = "";
						
						if( preg_match( "/\[FLASH\](.*?)\[\/FLASH\]/i", $output, $m ) )
						{
							$output = get_headers( $m[1] );
							
							foreach( $output as $tmp )
							{
								if( preg_match( "/^Location: (.*)/is", $tmp, $m ) )
								{
									if( preg_match( "/file\=(.*)\&ads\=/is", $tmp, $m ) )
									{
										$output = simplexml_load_string( nv_get_URL_content( $m[1] ) );
										$output = trim( ( string ) $output->track->location );
										break;
									}
								}
							}
						}

						$cache = serialize( $cache );
						nv_set_cache( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "zing" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
						
						unset( $m );
						$pattern = "/\'playlistfile\'\: \'(.*?)\'\,/i";
						if( ! empty( $output ) and preg_match( $pattern, $output, $m ) )
						{
							$output = nv_get_URL_content( "http://hcm.nhac.vui.vn" . trim( $m[1] ) );
							unset( $m );
							$pattern = "/\<jwplayer\:file\>\<\!\[CDATA\[(.*?)\]\]\>\<\/jwplayer\:file\>/i";
							if( ! empty( $output ) and preg_match( $pattern, $output, $m ) )
							{
								$output = trim( $m[1] );
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
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
				elseif( $data['host'] == "zingclip" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_zingclip_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
						
						unset( $m );
						if( ! preg_match( "/\<input type\=\"hidden\" id\=\"\_strAuto\" value\=\"([^\"]+)\"[^\/]+\/\>/is", $output, $m ) )
						{
							$output = "";
						}
						else
						{
							$output = nv_get_URL_content( $m[1] );
							if( ( $xml = simplexml_load_string( $output ) ) == false ) return "";
							$output = ( string )$xml->item->f480;
						}

						$cache = serialize( $output );
						nv_set_cache( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nctclip" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_nctclip_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

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
						
						if( ! preg_match( "/\<input id\=\"urlEmbedBlog\" type\=\"text\" readonly\=\"readonly\" value\=\"\[FLASH\](.*?)\[\/FLASH\]\" class\=\"link3\" \/\>/is", $output, $m ) )
						{
							$output = "";
						}
						else
						{
							$tmp = get_headers( $m[1] );
							$output = "";
							foreach( $tmp as $_tmp )
							{
								if( preg_match( "/file\=(.*?)\&autostart\=/is", $_tmp, $m ) )
								{
									$output = nv_get_URL_content( $m[1] );
									if( ( $xml = simplexml_load_string( $output ) ) == false ) return "";
									$output = trim( ( string ) $xml->track->location );
								}
							}
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

function nv_get_URL_content( $target_url )
{
	global $global_config;
	
	require_once( NV_ROOTDIR . "/includes/class/geturl.class.php" );
	
	$UrlGetContents = new UrlGetContents( $global_config );
	return $UrlGetContents->get( $target_url );
}

?>