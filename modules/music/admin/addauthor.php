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

// Khoi tao
$error = "";
$array_old = $array = array();

// Lay gia tri
$array['ten'] = filter_text_input( 'ten', 'get,post', '', 1, 255 );
$array['tenthat'] = filter_text_input( 'tenthat', 'post', '', 1, 255 );
$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$array['introduction'] = nv_editor_filter_textarea( 'introduction', '', NV_ALLOWED_HTML_TAGS );

// Lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $id == 0 )
{
	$page_title = $lang_module['author_add'];
}
else
{
	$page_title = $lang_module['author_edit'];

	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_author` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );

	if( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	$row = $db->sql_fetchrow( $result );

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
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_author` WHERE `tenthat`=" . $db->dbescape( $array['tenthat'] ) . " AND `introduction`=" . $db->dbescape( $array['introduction'] ) . " AND `id`!=" . $id );
		list( $exist ) = $db->sql_fetchrow( $result );
		
		if( $exist )
		{
			$error = $lang_module['error_exist_author'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_author` SET
			`ten`=" . $db->dbescape( $array['ten'] ) . ", 
			`tenthat`=" . $db->dbescape( $array['tenthat'] ) . ", 
			`thumb`=" . $db->dbescape( $array['thumb'] ) . ", 
			`introduction`=" . $db->dbescape( $array['introduction'] ) . " 
		WHERE `id` =" . $id;

		$result = $db->sql_query( $sql );
		if( $result )
		{
			nv_del_moduleCache( $module_name );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=author" );
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
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_author` WHERE `tenthat`=" . $db->dbescape( $array['tenthat'] ) . " AND `introduction`=" . $db->dbescape( $array['introduction'] ) );
		list( $exist ) = $db->sql_fetchrow( $result );
		if( $exist )
		{
			$error = $lang_module['error_exist_author'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_author` VALUES ( 
			NULL, 
			" . $db->dbescape( $array['ten'] ) . ", 
			" . $db->dbescape( $array['tenthat'] ) . ", 
			" . $db->dbescape( $array['thumb'] ) . ", 
			" . $db->dbescape( $array['introduction'] ) . ", 
			0, 0
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			nv_del_moduleCache( $module_name );
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=author" );
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
