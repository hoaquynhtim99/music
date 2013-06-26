<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

// Get lang
$path_lang_ini = NV_ROOTDIR . "/modules/" . $m_values['module_file'] . "/language/search.ini";
$xml = simplexml_load_file( $path_lang_ini );

if( $xml !== false )
{
	$xmllanguage_tmp = $xml->xpath( 'language' );
	$language_tmp = ( array )$xmllanguage_tmp[0];
	$lang_search = ( array )$language_tmp[NV_LANG_INTERFACE];

	$type_search = "";
	$type = explode( " ", $dbkeyword );
	if( in_array( strtolower( $type[0] ), array(
		"song",
		"video",
		"album",
		"playlist" ) ) )
	{
		$type_search = strtolower( $type[0] );
		unset( $type[0] );
		$dbkeyword = implode( " ", $type );
	}

	if( empty( $type_search ) or ( $type_search == "song" ) )
	{
		// search song
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `ten`, `tenthat` 
		FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "` 
		WHERE `active`=1 
		AND " . nv_like_logic( 'tenthat', $dbkeyword, $logic ) . " 
		ORDER BY `id` DESC 
		LIMIT " . $pages . "," . $limit;

		$tmp_re = $db->sql_query( $sql );

		$result = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result );

		if( $all_page )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=listenone/';

			while( list( $id, $ten, $tenthat ) = $db->sql_fetchrow( $tmp_re ) )
			{
				$result_array[] = array( //
					'link' => $link . $id . '/' . $ten, //
					'title' => $lang_search['song'] . ": " . BoldKeywordInStr( $tenthat, $key, $logic ), //
					'content' => "" //
						);
			}
		}
	}
	elseif( $type_search == "video" )
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `name`, `tname` 
		FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_video` 
		WHERE `active`=1 
		AND " . nv_like_logic( 'tname', $dbkeyword, $logic ) . " 
		ORDER BY `id` DESC 
		LIMIT " . $pages . "," . $limit;

		$tmp_re = $db->sql_query( $sql );

		$result = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result );

		if( $all_page )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=viewvideo/';

			while( list( $id, $ten, $tenthat ) = $db->sql_fetchrow( $tmp_re ) )
			{
				$result_array[] = array( //
					'link' => $link . $id . '/' . $ten, //
					'title' => $lang_search['video'] . ": " . BoldKeywordInStr( $tenthat, $key, $logic ), //
					'content' => "" //
						);
			}
		}
	}
	elseif( $type_search == "album" )
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `name`, `tname` 
		FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_album` 
		WHERE `active`=1 
		AND " . nv_like_logic( 'tname', $dbkeyword, $logic ) . " 
		ORDER BY `id` DESC 
		LIMIT " . $pages . "," . $limit;

		$tmp_re = $db->sql_query( $sql );

		$result = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result );

		if( $all_page )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=listenlist/';

			while( list( $id, $ten, $tenthat ) = $db->sql_fetchrow( $tmp_re ) )
			{
				$result_array[] = array( //
					'link' => $link . $id . '/' . $ten, //
					'title' => $lang_search['album'] . ": " . BoldKeywordInStr( $tenthat, $key, $logic ), //
					'content' => "" //
						);
			}
		}
	}
	else
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `keyname`, `name` 
		FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_playlist` 
		WHERE `active`=1 
		AND " . nv_like_logic( 'name', $dbkeyword, $logic ) . " 
		ORDER BY `id` DESC 
		LIMIT " . $pages . "," . $limit;

		$tmp_re = $db->sql_query( $sql );

		$result = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result );

		if( $all_page )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=listenuserlist/';

			while( list( $id, $ten, $tenthat ) = $db->sql_fetchrow( $tmp_re ) )
			{
				$result_array[] = array( //
					'link' => $link . $id . '/' . $ten, //
					'title' => $lang_search['playlist'] . ": " . BoldKeywordInStr( $tenthat, $key, $logic ), //
					'content' => "" //
						);
			}
		}
	}
}

?>