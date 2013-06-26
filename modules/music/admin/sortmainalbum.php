<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$new = $nv_Request->get_int( 'new', 'post', 0 );
$old = $nv_Request->get_int( 'old', 'post', 0 );

if( $old != $new )
{
	changeorder( $old, 0, 'main_album' );

	if( $old < $new )
	{
		for( $i = $old; $i < $new; $i++ )
		{
			changeorder( $i + 1, $i, 'main_album' );
		}
	}
	else
	{
		for( $i = $old; $i > $new; $i-- )
		{
			changeorder( $i - 1, $i, 'main_album' );
		}
	}
	changeorder( 0, $new, 'main_album' );
}

echo $lang_module['update_success'];

?>