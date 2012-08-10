<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$q = $nv_Request->get_string( 'q', 'get', '' );

// Tien ich xoa chu de bai hat va chuyen bai hat o chu de do sang chu de khac sang chu de khac
if( $q == 'deletecatsong' )
{
	$array_cat = get_category();
	unset( $array_cat[0] );
	
	if( $nv_Request->isset_request( 'get', 'get' ) )
	{
		$cat1 = $nv_Request->get_int( 'cat1', 'get', 0 );
		$cat2 = $nv_Request->get_int( 'cat2', 'get', 0 );
		
		if( empty( $cat1 ) ) die( $lang_module['ex_delete_cat_error_cat1'] );
		if( empty( $cat2 ) ) die( $lang_module['ex_delete_cat_error_cat2'] );
		if( $cat1 == $cat2 ) die( $lang_module['ex_delete_cat_error_same'] );
		
		if( ! isset( $array_cat[$cat1] ) or ! isset( $array_cat[$cat2] ) ) die( $lang_module['ex_delete_cat_error_exists'] );
		
		// Lay tong so (tu bang bai hat cho chinh xac)
		$sql = "SELECT COUNT(*) AS `numsong` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `theloai`=" . $cat1;
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		
		// Cap nhat cho the loai moi
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `numsong`=`numsong`+" . ( ( int ) $row['numsong'] ) . " WHERE `id`=" . $cat2;
		$db->sql_query( $sql );
		
		// Cap nhat cac bai hat sang the loai moi
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `theloai`=" . $cat2 . " WHERE `theloai`=" . $cat1;
		$db->sql_query( $sql );
		
		// Xoa the loai
		$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `id`=" . $cat1;
		$db->sql_query( $sql );
		
		// Sap xep lai thu tu
		$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		$weight = 0;
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$weight ++;
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
		}
		
		nv_del_moduleCache( $module_name );
		die('<div class="infook">' . $lang_module['ex_delete_cat_ok'] . '</div>');
	}

	$page_title = $lang_module['ex_delete_cat'];
	
	$xtpl = new XTemplate( "exdeletecat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ex&q=deletecatsong" );
	
	foreach( $array_cat as $cat )
	{
		$xtpl->assign( 'CAT', $cat );
		$xtpl->parse( 'main.loop1' );
		$xtpl->parse( 'main.loop2' );
	}
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}
elseif( $q == 'detected-and-delete-duplicate-singer' )
{
    if( ! defined( 'SHADOWBOX' ) )
    {
		$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
		$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
		$my_head .= "<script type=\"text/javascript\">\n";
		$my_head .= "Shadowbox.init();\n";
		$my_head .= "</script>\n";
		
		define( 'SHADOWBOX', true );
	}
	
	$page_title = $lang_module['ex_detected_and_delete_duplicate_singer'];
	
	$xtpl = new XTemplate( "exdetected_and_delete_duplicate_singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ex&q=detected-and-delete-duplicate-singer" );
	
	$checksess = md5( $global_config['sitekey'] . $nv_Request->session_id );
	
	// Xem chi tiet ca si bi trung
	if( $nv_Request->isset_request( 'singer', 'get' ) )
	{
		$singer = $nv_Request->get_string( 'singer', 'get', '' );
		$tokend = $nv_Request->get_string( 'checksess', 'get', '' );
		if( ! empty( $singer ) and $tokend == $checksess )
		{
			$page_title = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer\">" . $lang_module['ex_detected_and_delete_duplicate_singer'] . "</a> ►► <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer&singer=" . urlencode( $singer ) . "&amp;checksess=" . $checksess . "\">" . sprintf( $lang_module['ex_detected_and_delete_duplicate_singer_detail'], $singer ) . "</a>";
		
			$sql = "SELECT `id`, `tenthat`, `thumb`, `numsong`, `numalbum`, `numvideo` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `tenthat`=" . $db->dbescape( $singer );
			$result = $db->sql_query( $sql );
			
			$array_allow_singer_id = array();
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$row['class'] = $i ++ % 2 ? " class=\"second\"" : "";
				$array_allow_singer_id[$row['id']] = $row['id'];
				
				$xtpl->assign( 'ROW', $row );
				
				if( ! empty( $row['thumb'] ) )
				{
					$xtpl->parse( 'main.detail_duplicate.loop.thumb' );
				}
				
				$xtpl->parse( 'main.detail_duplicate.loop' );
			}
			
			$xtpl->parse( 'main.detail_duplicate' );
			
			// Thuc hien xoa
			if( $nv_Request->isset_request( 'submit', 'post' ) )
			{
				$deleteid = $nv_Request->get_typed_array( 'deleteid', 'post', 'int' );
				$toid = $nv_Request->get_int( 'toid', 'post', 'int' );
				
				if( ! empty( $toid ) and in_array( $toid, $array_allow_singer_id ) )
				{
					$deleteid = array_filter( array_intersect( array_diff( $deleteid, array( $toid ) ), $array_allow_singer_id ) );
					
					if( ! empty( $deleteid ) )
					{
						// Lay tong so video, song, album
						list( $numsong ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" ) );
						list( $numalbum ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" ) );
						list( $numvideo ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" ) );
						
						// Cap nhat so song, album, video cho ca si moi
						if( $numsong > 0 ) $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET `numsong`=`numsong`+" . $numsong . " WHERE `id`=" . $toid );
						if( $numalbum > 0 ) $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET `numalbum`=`numalbum`+" . $numalbum . " WHERE `id`=" . $toid );
						if( $numvideo > 0 ) $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET `numvideo`=`numvideo`+" . $numvideo . " WHERE `id`=" . $toid );
						
						// Xoa anh ca si
						$sql = "SELECT `id`, `thumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id` IN( " . implode( ",", $deleteid ) . " )";
						$result = $db->sql_query( $sql );
						while( $row = $db->sql_fetchrow( $result ) )
						{
							if( ! $db->sql_numrows( $db->sql_query( "SELECT `thumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `thumb`=" . $db->dbescape( $row['thumb'] ) . " AND `id`!=" . $row['id'] ) ) )
							{
								$thumb = NV_DOCUMENT_ROOT . $row['thumb'];
								if( is_file( $thumb ) )
								{
									nv_deletefile( $thumb );
								}
							}
						}
						
						// Xoa ca si
						$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id` IN( " . implode( ",", $deleteid ) . " )" );
						
						// Cap nhat lai song, album, video
						$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `casi`=" . $toid . " WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" );
						$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `casi`=" . $toid . " WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" );
						$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `casi`=" . $toid . " WHERE `casi` IN( " . implode( ",", $deleteid ) . " )" );
						
						// Xoa cache
						nv_del_moduleCache( $module_name );
						
						$xtpl->parse( 'complete_duplicate' );
						$contents = $xtpl->text( 'complete_duplicate' );
						
						include ( NV_ROOTDIR . "/includes/header.php" );
						echo nv_admin_theme( $contents );
						include ( NV_ROOTDIR . "/includes/footer.php" );
						exit();
					}
				}
			}
			
			$xtpl->parse( 'main' );
			$contents = $xtpl->text( 'main' );
			
			include ( NV_ROOTDIR . "/includes/header.php" );
			echo nv_admin_theme( $contents );
			include ( NV_ROOTDIR . "/includes/footer.php" );
			exit();
		}
	}
	
	// Lay ca si trung
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 20;
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `tenthat`, `thumb`, `numsong`, `numalbum`, `numvideo`, COUNT(*) AS `duplicate` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` GROUP BY `tenthat` ORDER BY `duplicate` DESC LIMIT " . $page . ", " . $per_page;
	
	$result = $db->sql_query( $sql );
	$query = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $query );

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	
	$array = array();
	$i = 1;
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$row['class'] = $i ++ % 2 ? " class=\"second\"" : "";
		$row['link'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer&singer=" . urlencode( $row['tenthat'] ) . "&amp;checksess=" . $checksess;
		
		$xtpl->assign( 'ROW', $row );
		
		if( ! empty( $row['thumb'] ) )
		{
			$xtpl->parse( 'main.find_duplicate.loop.thumb' );
		}
		
		$xtpl->parse( 'main.find_duplicate.loop' );
	}
	
	// Phan trang
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.find_duplicate.generate_page' );
	}
	
	$xtpl->parse( 'main.find_duplicate' );
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

?>