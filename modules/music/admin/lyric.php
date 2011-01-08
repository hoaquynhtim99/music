<?php
/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['content_list'];

$contents = '' ;

$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
if ( $now_page == 0 ) 
{
	$now_page = 1 ;
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*100;
}	

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric ORDER BY id DESC LIMIT ".$first_page.",100";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric ORDER BY id DESC ";

$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=lyric" ;

$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * 100 < $output ) {$ts ++ ;}

// ket qua
$xtpl = new XTemplate("lyric.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=lyric" );
$xtpl->assign('URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_lyric");

//lay du lieu
$result = mysql_query( $sql );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";

while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);
	$xtpl->assign('user', $rs['user']);
	$xtpl->assign('body', strip_tags(substr($rs['body'], 1, 200)) );
	
	// lay ra ten bai hat
	$sql_song = "SELECT `tenthat` FROM ".NV_PREFIXLANG."_".$module_data." WHERE id=".$rs['songid'] ;
	$result_song = mysql_query( $sql_song );
	$song = $db->sql_fetchrow($result_song);
	$xtpl->assign('song', $song['tenthat']);

	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->assign('URL_DEL_ONE', $link_del . "&where=_lyric&id=" . $rs['id']);
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