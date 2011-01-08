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
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

//khoi tao
$contents = "";
$error = "";
$lyric = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$page_title = $lang_module['edit_lyric'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `id` = ".$id."";
$resuilt = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $resuilt );
$lyric['name'] = $row['user'];
$lyric['body'] = $row['body'];

$lyric['dt'] = nv_date( "d/m/Y H:i", $row['dt'] );

$tmp = getsongbyID( $row['songid'] );
$lyric['songname'] = $tmp['tenthat'];

// sua
if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$datac = array() ;
	$datac['user'] = filter_text_input( 'user', 'post', '' );
	$datac['body'] = $nv_Request->get_string( 'body', 'post', '' );

	foreach ( $datac as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=lyric" ); die();
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
					".$lang_module['edit_lyric']."
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
				if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
				{
					$contents .= nv_aleditor( 'body', '680px', '250px', $lyric['body'] );
				}
				else
				{
					$contents .= "<textarea style=\"width: 680px\" value=\"".$lyric['describe']."\" name=\"body\" id=\"body\" cols=\"20\" rows=\"15\"></textarea>\n";
				}
$contents .= "				
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