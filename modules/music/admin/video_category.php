<?php

/**
 * @Project NUKEVIET 3.0
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 25-12-2010 14:43
 */
 
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

$page_title = $lang_module['manager_category'];

$contents = "<br /><p><strong>" . $lang_module['manager_category_info'] . "</strong></p>";
$error = ""; 

$add = 0; 
$data= filter_text_input( 'title', 'post,get', '' );
$id = $nv_Request->get_int( 'id', 'post,get', 0 );	
$title = $data ;

if( $id )
{
	$sqla = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` WHERE id = ".$id."";
	$resuilta = $db->sql_query( $sqla );
	$rowa = $db->sql_fetchrow( $resuilta );
	$title = $rowa['title'];
}

// sua mot the loai
if( $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
{
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` SET `title` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "";
    $db->sql_query( $sql );
	nv_del_moduleCache( $module_name );
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=music&" . NV_OP_VARIABLE . "=video_category" );
    exit();  
}

// them the loai
if ( $nv_Request->get_int( 'add', 'post' ) == 1 ) 
{ 
	if ( empty( $title ) ) { $error = $lang_module['error_full_info']; }
	else 
	{ 
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_video_category` (`id`, `title`) VALUES ( NULL, " . $db->dbescape( $title ) . ")"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$db->sql_freeresult();
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=music&" . NV_OP_VARIABLE . "=video_category" ); die(); 
		} 
		else { $error = $lang_module['error_save']; } 
	} 
} 
// hien thong bao loi
if ( $error != "" ) 
{ 
	$contents .= "<div class=\"quote\" style=\"width:780px;\">\n"; 
	$contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n"; 
	$contents .= "</div>\n"; 
	$contents .= "<div class=\"clear\"></div>\n"; 
} 
// noi dung
$contents .= "<br /><strong>".$lang_module['fillin']."<br /></strong>";
$contents .= "<form method=\"post\">"; 
$contents .= " 		<input type=\"text\" name=\"title\" value=\" " . $title . " \" size=\"100\">\n"; 
$contents .= " 		<input type=\"submit\" value=\"OK\"/>\n"; 
if (!$id)
$contents .= " 		<input name=\"add\" type=\"hidden\" value=\"1\" />\n";
else 
$contents .= " 		<input name=\"edit\" type=\"hidden\" value=\"1\" />\n";
$contents .= "</form>\n
<table class=\"tab1\">\n
<thead>\n
<tr style=\"text-align:center;\">\n
	<td>ID</td>
	<td>".$lang_module['category']."</td>\n
	<td>".$lang_module['feature']."</td>\n
</tr>
</thead>";
$category = get_videocategory() ;
foreach ( $category as $key => $data )
{
	$link = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&" . NV_OP_VARIABLE . "=addvideo&theloai=". $key ;
	$contents.="
	<tr>
		<td style=\"text-align:center;background: #eee;\">". $key ."</td>
		<td style=\"background: #eee;\">". $data ."</td>
		<td style=\"text-align:center;background: #eee;\">		
		<a href=\"index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video_category&id=" . $key . "\">". $lang_module['edit'] ."</a>
		<span class=\"add_icon\">
		<a href=\"".$link."\">". $lang_module['video_add'] ."</a>
		&nbsp;&nbsp;
		</span>
		</td> 
	</td>";
}
$contents.="</table>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>