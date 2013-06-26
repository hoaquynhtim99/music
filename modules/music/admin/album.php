<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['list_album'];

// lay du lieu
$contents = '';
$numshow = $nv_Request->get_int( 'numshow', 'get', 100 );
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$q = filter_text_input( 'q', 'get', '' );
$type = filter_text_input( 'type', 'get', 'name' );

$order = filter_text_input( 'order', 'get', 'id' );

if( $order == 'name' )
{
	$sort = "ORDER BY a.tname ASC";
}
elseif( $order == 'casi' )
{
	$sort = "ORDER BY b.tenthat ASC";
}
else
{
	$sort = "ORDER BY a.id DESC";
}

// xu li du lieu
if( ! $now_page )
{
	$now_page = 1;
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * $numshow;
}

if( $type == 'name' )
{
	$where = "a.tname LIKE '%" . $q . "%'";
}
else
{
	$where = "b.tenthat LIKE '%" . $q . "%'";
}

$sqlnum = "SELECT a.*, b.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.id WHERE " . $where;
$sql = $sqlnum . " " . $sort . " LIMIT " . $first_page . "," . $numshow;

$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album&numshow=" . $numshow . "&q=" . $q . "&order=" . $order . "&type=" . $type;

// tinh so trang
$num = $db->sql_query( $sqlnum );
$output = $db->sql_numrows( $num );
$ts = ceil( $output / $numshow );

// Form tim kiem
$i = 5;
$contents .= "
<form action=\"\" method=\"get\">
<input type=\"hidden\" name=\"" . NV_NAME_VARIABLE . "\" value=\"" . $module_name . "\"/>
<input type=\"hidden\" name=\"" . NV_OP_VARIABLE . "\" value=\"" . $op . "\"/>
<table class=\"tab1 fixbottomtable\">
	<tbody>
		<tr>
			<td class=\"fixbg\">
				<strong>" . $lang_module['search_album'] . ":&nbsp;&nbsp;&nbsp;</strong>
				" . $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n
				" . $lang_module['search_per_page'] . "";
$contents .= "
<select name=\"numshow\">\n";
while( $i <= 1000 )
{
	$a = '';
	if( $i == $numshow ) $a = "selected=\"selected\"";
	$contents .= "<option " . $a . " value=\"" . $i . "\" >" . $i . "</option>\n";
	$i = $i + 10;
}
$contents .= "</select>\n";
$contents .= "
				<input type=\"submit\" value=\"" . $lang_module['search'] . "\"><br>\n
			</td>
		</tr>
	</tbody>
</table>
";
$contents .= "<input type=\"hidden\" name =\"data\" value=\"" . $sql . "\" />";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</form>\n";

// Ket qua
$xtpl = new XTemplate( "album.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addalbum" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_ACTIVE_LIST', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=listactive&where=_album" );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_album" );
$xtpl->assign( 'ORDER_NAME', $link . "&order=name" );
$xtpl->assign( 'ORDER_SINGER', $link . "&order=casi" );

//lay du lieu
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addalbum";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&where=_album&id=";

while( $rs = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $rs['id'] );
	$xtpl->assign( 'name', $rs['tname'] );
	$xtpl->assign( 'singer', $rs['singername'] );
	$xtpl->assign( 'numsong', $rs['numsong'] );
	$xtpl->assign( 'URL', $mainURL . "=listenlist/" . $rs['id'] . "/" . $rs['name'] );

	$xtpl->assign( 'URL_SONG', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&type_search=album&q=" . $rs['tname'] );

	$xtpl->assign( 'url_add_song', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addsong&album=" . $rs['name'] );
	$xtpl->assign( 'URL_ADD_TO_MAINALBUM', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=mainalbum&id=" . $rs['id'] );

	$class = ( $i % 2 ) ? " class=\"second\"" : "";
	$xtpl->assign( 'class', $class );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_album&id=" . $rs['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $rs['id'] );

	$str_ac = ( $rs['active'] == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'];
	$xtpl->assign( 'active', $str_ac );
	$xtpl->assign( 'URL_ACTIVE', $link_active . $rs['id'] );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n ";
$contents .= new_page_admin( $ts, $now_page, $link );
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>