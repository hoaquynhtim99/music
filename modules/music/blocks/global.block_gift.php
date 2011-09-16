<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung922gmail.com)
 * @Copyright (C) 2011
 * @Createdate 29/01/2011 02:41 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_music_gift_block' ) )
{
    function nv_music_gift_block ( $block_config )
    {
        global $module_array_cat, $module_info, $lang_module, $site_mods, $db;
        $module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];
		
		$block_file_lang = NV_ROOTDIR . "/modules/" . $file . "/language/block." . $block_config['block_name'] . "_" . NV_LANG_INTERFACE . ".php";
		if( file_exists( $block_file_lang ) )
		{
			include( $block_file_lang );
			
			$sql = "SELECT a.who_send, a.who_receive, a.time, a.body, b.id AS songid, b.ten AS songalias, b.tenthat AS songtitle, c.ten AS singeralias, c.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $data . "_gift` as a INNER JOIN `" . NV_PREFIXLANG . "_" . $data . "` AS b ON a.songid =b.id RIGHT JOIN `" . NV_PREFIXLANG . "_" . $data . "_singer` AS c ON b.casi=c.ten WHERE a.active=1 ORDER BY a.time DESC LIMIT 0,3";
			
			$query = $db->sql_query( $sql );
		
			if( $db->sql_numrows( $query ) )
			{
				if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_music_gift.tpl" ) )
				{
					$block_theme = $module_info['template'];
				}
				else
				{
					$block_theme = "default";
				}
				
				$xtpl = new XTemplate( "block_music_gift.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
				$xtpl->assign( 'LANG', $lang_block );
				
				$i = 1;
				while( $row =  $db->sql_fetchrow( $query ) )
				{
					$row['linksong'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=listenone/" . $row['songid'] . "/" . $row['songalias'];
					$row['search_singer'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=search/singer/" . $row['singeralias'];

					$num = strlen( $row['body'] );
					
					if( $num > 100 )
					{
						$body = $row['body'];
						$row['body'] = nv_clean60( $row['body'], 100 );
						
						if( preg_match( "/^(.*)\\.\.\.$/" , $row['body'] ) )
						{
							$row['body'] = substr( $row['body'], 0, -3 );
							$row['mbody'] = substr( $body, strlen( $row['body'] ) );
							
							$xtpl->assign( 'I', $i );
							$xtpl->assign( 'mbody', $row['mbody'] );
							$xtpl->parse( 'main.loop.more' );
						}
					}
					
					$xtpl->assign( 'ROW', $row );
					
					$xtpl->parse( 'main.loop' );
					$i ++;
				}
				
				$xtpl->parse( 'main' );
				return $xtpl->text( 'main' );
			}
		}
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $content = nv_music_gift_block( $block_config );
    }
}

?>