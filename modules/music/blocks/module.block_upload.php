<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $module_file, $module_info, $mainURL;

$content = "
<p><a href=\"" . $mainURL . "=upload\">
<img style=\"border-width: 0px;\" height=\"80\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/uploadicon.jpg\" width=\"234\" /></a></p>";
?>