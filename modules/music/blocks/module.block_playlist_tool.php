<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_file, $module_info, $mainURL;


$content = "
<p align=\"center\">\n
	<a title=\"". $lang_module['playlist_listen'] ."\" href=\"". $mainURL . "=playlist\">\n
		<img style=\"cursor:pointer;\" height=\"40\" src=\"". NV_BASE_SITEURL ."themes/" . $module_info['template'] ."/images/".$module_file."/playlist.png\" width=\"40\" />\n
	</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n
	<a title=\"". $lang_module['playlist_delete'] ."\" onclick=\"delplaylist();\">\n
		<img style=\"cursor:pointer;\" height=\"40\" src=\"".NV_BASE_SITEURL ."themes/" . $module_info['template'] ."/images/".$module_file."/page_delete.png\" width=\"40\" />\n
	</a>\n
</p>\n";
?>