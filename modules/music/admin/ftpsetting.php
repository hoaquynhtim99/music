<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 08:52 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Xoa cau hinh FTP
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	
	if ( empty( $id ) ) die( 'NO' );
	
	$sql = "SELECT host FROM " . NV_PREFIXLANG . "_" . $module_data . "_ftp WHERE id=" . $id;
	$result = $db->query( $sql );
	$host = $result->fetchColumn();
	
	if ( empty( $host ) ) die( 'NO' );
	
	// Xoa FTP
	$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_ftp WHERE id=" . $id;
	$db->query( $sql );
	
	// Cap nhat cac bai hat
	$classMusic->updatewhendelFTP( $id, 0 );

	$nv_Cache->delMod( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, 'Delete FTP', $host, $admin_info['userid'] );
	
	die( 'OK' );
}

// Thay doi hoat dong
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	
	if ( empty( $id ) ) die( 'NO' );
	
	$sql = "SELECT active FROM " . NV_PREFIXLANG . "_" . $module_data . "_ftp WHERE id=" . $id;
	$result = $db->query( $sql );
	$numrows = $result->rowCount();
	if ( $numrows != 1 ) die( 'NO' );
	
	$active = $result->fetchColumn();
	$active = $active ? 0 : 1;
	
	// Cap nhat cac bai hat
	$classMusic->updatewhendelFTP( $id, $active );
	
	$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_ftp SET active=" . $active . " WHERE id=" . $id;
	$db->query( $sql );
	
	$nv_Cache->delMod( $module_name );
	
	die( 'OK' );
}

$page_title = $classMusic->lang('ftpsetting');

$error = '';
$array = array();

if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
{
	$newid = $nv_Request->get_int( 'newid', 'post', 0 );
	$lastid = $nv_Request->get_int( 'lastid', 'post', 0 );
	
	for( $i = 1; $i <= $newid; $i++ )
	{
		if( ( $nv_Request->get_title( 'host' . $i . '', 'post', '' ) == '' ) || ( $nv_Request->get_title( 'user' . $i . '', 'post', '' ) == '' ) || ( $nv_Request->get_title( 'pass' . $i . '', 'post', '' ) == '' ) || ( $nv_Request->get_title( 'fulladdress' . $i . '', 'post', '' ) == '' ) || ( $nv_Request->get_title( 'subpart' . $i . '', 'post', '' ) == '' ) ) continue;
		
		$array[$i] = array(
			"host" => $nv_Request->get_title( 'host' . $i . '', 'post', '' ),
			"user" => $nv_Request->get_title( 'user' . $i . '', 'post', '' ),
			"pass" => $nv_Request->get_title( 'pass' . $i . '', 'post', '' ),
			"fulladdress" => $nv_Request->get_string( 'fulladdress' . $i . '', 'post', '' ),
			"subpart" => $nv_Request->get_string( 'subpart' . $i . '', 'post', '' ),
			"ftppart" => $nv_Request->get_string( 'ftppart' . $i . '', 'post', '' )
		);
	}
	
	foreach( $array as $i => $data )
	{
		if( $i > $lastid )
		{
			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_ftp ( id, host, user, pass, fulladdress, subpart, ftppart, active ) VALUES ( " . $i . ", " . $db->quote( $data['host'] ) . ", " . $db->quote( $data['user'] ) . ", " . $db->quote( $data['pass'] ) . ", " . $db->quote( $data['fulladdress'] ) . ", " . $db->quote( $data['subpart'] ) . ", " . $db->quote( $data['ftppart'] ) . ", 1 ) ";
			if( $db->insert_id( $sql ) )
			{
				//$xxx->closeCursor();
				$nv_Cache->delMod( $module_name );
			}
			else
			{
				$error .= $classMusic->lang('error_save');
				break;
			}
		}
		else
		{
			foreach( $data as $key => $value )
			{
				if( ! $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_ftp SET " . $key . " = " . $db->quote( $value ) . " WHERE id = " . $i . "  LIMIT 1 " ) )
				{
					$error .= $classMusic->lang('error_save');
					break;
				}
			}
			$nv_Cache->delMod( $module_name );
		}
	}
}

$newid = 2;
$lastid = 1;
$array = $classMusic->getFTP();

foreach( $array as $data )
{
	if( $newid <= $data['id'] )
	{
		$newid = $data['id'] + 1;
		$lastid = $data['id'];
	}
}

$xtpl = new XTemplate( "ftpsetting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NEWID', $newid );
$xtpl->assign( 'LASTID', $lastid );

// Xuat thong bao loi
if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$i = 1;
ksort( $array );
foreach( $array as $j => $row )
{
	$row['key'] = $j;
	$row['class'] = $i ++ % 2 == 0 ? " class=\"second\"" : "";
	$row['status'] = $row['status'] ? " checked=\"checked\"" : "";

	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';