<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['sub_comment'];
list( $numsong ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_song`" ) );
list( $numalbum ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_album`" ) );
list( $numvideo ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_video`" ) );

$contents = '
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=commentsong">' . $lang_module['sub_commentsong'] . '</a> (' . $numsong . ')
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=commentalbum">' . $lang_module['sub_commentalbum'] . '</a> (' . $numalbum . ')
    </caption>
</table>
<table class="tab1">
    <caption>
        <a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=commentvideo">' . $lang_module['sub_commentvideo'] . '</a> (' . $numvideo . ')
    </caption>
</table>
' ;
 
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>