<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['set_global'];

$contents = '
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=hotalbum">' . $lang_module['sub_hotalbum'] . '</a>
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=fourcategory">' . $lang_module['sub_fourcategory'] . '</a>
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=maincategory">' . $lang_module['sub_maincategory'] . '</a>
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=mainalbum">' . $lang_module['sub_mainalbum'] . '</a>
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=ftpsetting">' . $lang_module['ftpsetting'] . '</a>
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting">' . $lang_module['music_setting'] . '</a>
    </caption>
</table>
' ;
 
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>