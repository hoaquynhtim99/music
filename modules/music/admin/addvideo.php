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
//khoi tao
$contents = "";
$error = "";
//lay gia tri
$videodata['name'] = filter_text_input( 'name', 'post', '' );
$videodata['tname'] = filter_text_input( 'tname', 'post', '' );
$videodata['casi'] = filter_text_input( 'casi', 'post', '' );
$videodata['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$videodata['theloai'] = $nv_Request->get_int( 'theloai', 'get,post', 0 );
$videodata['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
$videodata['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );

if ( $videodata['casimoi'] != '')
{
	$videodata['casi'] = change_alias( $videodata['casimoi'] );
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` 
	(
		`id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numalbum`
	) 
	VALUES 
	( 
		NULL, 
		" . $db->dbescape( $videodata['casi'] ) . ", 
		" . $db->dbescape( $videodata['casimoi'] ) . ", 
		'', 
		'', 
		0, 
		0
	)
	"; 
	if ( $db->sql_query_insert_id( $query ) ) 
	{ 
		$db->sql_freeresult();
	} 
	else 
	{ 
		$error = $lang_module['singer_new_added']; 
	} 
}
$category = get_videocategory() ;
$allsinger = getallsinger();
$setting = setting_music();
// lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $id == 0 )
{
    $page_title = $lang_module['video_add'];
}
else
{
    $page_title = $lang_module['video_edit'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id` = ".$id."";
	$resuilt = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $resuilt );
	if ( !$nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$videodata['name'] = $row['name'];
		$videodata['tname'] = $row['tname'];
		$videodata['casi'] = $row['casi'];
		$videodata['theloai'] = $row['theloai'];
		$videodata['duongdan'] = $row['duongdan'];
		$videodata['thumb'] = $row['thumb'];	
		
		if( $row['server'] != 0 )
		{
			$videodata['duongdan'] = "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/video/" . $row['duongdan'];
		}
		else
		{
			$videodata['duongdan'] = $row['duongdan'];
		}
	}
}

//sua video
if ( (($nv_Request->get_int( 'edit', 'post', 0 )) == 1) && ($error =='') )
{
	if (preg_match('/^(ht|f)tp:\/\//', $videodata['duongdan'])) 
	{
		$videodata['server'] = 0;
	}
	else
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/video/" );
		$videodata['duongdan'] = substr( $videodata['duongdan'], $lu );
		$videodata['server'] = 1;
	}

	foreach ( $videodata as $key => $data  )
	{	
		$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" ); die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}

// them video moi
if ( ($nv_Request->get_int( 'add', 'post', 0 ) == 1) && ($error =='') )
{	
	
	foreach ( $videodata as $data => $null )
	{
		if ( $data == 'casimoi' ) continue;
		if	($null == '') $error = $lang_module['error_video']; 
	}
	if ( $error == "" )
	{
		if (preg_match('/^(ht|f)tp:\/\//', $videodata['duongdan'])) 
		{
			$data = $videodata['duongdan'];
			$server = 0;
		}
		else
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/video/" );
			$data = substr( $videodata['duongdan'], $lu );
			$server = 1;
		}
	
		updatesinger( $videodata['casi'], 'numvideo', '+1' );
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_video` 
		(
			`id`, `name`, `tname`, `casi`, `theloai`, `duongdan`, `thumb`, `view`, `active`, `dt`, `server`
		) 
		VALUES 
		( 
			NULL, 
			" . $db->dbescape( $videodata['name'] ) . ", 
			" . $db->dbescape( $videodata['tname'] ) . ", 
			" . $db->dbescape( $videodata['casi'] ) . ", 
			" . $db->dbescape( $videodata['theloai'] ) . ", 
			" . $db->dbescape( $data )  . ", 
			" . $db->dbescape( $videodata['thumb'] ) . " ,
			0,
			1,
			UNIX_TIMESTAMP() ,
			" . $server . "
		)
		"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" ); die();
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
// noi dung trsng
$contents .="
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					".$lang_module['video_info']."
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['video_name']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tname\" style=\"width: 470px;\" value=\"".$videodata['tname']."\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"".NV_BASE_SITEURL."images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['video_name_short']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"name\" style=\"width: 470px;\" value=\"".$videodata['name']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['singer']."	
				</td>
				<td style=\"background: #eee;\">
					<select name=\"casi\">\n";
					foreach ( $allsinger as $key => $title )
					{
						$i= "";
						if ( $videodata['casi'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['singer_new']."	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"singer_sortname\" name=\"casimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['category']."
				</td>
				<td style=\"background: #eee;\">
					<select name=\"theloai\">\n";
					foreach ( $category as $key => $title )
					{
						$i= "";
						if ( $videodata['theloai'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['link']."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duongdan\" name=\"duongdan\" style=\"width: 370px;\" value=\"".$videodata['duongdan']."\" type=\"text\" />
                <input name=\"selectvideo\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=selectvideo]\").click(function()
				{
					var area = \"duongdan\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/" . $setting['root_contain'] . "/video\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['thumb']."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"".$videodata['thumb']."\" type=\"text\"  readonly=\"readonly\"/>
                <input name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"thumb\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/clipthumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
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
// Neu ten ngan gon bai hat chua có thi tu dong tao ten
if ( empty( $videodata['ten'] ) )
{
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= "$(\"#idtitle\").change(function () {
                    get_alias('idtitle', 'res_get_alias');
                });";
    $contents .= "</script>\n";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
