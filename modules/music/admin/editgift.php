<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung@gmail.com)
 * @Copyright (C) 2010 Freeware
 * @Createdate 29-12-2010 18:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$contents = "";
$error = "";
$gift = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$page_title = $lang_module['edit_gift'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` WHERE `id` = " . $id . "";
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );

$gift['who_send'] = $row['who_send'];
$gift['who_receive'] = $row['who_receive'];
$gift['body'] = nv_br2nl( $row['body'] );

$gift['time'] = nv_date( "d/m/Y H:i", $row['time'] );

$tmp = getsongbyID( $row['songid'] );
$gift['songname'] = $tmp['tenthat'];

// Sua
if( ( $nv_Request->get_int( 'save', 'post', 0 ) ) == 1 )
{
	$gift['who_send'] = filter_text_input( 'who_send', 'post', '', 1, 100 );
	$gift['who_receive'] = filter_text_input( 'who_receive', 'post', '', 1, 100 );
	$gift['body'] = filter_text_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );

	if( empty( $gift['who_send'] ) )
	{
		$error = $lang_module['gift_error_who_send'];
	}
	elseif( empty( $gift['who_receive'] ) )
	{
		$error = $lang_module['gift_error_who_receive'];
	}
	elseif( empty( $gift['body'] ) )
	{
		$error = $lang_module['gift_error_body'];
	}
	else
	{
		$array['body'] = nv_nl2br( $array['body'] );
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_gift` SET
			`who_send`=" . $db->dbescape( $gift['who_send'] ) . ",
			`who_receive`=" . $db->dbescape( $gift['who_receive'] ) . ",
			`body`=" . $db->dbescape( $gift['body'] ) . "
		WHERE `id` =" . $id;

		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=gift" );
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
					" . $lang_module['edit_gift'] . "
				</td>
			</tr>
		</thead>
		<tbody style=\"background: #eee;\">
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['user_send_gift'] . "
				</td>
				<td><input name=\"who_send\" style=\"width: 470px;\" value=\"" . $gift['who_send'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['user_recive_gift'] . "
				</td>
				<td><input name=\"who_receive\" style=\"width: 470px;\" value=\"" . $gift['who_receive'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['song_name'] . "
				</td>
				<td><input name=\"what\" style=\"width: 470px;\" value=\"" . $gift['songname'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['comment_time'] . "
				</td>
				<td><input name=\"time\" style=\"width: 470px;\" value=\"" . $gift['time'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['content'] . "
				</td>
				<td>
					<textarea style=\"width: 680px\" name=\"body\" id=\"body\" cols=\"20\" rows=\"15\">" . nv_htmlspecialchars( $gift['body'] ) . "</textarea>\n		
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