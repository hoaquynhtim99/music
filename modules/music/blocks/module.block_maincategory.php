<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:57:52 PM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );
global $lang_module, $module_data, $module_file, $module_info;


$xtpl = new XTemplate( "block_maincategory.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'title', $lang_module['category'] );

$category = array() ;
$result = mysql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category " );
while($rs = $db->sql_fetchrow($result))
{
	$category[ $rs['id'] ] = $rs[ 'title' ] ;
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_main_category ORDER BY `order` ASC";
$query = mysql_query( $sql );
while($song =  mysql_fetch_array( $query ))
{
	$xtpl->assign( 'name', $category[$song['cid']] );
	$xtpl->assign( 'url', $mainURL . "=search/category/" .$song['cid'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>