<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 12:01 PM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$userid = 0;
$allsinger = getallsinger();
$allauthor = getallauthor();
$category = get_category();

if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
	$username = $admin_info['username'];
	$userid = $admin_info['userid'];
}

$xtpl = new XTemplate( "managersong.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
// khong duoc vao
if( $userid == 0 )
{
	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login" );
    $xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" );		
	$xtpl->parse( 'main.noaccess' );
}
//duoc vao
else
{
	$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
	if ( $id > 0 )
	{
		$song = getsongbyID( $id );
		if ( $song['userid'] != $userid ) die ('Stop!!!');
		if ( $nv_Request->get_int( 'ok', 'post', 0 ) == 1 )
		{
			$song['tenthat'] = $songdata['tenthat'] = filter_text_input( 'name', 'post', '' );
			$song['ten'] = $songdata['ten'] = change_alias( $songdata['tenthat'] );
			$song['casi'] = $songdata['casi'] = filter_text_input( 'singer', 'post', '' );
			$songdata['casimoi'] = filter_text_input( 'newsinger', 'post', '' );
			$song['nhacsi'] = $songdata['nhacsi'] = filter_text_input( 'nhacsi', 'post', '' );
			$songdata['nhacsimoi'] = filter_text_input( 'nhacsimoi', 'post', '' );
			$song['theloai'] = $songdata['theloai'] = $nv_Request->get_int( 'theloai', 'post', 0 );
			if ( $songdata['casimoi'] != '')
			{
				$song['casi'] = $songdata['casi'] = change_alias( $songdata['casimoi'] );
				newsinger( $songdata['casi'], $songdata['casimoi'] );
				updatesinger( $songdata['casi'], 'numsong', '+1' );
			}
			if ( $songdata['nhacsimoi'] != '')
			{
				$song['nhacsi'] = $songdata['nhacsi'] = change_alias( $songdata['nhacsimoi'] );
				newauthor( $songdata['nhacsi'], $songdata['nhacsimoi'] );
				updateauthor( $songdata['nhacsi'], 'numsong', '+1' );
			}
			foreach ( $songdata as $key => $data  )
			{	
				if ( $key == 'casimoi' ) continue;
				if ( $key == 'nhacsimoi' ) continue;
				$resuit = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
			}
			if ( $resuit )
			{
				$xtpl->assign( 'url_play', $mainURL . "=listenone/" . $song['id'] . "/" . $song['ten'] );
				$xtpl->assign( 'url_back', $mainURL . "=managersong" );
				$xtpl->parse( 'main.access.edit.ok' );
			}
		}
		$category = get_category( ) ;
		$allsinger = getallsinger( );
		
		$cate = '';
		$singer = '';
		$author = '';
		foreach( $category as $key => $data )
		{
			$cate .= "<option " . ( ( $key == $song['theloai'] )? ( "selected=\"selected\"" ):( "" ) ) . " value=\"" . $key . "\">" . $data . "</option>";
		}
		foreach( $allsinger as $key => $data )
		{
			$singer .= "<option " . ( ( $key == $song['casi'] )? ( "selected=\"selected\"" ):( "" ) ) . " value=\"" . $key . "\">" . $data . "</option>";
		}
		foreach( $allauthor as $key => $data )
		{
			$author .= "<option " . ( ( $key == $song['nhacsi'] )? ( "selected=\"selected\"" ):( "" ) ) . " value=\"" . $key . "\">" . $data . "</option>";
		}
		$xtpl->assign( 'AUTHOR', $author );
		$xtpl->assign( 'SINGER', $singer );
		$xtpl->assign( 'CATEGORY', $cate );
		$xtpl->assign( 'SONG', $song );
		$xtpl->parse( 'main.access.edit' );
	}
	else
	{
		$now_page = isset( $array_op[2] ) ?  intval( $array_op[2] ) : 1;
		$link = $mainURL . "=song/0" ;

		if ( $now_page == 1) 
		{
			$first_page = 0 ;
		}
		else 
		{
			$first_page = ($now_page -1)*20;
		}	
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `userid` = " . $userid . " ORDER BY id DESC LIMIT ".$first_page.",20";
		$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `userid` = " . $userid ;

		$num = $db->sql_query($sqlnum);
		$output = $db->sql_numrows($num);
		$ts = 1;
		while ( $ts * 20 < $output ) {$ts ++ ;}

		$query = $db->sql_query( $sql );
		while ( $row = $db->sql_fetchrow( $query ) )
		{
			$xtpl->assign( 'id', $row['id'] );
			$xtpl->assign( 'name', $row['tenthat'] );
			$xtpl->assign( 'singer', $allsinger[$row['casi']] );
			$xtpl->assign( 'view', $row['numview'] );
			
			$xtpl->assign( 'bitrate', $row['bitrate']/1000);
			$xtpl->assign( 'size', round ( ( $row['size']/1024/1024 ), 2 ) );
			$xtpl->assign( 'duration', (int)($row['duration']/60) . ":" . $row['duration']%60 );
			$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $row['casi']);

			$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $row['theloai']);
		
			$xtpl->assign( 'category', $category[$row['theloai']] );
			$xtpl->assign( 'url_view', $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'] );
			$xtpl->assign( 'url_edit', $mainURL . "=managersong/" . $row['id'] );
			
			if( $row['active'] == 0 )
			{
				$xtpl->parse( 'main.access.song.noacept' );
			}
			else
			{
				$xtpl->parse( 'main.access.song.acept' );
			}
			$xtpl->parse( 'main.access.song' );
		}
	}
	$xtpl->parse( 'main.access' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
if ( $userid != 0 ) if ( $id == 0 )
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>