<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['hot_singer'];
$contents = '' ;

if ( ($nv_Request->get_int( 'save', 'post', 0 )) == 1 )
{
	$data = array();
	for ( $i = 1 ; $i <= 3 ; $i ++ )
	{	
		$data['stt'] = $nv_Request->get_int( 'stt'.$i.'', 'post', '' );
		$data['fullname'] = filter_text_input( 'fullname'.$i.'', 'post', '' );
		$data['name'] = filter_text_input( 'name'.$i.'', 'post', '' );
		$data['thumb'] = filter_text_input( 'thumb'.$i.'', 'post', '' );
		$data['large_thumb'] = filter_text_input( 'large_thumb'.$i.'', 'post', '' );
		
		foreach ( $data as $key => $value )
		{
			$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hot_singer` SET `".$key."` = " . $db->dbescape( $value ) . " WHERE `id` =" . $i . "");
		}
	}
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name ."&op=blockhotsinger"); die();
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_hot_singer` ORDER BY stt ASC";
$resuilt = $db->sql_query( $sql );
$stt = array() ;
$fullname = array() ;
$name = array() ;
$thumb = array() ;
$large_thumb = array() ;
$i = 1 ;
while ( $row = $db->sql_fetchrow( $resuilt ) )
{
	$stt[$i] = $row['stt'] ;
	$fullname[$i] = $row['fullname'] ;
	$name[$i] = $row['name'] ;
	$thumb[$i] = $row['thumb'] ;
	$large_thumb[$i] = $row['large_thumb'] ;
	
	$i ++ ;
}


$contents .="
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['hot_singer_info'] . " 1
				</td>
			</tr>
		</thead>
		<tbody>
			<tr><td style=\"background: #eee;\">".$lang_module['order']."</td><td style=\"background: #eee;\">
				<input id=\"idtitle\" name=\"stt1\" style=\"width: 470px;\" value=\"".$stt[1]."\" type=\"text\">
			</td></tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_fullname']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"fullname1\" style=\"width: 470px;\" value=\"".$fullname[1]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					<strong>".$lang_module['hot_singer_name']."</strong>: ".$lang_module['hot_singer_name_info']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"name1\" style=\"width: 470px;\" value=\"".$name[1]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_simg']." (120x84px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle1\" name=\"thumb1\" style=\"width: 470px;\" value=\"".$thumb[1]."\" type=\"text\">
					<input name=\"select1\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select1]\").click(function()
				{
					var area = \"idtitle1\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_limg']." (450x135px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle2\" name=\"large_thumb1\" style=\"width: 470px;\" value=\"".$large_thumb[1]."\" type=\"text\">
					<input name=\"select2\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select2]\").click(function()
				{
					var area = \"idtitle2\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>

				</td>
			</tr>
		</tbody>
	</table>
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['hot_singer_info'] . " 2
				</td>
			</tr>
		</thead>
		<tbody>
			<tr><td style=\"background: #eee;\">".$lang_module['order']."</td><td style=\"background: #eee;\">
				<input id=\"idtitle\" name=\"stt2\" style=\"width: 470px;\" value=\"".$stt[2]."\" type=\"text\">
			</td></tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_fullname']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"fullname2\" style=\"width: 470px;\" value=\"".$fullname[2]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					<strong>".$lang_module['hot_singer_name']."</strong>: ".$lang_module['hot_singer_name_info']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"name2\" style=\"width: 470px;\" value=\"".$name[2]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_simg']." (120x84px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle3\" name=\"thumb2\" style=\"width: 470px;\" value=\"".$thumb[2]."\" type=\"text\">
					<input name=\"select3\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select3]\").click(function()
				{
					var area = \"idtitle3\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_limg']." (450x135px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle4\" name=\"large_thumb2\" style=\"width: 470px;\" value=\"".$large_thumb[2]."\" type=\"text\">
					<input name=\"select4\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select4]\").click(function()
				{
					var area = \"idtitle4\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
					
				</td>
			</tr>
		</tbody>
	</table>
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					" . $lang_module['hot_singer_info'] . " 3
				</td>
			</tr>
		</thead>
		<tbody>
			<tr><td style=\"background: #eee;\">".$lang_module['order']."</td><td style=\"background: #eee;\">
				<input id=\"idtitle\" name=\"stt3\" style=\"width: 470px;\" value=\"".$stt[3]."\" type=\"text\">
			</td></tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_fullname']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"fullname3\" style=\"width: 470px;\" value=\"".$fullname[3]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					<strong>".$lang_module['hot_singer_name']."</strong>: ".$lang_module['hot_singer_name_info']."
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle\" name=\"name3\" style=\"width: 470px;\" value=\"".$name[3]."\" type=\"text\">
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_simg']." (120x84px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle5\" name=\"thumb3\" style=\"width: 470px;\" value=\"".$thumb[3]."\" type=\"text\">
					<input name=\"select5\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select5]\").click(function()
				{
					var area = \"idtitle5\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td style=\"width: 150px; background: #eee;\">
					".$lang_module['hot_singer_limg']." (450x135px)
				</td>
				<td style=\"background: #eee;\">
					<input id=\"idtitle6\" name=\"large_thumb3\" style=\"width: 470px;\" value=\"".$large_thumb[3]."\" type=\"text\">
					<input name=\"select6\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select6]\").click(function()
				{
					var area = \"idtitle6\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<thead>
				<tr>
					<td align=\"center\" colspan=\"2\">
						<input type=\"hidden\" name=\"save\" value=\"1\" />
						<input type=\"submit\" value=\"SAVE\" />
					</td>
				</tr>
			</thead>
		</tbody>
	</table>";

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>