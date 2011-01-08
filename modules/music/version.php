<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/12/2010 09:47
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' )) die( 'Stop!!!' );
 
$module_version = array( 
"name" => "Music", // Tieu de module
"modfuncs" => "main, listenone, listenlist, search, playlist, album, song" , // Cac function co block
"is_sysmod" => 0, // Co phai la module he thong hay khong
"virtual" => 1, // Co cho phep ao hao module hay khong
"version" => "3.0.01", // Phien ban cua modle
"date" => "Mon, 27 Dec 2010 12:47:15 GMT", // Ngay phat hanh phien ban
"author" => "PHAN TAN DUNG (email: phantandung1912@gmail.com)", // 
"note"=>"", // ghi chu
"uploads_dir" => array(
	$module_name, 
	$module_name . "/data", 
	$module_name . "/thumb", 
	$module_name . "/ads", 
	$module_name . "/tmp")
);
?>