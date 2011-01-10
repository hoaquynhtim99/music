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
//khoi tao
$contents = "";
$error = "";
//lay gia tri

$songdata = array();
$numsong = 0;
for ( $i = 1; $i <= 20; $i ++ )
{
	$songdata[$i] = array(
		"tenthat" => filter_text_input( 'tenthat' . $i . '', 'post', '' ),
		"ten" => change_alias( filter_text_input( 'tenthat' . $i . '', 'post', '' ) ),
		"duongdan" => $nv_Request->get_string( 'duongdan' . $i . '', 'post', '' ),
		"bitrate" => $nv_Request->get_int( 'bitrate' . $i . '', 'post', 0 ),
		"duration" => $nv_Request->get_int( 'duration' . $i . '', 'post', 0 ),
		"size" => $nv_Request->get_int( 'size' . $i . '', 'post', 0 )
	);
	if ( $songdata[$i]['tenthat'] != '' ) $numsong ++;
}

$casi = filter_text_input( 'casi', 'post', '' );
$casimoi = filter_text_input( 'casimoi', 'post', '' );
$album = filter_text_input( 'album', 'post', '' );
$theloai = $nv_Request->get_int( 'theloai', 'post', 0 );
$upboi = $nv_Request->get_string( 'upboi', 'post', '' );

if ( $casimoi != '')
{
	$casi = change_alias( $casimoi );
	$error = newsinger( $casi, $casimoi );
}
$category = get_category() ;
$setting = setting_music();
$allalbum = getallalbum();
$allsinger = getallsinger();

$page_title = $lang_module['sub_addlistsong'];

// them bai hat moi
if ( ($nv_Request->get_int( 'add', 'post', 0 ) == 1) && ( $error == '' ) )
{	
	
	if	( ( $casi == '' ) || ( $album == '' ) || ( $theloai == '' ) || ( $upboi == '' ) || ( $numsong == 0 )) 
	{
		$error = $lang_module['error_song']; 
	}
	
	if ( $error == "" )
	{
		$ok = false;
		for ( $i = 1; $i <= $numsong; $i ++)
		{
			if (preg_match('/^(ht|f)tp:\/\//', $songdata[$i]['duongdan'])) 
			{
				$data = $songdata[$i]['duongdan'];
				$server = 0;
			}
			else
			{
				$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
				$data = substr( $songdata[$i]['duongdan'], $lu );
				$server = 1;
			}
			
			// update so bai hat
			updatesinger( $casi, 'numsong', '+1' );
			updatealbum( $album, '+1' );
			
			$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` 
			(
				`id`, `ten`, `tenthat`, `casi`, `album`, `theloai`, `duongdan`, `upboi`, `numview`, `active`, `bitrate`, `size`, `duration`, `server`
			) 
			VALUES 
			( 
				NULL, 
				" . $db->dbescape( $songdata[$i]['ten'] ) . ", 
				" . $db->dbescape( $songdata[$i]['tenthat'] ) . ", 
				" . $db->dbescape( $casi ) . ", 
				" . $db->dbescape( $album ) . ", 
				" . $db->dbescape( $theloai ) . ", 
				" . $db->dbescape( $data )  . ", 
				" . $db->dbescape( $upboi ) . " ,
				0,
				1,
				" . $db->dbescape( $songdata[$i]['bitrate'] ) . " ,
				" . $db->dbescape( $songdata[$i]['size'] ) . " ,
				" . $db->dbescape( $songdata[$i]['duration'] ) . ",
				" . $server . "
			)
			"; 
			if ( $db->sql_query_insert_id( $query ) ) 
			{ 
				$db->sql_freeresult();
				$ok = true;
			} 
			else 
			{ 
				$error = $lang_module['error_save']; 
				$ok = false;
				break;
			} 
		}
		if ( $ok )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name); die();
		}
	}

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
				".$lang_module['singer']."	
				</td>
				<td style=\"background: #eee;\">
					<select name=\"casi\">\n";
					foreach ( $allsinger as $key => $title )
					{
						$i= "";
						if ( $casi == $key )
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
					Album
				</td>
				<td style=\"background: #eee;\">
				<select name=\"album\">\n";
					foreach ( $allalbum as $key => $title )
					{
						$i= "";
						if ( $album == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['category']."
				</td>
				<td style=\"background: #eee;\">
					<select name=\"theloai\">\n";
					foreach ( $category as $key => $title )
					{
						$i= "";
						if ( $theloai == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['who_up']."
				</td>
				<td style=\"background: #eee;\">
				<input name=\"upboi\" style=\"width: 470px;\" value=\"" . $upboi . "\" type=\"text\" />
				</td>
			</tr>
			<thead>
				<tr>
					<td colspan=\"2\">
						".$lang_module['song']."
					</td>
				</tr>
			</thead>";
		for ( $i = 1; $i <= 20; $i ++ )
		{
			$contents .="
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['song_name'] . " " . $i ."
				</td>
				<td style=\"background: #eee;\">
					<input name=\"tenthat" . $i . "\" style=\"width: 470px;\" value=\"".$songdata[$i]['tenthat']."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['link'] . " " . $i ."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duongdan" . $i . "\" name=\"duongdan" . $i . "\" style=\"width: 370px;\" value=\"".$songdata[$i]['duongdan']."\" type=\"text\" />
                <input id=\"" . $i . "\" name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" /> =&gt;
                <input id=\"get_info\" name=\"getinfo\" class=\"" . $i . "\" type=\"button\" value=\"".$lang_module['get_info']."\" />
				</td>
			</tr>
			<tr>
				<td style=\"background: #eee;\">" . $lang_module['bitrate'] . "-" . $lang_module['duration'] . "-" . $lang_module['size'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"bitrate" . $i . "\" name=\"bitrate" . $i . "\" value=\"".$songdata[$i]['bitrate']."\" type=\"text\" />
				<input id=\"duration" . $i . "\" name=\"duration" . $i . "\" value=\"".$songdata[$i]['duration']."\" type=\"text\" />
				<input id=\"size" . $i . "\" name=\"size" . $i . "\" value=\"".$songdata[$i]['size']."\" type=\"text\" />
				</td>
			</tr>";
		}
		$contents .="
			<tr>
				<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">\n
					<input name=\"confirm\" value=\"".$lang_module['save']."\" type=\"submit\">\n
					<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n
					<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n

<script type=\"text/javascript\">			
$(\"input[name=select]\").click(function()
{
	var id = $(this).attr(\"id\");
	var area = \"duongdan\" + id; // return value area
	var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/" . $setting['root_contain'] . "\";
	nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
	return false;
});
</script>
<script type=\"text/javascript\">			
$(\"input[name=getinfo]\").click(function () {
	var id = $(this).attr(\"class\");
	getsonginfo1(id);
});
</script>";	

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
