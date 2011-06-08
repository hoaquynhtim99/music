<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 10:12 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$channel = array();
$items = array();

$channel['title'] = $global_config['site_name'] . ' RSS: ' . $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = $global_config['site_description'];

// Get lang
$path_lang_ini = NV_ROOTDIR . "/modules/" . $module_file . "/language/rss.ini";
$xml = simplexml_load_file( $path_lang_ini );
		
if ( $xml !== false and $module_info['rss'] )
{
	$xmllanguage_tmp = $xml->xpath( 'language' );
	$language_tmp = ( array )$xmllanguage_tmp[0];
	$lang_rss = ( array )$language_tmp[NV_LANG_INTERFACE];
	
	if ( isset ( $array_op[1] ) )
	{	
		$type = $array_op[1];
		
		if ( $type == change_alias ( $lang_rss['rss_music'] ) )
		{
		
		}
		
		$get_data = explode( "-", $array_op[1] );
		$id = intval( end( $get_data ) );
		$number = strlen( $id ) + 1;
		$album_alias = substr( $array_op[1], 0, - $number );
	}
	else
	{
		$sql = "SELECT a.id AS id, a.ten AS ten, a.tenthat AS tenthat, b.tenthat AS casithat, a.dt AS add_time FROM `" . NV_PREFIXLANG . "_" . $module_data . "` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_singer` AS b ON a.casi=b.ten ORDER BY a.id DESC LIMIT 30";
		
		if ( ( $result = $db->sql_query( $sql ) ) !== false )
		{
			while ( list( $id, $ten, $tenthat, $casithat, $add_time ) = $db->sql_fetchrow( $result ) )
			{
				//$rimages = ( ! empty( $logo ) ) ? "<img src=\"" . NV_MY_DOMAIN . $logo . "\" width=\"100\" align=\"left\" border=\"0\">" : "";
				$items[] = array(  //
					'title' => $tenthat . " - " . $casithat, //
					'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=listenone/" . $id . "/" . $ten, //
					'guid' => $module_name . '_' . $id, //
					'description' => "", //
					'pubdate' => $add_time  //
				);
			}
		}
	}
}

nv_rss_generate( $channel, $items );
die();

?>