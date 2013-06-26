<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung@gmail.com)
 * @Copyright (C) 2010 Freeware
 * @Createdate 29-12-2010 18:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$contents = "";
$error = "";
$comment = array();

$where = filter_text_input( 'where', 'get,post', '' );
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $where == 'song' )
{
	$whatcomment = $lang_module['song_name'];
	$page_title = $lang_module['edit_comment_song'];
	$back = "commentsong";
}
elseif( $where == 'album' )
{
	$whatcomment = $lang_module['album_name'];
	$page_title = $lang_module['edit_comment_album'];
	$back = "commentalbum";
}
elseif( $where == 'video' )
{
	$whatcomment = $lang_module['video_name'];
	$page_title = $lang_module['video_comment_edit'];
	$back = "commentvideo";
}
else  die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` WHERE `id` = " . $id;
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
$comment['name'] = $row['name'];
$comment['body'] = nv_br2nl( $row['body'] );

$comment['dt'] = nv_date( "d/m/Y H:i", $row['dt'] );

if( $where == 'song' )
{
	$tmp = getsongbyID( $row['what'] );
	$comment['what'] = $tmp['tenthat'];
}
elseif( $where == 'album' )
{
	$tmp = getalbumbyID( $row['what'] );
	$comment['what'] = $tmp['tname'];
}
elseif( $where == 'video' )
{
	$tmp = getvideobyID( $row['what'] );
	$comment['what'] = $tmp['tname'];
}

// Sua
if( ( $nv_Request->get_int( 'save', 'post', 0 ) ) == 1 )
{
	$comment['name'] = filter_text_input( 'name', 'post', '', 1, 100 );
	$comment['body'] = filter_text_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );

	if( empty( $comment['name'] ) )
	{
		$error = $lang_module['comment_error_name'];
	}
	elseif( empty( $comment['body'] ) )
	{
		$error = $lang_module['comment_error_body'];
	}
	else
	{
		$array['body'] = nv_nl2br( $array['body'] );
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` SET
			`name`=" . $db->dbescape( $comment['name'] ) . ",
			`body`=" . $db->dbescape( $comment['body'] ) . "
		WHERE `id` =" . $id;

		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $back );
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
	<div class=\"clear\"></div>";
}

$contents .= "
	<form method=\"post\"> 
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['edit_comment'] . "
				</td>
			</tr>
		</thead>
		<tbody style=\"background: #eee;\">
			<tr>
				<td style=\"width: 160px;\">" . $lang_module['user_comment'] . "
				</td>
				<td><input name=\"name\" style=\"width: 470px;\" value=\"" . $comment['name'] . "\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td>" . $whatcomment . "
				</td>
				<td><input name=\"what\" style=\"width: 470px;\" value=\"" . $comment['what'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['comment_time'] . "
				</td>
				<td><input name=\"time\" style=\"width: 470px;\" value=\"" . $comment['dt'] . "\" type=\"text\" readonly=\"readonly\" />
				</td>
			</tr>
			<tr>
				<td>" . $lang_module['content'] . "
				</td>
				<td>
					<textarea style=\"width: 680px\" value=\"\" name=\"body\" id=\"describe\" cols=\"20\" rows=\"15\">" . nv_htmlspecialchars( $comment['body'] ) . "</textarea>\n
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