<?php
/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 25-12-2010 14:43
 */
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['list_album'];

if ( $nv_Request->get_int( 'do', 'post', 0 ) == 1 )
{
	$numshow = $nv_Request->get_int( 'numshow', 'post', 100 );
	$q = change_alias( $nv_Request->get_string( 'q', 'post', '' ));
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album&numshow=" . $numshow . "&q=" . $q ) ;  die();	
}
// lay du lieu 
$contents = '' ;
$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$q = filter_text_input( 'q', 'get', '' );

$order = filter_text_input( 'order', 'get', 'id' );
if ( $order == 'id' ) $sort = "ORDER BY " . $order  . " DESC" ; else $sort = "ORDER BY " . $order  . " ASC" ;

// xu li du lieu
if (!$now_page) 
{
	$now_page = 1 ;
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*$numshow;
}	

$where = "`name` LIKE '%". $q ."%'";

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE ".$where." " . $sort . " LIMIT ".$first_page.",".$numshow."";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE ".$where."";

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album&numshow=" . $numshow . "&q=" . $q . "&order=" . $order ;

// tinh so trang
$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * $numshow < $output ) {$ts ++ ;}


// Form tim kiem
$contents .= "<br /><form action=\"\" method=\"post\">";
$contents .= "<label>" . $lang_module['search_album'] . ": </label><br />\n";

// so ket qua hien thi
$i = 5;
$contents .= "<label>".$lang_module['search_per_page'].":</label>\n";
$contents .= "<select name=\"numshow\">\n";
while ( $i <= 1000 )
{
	$a = '';
	if ($i == $numshow) $a = "selected=\"selected\"";
    $contents .= "<option ".$a." value=\"".$i."\" >".$i."</option>\n";
    $i = $i + 10;
}
$contents .= "</select>\n";
$contents .= "<br>\n";

$contents .= "" . $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['search'] . "\"><br>\n";
$contents .= "<input type=\"hidden\" name =\"data\" value=\"" . $sql . "\" />";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "<br />\n";
$contents .= "</form>\n";

// ket qua
$xtpl = new XTemplate("album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addalbum");
$xtpl->assign('URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=album");
$xtpl->assign('URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_album");
$xtpl->assign('ORDER_NAME', $link ."&order=name" );
$xtpl->assign('ORDER_SINGER', $link ."&order=casi" );

//lay du lieu
$result = mysql_query( $sql );
$num = $db->sql_numrows($result);

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addalbum";

while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);
	$xtpl->assign('name', $rs['tname']);
	$xtpl->assign('singer', $rs['casithat']);
	$xtpl->assign( 'URL', $mainURL . "=listenlist/" . $rs['id'] . "/" . $rs['name'] );
	$xtpl->assign('url_add_song', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong&album=".$rs['name'] );
	$xtpl->assign('URL_ADD_TO_MAINALBUM', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=mainalbum&id=".$rs['id'] );
	
	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->assign('URL_DEL_ONE', $link_del . "&where=_album&id=" . $rs['id']);
	$xtpl->assign('URL_EDIT', $link_edit . "&id=" . $rs['id']);
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