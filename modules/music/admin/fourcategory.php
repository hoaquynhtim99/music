<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011
 * @createdate 26/01/2011 10:08 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['sub_fourcategory'];

// Them moi mot tab
if( $nv_Request->isset_request( 'submit_add', 'post' ) )
{
	$cid = $nv_Request->get_int( 'add_theloai', 'post', 0 );
	if( ! empty( $cid ) )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_4category` VALUES(NULL, " . $cid . ")";
		$db->sql_query( $sql );
	}

	nv_del_moduleCache( $module_name );
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

// Cap nhat cac tab
if( $nv_Request->isset_request( 'submit_save', 'post' ) )
{
	$array_id = $nv_Request->get_typed_array( 'hide_theloai', 'post', 'int' );
	$array_cat = $nv_Request->get_typed_array( 'theloai', 'post', 'int' );

	foreach( $array_id as $_id => $id )
	{
		if( empty( $array_cat[$_id] ) )
		{
			$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_4category` WHERE `id`=" . $id;
			$db->sql_query( $sql );
		}
		else
		{
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_4category` SET `cid`=" . $array_cat[$_id] . " WHERE `id`=" . $id;
			$db->sql_query( $sql );
		}
	}

	nv_del_moduleCache( $module_name );
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

// Danh sach the loai
$category = get_category();
if( count( $category ) == 0 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category" );
	die();
}

$array = array();

$sql = "SELECT `id`, `cid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_4category` ORDER BY `id` ASC";
$result = $db->sql_query( $sql );

$i = 1;
while( list( $id, $cid ) = $db->sql_fetchrow( $result ) )
{
	$array[$id] = array(
		"id" => $id, //
		"cid" => $cid, //
		"stt" => $i, //
		"title" => $category[$cid]['title'], //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : "" //
			);

	$i++;
}

$xtpl = new XTemplate( "cat_tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );

foreach( $array as $row )
{
	$xtpl->assign( 'ROW', $row );

	foreach( $category as $cat )
	{
		$cat['selected'] = ( $cat['id'] == $row['cid'] ) ? " selected=\"selected\"" : "";
		$xtpl->assign( 'CAT', $cat );
		$xtpl->parse( 'main.row.cat' );
	}

	$xtpl->parse( 'main.row' );
}

foreach( $category as $cat )
{
	$xtpl->assign( 'CAT', $cat );
	$xtpl->parse( 'main.cat' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>