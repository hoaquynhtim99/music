<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 06:37 PM
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['video_list'];

if ( $nv_Request->get_int( 'do', 'post', 0 ) == 1 )
{
	$numshow = $nv_Request->get_int( 'numshow', 'post', 50 );
	$where_search = $nv_Request->get_int( 'where_search', 'post', 0 );
	$type_search = filter_text_input( 'type_search', 'post', '' );
	$q = change_alias( $nv_Request->get_string( 'q', 'post', '' ));
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip&numshow=" . $numshow . "&where_search=" . $where_search . "&type_search=" . $type_search . "&q=" . $q ) ;  die();	
}

$contents = '';

$category = get_videocategory();
if ( empty( $category ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video_category" ) ;  
	die();	
}

$allsinger = getallsinger();
$numshow = $nv_Request->get_int( 'numshow', 'get', 50 );

$order = filter_text_input( 'order', 'get', 'id' );
if ( $order == 'id' ) $sort = "ORDER BY " . $order  . " DESC" ; else $sort = "ORDER BY " . $order  . " ASC" ;

$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$where_search = $nv_Request->get_int( 'where_search', 'get', 0 );
$type_search = filter_text_input( 'type_search', 'get', 'name' );
$q = filter_text_input( 'q', 'get', '' );

if ( $where_search == 0 ) $theloai = "" ;
else $theloai = "theloai = " . $where_search . " AND";
$where = $type_search . " LIKE '%". $q ."%'";

// Xu li du lieu
if ( $now_page == 0 ) 
{
	$now_page = 1 ;
	$first_page = 0 ;
}
else 
{
	$first_page = ( $now_page -1 ) * $numshow;
}	

$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=videoclip&amp;numshow=" . $numshow . "&amp;where_search=" . $where_search . "&amp;type_search=" . $type_search . "&amp;q=" . $q . "&amp;order=" . $order;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE " . $theloai . " " . $where." " . $sort . " LIMIT " . $first_page . "," . $numshow;

$sqlnum = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE " . $theloai . " " . $where;
$num = $db->sql_query( $sqlnum );
list( $output ) = $db->sql_fetchrow( $num );

$ts = 1;
while ( $ts * $numshow < $output ) { $ts ++; }

// Form tim kiem
$contents .= "<form action=\"\" method=\"post\"><table class=\"tab1 fixbottomtable\"><tbody><tr><td>";
$contents .= $lang_module['search_video'] . ": ";

// Tim theo the loai
$contents .= "<select name=\"where_search\">\n";
$contents .= "<option value=\"0\" >" . $lang_module['select_category'] . "</option>\n";
foreach ( $category as $id => $title )
{	
	$a = '';
	if ( $id == $where_search ) $a = " selected=\"selected\"";
	$contents .= "<option" . $a . " value=\"" . $id . "\" >" . $title['title'] . "</option>\n";
}
$contents .= "</select>";

// Kieu tim
$contents .= "<select name=\"type_search\">";
$contents .= "<option"; ( $type_search == 'name' )? ( $contents .= " selected=\"selected\"" ): ''; $contents .= " value=\"name\" >" . $lang_module['search_with_name'] . "</option>\n";
$contents .= "<option"; ( $type_search == 'casi' )? ( $contents .= " selected=\"selected\"" ): ''; $contents .= " value=\"casi\" >" . $lang_module['search_with_singer'] . "</option>\n";
$contents .= "<option"; ( $type_search == 'nhacsi' )? ( $contents .= " selected=\"selected\"" ) : ''; $contents .= " value=\"nhacsi\" >" . $lang_module['search_with_author'] . "</option>\n";
$contents .= "</select>";

// So ket qua hien thi
$i = 5;
$contents .= "&nbsp;" . $lang_module['search_per_page'] . ": ";
$contents .= "<select name=\"numshow\">\n";
while ( $i <= 1000 )
{
	$a = '';
	if ( $i == $numshow ) $a = "selected=\"selected\"";
    $contents .= "<option " . $a . " value=\"" . $i . "\" >" . $i . "</option>\n";
    $i = $i + 10;
}
$contents .= "</select>\n";

$contents .= $lang_module['search_key'] . ": <input type=\"text\" value=\"" . $q . "\" maxlength=\"64\" name=\"q\" style=\"width: 265px\">\n";
$contents .= "<input type=\"submit\" value=\"" . $lang_module['search'] . "\"><br>\n";
$contents .= "<input type=\"hidden\" name =\"do\" value=\"1\" />";
$contents .= "</td></tr></tbody></table></form>\n";

$xtpl = new XTemplate( "video.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addvideo" );
$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_video" );
$xtpl->assign( 'URL_ACTIVE_LIST', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=listactive&where=_video" );
$xtpl->assign( 'ORDER_NAME', $link ."&order=name" );
$xtpl->assign( 'ORDER_SINGER', $link ."&order=casi" );

//lay du lieu
$result = $db->sql_query( $sql );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del&where=_video";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addvideo";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&where=_video&id=";

while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'name', $row['tname'] );
	$xtpl->assign( 'singer', $allsinger[$row['casi']] );
	$xtpl->assign( 'URL', $mainURL . "=viewvideo/" . $row['id'] . "/" . $row['name'] );
	$xtpl->assign( 'category', $category[$row['theloai']]['title'] );
	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&id=" . $row['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $row['id'] );
	$xtpl->assign( 'active', ( $row['active'] == 1) ? $lang_module['active_yes'] : $lang_module['active_no'] );
	$xtpl->assign( 'URL_ACTIVE', $link_active . $row['id'] );
	
	$xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n";
$contents .= new_page_admin( $ts, $now_page, $link );
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>