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

	if( empty( $id ) ) die( "NO" );

	$query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `id`!=" . $id . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $query );
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$weight++;
		if( $weight == $new ) $weight++;
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET `weight`=" . $new . " WHERE `id`=" . $id;
	$db->sql_query( $sql );

	nv_del_moduleCache( $module_name );

	die( "OK" );
}

// Page title collum
$page_title = $lang_module['manager_category'];

// List
$array_data = array();
$sql = "SELECT `id`, `title`, `keywords`, `description`, `numsong`, `weight` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$i = 1;
while( list( $id, $title, $keywords, $description, $numsong, $weight ) = $db->sql_fetchrow( $result ) )
{
	$list_weight = array();
	for( $j = 1; $j <= $num; $j++ )
	{
		$list_weight[$j] = array(
			"weight" => $j, //
			"title" => $j, //
			"selected" => ( $j == $weight ) ? " selected=\"selected\"" : "" //
		);
	}

	$array_data[$id] = array(
		"id" => $id, //
		"title" => $title, //
		"description" => $description, //
		"numsong" => $numsong, //
		"weight" => $list_weight, //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id . "#addeditarea", //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : "" //
	);
	
	$i++;
}

// Add - Edit
$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

if( $id )
{
	$sql = "SELECT `id`, `title`, `keywords`, `description` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check_ok = $db->sql_numrows( $result );

	if( $check_ok != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}

	list( $id, $title, $keywords, $description ) = $db->sql_fetchrow( $result );
	$array_old = $array = array(
		"id" => $id, //
		"title" => $title, //
		"keywords" => $keywords, //
		"description" => $description //
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['cat_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['cat_add'];

	$array = array(
		"id" => 0, //
		"title" => "", //
		"keywords" => "", //
		"description" => "" //
	);
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['id'] = $id;
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$array['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
	$array['description'] = filter_text_input( 'description', 'post', '', 1, 255 );

	if( empty( $array['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	else
	{
		if( empty( $id ) )
		{
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `title`=" . $db->dbescape( $array['title'] );
			$result = $db->sql_query( $sql );
			list( $check_exist ) = $db->sql_fetchrow( $result );

			if( $check_exist )
			{
				$error = $lang_module['cat_error_exist'];
			}
			else
			{
				// Get new weight
				$sql = "SELECT MAX(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category`";
				$result = $db->sql_query( $sql );
				list( $weight ) = $db->sql_fetchrow( $result );
				$new_weight = $weight + 1;

				// Insert into database
				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_category` VALUES (
					NULL, 
					" . $db->dbescape( $array['title'] ) . ",
					" . $db->dbescape( $array['keywords'] ) . ",
					" . $db->dbescape( $array['description'] ) . ",
					0,
					" . $new_weight . "
				)";

				if( $db->sql_query_insert_id( $sql ) )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cat_add'], $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
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
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_category` WHERE `title`=" . $db->dbescape( $array['title'] ) . " AND `id`!=" . $id;
			$result = $db->sql_query( $sql );
			list( $check_exist ) = $db->sql_fetchrow( $result );

			if( $check_exist )
			{
				$error = $lang_module['cat_error_exist'];
			}
			else
			{
				$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_category` SET 
					`title`= " . $db->dbescape( $array['title'] ) . ",
					`keywords`= " . $db->dbescape( $array['keywords'] ) . ",
					`description`= " . $db->dbescape( $array['description'] ) . "
				WHERE `id` =" . $id;
					
				if( $db->sql_query( $query ) )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['cat_edit'], $array_old['title'] . "&nbsp;=&gt;&nbsp;" . $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>