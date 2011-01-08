<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 29-12-2010 18:43
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
    die( 'Stop!!!' );
}
$page_title = $lang_module['playlist_edit'];

//khoi tao
$contents = "";
$error = "";
$playlist = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_playlist` WHERE `id` = ".$id."";
$resuilt = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $resuilt );
$playlist['name'] = $row['name'];
$playlist['singer'] = $row['singer'];
$playlist['username'] = $row['username'];
$playlist['songdata'] = $row['songdata'];
$playlist['message'] = $row['message'];

$playlist['time'] = nv_date( "d/m/Y H:i", $row['time'] );

// sua
if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$datac = array() ;
	$datac['name'] = filter_text_input( 'name', 'post', '' );
	$datac['singer'] = filter_text_input( 'singer', 'post', '' );
	$datac['message'] = $nv_Request->get_string( 'message', 'post', '' );
	$datac['songdata'] = $nv_Request->get_string( 'songdata', 'post', '' );

	foreach ( $datac as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_playlist" . $where . "` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=userplaylist"); die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}
else
{

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
$contents .= "
	<form method=\"post\"> 
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					".$lang_module['playlist_edit']."
				</td>
			</tr>
		</thead>
		<tbody style=\"background: #eee;\">
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['playlist_name'] . "
				</td>
				<td><input name=\"name\" style=\"width: 470px;\" value=\"" . $playlist['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['singer'] . "
				</td>
				<td><input name=\"singer\" style=\"width: 470px;\" value=\"" . $playlist['singer'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['siteinfo_numsong'] . "
				</td>
				<td><input name=\"songdata\" style=\"width: 470px;\" value=\"" . $playlist['songdata'] . "\" type=\"text\"  />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['user_send_lyric'] . "
				</td>
				<td><input name=\"username\" style=\"width: 470px;\" value=\"" . $playlist['username'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['playlist_time'] . "
				</td>
				<td><input name=\"time\" style=\"width: 470px;\" value=\"" . $playlist['time'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['content'] . "
				</td>
				<td>
					<textarea style=\"width: 680px\" value=\"\" name=\"message\" id=\"message\" cols=\"20\" rows=\"15\">" . $playlist['message'] . "</textarea>\n
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"".$lang_module['save']."\" type=\"submit\">\n
					<input type=\"hidden\" name=\"save\" id=\"save\" value=\"1\">\n
				</td>\n
			</tr>\n
		</tbody>
	</table></form>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>