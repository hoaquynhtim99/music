<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 09:41 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

/**
 * nv_music_listenone()
 * 
 * @param mixed $gdata
 * @param mixed $sdata
 * @param mixed $cdata
 * @param mixed $ldata
 * @return
 */
function nv_music_listenone ( $gdata, $sdata, $cdata, $ldata )
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $setting, $lang_global;
    
	$xtpl = new XTemplate( "listenone.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	
	$xtpl->assign( 'SDATA', $sdata );
	$xtpl->assign( 'GDATA', $gdata );
	$xtpl->assign( 'CDATA', $cdata );
	
	if ( $cdata['checkhit'] >= 20 )
	{
		$xtpl->parse( 'main.hit' );
	}

	if ( $ldata['number'] >= 1 )
	{
		$i = 1 ;
		foreach ( $ldata['data'] as $rowlyric )
		{	
			if ( ( $ldata['number'] > 1 ) && ( $i < $ldata['number'] ) )
			{
				$nextdiv = $i + 1 ;
				$xtpl->assign( 'nextdiv', $nextdiv );
				$xtpl->parse( 'main.lyric.next' );			
			}
			
			if ( $i > 1 )
			{
				$prevdiv = $i - 1 ;
				$xtpl->assign( 'prevdiv', $prevdiv );
				$xtpl->parse( 'main.lyric.prev' );					
			}

			$xtpl->assign( 'LYRIC_DATA', $rowlyric );		
			
			$xtpl->assign( 'thisdiv', $i );
			$xtpl->parse( 'main.lyric' );
			$i ++ ;
		}
	}
	else
	{
		$xtpl->parse( 'main.nolyric' );
	}

	// Send gift
	if ( ( $setting['who_gift'] == 0 ) &&  ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nogift' );
	}
	elseif ( $setting['who_gift'] == 2 )
	{
		$xtpl->parse( 'main.stopgift' );	
	}
	else
	{
		$xtpl->parse( 'main.gift' );
	}
	
	// Send lyric
	if ( ( $setting['who_lyric'] == 0 ) &&  ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.noaccesslyric' );
	}
	elseif ( $setting['who_lyric'] == 2 )
	{
		$xtpl->parse( 'main.stoplyric' );	
	}
	else
	{
		$xtpl->parse( 'main.accesslyric' );
	}

	// Comment
	if ( ( $setting['who_comment'] == 0 ) && ! defined( 'NV_IS_USER' ) && ! defined( 'NV_IS_ADMIN' ) )
	{
		$xtpl->parse( 'main.nocomment' );
	}
	elseif ( $setting['who_comment'] == 2 )
	{
		$xtpl->parse( 'main.stopcomment' );	
	}
	else
	{
		require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/emotions.php';
		$xtpl->assign( 'EMOTIONS', show_emotions() );
		$xtpl->parse( 'main.comment' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>