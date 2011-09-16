<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011
 * @Createdate 29/01/2011 02:41 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_new_album_boost' ) )
{
    function nv_new_album_boost ( $block_config )
    {
        global $module_array_cat, $module_info, $lang_module, $site_mods;
        $module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];
		
        $sql = "SELECT a.id, a.name, a.tname, a.thumb, a.casi, b.tenthat FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_album` as a INNER JOIN `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_singer` AS b ON a.casi =b.ten WHERE a.active=1 ORDER BY a.id DESC LIMIT 0,8";
        $list = nv_db_cache( $sql, 'id', $module );

        if ( ! empty( $list ) )
        {
            if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_new_album_boost.tpl" ) )
            {
                $block_theme = $module_info['template'];
            }
            else
            {
                $block_theme = "default";
            }
			
            $xtpl = new XTemplate( "block_new_album_boost.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
			
            foreach ( $list as $row )
            {
				$row['linkalbum'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=listenlist/" . $row['id'] . "/" . $row['name'];
				$row['search_singer'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=search/singer/" . $row['casi'];
				
                $xtpl->assign( 'ROW', $row );
				
                $xtpl->parse( 'main.loop' );
            }
			
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $content = nv_new_album_boost( $block_config );
    }
}

?>