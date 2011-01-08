<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if(!defined('NV_IS_MUSIC_ADMIN'))
{
	die('Stop!!!');
}

$page_title = $lang_module['album_menu'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows($result);

$id = $nv_Request->get_int( 'id', 'get', 0 ) ;
if( $id != 0 )
{
	$numnew = $num + 1 ;
	$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` (`id`, `albumid`, `order`) VALUES ( NULL, " . $db->dbescape( $id ) . ", " . $db->dbescape( $numnew ) . ")" ); 
	
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=mainalbum"); die();
}

// tao xtpl
$xtpl = new XTemplate("mainalbum.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=mainalbum");

$link_del_one = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";

// xu li cac du lieu
$i = 0;
while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);
	$sqla = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE id=".$rs['albumid']."";
	$resulta = $db->sql_query( $sqla );
	$rsa = $db->sql_fetchrow($resulta);
	$xtpl->assign('name', $rsa['tname']);
	
	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->assign('URL_DEL_ONE', $link_del_one . "&where=_main_album&id=" . $rs['id']);

	for($j = 0; $j < $num; $j++)
	{
		$xtpl->assign('VAL', $j + 1);

		if($j == $i)
		{
			$xtpl->assign('SELECT', 'selected="selected"');
		}
		else
		{
			$xtpl->assign('SELECT', '');
		}

		$xtpl->parse('main.row.sel.sel_op');
	}

	$xtpl->assign('SEL_W', $rs['order']);
	$xtpl->parse('main.row.sel');
	$xtpl->parse('main.row');

	$i++;
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>