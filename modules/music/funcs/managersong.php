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

$id = 0;
$userid = 0;
$username = "";
$allsinger = getallsinger();
$allauthor = getallauthor();
$category = get_category();

if ( defined( 'NV_IS_USER' ) )
{
	$username = $user_info['username'];
	$userid = $user_info['userid'];
}

$g_array = array(
	"username" => $username,  //
	"userid" => $userid  //
);

//duoc vao
$array_song = array();
$data_song = array();
if( ! empty( $userid ) )
{
	$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
	if ( $id > 0 )
	{
		$resuit = false;
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
		
		$data_song = array(
			"author" => $author,  //
			"singer" => $singer,  //
			"cate" => $cate,  //
			"song" => $song,  //
			"resuit" => $resuit  //
		);
	}
	else
	{
		$now_page = isset( $array_op[2] ) ?  intval( $array_op[2] ) : 1;
		$link = $mainURL . "=managersong/0" ;

		if ( $now_page == 1 ) 
		{
			$first_page = 0 ;
		}
		else 
		{
			$first_page = ( $now_page -1 ) * 20;
		}	
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `userid` = " . $userid . " ORDER BY id DESC LIMIT ".$first_page.",20";
		$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `userid` = " . $userid ;

		$num = $db->sql_query( $sqlnum );
		$output = $db->sql_numrows( $num );
		$ts = 1;
		while ( $ts * 20 < $output ) {$ts ++ ;}

		$result = $db->sql_query( $sql );
		
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$array_song[] = array(
				"id" => $row['id'],  //
				"name" => $row['tenthat'],  //
				"singer" => $allsinger[$row['casi']],  //
				"view" => $row['numview'],  //
				"bitrate" => $row['bitrate'],  //
				"size" => $row['size'],  //
				"duration" => $row['duration'],  //
				"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'],  //
				"url_search_category" => $mainURL . "=search/category/" . $row['theloai'],  //
				"category" => $category[$row['theloai']],  //
				"url_view" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'],  //
				"url_edit" => $mainURL . "=managersong/" . $row['id'],  //
				"active" => $row['active']  //
			);
		}
	}
}

$contents = nv_music_managersong( $g_array, $array_song, $data_song );
if ( $userid != 0 ) if ( $id == 0 )
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>