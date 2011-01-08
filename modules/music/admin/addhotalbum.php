<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['list_album'];
$contents = '' ;

// lay du lieu 
$stt = $nv_Request->get_int( 'stt', 'get', 0 );
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
// xu li du lieu
if ( $now_page == 0 ) 
{
	$now_page = 1 ;
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*100;
}	

$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album";
$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * 100 < $output ) {$ts ++ ;}

$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addhotalbum&stt=" . $stt ;

// ket qua
$xtpl = new XTemplate("addhotalbum.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);
$xtpl->assign('LANG', $lang_module);

//lay du lieu
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ORDER BY ID DESC LIMIT " . $first_page . ",100";
$result = $db->sql_query( $sql );
while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);
	$xtpl->assign('name', $rs['tname']);
	$xtpl->assign('singer', $rs['casithat']);
	$xtpl->assign('url', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=hotalbum&stt=".$stt."&id=".$rs['id'] );
	
	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');
$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n ";
$contents .= new_page_admin( $ts, $now_page, $link);
$contents .= "</div>\n";

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>