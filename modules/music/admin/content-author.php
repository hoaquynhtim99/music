<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

function nv_check_ok_author( $array )
{
	global $lang_module;

	if( empty( $array['ten'] ) ) return $lang_module['author_error_ten'];
	if( empty( $array['tenthat'] ) ) return $lang_module['author_error_tenthat'];

	return "";
}

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

// Danh dau menu duoc active
$set_active_op = "author";

// Khoi tao
$error = "";
$array_old = $array = array();

// Lay gia tri
$array['ten'] = nv_substr( $nv_Request->get_title( 'ten', 'get,post', '', 1 ), 0, 255);
$array['tenthat'] = nv_substr( $nv_Request->get_title( 'tenthat', 'post', '', 1 ), 0, 255);
$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$array['introduction'] = $nv_Request->get_editor( 'introduction', '', NV_ALLOWED_HTML_TAGS );

// Lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $id == 0 )
{
	$page_title = $lang_module['author_add'];
}
else
{
	$page_title = $lang_module['author_edit'];

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_author WHERE id=" . $id;
	$result = $db->query( $sql );
	$check = $result->rowCount();

	if( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	$row = $result->fetch();

	if( ! $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$array['ten'] = $row['ten'];
		$array['tenthat'] = $row['tenthat'];
		$array['thumb'] = $row['thumb'];
		$array['introduction'] = nv_editor_br2nl( $row['introduction'] );
	}
}

// Sua nhac si
if( ( $nv_Request->get_int( 'edit', 'post', 0 ) ) == 1 )
{
	$error .= nv_check_ok_author( $array );
	$array['introduction'] = nv_editor_nl2br( $array['introduction'] );

	// Kiem tra xem nhac si da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_author WHERE tenthat=" . $db->quote( $array['tenthat'] ) . " AND introduction=" . $db->quote( $array['introduction'] ) . " AND id!=" . $id );
		$exist = $result->fetchColumn();
		
		if( $exist )
		{
			$error = $lang_module['error_exist_author'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_author SET
			ten=" . $db->quote( $array['ten'] ) . ", 
			tenthat=" . $db->quote( $array['tenthat'] ) . ", 
			thumb=" . $db->quote( $array['thumb'] ) . ", 
			introduction=" . $db->quote( $array['introduction'] ) . " 
		WHERE id =" . $id;

		$result = $db->query( $sql );
		if( $result )
		{
			$nv_Cache->delMod( $module_name );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=author" );
			die();
		}
		else
		{
			$array['introduction'] = nv_editor_br2nl( $array['introduction'] );
			$error = $lang_module['error_save'];
		}
	}
}

// Them nhac si
if( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{
	$error .= nv_check_ok_author( $array );
	$array['introduction'] = nv_editor_nl2br( $array['introduction'] );

	// Kiem tra xem ca si da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_author WHERE tenthat=" . $db->quote( $array['tenthat'] ) . " AND introduction=" . $db->quote( $array['introduction'] ) );
		$exist = $result->fetchColumn();
		if( $exist )
		{
			$error = $lang_module['error_exist_author'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_author VALUES ( 
			NULL, 
			" . $db->quote( $array['ten'] ) . ", 
			" . $db->quote( $array['tenthat'] ) . ", 
			" . $db->quote( $array['thumb'] ) . ", 
			" . $db->quote( $array['introduction'] ) . ", 
			0, 0
		)";

		if( $db->insert_id( $sql ) )
		{
			$nv_Cache->delMod( $module_name );
			//$xxx->closeCursor();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=author" );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

if( ! empty( $array['introduction'] ) ) $array['introduction'] = nv_htmlspecialchars( $array['introduction'] );

if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$array['introduction'] = nv_aleditor( 'introduction', '98%', '250px', $array['introduction'] );
}
else
{
	$array['introduction'] = "<textarea style=\"width:98%\" name=\"introduction\" id=\"introduction\" cols=\"20\" rows=\"15\">" . $array['introduction'] . "</textarea>\n";
}

$xtpl = new XTemplate( "content-author.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'IMAGE_DIR', NV_UPLOADS_DIR . "/" . $module_name . "/authorthumb" );
$xtpl->assign( 'DATA', $array );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( $id == 0 )
{
	$xtpl->parse( 'main.add' );
}
else
{
	$xtpl->parse( 'main.edit' );
}

if( empty( $array['ten'] ) )
{
	$xtpl->parse( 'main.get_alias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';