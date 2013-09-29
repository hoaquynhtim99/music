<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Call jquery UI sortable, Tipsy
$classMusic->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy', 'jquery.autosize' );

// Tieu de trang
$page_title = $classMusic->lang('add_song');

// Lay ID bai hat
$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

if( $id )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
	
	if ( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	$row = $db->sql_fetchrow( $result );
	
	$array_old = $array = array(
		"ten" => $row['ten'],
		"tenthat" => $row['tenthat'],
		"casi" => $classMusic->string2array( $row['casi'] ),
		"nhacsi" => $classMusic->string2array( $row['nhacsi'] ),
		"album" => $row['album'],
		"theloai" => $row['theloai'],
		"listcat" => $classMusic->string2array( $row['listcat'] ),
		"duongdan" => $row['duongdan'],
		"upboi" => $row['upboi'],
		"bitrate" => $row['bitrate'],
		"size" => $row['size'],
		"duration" => $row['duration'],
		"userid" => $row['userid'],
		"is_official" => $row['is_official'],
		"lyric" => '',
		"lyric_id" => 0,
		"server" => $row['server'],
	);
	
	// Lay loi bai hat
	$sql = "SELECT `id`, `body` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $id . " ORDER BY `dt` ASC LIMIT 0,1";
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) )
	{
		list( $lyric_id, $lyric_body ) = $db->sql_fetchrow( $result );
		$array_old['lyric'] = $array['lyric'] = nv_br2nl( $lyric_body );
		$array_old['lyric_id'] = $array['lyric_id'] = $lyric_id;
	}
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $classMusic->lang('edit_song');
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $classMusic->lang('add_song');
	
	$array = array(
		"ten" => '',
		"tenthat" => '',
		"casi" => array(),
		"nhacsi" => array(),
		"album" => 0,
		"theloai" => 0,
		"listcat" => array(),
		"duongdan" => '',
		"upboi" => '',
		"bitrate" => 0,
		"size" => 0,
		"duration" => 0,
		"userid" => 0,
		"is_official" => 1,
		"lyric" => '',
		"lyric_id" => 0,
		"server" => 0,
	);
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['ten'] = filter_text_input( 'ten', 'post', '', 1, 255 );
	$array['tenthat'] = filter_text_input( 'tenthat', 'post', '', 1, 255 );
	$array['casi'] = filter_text_input( 'casi', 'post', '', 1, 255 );
	$array['casimoi'] = filter_text_input( 'casimoi', 'post', '', 1, 255 );
	$array['nhacsi'] = filter_text_input( 'nhacsi', 'post', '', 1, 255 );
	$array['nhacsimoi'] = filter_text_input( 'nhacsimoi', 'post', '', 1, 255 );
	$array['album'] = $nv_Request->get_int( 'album', 'post', 0 );
	$array['theloai'] = $nv_Request->get_int( 'theloai', 'post', 0 );
	$array['listcat'] = $nv_Request->get_typed_array( 'listcat', 'post', 'int' );
	$array['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
	$array['upboi'] = filter_text_input( 'upboi', 'post', '', 1, 255 );
	$array['bitrate'] = $nv_Request->get_int( 'bitrate', 'post', 0 );
	$array['size'] = $nv_Request->get_int( 'size', 'post', 0 );
	$array['duration'] = $nv_Request->get_int( 'duration', 'post', 0 );
	$array['is_official'] = $nv_Request->get_int( 'is_official', 'post', 0 );
	$array['lyric'] = filter_text_textarea( 'lyric', '', NV_ALLOWED_HTML_TAGS );

	// Chuan hoa alias
	$array['ten'] = empty( $array['ten'] ) ? change_alias( $array['tenthat'] ) : change_alias( $array['ten'] );

	// Chuyen ca si, nhac si tu chuoi thanh mang
	$array['casi'] = $classMusic->string2array( $array['casi'] );
	$array['nhacsi'] = $classMusic->string2array( $array['nhacsi'] );
	
	// Kiem tra loi
	if( empty( $array['ten'] ) )
	{
		$error = $classMusic->lang('song_error_ten');
	}
	elseif( empty( $array['tenthat'] ) )
	{
		$error = $classMusic->lang('song_error_tenthat');
	}
	elseif( empty( $array['duongdan'] ) )
	{
		$error = $classMusic->lang('song_error_duongdan');
	}
	
	if( empty( $error ) )
	{
		// Them ca si moi
		if( $array['casimoi'] != '' )
		{
			$singer_exists = $classMusic->getsingerbyName( $array['casimoi'] );
			if( $singer_exists )
			{
				$array['casi'][] = $singer_exists['id'];
			}
			else
			{
				$new_singer = $classMusic->newsinger( change_alias( $array['casimoi'] ), $array['casimoi'] );
				if( $new_singer  === false )
				{
					$error = $classMusic->lang('error_add_new_singer');
				}
				else
				{
					$array['casi'][] = $new_singer;
				}
			}
		}
		
		// Them nhac si moi
		if( $array['nhacsimoi'] != '' )
		{
			$author_exists = $classMusic->getauthorbyName( $array['nhacsimoi'] );
			if( $author_exists )
			{
				$array['nhacsi'][] = $author_exists['id'];
			}
			else
			{
				$new_author = $classMusic->newauthor( change_alias( $array['nhacsimoi'] ), $array['nhacsimoi'] );
				if( $new_author  === false )
				{
					$error = $classMusic->lang('error_add_new_author');
				}
				else
				{
					$array['nhacsi'][] = $new_author;
				}
			}
		}
		
	}
	
	if( empty( $error ) )
	{
		if( $id )
		{
			$check_url = $classMusic->creatURL( $array['duongdan'] );

			$array['duongdan'] = $check_url['duongdan'];
			$array['server'] = $check_url['server'];
			$array['id'] = $id;
			$array['upboi'] = $admin_info['username'];
			$array['userid'] = $admin_info['userid'];

			$check = $classMusic->edit_song( $array_old, $array );

			if( $check )
			{
				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
				die();
			}
			else
			{
				$error = $classMusic->lang('error_save');
			}
		}
		else
		{
			$hit = "0-" . NV_CURRENTTIME;
			$check_url = $classMusic->creatURL( $array['duongdan'] );
			$duongdan = $check_url['duongdan'];
			$server = $check_url['server'];

			$array['duongdan'] = $duongdan;
			$array['server'] = $server;
			$array['upboi'] = $admin_info['username'];
			$array['userid'] = $admin_info['userid'];
			$array['hit'] = $hit;

			$result_song_id = $classMusic->new_song( $array );

			if( $result_song_id )
			{
				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
				die();
			}
			else
			{
				$error = $classMusic->lang('error_save');
			}
		}
	}
}

if ( ! empty( $array['lyric'] ) ) $array['lyric'] = nv_htmlspecialchars( $array['lyric'] );
$array['is_official'] = $array['is_official'] ? " checked=\"checked\"" : "";
$array['duongdan'] = $classMusic->admin_outputURL( $array['server'], $array['duongdan'] );

// Lay danh sach ca si
if( ! empty( $array['casi'] ) )
{
	$singers = $classMusic->getsingerbyID( $array['casi'], true );
	
	$array['casi'] = array();
	foreach( $singers as $singer )
	{
		$array['casi'][$singer['id']] = $singer['tenthat'];
	}
}
else
{
	$array['casi'] = array();
}

// Lay danh sach nhac si
if( ! empty( $array['nhacsi'] ) )
{
	$authors = $classMusic->getauthorbyID( $array['nhacsi'], true );
	
	$array['nhacsi'] = array();
	foreach( $authors as $author )
	{
		$array['nhacsi'][$author['id']] = $author['tenthat'];
	}
}
else
{
	$array['nhacsi'] = array();
}

$xtpl = new XTemplate( "content-song.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'SETTING', $classMusic->setting );

$xtpl->assign( 'FILE_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/' . $classMusic->setting['root_contain'] );
$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );

$xtpl->assign( 'LISTSINGERS', implode( ",", array_keys( $array['casi'] ) ) );
$xtpl->assign( 'LISTAUTHORS', implode( ",", array_keys( $array['nhacsi'] ) ) );

// Xuat the loai bai hat
$global_array_cat_song = $classMusic->get_category();
foreach( $global_array_cat_song as $theloai )
{
	$theloai['selected'] = $array['theloai'] == $theloai['id'] ? " selected=\"selected\"" : "";
	$theloai['disabled'] = $array['theloai'] == $theloai['id'] ? " disabled=\"disabled\"" : "";
	$theloai['checked'] = in_array( $theloai['id'], $array['listcat'] ) ? " checked=\"checked\"" : "";
	
	$xtpl->assign( 'THELOAI', $theloai );
	
	$xtpl->parse( 'main.theloai' );
	
	if( $theloai['id'] )
	{
		$xtpl->parse( 'main.listcat' );
	}
}

// Xuat thong bao loi
if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

// Tao alias thu cong
if( empty( $array['ten'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}

// Xuat ca si
if( ! empty( $array['casi'] ) )
{
	foreach( $array['casi'] as $_id => $_tmp )
	{
		$xtpl->assign( 'SINGER', array( "id" => $_id, "title" => $_tmp ) );
		$xtpl->parse( 'main.singer' );
	}
}

// Xuat nhac si
if( ! empty( $array['nhacsi'] ) )
{
	foreach( $array['nhacsi'] as $_id => $_tmp )
	{
		$xtpl->assign( 'AUTHOR', array( "id" => $_id, "title" => $_tmp ) );
		$xtpl->parse( 'main.author' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>