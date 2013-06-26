<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_scroll_tabvideo' ) )
{
	function nv_block_config_tabvideo( $module, $data_block, $lang_block )
	{
		global $db, $language_array, $site_mods;
		$html = "";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['col'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_col\" size=\"5\" value=\"" . $data_block['col'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['row'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_row\" size=\"5\" value=\"" . $data_block['row'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['img_size'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_width\" size=\"5\" value=\"" . $data_block['width'] . "\"/> X <input type=\"text\" name=\"config_height\" size=\"5\" value=\"" . $data_block['height'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['length'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_length\" size=\"5\" value=\"" . $data_block['length'] . "\"/></td>";
		$html .= "</tr>";
		return $html;
	}

	function nv_block_config_tabvideo_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['col'] = $nv_Request->get_int( 'config_col', 'post', 0 );
		$return['config']['row'] = $nv_Request->get_int( 'config_row', 'post', 0 );
		$return['config']['width'] = $nv_Request->get_int( 'config_width', 'post', 0 );
		$return['config']['height'] = $nv_Request->get_int( 'config_height', 'post', 0 );
		$return['config']['length'] = $nv_Request->get_int( 'config_length', 'post', 0 );
		return $return;
	}

	function nv_scroll_tabvideo( $block_config )
	{
		global $module_info, $site_mods, $db, $nv_Request, $op, $array_op, $main_header_URL;
		$module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];

		$load_type = $nv_Request->get_int( 'loadblocktabvideo', 'get', 0 );

		$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat FROM `" . NV_PREFIXLANG . "_" . $data . "_video` AS a LEFT JOIN `" . NV_PREFIXLANG . "_" . $data . "_singer` AS b ON a.casi=b.id WHERE a.active=1 ORDER BY";

		switch( $load_type )
		{
			case 1:
				$sql .= " a.id DESC";
				break;
			case 2:
				$sql .= " ( a.view / ( " . NV_CURRENTTIME . " - a.dt ) ) DESC";
				break;
			default:
				$sql .= " a.id DESC";
		}
		$sql .= " LIMIT 0," . ( $block_config['col'] * $block_config['row'] );
		$result = $db->sql_query( $sql );

		if( $db->sql_numrows( $result ) )
		{
			if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_tab_video.tpl" ) )
			{
				$block_theme = $module_info['template'];
			}
			else
			{
				$block_theme = "default";
			}
			include_once NV_ROOTDIR . "/modules/" . $file . "/language/block." . $block_config['block_name'] . "_" . NV_LANG_DATA . ".php";

			$xtpl = new XTemplate( "block_tab_video.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
			$xtpl->assign( 'CONFIG', $block_config );
			$xtpl->assign( 'LANG', $lang_block );
			$xtpl->assign( 'WIDTH_ITEM', intval( 100 / $block_config['col'] ) );
			$xtpl->assign( 'URL_LOAD', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=" . ( empty( $array_op ) ? $op : implode( "/", $array_op ) ) . "&loadblocktabvideo=" );
			$xtpl->assign( 'URL_ALL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=searchvideo/id" );

			$i = 0;
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$row['url_view'] = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&" . NV_OP_VARIABLE . "=viewvideo/" . $row['id'] . "/" . $row['name'], true );

				if( empty( $row['tenthat'] ) )
				{
					$row['tenthat'] = $lang_block['ns'];
				}

				$row['url_search_singer'] = nv_url_rewrite( $main_header_URL . "=search&where=video&q=" . urlencode( $row['tenthat'] ) . "&id=" . $row['casi'] . "&type=singer", true );

				$row['stenthat'] = nv_clean60( $row['tenthat'], $block_config['length'] );
				$row['stname'] = nv_clean60( $row['tname'], $block_config['length'] );
				
				$xtpl->assign( 'ROW', $row );

				if( ++$i % $block_config['col'] == 0 ) $xtpl->parse( 'main.data.loop.break' );
				$xtpl->parse( 'main.data.loop' );
			}

			$xtpl->parse( 'main.data' );
			if( $nv_Request->isset_request( 'loadblocktabvideo', 'get' ) ) die( $xtpl->text( 'main.data' ) );

			$xtpl->parse( 'main' );

			return $xtpl->text( 'main' );
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_scroll_tabvideo( $block_config );
}

?>