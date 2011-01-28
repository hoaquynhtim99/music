<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $module_data, $mainURL, $db;
$content = '';

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY numview DESC LIMIT 0,10";
$query = $db->sql_query( $sql );

while($song =  $db->sql_fetchrow( $query ))
{
	$url_listen =  $mainURL . "=listenone/" .$song['id']. "/" . $song['ten'];
	$content .= "<a class=\"listsong\" href=\"" . $url_listen . "\">" . $song['tenthat'] . "</a>";
}
?>