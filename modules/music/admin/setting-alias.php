<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 05/10/2013 09:17 AM
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Call jquery Tipsy
$classMusic->callJqueryPlugin( 'jquery.tipsy' );

// Active
$set_active_op = "globalsetting";

// Tieu de cua trang
$page_title = $classMusic->lang('setting_alias');

// Khoi tao
$array = array(
	'alias_listen_song' => $classMusic->setting['alias_listen_song'],
	'alias_view_album' => $classMusic->setting['alias_view_album'],
	'alias_view_videoclip' => $classMusic->setting['alias_view_videoclip'],
);
$error = '';

// Lay thong tin submit
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['alias_listen_song'] = filter_text_input( 'alias_listen_song', 'post', '', 1, 255 );
	$array['alias_view_album'] = filter_text_input( 'alias_view_album', 'post', '', 1, 255 );
	$array['alias_view_videoclip'] = filter_text_input( 'alias_view_videoclip', 'post', '', 1, 255 );

	// Kiem tra hop le
	foreach( $array as $key => $value )
	{
		if( empty( $value ) or ! preg_match( $global_config['check_op'], $value ) )
		{
			$error = $classMusic->lang('setting_alias_error');
			break;
		}
	}
	
	// Kiem tra giong nhau
	if( sizeof( array_unique( $array ) ) != sizeof( $array ) )
	{
		$error = $classMusic->lang('setting_alias_error_exists');
	}
	
	if( empty( $error ) )
	{
		foreach( $array as $key => $value )
		{
			$sql = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_setting` VALUES ('" . $key . "','" . $value . "')";
			$db->sql_query( $sql );
		}

		nv_del_moduleCache( $module_name );

		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}
}

$xtpl = new XTemplate( "setting-alias.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array );

if( ! empty( $error ) )
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