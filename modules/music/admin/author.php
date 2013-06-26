<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['author_list'];

// Lay du lieu
$contents = "";

$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$q = filter_text_input( 'q', 'get', '' );

$order = filter_text_input( 'order', 'get', 'id' );
if( $order == 'id' ) $sort = "ORDER BY " . $order . " DESC";
else  $sort = "ORDER BY " . $order . " ASC";

// Xu li du lieu
if( ! $now_page )
{
	$now_page = 1;
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * $numshow;
}

$where = "`tenthat` LIKE '%" . $db->dblikeescape( $q ) . "%'";

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_author` WHERE " . $where . " " . $sort . " LIMIT " . $first_page . "," . $numshow;
$sqlnum = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_author` WHERE " . $where;

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=author&numshow=" . $numshow . "&q=" . $q . "&order=" . $order;

// Tinh so trang
$num = $db->sql_query( $sqlnum );
list( $output ) = $db->sql_fetchrow( $num );
$ts = ceil( $output / $numshow );

// Form tim kiem
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?\" method=\"get\"><table class=\"tab1 fixbottomtable\"><tbody><tr><td>";
$contents .= "<input type=\"hidden\" name=\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\" />\n";
$contents .= "<input type=\"hidden\" name=\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\" />\n";
$contents .= $lang_module['author_search'] . ":&nbsp;";

// So ket qua hien thi
$i = 5;
$contents .= "&nbsp;" . $lang_module['search_per_page'] . ":&nbsp;";
$contents .= "<select name=\"numshow\">\n";
while( $i <= 1000 )
{
	$a = '';
	if( $i == $numshow ) $a = "selected=\"selected\"";
	$contents .= "<option " . $a . " value=\"" . $i . "\" >" . $i . "</option>\n";
	$i = $i + 10;
}
$contents .= "</select>\n";

$contents .= $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['search'] . "\">\n";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</td></tr></tbody></table></form>\n";

// Ket qua
$xtpl = new XTemplate( "author.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addauthor" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_author" );
$xtpl->assign( 'ORDER_NAME', $link . "&order=ten" );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addauthor";

$result = mysql_query( $sql );
while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'ten', $row['tenthat'] );
	$xtpl->assign( 'numsong', $row['numsong'] );
	$xtpl->assign( 'numvideo', $row['numvideo'] );
	$xtpl->assign( 'URL_SONG', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&type_search=nhacsi&q=" . $row['tenthat'] );
	$xtpl->assign( 'URL_VIDEO', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip&type_search=nhacsi&q=" . $row['tenthat'] );
	$xtpl->assign( 'url_add_song', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong&nhacsi=" . $row['id'] );
	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_author&id=" . $row['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $row['id'] );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n";
$contents .= new_page_admin( $ts, $now_page, $link );
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>