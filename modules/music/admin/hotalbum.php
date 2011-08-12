<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $new = $nv_Request->get_int( 'new', 'post', 0 );
    
    if ( empty( $id ) ) die( "NO" );
        
    $query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` WHERE `id`!=" . $id . " ORDER BY `stt` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        if ( $weight == $new ) $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` SET `stt`=" . $weight . " WHERE `id`=" . $row['id'];
        $db->sql_query( $sql );
    }
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` SET `stt`=" . $new . " WHERE `id`=" . $id;
    $db->sql_query( $sql );
    
    nv_del_moduleCache( $module_name );
    
    die( "OK" );
}

$page_title = $lang_module['hot_album'];

// lay du lieu 
$id = $nv_Request->get_int( 'id', 'get', 0 );
$stt = $nv_Request->get_int( 'stt', 'get', 0 );

if ( $id ) 
{
	$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` SET `albumid` = " . $db->dbescape( $id ) . " WHERE `stt` =" . $stt . "");

	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=hotalbum"); die();
	}
	else  die($lang_module['error_save']) ;
}

// ket qua
$xtpl = new XTemplate("hotalbum.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name);
$xtpl->assign('LANG', $lang_module);

//lay du lieu
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album_hot` ORDER BY `stt` ASC" ;
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

while( $rs = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'STT', $rs['stt'] );
	$xtpl->assign( 'ID', $rs['id'] );
	
    for ( $i = 1; $i <= $num; $i ++ )
    {
		$xtpl->assign('title', $i );
		$xtpl->assign('pos', $i );
		$xtpl->assign('selected', ( $i == $rs['stt'] ) ? " selected=\"selected\"" : "" );

		$xtpl->parse('main.row.stt');
    }
	
	$sql_album = "SELECT `tname` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $rs['albumid'];
	$result_album = $db->sql_query( $sql_album );
	$album = $db->sql_fetchrow( $result_album );
	
	$xtpl->assign('album', $album['tname'] );
	$xtpl->assign('LINK_ADD', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addhotalbum&stt=" . $rs['stt'] );

	$class = ( $i % 2 ) ? " class=\"second\"" : "";
	$xtpl->assign( 'class', $class );
	$xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>