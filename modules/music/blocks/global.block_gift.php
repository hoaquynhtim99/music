<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_music_gift_block' ) )
{
	function nv_music_gift_block( $block_config )
	{
		global $module_array_cat, $module_info, $lang_module, $site_mods, $db, $module_name, $my_head;
		$module = $block_config['module'];
		$data = $site_mods[$module]['module_data'];
		$file = $site_mods[$module]['module_file'];

		// Neu khong phai la module music
		if( $module_name != $module )
		{
			$my_head .= '<script type="text/javascript">function ShowHide(what){$("#"+what+"").animate({"height": "toggle"}, { duration: 1 });}</script>';
		}

		$block_file_lang = NV_ROOTDIR . "/modules/" . $file . "/language/block." . $block_config['block_name'] . "_" . NV_LANG_INTERFACE . ".php";
		if( file_exists( $block_file_lang ) )
		{
			include ( $block_file_lang );

			$sql = "SELECT a.who_send, a.who_receive, a.time, a.body, b.id AS songid, b.ten AS songalias, b.tenthat AS songtitle, c.ten AS singeralias, c.tenthat AS singername FROM `" . NV_PREFIXLANG . "_" . $data . "_gift` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $data . "` AS b ON a.songid =b.id LEFT JOIN `" . NV_PREFIXLANG . "_" . $data . "_singer` AS c ON b.casi=c.id WHERE a.active=1 ORDER BY a.id DESC LIMIT 0,3";

			$list = nv_db_cache( $sql, 0, $module );

			if( ! empty( $list ) )
			{
				if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_music_gift.tpl" ) )
				{
					$block_theme = $module_info['template'];
				}
				else
				{
					$block_theme = "default";
				}

				$xtpl = new XTemplate( "block_music_gift.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
				$xtpl->assign( 'LANG', $lang_block );
				$xtpl->assign( 'GIFT_LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=gift" );

				$i = 1;
				foreach( $list as $gift )
				{
					$xtpl->assign( 'url_listen', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . '&amp;' . NV_OP_VARIABLE . "=listenone/" . $gift['songid'] . "/" . $gift['songalias'] );
					$xtpl->assign( 'from', $gift['who_send'] );
					$xtpl->assign( 'to', $gift['who_receive'] );
					$xtpl->assign( 'time', nv_date( "d/m/Y H:i", $gift['time'] ) );

					$sub = explode( ' ', $gift['body'] );
					$bodymini = $bodyfull = '';
					foreach( $sub as $i => $value )
					{
						if( $i < 25 )
						{
							$bodymini .= " " . $value;
						}
						else
						{
							$bodyfull .= " " . $value;
						}
					}

					$xtpl->assign( 'message', $bodymini );
					$xtpl->assign( 'fullmessage', $bodyfull );
					$xtpl->assign( 'DIV', $i );
					$xtpl->assign( 'songname', $gift['songtitle'] );

					$i++;
					$xtpl->parse( 'main.loop' );
				}

				$xtpl->parse( 'main' );
				return $xtpl->text( 'main' );
			}
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $global_array_cat, $module_array_cat;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		$content = nv_music_gift_block( $block_config );
	}
}

?>