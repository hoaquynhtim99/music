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

$page_title = $lang_module['music_setting'];
$contents = '';
$setting = setting_music();

if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$data['root_contain'] = md5(filter_text_input( 'root_contain', 'post', '' ));
	$data['who_comment'] = $nv_Request->get_int( 'who_comment', 'post', 0 );
	$data['who_download'] = $nv_Request->get_int( 'who_download', 'post', 0 );
	$data['auto_comment'] = $nv_Request->get_int( 'auto_comment', 'post', 0 );
	$data['who_lyric'] = $nv_Request->get_int( 'who_lyric', 'post', 0 );
	$data['auto_lyric'] = $nv_Request->get_int( 'auto_lyric', 'post', 0 );
	$data['who_gift'] = $nv_Request->get_int( 'who_gift', 'post', 0 );
	$data['auto_gift'] = $nv_Request->get_int( 'auto_gift', 'post', 0 );
	$data['auto_album'] = $nv_Request->get_int( 'auto_album', 'post', 0 );

	foreach ( $data as $key => $value  )
	{	
		if ( $key == "root_contain" )
		{
			@rename( NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'], NV_ROOTDIR . "/" . NV_UPLOADS_DIR . "/" . $module_name . "/" . $value );
			$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_setting` SET `char` = " . $db->dbescape( $value ) . " WHERE `key` = \"" . $key . "\"  LIMIT 1 " );
		}
		else
		{
		$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_setting` SET `value` = " . $db->dbescape( $value ) . " WHERE `key` = \"" . $key . "\"  LIMIT 1 " );
		}
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setting" ); die();
	}
	else
	{
		$contents .= $lang_module['error_save'];
	}
}

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<table summary=\"\" class=\"tab1\">
<tr>
    <td style=\"width: 170px;\"><strong>" . $lang_module['set_who_comment'] . "</strong></td>
    <td>
		<select name=\"who_comment\">
			<option value=\"1\"" . ( $setting['who_comment'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $setting['who_comment'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
	</td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_comment'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_comment\" " . ( ( $setting['auto_comment'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>

<tr>
    <td><strong>" . $lang_module['set_who_lyric'] . "</strong></td>
    <td>
		<select name=\"who_lyric\">
			<option value=\"1\"" . ( $setting['who_lyric'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $setting['who_lyric'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_lyric'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_lyric\" " . ( ( $setting['auto_lyric'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_who_gift'] . "</strong></td>
    <td>
		<select name=\"who_gift\">
			<option value=\"1\"" . ( $setting['who_gift'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $setting['who_gift'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_gift'] . "</strong></td>
    <td>
        <input type=\"checkbox\" value=\"1\" name=\"auto_gift\" " . ( ( $setting['auto_gift'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_floder_file'] . "</strong></td>
    <td>
		<input name=\"root_contain\" type=\"text\" value=\"". $setting['root_contain'] . "\" />
    </td>
</tr>
<tbody class=\"second\">
<tr>
    <td><strong>" . $lang_module['set_auto_album'] . "</strong></td>
    <td>
		<input type=\"checkbox\" value=\"1\" name=\"auto_album\" " . ( ( $setting['auto_album'] ) ? "checked=\"checked\"" : "" ) . ">
    </td>
</tr>
</tbody>
<tr>
    <td><strong>" . $lang_module['set_who_download'] . "</strong></td>
    <td>
		<select name=\"who_download\">
			<option value=\"1\"" . ( $setting['who_download'] == 1 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_all'] . "</option>\n
			<option value=\"0\"" . ( $setting['who_download'] == 0 ? " selected=\"selected\"" : "" ) . ">" . $lang_module['set_user'] . "</option>\n
		</select>
    </td>
</tr>
</table>
<div style=\"text-align: center;\" colspan=\"2\">
<input type=\"submit\" value=\" " . $lang_module['save'] . " \" name=\"Submit1\">
<input type=\"hidden\" value=\"1\" name=\"save\">
</div>
</form>";


include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>