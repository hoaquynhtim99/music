<?php

/**
 * @Project NUKEVIET 3.0
 * @Author Phan Tan Dung (phantandung@gmail.com)
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$category = get_category() ;
$allsinger = getallsinger();
    
// xu li
$type = isset( $array_op[1] ) ?  $array_op[1]  : 'name';
$now_page = isset( $array_op[3] ) ?  $array_op[3]  : 1;
$key = isset( $array_op[2] ) ?  $array_op[2]  : '-';

// xu li thong tin submit
if ( $nv_Request->get_int( 'block_sed', 'post', 0 ) == 1 )
{
	$type = filter_text_input( 'type', 'post', 'name' );
	$key =  ( filter_text_input( 'key', 'post', '' ) == '' ) ? '-' : change_alias( filter_text_input( 'key', 'post', '' ) );
	
	if ( $type == 'album' )
	{
		Header( "Location: " . nv_url_rewrite ( $main_header_URL . "=album/id/" . $key, true ) ) ;  
		die();	
	}
	
	if ( $type == 'playlist' )
	{
		Header( "Location: " . nv_url_rewrite ( $main_header_URL . "=allplaylist/id/" . $key, true ) ) ;  
		die();	
	}
	
	Header( "Location: " . nv_url_rewrite ( $main_header_URL . "=search/" . $type . "/" . $key, true ) ) ;  
	die();
}

$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'URL_DOWN', $downURL );
$xtpl->assign( 'allvideo', $mainURL . "=searchvideo/" . $type . "/" . $key);
$xtpl->assign( 'allalbum', $mainURL . "=album/id/" . $key);

$link = $mainURL . "=search/" . $type . "/" . $key ;
$data = '';

if ( $type == "name" )
{
	$data = "WHERE ten LIKE '%". $key ."%'";
	$video = "WHERE name LIKE '%". $key ."%'";
}
elseif ( $type == "singer" )
{
	$data = "WHERE casi LIKE '%". $key ."%'";
	$video = "WHERE casi LIKE '%". $key ."%'";
}
elseif ( $type == "category" )
{
	$data = "WHERE theloai =". $key ;
	$video = "WHERE theloai =". $key ;
}
elseif ( $type == "upload" )
{
	$data = "WHERE upboi =\"". $key."\"" ;
	$video = "WHERE id = 0" ;
}
// xu li du lieu
if ( $now_page == 1) 
{
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*20;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " " . $data . " AND `active` = 1 ORDER BY id DESC LIMIT " . $first_page . ",20";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " " . $data . " AND `active` = 1 ";

// tinh so trang
$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * 20 < $output ) {$ts ++ ;}

// ket qua
$result = $db->sql_query( $sql );
$xtpl->assign( 'num', $output);

$i = 1 ;
while($rs = $db->sql_fetchrow($result))
{
	if ( ( $i == 4 ) && ( $now_page == 1 ))
	{
		$sqlvideo = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video " . $video . " AND `active` = 1 ORDER BY id DESC LIMIT 0,3";
		$sqlalbum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album " . $video . " AND `active` = 1 ORDER BY id DESC LIMIT 0,4";
		
		$resultvideo = $db->sql_query( $sqlvideo );
		$resultalbum = $db->sql_query( $sqlalbum );
	
		if ( $db->sql_numrows($resultvideo) > 0 )
		{
			while( $rsv = $db->sql_fetchrow($resultvideo) )
			{
				$xtpl->assign( 'videoname', $rsv['tname'] );
				$xtpl->assign( 'videosinger', $allsinger[$rsv['casi']] );
				$xtpl->assign( 'thumb', $rsv['thumb'] );
				$xtpl->assign( 'videoview', $mainURL . "=viewvideo/" .$rsv['id']. "/" . $rsv['name']);
				$xtpl->assign( 's_video', $mainURL . "=searchvideo/singer/" . $rsv['casi']);

				$xtpl->parse( 'main.loop.sub.video.loop' );
			}
			$xtpl->parse( 'main.loop.sub.video' );
		}
		if ( $db->sql_numrows($resultalbum) > 0 )
		{
			while( $rsa = $db->sql_fetchrow($resultalbum) )
			{
				$xtpl->assign( 'albumname', $rsa['tname'] );
				$xtpl->assign( 'albumsinger', $allsinger[$rsa['casi']] );
				$xtpl->assign( 'thumb', $rsa['thumb'] );
				$xtpl->assign( 'albumview', $mainURL . "=listenlist/" .$rsa['id']. "/" . $rsa['name']);
				$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $rsa['casi']);

				$xtpl->parse( 'main.loop.sub.album.loop' );
			}
			$xtpl->parse( 'main.loop.sub.album' );
		}
		$xtpl->parse( 'main.loop.sub' );
	}
	
	$xtpl->assign( 'ID', $rs['id']);
	$xtpl->assign( 'num', $output);
	$xtpl->assign( 'name', $rs['tenthat']);
	$xtpl->assign( 'singer', $allsinger[$rs['casi']]);
	$xtpl->assign( 'upload', $rs['upboi']);
	$xtpl->assign( 'category', $category[$rs['theloai']]);
	$xtpl->assign( 'view', $rs['numview']);
	
	$xtpl->assign( 'bitrate', $rs['bitrate']/1000);
	$xtpl->assign( 'size', round ( ( $rs['size']/1024/1024 ), 2 ) );
	$xtpl->assign( 'duration', (int)($rs['duration']/60) . ":" . $rs['duration']%60 );
	
	$xtpl->assign( 'url_listen', $mainURL . "=listenone/".$rs['id'] . "/" . $rs['ten'] );
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $rs['casi']);
	$xtpl->assign( 'url_search_category', $mainURL . "=search/category/" . $rs['theloai']);
	$xtpl->assign( 'url_search_upload', $mainURL . "=search/upload/" . $rs['upboi']);
	
	// phan cach cac div chan le
	if ( ($i % 2) == 0 ) $xtpl->assign( 'gray', 'gray'); else $xtpl->assign( 'gray', '');
	$checkhit = explode ( "-", $rs['hit'] );
	$checkhit = $checkhit[0];
	if ( $checkhit >= 20 )
	{
		$xtpl->parse( 'main.loop.hit' );
	}
	$xtpl->parse( 'main.loop' );
	$i ++ ;
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$contents .= new_page( $ts, $now_page, $link);

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>