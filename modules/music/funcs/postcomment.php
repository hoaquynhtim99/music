<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
 
if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$difftimeout = 360;
$id = $nv_Request->get_int( 'id', 'post', 0 );
$body = filter_text_input( 'body', 'post', '', 1 );
$where = filter_text_input( 'where', 'post', '', 1 );

if ( defined( 'NV_IS_USER' ) )
{
    $name = $user_info['username'];
    $userid = $user_info['userid'];
}
elseif ( defined( 'NV_IS_ADMIN' ) )
{
    $name = $admin_info['username'];
    $userid = $admin_info['userid'];
}
else
{
    $name = filter_text_input( 'name', 'post', '', 1 );
    $userid = 0;
}

$contents = "";

$timeout = $nv_Request->get_int( $module_name . '_' . $op . '_' . $where . '_' . $id, 'cookie', 0 );

if ( $timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout )
{
    $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` (`id`, `name`, `body`, `dt`, `what`, `userid`) VALUES (NULL, " . $db->dbescape( $name ) . ", " . $db->dbescape( $body ) . ", NULL , " . $db->dbescape( $id ) . ",  " . $userid . " )";
    $result = $db->sql_query( $sql );
    if ( $result )
    {
        $nv_Request->set_Cookie( $module_name . '_' . $op . '_' . $where . '_' . $id, NV_CURRENTTIME );
        $contents = "OK_" . $id . "_" . $where . "_" . $lang_module['comment_success'];
    }
    else
    {
        $contents = "ERR_" . $lang_module['comment_error'];
    }
}
else
{
    $contents = "ERR_" . $lang_module['comment_timeouts'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>