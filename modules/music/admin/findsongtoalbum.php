<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-03-2011 20:08
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'loadname', 'get' ) )
{
	$songlist = $nv_Request->get_string( 'songlist', 'get', '' );
	$sql = "SELECT `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $songlist . ")";
	$result = $db->sql_query( $sql );
	
	$list_song = array();
	while( list( $songname ) = $db->sql_fetchrow( $result ) )
	{
		$list_song[] = $songname;
	}
	
	$list_song = "<div style=\"width:300px\" class=\"fl\">" . implode( "</div><div style=\"width:300px\" class=\"fl\">", $list_song ) . "</div><div class=\"clear\"></div>";
	die( $list_song );
}

$songlist = $nv_Request->get_string( 'songlist', 'get', '' );

$allsinger = getallsinger();
$allauthor = getallauthor();

if ( ! empty ( $songlist ) )
{
	$songlist = explode ( ",", $songlist );
}
else
{
	$songlist = array();
}

$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op ;

$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

$sql .= " ORDER BY `id` DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 14;

$sql2 = "SELECT `id`, `tenthat`, `casi`, `nhacsi` " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$array = array();
while ( $row = $db->sql_fetchrow( $query2 ) )
{
	$array[$row['id']] = array(
		"id" => $row['id'],
		"tenthat" => $row['tenthat'],
		"casi" => $allsinger[$row['casi']],
		"nhacsi" => $allauthor[$row['nhacsi']],
		"checked" => in_array ( $row['id'], $songlist ) ? " checked=\"checked\"" : ""
	);
}

$generate_page =  nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, "nv_load_user", "data" );

$xtpl = new XTemplate( "findsongtoalbum.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'songlist', implode ( ",", $songlist ) );

if ( ! empty( $array ) )
{
    $a = 0;
    foreach ( $array as $row )
    {
        $xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
        $xtpl->assign( 'ROW', $row );
        $xtpl->parse( 'main.data.row' );
        $a ++;
    }
    
	if ( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.data.generate_page' );
	}
	
	$xtpl->parse( 'main.data' );
}

if ( $nv_Request->isset_request( 'getdata', 'get' ) )
{
	$contents = $xtpl->text( 'main.data' );
}
else
{
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo ( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>