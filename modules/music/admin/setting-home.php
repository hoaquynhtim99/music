<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 05/10/2013 09:17 AM
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Call jquery Tipsy
$classMusic->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy' );

// Tieu de cua trang
$page_title = $classMusic->lang('home_setting');

// Khoi tao
$array = array(
	'albums' => array(),
	'videos' => array(),
);

$sql = "SELECT * FROM " . $classMusic->table_prefix . "_setting_home ORDER BY object_type, weight ASC";
$result = $db->query( $sql );

while( $row = $result->fetch() )
{
	if( $row['object_type'] == 0 )
	{
		$array['albums'][$row['object_id']] = $row['object_id'];
	}
	elseif( $row['object_type'] == 1 )
	{
		$array['videos'][$row['object_id']] = $row['object_id'];
	}
}

// Lay thong tin submit
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['albums'] = nv_substr( $nv_Request->get_title( 'albums', 'post', '', 1 ), 0, 255);
	$array['videos'] = nv_substr( $nv_Request->get_title( 'videos', 'post', '', 1 ), 0, 255);
	
	// Chuyen chuoi thanh mang
	$array['albums'] = $classMusic->string2array( $array['albums'] );
	$array['videos'] = $classMusic->string2array( $array['videos'] );
	
	// Xoa het du lieu
	$db->query( "TRUNCATE TABLE " . $classMusic->table_prefix . "_setting_home" );
	
	// Luu album
	$i = 1;
	foreach( $array['albums'] as $albumid )
	{
		$sql = "REPLACE INTO " . $classMusic->table_prefix . "_setting_home VALUES (0, " . intval( $albumid ) . ", " . $i . ")";
		$db->query( $sql );
		$i ++;
	}
	
	// Luu video
	$i = 1;
	foreach( $array['videos'] as $videoid )
	{
		$sql = "REPLACE INTO " . $classMusic->table_prefix . "_setting_home VALUES (1, " . intval( $videoid ) . ", " . $i . ")";
		$db->query( $sql );
		$i ++;
	}

	$nv_Cache->delMod( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$xtpl = new XTemplate( "setting-home.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array );

$xtpl->assign( 'LISTALBUMS', implode( ",", $array['albums'] ) );
$xtpl->assign( 'LISTVIDEOS', implode( ",", $array['videos'] ) );

$array['albums'] = $classMusic->getalbumbyID( $array['albums'], true );
$array['videos'] = $classMusic->getvideobyID( $array['videos'], true );

// Xuat album
foreach( $array['albums'] as $tmp )
{
	$xtpl->assign( 'ALBUM', $tmp );
	$xtpl->parse( 'main.album' );
}

// Xuat videoclip
foreach( $array['videos'] as $tmp )
{
	$xtpl->assign( 'VIDEO', $tmp );
	$xtpl->parse( 'main.video' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';