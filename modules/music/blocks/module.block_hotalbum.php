<?php
global $lang_module, $module_data, $module_file, $module_info, $mainURL;
$xtpl = new XTemplate( "block_hotalbum.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

// lay id bai hat
$source = mysql_query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album ORDER BY numview DESC LIMIT 0,8");
while($song =  mysql_fetch_array( $source ))
{
	$xtpl->assign( 'url_search_singer', $mainURL . "=search/singer/" . $song['casi']);	
	$xtpl->assign( 'url_listen', $mainURL . "=listenlist/" .$song['id']. "/" . $song['name']);
	$xtpl->assign( 'name', $song['tname'] );
	$xtpl->assign( 'singer', $song['casithat'] );
	$xtpl->assign( 'view', $song['numview'] );
	$xtpl->assign( 'img', $song['thumb'] );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
?>