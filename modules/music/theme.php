<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:41 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $my_head;
if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/js/music/music.js" ) )
{
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/music/music.js\"></script>\n";
}
else
{
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/default/js/music/music.js\"></script>\n";
}

// Giao dien
// Nghe mot bai hat
function nv_music_listenone( $gdata, $sdata, $cdata, $ldata, $array_album, $array_video, $array_singer )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $my_head, $main_header_URL, $mainURL;

	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/logo.png" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $sdata['song_id'] . "/" . $sdata['song_sname'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="84" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';

	$xtpl = new XTemplate( "listenone.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

	$xtpl->assign( 'SDATA', $sdata );
	$xtpl->assign( 'GDATA', $gdata );
	$xtpl->assign( 'CDATA', $cdata );

	if( ! empty( $sdata['song_listcat'] ) )
	{
		foreach( $sdata['song_listcat'] as $cat )
		{
			$xtpl->assign( 'CAT', $cat );
			$xtpl->parse( 'main.cat.loop' );
		}
		$xtpl->parse( 'main.cat' );
	}

	if( $cdata['checkhit'] >= 20 )
	{
		$xtpl->parse( 'main.hit' );
	}

	// Ten album neu co
	if( $sdata['album_name'] )
	{
		$xtpl->parse( 'main.album' );
	}

	if( $ldata['number'] >= 1 )
	{
		$i = 1;
		foreach( $ldata['data'] as $rowlyric )
		{
			if( ( $ldata['number'] > 1 ) && ( $i < $ldata['number'] ) )
			{
				$nextdiv = $i + 1;
				$xtpl->assign( 'nextdiv', $nextdiv );
				$xtpl->parse( 'main.lyric.next' );
			}

			if( $i > 1 )
			{
				$prevdiv = $i - 1;
				$xtpl->assign( 'prevdiv', $prevdiv );
				$xtpl->parse( 'main.lyric.prev' );
			}

			$xtpl->assign( 'LYRIC_DATA', $rowlyric );

			$xtpl->assign( 'thisdiv', $i );
			$xtpl->parse( 'main.lyric' );
			$i++;
		}
	}
	else
	{
		$xtpl->parse( 'main.nolyric' );
	}

	// Send gift
	if( ( $setting['who_gift'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nogift' );
	}
	elseif( $setting['who_gift'] == 2 )
	{
		$xtpl->parse( 'main.stopgift' );
	}
	else
	{
		$xtpl->parse( 'main.gift' );
	}

	// Send lyric
	if( ( $setting['who_lyric'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.noaccesslyric' );
	}
	elseif( $setting['who_lyric'] == 2 )
	{
		$xtpl->parse( 'main.stoplyric' );
	}
	else
	{
		$xtpl->parse( 'main.accesslyric' );
	}

	// Thong tin ca si
	if( ! empty( $array_singer ) )
	{
		$xtpl->assign( 'SINGER_INFO', $array_singer );
		$xtpl->parse( 'main.singer_info' );
	}
	
	// Album cung ca si
	if( ! empty( $array_album ) )
	{
		$xtpl->assign( 'SEARCH_ALL_ALBUM', $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $sdata['song_singer'] ) . "&amp;id=" . $sdata['song_singer_id'] . "&amp;type=singer");
		
		foreach( $array_album as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_album.loop' );
		}
		
		$xtpl->parse( 'main.other_album' );
	}
	
	// Video cung ca si
	if( ! empty( $array_video ) )
	{
		$xtpl->assign( 'SEARCH_ALL_VIDEO', $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $sdata['song_singer'] ) . "&amp;id=" . $sdata['song_singer_id'] . "&amp;type=singer");
		
		foreach( $array_video as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_video.loop' );
		}
		
		$xtpl->parse( 'main.other_video' );
	}
	
	// Comment
	if( ( $setting['who_comment'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nocomment' );
	}
	elseif( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );
	}
	else
	{
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Nghe playlist cua thanh vien
function nv_music_listen_playlist( $gdata, $sdata )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $main_header_URL, $my_head, $downURL;

	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/randimg/img(' . rand( 1, 10 ) . ').jpg" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/playlist/" . $gdata['id'] . "/" . $gdata['playlist_alias'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="84" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';

	$xtpl = new XTemplate( "listenuserlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'URL_DOWN', $downURL );

	$xtpl->assign( 'GDATA', $gdata );

	$i = 1;
	foreach( $sdata as $song )
	{
		$song['song_names'] = nv_clean60( $song['song_name'], 30 );
		$song['song_singers'] = nv_clean60( $song['song_singer'], 30 );
		$song['stt'] = $i;
		$xtpl->assign( 'SDATA', $song );
		$xtpl->parse( 'main.song' );
		$i++;
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Danh sach cac album
function nv_music_album( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;

	$xtpl = new XTemplate( "album.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );

	// Active span
	if( $g_array['type'] == 'id' )
	{
		$xtpl->assign( 'active_1', '' );
		$xtpl->assign( 'active_2', ' active' );
	}
	else
	{
		$xtpl->assign( 'active_1', ' active' );
		$xtpl->assign( 'active_2', '' );
	}

	foreach( $array as $row )
	{
		$row['describe'] = nv_clean60( strip_tags( $row['describe'] ), 200 );
		$xtpl->assign( 'ROW', $row );

		if( $row['checkhit'] >= 20 )
		{
			$xtpl->parse( 'main.loop.hit' );
		}
		
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Tat cac cac playlist
function nv_music_allplaylist( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;

	$xtpl = new XTemplate( "allplaylist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );

	// active span
	if( $g_array['type'] == 'id' )
	{
		$xtpl->assign( 'active_1', 'class="active"' );
		$xtpl->assign( 'active_2', '' );
	}
	else
	{
		$xtpl->assign( 'active_1', '' );
		$xtpl->assign( 'active_2', 'class="active"' );
	}

	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Hien thi binh luan
function nv_music_showcomment( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;

	$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );

	// Phan trang binh luan
	if( $g_array['num'] > ( $g_array['page'] + 8 ) )
	{
		$next = $g_array['page'] + 8;
		$xtpl->assign( 'next', $next );
		$xtpl->parse( 'main.next' );
	}
	if( $g_array['page'] > 0 )
	{
		$prev = $g_array['page'] - 8;
		$xtpl->assign( 'prev', $prev );
		$xtpl->parse( 'main.prev' );
	}

	if( $array )
	{
		foreach( $array as $row )
		{
			$row['avatar'] = empty( $row['avatar'] ) ? NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/d-avatar.gif" : NV_BASE_SITEURL . $row['avatar'];
			$row['date'] = nv_date( "d/m/Y H:i", $row['date'] );

			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.datacomment.loop' );
		}
		$xtpl->parse( 'main.datacomment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Quan ly playlist cua thanh vien
function nv_music_creatalbum( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $client_info;

	$xtpl = new XTemplate( "creatalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'img', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg" );

	// Chua dang nhap
	if( empty( $g_array['userid'] ) )
	{
		$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] ) );
		$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" );
		$xtpl->parse( 'main.noaccess' );
	}
	else
	{
		if( ! empty( $array['song'] ) )
		{
			foreach( $array['song'] as $song )
			{
				$xtpl->assign( 'SONG', $song );
				$xtpl->parse( 'main.access.creatlist.loop' );
			}

			$xtpl->parse( 'main.access.creatlist' );
		}

		foreach( $array['playlist'] as $playlist )
		{
			$playlist['playlist_img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg";
			$playlist['date'] = nv_date( "d/m/Y H:i:s", $playlist['date'] );
			$xtpl->assign( 'PLIST', $playlist );
			$xtpl->parse( 'main.access.list' );
		}

		$xtpl->parse( 'main.access' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Sua playlist
function nv_music_editplaylist( $g_array, $array, $row )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL;

	$xtpl = new XTemplate( "editplaylist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'ACTION', $mainURL . "=editplaylist/" . $g_array['id'] );
	$xtpl->assign( 'img', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg" );
	$xtpl->assign( 'INFO', $row );

	if( $g_array['ok'] == 1 )
	{
		$xtpl->assign( 'url_play', $mainURL . "=listenuserlist/" . $row['id'] . "/" . $row['keyname'] );
		$xtpl->assign( 'url_back', $mainURL . "=creatalbum" );
		$xtpl->parse( 'main.sucess' );
	}

	foreach( $array as $song )
	{
		$xtpl->assign( 'ROW', $song );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Nghe album
function nv_music_listenlist( $g_array, $album_array, $song_array, $array_album, $array_video, $array_singer )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL, $my_head, $main_header_URL, $downURL;
	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . $album_array['album_thumb'] . '" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/album/" . $g_array['id'] . "/" . $g_array['sname'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="84" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';

	$xtpl = new XTemplate( "listenlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'URL_DOWN', $downURL );
	$xtpl->assign( 'playerurl', $global_config['site_url'] . "/modules/" . $module_file . "/data/" );
	$xtpl->assign( 'base_url', NV_BASE_SITEURL . "modules/" . $module_file . "/data/" );
	$xtpl->assign( 'ads', getADS() );
	$xtpl->assign( 'ALBUM', $album_array );

	$xtpl->assign( 'USER_NAME', $g_array['name'] );
	$xtpl->assign( 'NO_CHANGE', ( $g_array['name'] == '' ) ? '' : 'readonly="readonly"' );

	$xtpl->assign( 'ID', $g_array['id'] );

	if( $album_array['checkhit'] >= 20 )
	{
		$xtpl->parse( 'main.hit' );
	}

	$i = 1;
	foreach( $song_array as $song )
	{
		$song['stt'] = $i;
		$song['song_singers'] = nv_clean60( $song['song_singer'], 30 );
		$song['song_names'] = nv_clean60( $song['song_name'], 30 );
		$xtpl->assign( 'SONG', $song );
		$xtpl->parse( 'main.song' );
		$i++;
	}

	// Thong tin ca si
	if( ! empty( $array_singer ) )
	{
		$xtpl->assign( 'SINGER_INFO', $array_singer );
		$xtpl->parse( 'main.singer_info' );
	}
	
	// Album cung ca si
	if( ! empty( $array_album ) )
	{
		$xtpl->assign( 'SEARCH_ALL_ALBUM', $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $album_array['singer'] ) . "&amp;id=" . $album_array['singerid'] . "&amp;type=singer");
		
		foreach( $array_album as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_album.loop' );
		}
		
		$xtpl->parse( 'main.other_album' );
	}
	
	// Video cung ca si
	if( ! empty( $array_video ) )
	{
		$xtpl->assign( 'SEARCH_ALL_VIDEO', $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $album_array['singer'] ) . "&amp;id=" . $album_array['singerid'] . "&amp;type=singer");
		
		foreach( $array_video as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_video.loop' );
		}
		
		$xtpl->parse( 'main.other_video' );
	}

	// Binh luan
	if( ( $setting['who_comment'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->assign( 'USER_LOGIN', $g_array['user_login'] );
		$xtpl->assign( 'USER_REGISTER', $g_array['user_register'] );
		$xtpl->parse( 'main.nocomment' );
	}
	elseif( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );
	}
	else
	{
		$xtpl->assign( 'USER_NAME', $g_array['name'] );
		$xtpl->assign( 'NO_CHANGE', ( $g_array['name'] == '' ) ? '' : 'readonly="readonly"' );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Quan ly bai hat
function nv_music_managersong( $g_array, $array_song, $data_song )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL, $client_info;

	$xtpl = new XTemplate( "managersong.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );

	// khong duoc vao
	if( $g_array['userid'] == 0 )
	{
		$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] ) );
		$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" );
		$xtpl->parse( 'main.noaccess' );
	}
	else
	{
		if( ! empty( $data_song ) )
		{
			$xtpl->assign( 'SONG', $data_song['song'] );
			$xtpl->assign( 'SINGER', $data_song['singer'] );
			$xtpl->assign( 'AUTHOR', $data_song['author'] );
			$xtpl->assign( 'CATEGORY', $data_song['cate'] );

			if( $g_array['updateok'] )
			{
				$xtpl->assign( 'url_play', $mainURL . "=listenone/" . $data_song['song']['id'] . "/" . $data_song['song']['ten'] );
				$xtpl->assign( 'url_back', $mainURL . "=managersong" );
				$xtpl->parse( 'main.access.edit.ok' );
			}

			$xtpl->parse( 'main.access.edit' );
		}

		if( ! empty( $array_song ) )
		{
			foreach( $array_song as $song )
			{
				$song['bitrate'] = $song['bitrate'] / 1000;
				$song['size'] = round( ( $song['size'] / 1024 / 1024 ), 2 );
				$song['duration'] = ( int )( $song['duration'] / 60 ) . ":" . $song['duration'] % 60;

				$xtpl->assign( 'SONG', $song );
				if( $song['active'] == 0 )
				{
					$xtpl->parse( 'main.access.song.loop.noacept' );
				}
				else
				{
					$xtpl->parse( 'main.access.song.loop.acept' );
				}
				$xtpl->parse( 'main.access.song.loop' );
			}
			$xtpl->parse( 'main.access.song' );
		}
		$xtpl->parse( 'main.access' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Nghe gio nhac
function nv_music_playlist( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $downURL;

	$xtpl = new XTemplate( "playlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'base_url', NV_BASE_SITEURL . "modules/" . $module_file . "/data/" );
	$xtpl->assign( 'ads', getADS() );
	$xtpl->assign( 'URL_DOWN', $downURL );

	if( empty( $g_array['num'] ) )
	{
		$xtpl->parse( 'main.null' );
	}
	else
	{
		$i = 1;
		foreach( $array as $row )
		{
			$row['stt'] = $i;
			$row['song_names'] = nv_clean60( $row['song_name'], 30 );
			$row['song_singers'] = nv_clean60( $row['song_singer'], 30 );
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.song' );
			$i++;
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Tiem kiem bai hat
function nv_music_search( $array_song, $array_album, $array_video, $array_singer, $array_playlist, $query_search, $all_page, $ts, $base_url )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $downURL, $mainURL;

	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'URL_DOWN', $downURL );
	$xtpl->assign( 'allvideo', $mainURL . "=search&where=video&q=" . urlencode( $query_search['key'] ) );
	$xtpl->assign( 'allalbum', $mainURL . "=search&where=album&q=" . urlencode( $query_search['key'] ) );
	
	$xtpl->assign( 'NUM_RESULT', $all_page );
	$xtpl->assign( 'QUERY_SEARCH', $query_search );

	// Kieu tim kiem
	$array_where_search = array(
		'song' => $lang_module['song1'],
		'album' => $lang_module['album'],
		'video' => $lang_module['video'],
		'playlist' => $lang_module['search_quick_res_playlist'],
	);
	foreach( $array_where_search as $k => $v )
	{
		$xtpl->assign( 'CURRENT', $k == $query_search['where'] ? ' boldcolor' : '' );
		$xtpl->assign( 'URL', $mainURL . "=search&amp;where=" . $k . "&amp;q=" . urlencode( $query_search['key'] ) . "&amp;id=" . $query_search['id'] . "&amp;type=" . $query_search['SearchBy'] );
		$xtpl->assign( 'TITLE', $v );
		$xtpl->parse( 'main.wheresearch' );
	}

	$xtpl->assign( 'TITLE_SEARCH', $array_where_search[$query_search['where']] );
	
	// Tim theo
	$array_type_search = array(
		'name' => $lang_module['search_adv_search_name'],
		'singer' => $lang_module['singer']
	);
	
	if( in_array( $query_search['where'], array( 'song', 'video' ) ) )
	{
		$array_type_search['author'] = $lang_module['author_1'];
	}
	
	if( in_array( $query_search['where'], array( 'song', 'video', 'playlist' ) ) )
	{
		$array_type_search['upload'] = $lang_module['who_post'];
	}
	
	foreach( $array_type_search as $k => $v )
	{
		$xtpl->assign( 'CURRENT', $k == $query_search['SearchBy'] ? ' boldcolor' : '' );
		$xtpl->assign( 'URL', $mainURL . "=search&amp;where=" . $query_search['where'] . "&amp;q=" . urlencode( $query_search['key'] ) . "&amp;id=" . $query_search['id'] . "&amp;type=" . $k );
		$xtpl->assign( 'TITLE', $v );
		$xtpl->parse( 'main.typesearch' );
	}
	
	// Thong tin ca si
	if( ! empty( $array_singer ) and $query_search['where'] == "song" )
	{
		$xtpl->assign( 'SDATA', $array_singer );
		$xtpl->parse( 'main.singer_info' );
	}
	
	// Hien thi ket qua bai hat
	if( $query_search['where'] == 'song' )
	{
		$i = 1;
		foreach( $array_song as $song )
		{
			$song['bitrate'] = $song['bitrate'] / 1000;
			$song['size'] = round( ( $song['size'] / 1024 / 1024 ), 2 );
			$song['duration'] = ( int )( $song['duration'] / 60 ) . ":" . $song['duration'] % 60;

			$xtpl->assign( 'SONG', $song );

			if( ( $i == 4 ) and ( $query_search['page'] == 1 ) )
			{
				if( ! empty( $array_video ) )
				{
					foreach( $array_video as $video )
					{
						$video['videonames'] = nv_clean60( $video['videoname'], 40 );
						$xtpl->assign( 'VIDEO', $video );
						$xtpl->parse( 'main.typesong.loop.sub.video.loop' );
					}
					$xtpl->parse( 'main.typesong.loop.sub.video' );
				}

				if( ! empty( $array_album ) )
				{
					foreach( $array_album as $album )
					{
						$album['albumnames'] = nv_clean60( $album['albumname'], 40 );
						$xtpl->assign( 'ALBUM', $album );
						$xtpl->parse( 'main.typesong.loop.sub.album.loop' );
					}
					$xtpl->parse( 'main.typesong.loop.sub.album' );
				}
				$xtpl->parse( 'main.typesong.loop.sub' );
			}

			if( ( $i % 2 ) == 0 ) $xtpl->assign( 'gray', 'gray' );
			else  $xtpl->assign( 'gray', '' );

			if( $song['checkhit'] )
			{
				$xtpl->parse( 'main.typesong.loop.hit' );
			}

			$xtpl->parse( 'main.typesong.loop' );
			$i++;
		}
		
		$xtpl->parse( 'main.typesong' );
	}
	elseif( $query_search['where'] == 'album' )
	{
		foreach( $array_album as $album )
		{	
			$album['describe'] = nv_clean60( strip_tags( $album['describe'] ), 200 );
		
			$xtpl->assign( 'ALBUM', $album );

			if( $album['checkhit'] >= 20 )
			{
				$xtpl->parse( 'main.typealbum.loop.hit' );
			}
			
			$xtpl->parse( 'main.typealbum.loop' );
		}
		
		$xtpl->parse( 'main.typealbum' );
	}
	elseif( $query_search['where'] == 'video' )
	{
		foreach( $array_video as $video )
		{
			$video['dt'] = nv_date( 'd/m/Y H:i', $video['dt'] );
			
			$xtpl->assign( 'VIDEO', $video );

			if( $video['checkhit'] >= 20 )
			{
				$xtpl->parse( 'main.typevideo.loop.hit' );
			}
			
			$xtpl->parse( 'main.typevideo.loop' );
		}
		
		$xtpl->parse( 'main.typevideo' );
	}
	elseif( $query_search['where'] == 'playlist' )
	{
		foreach( $array_playlist as $playlist )
		{	
			$playlist['message'] = nv_clean60( strip_tags( $playlist['message'] ), 200 );
			$playlist['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/randimg/img(' . rand(1, 10) . ').jpg';
		
			$xtpl->assign( 'PLAYLIST', $playlist );
			$xtpl->parse( 'main.typeplaylist.loop' );
		}
		
		$xtpl->parse( 'main.typeplaylist' );
	}

	$gennerate_page = new_page( $ts, $query_search['page'], $base_url, false );
	
	if( ! empty( $gennerate_page ) )
	{
		$xtpl->assign( 'GENNERATE_PAGE', $gennerate_page );
		$xtpl->parse( 'main.gennerate_page' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Tim kiem video
function nv_music_searchvideo( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL;

	$xtpl = new XTemplate( "searchvideo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );

	foreach( $array as $row )
	{
		$row['creat'] = nv_date( "H:i d/m/Y", $row['creat'] );
		$xtpl->assign( 'ROW', $row );
		
		if( $row['checkhit'] >= 20 )
		{
			$xtpl->parse( 'main.loop.hit' );
		}
		
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Gui email bai hat
function nv_sendmail_themme( $sendmail )
{
	global $module_name, $module_info, $module_file, $global_config, $lang_module, $lang_global;
	$script = nv_html_site_js();
	$script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
	$script .= "<script type=\"text/javascript\">\n";
	$script .= "          $(document).ready(function(){\n";
	$script .= "            $(\"#sendmailForm\").validate();\n";
	$script .= "          });\n";
	$script .= "</script>\n";
	if( NV_LANG_INTERFACE == 'vi' )
	{
		$script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/mudim.js\"></script>";
	}
	$sendmail['script'] = $script;
	$xtpl = new XTemplate( "sendmail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'SENDMAIL', $sendmail );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
	if( $global_config['gfx_chk'] > 0 )
	{
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->parse( 'main.content.captcha' );
	}
	$xtpl->parse( 'main.content' );
	if( ! empty( $sendmail['result'] ) )
	{
		$xtpl->assign( 'RESULT', $sendmail['result'] );
		$xtpl->parse( 'main.result' );
		if( $sendmail['result']['check'] == true )
		{
			$xtpl->parse( 'main.close' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Hien thi danh sach cac bai hat trong playlist cookie
function nv_music_showplaylist( $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL;

	$xtpl = new XTemplate( "playlistshow.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Tat cac cac bai hat
function nv_music_song( $g_array, $array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $downURL, $mainURL;

	$xtpl = new XTemplate( "song.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'URL_DOWN', $downURL );
	$xtpl->assign( 'hot', $mainURL . "=song/numview" );
	$xtpl->assign( 'new', $mainURL . "=song/id" );

	// Active span
	if( $g_array['type'] == 'id' )
	{
		$xtpl->assign( 'active_1', '' );
		$xtpl->assign( 'active_2', ' active' );
	}
	else
	{
		$xtpl->assign( 'active_1', ' active' );
		$xtpl->assign( 'active_2', '' );
	}

	foreach( $array as $row )
	{
		$row['bitrate'] = $row['bitrate'] / 1000;
		$row['size'] = round( ( $row['size'] / 1024 / 1024 ), 2 );
		$row['duration'] = ( int )( $row['duration'] / 60 ) . ":" . $row['duration'] % 60;

		$xtpl->assign( 'ROW', $row );

		if( $row['checkhit'] >= 20 )
		{
			$xtpl->parse( 'main.loop.hit' );
		}

		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Upload bai hat
function nv_music_upload( $g_array )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $downURL, $mainURL;

	$xtpl = new XTemplate( "upload.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'DATA_URL', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" );
	$xtpl->assign( 'DATA_ACTION', $mainURL . "=uploadfile" );
	$xtpl->assign( 'MAXUPLOAD', $setting['upload_max'] );

	if( ( $setting['who_upload'] == 0 ) and ! defined( 'NV_IS_USER' ) and ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->assign( 'USER_LOGIN', $g_array['user_login'] );
		$xtpl->assign( 'USER_REGISTER', $g_array['user_register'] );
		$xtpl->parse( 'main.noaccess' );
	}
	elseif( $setting['who_upload'] == 2 )
	{
		$xtpl->parse( 'main.stopaccess' );
	}
	else
	{
		$xtpl->parse( 'main.access' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Gui email video
function nv_sendmail_video_themme( $sendmail )
{
	global $module_name, $module_info, $module_file, $global_config, $lang_module, $lang_global;
	$script = nv_html_site_js();
	$script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
	$script .= "<script type=\"text/javascript\">\n";
	$script .= "          $(document).ready(function(){\n";
	$script .= "            $(\"#sendmailForm\").validate();\n";
	$script .= "          });\n";
	$script .= "</script>\n";
	if( NV_LANG_INTERFACE == 'vi' )
	{
		$script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/mudim.js\"></script>";
	}
	$sendmail['script'] = $script;
	$xtpl = new XTemplate( "videosendmail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'SENDMAIL', $sendmail );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
	if( $global_config['gfx_chk'] > 0 )
	{
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->parse( 'main.content.captcha' );
	}
	$xtpl->parse( 'main.content' );
	if( ! empty( $sendmail['result'] ) )
	{
		$xtpl->assign( 'RESULT', $sendmail['result'] );
		$xtpl->parse( 'main.result' );
		if( $sendmail['result']['check'] == true )
		{
			$xtpl->parse( 'main.close' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien trang chu video
function nv_music_video( $category, $array_new, $array_hot )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $downURL, $mainURL;

	$xtpl = new XTemplate( "video.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'url_viewhot', $mainURL . "=searchvideo/view" );
	$xtpl->assign( 'url_viewnew', $mainURL . "=searchvideo/id" );

	// Viet the loai
	foreach( $category as $key => $value )
	{
		if( $key == 0 ) continue;
		
		$xtpl->assign( 'name', $value['title'] );
		$xtpl->assign( 'url_view_category', $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $value['title'] ) . "&amp;id=" . $key . "&amp;type=category" );

		$xtpl->parse( 'main.cat' );
	}

	$i = 0;
	foreach( $array_new as $row )
	{
		$xtpl->assign( 'ROW', $row );

		if( ++$i % 4 == 0 ) $xtpl->parse( 'main.new.break' );
		$xtpl->parse( 'main.new' );
	}

	$i = 0;
	foreach( $array_hot as $row )
	{
		$xtpl->assign( 'ROW', $row );
		if( ++$i % 4 == 0 ) $xtpl->parse( 'main.hot.break' );
		$xtpl->parse( 'main.hot' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Xem video
function nv_music_viewvideo( $g_array, $array, $array_album, $array_video, $array_singer )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL, $my_head, $main_header_URL;

	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . $array['thumb'] . '" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/video/" . $array['ID'] . "/" . $array['sname'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="227" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';

	$xtpl = new XTemplate( "viewvideo.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'DATA', $array );
	$xtpl->assign( 'base_url', NV_BASE_SITEURL . "modules/" . $module_file . "/data/" );
	$xtpl->assign( 'ads', getADS() );
	$xtpl->assign( 'playerurl', $global_config['site_url'] . "/modules/" . $module_file . "/data/" );
	$xtpl->assign( 'thisurl', $mainURL . "=video" );

	if( $array['checkhit'] >= 20 )
	{
		$xtpl->parse( 'main.hit' );
	}
	
	foreach( $array['listcat'] as $cat )
	{
		$xtpl->assign( 'SUBCAT', $cat );
		$xtpl->parse( 'main.subcat' );
	}
	
	// Thong tin ca si
	if( ! empty( $array_singer ) )
	{
		$xtpl->assign( 'SINGER_INFO', $array_singer );
		$xtpl->parse( 'main.singer_info' );
	}
	
	// Album cung ca si
	if( ! empty( $array_album ) )
	{
		$xtpl->assign( 'SEARCH_ALL_ALBUM', $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $array['singer'] ) . "&amp;id=" . $array['singerid'] . "&amp;type=singer");
		
		foreach( $array_album as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_album.loop' );
		}
		
		$xtpl->parse( 'main.other_album' );
	}
	
	// Video cung ca si
	if( ! empty( $array_video ) )
	{
		$xtpl->assign( 'SEARCH_ALL_VIDEO', $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $array['singer'] ) . "&amp;id=" . $array['singerid'] . "&amp;type=singer");
		
		foreach( $array_video as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.other_video.loop' );
		}
		
		$xtpl->parse( 'main.other_video' );
	}
	
	// Binh luan
	if( ( $setting['who_comment'] == 0 ) and ! defined( 'NV_IS_USER' ) and ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->assign( 'USER_LOGIN', $g_array['user_login'] );
		$xtpl->assign( 'USER_REGISTER', $g_array['user_register'] );
		$xtpl->parse( 'main.nocomment' );
	}
	elseif( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );
	}
	else
	{
		$xtpl->assign( 'USER_NAME', $g_array['name'] );
		$xtpl->assign( 'NO_CHANGE', ( $g_array['name'] == '' ) ? '' : 'readonly="readonly"' );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Danh sach qua tang am nhac
function nv_music_gift( $array, $generate_page )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting;

	$xtpl = new XTemplate( "gift.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	if( empty( $array ) )
	{
		$xtpl->parse( 'empty' );
		return $xtpl->text( 'empty' );
	}

	foreach( $array as $row )
	{
		$row['time'] = nv_date( "d/m/Y h:i:s A", $row['time'] );
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'generate_page', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Trang chu module
function nv_music_main( $array, $array_album, $first_album_data )
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $downURL, $op, $main_header_URL, $nv_Request, $array_op;

	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'DATA', $array );
	$xtpl->assign( 'URL_DOWN', $downURL );
	$xtpl->assign( 'URL_LOAD', $main_header_URL . "=" . ( empty( $array_op ) ? $op : implode( "/", $array_op ) ) . "&load_main_song=" );

	if( empty( $setting['type_main'] ) )
	{
		$xtpl->parse( 'main.type_tab1' );
	}
	else
	{
		$xtpl->parse( 'main.type_tab2' );
	}
	
	$i = 1;
	$j = 0;
	if( ! empty( $array_album ) )
	{
		foreach( $array_album as $album )
		{
			$album['tname1'] = nv_clean60( $album['tname'], 30 );
			$album['casi1'] = nv_clean60( $album['casi'], 30 );
			$album['tname2'] = nv_clean60( $album['tname'], 40 );
			$album['casi2'] = nv_clean60( $album['casi'], 40 );

			$xtpl->assign( 'ALBUM', $album );

			if( ( $i++ ) == 1 )
			{
				foreach( $first_album_data as $song )
				{
					$song['tenthat1'] = nv_clean60( $song['tenthat'], 23 );
					$xtpl->assign( 'SONG', $song );
					$xtpl->parse( 'main.data.first.song' );
				}
				$xtpl->parse( 'main.data.first' );
			}
			else
			{
				if( ++$j % 4 == 0 ) $xtpl->parse( 'main.data.old.break' );
				$xtpl->parse( 'main.data.old' );
			}
		}

		$xtpl->parse( 'main.data' );
		if( $nv_Request->isset_request( 'load_main_song', 'get' ) ) die( $xtpl->text( 'main.data' ) );
	}
	elseif( $nv_Request->isset_request( 'load_main_song', 'get' ) )
	{
		die( "" );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Hien thi bieu tuong cam xuc
function nv_emotion_theme()
{
	global $module_info, $module_file, $lang_module, $lang_global;
	$xtpl = new XTemplate( "emotions.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MTEM', "yahoo" );

	require_once NV_ROOTDIR . "/modules/" . $module_file . "/class/emotions.php";

	$emotions = m_emotions_array();

	foreach( $emotions as $name => $value )
	{
		if( is_array( $value ) ) $value = $value[0];
		$xtpl->assign( 'VALUE', nv_htmlspecialchars( $value ) );
		$xtpl->assign( 'NAME', $name );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Giao dien
// Tim kiem nhanh
function nv_quicksearch_theme( $q, $array_singer, $array_song, $array_album, $array_video, $array_playlist )
{
	global $module_info, $module_file, $lang_module, $lang_global, $main_header_URL;
	
	$xtpl = new XTemplate( "quicksearch.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	// Ket qua ca si
	if( ! empty( $array_singer ) )
	{
		foreach( $array_singer as $singer )
		{
			if( empty( $singer['thumb'] ) ) $singer['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/d-img.png';
			
			$xtpl->assign( 'SINGER', $singer );
			$xtpl->parse( 'main.singer.loop' );
		}
		
		$xtpl->parse( 'main.singer' );
	}

	// Ket qua album
	if( ! empty( $array_album ) )
	{
		foreach( $array_album as $album )
		{			
			if( empty( $album['singer'] ) ) $album['singer'] = $lang_module['unknow'];

			$xtpl->assign( 'ALBUM', $album );
			$xtpl->parse( 'main.album.loop' );
		}
		
		$xtpl->parse( 'main.album' );
	}

	if( ! empty( $array_playlist ) )
	{
		foreach( $array_playlist as $playlist )
		{			
			$playlist['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/randimg/img(' . rand(1, 10) . ').jpg';
			$xtpl->assign( 'PLAYLIST', $playlist );
			$xtpl->parse( 'main.playlist.loop' );
		}
		
		$xtpl->parse( 'main.playlist' );
	}

	if( ! empty( $array_video ) )
	{
		foreach( $array_video as $video )
		{			
			if( empty( $video['singer'] ) ) $video['singer'] = $lang_module['unknow'];

			$xtpl->assign( 'VIDEO', $video );
			$xtpl->parse( 'main.video.loop' );
		}
		
		$xtpl->parse( 'main.video' );
	}

	if( ! empty( $array_song ) )
	{
		foreach( $array_song as $song )
		{
			if( empty( $song['singer'] ) ) $song['singer'] = $lang_module['unknow'];
			
			$xtpl->assign( 'SONG', $song );
			$xtpl->parse( 'main.song.loop' );
		}
		
		$xtpl->parse( 'main.song' );
	}
	
	$xtpl->assign( 'Q', $q );
	$xtpl->assign( 'URL_SEARCH', nv_url_rewrite( $main_header_URL . "=search&where=song&q=" . urlencode( $q ), true ) );
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>