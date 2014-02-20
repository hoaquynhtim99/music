<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_music_block_album' ) )
{
	function nv_block_config_music_block_album( $module, $data_block, $lang_block )
	{
		global $db, $site_mods, $db_config, $lang_global, $global_config;
		
		$module_name = $module;
		$module_file = $site_mods[$module]['module_file'];
		$module_data = $site_mods[$module]['module_data'];
		
		require( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
		
		$classMusic = new nv_mod_music( $module_data, $module_name, $module_file, NV_LANG_DATA, true );

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css" ) )
		{
			$css_file = NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css";
			$tpl_path = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/modules/" . $module_file;
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/admin_default/css/" . $module_file . ".css" ) )
		{
			$css_file = NV_BASE_SITEURL . "themes/admin_default/css/" . $module_file . ".css";
			$tpl_path = NV_ROOTDIR . "/themes/admin_default/modules/" . $module_file;
		}

		$xtpl = new XTemplate( "block.album.tpl", $tpl_path );
		$xtpl->assign( 'LANG', $classMusic->language );
		$xtpl->assign( 'GLANG', $classMusic->glanguage );
		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'CSS_FILE', $css_file );
		$xtpl->assign( 'JQUERY_PLUGIN', $classMusic->getJqueryPlugin( 'jquery.ui.sortable', 'root_admin' ) );
		$xtpl->assign( 'LISTALBUMS', $data_block['albums'] );
		
		// Xuat kieu hien thi
		for( $i = 0; $i <= 0; $i ++ )
		{
			$xtpl->assign( 'DISPLAY_TYPE', array( "key" => $i, 'title' => $classMusic->lang('block_album_display_type_' . $i), "selected" => $i == $data_block['display_type'] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.display_type' );
		}
		
		// Xuat cac album tuy chon
		$data_block['albums'] = $classMusic->string2array( $data_block['albums'] );
		if( ! empty( $data_block['albums'] ) )
		{
			$data_block['albums'] = $classMusic->getalbumbyID( $data_block['albums'], true );
			
			foreach( $data_block['albums'] as $tmp )
			{
				$xtpl->assign( 'ALBUM', $tmp );
				$xtpl->parse( 'main.album' );
			}
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	function nv_block_config_music_block_album_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		
		$return['config']['display_type'] = $nv_Request->get_int( 'config_display_type', 'post', 0 );
		$return['config']['albums'] = filter_text_input( 'config_albums', 'post', '', 1, 255 );
		
		return $return;
	}

	function nv_music_block_album( $block_config )
	{
		global $site_mods, $classMusic, $module_info;
		
		$module_name = $block_config['module'];
		$module_data = $site_mods[$module_name]['module_data'];
		$module_file = $site_mods[$module_name]['module_file'];

		// Goi class neu chua co
		if( empty( $classMusic ) )
		{
			require( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
			
			$classMusic = new nv_mod_music( $module_data, $module_name, $module_file, NV_LANG_DATA, true );
		}
		
		// Lay thong tin co ban
		$array = array();
		
		if( $block_config['display_type'] == 0 and ! empty( $block_config['albums'] ) )
		{
			$array = $classMusic->getalbumbyID( $classMusic->string2array( $block_config['albums'] ), true );
		}
		
		// Xu ly thong tin chi tiet
		foreach( $array as $key => $val )
		{
			$array[$key]['link'] = $classMusic->getLink( 3, $classMusic->setting['alias_view_album'] . "/" . $val['name'] . '-' . $val['id'] );
		}
		
		if( ! empty( $array ) )
		{
			// Xac dinh ten file
			switch( $block_config['display_type'] )
			{
				case 0 : $file_tpl = "block.album.0.menu.tpl"; break;
				default : $file_tpl = "";
			}
			
			// Xac dinh giao dien chua block
			if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/" . $file_tpl ) )
			{
				$block_theme = $module_info['template'];
			}
			else
			{
				$block_theme = "default";
			}
			
			$xtpl = new XTemplate( $file_tpl, NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $classMusic->language );
			$xtpl->assign( 'GLANG', $classMusic->glanguage );
			$xtpl->assign( 'TEMPLATE', $block_theme );
			
			if( $block_config['display_type'] == 0 )
			{
				$i = 1;
				foreach( $array as $row )
				{
					$xtpl->assign( 'ROW', $row );
					
					if( $i ++ == 1 )
					{
						$xtpl->parse( 'main.loop.first' );
					}
					
					$xtpl->parse( 'main.loop' );
				}
			}
			
			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_music_block_album( $block_config );
}

?>