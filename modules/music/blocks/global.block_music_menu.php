<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011
 * @Createdate 29/01/2011 02:41 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_music_menu' ) )
{
    function nv_music_menu ( $block_config )
    {
        global $module_info, $lang_module, $site_mods, $nv_vertical_menu, $module_name;
		
        $module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];
		
		// Neu module khong phai là module music
		if( $module_name != $module )
		{
			// Goi ra ngon ngu cho block
			include NV_ROOTDIR . "/modules/" . $file . "/language/block." . $block_config['block_name'] . "_" . NV_LANG_INTERFACE . ".php";
			
			// Goi ra file data
			if ( ! nv_function_exists( 'nv_music_global_menu' ) )
			{
				include NV_ROOTDIR . "/modules/" . $file . '/data.functions.php';
			}
			
			$_menu = nv_music_global_menu( $module, $lang_block );
		}
		else
		{
			$_menu = $nv_vertical_menu;
		}

		if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_music_menu.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		elseif ( file_exists( NV_ROOTDIR . "/themes/default/modules/" . $file . "/block_music_menu.tpl" ) )
		{
			$block_theme = "default";
		}
		else
		{
			$block_theme = "modern";
		}
			
		$xtpl = new XTemplate( "block_music_menu.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
			
		foreach ( $_menu as $menu )
		{
			$xtpl->assign( 'MENU', $menu );
			$xtpl->parse( 'main.loop' );
		}
			
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $content = nv_music_menu( $block_config );
    }
}

?>