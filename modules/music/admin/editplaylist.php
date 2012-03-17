<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['playlist_edit'];

// Java sap xep
$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.sortable.min.js\"></script>\n";

$contents = "";
$error = "";
$array = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$check = $db->sql_numrows( $result );
if( $check != 1 ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$row = $db->sql_fetchrow( $result );
$array['name'] = $row['name'];
$array['singer'] = $row['singer'];
$array['username'] = $row['username'];
$array['songdata'] = $row['songdata'];
$array['message'] = $row['message'];

$array['time'] = nv_date( "d/m/Y H:i", $row['time'] );

// Sua
if( ( $nv_Request->get_int( 'save', 'post', 0 ) ) == 1 )
{
	$array = array();
	$array['name'] = filter_text_input( 'name', 'post', '' );
	$array['singer'] = filter_text_input( 'singer', 'post', '' );
	$array['message'] = $nv_Request->get_string( 'message', 'post', '' );
	$array['songdata'] = $nv_Request->get_string( 'listsong', 'post', '' );

	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` SET
		`name`=" . $db->dbescape( $array['name'] ) . ", 
		`singer`=" . $db->dbescape( $array['singer'] ) . ", 
		`message`=" . $db->dbescape( $array['message'] ) . ", 
		`songdata`=" . $db->dbescape( $array['songdata'] ) . "
	WHERE `id`=" . $id;

	if( $db->sql_query( $sql ) )
	{
		nv_del_moduleCache( $module_name );
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=userplaylist" );
		die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}

// Lay danh sach bai hat cua playlist
if( ! empty( $array['songdata'] ) )
{
	$songdata = $array['songdata'];
	$array['songdata'] = array();
	$_tmp = array();

	$sql = "SELECT `id`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $songdata . ")";
	$result = $db->sql_query( $sql );
	while( list( $songid, $songname ) = $db->sql_fetchrow( $result ) )
	{
		$_tmp[$songid] = $songname;
	}

	foreach( explode( ",", $songdata ) as $_id )
	{
		if( isset( $_tmp[$_id] ) ) $array['songdata'][$_id] = $_tmp[$_id];
	}
}
else
{
	$array['songdata'] = array();
}

// Hien bao loi
if( ! empty( $error ) )
{
	$contents .= "<div class=\"quote\" style=\"width: 98%;\"><blockquote class=\"error\"><span> " . $error . "</span></blockquote></div><div class=\"clear\"></div>";
}

// Noi dung
$contents .= "
	<form method=\"post\"> 
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">" . $lang_module['playlist_edit'] . "</td>
			</tr>
		</thead>
		<tbody style=\"background: #eee;\">
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['playlist_name'] . "</td>
				<td><input name=\"name\" style=\"width: 470px;\" value=\"" . $array['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['singer'] . "</td>
				<td><input name=\"singer\" style=\"width: 470px;\" value=\"" . $array['singer'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg\">" . $lang_module['content_list'] . "<br />
					<input type=\"hidden\" name=\"listsong\" value=\"" . implode( ",", array_keys( $array['songdata'] ) ) . "\"/>
					<a href=\"javascript:void(0);\" id=\"selectsongtoadd\">" . $lang_module['album_add_list_song'] . "</a><br />
					<a href=\"javascript:void(0);\" id=\"addasong\">" . $lang_module['album_add_a_song'] . "</a>
				</td>
				<td><ul id=\"listsong-area\" class=\"fixbg list_song\">
				";
if( ! empty( $array['songdata'] ) )
{
	foreach( $array['songdata'] as $_id => $_tmp )
	{
		$contents .= "<li class=\"" . $_id . "\">" . $_tmp . "<span onclick=\"nv_del_song_fromalbum(" . $_id . ")\" class=\"delete_icon\">&nbsp;</span></li>\n";
	}
}
$contents .= "</ul></td>
			</tr>
			<tr>
				<td>" . $lang_module['user_send_lyric'] . "</td>
				<td><input name=\"username\" style=\"width: 470px;\" value=\"" . $array['username'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['playlist_time'] . "</td>
				<td><input name=\"time\" style=\"width: 470px;\" value=\"" . $array['time'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['content'] . "</td>
				<td>
					<textarea style=\"width: 680px\" value=\"\" name=\"message\" id=\"message\" cols=\"20\" rows=\"15\">" . $array['message'] . "</textarea>\n
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"" . $lang_module['save'] . "\" type=\"submit\">\n
					<input type=\"hidden\" name=\"save\" id=\"save\" value=\"1\">\n
				</td>\n
			</tr>\n
		</tbody>
	</table></form>";

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