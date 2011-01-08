<?php
global $lang_module, $module_data, $module_file, $module_info, $mainURL;
$xtpl = new XTemplate( "block_hotalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

// lay id bai hat
$source = mysql_query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_playlist WHERE `active` = 1 ORDER BY view DESC LIMIT 0,8");
while($song =  mysql_fetch_array( $source ))
{
	$xtpl->assign( 'url_search_singer', '');	
	$xtpl->assign( 'url_listen', $mainURL . "=listenuserlist/" .$song['id']. "/" . $song['keyname']);
	$xtpl->assign( 'name', $song['name'] );
	$xtpl->assign( 'singer', $song['singer'] );
	$xtpl->assign( 'view', $song['view'] );
	
	$img = rand( 1, 10);
	$xtpl->assign( 'img', NV_BASE_SITEURL ."modules/" . $module_data . "/data/img(" . $img . ").jpg" );
	
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>