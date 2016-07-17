<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_music_block_search' ) )
{
	function nv_music_block_search( $block_config )
	{
		global $site_mods, $classMusic, $module_info, $module_name, $global_config;
		
		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];
		$module_file = $site_mods[$module]['module_file'];
	
		// Goi class neu chua co
		if( empty( $classMusic ) )
		{
			require( NV_ROOTDIR . "/modules/" . $module_file . "/global.class.php" );
			
			$classMusic = new nv_mod_music( $module_data, $module, $module_file, NV_LANG_DATA, true );
		}
		
		// Xac dinh giao dien chua block
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block.search.tpl" ) )
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
		
		$xtpl = new XTemplate( "block.search.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $classMusic->language );
		$xtpl->assign( 'GLANG', $classMusic->glanguage );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'CONFIG', $block_config );
		
		$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . 'index.php?' );

		$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
		$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

		$xtpl->assign( 'MODULE_FILE', $module_file );
		$xtpl->assign( 'MODULE_NAME', $module );
		$xtpl->assign( 'KEY', nv_substr( $nv_Request->get_title( 'q', 'get', '', 0 ), 0, NV_MAX_SEARCH_LENGTH ));
		$xtpl->assign( 'CHECKSESS', md5( $global_config['sitekey'] . session_id() ) );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_music_block_search( $block_config );
}