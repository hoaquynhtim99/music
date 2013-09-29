<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

// Danh dau menu duoc active
$set_active_op = "album";

// Call jquery UI sortable, Tipsy
$classMusic->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy' );

// Khoi tao
$error = "";
$array = $array_old = array();

// Lay ID neu la sua album
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ! empty( $id ) )
{
	$row = $classMusic->getalbumbyID( $id );
	
	if( empty( $row ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$array = $array_old = array(
		"name" => $row['name'],
		"tname" => $row['tname'],
		"casi" => $classMusic->string2array( $row['casi'] ),
		"casimoi" => '',
		"thumb" => $row['thumb'],
		"describe" => nv_editor_br2nl( $row['describe'] ),
		"listsong" => $classMusic->string2array( $row['listsong'] ),
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $page_title = $classMusic->lang('edit_album');
}
else
{
	$array = array(
		"name" => '',
		"tname" => '',
		"casi" => array(),
		"casimoi" => '',
		"thumb" => '',
		"describe" => '',
		"listsong" => array(),
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $page_title = $classMusic->lang('add_album');
}

if( $nv_Request->isset_request( "submit", "post" ) )
{
	$array['name'] = filter_text_input( 'ten', 'post', '', 1, 255 );
	$array['tname'] = filter_text_input( 'tenthat', 'post', '', 1, 255 );
	$array['casi'] = filter_text_input( 'casi', 'post', '', 1, 255 );
	$array['casimoi'] = filter_text_input( 'casimoi', 'post', '', 1, 255 );
	$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
	$array['describe'] = nv_editor_filter_textarea( 'describe', '', NV_ALLOWED_HTML_TAGS );
	$array['listsong'] = filter_text_input( 'listsong', 'post', '', 1, 255 );
	
	$array['name'] = empty( $array['name'] ) ? change_alias( $array['tname'] ) : change_alias( $array['name'] );
	
	// Chuyen ca si, cac bai hat tu chuoi thanh mang
	$array['casi'] = $classMusic->string2array( $array['casi'] );
	$array['listsong'] = $classMusic->string2array( $array['listsong'] );

	// Kiem tra loi
	if( empty( $array['name'] ) )
	{
		$error = $classMusic->lang('album_error_alias');
	}
	elseif( empty( $array['tname'] ) )
	{
		$error = $classMusic->lang('album_error_title');
	}
	elseif( empty( $array['thumb'] ) )
	{
		$error = $classMusic->lang('album_error_thumb');
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
	}
	
	if( empty( $error ) )
	{
		// Kiem tra ton tai
		if( $id )
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $db->dbescape( $classMusic->build_query_singer_author( $array['casi'] ) ) . " AND `name`=" . $db->dbescape( $array['name'] ) . " AND `id`!=" . $id;
		}
		else
		{
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $db->dbescape( $classMusic->build_query_singer_author( $array['casi'] ) ) . " AND `name`=" . $db->dbescape( $array['name'] );
		}
		
		$result = $db->sql_query( $sql );
		if( $db->sql_numrows( $result ) )
		{
			$error = $classMusic->lang('error_exist_album');
		}
	}
	
	if( empty( $error ) )
	{
		// Chinh sua mo ta
		$array['describe'] = ! empty( $array['describe'] ) ? nv_editor_nl2br( $array['describe'] ) : "";
		
		// Sua album
		if( $id )
		{
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET 
				`name`=" . $db->dbescape( $array['name'] ) . ",
				`tname`=" . $db->dbescape( $array['tname'] ) . ",
				`casi`=" . $db->dbescape( $classMusic->build_query_singer_author( $array['casi'] ) ) . ",
				`thumb`=" . $db->dbescape( $array['thumb'] ) . ",
				`numsong`=" . sizeof( $array['listsong'] ) . ",
				`describe`=" . $db->dbescape( $array['describe'] ) . ",
				`listsong`=" . $db->dbescape( implode( ",", $array['listsong'] ) ) . "
			WHERE `id` =" . $id;
			$result = $db->sql_query( $sql );

			if( $result )
			{
				if( ! empty( $array['listsong'] ) )
				{
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $id . " WHERE `id` IN(" . implode( ",", $array['listsong'] ) . ") AND `album`=0" );
				}

				// Cap nhat ca si
				$array_singer_update = array_unique( array_filter( array_merge_recursive( $array_old['casi'], $array['casi'] ) ) );
				$classMusic->fix_singer( $array_singer_update );

				$diff_old_song = array_diff( $array_old['listsong'], $array['listsong'] );
				$diff_new_song = array_diff( $array['listsong'], $array_old['listsong'] );

				// Tra va gia tri trong cho bai hat
				if( ! empty( $diff_old_song ) )
				{
					$diff_old_song = implode( ",", $diff_old_song );
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=0 WHERE `id` IN(" . $diff_old_song . ") AND `album`=" . $id );
				}

				// Cap nhat album cho cac bai hat duoc them moi
				if( ! empty( $diff_new_song ) )
				{
					$diff_new_song = implode( ",", $diff_new_song );
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $id . " WHERE `id` IN(" . $diff_new_song . ") AND `album`=0" );
				}

				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album" );
				die();
			}
			else
			{
				$error = $lang_module['error_save'];
			}
		}
		else
		{
			$classMusic->fix_singer( $array['casi'] );

			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_album` VALUES (
				NULL, 
				" . $db->dbescape( $array['name'] ) . ", 
				" . $db->dbescape( $array['tname'] ) . ", 
				" . $db->dbescape( $classMusic->build_query_singer_author( $array['casi'] ) ) . ", 
				" . $db->dbescape( $array['thumb'] ) . ", 
				0, 
				" . $db->dbescape( $admin_info['username'] ) . ",	
				" . $db->dbescape( $array['describe'] ) . "	,
				1,
				" . sizeof( $array['listsong'] ) . ",
				" . $db->dbescape( implode( ",", $array['listsong'] ) ) . ",
				" . NV_CURRENTTIME . ",
				'0-" . NV_CURRENTTIME . "'
			)";

			$newid = $db->sql_query_insert_id( $sql );

			if( $newid )
			{
				$db->sql_freeresult();

				// Cap nhat album cho cac bai hat
				if( ! empty( $array['listsong'] ) )
				{
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $newid . " WHERE `id` IN(" . implode( ",", $array['listsong'] ) . ") AND `album`=0" );
				}

				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album" );
				die();
			}
			else
			{
				$error = $lang_module['error_save'];
			}
		}
	}
}

// Lay danh sach bai hat cua album
if( ! empty( $array['listsong'] ) )
{
	$songs = $classMusic->getsongbyID( $array['listsong'] , true );
	
	$array['listsong'] = array();
	foreach( $songs as $song )
	{
		$array['listsong'][$song['id']] = $song['tenthat'];
	}
}
else
{
	$array['listsong'] = array();
}

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

// Sua lai mo ta album
if ( ! empty( $array['describe'] ) ) $array['describe'] = nv_htmlspecialchars( $array['describe'] );

if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$array['describe'] = nv_aleditor( 'describe', '100%', '250px', $array['describe'] );
}
else
{
	$array['describe'] = "<textarea style=\"width: 100%\" value=\"" . $array['describe'] . "\" name=\"describe\" id=\"describe\" cols=\"20\" rows=\"15\"></textarea>\n";
}

$xtpl = new XTemplate( "content-album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'SETTING', $classMusic->setting );

$xtpl->assign( 'LISTSONG', implode( ",", array_keys( $array['listsong'] ) ) );
$xtpl->assign( 'LISTSINGERS', implode( ",", array_keys( $array['casi'] ) ) );

$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/thumb' );

// Xuat thong bao loi
if( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

// Tu dong tao alias
if( empty( $array['ten'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}

// Xuat cac bai hat
if( ! empty( $array['listsong'] ) )
{
	foreach( $array['listsong'] as $_id => $_tmp )
	{
		$xtpl->assign( 'SONG', array( "id" => $_id, "title" => $_tmp ) );
		$xtpl->parse( 'main.song' );
	}
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

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
