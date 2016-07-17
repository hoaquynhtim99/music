<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Call jquery UI sortable, Tipsy
$classMusic->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy' );

// Danh dau menu duoc active
$set_active_op = "videoclip";

// Khoi tao
$error = "";
$array = $array_old = array();

// Lay ID videoclip
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( $id )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id=" . $id;
	$result = $db->query( $sql );
	$check = $result->rowCount();
	
	if ( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	$row = $result->fetch();
	
	$array_old = $array = array(
		"name" => $row['name'],
		"tname" => $row['tname'],
		"casi" => $classMusic->string2array( $row['casi'] ),
		"nhacsi" => $classMusic->string2array( $row['nhacsi'] ),
		"theloai" => $row['theloai'],
		"listcat" => $classMusic->string2array( $row['listcat'] ),
		"duongdan" => $row['duongdan'],
		"thumb" => $row['thumb'],
		"server" => $row['server'],
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $page_title = $classMusic->lang('video_edit');
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $page_title = $classMusic->lang('video_add');
	
	$array = array(
		"name" => '',
		"tname" => '',
		"casi" => array(),
		"nhacsi" => array(),
		"theloai" => 0,
		"listcat" => array(),
		"duongdan" => '',
		"thumb" => '',
		"server" => 0,
	);
}

// Xu ly khi submit form
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['name'] = nv_substr( $nv_Request->get_title( 'name', 'post', '', 1 ), 0, 255);
	$array['tname'] = nv_substr( $nv_Request->get_title( 'tname', 'post', '', 1 ), 0, 255);
	$array['casi'] = nv_substr( $nv_Request->get_title( 'casi', 'post', '', 1 ), 0, 255);
	$array['casimoi'] = nv_substr( $nv_Request->get_title( 'casimoi', 'post', '', 1 ), 0, 255);
	$array['nhacsi'] = nv_substr( $nv_Request->get_title( 'nhacsi', 'post', '', 1 ), 0, 255);
	$array['nhacsimoi'] = nv_substr( $nv_Request->get_title( 'nhacsimoi', 'post', '', 1 ), 0, 255);
	$array['theloai'] = $nv_Request->get_int( 'theloai', 'post', 0 );
	$array['listcat'] = $nv_Request->get_typed_array( 'listcat', 'post', 'int' );
	$array['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
	$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
	
	// Chuan hoa alias
	$array['name'] = empty( $array['name'] ) ? change_alias( $array['tname'] ) : change_alias( $array['name'] );

	// Chuyen ca si, nhac si tu chuoi thanh mang
	$array['casi'] = $classMusic->string2array( $array['casi'] );
	$array['nhacsi'] = $classMusic->string2array( $array['nhacsi'] );
	
	// Kiem tra loi
	if( empty( $array['tname'] ) )
	{
		$error = $classMusic->lang('video_error_tname');
	}
	elseif( empty( $array['name'] ) )
	{
		$error = $classMusic->lang('video_error_name');
	}
	elseif( empty( $array['duongdan'] ) )
	{
		$error = $classMusic->lang('video_error_duongdan');
	}
	elseif( empty( $array['thumb'] ) )
	{
		$error = $classMusic->lang('video_error_thumb');
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
		
		// Kiem tra ton tai
		if( empty( $error ) )
		{
			if( $id )
			{
				$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE casi=" . $db->quote( $classMusic->build_query_singer_author( $array['casi'] ) ) . " AND tname=" . $db->quote( $array['tname'] ) . " AND id!=" . $id;
			}
			else
			{
				$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE casi=" . $db->quote( $classMusic->build_query_singer_author( $array['casi'] ) ) . " AND tname=" . $db->quote( $array['tname'] );
			}
			
			$result = $db->query( $sql );
			$exist = $result->fetchColumn();
			if( $exist )
			{
				$error = $classMusic->lang('error_exist_video');
			}
		}
		
		if( empty( $error ) )
		{
			if( ! empty( $id ) )
			{
				$check_url = $classMusic->creatURL( $array['duongdan'] );
				$array['duongdan'] = $check_url['duongdan'];
				$array['server'] = $check_url['server'];

				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_video SET 
					name=" . $db->quote( $array['name'] ) . ", 
					tname=" . $db->quote( $array['tname'] ) . ", 
					casi=" . $db->quote( $classMusic->build_query_singer_author( $array['casi'] ) ) . ", 
					nhacsi=" . $db->quote( $classMusic->build_query_singer_author( $array['nhacsi'] ) ) . ", 
					theloai=" . $db->quote( $array['theloai'] ) . ", 
					listcat=" . $db->quote( $classMusic->build_query_singer_author( $array['listcat'] ) ) . ", 
					duongdan=" . $db->quote( $array['duongdan'] ) . ", 
					thumb=" . $db->quote( $array['thumb'] ) . ", 
					server=" . $array['server'] . "
				WHERE id=" . $id;

				if( $db->query( $sql ) )
				{
					// Cap nhat chu de
					$array_cat_update = array_unique( array_filter( array_merge_recursive( $array_old['listcat'], array( $array_old['theloai'] ), $array['listcat'], array( $array['theloai'] ) ) ) );
					$classMusic->fix_cat_video( $array_cat_update );

					// Cap nhat ca si
					$array_singer_update = array_unique( array_filter( array_merge_recursive( $array_old['casi'], $array['casi'] ) ) );
					$classMusic->fix_singer( $array_singer_update );
					
					// Cap nhat nhac si
					$array_author_update = array_unique( array_filter( array_merge_recursive( $array_old['nhacsi'], $array['nhacsi'] ) ) );
					$classMusic->fix_author( $array_author_update );

					$nv_Cache->delMod( $module_name );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" );
					die();
				}
				else
				{
					$error = $classMusic->lang('error_save');
				}
			}
			else
			{
				$check_url = $classMusic->creatURL( $array['duongdan'] );

				$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_video VALUES (
					NULL, 
					" . $db->quote( $array['name'] ) . ", 
					" . $db->quote( $array['tname'] ) . ", 
					" . $db->quote( $classMusic->build_query_singer_author( $array['casi'] ) ) . ", 
					" . $db->quote( $classMusic->build_query_singer_author( $array['nhacsi'] ) ) . ", 
					" . $db->quote( $array['theloai'] ) . ", 
					" . $db->quote( $classMusic->build_query_singer_author( $array['listcat'] ) ) . ",
					" . $db->quote( $check_url['duongdan'] ) . ", 
					" . $db->quote( $array['thumb'] ) . " ,
					0,
					1,
					" . NV_CURRENTTIME . ",
					" . $check_url['server'] . ",
					0,
					" . $db->quote( "0-" . NV_CURRENTTIME ) . "			
				)";

				if( $db->insert_id( $sql ) )
				{
					$classMusic->fix_cat_video( array_unique( array_filter( array_merge_recursive( array( $array['theloai'] ), $array['listcat'] ) ) ) );
					$classMusic->fix_singer( $array['casi'] );
					$classMusic->fix_author( $array['nhacsi'] );

					$nv_Cache->delMod( $module_name );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" );
					die();
				}
				else
				{
					$error = $classMusic->lang('error_save');
				}
			}
		}
	}
}

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

$xtpl = new XTemplate( "content-videoclip.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );

$xtpl->assign( 'FILE_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/' . $classMusic->setting['root_contain'] . '/video' );
$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/clipthumb' );

$xtpl->assign( 'LISTSINGERS', implode( ",", array_keys( $array['casi'] ) ) );
$xtpl->assign( 'LISTAUTHORS', implode( ",", array_keys( $array['nhacsi'] ) ) );

// Xuat the loai videoclip
$global_array_cat_song = $classMusic->get_videocategory();
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

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';