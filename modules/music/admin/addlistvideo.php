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
$contents = "";
$error = "";

$videodata = array();
$numvideo = 0;
for ( $i = 1; $i <= 20; $i ++ )
{
	$videodata[$i] = array(
		"tname" => filter_text_input( 'tname' . $i . '', 'post', '' ),
		"name" => change_alias( filter_text_input( 'tname' . $i . '', 'post', '' ) ),
		"thumb" => $nv_Request->get_string( 'thumb' . $i . '', 'post', '' ),
		"duongdan" => $nv_Request->get_string( 'duongdan' . $i . '', 'post', '' ),
	);
	if ( $videodata[$i]['tname'] != '' ) $numvideo ++;
}

$casi = filter_text_input( 'casi', 'post', '' );
$casimoi = filter_text_input( 'casimoi', 'post', '' );
$nhacsi = filter_text_input( 'nhacsi', 'post', '' );
$nhacsimoi = filter_text_input( 'nhacsimoi', 'post', '' );
$theloai = $nv_Request->get_int( 'theloai', 'post', 0 );

if ( $casimoi != '')
{
	$casi = change_alias( $casimoi );
	$error = newsinger( $casi, $casimoi );
}
if ( $nhacsimoi != '')
{
	$nhacsi = change_alias( $nhacsimoi );
	$error = newsinger( $nhacsi, $nhacsimoi );
}
$category = get_videocategory() ;
if ( count ( $category ) == 0 ) 
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video_category" ) ;  
	die();	
}
$setting = setting_music();
$allsinger = getallsinger();
$allauthor = getallauthor();

$page_title = $lang_module['video_listadd'];

// them video moi
if ( ($nv_Request->get_int( 'add', 'post', 0 ) == 1) && ( $error == '' ) )
{	
	
	if	( ( $casi == '' ) || ( $theloai == '' ) || ( $numvideo == 0 )) 
	{
		$error = $lang_module['error_video']; 
	}
	
	if ( $error == "" )
	{
		$ok = false;
		$hit = "0-" . NV_CURRENTTIME;
		for ( $i = 1; $i <= $numvideo; $i ++)
		{
			$check_url = creatURL ( $videodata[$i]['duongdan'] );
			$data = $check_url['duongdan'];
			$server = $check_url['server'];
			
			// update so video
			updatesinger( $casi, 'numvideo', '+1' );
			updateauthor( $nhacsi, 'numvideo', '+1' );
			
			$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_video` 
			(
				`id`, `name`, `tname`, `casi`, `nhacsi`, `theloai`, `duongdan`, `thumb`, `view`, `active`, `dt`, `server`, `binhchon`, `hit`
			) 
			VALUES 
			( 
				NULL, 
				" . $db->dbescape( $videodata[$i]['name'] ) . ", 
				" . $db->dbescape( $videodata[$i]['tname'] ) . ", 
				" . $db->dbescape( $casi ) . ", 
				" . $db->dbescape( $nhacsi ) . ", 
				" . $db->dbescape( $theloai ) . ", 
				" . $db->dbescape( $data )  . ", 
				" . $db->dbescape( $videodata[$i]['thumb'] ) . " ,
				0,
				1,
				UNIX_TIMESTAMP() ,
				" . $server . ",
				0,
				" . $db->dbescape( $hit ) . "
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
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=videoclip"); die();
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
					".$lang_module['video_info']."
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
				".$lang_module['author']."	
				</td>
				<td style=\"background: #eee;\">
					<select name=\"nhacsi\">\n";
					foreach ( $allauthor as $key => $title )
					{
						$i= "";
						if ( $nhacsi == $key )
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
			<thead>
				<tr>
					<td colspan=\"2\">
						".$lang_module['video']."
					</td>
				</tr>
			</thead>";
		for ( $i = 1; $i <= 20; $i ++ )
		{
			$contents .="
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['video_name'] . " " . $i ."
				</td>
				<td style=\"background: #eee;\">
					<input name=\"tname" . $i . "\" style=\"width: 470px;\" value=\"".$videodata[$i]['tname']."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['link'] . " " . $i ."
				</td>
				<td style=\"background: #eee;\">
				<input id=\"duongdan" . $i . "\" name=\"duongdan" . $i . "\" style=\"width: 370px;\" value=\"".$videodata[$i]['duongdan']."\" type=\"text\" />
                <input id=\"" . $i . "\" name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" />
				</td>
			</tr>
			<tr>
				<td style=\"background: #eee;\">
					" . $lang_module['thumb'] . "	
				</td>
				<td style=\"background: #eee;\">
				<input id=\"thumb" . $i . "\" name=\"thumb" . $i . "\" style=\"width: 370px;\" value=\"".$videodata[$i]['thumb']."\" type=\"text\" readonly=\"readonly\"/>
                <input id=\"" . $i . "\" name=\"selectthumb\" type=\"button\" value=\"".$lang_module['select']."\" />
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
	var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/" . $setting['root_contain'] . "/video\";
	nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
	return false;
});
</script>
<script type=\"text/javascript\">			
$(\"input[name=selectthumb]\").click(function()
{
	var id = $(this).attr(\"id\");
	var area = \"thumb\" + id;
	var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/clipthumb\";
	nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
	return false;
});
</script>";	

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
