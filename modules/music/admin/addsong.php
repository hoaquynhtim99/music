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

$songdata['ten'] = filter_text_input( 'ten', 'post', '' );
$songdata['tenthat'] = filter_text_input( 'tenthat', 'post', '' );
$songdata['casi'] = filter_text_input( 'casi', 'get,post', '' );
$songdata['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$songdata['album'] = filter_text_input( 'album', 'get,post', '' );
$songdata['theloai'] = $nv_Request->get_int( 'theloai', 'get,post', 0 );
$songdata['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
$songdata['upboi'] = $nv_Request->get_string( 'upboi', 'post', '' );
$songdata['bitrate'] = $nv_Request->get_int( 'bitrate', 'post', 0 );
$songdata['duration'] = $nv_Request->get_int( 'duration', 'post', 0 );
$songdata['size'] = $nv_Request->get_int( 'size', 'post', 0 );


if ( $songdata['casimoi'] != '')
{
	$songdata['casi'] = change_alias( $songdata['casimoi'] );
	$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` 
	(
		`id`, `ten`, `tenthat`, `thumb`, `introduction`, `numsong`, `numalbum`
	) 
	VALUES 
	( 
		NULL, 
		" . $db->dbescape( $songdata['casi'] ) . ", 
		" . $db->dbescape( $songdata['casimoi'] ) . ", 
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
$category = get_category() ;
$setting = setting_music();
$allalbum = getallalbum();
$allsinger = getallsinger();

// lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $id == 0 )
{
    $page_title = $lang_module['add_song'];
}
else
{
    $page_title = $lang_module['edit_song'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` = ".$id."";
	$resuilt = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $resuilt );
	if ( !$nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$songdata['ten'] = $row['ten'];
		$songdata['tenthat'] = $row['tenthat'];
		$songdata['casi'] = $row['casi'];
		$songdata['album'] = $row['album'];
		$songdata['theloai'] = $row['theloai'];
		
		if( $row['server'] != 0 )
		{
			$songdata['duongdan'] = "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" . $row['duongdan'];
		}
		else
		{
			$songdata['duongdan'] = $row['duongdan'];
		}
		$songdata['upboi'] = $row['upboi'];
		$songdata['bitrate'] = $row['bitrate'];
		$songdata['duration'] = $row['duration'];
		$songdata['size'] = $row['size'];
	}
}

//sua bai hat
if ( ($nv_Request->get_int( 'edit', 'post', 0 ) == 1) && ( $error == '' ) )
{
	if (preg_match('/^(ht|f)tp:\/\//', $songdata['duongdan'])) 
	{
		$songdata['server'] = 0;
	}
	else
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
		$songdata['duongdan'] = substr( $songdata['duongdan'], $lu );
		$songdata['server'] = 1;
	}
	foreach ( $songdata as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name); die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}

// them bai hat moi
if ( ($nv_Request->get_int( 'add', 'post', 0 ) == 1) && ( $error == '' ) )
{	
	
	foreach ( $songdata as $data => $null )
	{
		if ( $data == 'casimoi' ) continue;
		if	($null == '') $error = $lang_module['error_song']; 
	}
	if ( $error == "" )
	{
		if (preg_match('/^(ht|f)tp:\/\//', $songdata['duongdan'])) 
		{
			$data = $songdata['duongdan'];
			$server = 0;
		}
		else
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
			$data = substr( $songdata['duongdan'], $lu );
			$server = 1;
		}
		
		// update so bai hat
		updatesinger( $songdata['casi'], 'numsong', '+1' );
		updatealbum( $songdata['album'], '+1' );
		
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` 
		(
			`id`, `ten`, `tenthat`, `casi`, `album`, `theloai`, `duongdan`, `upboi`, `numview`, `active`, `bitrate`, `size`, `duration`, `server`
		) 
		VALUES 
		( 
			NULL, 
			" . $db->dbescape( $songdata['ten'] ) . ", 
			" . $db->dbescape( $songdata['tenthat'] ) . ", 
			" . $db->dbescape( $songdata['casi'] ) . ", 
			" . $db->dbescape( $songdata['album'] ) . ", 
			" . $db->dbescape( $songdata['theloai'] ) . ", 
			" . $db->dbescape( $data )  . ", 
			" . $db->dbescape( $songdata['upboi'] ) . " ,
			0,
			1,
			" . $db->dbescape( $songdata['bitrate'] ) . " ,
			" . $db->dbescape( $songdata['size'] ) . " ,
			" . $db->dbescape( $songdata['duration'] ) . ",
			" . $server . "
		)
		"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name); die();
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
					".$lang_module['song_info']."
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['song_name']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"".$songdata['tenthat']."\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"".NV_BASE_SITEURL."images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['song_name_short']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"".$songdata['ten']."\" type=\"text\" />
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
						if ( $songdata['casi'] == $key )
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
					Album
				</td>
				<td style=\"background: #eee;\">
				<select name=\"album\">\n";
					foreach ( $allalbum as $key => $title )
					{
						$i= "";
						if ( $songdata['album'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
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
						if ( $songdata['theloai'] == $key )
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
				<input id=\"duongdan\" name=\"duongdan\" style=\"width: 370px;\" value=\"".$songdata['duongdan']."\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" /> =&gt;
                <input id=\"get_info\" name=\"get_info\" type=\"button\" value=\"".$lang_module['get_info']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"duongdan\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/" . $setting['root_contain'] . "\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				<script type=\"text/javascript\">			
				$(\"#get_info\").click(function () {
                    getsonginfo();
                });
				</script>	
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['bitrate'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"bitrate\" name=\"bitrate\" style=\"width: 370px;\" value=\"".$songdata['bitrate']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['duration'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duration\" name=\"duration\" style=\"width: 370px;\" value=\"".$songdata['duration']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['size'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"size\" name=\"size\" style=\"width: 370px;\" value=\"".$songdata['size']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['who_up']."
				</td>
				<td style=\"background: #eee;\">
				<input name=\"upboi\" style=\"width: 470px;\" value=\"".$songdata['upboi']."\" type=\"text\" />
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
if ( empty( $songdata['ten'] ) )
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
