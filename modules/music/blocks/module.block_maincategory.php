<?php
/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 09:17 AM
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info, $db;

$xtpl = new XTemplate( "block_maincategory.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'title', $lang_module['category'] );

$category = get_category() ;

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_main_category ORDER BY `order` ASC";
$query = $db->sql_query( $sql );
while( $song =  $db->sql_fetchrow( $query ) )
{
	$xtpl->assign( 'name', $category[$song['cid']] );
	$xtpl->assign( 'url', $mainURL . "=search/category/" .$song['cid'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>