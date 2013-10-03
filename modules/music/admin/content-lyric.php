<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2010 Freeware
 * @Createdate 29-12-2010 18:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

// Danh dau menu duoc active
$set_active_op = "lyric";

// Call jquery Tipsy
$classMusic->callJqueryPlugin( 'jquery.tipsy' );

// Khoi tao
$error = "";
$array = $array_old = array();

// Lay ID lyric
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ! empty( $id ) )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $result );
	
	if( $db->sql_numrows( $result ) != 1 ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$array = $array_old = array(
		"user" => $row['user'],
		"body" => nv_editor_br2nl( $row['body'] ),
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $page_title = $classMusic->lang('edit_lyric');
}
else
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

if( $nv_Request->isset_request( "submit", "post" ) )
{
	$array['user'] = filter_text_input( 'user', 'post', '', 1, 255 );
	$array['body'] = nv_editor_filter_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );
	
	// Kiem tra loi
	if( empty( $array['user'] ) )
	{
		$error = $classMusic->lang('lyric_error_name');
	}
	elseif( empty( $array['body'] ) )
	{
		$error = $classMusic->lang('lyric_error_body');
	}
	else
	{
		$array['body'] = nv_editor_nl2br( $array['body'] );
		
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` SET
			`user`=" . $db->dbescape( $array['user'] ) . ",
			`body`=" . $db->dbescape( $array['body'] ) . "
		WHERE `id` =" . $id;

		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=lyric" );
			die();
		}
		else
		{
			$db->sql_freeresult();
			$error = $classMusic->lang('error_save');
		}
	}
}

// Sua lai loi bai hat
if ( ! empty( $array['body'] ) ) $array['body'] = nv_htmlspecialchars( $array['body'] );

if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$array['body'] = nv_aleditor( 'body', '100%', '350px', $array['body'] );
}
else
{
	$array['body'] = "<textarea style=\"width: 100%\" value=\"" . $array['body'] . "\" name=\"body\" id=\"body\" cols=\"20\" rows=\"20\"></textarea>\n";
}

$xtpl = new XTemplate( "content-lyric.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );

// Xuat thong bao loi
if( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>