<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$num = $nv_Request->get_int( $module_name . '_numlist' , 'cookie', 0 );

$id = array();
for ( $i = 1 ; $i <= $num ; $i ++ )
{
	$id[] = $nv_Request->get_int( $module_name . '_song' . $i, 'cookie', 0 );
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN (" . implode( ",", $id ) . ") AND `active`=1";
$result = $db->sql_query( $sql );

$array = array();
while( $row = $db->sql_fetchrow( $result ) )
{
	$array[] = array(
		"name" => $row['tenthat'],  //
		"url" => $mainURL . "=listenone/" . $row['id'] . "/" . $row['ten'],  //
	);
}

$contents = nv_music_showplaylist( $array );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>