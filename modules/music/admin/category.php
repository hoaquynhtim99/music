<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$new = $nv_Request->get_int( 'new', 'post', 0 );

	if( empty( $id ) ) die( 'NO' );

	$query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_category WHERE id!=" . $id . " ORDER BY weight ASC";
	$result = $db->query( $query );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		$weight++;
		if( $weight == $new ) $weight++;
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_category SET weight=" . $weight . " WHERE id=" . $row['id'];
		$db->query( $sql );
	}
	$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_category SET weight=" . $new . " WHERE id=" . $id;
	$db->query( $sql );

	$nv_Cache->delMod( $module_name );

	die( 'OK' );
}

// Page title collum
$page_title = $lang_module['manager_category'];

// List
$array_data = array();
$sql = "SELECT id, title, keywords, description, numsong, weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_category ORDER BY weight ASC";
$result = $db->query( $sql );
$num = $result->rowCount();

$i = 1;
while( list( $id, $title, $keywords, $description, $numsong, $weight ) = $result->fetch( 3 ) )
{
	$list_weight = array();
	for( $j = 1; $j <= $num; $j++ )
	{
		$list_weight[$j] = array(
			"weight" => $j,
			"title" => $j,
			"selected" => ( $j == $weight ) ? " selected=\"selected\"" : ""
		);
	}

	$array_data[$id] = array(
		"id" => $id,
		"title" => $title,
		"description" => $description,
		"numsong" => $numsong,
		"weight" => $list_weight,
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id . "#addeditarea", //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""
	);
	
	$i++;
}

// Add - Edit
$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

if( $id )
{
	$sql = "SELECT id, title, keywords, description FROM " . NV_PREFIXLANG . "_" . $module_data . "_category WHERE id=" . $id;
	$result = $db->query( $sql );
	$check_ok = $result->rowCount();

	if( $check_ok != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}

	list( $id, $title, $keywords, $description ) = $result->fetch( 3 );
	$array_old = $array = array(
		"id" => $id,
		"title" => $title,
		"keywords" => $keywords,
		"description" => $description
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['cat_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['cat_add'];

	$array = array(
		"id" => 0,
		"title" => "",
		"keywords" => "",
		"description" => ""
	);
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['id'] = $id;
	$array['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255);
	$array['keywords'] = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$array['description'] = nv_substr( $nv_Request->get_title( 'description', 'post', '', 1 ), 0, 255);

	if( empty( $array['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	else
	{
		if( empty( $id ) )
		{
			$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_category WHERE title=" . $db->quote( $array['title'] );
			$result = $db->query( $sql );
			$check_exist = $result->fetchColumn();

			if( $check_exist )
			{
				$error = $lang_module['cat_error_exist'];
			}
			else
			{
				// Get new weight
				$sql = "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_category";
				$result = $db->query( $sql );
				$weight = $result->fetchColumn();
				$new_weight = $weight + 1;

				// Insert into database
				$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_category VALUES (
					NULL, 
					" . $db->quote( $array['title'] ) . ",
					" . $db->quote( $array['keywords'] ) . ",
					" . $db->quote( $array['description'] ) . ",
					0,
					" . $new_weight . "
				)";

				if( $db->insert_id( $sql ) )
				{
					//$xxx->closeCursor();
					$nv_Cache->delMod( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cat_add'], $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_save'];
				}
			}
		}
		else
		{
			$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_category WHERE title=" . $db->quote( $array['title'] ) . " AND id!=" . $id;
			$result = $db->query( $sql );
			$check_exist = $result->fetchColumn();

			if( $check_exist )
			{
				$error = $lang_module['cat_error_exist'];
			}
			else
			{
				$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_category SET 
					title= " . $db->quote( $array['title'] ) . ",
					keywords= " . $db->quote( $array['keywords'] ) . ",
					description= " . $db->quote( $array['description'] ) . "
				WHERE id =" . $id;
					
				if( $db->query( $query ) )
				{
					//$xxx->closeCursor();
					$nv_Cache->delMod( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cat_edit'], $array_old['title'] . "&nbsp;=&gt;&nbsp;" . $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_update'];
				}
			}
		}
	}
}

$xtpl = new XTemplate( "music_cat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'DATA', $array );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

foreach( $array_data as $row )
{
	$xtpl->assign( 'ROW', $row );

	foreach( $row['weight'] as $weight )
	{
		$xtpl->assign( 'WEIGHT', $weight );
		$xtpl->parse( 'main.row.weight' );
	}

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';