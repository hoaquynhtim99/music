<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// So bai hat
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_numsong'], 'value' => $number );
}

// So video
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_video`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_numvideo'], 'value' => $number );
}

// So album
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_album`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_numalbum'], 'value' => $number );
}

// So binh luan cho bai hat
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comment_song`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_commentsong'], 'value' => $number );
}

// So binh luan cho album
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comment_album`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_commentalbum'], 'value' => $number );
}

// So bao loi chua doc
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_error`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_error'], 'value' => $number );
}

// So qua tang
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_gift`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_gift'], 'value' => $number );
}

?>