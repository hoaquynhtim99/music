<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$category = get_category();
$xtpl = new XTemplate( "listenone.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );
$xtpl->assign( 'img_url',  NV_BASE_SITEURL ."themes/" . $module_info['template'] ."/images/".$module_file );

$xtpl->assign( 'ads', getADS() );

// lay bai hat
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = ".$id ."";
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );
$xtpl->assign( 'URL_SENDMAIL',  NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=sendmail&amp;id=". $id );
$xtpl->assign( 'TITLE',  $lang_module['sendtomail'] );
$xtpl->assign( 'ID',  $id );
$xtpl->assign( 'name', $row['tenthat'] );
$xtpl->assign( 'singer', $row['casithat'] );
$xtpl->assign( 'category', $category[ $row['theloai'] ] );
$xtpl->assign( 'url_search_singer', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=search/singer/" . $row['casi']);

$xtpl->assign( 'url_search_category', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=search/category/" . $row['theloai']);

$xtpl->assign( 'url_search_album', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=album/numview/" . $row['album']);

$sqlalbum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE `name` =\"".$row['album']."\" ";
$queryalbum = $db->sql_query( $sqlalbum );
$album_name = $db->sql_fetchrow( $queryalbum );
$xtpl->assign( 'album', $album_name['tname'] );
$xtpl->assign( 'numview', $row['numview'] );
$xtpl->assign( 'link', $row['duongdan'] );
$xtpl->assign( 'URL_SONG', get_URL() );

// update bai hat
$i = $row['numview'] + 1 ;
$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `numview` = " . $db->dbescape( $i ) . " WHERE `id` =" . $id . "");

// loi bai hst
$sqllyric = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE songid = ". $id ." ORDER BY id DESC";
$querylyric = $db->sql_query( $sqllyric );
$num_lyric = $db->sql_numrows($querylyric);

if ( $num_lyric >= 1 )
{
	$i = 1 ;
	while ( $rowlyric = $db->sql_fetchrow( $querylyric ) )
	{	
		if ( ($num_lyric > 1) && ( $i < $num_lyric ))
		{
			$nextdiv = $i + 1 ;
			$xtpl->assign( 'nextdiv', $nextdiv );
			$xtpl->parse( 'main.lyric.next' );			
		}
		if ( $i > 1 )
		{
			$prevdiv = $i - 1 ;
			$xtpl->assign( 'prevdiv', $prevdiv );
			$xtpl->parse( 'main.lyric.prev' );					
		}

		$xtpl->assign( 'uesrlyric', $rowlyric['user'] );		
		$xtpl->assign( 'lyriccontent', $rowlyric['body'] );		
		$xtpl->assign( 'thisdiv', $i );
		$xtpl->parse( 'main.lyric' );
		$i ++ ;
	}
}
else
{
	$xtpl->parse( 'main.nolyric' );
}
// tieu de trang
$page_title = "Bài hát ". $row['tenthat'] . " - " .$row['casithat'] ;
$key_words =  $row['tenthat'] . " - " .$row['casithat'] ;

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );



?>