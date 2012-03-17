<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

function nv_check_ok_singer( $array )
{
	global $lang_module;

	if( empty( $array['ten'] ) ) return $lang_module['singer_error_ten'];
	if( empty( $array['tenthat'] ) ) return $lang_module['singer_error_tenthat'];

	return "";
}

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

// Khoi tao
$contents = "";
$error = "";
$array = $array_old = array();

// Lay gia tri
$array['ten'] = filter_text_input( 'ten', 'get,post', '', 1, 100 );
$array['tenthat'] = filter_text_input( 'tenthat', 'post', '', 1, 100 );
$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$array['introduction'] = nv_editor_filter_textarea( 'introduction', '', NV_ALLOWED_HTML_TAGS );

// Lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $id == 0 )
{
	$page_title = $lang_module['singer_add'];
}
else
{
	$page_title = $lang_module['singer_edit'];

	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `id` = " . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );

	if( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	$row = $db->sql_fetchrow( $result );

	if( ! $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$array['ten'] = $row['ten'];
		$array['tenthat'] = $row['tenthat'];
		$array['thumb'] = $row['thumb'];
		$array['introduction'] = nv_editor_br2nl( $row['introduction'] );
	}
}

// Sua ca si
if( $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
{
	$error .= nv_check_ok_singer( $array );
	$array['introduction'] = nv_editor_nl2br( $array['introduction'] );

	// Kiem tra xem ca si da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `tenthat`=" . $db->dbescape( $array['tenthat'] ) . " AND `introduction`=" . $db->dbescape( $array['introduction'] ) . " AND `id`!=" . $id );
		list( $exist ) = $db->sql_fetchrow( $result );
		if( $exist )
		{
			$error = $lang_module['error_exist_singer'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_singer` SET
			`ten`=" . $db->dbescape( $array['ten'] ) . ", 
			`tenthat`=" . $db->dbescape( $array['tenthat'] ) . ", 
			`thumb`=" . $db->dbescape( $array['thumb'] ) . ", 
			`introduction`=" . $db->dbescape( $array['introduction'] ) . " 
		WHERE `id` =" . $id;

		$result = $db->sql_query( $sql );
		if( $result )
		{
			nv_del_moduleCache( $module_name );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=singer" );
			die();
		}
		else
		{
			$array['introduction'] = nv_editor_br2nl( $array['introduction'] );
			$error = $lang_module['error_save'];
		}
	}
}

// Them moi ca si
if( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{
	$error .= nv_check_ok_singer( $array );
	$array['introduction'] = nv_editor_nl2br( $array['introduction'] );

	// Kiem tra xem ca si da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_singer` WHERE `tenthat`=" . $db->dbescape( $array['tenthat'] ) . " AND `introduction`=" . $db->dbescape( $array['introduction'] ) );
		list( $exist ) = $db->sql_fetchrow( $result );
		if( $exist )
		{
			$error = $lang_module['error_exist_singer'];
		}
	}

	if( empty( $error ) )
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_singer` VALUES ( 
			NULL, 
			" . $db->dbescape( $array['ten'] ) . ", 
			" . $db->dbescape( $array['tenthat'] ) . ", 
			" . $db->dbescape( $array['thumb'] ) . ", 
			" . $db->dbescape( $array['introduction'] ) . ", 
			0, 0, 0
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			$db->sql_freeresult();
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=singer" );
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

//
if( ! empty( $array['introduction'] ) ) $array['introduction'] = nv_htmlspecialchars( $array['introduction'] );

// Noi dung
$contents .= "
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">" . $lang_module['singer_info'] . "</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['singer_name'] . "</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"" . $array['tenthat'] . "\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"" . NV_BASE_SITEURL . "images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['song_name_short'] . "</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"" . $array['ten'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['thumb'] . "</td>
				<td style=\"background: #eee;\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"" . $array['thumb'] . "\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"" . $lang_module['select'] . "\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function(){
					var area = \"thumb\"; // return value area
					var path = \"" . NV_UPLOADS_DIR . "/" . $module_name . "/singerthumb\";
					nv_open_browse_file(\"" . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type=image", "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no' . "\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">" . $lang_module['describle'] . "</td>
				<td style=\"background: #eee;\">";
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$contents .= nv_aleditor( 'introduction', '98%', '250px', $array['introduction'] );
}
else
{
	$contents .= "<textarea style=\"width:98%\" name=\"introduction\" id=\"introduction\" cols=\"20\" rows=\"15\">" . $array['introduction'] . "</textarea>\n";
}
$contents .= "
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

// Neu khong co ten album thi tu dong tao
if( empty( $array['ten'] ) )
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
