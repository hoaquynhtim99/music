<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

function nv_check_ok_song( $array )
{
	global $lang_module;
	
	if( empty( $array['ten'] ) ) return $lang_module['song_error_ten'];
	if( empty( $array['tenthat'] ) ) return $lang_module['song_error_tenthat'];
	if( empty( $array['theloai'] ) ) return $lang_module['song_error_theloai'];
	if( empty( $array['duongdan'] ) ) return $lang_module['song_error_duongdan'];
}

$contents = "";
$error = "";
$array_old = $array = array();

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$array['ten'] = filter_text_input( 'ten', 'post', '' );
$array['tenthat'] = filter_text_input( 'tenthat', 'post', '' );
$array['casi'] = filter_text_input( 'casi', 'get,post', '' );
$array['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$array['nhacsi'] = filter_text_input( 'nhacsi', 'get,post', '' );
$array['nhacsimoi'] = filter_text_input( 'nhacsimoi', 'get,post', '' );
$array['album'] = filter_text_input( 'album', 'get,post', '' );
$array['theloai'] = $nv_Request->get_int( 'theloai', 'get,post', 0 );
$array['duongdan'] = $nv_Request->get_string( 'duongdan', 'post', '' );
$array['bitrate'] = $nv_Request->get_int( 'bitrate', 'post', 0 );
$array['duration'] = $nv_Request->get_int( 'duration', 'post', 0 );
$array['size'] = $nv_Request->get_int( 'size', 'post', 0 );
$array['lyric'] = filter_text_textarea( 'lyric', '', NV_ALLOWED_HTML_TAGS );
$array['listcat'] = $nv_Request->get_typed_array( 'listcat', 'post', 'int' );

// Them ca si va nhac si moi
if ( $array['casimoi'] != '')
{
	$array['casi'] = change_alias( $array['casimoi'] );
	newsinger( $array['casi'], $array['casimoi'] );
}
if ( $array['nhacsimoi'] != '')
{
	$array['nhacsi'] = change_alias( $array['nhacsimoi'] );
	newauthor( $array['nhacsi'], $array['nhacsimoi'] );
}

// Lay cac the loai
$category = get_category() ;
if ( count ( $category ) == 0 ) 
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category" ) ;  
	die();	
}

$setting = setting_music();
$allsinger = getallsinger();
$allauthor = getallauthor();

if ( empty( $id ) )
{
    $page_title = $lang_module['add_song'];
}
else
{
    $page_title = $lang_module['edit_song'];
	
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` = " . $id;
	$result = $db->sql_query( $sql );
	$numrow = $db->sql_numrows( $result );
	$row = $db->sql_fetchrow( $result );
	
	if( $numrow != 1 ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$array_old['casi'] = $row['casi'];
	$array_old['nhacsi'] = $row['nhacsi'];
	$array_old['album'] = $row['album'];
	$array_old['theloai'] = $row['theloai'];
	
	$sql = "SELECT `body` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $id;
	$result = $db->sql_query( $sql );
	list( $array_old['lyric'] ) = $db->sql_fetchrow( $result );
	$array_old['lyric'] = nv_br2nl( $array_old['lyric'] );
	
	if ( $nv_Request->get_int( 'edit', 'post', 0 ) != 1 )
	{
		$array['ten'] = $row['ten'];
		$array['tenthat'] = $row['tenthat'];
		$array['casi'] = $row['casi'];
		$array['nhacsi'] = $row['nhacsi'];
		$array['album'] = $row['album'];
		$array['theloai'] = $row['theloai'];	
		$array['duongdan'] = admin_outputURL ( $row['server'], $row['duongdan'] );
		$array['upboi'] = $row['upboi'];
		$array['bitrate'] = $row['bitrate'];
		$array['duration'] = $row['duration'];
		$array['size'] = $row['size'];
		$array['listcat'] = $row['listcat'];
			
		if( ! empty( $array['listcat'] ) )
		{
			$array['listcat'] = explode( ",", $array['listcat'] );
		}
		else
		{
			$array['listcat'] = array();
		}
			
		$sql = "SELECT `body` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_lyric` WHERE `songid`=" . $id;
		$result = $db->sql_query( $sql );
		list( $array['lyric'] ) = $db->sql_fetchrow( $result );
		$array['lyric'] = nv_br2nl( $array['lyric'] );
	}
}

// Sua bai hat
if ( $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
{
	$error = nv_check_ok_song( $array );
	
	if( empty( $error ) )
	{
		$check_url = creatURL ( $array['duongdan'] );
		
		$array['duongdan'] = $check_url['duongdan'];
		$array['server'] = $check_url['server'];
		$array['id'] = $id;
		$array['username'] = $admin_info['username'];
		$array['userid'] = $admin_info['userid'];
				
		$check = nvm_edit_song( $array_old, $array );
		
		if ( $check ) 
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name); die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
	
}

// Them bai hat moi
if ( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{	
	$error = nv_check_ok_song( $array );
	
	if ( empty( $error ) )
	{
		$hit = "0-" . NV_CURRENTTIME;
		$check_url = creatURL ( $array['duongdan'] );
		$data = $check_url['duongdan'];
		$server = $check_url['server'];
		// update so bai hat
		
		$array['data'] = $data;
		$array['server'] = $server;
		$array['username'] = $admin_info['username'];
		$array['userid'] = $admin_info['userid'];
		$array['hit'] = $hit;
		
		$result_song_id = nvm_new_song( $array );
		
		if ( $result_song_id ) 
		{ 
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name ); die();
		}
		else 
		{ 
			$error = $lang_module['error_save']; 
		} 
	}
}

// Hien bao loi
if( $error )
{
	$contents .= "<div class=\"quote\" style=\"width:98%;\">\n
					<blockquote class=\"error\">
						<span>".$error."</span>
					</blockquote>
				</div>\n
				<div class=\"clear\">
				</div>";
}

if ( ! empty( $array['lyric'] ) ) $array['lyric'] = nv_htmlspecialchars( $array['lyric'] );

// noi dung trsng
$contents .="
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					".$lang_module['song_info']."
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['song_name']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"".$array['tenthat']."\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"".NV_BASE_SITEURL."images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['song_name_short']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"".$array['ten']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['singer']."	
				</td>
				<td style=\"background: #eee;\">
					<select name=\"casi\">\n";
					foreach ( $allsinger as $key => $title )
					{
						$i= "";
						if ( $array['casi'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['singer_new']."	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"singer_sortname\" name=\"casimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['author']."	
				</td>
				<td style=\"background: #eee;\">
					<select name=\"nhacsi\">\n";
					foreach ( $allauthor as $key => $title )
					{
						$i= "";
						if ( $array['nhacsi'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				".$lang_module['author_new']."	
				</td>
				<td style=\"background: #eee;\">
				<input name=\"nhacsimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					Album
				</td>
				<td style=\"background: #eee;\">
				<input name=\"album\" id=\"album\" style=\"width: 470px;\" type=\"text\" readonly=\"readonly\" value=\"" . $array['album'] . "\"/>
				<input type=\"button\" name=\"selectalbum\" value=\"" . $lang_module['select'] . "\"/>";
$contents .= "</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['category_base']."
				</td>
				<td style=\"background: #eee;\">
					<select name=\"theloai\">\n";
					foreach ( $category as $key => $title )
					{
						$i= "";
						if ( $array['theloai'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title['title'] . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['category_sub']."
				</td>
				<td style=\"background: #eee;max-height:250px;overflow:auto\">";
				
					foreach ( $category as $key => $title )
					{
						$checked = in_array( $key, $array['listcat'] ) ? " checked=\"checked\"" : "";
						$contents .= "<input name=\"listcat[]\" type=\"checkbox\"" . $checked . " value=\"" . $key . "\" />" . $title['title'] . "<br />\n";
					}
					
					$contents .= "
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['link']."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duongdan\" name=\"duongdan\" style=\"width: 370px;\" value=\"".$array['duongdan']."\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" /> =&gt;
                <input id=\"get_info\" name=\"get_info\" type=\"button\" value=\"".$lang_module['get_info']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"duongdan\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/" . $setting['root_contain'] . "\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				<script type=\"text/javascript\">			
				$(\"#get_info\").click(function () {
                    getsonginfo();
                });
				</script>	
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['bitrate'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"bitrate\" name=\"bitrate\" style=\"width: 370px;\" value=\"".$array['bitrate']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['duration'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duration\" name=\"duration\" style=\"width: 370px;\" value=\"".$array['duration']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
				" . $lang_module['size'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"size\" name=\"size\" style=\"width: 370px;\" value=\"".$array['size']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					" . $lang_module['add_lyric'] . "
				</td>
				<td style=\"background: #eee;\">
				<textarea name=\"lyric\" style=\"width: 470px;height:150px\" />" . $array['lyric'] . "</textarea>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"".$lang_module['save']."\" type=\"submit\">\n";
					if ( $id == 0 ) 
						$contents .="<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n";
					else
						$contents .="<input type=\"hidden\" name=\"edit\" id=\"edit\" value=\"1\">\n";
                    $contents .="<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n";
// Neu ten ngan gon bai hat chua có thi tu dong tao ten
if ( empty( $array['ten'] ) )
{
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= "$(\"#idtitle\").change(function () {
                    get_alias('idtitle', 'res_get_alias');
                });";
    $contents .= "</script>\n";
}

$contents .= "
<script type=\"text/javascript\">\n
	$(\"input[name=selectalbum]\").click( function() {
		nv_open_browse_file( \"" . NV_BASE_ADMINURL . "index.php?\" + nv_name_variable + \"=" . $module_name . "&\" + nv_fc_variable + \"=getalbumid&area=album\", \"NVImg\", \"850\", \"600\", \"resizable=no,scrollbars=no,toolbar=no,location=no,status=no\" );
		return false;
	});
</script>\n
";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
