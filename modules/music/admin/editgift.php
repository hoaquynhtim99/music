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
$gift = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$page_title = $lang_module['edit_gift'];

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_gift` WHERE `id` = ".$id."";
$resuilt = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $resuilt );
$gift['who_send'] = $row['who_send'];
$gift['who_receive'] = $row['who_receive'];
$gift['body'] = $row['body'];

$gift['time'] = nv_date( "d/m/Y H:i", $row['time'] );

$tmp = getsongbyID( $row['songid'] );
$gift['songname'] = $tmp['tenthat'];

// sua
if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$datac = array() ;
	$datac['who_send'] = filter_text_input( 'who_send', 'post', '' );
	$datac['who_receive'] = filter_text_input( 'who_receive', 'post', '' );
	$datac['body'] = $nv_Request->get_string( 'body', 'post', '' );

	foreach ( $datac as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_gift` SET `".$key."` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id . "");
	}
	if ( $query ) 
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=gift" ); die();
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
					".$lang_module['edit_gift']."
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
					<textarea style=\"width: 680px\" name=\"body\" id=\"body\" cols=\"20\" rows=\"15\">" . $gift['body'] ."</textarea>\n		
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