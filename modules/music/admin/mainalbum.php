<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['album_menu'];

$sql = "SELECT a.id, a.albumid, a.order, b.tname FROM `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_album` AS b ON a.albumid=b.id ORDER BY a.order ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$id = $nv_Request->get_int( 'id', 'get', 0 );
if( ! empty( $id ) )
{
	$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	list( $exist ) = $db->sql_fetchrow( $result );

	if( empty( $exist ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

	$numnew = $num + 1;
	$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_main_album` (`id`, `albumid`, `order`) VALUES ( NULL, " . $db->dbescape( $id ) . ", " . $db->dbescape( $numnew ) . ")" );
	nv_del_moduleCache( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$xtpl = new XTemplate( "mainalbum.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );

// Xu li cac du lieu
$i = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'id', $row['id'] );
	$xtpl->assign( 'name', $row['tname'] );

	$class = ( $i % 2 ) ? " class=\"second\"" : "";

	$xtpl->assign( 'class', $class );
	$xtpl->assign( 'URL_DEL_ONE', "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del&where=_main_album&id=" . $row['id'] );

	for( $j = 0; $j < $num; $j++ )
	{
		$xtpl->assign( 'VAL', $j + 1 );

		if( $j == $i )
		{
			$xtpl->assign( 'SELECT', 'selected="selected"' );
		}
		else
		{
			$xtpl->assign( 'SELECT', '' );
		}

		$xtpl->parse( 'main.row.sel.sel_op' );
	}

	$xtpl->assign( 'SEL_W', $row['order'] );
	$xtpl->parse( 'main.row.sel' );
	$xtpl->parse( 'main.row' );

	$i++;
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>