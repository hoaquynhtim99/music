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
        global $module_array_cat, $module_info, $lang_module, $site_mods, $nv_vertical_menu;
        $module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];
		

		if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_music_menu.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}
			
		$xtpl = new XTemplate( "block_music_menu.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
			
		foreach ( $nv_vertical_menu as $menu )
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
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $content = nv_music_menu( $block_config );
    }
}

?>