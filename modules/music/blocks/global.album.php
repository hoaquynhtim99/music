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
		$xtpl->assign( 'DATA', $data_block );
		
		// Xuat kieu hien thi
		for( $i = 0; $i <= 3; $i ++ )
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
		
		// Xuat vi tri tieu de
		for( $i = 0; $i <= 1; $i ++ )
		{
			$xtpl->assign( 'CAP_POSITION', array( "key" => $i, 'title' => $classMusic->lang('block_album_cap_pos_' . $i), "selected" => $i == $data_block['cap_position'] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.cap_position' );
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
		$return['config']['str_length'] = $nv_Request->get_int( 'config_str_length', 'post', 0 );
		$return['config']['cap_position'] = $nv_Request->get_int( 'config_cap_position', 'post', 0 );
		$return['config']['num_cols'] = $nv_Request->get_int( 'config_num_cols', 'post', 3 );
		$return['config']['image_size'] = $nv_Request->get_int( 'config_image_size', 'post', 128 );
		$return['config']['num_albums'] = $nv_Request->get_int( 'config_num_albums', 'post', 10 );
		
		return $return;
	}

	function nv_music_block_album( $block_config )
	{
		global $site_mods, $classMusic, $module_info, $db, $module_name, $my_head;
		
		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];
		$module_file = $site_mods[$module]['module_file'];

		// Goi class neu chua co
		if( empty( $classMusic ) )
		{
			require( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
			
			$classMusic = new nv_mod_music( $module_data, $module, $module_file, NV_LANG_DATA, true );
		}
		
		// Lay thong tin co ban
		$array = array();
		
		if( $block_config['display_type'] == 0 and ! empty( $block_config['albums'] ) )
		{
			$array = $classMusic->getalbumbyID( $classMusic->string2array( $block_config['albums'] ), true );
		}
		elseif( $block_config['display_type'] == 1 or $block_config['display_type'] == 2 )
		{
			// Lay cau hinh trong admin
			$sql = "SELECT * FROM " . $classMusic->table_prefix . "_setting_home WHERE object_type = 0 ORDER BY weight ASC";
			$result = $db->sql_query( $sql );
			
			$albums_id = array();
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$albums_id[] = $row['object_id'];
			}
			$array = $classMusic->getalbumbyID( $albums_id, true );
		}
		elseif( $block_config['display_type'] == 3 and ! empty( $block_config['num_albums'] ) )
		{
			$sql = "SELECT * FROM " . $classMusic->table_prefix . "_album WHERE active = 1 ORDER BY numview DESC LIMIT 0, " . $block_config['num_albums'];
			$result = $db->sql_query( $sql );
			
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$array[$row['id']] = $row;
			}
		}
		
		// Xu ly thong tin chi tiet, lay ID nhac si va ca si
		$array_singers =  array();
		$array_singer_ids = '';
		foreach( $array as $key => $val )
		{
			$array_singer_ids = $array_singer_ids == '' ? $val['casi'] : $array_singer_ids . "," . $val['casi'];
			
			$array[$key]['link'] = $classMusic->getLink( 3, $classMusic->setting['alias_view_album'] . "/" . $val['name'] . '-' . $val['id'] );
		}
		
		// Lay thong tin ca si, nhac si
		$array_singer_ids = $classMusic->string2array( $array_singer_ids );

		if( ! empty( $array_singer_ids ) ) $array_singers = $classMusic->getsingerbyID( $array_singer_ids );
		
		// Ghi thong tin ca si nhac si vao
		foreach( $array as $key => $val )
		{
			$array[$key]['singers'] = $classMusic->build_author_singer_2string( $array_singers, $val['casi'] );
		}
		
		if( ! empty( $array ) )
		{
			// Xac dinh ten file
			switch( $block_config['display_type'] )
			{
				case 0 : $file_tpl = "block.album.0.menu.tpl"; break;
				case 1 : $file_tpl = "block.album.1.gird.tpl"; break;
				case 2 : $file_tpl = "block.album.0.menu.tpl"; break;
				case 3 : $file_tpl = "block.album.0.menu.tpl"; break;
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
			
			// Goi CSS module neu nhu khong phai module music
			if( $module != $module_name and ! defined( "NV_MUSIC_CSS" ) )
			{
				define( 'NV_MUSIC_CSS', true );
				
				if( is_file( NV_ROOTDIR . "/themes/" . $block_theme . "/css/" . $module_file . ".css" ) )
				{
					$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $module_file . ".css\" />" . NV_EOL;
				}
			}
			
			// Goi JS module neu nhu khong phai module music
			if( $module != $module_name and ! defined( "NV_MUSIC_JS" ) )
			{
				define( 'NV_MUSIC_JS', true );
				
				if( is_file( NV_ROOTDIR . "/themes/" . $block_theme . "/js/module.music.js" ) )
				{
					$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/js/module.music.js\"></script>" . NV_EOL;
				}
				else
				{
					$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/music.js\"></script>" . NV_EOL;
				}
			}
			
			$xtpl = new XTemplate( $file_tpl, NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $classMusic->language );
			$xtpl->assign( 'GLANG', $classMusic->glanguage );
			$xtpl->assign( 'TEMPLATE', $block_theme );
			$xtpl->assign( 'CONFIG', $block_config );
			
			// Kieu hien thi dang menu cho loai: 0, 2, 3
			if( in_array( $block_config['display_type'], array( 0, 2, 3 ) ) )
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
			// Kieu hien thi dang luoi cho loai 1
			elseif( $block_config['display_type'] == 1 )
			{
				$xtpl->assign( 'COL_WIDTH_PER', 100 / $block_config['num_cols'] );
				
				$i = 1;
				foreach( $array as $row )
				{
					$xtpl->assign( 'ROW', $row );
					
					if( $i ++ % $block_config['num_cols'] == 0 )
					{
						$xtpl->parse( 'main.loop.break' );
					}
					
					if( $block_config['cap_position'] == 0 )
					{
						$xtpl->parse( 'main.loop.inset' );
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