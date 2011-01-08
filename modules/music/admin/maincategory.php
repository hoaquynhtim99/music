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

$page_title = $lang_module['category_main'];
$category = get_category() ;
$save = $nv_Request->get_int( 'save', 'post', 0 );
if( $save == 1 )
{
	$num = $nv_Request->get_int( 'num', 'post', 0 );
	$numnew = $num + 1 ;
	$newct = $nv_Request->get_int( 'c'.$numnew, 'post', 0 );
	$i = 1 ;
	while ($i <= $num )
	{
		$data =  $nv_Request->get_int( 'c'.$i , 'post', 0 );
		mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_main_category` SET `cid` = " . $db->dbescape( $data ) . " WHERE `order` =" . $i . "");
		$i ++ ;
	}
	// them vao the loai moi
	if ( $newct != 0 )
	{
		mysql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_main_category` (`id`, `cid`, `order`) VALUES ( NULL, " . $db->dbescape( $newct ) . ", " . $db->dbescape( $numnew ) . ")" ); 
	}
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=maincategory"); die();
}

// tao xtpl
$xtpl = new XTemplate("maincategory.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=maincategory");

$link_del_one = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";

// xu li cac du lieu
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_category` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows($result);
$i = 0;
while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);

	$td = "<select name=\"c".$rs['order']."\">\n";
	foreach ( $category as $key => $title )
	{
		$j= "";
		if ( $rs['cid'] == $key )
		$j = "selected=\"selected\"";
		$td .= "<option ". $j ." value=\"".$key."\" >" . $title . "</option>\n";
	}
	$td .= "</select>";
	$xtpl->assign('td', $td);
	$xtpl->assign('num', $num);
	
	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->assign('URL_DEL_ONE', $link_del_one . "&where=_main_category&id=" . $rs['id']);

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

	$numadd = $num + 1 ;
	$tdadd = "<select name=\"c".$numadd."\">\n";
	$tdadd .= "<option value=\"0\" >Chọn một thể loại</option>\n";
	foreach ( $category as $key => $title )
	{
		$tdadd .= "<option value=\"".$key."\" >" . $title . "</option>\n";
	}
	$tdadd .= "</select>";
	
$xtpl->assign('tdadd', $tdadd);
$xtpl->parse('main.add');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>