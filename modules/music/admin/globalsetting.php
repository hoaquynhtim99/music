<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $classMusic->lang('set_global');

$xtpl = new XTemplate( "global_setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$array = array();

// Cau hinh FTP
$array[] = array(
	"title" => $classMusic->lang('ftpsetting'),
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ftpsetting",
	"guide" => $classMusic->lang('guide_ftpsetting')
);

// Cau hinh lien ket tinh
$array[] = array(
	"title" => $classMusic->lang('setting_alias'),
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting-alias",
	"guide" => ""
);

// Cau hinh chinh cua module
$array[] = array(
	"title" => $classMusic->lang('music_setting'),
	"link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting",
	"guide" => $classMusic->lang('guide_setting')
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