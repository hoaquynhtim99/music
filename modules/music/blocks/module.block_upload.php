<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $module_file, $module_info, $mainURL;

$content = "
<p><a href=\"" . $mainURL . "=upload\">
<img style=\"border-width: 0px;\" height=\"80\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/uploadicon.jpg\" width=\"234\" /></a></p>";
?>