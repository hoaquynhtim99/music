<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
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
unlinkSV ( $song['server'], $song['duongdan'] );
$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
echo $id;
?>