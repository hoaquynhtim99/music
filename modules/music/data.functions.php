<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_music_global_menu( $mod_name, $lang )
{
	$nv_music_menu = array();
	$nv_music_menu[] = array(
		$lang['menu1'], //
		NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name, //
		0, //
		'submenu' => array() //
			);
	$nv_music_menu[] = array(
		$lang['video'],
		NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . '&amp;' . NV_OP_VARIABLE . "=video",
		0,
		'submenu' => array() );
	$nv_music_menu[] = array(
		$lang['menu4'],
		NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . '&amp;' . NV_OP_VARIABLE . "=creatalbum",
		0,
		'submenu' => array() );
	$nv_music_menu[] = array(
		$lang['menu3'],
		NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . '&amp;' . NV_OP_VARIABLE . "=managersong",
		0,
		'submenu' => array() );

	return $nv_music_menu;
}

?>