<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $lang_module, $module_data, $module_file, $module_info, $mainURL;
$xtpl = new XTemplate( "block_playlisttool.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'img_url', NV_BASE_SITEURL ."themes/" . $module_info['template'] ."/images/".$module_file."/" );
$xtpl->assign( 'URL', $mainURL . "=playlist" );
$xtpl->assign( 'URL_CREAT', $mainURL . "=creatalbum" );

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

/*
<script type=\"text/javascript\">
$(document).ready(function() {
	resultplaylist( 'OK_');
	$(\"#playlist\").hide();
    $(\"#showplaylist\").click(function () {
      $(\"#playlist\").slideToggle(\"slow\");
    });
});
</script>";
*/

?>