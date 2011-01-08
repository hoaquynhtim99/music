<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if (! defined ( 'NV_IS_MUSIC_ADMIN' )) {
	die ( 'Stop!!!' );
}
$id = $nv_Request->get_int ( 'id', 'get', 0 );
$where = filter_text_input( 'where', 'get', '' );

$sql = "SELECT active FROM `" . NV_PREFIXLANG . "_" . $module_data . $where . "`
					  WHERE `id` =" . $id . ";";
		$result = $db->sql_query($sql);
list($active) = $db->sql_fetchrow($result);
$active = ($active == 1) ? 0 : 1;
$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "`
					SET `active` = " . $db->dbescape($active) . "
					WHERE `id` =" . $id . ";";
$db->sql_query($sql);
		
$str = ($active == 1) ? $lang_module['active_yes'] : $lang_module['active_no'];
echo $lang_module['active_succer']. " \"". $str . " \"";
?>