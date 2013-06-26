<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['singer_list'];

// Lay du lieu
$contents = '';

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

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=singer&numshow=" . $numshow . "&q=" . $q . "&order=" . $order;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE " . $where . " " . $sort . " LIMIT " . $first_page . "," . $numshow;
$sqlnum = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE " . $where;

// Tinh so trang
$num = $db->sql_query( $sqlnum );
list( $output ) = $db->sql_fetchrow( $num );
$ts = ceil( $output / $numshow );

// Form tim kiem
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?\" method=\"get\"><table class=\"tab1 fixbottomtable\"><tbody><tr><td>";
$contents .= "<input type=\"hidden\" name=\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\" />\n";
$contents .= "<input type=\"hidden\" name=\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\" />\n";
$contents .= $lang_module['search_singer'] . ": ";

// So ket qua hien thi tim kiem
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

// Tu khoa tim kiem
$contents .= $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['search'] . "\">\n";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</td></tr></tbody></table></form>\n";

// Ket qua
$xtpl = new XTemplate( "singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsinger" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_singer" );
$xtpl->assign( 'ORDER_NAME', $link . "&order=ten" );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsinger";

$result = $db->sql_query( $sql );
while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'ten', $row['tenthat'] );
	$xtpl->assign( 'numsong', $row['numsong'] );
	$xtpl->assign( 'numalbum', $row['numalbum'] );
	$xtpl->assign( 'numvideo', $row['numvideo'] );
	$xtpl->assign( 'URL_SONG', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&type_search=casi&q=" . $row['tenthat'] );
	$xtpl->assign( 'URL_VIDEO', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip&type_search=casi&q=" . $row['tenthat'] );
	$xtpl->assign( 'URL_ALBUM', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album&type=singer&q=" . $row['tenthat'] );
	$xtpl->assign( 'url_add_song', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong&casi=" . $row['id'] );
	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_singer&id=" . $row['id'] );
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