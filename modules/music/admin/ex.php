<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );


$q = $nv_Request->get_string( 'q', 'get', '' );

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

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

?>