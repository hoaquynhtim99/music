<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

// Call jquery UI datepicker + shadowbox
$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.sortable.min.js\"></script>\n";

// Khoi tao
$contents = "";
$error = "";
$array = $array_old = array();

// Kiem tra du lieu album
function nv_check_ok_album( $array )
{
	global $lang_module;
	if( empty( $array['name'] ) ) return $lang_module['album_error_alias'];
	if( empty( $array['tname'] ) ) return $lang_module['album_error_title'];
	if( empty( $array['thumb'] ) ) return $lang_module['album_error_thumb'];
	return "";
}

// Lay gia tri
$array['name'] = filter_text_input( 'ten', 'post', '' );
$array['tname'] = filter_text_input( 'tenthat', 'post', '' );
$array['casi'] = $nv_Request->get_string( 'casi', 'get,post', 0 );
$array['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$array['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$array['describe'] = $nv_Request->get_string( 'describe', 'post', '' );
$array['listsong'] = filter_text_input( 'listsong', 'post', '' );

$array['name'] = empty( $array['name'] ) ? change_alias( $array['tname'] ) : change_alias( $array['name'] );

if( $array['casimoi'] != '' )
{
	$array['casi'] = newsinger( change_alias( $array['casimoi'] ), $array['casimoi'] );

	if( $array['casi'] === false )
	{
		$array['casi'] = 0;
		$error = $lang_module['error_add_new_singer'];
	}
}

$allsinger = getallsinger();

// Lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $id == 0 )
{
	$page_title = $lang_module['add_album'];
}
else
{
	$page_title = $lang_module['edit_album'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$numrow = $db->sql_numrows( $result );

	if( $numrow != 1 ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

	$row = $db->sql_fetchrow( $result );

	$array_old['casi'] = $row['casi'];
	$array_old['name'] = $row['name'];
	$array_old['listsong'] = $row['listsong'];

	if( ! $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$array['name'] = $row['name'];
		$array['tname'] = $row['tname'];
		$array['casi'] = $row['casi'];
		$array['thumb'] = $row['thumb'];
		$array['describe'] = nv_editor_br2nl( $row['describe'] );
		$array['listsong'] = $row['listsong'];
	}
}

// Sua album
if( ( ( $nv_Request->get_int( 'edit', 'post', 0 ) ) == 1 ) and ( $error == '' ) )
{
	$error .= nv_check_ok_album( $array );

	// Kiem tra album da co chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $array['casi'] . " AND `tname`=" . $db->dbescape( $array['tname'] ) . " AND `id`!=" . $id );
		list( $existalbum ) = $db->sql_fetchrow( $result );
		if( $existalbum )
		{
			$error = $lang_module['error_exist_album'];
		}
	}

	if( empty( $error ) )
	{
		$numsong = 0;
		if( ! empty( $array['listsong'] ) )
		{
			$numsong = explode( ",", $array['listsong'] );
			$numsong = count( $numsong );
		}

		$array['describe'] = ! empty( $array['describe'] ) ? nv_editor_nl2br( $array['describe'] ) : "";

		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET 
			`name`=" . $db->dbescape( $array['name'] ) . ",
			`tname`=" . $db->dbescape( $array['tname'] ) . ",
			`casi`=" . $array['casi'] . ",
			`thumb`=" . $db->dbescape( $array['thumb'] ) . ",
			`numsong`=" . $db->dbescape( $numsong ) . ",
			`describe`=" . $db->dbescape( $array['describe'] ) . ",
			`listsong`=" . $db->dbescape( $array['listsong'] ) . "
		WHERE `id` =" . $id;
		$result = $db->sql_query( $sql );

		if( $result )
		{
			if( ! empty( $array['listsong'] ) )
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $id . " WHERE `id` IN(" . $array['listsong'] . ") AND `album`=0" );
			}

			// Cap nhat lai so album cua ca si
			if( $array_old['casi'] != $array['casi'] )
			{
				updatesinger( $array_old['casi'], 'numalbum', '-1' );
				updatesinger( $array['casi'], 'numalbum', '+1' );
			}

			// Cap nhat lai album cua nhung bai hat bi loai bo khoi album
			if( $array_old['listsong'] != $array['listsong'] )
			{
				$new_song = explode( ",", $array['listsong'] );
				$old_song = explode( ",", $array_old['listsong'] );
				$diff_old_song = array_diff( $old_song, $new_song );
				$diff_new_song = array_diff( $new_song, $old_song );

				// Tra va gia tri trong cho bai hat
				if( ! empty( $diff_old_song ) )
				{
					$diff_old_song = implode( ",", $diff_old_song );
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=0 WHERE `id` IN(" . $diff_old_song . ") AND `album`=" . $id );
				}

				// Cap nhat album cho cac bai hat duoc them moi
				if( ! empty( $diff_new_song ) )
				{
					$diff_new_song = implode( ",", $diff_new_song );
					$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $id . " WHERE `id` IN(" . $diff_new_song . ") AND `album`=0" );
				}
			}

			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album" );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

// Them album
if( ( $nv_Request->get_int( 'add', 'post', 0 ) == 1 ) and ( $error == '' ) )
{
	$error .= nv_check_ok_album( $array );

	// Kiem tra album da ton tai chua
	if( empty( $error ) )
	{
		$result = $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `casi`=" . $array['casi'] . " `tname`=" . $db->dbescape( $array['tname'] ) );
		list( $existalbum ) = $db->sql_fetchrow( $result );
		if( $existalbum )
		{
			$error = $lang_module['error_exist_album'];
		}
	}

	if( empty( $error ) )
	{
		updatesinger( $array['casi'], 'numalbum', '+1' );

		$numsong = 0;
		if( ! empty( $array['listsong'] ) )
		{
			$numsong = explode( ",", $array['listsong'] );
			$numsong = count( $numsong );
		}

		$array['describe'] = ! empty( $array['describe'] ) ? nv_editor_nl2br( $array['describe'] ) : "";

		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_album` VALUES (
			NULL, 
			" . $db->dbescape( $array['name'] ) . ", 
			" . $db->dbescape( $array['tname'] ) . ", 
			" . $array['casi'] . ", 
			" . $db->dbescape( $array['thumb'] ) . ", 
			0, 
			" . $db->dbescape( $admin_info['username'] ) . ",	
			" . $db->dbescape( $array['describe'] ) . "	,
			1,
			" . $numsong . ",
			" . $db->dbescape( $array['listsong'] ) . ",
			" . NV_CURRENTTIME . ",
			'0-" . NV_CURRENTTIME . "'
		)";

		$newid = $db->sql_query_insert_id( $sql );

		if( $newid )
		{
			$db->sql_freeresult();

			// Cap nhat album cho cac bai hat
			if( ! empty( $array['listsong'] ) )
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $newid . " WHERE `id` IN(" . $array['listsong'] . ") AND `album`=0" );
			}

			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=album" );
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
	$contents .= "<div class=\"quote\" style=\"width: 98%\">\n
					<blockquote class=\"error\">
						<span>" . $error . "</span>
					</blockquote>
				</div>\n
				<div class=\"clear\">
				</div>";
}

// Lay danh sach bai hat cua album
if( ! empty( $array['listsong'] ) )
{
	$listsong = $array['listsong'];
	$array['listsong'] = array();
	$_tmp = array();

	$sql = "SELECT `id`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $listsong . ")";
	$result = $db->sql_query( $sql );
	while( list( $songid, $songname ) = $db->sql_fetchrow( $result ) )
	{
		$_tmp[$songid] = $songname;
	}

	foreach( explode( ",", $listsong ) as $_id )
	{
		if( isset( $_tmp[$_id] ) ) $array['listsong'][$_id] = $_tmp[$_id];
	}
}
else
{
	$array['listsong'] = array();
}

// Noi dung trang
$contents .= "
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['album_info'] . "
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					" . $lang_module['album_name'] . "
				</td>
				<td class=\"fixbg\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"" . $array['tname'] . "\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"" . NV_BASE_SITEURL . "images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
				" . $lang_module['song_name_short'] . "
				</td>
				<td class=\"fixbg\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"" . $array['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
				" . $lang_module['singer'] . "	
				</td>
				<td class=\"fixbg\">
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
				<td class=\"fixbg strong\" style=\"width:150px\">
				" . $lang_module['singer_new'] . "	
				</td>
				<td class=\"fixbg\">
				<input id=\"singer_sortname\" name=\"casimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					" . $lang_module['thumb'] . "
				</td>
				<td class=\"fixbg\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"" . $array['thumb'] . "\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"" . $lang_module['select'] . "\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"thumb\"; // return value area
					var path = \"" . NV_UPLOADS_DIR . "/" . $module_name . "/thumb\";
					nv_open_browse_file(\"" . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type=image", "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no' . "\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					" . $lang_module['describle'] . "
				</td>
				<td class=\"fixbg\">";
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$contents .= nv_aleditor( 'describe', '98%', '250px', $array['describe'] );
}
else
{
	$contents .= "<textarea style=\"width: 98%\" value=\"" . $array['describe'] . "\" name=\"describe\" id=\"describe\" cols=\"20\" rows=\"15\"></textarea>\n";
}
$contents .= "
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\">" . $lang_module['content_list'] . "<br />
					<input type=\"hidden\" name=\"listsong\" value=\"" . implode( ",", array_keys( $array['listsong'] ) ) . "\"/>
					<a href=\"javascript:void(0);\" id=\"selectsongtoadd\">" . $lang_module['album_add_list_song'] . "</a><br />
					<a href=\"javascript:void(0);\" id=\"addasong\">" . $lang_module['album_add_a_song'] . "</a>
				</td>
				<td><ul id=\"listsong-area\" class=\"fixbg list_song\">
				";

if( ! empty( $array['listsong'] ) )
{
	foreach( $array['listsong'] as $_id => $_tmp )
	{
		$contents .= "<li class=\"" . $_id . "\">" . $_tmp . "<span onclick=\"nv_del_song_fromalbum(" . $_id . ")\" class=\"delete_icon\">&nbsp;</span></li>\n";
	}
}
$contents .= "</ul></td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" class=\"fixbg\">\n
					<input name=\"confirm\" value=\"" . $lang_module['save'] . "\" type=\"submit\">\n";
if( $id == 0 ) $contents .= "<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n";
else  $contents .= "<input type=\"hidden\" name=\"edit\" id=\"edit\" value=\"1\">\n";
$contents .= "<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n";
// Neu khong co ten album thi tu dong tao ten ngan gon
if( empty( $array['ten'] ) )
{
	$contents .= "<script type=\"text/javascript\">\n";
	$contents .= '$("#idtitle").change(function () {
                    get_alias(\'idtitle\', \'res_get_alias\');
                });';
	$contents .= "</script>\n";
}

$contents .= '<script type="text/javascript">
$(document).ready(function() 
{
	$( "#listsong-area" ).sortable({ 
		cursor: "crosshair",
		update: function(event, ui) { nv_soft_song(); }
	});
	$( "#listsong-area" ).disableSelection();
	// Them nhieu bai hat
	$("a#selectsongtoadd").click(function(){
		var songlist = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "' . NV_BASE_ADMINURL . 'index.php?" + nv_name_variable + "=' . $module_name . '&" + nv_fc_variable + "=findsongtoalbum&songlist=" + songlist, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	// Them mot bai hat
	$("a#addasong").click(function(){
		var songlist = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "' . NV_BASE_ADMINURL . 'index.php?" + nv_name_variable + "=' . $module_name . '&" + nv_fc_variable + "=findasongtoalbum&songlist=" + songlist, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
function nv_soft_song(){
	var list_song = new Array();
	$("#listsong-area li").each(function(){
		list_song.push($(this).attr("class"));
	});
	list_song = list_song.toString();
	$("input[name=listsong]").val(list_song);
	return;
}
function nv_del_song_fromalbum(songid){
	if( confirm( "' . $lang_module['album_confirm_delsong'] . '" ) )
	{
		$("#listsong-area li." + songid).remove();
		nv_soft_song();
	}
	return false;
}
</script>';

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>
