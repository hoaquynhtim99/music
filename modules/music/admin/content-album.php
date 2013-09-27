<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

// Call jquery UI sortable
$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.sortable.min.js\"></script>\n";

// Khoi tao
$contents = "";
$error = "";
$array = $array_old = array();

// Kiem tra du lieu album
function nv_check_ok_album( $array )
{
	global $classMusic;
	if( empty( $array['name'] ) ) return $classMusic->lang('album_error_alias');
	if( empty( $array['tname'] ) ) return $classMusic->lang('album_error_title');
	if( empty( $array['thumb'] ) ) return $classMusic->lang('album_error_thumb');
	return "";
}

// Lay ID neu la sua album
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ! empty( $id ) )
{
	$page_title = $lang_module['edit_album'];
	$row = $classMusic->getalbumbyID( $id );
	
	if( empty( $row ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$array = $array_old = array(
		"name" => $row['name'],
		"tname" => $row['tname'],
		"casi" => $classMusic->string2array( $row['casi'] ),
		"casimoi" => '',
		"thumb" => $row['thumb'],
		"describe" => nv_editor_br2nl( $row['describe'] ),
		"listsong" => $row['listsong'],
	);
}
else
{
	$page_title = $lang_module['add_album'];
	
	$array = array(
		"name" => '',
		"tname" => '',
		"casi" => array(),
		"casimoi" => '',
		"thumb" => '',
		"describe" => '',
		"listsong" => '',
	);
}

if( $nv_Request->isset_request( "submit", "post" ) )
{
	$array['name'] = filter_text_input( 'ten', 'post', '', 1, 255 );
	$array['tname'] = filter_text_input( 'tenthat', 'post', '', 1, 255 );
	$array['casi'] = $nv_Request->get_string( 'casi', 'get,post', 0 );
	$array['casimoi'] = filter_text_input( 'casimoi', 'post', '', 1, 255 );
	$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
	$array['describe'] = $nv_Request->get_string( 'describe', 'post', '' );
	$array['listsong'] = filter_text_input( 'listsong', 'post', '', 1, 255 );
	
	$array['name'] = empty( $array['name'] ) ? change_alias( $array['tname'] ) : change_alias( $array['name'] );
}


if( $array['casimoi'] != '' )
{
	$array['casi'] = newsinger( change_alias( $array['casimoi'] ), $array['casimoi'] );

	if( $array['casi'] === false )
	{
		$array['casi'] = 0;
		$error = $lang_module['error_add_new_singer'];
	}
}

// Sua album
if( ( ( $nv_Request->get_int( 'edit', 'post', 0 ) ) == 1 ) and ( $error == '' ) )
{
	$error .= nv_check_ok_album( $array );

	// Kiem tra album da co chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $array['casi'] . " AND `tname`=" . $db->dbescape( $array['tname'] ) . " AND `id`!=" . $id );
		list( $existalbum ) = $db->sql_fetchrow( $result );
		if( $existalbum )
		{
			$error = $lang_module['error_exist_album'];
		}
	}

	if( empty( $error ) )
	{
		$numsong = 0;
		if( ! empty( $array['listsong'] ) )
		{
			$numsong = explode( ",", $array['listsong'] );
			$numsong = count( $numsong );
		}

		$array['describe'] = ! empty( $array['describe'] ) ? nv_editor_nl2br( $array['describe'] ) : "";

		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET 
			`name`=" . $db->dbescape( $array['name'] ) . ",
			`tname`=" . $db->dbescape( $array['tname'] ) . ",
			`casi`=" . $array['casi'] . ",
			`thumb`=" . $db->dbescape( $array['thumb'] ) . ",
			`numsong`=" . $db->dbescape( $numsong ) . ",
			`describe`=" . $db->dbescape( $array['describe'] ) . ",
			`listsong`=" . $db->dbescape( $array['listsong'] ) . "
		WHERE `id` =" . $id;
		$result = $db->sql_query( $sql );

		if( $result )
		{
			if( ! empty( $array['listsong'] ) )
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $id . " WHERE `id` IN(" . $array['listsong'] . ") AND `album`=0" );
			}

			// Cap nhat lai so album cua ca si
			if( $array_old['casi'] != $array['casi'] )
			{
				updatesinger( $array_old['casi'], 'numalbum', '-1' );
				updatesinger( $array['casi'], 'numalbum', '+1' );
			}

			// Cap nhat lai album cua nhung bai hat bi loai bo khoi album
			if( $array_old['listsong'] != $array['listsong'] )
			{
				$new_song = explode( ",", $array['listsong'] );
				$old_song = explode( ",", $array_old['listsong'] );
				$diff_old_song = array_diff( $old_song, $new_song );
				$diff_new_song = array_diff( $new_song, $old_song );

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

// Them album
if( ( $nv_Request->get_int( 'add', 'post', 0 ) == 1 ) and ( $error == '' ) )
{
	$error .= nv_check_ok_album( $array );

	// Kiem tra album da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $array['casi'] . " `tname`=" . $db->dbescape( $array['tname'] ) );
		list( $existalbum ) = $db->sql_fetchrow( $result );
		if( $existalbum )
		{
			$error = $lang_module['error_exist_album'];
		}
	}

	if( empty( $error ) )
	{
		updatesinger( $array['casi'], 'numalbum', '+1' );

		$numsong = 0;
		if( ! empty( $array['listsong'] ) )
		{
			$numsong = explode( ",", $array['listsong'] );
			$numsong = count( $numsong );
		}

		$array['describe'] = ! empty( $array['describe'] ) ? nv_editor_nl2br( $array['describe'] ) : "";

		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_album` VALUES (
			NULL, 
			" . $db->dbescape( $array['name'] ) . ", 
			" . $db->dbescape( $array['tname'] ) . ", 
			" . $array['casi'] . ", 
			" . $db->dbescape( $array['thumb'] ) . ", 
			0, 
			" . $db->dbescape( $admin_info['username'] ) . ",	
			" . $db->dbescape( $array['describe'] ) . "	,
			1,
			" . $numsong . ",
			" . $db->dbescape( $array['listsong'] ) . ",
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
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $newid . " WHERE `id` IN(" . $array['listsong'] . ") AND `album`=0" );
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

// Lay danh sach bai hat cua album
if( ! empty( $array['listsong'] ) )
{
	$songs = $classMusic->getsongbyID( $classMusic->string2array( $array['listsong'] ) );
	
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
// $xtpl->assign( 'TABLE_CAPTION', $table_caption );
// $xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_UPLOADS_DIR', NV_UPLOADS_DIR );
$xtpl->assign( 'SETTING', $classMusic->setting );
$xtpl->assign( 'LISTSONG', implode( ",", array_keys( $array['listsong'] ) ) );

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

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
