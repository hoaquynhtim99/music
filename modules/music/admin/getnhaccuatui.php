<?php

/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

$page_title = $lang_module['nct_title'];

$all_album = getallalbum( );
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
	//$array['upboi'] = filter_text_input( 'upboi', 'post', 0 );
	$array['upboi'] = $admin_info['username'];
		
	$array['alias'] = ( $array['alias'] == "" ) ? change_alias( $array['title'] ) : change_alias( $array['alias'] );
		
	foreach ( $array['link'] as $link )
	{

		$array_meta_tag = get_meta_tags( $link );
		
		$array['keywords'] = $array_meta_tag['keywords']? $array_meta_tag['keywords'] : "";
		$array['keywords'] = explode ( ",", $array['keywords'] );
		$array['keywords'] = array_map ( "trim", $array['keywords'] );

		$title = ! empty ( $array['keywords'][0] ) ? $array['keywords'][0] : "";
		$alias = ! empty ( $title ) ? change_alias( $title ) : "";
		
		$singer = ! empty ( $array['keywords'][1] ) ? $array['keywords'][1] : "ns";
		
		if ( ! empty ( $title ) )
		{
			if ( ! in_array ( $singer, $all_singer ) )
			{
				newsinger( change_alias ( $singer ), $singer );
			}
			
			$hit = "0-" . NV_CURRENTTIME;
			$check_url = creatURL ( $link );
			$data = $check_url['duongdan'];
			$server = $check_url['server'];
			
			// update so bai hat
			updatesinger( change_alias ( $singer ), 'numsong', '+1' );
			updatealbum( $array['album'], '+1' );
			
			$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` 
			(
				`id`, `ten`, `tenthat`, `casi`, `nhacsi`, `album`, `theloai`, `duongdan`, `upboi`, `numview`, `active`, `bitrate`, `size`, `duration`, `server`, `userid`, `dt`, `binhchon`, `hit`
			) 
			VALUES 
			( 
				NULL, 
				" . $db->dbescape( $alias ) . ", 
				" . $db->dbescape( $title ) . ", 
				" . $db->dbescape( change_alias ( $singer ) ) . ", 
				" . $db->dbescape( "na" ) . ", 
				" . $db->dbescape( $array['album'] ) . ", 
				" . $db->dbescape( $array['theloai'] ) . ", 
				" . $db->dbescape( $data )  . ", 
				" . $db->dbescape( $array['upboi'] ) . " ,
				0,
				1,
				" . $db->dbescape( 0 ) . " ,
				" . $db->dbescape( 0 ) . " ,
				" . $db->dbescape( 0 ) . ",
				" . $server . ",
				" . $admin_info['userid'] . ",
				UNIX_TIMESTAMP(),
				0,
				" . $db->dbescape( $hit ) . "
			)"; 
			
			if ( $db->sql_query_insert_id( $query ) )
			{
				$db->sql_freeresult();
			}
		}
	}
	
	nv_insert_logs( NV_LANG_DATA, $module_name, "Add song from nhaccuatui" , "List song", $admin_info['userid'] );
	nv_del_moduleCache( $module_name );
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	exit();
}
else
{
	$array['album'] = "NO";
	$array['theloai'] = "NO";
}

$xtpl = new XTemplate( "nhaccuatui.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $page_title );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );

foreach ( $all_album as $name => $title )
{
	$album_selected = ( $array['album'] == $name ) ? " selected=\"selected\"" : "";
	
	$xtpl->assign( 'album_name', $name );
	$xtpl->assign( 'album_title', $title );
	$xtpl->assign( 'album_selected', $album_selected );
	$xtpl->parse( 'main.album' );
}

foreach ( $all_cat as $id => $cat )
{
	$selected = ( $array['theloai'] == $id ) ? " selected=\"selected\"" : "";
	
	$xtpl->assign( 'catid', $id );
	$xtpl->assign( 'cat_title', $cat );
	$xtpl->assign( 'selected', $selected );
	$xtpl->parse( 'main.catid' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>