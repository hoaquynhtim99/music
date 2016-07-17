<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:26 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

// Hien thi bieu tuong cam xuc
if( $nv_Request->isset_request( 'loademotion', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$contents = nv_emotion_theme();

	include NV_ROOTDIR . '/includes/header.php';
	echo ( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

// Thong tin trang
$page_title = $module_info['custom_title'];
$description = $classMusic->setting['description'];
$key_words = $module_info['keywords'];

$contents = "";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';