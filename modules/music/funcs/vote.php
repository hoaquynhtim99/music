<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2010 05:04 PM
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 86400;
$id = $nv_Request->get_int( 'id', 'post', 0 );

if ( ! defined( 'NV_IS_USER' ) and ! defined( 'NV_IS_ADMIN' ) )
{
	echo "ERR_no_" . $lang_module['song_vote_err'];
}
else
{
	$timeout = $nv_Request->get_int( $module_name . '_vote_song_' . $id, 'cookie', 0 );
	if ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
	{
		$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET binhchon = binhchon+1 WHERE `id` =" . $id );
		$nv_Request->set_Cookie( $module_name . '_vote_song_' . $id, NV_CURRENTTIME );
		$song = getsongbyID ( $id );
		$numvote = $song['binhchon'];
		$endtime = NV_CURRENTTIME - 2592000;
		$result_time = $db->sql_query("SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` WHERE songid=" .  $id . " LIMIT 1");
		$nums_music = $db->sql_numrows( $result_time );
		if ( $nums_music == 0 ) 
		{
			$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` (`id`, `songid`, `dt`, `hit`) VALUES ( NULL, " . $db->dbescape( $id ) . ", " . $db->dbescape( NV_CURRENTTIME ) . ", 1 )");
		} 
		else 
		{
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` SET hit=hit+1 WHERE songid = " . $id ); 
		}
		$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topsong` WHERE dt < " . $endtime . "");
		echo "OK_" . $numvote . "_" . $lang_module['song_vote_success'];
	}
	else
	{
		echo "ERR_no_" . $lang_module['song_vote_timeout'];
	}
}

?>