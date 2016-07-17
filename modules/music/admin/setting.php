<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:34 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $classMusic->lang('music_setting');

if( ( $nv_Request->get_int( 'save', 'post', 0 ) ) == 1 )
{
	$array['root_contain'] = md5( $nv_Request->get_title( 'root_contain', 'post', '' ) );
	$array['who_comment'] = $nv_Request->get_int( 'who_comment', 'post', 0 );
	$array['who_download'] = $nv_Request->get_int( 'who_download', 'post', 0 );
	$array['auto_comment'] = $nv_Request->get_int( 'auto_comment', 'post', 0 );
	$array['who_lyric'] = $nv_Request->get_int( 'who_lyric', 'post', 0 );
	$array['auto_lyric'] = $nv_Request->get_int( 'auto_lyric', 'post', 0 );
	$array['who_gift'] = $nv_Request->get_int( 'who_gift', 'post', 0 );
	$array['auto_gift'] = $nv_Request->get_int( 'auto_gift', 'post', 0 );
	$array['auto_album'] = $nv_Request->get_int( 'auto_album', 'post', 0 );
	$array['who_upload'] = $nv_Request->get_int( 'who_upload', 'post', 0 );
	$array['auto_upload'] = $nv_Request->get_int( 'auto_upload', 'post', 0 );
	$array['upload_max'] = $nv_Request->get_int( 'upload_max', 'post', 0 );
	$array['default_server'] = $nv_Request->get_int( 'default_server', 'post', 0 );
	$array['playlist_max'] = $nv_Request->get_int( 'playlist_max', 'post', 0 );
	$array['del_cache_time_out'] = $nv_Request->get_int( 'del_cache_time_out', 'post', 0 );
	$array['num_blocktab'] = $nv_Request->get_int( 'num_blocktab', 'post', 0 );
	$array['description'] = nv_substr( $nv_Request->get_title( 'description', 'post', '', 1 ), 0, 255);
	$array['type_main'] = $nv_Request->get_int( 'type_main', 'post', 0 );

	$array['del_cache_time_out'] = $array['del_cache_time_out'] * 60;

	if( ( $array['upload_max'] * ( 1024 * 1024 ) ) > $global_config['nv_max_size'] )
	{
		$array['upload_max'] = $global_config['nv_max_size'] / ( 1024 * 1024 );
	}

	foreach( $array as $key => $value )
	{
		if( $key == "root_contain" )
		{
			@rename( NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $classMusic->setting['root_contain'], NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $value );
			$query = $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_setting SET char = " . $db->quote( $value ) . " WHERE key = \"" . $key . "\"  LIMIT 1 " );
		}
		elseif( $key == "description" )
		{
			$query = $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_setting SET char = " . $db->quote( $value ) . " WHERE key = \"" . $key . "\"  LIMIT 1 " );
		}
		else
		{
			$query = $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_setting SET value = " . $db->quote( $value ) . " WHERE key = \"" . $key . "\"  LIMIT 1 " );
		}
	}
	if( $query )
	{
		$nv_Cache->delMod( $module_name );
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}
	else
	{
		$contents .= $lang_module['error_save'];
	}
}

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table class=\"tab1\">
<tr>
    <td style=\"width: 170px;\"><strong>" . $lang_module['set_who_comment'] . "</strong></td>
    <td>
		<select name=\"who_comment\">
			<option value=\"2\"" . ( $classMusic->setting['who_comment'] == 2 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_no'] . "</option>\n
			<option value=\"1\"" . ( $classMusic->setting['who_comment'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $classMusic->setting['who_comment'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
	</td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_comment'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_comment\" " . ( ( $classMusic->setting['auto_comment'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>

<tr>
    <td><strong>" . $lang_module['set_who_lyric'] . "</strong></td>
    <td>
		<select name=\"who_lyric\">
			<option value=\"2\"" . ( $classMusic->setting['who_lyric'] == 2 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_no'] . "</option>\n
			<option value=\"1\"" . ( $classMusic->setting['who_lyric'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $classMusic->setting['who_lyric'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_lyric'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_lyric\" " . ( ( $classMusic->setting['auto_lyric'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_who_gift'] . "</strong></td>
    <td>
		<select name=\"who_gift\">
			<option value=\"2\"" . ( $classMusic->setting['who_gift'] == 2 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_no'] . "</option>\n
			<option value=\"1\"" . ( $classMusic->setting['who_gift'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $classMusic->setting['who_gift'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_gift'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_gift\" " . ( ( $classMusic->setting['auto_gift'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_who_upload'] . "</strong></td>
    <td>
		<select name=\"who_upload\">
			<option value=\"2\"" . ( $classMusic->setting['who_upload'] == 2 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_no'] . "</option>\n
			<option value=\"1\"" . ( $classMusic->setting['who_upload'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $classMusic->setting['who_upload'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_upload'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_upload\" " . ( ( $classMusic->setting['auto_upload'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_floder_file'] . "</strong></td>
    <td>
		<input name=\"root_contain\" type=\"text\" value=\"" . $classMusic->setting['root_contain'] . "\" />
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_album'] . "</strong></td>
    <td>
		<input type=\"checkbox\" value=\"1\" name=\"auto_album\" " . ( ( $classMusic->setting['auto_album'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_who_download'] . "</strong></td>
    <td>
		<select name=\"who_download\">
			<option value=\"2\"" . ( $classMusic->setting['who_download'] == 2 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_no'] . "</option>\n
			<option value=\"1\"" . ( $classMusic->setting['who_download'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $classMusic->setting['who_download'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_uploadmax'] . "</strong></td>
    <td>
		<input name=\"upload_max\" type=\"text\" value=\"" . $classMusic->setting['upload_max'] . "\" />
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_default_server'] . "</strong></td>
    <td>
		<select name=\"default_server\">
			<option value=\"1\"" . ( $classMusic->setting['default_server'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_root_server'] . "</option>\n";
$ftpdata = $classMusic->getFTP();
foreach( $ftpdata as $id => $array )
{
	$contents .= "
				<option value=\"" . $id . "\"" . ( $classMusic->setting['default_server'] == $id ? " selected=\"selected\"" : "" ) . ">" . $array['fulladdress'] . "</option>\n";

}
$contents .= "
		</select>
    </td>
</tr>
<tbody class=\"second\">
	<tr>
		<td><strong>" . $lang_module['set_playlist_max'] . "</strong></td>
		<td>
			<input name=\"playlist_max\" type=\"text\" value=\"" . $classMusic->setting['playlist_max'] . "\" />
		</td>
	</tr>
</tbody>
<tbody>
	<tr>
		<td><strong>" . $lang_module['set_time_del_cache'] . "</strong></td>
		<td>
			<input maxlength=\"4\" name=\"del_cache_time_out\" type=\"text\" value=\"" . ( $classMusic->setting['del_cache_time_out'] / 60 ) . "\" />
			" . $lang_module['set_time_del_cache_info'] . "
		</td>
	</tr>
</tbody>
<tbody class=\"second\">
	<tr>
		<td><strong>" . $lang_module['setting_num_blocktab'] . "</strong></td>
		<td>
			<input maxlength=\"4\" name=\"num_blocktab\" type=\"text\" value=\"" . $classMusic->setting['num_blocktab'] . "\" />
		</td>
	</tr>
</tbody>
<tbody>
	<tr>
		<td><strong>" . $lang_module['setting_description'] . "</strong></td>
		<td>
			<input style=\"width:350px\" maxlength=\"255\" name=\"description\" type=\"text\" value=\"" . $classMusic->setting['description'] . "\" />
		</td>
	</tr>
</tbody>
<tbody class=\"second\">
	<tr>
		<td><strong>" . $lang_module['set_type_main'] . "</strong></td>
		<td>
			<select class=\"txt-half\" name=\"type_main\">";
			
for( $i = 0; $i <= 1; ++ $i )
{	
	$contents .= "<option value=\"" . $i . "\"" . ( $i == $classMusic->setting['type_main'] ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_type_main_' . $i ] . "</option>";
}
			
$contents .= "				
			</select>
		</td>
	</tr>
</tbody>
</table>
<div style=\"text-align: center;\" colspan=\"2\">
<input type=\"submit\" value=\" " . $lang_module['save'] . " \" name=\"Submit1\">
<input type=\"hidden\" value=\"1\" name=\"save\">
</div>
</form>";

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';