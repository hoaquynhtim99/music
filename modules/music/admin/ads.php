<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['ads_title'];

$contents = "";
$error = "";

$name = filter_text_input( 'name', 'post', '' );
$link = $nv_Request->get_string( 'link', 'post', '' );
$url = $nv_Request->get_string( 'url', 'post', '' );

// Xu li
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ads` ORDER BY `stt`";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
$numadd = $num + 1;

if( $nv_Request->get_int( 'add', 'post', 0 ) == 1 )
{
	if( empty( $name ) )
	{
		$error = $lang_module['error_name_ads'];
	}
	elseif( empty( $link ) )
	{
		$error = $lang_module['error_link_ads'];
	}
	else
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_ads` VALUES (
			NULL, 
			" . $db->dbescape( $numadd ) . ", 
			" . $db->dbescape( $link ) . ", 
			" . $db->dbescape( $name ) . ",
			" . $db->dbescape( $url ) . "
		)";

		if( $db->sql_query_insert_id( $sql ) )
		{
			$db->sql_freeresult();
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
			die();
		}
		else
		{
			$error = $lang_module['error_save'];
		}
	}
}

if( $error != '' )
{
	$contents .= "<div class=\"quote\" style=\"width: 98%;\"><blockquote class=\"error\"><span>" . $error . "</span></blockquote></div><div class=\"clear\"</div>";
}

$contents .= "<table class=\"tab1\">
	<thead>
		<tr>
			<td width=\"20px\">STT</td>
			<td>" . $lang_module['ads_name'] . "</td>
			<td width\"100px\" align=\"center\">" . $lang_module['feature'] . "</td>
		</tr>
	</thead>
	<tbody>";
$i = 1;
while( $row = $db->sql_fetchrow( $result ) )
{
	$contents .= "
	<tr>
		<td align=\"center\">" . $i . "</td>
		<td>" . $row['name'] . "</td>
		<td align=\"center\">
			<span class=\"delete_icon\">
				<a class=\"delfile\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delads&id=" . $row['id'] . "\">" . $lang_module['delete'] . "</a>
			</span>
		</td>
	</tr>";
	$i++;
}
$contents .= "</tbody>
</table>
<form method=\"post\">
<table class=\"tab1\">
	<thead>
		<tr>
			<td colspan=\"2\">" . $lang_module['ads_add'] . "</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style=\"background: #eee;\">" . $lang_module['ads_name'] . "</td>
			<td style=\"background: #eee;\">
				<input style=\"width:400px;\" type=\"text\" name=\"name\" value=\"" . $name . "\" />
			</td>
		</tr>
		<tr>
			<td style=\"background: #eee;\">" . $lang_module['link'] . "</td>
			<td style=\"background: #eee;\">
				<input style=\"width:400px;\" type=\"text\" name=\"link\" id=\"link\" value=\"" . $link . "\" />
                <input name=\"select\" type=\"button\" value=\"" . $lang_module['select'] . "\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"link\"; // return value area
					var path = \"" . NV_UPLOADS_DIR . "/" . $module_name . "/ads\";
					nv_open_browse_file(\"" . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no' . "\");
					return false;
				});
				</script>
			</td>
		</tr>
		<tr>
			<td style=\"background: #eee;\">" . $lang_module['ads_url'] . "</td>
			<td style=\"background: #eee;\">
				<input style=\"width:400px;\" type=\"text\" name=\"url\" id=\"url\" value=\"" . $url . "\" />
			</td>
		</tr>
		<tr>
			<td colspan=\"2\" align=\"center\" style=\"background: #eee;\">
				<input type=\"hidden\" name=\"add\" value=\"1\" />
				<input type=\"submit\" value=\"" . $lang_module['save'] . "\" />
			</td>
		</tr>
	</tbody>
</table>
</form>
<script type=\"text/javascript\">
	$(function(){
		$('a[class=\"delfile\"]').click(function(event){
			event.preventDefault();
			if (confirm(\"" . $lang_module['ads_del_confirm'] . "\")){
				var href = $(this).attr('href');
				$.ajax({
					type: 'POST',
					url: href,
					data: '',
					success: function(data){
						alert(data);
						window.location = '" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ads';
					}
				});
			}
		});
	});
</script>";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>