<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['add_song'];

$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

if( $id )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
	
	if ( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	$row = $db->sql_fetchrow( $result );
	
	$array_old = $array = array(
		"ten" => $row['ten'],
		"tenthat" => $row['tenthat'],
		"casi" => $row['casi'],
		"nhacsi" => $row['nhacsi'],
		"album" => $row['album'],
		"theloai" => $row['theloai'],
		"listcat" => $row['listcat'],
		"duongdan" => $row['duongdan'],
		"upboi" => $row['upboi'],
		"introtext" => nv_br2nl( $row['introtext'] ),
		"description" => nv_editor_br2nl( $row['description'] ),
		"score" => $row['score'],
		"actor" => array(),
		"link" => empty( $row['link'] ) ? array() : explode( "[<NV3>]", $row['link'] ),
		"linkname" => empty( $row['linkname'] ) ? array() : explode( "[<NV3>]", $row['linkname'] )
	);
		
	$sql = "SELECT `aid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_set_actor` WHERE `fid`=" . $id;
	$result = $db->sql_query( $sql );
	while( list( $actorid ) = $db->sql_fetchrow( $result ) )
	{
		$array_old['actor'][] = $array['actor'][] = $actorid;
	}
		
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['content_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['content'];
	
	$array = array(
		"catid" => 0,
		"national" => 0,
		"standard" => 0,
		"format" => 0,
		"demo" => '',
		"size" => 0,
		"time" => 0,
		"title" => '',
		"images" => '',
		"introtext" => '',
		"description" => '',
		"score" => 0,
		"actor" => array(),
		"link" => array(),
		"linkname" => array()
	);
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$array['national'] = $nv_Request->get_int( 'national', 'post', 0 );
	$array['standard'] = $nv_Request->get_int( 'standard', 'post', 0 );
	$array['format'] = $nv_Request->get_int( 'format', 'post', 0 );
	$array['demo'] = trim( nv_nl2br( filter_text_textarea( 'demo', '', NV_ALLOWED_HTML_TAGS ), " " ) );
	$array['size'] = $nv_Request->get_int( 'size', 'post', 0 );
	$array['time'] = $nv_Request->get_int( 'time', 'post', 0 );
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$array['images'] = nv_unhtmlspecialchars( filter_text_input( 'images', 'post', '', 1, 255 ) );
	$array['introtext'] = filter_text_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
	$array['description'] = nv_editor_filter_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
	$array['score'] = $nv_Request->get_int( 'score', 'post', 0 );
	
	$array['actor'] = array();
	$array['actor'] = $nv_Request->get_typed_array( 'actor', 'post', 'int' );
	
	$new_actor = $nv_Request->get_typed_array( 'newactor', 'post', 'string' );
	$new_actor = array_filter( $new_actor );
	$new_actor = nv_new_actor( $new_actor );
	foreach( $new_actor as $_acid )
	{
		$array['actor'][] = $_acid;
	}
	
	$array['actor'] = array_unique( $array['actor'] );
	$array['actor'] = array_filter( $array['actor'] );
	
	$links = $nv_Request->get_typed_array( 'link', 'post', 'int' );

	$array['link'] = array();
	foreach( $links as $tmp )
	{
		$array['link'][$tmp] = trim( nv_nl2br( filter_text_textarea( 'contentlink' . $tmp, '', NV_ALLOWED_HTML_TAGS ), " " ) );
		$array['linkname'][$tmp] = filter_text_input( 'linkname' . $tmp, 'post', '', 1, 255 );
		
		if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $array['link'][$tmp] ) )
		{
			$array['link'][$tmp] = substr ( $array['link'][$tmp], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
		}
	}
	
	// Sort image and demo
	if ( ! empty ( $array['images'] ) )
	{
		if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $array['images'] ) )
		{
			$array['images'] = substr ( $array['images'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
		}
	}
	if ( ! empty ( $array['demo'] ) )
	{
		if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $array['demo'] ) )
		{
			$array['demo'] = substr ( $array['demo'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
		}
	}
	
	$nationalcheck = false;
	if( ! empty( $array['national'] ) )
	{
		$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_national` WHERE `id`=" . $array['national'];
		$result = $db->sql_query( $sql );
		list( $nationalcheck ) = $db->sql_fetchrow( $result );
	}
		
	$standardcheck = false;
	if( ! empty( $array['standard'] ) )
	{
		$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_standard` WHERE `id`=" . $array['standard'];
		$result = $db->sql_query( $sql );
		list( $standardcheck ) = $db->sql_fetchrow( $result );
	}
		
	$formatcheck = false;
	if( ! empty( $array['format'] ) )
	{
		$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_format` WHERE `id`=" . $array['format'];
		$result = $db->sql_query( $sql );
		list( $formatcheck ) = $db->sql_fetchrow( $result );
	}
	
	$catcheck = false;
	if( ! empty( $array['catid'] ) )
	{
		$sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $array['catid'];
		$result = $db->sql_query( $sql );
		list( $catcheck ) = $db->sql_fetchrow( $result );
	}
	
	$okmen = true;
	$i = 1;
	foreach( $array['link'] as $link )
	{
		if( empty( $link ) )
		{
			$error = sprintf( $lang_module['content_error_link'], $i );
			$okmen = false;
			break;
		}
		$i ++;
	}
	
	// Check error
	if ( empty ( $array['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	elseif( $okmen )
	{
		// Replace <br /> description, datas
		$array['introtext'] = nv_nl2br( $array['introtext'] );
		$array['description'] = nv_editor_nl2br( $array['description'] );
				
		if( empty( $id ) )
		{
			// Check exist
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `title`=" . $db->dbescape( $array['title'] ) . " AND `national`=" . $array['national'] . " AND `standard`=" . $array['standard'] . " AND `format`= " . $array['format'];
			$result = $db->sql_query( $sql );
			list ( $check_exist ) = $db->sql_fetchrow( $result );
			
			if ( $check_exist )
			{
				$error = $lang_module['content_error_exist'];
			}
			else
			{
				// Insert into database
				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
					NULL, 
					" . $array['catid'] . ",
					" . $array['national'] . ",
					" . $admin_info['userid'] . ",
					" . $array['standard'] . ",
					" . $array['format'] . ",
					" . $db->dbescape( $admin_info['username'] ) . ", 
					" . NV_CURRENTTIME . ",
					" . NV_CURRENTTIME . ",
					" . $array['size'] . ",
					" . $array['time'] . ",
					" . $db->dbescape( $array['title'] ) . ", 
					" . $db->dbescape( $array['images'] ) . ", 
					" . $db->dbescape( implode( '[<NV3>]', $array['link'] ) ) . ", 
					" . $db->dbescape( implode( '[<NV3>]', $array['linkname'] ) ) . ", 
					" . $db->dbescape( $array['demo'] ) . ", 
					" . $db->dbescape( $array['introtext'] ) . ", 
					" . $db->dbescape( $array['description'] ) . ",
					0, 0, 0, 0, 0, " . $array['score'] . ",
					1
				)";
					
				$id_result = $db->sql_query_insert_id( $sql );
				
				if ( $id_result )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					
					if( ! empty( $array['actor'] ) )
					{
						foreach( $array['actor'] as $actor )
						{
							$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_set_actor` VALUES (NULL, " . $id_result . ", " . $actor . ")";
							$db->sql_query( $sql );
						}
					}
					
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content'], $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
					die();
				}
				else
				{
					$error = $lang_module['error_save'];
				}
			}
		}
		else
		{
			// Check exist
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `title`=" . $db->dbescape( $array['title'] ) . " AND `national`=" . $array['national'] . " AND `standard`=" . $array['standard'] . " AND `format`= " . $array['format'] . " AND `id`!=" . $id;
			$result = $db->sql_query( $sql );
			list ( $check_exist ) = $db->sql_fetchrow( $result );
			
			if ( $check_exist )
			{
				$error = $lang_module['content_error_exist'];
			}
			else
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET 
					`catid`=" . $array['catid'] . ",
					`national`=" . $array['national'] . ",
					`standard`=" . $array['standard'] . ",
					`format`=" . $array['format'] . ",
					`updatetime`=" . NV_CURRENTTIME . ",
					`size`=" . $array['size'] . ",
					`time`=" . $array['time'] . ",
					`title`=" . $db->dbescape( $array['title'] ) . ", 
					`images`=" . $db->dbescape( $array['images'] ) . ", 
					`link`=" . $db->dbescape( implode( '[<NV3>]', $array['link'] ) ) . ", 
					`linkname`=" . $db->dbescape( implode( '[<NV3>]', $array['linkname'] ) ) . ", 
					`demo`=" . $db->dbescape( $array['demo'] ) . ", 
					`introtext`=" . $db->dbescape( $array['introtext'] ) . ", 
					`description`=" . $db->dbescape( $array['description'] ) . ",
					`score`=" . $array['score'] . "
				WHERE `id` =" . $id;
					
				if ( $db->sql_query( $sql ) )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					
					$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_set_actor` WHERE `fid`=" . $id;
					$db->sql_query( $sql );
					foreach( $array['actor'] as $actor )
					{
						$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_set_actor` VALUES (NULL, " . $id . ", " . $actor . ")";
						$db->sql_query( $sql );
					}
					
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_edit'], $array_old['title'] . "&nbsp;=&gt;&nbsp;" . $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
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

if( empty( $array['link'] ) )
{
	$array['link'][] = "";
}
	
// Count
$nv_module_linknum = count( $array['link'] );

// Build description
if ( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
if ( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );

if ( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['description'] = nv_aleditor( 'description', '100%', '200px', $array['description'] );
}
else
{
	$array['description'] = "<textarea style=\"width:100%; height:200px\" name=\"description\" id=\"description\">" . $array['description'] . "</textarea>";
}
	
// List cat	
$list_cat = nv_listcats( $array['catid'] );
if( empty( $list_cat ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
	exit();
}

// List standard	
$list_standard = nv_list_standard( $array['standard'] );
if( empty( $list_standard ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=standard" );
	exit();
}

// List national	
$list_national = nv_list_national( $array['national'] );
if( empty( $list_national ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=national" );
	exit();
}

// List format	
$list_format = nv_list_format( $array['format'] );
if( empty( $list_format ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=format" );
	exit();
}

// List actor
$list_actor = array();
$sql = "SELECT `id`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_actor` ORDER BY `title` ASC";
$result = $db->sql_query( $sql );

while( list( $id, $title ) = $db->sql_fetchrow( $result ) )
{
	$list_actor[] = array(
		"id" => $id,
		"title" => $title,
		"checked" => ( in_array( $id, $array['actor'] ) ) ? " checked=\"checked\"" : ""
	);
}

// Build images and demo
if ( ! empty ( $array['images'] ) )
{
	if ( preg_match( "/^" . str_replace( "/", "\/", "/" . $module_name ) . "\//", $array['images'] ) )
	{
		$array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['images'];
	}
}
if ( ! empty ( $array['demo'] ) )
{
	if ( preg_match( "/^" . str_replace( "/", "\/", "/" . $module_name ) . "\//", $array['demo'] ) )
	{
		$array['demo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['demo'];
	}
}

foreach( $array['link'] as $id => $value )
{
	if ( preg_match( "/^" . str_replace( "/", "\/", "/" . $module_name ) . "\//", $value ) )
	{
		$array['link'][$id] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $value;
	}
}

// Int to string
$array['score'] = $array['score'] ? $array['score'] : "";
$array['time'] = $array['time'] ? $array['time'] : "";
$array['size'] = $array['size'] ? $array['size'] : "";

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'FILE_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/files' );
$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );
$xtpl->assign( 'nv_module_linknum', $nv_module_linknum );

// Prase link
foreach( $array['link'] as $id => $link )
{
	$xtpl->assign( 'linkid', $id );
	$xtpl->assign( 'link', $link );
	$xtpl->assign( 'linkname', isset( $array['linkname'][$id] ) ? $array['linkname'][$id] : "" );
	$xtpl->parse( 'main.link' );
}

//
foreach( $list_cat as $catid )
{
	$xtpl->assign( 'CAT', $catid );
	$xtpl->parse( 'main.catid' );
}

//
foreach( $list_standard as $standard )
{
	$xtpl->assign( 'standard', $standard );
	$xtpl->parse( 'main.standard' );
}

//
foreach( $list_national as $national )
{
	$xtpl->assign( 'national', $national );
	$xtpl->parse( 'main.national' );
}

//
foreach( $list_format as $format )
{
	$xtpl->assign( 'format', $format );
	$xtpl->parse( 'main.format' );
}

//
foreach( $list_actor as $actor )
{
	$xtpl->assign( 'actor', $actor );
	$xtpl->parse( 'main.actor' );
}

// Prase error
if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}
	
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>