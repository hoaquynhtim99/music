<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$where =  isset( $array_op[1] ) ? $array_op[1]:0;
$id =  isset( $array_op[2] ) ? intval( $array_op[2] ) : 0;
$name = isset( $array_op[3] ) ?  $array_op[3]  : 0;
$allsinger = getallsinger();
$globaldata = array();
if ( $where == 'song' )
{
	$song = getsongbyID( $id );
	if ( $song['ten'] != $name ) die('Stop!!!');
	if ( $song['server'] != 0 )
	{
		$song['duongdan'] =  $global_config['site_url'] . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'];
	}
	$song['casi'] = $allsinger[$song['casi']];
	$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `numview` = numview+1 WHERE `id` =" . $id );
	$globaldata[] = $song;
}
elseif ( $where == 'video' )
{
	$song = getvideobyID( $id );
	if ( $song['name'] != $name ) die('Stop!!!');
	if ( $song['server'] != 0 )
	{
		$song['duongdan'] =  $global_config['site_url'] . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'];
	}
	$song['casi'] = $allsinger[$song['casi']];
	$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET view = view+1 WHERE `id` =" . $id );
	$globaldata[] = $song;
}
elseif ( $where == 'album' )
{
	$albumdata = getalbumbyID( $id );
	if ( $albumdata['name'] != $name ) die('Stop!!!');
	$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numview` = numview+1 WHERE `id` =" . $id );
	$sqlsong = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE album = \"" . $albumdata['name'] . "\" AND `active` = 1 ORDER BY id DESC";
	$querysong = $db->sql_query( $sqlsong );
	while ( $song = $db->sql_fetchrow( $querysong ) )
	{
		if ( $song['server'] != 0 )
		{
			$song['duongdan'] =  $global_config['site_url'] . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $song['duongdan'];
		}
		$song['casi'] = $allsinger[$song['casi']];
		$globaldata[] = $song;
	}
}
else die('Stop!!!');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo "\n";
echo '<playlist version="1" xmlns:jwplayer="http://developer.longtailvideo.com/">';
echo "\n";
echo '<trackList>';
echo "\n";
foreach ( $globaldata as $song )
{
echo 
	'<track> 
		<title>' . $song['tenthat'] . '</title>
		<creator>' . $song['casi'] . '</creator>
		<location>' . $song['duongdan'] . '</location>
		<info>' . $global_config['site_url'] . '</info>
		<image>' . $global_config['site_url'] . '/modules/' . $module_data . '/data/logo.png</image>
	</track>';
}
echo "\n";
echo "	</trackList>\n";
echo "</playlist>\n";

?>