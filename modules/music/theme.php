<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:41 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

/**
 * nv_music_listenone()
 * 
 * @param mixed $gdata
 * @param mixed $sdata
 * @param mixed $cdata
 * @param mixed $ldata
 * @return
 */
function nv_music_listenone ( $gdata, $sdata, $cdata, $ldata )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $my_head, $main_header_URL;
    
	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/logo.png" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite ( $main_header_URL . "=creatlinksong/song/" . $sdata['song_id'] . "/" . $sdata['song_sname'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="84" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';

	$xtpl = new XTemplate( "listenone.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	$xtpl->assign( 'SDATA', $sdata );
	$xtpl->assign( 'GDATA', $gdata );
	$xtpl->assign( 'CDATA', $cdata );
	
	if ( $cdata['checkhit'] >= 20 )
	{
		$xtpl->parse( 'main.hit' );
	}

	if ( $ldata['number'] >= 1 )
	{
		$i = 1 ;
		foreach ( $ldata['data'] as $rowlyric )
		{	
			if ( ( $ldata['number'] > 1 ) && ( $i < $ldata['number'] ) )
			{
				$nextdiv = $i + 1 ;
				$xtpl->assign( 'nextdiv', $nextdiv );
				$xtpl->parse( 'main.lyric.next' );			
			}
			
			if ( $i > 1 )
			{
				$prevdiv = $i - 1 ;
				$xtpl->assign( 'prevdiv', $prevdiv );
				$xtpl->parse( 'main.lyric.prev' );					
			}

			$xtpl->assign( 'LYRIC_DATA', $rowlyric );		
			
			$xtpl->assign( 'thisdiv', $i );
			$xtpl->parse( 'main.lyric' );
			$i ++ ;
		}
	}
	else
	{
		$xtpl->parse( 'main.nolyric' );
	}

	// Send gift
	if ( ( $setting['who_gift'] == 0 ) &&  ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nogift' );
	}
	elseif ( $setting['who_gift'] == 2 )
	{
		$xtpl->parse( 'main.stopgift' );	
	}
	else
	{
		$xtpl->parse( 'main.gift' );
	}
	
	// Send lyric
	if ( ( $setting['who_lyric'] == 0 ) &&  ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.noaccesslyric' );
	}
	elseif ( $setting['who_lyric'] == 2 )
	{
		$xtpl->parse( 'main.stoplyric' );	
	}
	else
	{
		$xtpl->parse( 'main.accesslyric' );
	}

	// Comment
	if ( ( $setting['who_comment'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nocomment' );
	}
	elseif ( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );	
	}
	else
	{
		require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
		$xtpl->assign( 'EMOTIONS', show_emotions() );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_music_listen_playlist()
 * 
 * @param mixed $gdata
 * @param mixed $sdata
 * @return
 */
function nv_music_listen_playlist ( $gdata, $sdata )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $main_header_URL, $my_head;

	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/randimg/img(' . rand( 1, 10 ) . ').jpg" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite ( $main_header_URL . "=creatlinksong/playlist/" . $gdata['id'] . "/" . $gdata['playlist_alias'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="130" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';
    
	$xtpl = new XTemplate( "listenuserlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	
	$xtpl->assign( 'GDATA', $gdata );

	foreach ( $sdata as $song )
	{
		$xtpl->assign( 'SDATA', $song );
		$xtpl->parse( 'main.song' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_music_album()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @return
 */
function nv_music_album ( $g_array, $array )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;
    
	$xtpl = new XTemplate( "album.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	
	// active span
	if ( $g_array['type'] == 'id' )
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

/**
 * nv_music_allplaylist()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @return
 */
function nv_music_allplaylist ( $g_array, $array )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;
    
	$xtpl = new XTemplate( "allplaylist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	
	// active span
	if ( $g_array['type'] == 'id' )
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

/**
 * nv_music_showcomment()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @return
 */
function nv_music_showcomment( $g_array, $array )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;
    
	$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	
	// phan trang binh luan
	if (  $g_array['num'] > ( $g_array['page'] + 8 ) )
	{
		$next = $g_array['page'] + 8 ;
		$xtpl->assign( 'next', $next );
		$xtpl->parse( 'main.next' );			
	}
	if ( $g_array['page'] > 0 )
	{
		$prev = $g_array['page'] - 8 ;
		$xtpl->assign( 'prev', $prev );
		$xtpl->parse( 'main.prev' );					
	}
	
	foreach( $array as $row )
	{
		$row['avatar'] = empty( $row['avatar'] ) ? NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/default.jpg" : NV_BASE_SITEURL . $row['avatar'];
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_music_creatalbum()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @return
 */
function nv_music_creatalbum( $g_array, $array )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;
    
	$xtpl = new XTemplate( "creatalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'img',  NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10 ) . ").jpg" );
	
	// khong duoc vao
	if( empty( $g_array['userid'] ) )
	{
		$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" );
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

/**
 * nv_music_editplaylist()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @param mixed $row
 * @return
 */
function nv_music_editplaylist( $g_array, $array, $row )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL;
    
	$xtpl = new XTemplate( "editplaylist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'ACTION', $mainURL . "=editplaylist/" . $g_array['id'] );
	$xtpl->assign( 'img',  NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/randimg/img(" . rand( 1, 10) . ").jpg" );
	$xtpl->assign( 'INFO', $row );
	
	if ( $g_array['ok'] == 1 )
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

/**
 * nv_music_listenlist()
 * 
 * @param mixed $g_array
 * @param mixed $album_array
 * @param mixed $song_array
 * @return
 */
function nv_music_listenlist( $g_array, $album_array, $song_array )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL, $my_head, $main_header_URL;
	// My Head
	$my_head .= '
	<link rel="image_src" href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/logo.png" />
	<link rel="video_src" href="' . $global_config['site_url'] . '/modules/' . $module_file . '/data/player.swf?playlistfile=' . NV_MY_DOMAIN . nv_url_rewrite ( $main_header_URL . "=creatlinksong/album/" . $g_array['id'] . "/" . $g_array['sname'], true ) . '" />
	<meta name="video_width" content="360" />
	<meta name="video_height" content="100" />
	<meta name="video_type" content="application/x-shockwave-flash" />
	';
    
	$xtpl = new XTemplate( "listenlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	$xtpl->assign( 'playerurl', $global_config['site_url'] ."/modules/" . $module_file . "/data/" );
	$xtpl->assign( 'base_url', NV_BASE_SITEURL . "modules/" . $module_file . "/data/" );
	$xtpl->assign( 'ads', getADS() );
	$xtpl->assign( 'ALBUM', $album_array );
	
	$xtpl->assign( 'USER_NAME', $g_array['name'] );
	$xtpl->assign( 'NO_CHANGE', ( $g_array['name'] == '' ) ? '' : 'readonly="readonly"' );
	
	$xtpl->assign( 'ID', $g_array['id'] );
	
	$i = 1;
	foreach( $song_array as $song )
	{
		$song['stt'] = $i;
		$xtpl->assign( 'SONG', $song );
		$xtpl->parse( 'main.song' );
		$i ++;
	}
	
	// binh luan
	if ( ( $setting['who_comment'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->assign( 'USER_LOGIN', $g_array['user_login'] );
		$xtpl->assign( 'USER_REGISTER', $g_array['user_register'] );		
		$xtpl->parse( 'main.nocomment' );
	}
	elseif ( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );	
	}
	else
	{
		require_once NV_ROOTDIR . "/modules/" . $module_file . '/class/emotions.php';
		$xtpl->assign( 'EMOTIONS', show_emotions() );
		$xtpl->assign( 'USER_NAME', $g_array['name'] );
		$xtpl->assign( 'NO_CHANGE', ( $g_array['name'] == '' )? '' : 'readonly="readonly"' );
		$xtpl->parse( 'main.comment' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_music_managersong()
 * 
 * @param mixed $g_array
 * @param mixed $array
 * @param mixed $row
 * @return
 */
function nv_music_managersong( $g_array, $array_song, $data_song )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global, $mainURL;
    
	$xtpl = new XTemplate( "managersong.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GDATA', $g_array );
	
	// khong duoc vao
	if( $g_array['userid'] == 0 )
	{
		$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" );
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
			
			if ( $data_song['resuit'] )
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
				$song['size'] = round ( ( $song['size']/1024/1024 ), 2 );
				$song['duration'] = (int)( $song['duration'] / 60 ) . ":" . $song['duration'] % 60;
				
				$xtpl->assign( 'SONG', $song );
				if( $song['active'] == 0 )
				{
					$xtpl->parse( 'main.access.song.noacept' );
				}
				else
				{
					$xtpl->parse( 'main.access.song.acept' );
				}
				$xtpl->parse( 'main.access.song' );
			}
		}
		$xtpl->parse( 'main.access' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>