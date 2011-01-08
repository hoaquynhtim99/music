<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
    die( 'Stop!!!' );
}

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
// khoi tao
$contents = "";
$error = "";

//lay gia tri
$albumdata['ten'] = filter_text_input( 'ten', 'get,post', '' );
$albumdata['tenthat'] = filter_text_input( 'tenthat', 'post', '' );
$albumdata['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$albumdata['introduction'] = $nv_Request->get_string( 'introduction', 'post', '' );

// lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $id == 0 )
{
    $page_title = $lang_module['singer_add'];
}
else
{
    $page_title = $lang_module['singer_edit'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id` = ".$id."";
	$resuilt = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $resuilt );
	if ( !$nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$albumdata['ten'] = $row['ten'];
		$albumdata['tenthat'] = $row['tenthat'];
		$albumdata['thumb'] = $row['thumb'];
		$albumdata['introduction'] = $row['introduction'];
	}
}

//sua album
if ( ($nv_Request->get_int( 'edit', 'post', 0 )) == 1 )
{
	foreach ( $albumdata as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=singer"); die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}

// them album
if ( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{	
	
	foreach ( $albumdata as $data => $null )
	{
		if ( $data == 'introduction' ) continue;
		if	($null == ''& $data !="album") $error = $lang_module['singer_error']; 
	}
	if ( $error == "" )
	{
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` 
		(
			`id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numalbum`
		) 
		VALUES 
		( 
			NULL, 
			" . $db->dbescape( $albumdata['ten'] ) . ", 
			" . $db->dbescape( $albumdata['tenthat'] ) . ", 
			" . $db->dbescape( $albumdata['thumb'] ) . ", 
			" . $db->dbescape( $albumdata['introduction'] ) . ", 
			0,
			0
		)
		"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=singer"); die();
		} 
		else 
		{ 
			$error = $lang_module['error_save']; 
		} 

	}

}
// hien bao loi
if($error)
{
	$contents .= "<div class=\"quote\" style=\"width: 780px;\">\n
					<blockquote class=\"error\">
						<span>".$error."</span>
					</blockquote>
				</div>\n
				<div class=\"clear\">
				</div>";
}
// noi dung
$contents .="
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					".$lang_module['singer_info']."
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['singer_name']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"".$albumdata['tenthat']."\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"".NV_BASE_SITEURL."images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['song_name_short']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"".$albumdata['ten']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['thumb']."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"".$albumdata['thumb']."\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"thumb\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/singerthumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type=image", "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['describle']."
				</td>
				<td style=\"background: #eee;\">";
				if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
				{
					$contents .= nv_aleditor( 'introduction', '680px', '250px', $albumdata['introduction'] );
				}
				else
				{
					$contents .= "<textarea style=\"width: 680px\" value=\"".$albumdata['introduction']."\" name=\"introduction\" id=\"introduction\" cols=\"20\" rows=\"15\"></textarea>\n";
				}
				$contents .="
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"".$lang_module['save']."\" type=\"submit\">\n";
					if ( $id == 0 ) 
						$contents .="<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n";
					else
						$contents .="<input type=\"hidden\" name=\"edit\" id=\"edit\" value=\"1\">\n";
                    $contents .="<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n";
// neu khong co ten album thi tu dong tao
if ( empty( $albumdata['ten'] ) )
{
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= '$("#idtitle").change(function () {
                    get_alias(\'idtitle\', \'res_get_alias\');
                });';
    $contents .= "</script>\n";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
