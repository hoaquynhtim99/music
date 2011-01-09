<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$id =  isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;
$name = isset( $array_op[2] ) ?  $array_op[2]  : 0;
$allsinger = getallsinger();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id . " AND `active` = 1 ";
$query = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $query );

// update bai hat
$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `numview` = numview+1 WHERE `id` =" . $id );

if ( $row['server'] != 0 )
{
	$link =  $global_config['site_url'] . $songURL . $row['duongdan'];
}
else
{
	$link = $row['duongdan'];
}

if ( $name == $row['ten'] )
{
echo '
<playlist version="1" xmlns="http://xspf.org/ns/0/">
   <title>' . $global_config['site_url'] . '</title>
   <info>' . $global_config['site_url'] . '</info>
   <tracklist>
      <track>
         <title>' . $row['tenthat'] . '</title>
         <creator>' . $allsinger[$row['casi']] . '</creator>
         <info>' . $global_config['site_url'] . '</info>
         <annotation>' . $allsinger[$row['casi']] . '</annotation>
         <location>' . $link . '</location>
      </track>
   </tracklist>
</playlist>	';
}
?>