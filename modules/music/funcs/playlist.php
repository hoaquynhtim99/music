<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate( "playlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'base_url', NV_BASE_SITEURL ."modules/" . $module_data . "/data/" );

$xtpl->assign( 'ads',  getADS() );

// lay thong tin playlist
$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

if ( $num == 0 )	$xtpl->parse( 'main.null' );
else
{
	for ( $i = 1 ; $i <= $num ; $i ++ )
	{
		$songid = $nv_Request->get_int( $module_name . '_song'.$i.'' , 'cookie', 0 );

		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id =" . $songid ;
		$query = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $query ) ;
			
		$xtpl->assign( 'song_name', $i.". ".$row['tenthat'] );
		$xtpl->assign( 'song_singer', $row['casithat'] );
		$xtpl->assign( 'song_url', $row['duongdan'] );
		$xtpl->parse( 'main.song' );
		
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>