<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['set_global'];

$xtpl = new XTemplate( "global_setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array = array();

// Album HOT
$array[] = array(
	"title" => $lang_module['sub_hotalbum'], //
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=hotalbum", //
	"guide" => $lang_module['guide_hot'] //
);

// Cau hinh FTP
$array[] = array(
	"title" => $lang_module['ftpsetting'], //
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ftpsetting", //
	"guide" => $lang_module['guide_ftpsetting'] //
);

// Cau hinh chinh cua module
$array[] = array(
	"title" => $lang_module['music_setting'], //
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting", //
	"guide" => $lang_module['guide_setting'] //
);

// Tien ich xoa chu de
$array[] = array(
	"title" => $lang_module['ex_delete_cat'], //
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=deletecatsong", //
	"guide" => $lang_module['ex_delete_cat_explain'] //
);

// Tien ich phat hien va xoa ca si trung ten
$array[] = array(
	"title" => $lang_module['ex_detected_and_delete_duplicate_singer'], //
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ex&amp;q=detected-and-delete-duplicate-singer", //
	"guide" => $lang_module['ex_detected_and_delete_duplicate_singer_guide'] //
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>