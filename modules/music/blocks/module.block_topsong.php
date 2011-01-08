<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $module_data, $mainURL;
$content = '';

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY numview DESC LIMIT 0,10";
$query = mysql_query( $sql );
while($song =  mysql_fetch_array( $query ))
{
	$url_listen =  $mainURL . "=listenone/" .$song['id']. "/" . $song['ten'];
	$content .= "<a class=\"listsong\" href=\"".$url_listen."\">".$song['tenthat']."</a>";
}

?>