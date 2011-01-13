<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

$song = getsongbyID ( $id );
updatesinger( $song['casi'], 'numsong', '-1' );
delcomment('song', $song['id']);
dellyric($song['id']);
delerror( 'song', $song['id'] );
delgift( $song['id'] );
updatealbum( $song['album'], '-1' );
$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );

echo $id;
?>