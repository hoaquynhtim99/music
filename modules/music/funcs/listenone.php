<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011 Freeware
 * @Createdate 20/01/2011 11:56 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Get song id
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$alias = isset( $array_op[2] ) ? $array_op[2]  : "";

if ( ! $id ) module_info_die();

$sql = "SELECT a.id AS id, a.ten AS ten, a.album AS album, a.tenthat AS tenthat, a.casi AS casi, a.nhacsi AS nhacsi, a.theloai AS theloai, a.listcat AS listcat, a.duongdan AS duongdan, a.upboi AS upboi, a.numview AS numview, a.server AS server, a.binhchon AS binhchon, a.hit AS hit, b.name AS name, b.tname AS tname FROM " . NV_PREFIXLANG . "_" . $module_data . " AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS b ON a.album=b.name WHERE a.id=" . $id . " AND a.active=1";
$result = $db->sql_query( $sql );
$check_exit = $db->sql_numrows( $result );
$row = $db->sql_fetchrow( $result );

if ( $check_exit != 1 or $row['ten'] != $alias )
{
	module_info_die();
}

// Update
updateHIT_VIEW( $id, '', false );

// Global data
$category = get_category();
$allsinger = getallsinger();
$allauthor = getallauthor();

// Info user
$name = ( defined( 'NV_IS_USER' ) ) ? $user_info['username'] : "";
	
// Check HIT song
$checkhit = explode ( "-", $row['hit'] );
$checkhit = $checkhit[0];

// All data
$gdata = array();	// Global data
$sdata = array();	// Song data
$cdata = array();	// Check data
$ldata = array(); 	// Lyric data

$cdata = array(
	"no_change" => ( $name == '' ) ? "" : " readonly=\"readonly\"",  //
	"checkhit" => $checkhit,  //
	"url_login" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login",  //
	"url_register" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register"  //
);

$gdata = array(
	"username" => $name,  //
	"data_url" => NV_BASE_SITEURL . "modules/" . $module_data . "/data/",  //
	"full_data_url" => $global_config['site_url'] ."/modules/" . $module_data . "/data/",  //
	"img_url" =>  NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/",  //
	"download_url" =>  $downURL,  //
	"ads_data" =>  getADS(),  //
	"user_name" =>  $name,  //
	"creat_link_url" =>  NV_MY_DOMAIN . nv_url_rewrite ( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ),  //
	"selfurl_base" =>  $client_info['selfurl'],  //
	"selfurl_encode" =>  rawurlencode ( $client_info['selfurl'] ),  //
	"checksess_gift" =>  md5( "gift_" . $global_config['sitekey'] . session_id() )  //
);

if( ! empty( $row['listcat'] ) )
{
	$row['listcat'] = explode( ",", $row['listcat'] );
	$listcat = array();
	
	foreach( $row['listcat'] as $cat )
	{
		$listcat[] = array(
			"title" => $category[$cat]['title'],  //
			"url" => $mainURL . "=search/category/" . $cat  //
		);
	}
}
else
{
	$listcat = array();
}

$sdata = array(
	"send_mail_url" => $main_header_URL . "=sendmail&id=". $id,  //
	"send_mail_title" => $lang_module['sendtomail'],  //
	
	"song_id" => $id,  //
	"song_name" => $row['tenthat'],  //
	"song_sname" => $row['ten'],  //
	"song_singer" => $allsinger[$row['casi']],  //
	"song_author" => $allauthor[$row['nhacsi']],  //
	"song_cat" => $category[$row['theloai']]['title'],  //
	"song_listcat" => $listcat,  //
	"song_vote" => $row['binhchon'],  //
	"song_numview" => $row['numview'],  //
	"song_link" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ),  //
	
	"url_search_singer" => $mainURL . "=search/singer/" . $row['casi'],  //
	"url_search_category" => $mainURL . "=search/category/" . $row['theloai'],  //
	"url_search_album" => $mainURL . "=album/numview/" . $row['album'],  //
	
	"album_name" => $row['tname']  //
);

// Lyric data
$sqllyric = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE songid = ". $id ." AND `active` = 1 ORDER BY id DESC";
$querylyric = $db->sql_query( $sqllyric );
$num_lyric = $db->sql_numrows($querylyric);

$ldata = array(
	"number" => $num_lyric,  //
	"data" => array(),  //
);

while ( $rowlyric = $db->sql_fetchrow( $querylyric ) )
{	
	$ldata['data'][] = array(
		"user" => $rowlyric['user'],  //
		"content" => $rowlyric['body']  //
	);
}

// Page title
$page_title = $row['tenthat'] . " - " . $allsinger[$row['casi']] ;
$key_words =  $row['tenthat'] . " - " . $allsinger[$row['casi']] ;
$description = sprintf ( $lang_module['share_descreption'], $row['tenthat'], $allsinger[$row['casi']], $allauthor[$row['nhacsi']], NV_MY_DOMAIN );

$contents = nv_music_listenone ( $gdata, $sdata, $cdata, $ldata );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>