<?php

/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

$page_title = $lang_module['zing_title'];


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
	$array['upboi'] = $admin_info['username'];
		
	$array['alias'] = ( $array['alias'] == "" ) ? change_alias( $array['title'] ) : change_alias( $array['alias'] );
		
	$list_song = array();
		
	foreach ( $array['link'] as $link )
	{
		$array_meta_tag = get_meta_tags( $link );
		
		$array['title'] = $array_meta_tag['title']? $array_meta_tag['title'] : "";
		$array['title'] = explode ( "|", $array['title'] );
		$array['title'] = explode ( "-", $array['title'][0] );
		$array['title'] = array_map ( "trim", $array['title'] );

		$title = ! empty ( $array['title'][0] ) ? $array['title'][0] : "";
		$alias = ! empty ( $title ) ? change_alias( $title ) : "";
		
		$singer = ! empty ( $array['title'][1] ) ? $array['title'][1] : "ns";
		
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
			
			$song_id = $db->sql_query_insert_id( $query );
			
			if ( $song_id )
			{
				$list_song[] = $song_id;
				$db->sql_freeresult();
			}
		}
	}
	
	if( ! empty( $list_song ) and ! empty( $array['album'] ) )
	{
		$sql = "SELECT `listsong` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `name` =" . $db->dbescape( $array['album'] );
		$result = $db->sql_query( $sql );
		list( $data_song ) = $db->sql_fetchrow( $result );
		$data_song = explode( ",", $data_song );
		foreach( $list_song as $songid )
		{
			$data_song[] = $songid;
		}
		
		$data_song = array_filter( $data_song );
		
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `listsong`=" . $db->dbescape( implode( ",", $data_song ) ) . " WHERE `name`=" . $db->dbescape( $array['album'] );
		$db->sql_query( $sql );
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