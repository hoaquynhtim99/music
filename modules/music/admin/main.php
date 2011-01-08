<?php
/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['content_list'];

if ( $nv_Request->get_int( 'do', 'post', 0 ) == 1 )
{
	$numshow = $nv_Request->get_int( 'numshow', 'post', 100 );
	$where_search = $nv_Request->get_int( 'where_search', 'post', 0 );
	$type_search = filter_text_input( 'type_search', 'post', '' );
	$q = change_alias( $nv_Request->get_string( 'q', 'post', '' ));
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&numshow=" . $numshow . "&where_search=" . $where_search . "&type_search=" . $type_search . "&q=" . $q ) ;  die();	
}

// lay du lieu 
$contents = '' ;
$category = get_category() ;
$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );

$order = filter_text_input( 'order', 'get', 'id' );
if ( $order == 'id' ) $sort = "ORDER BY " . $order  . " DESC" ; else $sort = "ORDER BY " . $order  . " ASC" ;

$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$where_search = $nv_Request->get_int( 'where_search', 'get', 0 );
$type_search = filter_text_input( 'type_search', 'get', 'ten' );
$q = filter_text_input( 'q', 'get', '' );

if ( $where_search == 0 ) $theloai = "" ;
else $theloai = "theloai = ". $where_search ." AND";
$where = $type_search." LIKE '%". $q ."%'";

// xu li du lieu
if ( $now_page == 0 ) 
{
	$now_page = 1 ;
	$first_page = 0 ;
}
else 
{
	$first_page = ($now_page -1)*$numshow;
}	


$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE ".$theloai." ".$where." " . $sort . " LIMIT ".$first_page.",".$numshow."";
$sqlnum = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE ".$theloai." ".$where."";

$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&numshow=" . $numshow . "&where_search=" . $where_search . "&type_search=" . $type_search . "&q=" . $q . "&order=" . $order ;

$num = $db->sql_query($sqlnum);
$output = $db->sql_numrows($num);
$ts = 1;
while ( $ts * $numshow < $output ) {$ts ++ ;}

// Form tim kiem
$contents .= "<br />\n";
$contents .= "<form action=\"\" method=\"post\">";
$contents .= "<label>" . $lang_module['search_music'] . ": </label>\n";

// tim theo the loai
$contents .= "<select name=\"where_search\">\n";
$contents .= "<option value=\"0\" >" . $lang_module['select_category'] . "</option>\n";
foreach ( $category as $id => $title )
{	
	$a = '';
	if ($id == $where_search) $a = "selected=\"selected\"";
	$contents .= "<option ".$a." value=\"".$id."\" >" . $title . "</option>\n";
}
$contents .= "</select>";

// kieu tim
$contents .= "<select name=\"type_search\">";
$contents .= "<option"; ($type_search == 'ten')? ($contents .= " selected=\"selected\""):'' ; $contents .= " value=\"ten\" >" . $lang_module['search_with_name'] . "</option>\n";
$contents .= "<option"; ($type_search == 'casi')? ($contents .= " selected=\"selected\""):'' ; $contents .= " value=\"casi\" >" . $lang_module['search_with_singer'] . "</option>\n";
$contents .= "<option"; ($type_search == 'album')? ($contents .= " selected=\"selected\""):'' ; $contents .= " value=\"album\" >" . $lang_module['search_with_album'] . "</option>\n";
$contents .= "</select>";

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
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</form><br />\n";

// ket qua
$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong");
$xtpl->assign('URL_DEL_BACK', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name);
$xtpl->assign('URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall");
$xtpl->assign('ORDER_NAME', $link ."&order=ten" );
$xtpl->assign('ORDER_SINGER', $link ."&order=casi" );
$xtpl->assign('ORDER_ALBUM', $link ."&order=album" );

//lay du lieu
$result = mysql_query( $sql );
$num = $db->sql_numrows($result);

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong";

while($rs = $db->sql_fetchrow($result))
{
	$xtpl->assign('id', $rs['id']);
	$xtpl->assign('name', $rs['tenthat']);
	$xtpl->assign('singer', $rs['casithat']);
	$xtpl->assign( 'URL', $mainURL . "=listenone/" . $rs['id'] . "/" . $rs['ten'] );
	
	// lay ra ten album
	$sql_album = "SELECT `tname` FROM ".NV_PREFIXLANG."_".$module_data."_album WHERE name=\"".$rs['album']."\"";
	$result_album = mysql_query( $sql_album );
	$album = $db->sql_fetchrow($result_album);
	$xtpl->assign('album', $album['tname']);
	$xtpl->assign('category', $category[ $rs['theloai'] ] );

	$class = ($i % 2) ? " class=\"second\"" : "";
	$xtpl->assign('class', $class);
	$xtpl->assign('URL_DEL_ONE', $link_del . "&id=" . $rs['id']);
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