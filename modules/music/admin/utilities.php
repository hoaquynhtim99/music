<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['utilities'];

$xtpl = new XTemplate( "utilities.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array = array();

// Tien ich xoa chu de
$array[] = array(
	"title" => $lang_module['ex_delete_cat'],
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=deletecatsong",
	"guide" => $lang_module['ex_delete_cat_explain']
);

// Tien ich phat hien va xoa ca si trung ten
$array[] = array(
	"title" => $lang_module['ex_detected_and_delete_duplicate_singer'],
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer",
	"guide" => $lang_module['ex_detected_and_delete_duplicate_singer_guide']
);

$i = 1;
foreach( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->assign( 'CLASS', ( $i % 2 == 0 ) ? " class=\"second\"" : "" );

	$xtpl->parse( 'main.loop' );
	++$i;
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';