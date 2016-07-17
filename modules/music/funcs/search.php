<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $lang_module['search_song1'];
$key_words = $module_info['keywords'];

$category = get_category();

// Du lieu tim kiem
$query_search = array();
$query_search['where'] = nv_substr( $nv_Request->get_title( 'where', 'get', 'song', 1 ), 0, 255);
$query_search['id'] = $nv_Request->get_int( 'id', 'get', 0 );
$query_search['key'] = nv_substr( $nv_Request->get_title( 'q', 'get', '', 0 ), 0, NV_MAX_SEARCH_LENGTH);
$query_search['SearchBy'] = nv_substr( $nv_Request->get_title( 'type', 'get', 'name', 1 ), 0, 255);
$query_search['page'] = $nv_Request->get_int( 'page', 'get', 1 );

$base_url = $mainURL . "=search&amp;where=" . $query_search['where'] . "&amp;q=" . urlencode( $query_search['key'] ) . "&amp;id=" . $query_search['id'] . "&amp;type=" . $query_search['SearchBy'];

// Kiem tra type hop le
if( ! empty( $query_search['SearchBy'] ) and ! in_array( $query_search['SearchBy'], array( 'singer', 'name', 'author', 'upload', 'category' ) ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Tim kiem theo
if( empty( $query_search['key'] ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}
elseif( empty( $query_search['where'] ) )
{
	Header( "Location: " . $main_header_URL . "=search&amp;where=song&amp;q=" . urlencode( $query_search['key'] ) );
	exit();
}
elseif( ! in_array( $query_search['where'], array( 'song', 'album', 'video', 'playlist' ) ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Gia tri khoi tao
$array_song = array();
$array_album = array();
$array_video = array();
$array_singer = array();
$array_playlist = array();

$DB_LikeKey = $db->dblikeescape( $query_search['key'] );

// Hien thi thong tin ca si - Lay ra 10 ca si "Kha nghi nhat"
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE tenthat LIKE '%" . $DB_LikeKey . "%' AND thumb!='' AND introduction!='' ORDER BY RAND() LIMIT 10";
$list = $nv_Cache->db( $sql, 'id' );

foreach( $list as $row )
{
	$check = nv_strtolower( change_alias( $row['tenthat'] ) );
	if( $check == nv_strtolower( change_alias( $query_search['key'] ) ) )
	{
		$array_singer = $row;
		break;
	}
}

// Chuyen thanh tim ca si neu xac dinh dung ca si
if( ! empty( $array_singer ) and $query_search['where'] == 'song' )
{
	$query_search['SearchBy'] = 'singer';
	$query_search['id'] = $array_singer['id'];
}

if( $query_search['where'] == 'song' )
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.ten AS singeralias, b.tenthat AS singername, c.ten AS authoralias, c.tenthat AS authorname FROM " . NV_PREFIXLANG . "_" . $module_data . " AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_author AS c ON a.nhacsi=c.id WHERE a.active=1";
	
	if( $query_search['SearchBy'] == 'singer' ) // Tim bai hat theo ca si
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.casi=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND b.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'name' ) // Tim bai hat theo ten
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.id=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND a.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'author' ) // Tim bai hat theo nhac si
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND c.id=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND c.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'upload' ) // Tim bai hat theo nguoi dang
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.userid=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND a.upboi LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'category' ) // Tim bai hat theo the loai
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.theloai=" . $query_search['id'];
		}
		else
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}
	}
	
	if( $query_search['page'] <= 1 )
	{
		$first_page = 0;
	}
	else
	{
		$first_page = ( $query_search['page'] - 1 ) * 20;
	}
	
	$sql .= " ORDER BY a.id DESC LIMIT " . $first_page . ", 20";
	
	$result = $db->query( $sql );
	$query = $db->query( "SELECT FOUND_ROWS()" );
	$all_page = $query->fetchColumn();
	
	while( $row = $result->fetch() )
	{
		$checkhit = explode( "-", $row['hit'] );
		$checkhit = $checkhit[0];
		if( $checkhit >= 20 )
		{
			$checkhit = true;
		}
		else
		{
			$checkhit = false;
		}

		$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

		// Check HIT
		$checkhit = explode( "-", $row['hit'] );
		$checkhit = $checkhit[0];
		
		$array_song[] = array(
			"id" => $row['id'], //
			"name" => $row['tenthat'], //
			"singer" => $singername, //
			"checkhit" => $checkhit, //
			"upload" => $row['upboi'], //
			"category" => $category[$row['theloai']]['title'], //
			"view" => $row['numview'], //
			"bitrate" => $row['bitrate'], //
			"size" => $row['size'], //
			"duration" => $row['duration'], //
			"url_listen" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'], //
			"url_search_singer" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
			"url_search_category" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $category[$row['theloai']]['title'] ) . "&amp;id=" . $row['theloai'] . "&amp;type=category", //
			"url_search_upload" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $row['upboi'] ) . "&amp;id=" . $row['userid'] . "&amp;type=upload", //
			"checkhit" => $checkhit //
		);
	}
	
	if( $query_search['page'] <= 1 ) // Hien thi ket qua album va video
	{
		if( ! empty( $array_singer ) )
		{
			$sub_sql = " a.casi=" . $array_singer['id'];
		}
		else
		{
			$sub_sql = " a.tname LIKE '%" . $DB_LikeKey . "%'";
		}
	
		$sqlvideo = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM " . NV_PREFIXLANG . "_" . $module_data . "_video AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id WHERE" . $sub_sql . " AND a.active=1 ORDER BY a.id DESC LIMIT 0,3";
		$sqlalbum = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM " . NV_PREFIXLANG . "_" . $module_data . "_album AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id WHERE" . $sub_sql . " AND a.active=1 ORDER BY a.id DESC LIMIT 0,4";

		$resultvideo = $db->query( $sqlvideo );
		$resultalbum = $db->query( $sqlalbum );

		if( $resultvideo->rowCount() > 0 )
		{
			while( $rsv = $resultvideo->fetch() )
			{
				$singername = $rsv['singername'] ? $rsv['singername'] : $lang_module['unknow'];
				
				$array_video[] = array(
					"videoname" => $rsv['tname'], //
					"videosinger" => $singername, //
					"thumb" => $rsv['thumb'], //
					"videoview" => $mainURL . "=viewvideo/" . $rsv['id'] . "/" . $rsv['name'], //
					"s_video" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $rsv['casi'] . "&amp;type=singer" //
				);
			}
		}
		
		if( $resultalbum->rowCount() > 0 )
		{
			while( $rsa = $resultalbum->fetch() )
			{
				$singername = $rsa['singername'] ? $rsa['singername'] : $lang_module['unknow'];
				
				$array_album[] = array(
					"albumname" => $rsa['tname'], //
					"albumsinger" => $singername, //
					"thumb" => $rsa['thumb'], //
					"albumview" => $mainURL . "=listenlist/" . $rsa['id'] . "/" . $rsa['name'], //
					"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $singername ) . "&amp;id=" . $rsa['casi'] . "&amp;type=singer" //
				);
			}
		}
	}
}
elseif( $query_search['where'] == 'album' ) // Tim kiem album
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.ten AS singeralias, b.tenthat AS singername FROM " . NV_PREFIXLANG . "_" . $module_data . "_album AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id WHERE a.active=1";
	
	if( $query_search['SearchBy'] == 'singer' ) // Tim album theo ca si
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.casi=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND b.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'name' ) // Tim album theo ten
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.id=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND a.tname LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'upload' ) // Tim album theo nguoi dang
	{
		$sql .= " AND a.upboi LIKE '%" . $DB_LikeKey . "%'";
	}
	
	if( $query_search['page'] <= 1 )
	{
		$first_page = 0;
	}
	else
	{
		$first_page = ( $query_search['page'] - 1 ) * 20;
	}
	
	$sql .= " ORDER BY a.id DESC LIMIT " . $first_page . ", 20";
	
	$result = $db->query( $sql );
	$query = $db->query( "SELECT FOUND_ROWS()" );
	$all_page = $query->fetchColumn();
	
	while( $row = $result->fetch() )
	{
		$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

		// Check HIT
		$checkhit = explode( "-", $row['hit'] );
		$checkhit = $checkhit[0];
		
		$array_album[] = array(
			"albumname" => $row['tname'], //
			"albumsinger" => $singername, //
			"checkhit" => $checkhit, //
			"thumb" => $row['thumb'], //
			"upboi" => $row['upboi'], //
			"numview" => $row['numview'], //
			"describe" => $row['describe'], //
			"albumview" => $mainURL . "=listenlist/" . $row['id'] . "/" . $row['name'], //
			"url_search_upload" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $row['upboi'] ) . "&amp;id=0&amp;type=upload", //
			"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" //
		);
	}
}
elseif( $query_search['where'] == 'playlist' )
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.username, b.full_name FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist AS a LEFT JOIN " . NV_USERS_GLOBALTABLE . " AS b ON a.userid=b.userid WHERE a.active=1";
	
	if( $query_search['SearchBy'] == 'singer' ) // Tim playlist theo ca si
	{
		$sql .= " AND a.singer LIKE '%" . $DB_LikeKey . "%'";
	}
	elseif( $query_search['SearchBy'] == 'name' ) // Tim playlist theo ten
	{
		$sql .= " AND a.name LIKE '%" . $DB_LikeKey . "%'";
	}
	elseif( $query_search['SearchBy'] == 'upload' ) // Tim playlist theo nguoi dang
	{
		$sql .= " AND a.username LIKE '%" . $DB_LikeKey . "%'";
	}
	
	if( $query_search['page'] <= 1 )
	{
		$first_page = 0;
	}
	else
	{
		$first_page = ( $query_search['page'] - 1 ) * 20;
	}
	
	$sql .= " ORDER BY a.id DESC LIMIT " . $first_page . ", 20";
	
	$result = $db->query( $sql );
	$query = $db->query( "SELECT FOUND_ROWS()" );
	$all_page = $query->fetchColumn();
	
	while( $row = $result->fetch() )
	{
		$array_playlist[] = array(
			"name" => $row['name'], //
			"singer" => $row['singer'], //
			"message" => $row['message'], //
			"username" => $row['username'], //
			"view" => $row['view'], //
			"link" => $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'], //
			"url_search_upload" => $mainURL . "=search&amp;where=playlist&amp;q=" . urlencode( $row['username'] ) . "&amp;id=" . $row['userid'] . "&amp;type=upload", //
			"url_search_singer" => $mainURL . "=search&amp;where=playlist&amp;q=" . urlencode( $row['singer'] ) . "&amp;type=singer" //
		);
	}
}
elseif( $query_search['where'] == 'video' )
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.ten AS singeralias, b.tenthat AS singername, c.ten AS authoralias, c.tenthat AS authorname FROM " . NV_PREFIXLANG . "_" . $module_data . "_video AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_author AS c ON a.nhacsi=c.id WHERE a.active=1";
	
	if( $query_search['SearchBy'] == 'singer' ) // Tim video theo ca si
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.casi=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND b.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'name' ) // Tim video theo ten
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.id=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND a.tname LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'author' ) // Tim video theo nhac si
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND c.id=" . $query_search['id'];
		}
		else
		{
			$sql .= " AND c.tenthat LIKE '%" . $DB_LikeKey . "%'";
		}
	}
	elseif( $query_search['SearchBy'] == 'category' ) // Tim video theo the loai
	{
		if( ! empty( $query_search['id'] ) )
		{
			$sql .= " AND a.theloai=" . $query_search['id'];
		}
		else
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}
	}
	
	if( $query_search['page'] <= 1 )
	{
		$first_page = 0;
	}
	else
	{
		$first_page = ( $query_search['page'] - 1 ) * 20;
	}
	
	$sql .= " ORDER BY a.id DESC LIMIT " . $first_page . ", 20";
	
	$result = $db->query( $sql );
	$query = $db->query( "SELECT FOUND_ROWS()" );
	$all_page = $query->fetchColumn();
	
	while( $row = $result->fetch() )
	{
		$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

		// Check HIT
		$checkhit = explode( "-", $row['hit'] );
		$checkhit = $checkhit[0];
		
		$array_video[] = array(
			"videoname" => $row['tname'], //
			"videosinger" => $singername, //
			"checkhit" => $checkhit, //
			"thumb" => $row['thumb'], //
			"view" => $row['view'], //
			"dt" => $row['dt'], //
			"videoview" => $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'], //
			"s_video" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer" //
		);
	}
}

$ts = ceil( $all_page / 20 );

$contents = nv_music_search( $array_song, $array_album, $array_video, $array_singer, $array_playlist, $query_search, $all_page, $ts, $base_url );

// Hien thi ca si truoc
if( ! empty( $array_singer ) )
{
	$page_title = $array_singer['tenthat'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['singer'] . " " . $array_singer['tenthat'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
	
	$description = $array_singer['introduction'];
}
elseif( $query_search['where'] == "song" )
{
	$page_title = $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['song1'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['album'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['playlist'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['video'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
}
elseif( $query_search['where'] == "album" )
{
	$page_title = $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['album'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
}
elseif( $query_search['where'] == "video" )
{
	$page_title = $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['video'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
}
elseif( $query_search['where'] == "playlist" )
{
	$page_title = $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_module['playlist'] . " " . $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
}
else
{
	$page_title = $query_search['key'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';