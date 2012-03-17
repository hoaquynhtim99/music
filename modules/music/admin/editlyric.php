<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2010 Freeware
 * @Createdate 29-12-2010 18:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$contents = "";
$error = "";
$lyric = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$page_title = $lang_module['edit_lyric'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `id` = " . $id . "";
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
$lyric['name'] = $row['user'];
$lyric['body'] = nv_editor_br2nl( $row['body'] );

$lyric['dt'] = nv_date( "d/m/Y H:i", $row['dt'] );

$tmp = getsongbyID( $row['songid'] );
$lyric['songname'] = $tmp['tenthat'];

// Sua
if( ( $nv_Request->get_int( 'save', 'post', 0 ) ) == 1 )
{
	$lyric['name'] = filter_text_input( 'user', 'post', '' );
	$lyric['body'] = nv_editor_filter_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );

	if( empty( $lyric['name'] ) )
	{
		$error = $lang_module['lyric_error_name'];
	}
	elseif( empty( $lyric['body'] ) )
	{
		$error = $lang_module['lyric_error_body'];
	}
	else
	{
		$array['body'] = nv_editor_nl2br( $array['body'] );
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` SET
			`user`=" . $db->dbescape( $lyric['name'] ) . ",
			`body`=" . $db->dbescape( $lyric['body'] ) . "
		WHERE `id` =" . $id;

		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=lyric" );
			die();
		}
		else
		{
			$db->sql_freeresult();
			$error = $lang_module['error_save'];
		}
	}
}

// Hien bao loi
if( $error )
{
	$contents .= "<div class=\"quote\" style=\"width:98%\">\n
	<blockquote class=\"error\">
	<span>" . $error . "</span>
	</blockquote>
	</div>\n
	<div class=\"clear\">
	</div>";
}

$contents .= "
	<form method=\"post\"> 
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['edit_lyric'] . "
				</td>
			</tr>
		</thead>
		<tbody style=\"background: #eee;\">
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['user_send_lyric'] . "
				</td>
				<td><input name=\"user\" style=\"width: 470px;\" value=\"" . $lyric['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['song_name'] . "
				</td>
				<td><input name=\"what\" style=\"width: 470px;\" value=\"" . $lyric['songname'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['comment_time'] . "
				</td>
				<td><input name=\"time\" style=\"width: 470px;\" value=\"" . $lyric['dt'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['content'] . "
				</td>
				<td>";
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$contents .= nv_aleditor( 'body', '100%', '250px', nv_htmlspecialchars( $lyric['body'] ) );
}
else
{
	$contents .= "<textarea style=\"width:98%\" value=\"" . nv_htmlspecialchars( $lyric['body'] ) . "\" name=\"body\" id=\"body\" cols=\"20\" rows=\"15\"></textarea>\n";
}
$contents .= "				
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"" . $lang_module['save'] . "\" type=\"submit\">\n
					<input type=\"hidden\" name=\"save\" id=\"save\" value=\"1\">\n
				</td>\n
			</tr>\n
		</tbody>
	</table>
</form>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>