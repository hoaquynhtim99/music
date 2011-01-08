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

//khoi tao
$contents = "";
$error = "";
$comment = array();

$where = filter_text_input( 'where', 'get,post', '' );
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $where == 'song' )
{
	$whatcomment = $lang_module['song_name'] ;
    $page_title = $lang_module['edit_comment_song'];
	$back = "commentsong" ;
}
elseif ( $where == 'album' )
{
	$whatcomment = $lang_module['album_name'] ;
    $page_title = $lang_module['edit_comment_album'];
	$back = "commentalbum" ;
}
else die('Stop!!!');

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` WHERE `id` = ".$id."";
$resuilt = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $resuilt );
$comment['name'] = $row['name'];
$comment['body'] = $row['body'];

$comment['dt'] = nv_date( "d/m/Y H:i", $row['dt'] );

if ( $where == 'song' )
{
	$tmp = getsongbyID( $row['what'] );
	$comment['what'] = $tmp['tenthat'];
}
elseif ( $where == 'album' )
{
	$tmp = getalbumbyID( $row['what'] );
	$comment['what'] = $tmp['tname'];
}

// sua
if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$datac = array() ;
	$datac['name'] = filter_text_input( 'name', 'post', '' );
	$datac['body'] = $nv_Request->get_string( 'body', 'post', '' );

	foreach ( $datac as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comment_" . $where . "` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=" . $back); die();
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
					".$lang_module['edit_comment']."
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
					<textarea style=\"width: 680px\" value=\"\" name=\"body\" id=\"describe\" cols=\"20\" rows=\"15\">" . $comment['body'] . "</textarea>\n
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