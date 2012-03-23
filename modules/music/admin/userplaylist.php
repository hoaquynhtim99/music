<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['userplaylist'];

// Lay du lieu
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist`";
$now_page = $nv_Request->get_int( 'now_page', 'get', 0 );
$link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=userplaylist";

// Tim kiem
$q = filter_text_input( 'q', 'get', '', 0, NV_MAX_SEARCH_LENGTH );

if( isset( $q{NV_MIN_SEARCH_LENGTH - 1} ) and ! isset( $q{NV_MAX_SEARCH_LENGTH} ) )
{
	$link .= "&q=" . $q;
	$sql .= " WHERE `name` LIKE '%" . $db->dblikeescape( $q ) . "%'";
}

if( ! $now_page )
{
	$now_page = 1;
	$first_page = 0;
}
else
{
	$first_page = ( $now_page - 1 ) * 50;
}

// Tinh so trang
$sql1 = "SELECT COUNT(*) " . $sql;
$result = $db->sql_query( $sql1 );
list( $output ) = $db->sql_fetchrow( $result );
$ts = ceil( $output / 50 );

$xtpl = new XTemplate( "userplaylist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );

// Search
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php" );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', NV_OP_VARIABLE );
$xtpl->assign( 'OP1', $op );
$xtpl->assign( 'Q', $q );

$xtpl->assign( 'URL_DEL_BACK', $link );
$xtpl->assign( 'URL_ACTIVE_LIST', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=listactive&where=_playlist" );
$xtpl->assign( 'URL_DEL', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delall&where=_playlist" );

$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del";
$link_edit = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=editplaylist";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&where=_playlist&id=";

$sql = "SELECT * " . $sql . " ORDER BY `active` ASC, `time` DESC LIMIT " . $first_page . ",50";
$result = $db->sql_query( $sql );
while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'name', $row['name'] );
	$xtpl->assign( 'singer', $row['singer'] );
	$xtpl->assign( 'username', $row['userid'] ? "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['userid'] . "\" title=\"" . $row['username'] . "\">" . $row['username'] . "</a>" : $row['full_name'] . " - " . $lang_module['visittor'] );

	$xtpl->assign( 'class', ( $i % 2 ) ? " class=\"second\"" : "" );
	$xtpl->assign( 'URL_DEL_ONE', $link_del . "&where=_playlist&id=" . $row['id'] );
	$xtpl->assign( 'URL_EDIT', $link_edit . "&id=" . $row['id'] );

	$xtpl->assign( 'active', ( $row['active'] == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'] );
	$xtpl->assign( 'URL_ACTIVE', $link_active . $row['id'] );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
$contents .= "<div align=\"center\" style=\"width:300px;margin:0px auto 0px auto;\">\n";
$contents .= new_page_admin( $ts, $now_page, $link );
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>