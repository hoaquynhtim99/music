<?php

/**
 * @Project NUKEVIET MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

$page_title = $lang_module['nhacvui_get'];

$all_singer = getallsinger( );
$all_cat = get_category( );
if ( empty ( $all_cat ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category" ) ;  
	die();	
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array['link'] = $nv_Request->get_typed_array( 'song', 'post', 'string' );
	$array['album'] = filter_text_input( 'album', 'post', '' );
	$array['theloai'] = $nv_Request->get_int( 'theloai', 'post', 0 );
	$array['listcat'] = $nv_Request->get_typed_array( 'listcat', 'post', 'int' );

	$list_song = array();	

	foreach ( $array['link'] as $link )
	{
		$data = nv_get_URL_content( $link );

		$title = "";
		$singer = "ns";
		$author = "na";
		
		$_title = explode( '<span class="orgranText12">', $data );
		if( isset( $_title[1] ) )
		{
			$_title = explode( '</span>', $_title[1] );
			$title = trim( $_title[0] );
		}
		
		if( ! empty( $title ) )
		{
			$_title = explode( '<h3 class="nghenhac-baihat">Ca sĩ: ', $data );
			if( isset( $_title[1] ) )
			{
				$_title = explode( '|', $_title[1] );
				$_title = trim( $_title[0] );
				$_title = strip_tags( $_title );
				$singer = empty( $_title ) ? "ns" : $_title;
			}
		}
		
		if ( ! empty ( $title ) )
		{
			if ( ! in_array ( $singer, $all_singer ) and ( $singer != 'ns' ) and ! empty( $singer ) )
			{
				newsinger( change_alias ( $singer ), $singer );
			}
			
			$check_url = creatURL ( $link );
			
			$array_data['ten'] = change_alias ( $title );
			$array_data['tenthat'] = $title;
			$array_data['casi'] = change_alias ( $singer );
			$array_data['album'] = $array['album'];
			$array_data['theloai'] = $array['theloai'];
			$array_data['listcat'] = $array['listcat'];
			$array_data['data'] = $check_url['duongdan'];
			$array_data['username'] = $admin_info['username'];
			$array_data['server'] = $check_url['server'];
			$array_data['userid'] = $admin_info['userid'];

			$result_song_id = nvm_new_song( $array_data );

			if ( $result_song_id )
			{
				$list_song[] = $song_id;
				$db->sql_freeresult();
			}
		}
	}
		
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['nhacvui_get'], "List song", $admin_info['userid'] );
	nv_del_moduleCache( $module_name );
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	exit();
}
else
{
	$array['album'] = "";
	$array['theloai'] = "";
}

$xtpl = new XTemplate( "nhaccuatui.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $page_title );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );

foreach ( $all_cat as $id => $cat )
{
	$xtpl->assign( 'catid', $id );
	$xtpl->assign( 'cat_title', $cat['title'] );
	$xtpl->assign( 'selected', ( $array['theloai'] == $id ) ? " selected=\"selected\"" : "" );
	$xtpl->parse( 'main.catid' );
	$xtpl->parse( 'main.listcat' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>