<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 29/01/2011 02:41 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Gui qua tang am nhac
if( $nv_Request->isset_request( 'send_gift', 'post' ) )
{
	$checksess = filter_text_input( 'checksess', 'post', '' );
	if( $checksess != md5( "gift_" . $global_config['sitekey'] . session_id() ) ) die( 'Error access !!!' );

	// Lay du lieu
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$who_send = filter_text_input( 'who_send', 'post', '', 1, 255 );
	$who_receive = filter_text_input( 'who_receive', 'post', '', 1, 255 );
	$email_receive = filter_text_input( 'email_receive', 'post', '', 1, 255 );
	$body = filter_text_input( 'body', 'post', '', 1, 500 );

	// Kiem tra
	if( empty( $id ) ) die( "Error access !!!" );
	if( empty( $who_send ) ) die( $lang_module['error_gift_send'] );
	if( empty( $who_receive ) ) die( $lang_module['error_gift_recieve'] );
	if( empty( $email_receive ) ) die( $lang_module['error_empty_email'] );
	$check_valid_email = nv_check_valid_email( $email_receive );
	if( ! empty( $check_valid_email ) )
	{
		die( str_replace( array( "&rdquo;", "&ldquo;" ), " ", strip_tags( $check_valid_email ) ) );
	}

	// Kiem tra thoi gian
	$timeout = $nv_Request->get_int( $module_name . '_gift', 'cookie', 0 );
	if( $timeout == 0 or NV_CURRENTTIME - $timeout > 360 )
	{
		$song = getsongbyID( $id );
		if( empty( $song ) ) die( $lang_module['err_exist_song'] );

		$nv_Request->set_Cookie( $module_name . '_gift', NV_CURRENTTIME );

		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_gift` VALUES ( 
			NULL, 
			" . $db->dbescape( $who_send ) . ", 
			" . $db->dbescape( $who_receive ) . ", 
			" . $db->dbescape( $id ) . ", 
			UNIX_TIMESTAMP(), 
			" . $db->dbescape( $body ) . ", 
			" . $setting['auto_gift'] . " 
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			if( $setting['auto_gift'] )
			{
				nv_del_moduleCache( $module_name );
			}

			$link = "<a href=\"" . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "\">" . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=listenone/" . $song['id'] . "/" . $song['ten'] ) . "</a>";

			$subject = $lang_module['sendmail_welcome'] . " \"" . $who_send . "\"";
			$message = sprintf( $lang_module['gift_message'], $who_receive, $who_send, NV_MY_DOMAIN, $body, $link );
			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $email_receive, $subject, $message );

			if( $setting['auto_gift'] ) die( "OK" );
			else  die( "WAIT" );
		}
		else
		{
			die( $lang_module['send_gift_error'] );
		}
	}
	else
	{
		die( $lang_module['ready_send_gift'] );
	}
}

// Them bai hat vao playlist
if( $nv_Request->isset_request( 'addplaylist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$num = $nv_Request->get_int( $module_name . '_numlist', 'cookie', 0 );

	if( empty( $id ) ) die( "Error Access!!!" );

	if( $num >= 20 ) die( $lang_module['playlist_full'] );

	// Kiem tra bai hat da them vao chua
	for( $i = 1; $i <= $num; $i++ )
	{
		$song = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
		if( $song == $id ) die( $lang_module['playlist_added'] );
	}

	// Neu chua thi them vao
	$numnext = $num + 1;
	$nv_Request->set_Cookie( $module_name . '_song' . $numnext, $id );
	$nv_Request->set_Cookie( $module_name . '_numlist', $numnext );
	die( "OK_" . $lang_module['playlist_add_success'] );
}

// Xoa mot playlist chua luu vao CSDL
if( $nv_Request->isset_request( 'delplaylist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	// Lay so bai hat
	$num = $nv_Request->get_int( $module_name . '_numlist', 'cookie', 0 );

	if( $num == 0 ) die( $lang_module['playlist_null'] );

	// Dat cac ban hat lai thanh 0
	for( $i = 1; $i <= $num; $i++ )
	{
		$nv_Request->set_Cookie( $module_name . '_song' . $i, 0 );
	}

	$nv_Request->set_Cookie( $module_name . '_numlist', 0 );
	die( $lang_module['playlist_del_success'] );
}

// Gui loi bai hat
if( $nv_Request->isset_request( 'sendlyric', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$user_lyric = filter_text_input( 'user_lyric', 'post', '' );
	$body_lyric = filter_text_textarea( 'body_lyric', '', NV_ALLOWED_HTML_TAGS );
	$body_lyric = nv_nl2br( $body_lyric );

	// Kiem tra thoi gian
	$timeout = $nv_Request->get_int( $module_name . '_lyric', 'cookie', 0 );
	if( $timeout == 0 or NV_CURRENTTIME - $timeout > 360 )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` VALUES (
			NULL, 
			" . $db->dbescape( $id ) . ", 
			" . $db->dbescape( $user_lyric ) . ", 
			" . $db->dbescape( $body_lyric ) . ", 
			" . $setting['auto_lyric'] . ", 
			" . NV_CURRENTTIME . " 
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			$nv_Request->set_Cookie( $module_name . '_lyric', NV_CURRENTTIME );

			if( $setting['auto_lyric'] )
			{
				die( "OK" );
			}
			else
			{
				die( "WAIT" );
			}
		}
		else
		{
			die( $lang_module['send_lyric_error'] );
		}
	}
	else
	{
		die( $lang_module['ready_lyric_gift'] );
	}
}

// Gui bao loi bai hat, bao loi album cho quan tri
if( $nv_Request->isset_request( 'senderror', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$user = filter_text_input( 'user', 'post', '' ); // Ten nguoi gui bao loi
	$body = filter_text_input( 'body', 'post', '', 1 );
	$where = filter_text_input( 'where', 'post', '' );
	$root_error = filter_text_input( 'root_error', 'post', '' );
	$key = $nv_Request->get_int( 'id', 'post', 0 );

	// Neu da dang nhap thi khong duoc phep doi ten dang nhap
	$username = ! empty( $user_info['username'] ) ? $user_info['username'] : $user;
	$userid = ! empty( $user_info['userid'] ) ? $user_info['userid'] : 0;

	// Kiem tra thoi gian
	$timeout = $nv_Request->get_int( $module_name . '_error_' . $where . "_" . $key, 'cookie', 0 );
	if( $timeout == 0 or NV_CURRENTTIME - $timeout > 90 )
	{
		$check = 0;

		// Neu day la ba hat va kiem tra loi khong ton tai
		if( ( $where == 'song' ) and ( $root_error == "check" ) )
		{
			$song = getsongbyID( $key );
			$url = outputURL( $song['server'], $song['duongdan'] );
			if( $song['server'] == 1 )
			{
				$url = NV_MY_DOMAIN . $url;
			}
			if( nv_check_url( $url ) )
			{
				$ok = 1;
				die( $lang_module['send_error_not'] );
			}
			else
			{
				$ok = 0;
			}
			$check = 1;
		}
		$nv_Request->set_Cookie( $module_name . '_error_' . $where . "_" . $key, NV_CURRENTTIME );

		if( ( $check == 0 ) or ( ( $check == 1 ) and ( $ok == 0 ) ) )
		{
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_error` VALUES (
				NULL, 
				" . $key . ", 
				" . $userid . ", 
				" . $db->dbescape( $username ) . ", 
				" . $db->dbescape( $root_error . " | " . $body ) . ", 
				" . $db->dbescape( $where ) . ", 
				" . NV_CURRENTTIME . ", 
				" . $db->dbescape( $client_info['ip'] ) . ", 1
			)";

			if( $db->sql_query_insert_id( $sql ) )
			{
				die( $lang_module['send_error_suc'] );
			}
			else
			{
				die( $lang_module['send_error_error'] );
			}
		}
	}
	else
	{
		die( $lang_module['ready_send_error'] );
	}
}

// Binh chon bai hat
if( $nv_Request->isset_request( 'votesong', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$difftimeout = 86400;
	$id = $nv_Request->get_int( 'id', 'post', 0 );

	if( ! defined( 'NV_IS_USER' ) and ! defined( 'NV_IS_ADMIN' ) )
	{
		echo "ERR_no_" . $lang_module['song_vote_err'];
	}
	else
	{
		$timeout = $nv_Request->get_int( $module_name . '_vote_song_' . $id, 'cookie', 0 );
		if( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
		{
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET binhchon = binhchon+1 WHERE `id` =" . $id );
			$nv_Request->set_Cookie( $module_name . '_vote_song_' . $id, NV_CURRENTTIME );
			$song = getsongbyID( $id );
			$numvote = $song['binhchon'];
			$endtime = NV_CURRENTTIME - 2592000;
			$result_time = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` WHERE songid=" . $id . " LIMIT 1" );
			$nums_music = $db->sql_numrows( $result_time );
			if( $nums_music == 0 )
			{
				$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` (`id`, `songid`, `dt`, `hit`) VALUES ( NULL, " . $db->dbescape( $id ) . ", " . $db->dbescape( NV_CURRENTTIME ) . ", 1 )" );
			}
			else
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` SET hit=hit+1 WHERE songid = " . $id );
			}
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` WHERE dt < " . $endtime . "" );
			echo "OK_" . $numvote . "_" . $lang_module['song_vote_success'];
		}
		else
		{
			echo "ERR_no_" . $lang_module['song_vote_timeout'];
		}
	}
	die();
}

// Xoa bai hat tu playlist (Chua luu va CSDL)
if( $nv_Request->isset_request( 'delsongfrlist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$stt = $nv_Request->get_int( 'stt', 'post', 0 );
	$contents = "OK_" . $stt;

	$num = $nv_Request->get_int( $module_name . '_numlist', 'cookie', 0 );

	for( $i = $stt; $i <= $num; $i++ )
	{
		$j = $i + 1;
		$tmp = $nv_Request->get_int( $module_name . '_song' . $j, 'cookie', 0 );
		$nv_Request->set_Cookie( $module_name . '_song' . $i, $tmp );
	}

	$numprev = $num - 1;
	$nv_Request->set_Cookie( $module_name . '_numlist', $numprev );

	die( $contents );
}

// Luu playlist
if( $nv_Request->isset_request( 'savealbum', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$difftimeout = 180;

	$name = filter_text_input( 'name', 'post', '' );
	$keyname = change_alias( $name );
	$singer = filter_text_input( 'singer', 'post', '' );
	$message = nv_br2nl( filter_text_textarea( 'message', '', NV_ALLOWED_HTML_TAGS ) );

	if( defined( 'NV_IS_USER' ) )
	{
		$username = $user_info['username'];
		$userid = $user_info['userid'];
	}
	else
	{
		$username = "";
		$userid = 0;
	}

	$num = $nv_Request->get_int( $module_name . '_numlist', 'cookie', 0 );
	$songdata = array();
	for( $i = 1; $i <= $num; $i++ )
	{
		$tmp = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
		$songdata[] = $tmp;
	}

	$timeout = $nv_Request->get_int( $module_name . '_' . $userid, 'cookie', 0 );

	if( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` (`id`, `userid`, `username`, `name`, `keyname`, `singer`, `message`, `songdata`, `time`, `view`, `active`) VALUES ( NULL, " . $userid . ", \"" . $username . "\", \"" . $name . "\", \"" . $keyname . "\", \"" . $singer . "\", \"" . $message . "\", \"" . implode( ",", $songdata ) . "\", UNIX_TIMESTAMP() , 0, " . $setting['auto_album'] . ")";

		$aaaa = $db->sql_query( $query ) ? "1_" : "0_";
		$nv_Request->set_Cookie( $module_name . '_' . $userid, NV_CURRENTTIME );

		$aaaa .= $setting['auto_album'] ? $lang_module['playlist_add_ok'] : $lang_module['playlist_add_waiting'];
		$aaaa .= "_" . nv_url_rewrite( $main_header_URL . "=creatalbum", true );
		die( $aaaa );
	}
	else
	{
		die( $lang_module['err_cre_album'] );
	}
}

// Xoa playlist da duoc luu vao CSDL
if( $nv_Request->isset_request( 'dellist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	if( ! defined( 'NV_IS_USER' ) )
	{
		die( $lang_module['del_error'] );
	}

	if( $id > 0 )
	{
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `id`=" . $id . " AND `userid`=" . $user_info['userid'];
		$result = $db->sql_query( $sql );
	}

	if( ! empty( $result ) )
	{
		die( "OK_" . $id );
	}

	die( $lang_module['del_error'] );
}

// Xoa mot bai hat cua thanh vien
if( $nv_Request->isset_request( 'delsong', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	if( ! defined( 'NV_IS_USER' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$song = getsongbyID( $id );

	if( $song['userid'] != $user_info['userid'] ) die( 'Wrong URL' );

	updatesinger( $song['casi'], 'numsong', '-1' );
	updateauthor( $song['nhacsi'], 'numsong', '-1' );
	updatealbum( $song['album'], '-1' );
	delcomment( 'song', $song['id'] );
	dellyric( $song['id'] );
	delerror( 'song', $song['id'] );
	delgift( $song['id'] );
	unlinkSV( $song['server'], $song['duongdan'] );

	$list_cat = $song['listcat'] ? explode( ',', $song['listcat'] ) : array();
	$list_cat[] = $song['theloai'];
	$list_cat = array_filter( array_unique( $list_cat ) );
	
	foreach( $list_cat as $_cid )
	{
		UpdateSongCat( $_cid, '-1' );
	}
	
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
	$db->sql_query( $sql );
	nv_del_moduleCache( $module_name );

	die( "OK_" . $id );
}

// Xoa mot bai hat tu playlist da luu
if( $nv_Request->isset_request( 'delsongfrplaylist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	if( ! defined( 'NV_IS_USER' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$plid = $nv_Request->get_int( 'plid', 'post', 0 );

	if( empty( $plid ) ) die( 'Error !!!!!' );

	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active`=1 AND `id`=" . $plid . " AND `userid`=" . $user_info['userid'];
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
	if( $check != 1 ) die( 'Error !!!!!' );

	$row = $db->sql_fetchrow( $result );

	$songdatanew = array();
	$songdata = explode( ',', $row['songdata'] );
	foreach( $songdata as $value )
	{
		if( ( intval( $value ) == $id ) || ( $value == '' ) ) continue;
		$songdatanew[] = $value;
	}

	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET `songdata` = " . $db->dbescape( implode( ",", $songdatanew ) ) . " WHERE `id` =" . $plid );
	nv_del_moduleCache( $module_name );

	die( "OK_" . $id );
}

// Tim kiem nhanh
if( $nv_Request->isset_request( 'quicksearch', 'get' ) )
{
	$q = filter_text_input( 'q', 'get', '', 0, NV_MAX_SEARCH_LENGTH );
	$checksess = filter_text_input( 'checksess', 'get', '', 1, 255 );
	
	if( $checksess != md5( $global_config['sitekey'] . session_id() ) ) die('Error Access!!!');
	
	if( empty( $q ) ) die('Not thing to search!!!');
	
	$DB_LikeKey = $db->dblikeescape( $q );
	
	$array_singer = $array_song = $array_album = $array_video = $array_playlist = array();
	
	// Ket qua ca si
	$sql = "SELECT `id`, `tenthat`, `thumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE (`tenthat` LIKE '%" . $DB_LikeKey . "%' AND `tenthat` NOT LIKE '% ft. %') ORDER BY `tenthat` ASC LIMIT 0,2";
	$result = $db->sql_query( $sql );
	$num_singer = $db->sql_numrows( $result );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_singer[] = array(
			'title' => $row['tenthat'],
			'thumb' => $row['thumb'],
			'link' => nv_url_rewrite( $main_header_URL . "=search&where=song&q=" . urlencode( $row['tenthat'] ) . "&id=" . $row['id'] . "&type=singer", true )
		);
	}
	
	// Ket qua album
	$sql = "SELECT a.id, a.name, a.tname, a.thumb, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 AND a.tname LIKE '%" . $DB_LikeKey . "%' ORDER BY a.tname ASC LIMIT 0,2";
	$result = $db->sql_query( $sql );
	$num_album = $db->sql_numrows( $result );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_album[] = array(
			'title' => $row['tname'],
			'thumb' => $row['thumb'],
			'singer' => $row['singername'],
			'link' => nv_url_rewrite( $main_header_URL . "=listenlist/" . $row['id'] . "/" . $row['name'], true )
		);
	}
	
	// Ket qua playlist
	$sql = "SELECT `id`, `name`, `keyname`, `singer` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `active`=1 AND `name` LIKE '%" . $DB_LikeKey . "%' ORDER BY `name` ASC LIMIT 0,2";
	$result = $db->sql_query( $sql );
	$num_playlist = $db->sql_numrows( $result );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_playlist[] = array(
			'title' => $row['name'],
			'singer' => $row['singer'],
			'link' => nv_url_rewrite( $main_header_URL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'], true )
		);
	}
	
	// Ket qua video
	$sql = "SELECT a.id, a.name, a.tname, a.thumb, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 AND a.tname LIKE '%" . $DB_LikeKey . "%' ORDER BY a.tname ASC LIMIT 0,2";
	$result = $db->sql_query( $sql );
	$num_video = $db->sql_numrows( $result );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_video[] = array(
			'title' => $row['tname'],
			'singer' => $row['singername'],
			'thumb' => $row['thumb'],
			'link' => nv_url_rewrite( $main_header_URL . "=viewvideo/" . $row['id'] . "/" . $row['name'], true )
		);
	}

	// Ket qua bai hat
	$num_song = 10 - $num_video - $num_playlist - $num_album - $num_singer;
	$sql = "SELECT a.id, a.ten, a.tenthat, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 AND a.tenthat LIKE '%" . $DB_LikeKey . "%' ORDER BY a.tenthat ASC LIMIT 0,2";
	$result = $db->sql_query( $sql );

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_song[] = array(
			'title' => $row['tenthat'],
			'singer' => $row['singername'],
			'link' => nv_url_rewrite( $main_header_URL . "=listenone/" . $row['id'] . "/" . $row['ten'], true )
		);
	}
	
	$contents = nv_quicksearch_theme( $q, $array_singer, $array_song, $array_album, $array_video, $array_playlist );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

die( "Error Access !!!" );

?>