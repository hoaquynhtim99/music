<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Ham kiem tra video co day du thong tin chua
function nv_check_ok_video( $array )
{
	global $lang_module;

	if( empty( $array['name'] ) ) return $lang_module['video_error_name'];
	if( empty( $array['tname'] ) ) return $lang_module['video_error_tname'];
	if( empty( $array['theloai'] ) ) return $lang_module['video_error_theloai'];
	if( empty( $array['duongdan'] ) ) return $lang_module['video_error_duongdan'];
	if( empty( $array['thumb'] ) ) return $lang_module['video_error_thumb'];

	return "";
}

$contents = "";
$error = "";
$array = $array_old = array();

$array['name'] = filter_text_input( 'name', 'post', '' );
$array['tname'] = filter_text_input( 'tname', 'post', '' );
$array['casi'] = $nv_Request->get_int( 'casi', 'post', 0 );
$array['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$array['nhacsi'] = $nv_Request->get_int( 'nhacsi', 'post', 0 );
$array['nhacsimoi'] = filter_text_input( 'nhacsimoi', 'post', '' );
$array['theloai'] = $nv_Request->get_int( 'theloai', 'get,post', 0 );
$array['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$array['listcat'] = $nv_Request->get_typed_array( 'listcat', 'post', 'int' );

// Them ca si va nhac si moi
if( $array['casimoi'] != '' )
{
	$array['casi'] = newsinger( change_alias( $array['casimoi'] ), $array['casimoi'] );

	if( $array['casi'] === false )
	{
		$array['casi'] = 0;
		$error = $lang_module['error_add_new_singer'];
	}
}
if( $array['nhacsimoi'] != '' )
{
	$array['nhacsi'] = newauthor( change_alias( $array['nhacsimoi'] ), $array['nhacsimoi'] );

	if( $array['nhacsi'] === false )
	{
		$array['nhacsi'] = 0;
		$error = $lang_module['error_add_new_author'];
	}
}

$category = get_videocategory();
$allsinger = getallsinger();
$allauthor = getallauthor();
$setting = setting_music();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $id == 0 )
{
	$page_title = $lang_module['video_add'];
}
else
{
	$page_title = $lang_module['video_edit'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$numrow = $db->sql_numrows( $result );

	if( $numrow != 1 ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

	$row = $db->sql_fetchrow( $result );

	// Thong tin cu
	$array_old['casi'] = $row['casi'];
	$array_old['nhacsi'] = $row['nhacsi'];
	$array_old['theloai'] = $row['theloai'];
	$array_old['listcat'] = $row['listcat'] ? explode( ",", $row['listcat'] ) : array();

	if( ! $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$array['name'] = $row['name'];
		$array['tname'] = $row['tname'];
		$array['casi'] = $row['casi'];
		$array['nhacsi'] = $row['nhacsi'];
		$array['theloai'] = $row['theloai'];
		$array['thumb'] = $row['thumb'];
		$array['duongdan'] = admin_outputURL( $row['server'], $row['duongdan'] );
		$array['listcat'] = $row['listcat'];

		if( ! empty( $array['listcat'] ) )
		{
			$array['listcat'] = explode( ",", $array['listcat'] );
		}
		else
		{
			$array['listcat'] = array();
		}
	}
}

// Sua video
if( $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
{
	$error .= nv_check_ok_video( $array );

	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `casi`=" . $array['casi'] . " AND `tname`=" . $db->dbescape( $array['tname'] ) . " AND `id`!=" . $id );
		list( $exist ) = $db->sql_fetchrow( $result );
		if( $exist )
		{
			$error = $lang_module['error_exist_video'];
		}
	}

	if( empty( $error ) )
	{
		$check_url = creatURL( $array['duongdan'] );
		$array['duongdan'] = $check_url['duongdan'];
		$array['server'] = $check_url['server'];

		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_video` SET 
			`name`=" . $db->dbescape( $array['name'] ) . ", 
			`tname`=" . $db->dbescape( $array['tname'] ) . ", 
			`casi`=" . $array['casi'] . ", 
			`nhacsi`=" . $array['nhacsi'] . ", 
			`theloai`=" . $db->dbescape( $array['theloai'] ) . ", 
			`listcat`=" . $db->dbescape( implode( ",", $array['listcat'] ) ) . ", 
			`duongdan`=" . $db->dbescape( $array['duongdan'] ) . ", 
			`thumb`=" . $db->dbescape( $array['thumb'] ) . ", 
			`server`=" . $db->dbescape( $array['server'] ) . "
		WHERE `id`=" . $id;

		$check_update = $db->sql_query( $sql );

		if( $check_update )
		{
			// Cap nhat bai hat cho the loai
			// Xac dinh cac chu de cu
			$list_old_cat = $array_old['listcat'];
			$list_old_cat[] = $array_old['theloai'];
			$list_old_cat = array_unique( $list_old_cat );
			
			// Xac dinh chu de moi
			$list_new_cat = $array['listcat'];
			$list_new_cat[] = $array['theloai'];
			$list_new_cat = array_unique( $list_new_cat );
			
			$array_mul = array_diff( $list_new_cat, $list_old_cat );
			$array_div = array_diff( $list_old_cat, $list_new_cat );
			
			foreach( $array_mul as $_cid )
			{
				if( $_cid > 0 ) UpdateVideoCat( $_cid, '+1' );
			}
			
			foreach( $array_div as $_cid )
			{
				if( $_cid > 0 ) UpdateVideoCat( $_cid, '-1' );
			}

			// Cap nhat so video cua ca si
			if( $array_old['casi'] != $array['casi'] )
			{
				updatesinger( $array_old['casi'], 'numvideo', '-1' );
				updatesinger( $array['casi'], 'numvideo', '+1' );
			}

			// Cap nhat so video cua nhac si
			if( $array_old['nhacsi'] != $array['nhacsi'] )
			{
				updateauthor( $array_old['nhacsi'], 'numvideo', '-1' );
				updateauthor( $array['nhacsi'], 'numvideo', '+1' );
			}
			$db->sql_freeresult();

			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

// Them video moi
if( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{
	$error .= nv_check_ok_video( $array );

	// Kiem tra video da co chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_video` WHERE `casi`=" . $array['casi'] . " AND `tname`=" . $db->dbescape( $array['tname'] ) );
		list( $exist ) = $db->sql_fetchrow( $result );
		if( $exist )
		{
			$error = $lang_module['error_exist_video'];
		}
	}

	if( empty( $error ) )
	{
		$check_url = creatURL( $array['duongdan'] );

		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_video` VALUES (
			NULL, 
			" . $db->dbescape( $array['name'] ) . ", 
			" . $db->dbescape( $array['tname'] ) . ", 
			" . $array['casi'] . ", 
			" . $array['nhacsi'] . ", 
			" . $db->dbescape( $array['theloai'] ) . ", 
			" . $db->dbescape( implode( ",", $array['listcat'] ) ) . ",
			" . $db->dbescape( $check_url['duongdan'] ) . ", 
			" . $db->dbescape( $array['thumb'] ) . " ,
			0,
			1,
			" . NV_CURRENTTIME . ",
			" . $check_url['server'] . ",
			0,
			" . $db->dbescape( "0-" . NV_CURRENTTIME ) . "			
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			// Cap nhat bai hat cho the loai
			// Xac dinh chu de moi
			$list_new_cat = $array['listcat'];
			$list_new_cat[] = $array['theloai'];
			$list_new_cat = array_unique( $list_new_cat );
			
			foreach( $list_new_cat as $_cid )
			{
				if( $_cid > 0 ) UpdateVideoCat( $_cid, '+1' );
			}
			
			// Cap nhat so video cua ca si, nhac si
			updatesinger( $array['casi'], 'numvideo', '+1' );
			updateauthor( $array['nhacsi'], 'numvideo', '+1' );
			$db->sql_freeresult();

			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip" );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

// Hien bao loi
if( $error )
{
	$contents .= "<div class=\"quote\" style=\"width: 98%;\"><blockquote class=\"error\"><span>" . $error . "</span></blockquote></div><div class=\"clear\"></div>";
}

// Noi dung trang
$contents .= "
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['video_info'] . "
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['video_name'] . " </td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tname\" style=\"width: 470px;\" value=\"" . $array['tname'] . "\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"" . NV_BASE_SITEURL . "images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['video_name_short'] . "</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"name\" style=\"width: 470px;\" value=\"" . $array['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['singer'] . "</td>
				<td style=\"background: #eee;\">
					<select name=\"casi\">\n";
foreach( $allsinger as $key => $title )
{
	$i = "";
	if( $array['casi'] == $key ) $i = "selected=\"selected\"";
	$contents .= "<option " . $i . " value=\"" . $key . "\" >" . $title . "</option>\n";
}
$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['singer_new'] . "</td>
				<td style=\"background: #eee;\">
				<input id=\"singer_sortname\" name=\"casimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['author'] . "</td>
				<td style=\"background: #eee;\">
					<select name=\"nhacsi\">\n";
foreach( $allauthor as $key => $title )
{
	$i = "";
	if( $array['nhacsi'] == $key ) $i = "selected=\"selected\"";
	$contents .= "<option " . $i . " value=\"" . $key . "\" >" . $title . "</option>\n";
}
$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['author_new'] . "</td>
				<td style=\"background: #eee;\">
					<input id=\"singer_sortname\" name=\"nhacsimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['category_base'] . "</td>
				<td style=\"background: #eee;\">
					<select name=\"theloai\">\n";
foreach( $category as $key => $title )
{
	$i = "";
	if( $array['theloai'] == $key ) $i = "selected=\"selected\"";
	$contents .= "<option " . $i . " value=\"" . $key . "\" >" . $title['title'] . "</option>\n";
}
$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					" . $lang_module['category_sub'] . "
				</td>
				<td style=\"background: #eee;max-height:250px;overflow:auto\">";

foreach( $category as $key => $title )
{
	$checked = in_array( $key, $array['listcat'] ) ? " checked=\"checked\"" : "";
	$contents .= "<input name=\"listcat[]\" type=\"checkbox\"" . $checked . " value=\"" . $key . "\" />" . $title['title'] . "<br />\n";
}

$contents .= "
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['link'] . "</td>
				<td style=\"background: #eee;\">
				<input id=\"duongdan\" name=\"duongdan\" style=\"width: 370px;\" value=\"" . $array['duongdan'] . "\" type=\"text\" />
                <input name=\"selectvideo\" type=\"button\" value=\"" . $lang_module['select'] . "\" />
				<script type=\"text/javascript\">			
				$(\"input[name=selectvideo]\").click(function()
				{
					var area = \"duongdan\"; // return value area
					var path = \"" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/video\";
					nv_open_browse_file(\"" . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no' . "\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['thumb'] . "</td>
				<td style=\"background: #eee;\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"" . $array['thumb'] . "\" type=\"text\"  readonly=\"readonly\"/>
                <input name=\"select\" type=\"button\" value=\"" . $lang_module['select'] . "\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"thumb\"; // return value area
					var path = \"" . NV_UPLOADS_DIR . "/" . $module_name . "/clipthumb\";
					nv_open_browse_file(\"" . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no' . "\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"" . $lang_module['save'] . "\" type=\"submit\">\n";
if( $id == 0 ) $contents .= "<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n";
else  $contents .= "<input type=\"hidden\" name=\"edit\" id=\"edit\" value=\"1\">\n";
$contents .= "<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n";

// Neu ten ngan gon video chua có thi tu dong tao ten
if( empty( $array['ten'] ) )
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
