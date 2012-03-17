<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 26/01/2011 08:52 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
	die( 'Stop!!!' );
}

$page_title = $lang_module['ftpsetting'];
$contents = '';
$error = '';

$ftpdata = array();
$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del&where=_ftp&id=";
$urlback = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ftpsetting";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&where=_ftp&id=";

if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
{
	$newid = $nv_Request->get_int( 'newid', 'post', 0 );
	$lastid = $nv_Request->get_int( 'lastid', 'post', 0 );
	for( $i = 1; $i <= $newid; $i++ )
	{
		if( ( filter_text_input( 'host' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'user' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'pass' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'fulladdress' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'subpart' . $i . '', 'post', '' ) == '' ) ) continue;
		$ftpdata[$i] = array(
			"host" => filter_text_input( 'host' . $i . '', 'post', '' ),
			"user" => filter_text_input( 'user' . $i . '', 'post', '' ),
			"pass" => filter_text_input( 'pass' . $i . '', 'post', '' ),
			"fulladdress" => $nv_Request->get_string( 'fulladdress' . $i . '', 'post', '' ),
			"subpart" => $nv_Request->get_string( 'subpart' . $i . '', 'post', '' ),
			"ftppart" => $nv_Request->get_string( 'ftppart' . $i . '', 'post', '' ) );
	}
	foreach( $ftpdata as $i => $data )
	{
		if( $i > $lastid )
		{
			$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` ( `id`, `host`, `user`, `pass`, `fulladdress`, `subpart`, `ftppart`, `active` ) VALUES ( " . $i . ", " . $db->dbescape( $data['host'] ) . ", " . $db->dbescape( $data['user'] ) . ", " . $db->dbescape( $data['pass'] ) . ", " . $db->dbescape( $data['fulladdress'] ) . ", " . $db->dbescape( $data['subpart'] ) . ", " . $db->dbescape( $data['ftppart'] ) . ", 1 ) ";
			if( $db->sql_query_insert_id( $query ) )
			{
				$db->sql_freeresult();
				nv_del_moduleCache( $module_name );
			}
			else
			{
				$error .= $lang_module['error_save'];
				break;
			}
		}
		else
		{
			foreach( $data as $key => $value )
			{
				if( ! $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` SET `" . $key . "` = " . $db->dbescape( $value ) . " WHERE `id` = " . $i . "  LIMIT 1 " ) )
				{
					$error .= $lang_module['error_save'];
					break;
				}
			}
			nv_del_moduleCache( $module_name );
		}
	}
}

$newid = 2;
$lastid = 1;
$ftpdata = getFTP();

foreach( $ftpdata as $data )
{
	if( $newid <= $data['id'] )
	{
		$newid = $data['id'] + 1;
		$lastid = $data['id'];
	}
}

if( $error )
{
	$contents .= "
	<div class=\"quote\" style=\"width: 98%;\">
		<blockquote class=\"error\"><span>" . $error . "</span></blockquote>
	</div>
	<div class=\"clear\"></div>";
}

$contents .= "<form method=\"post\">\n";
$contents .= "<table class=\"tab1\">\n
<thead>\n
<tr>\n
    <td style=\"width:60px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_host'] . "<span></td>\n
    <td style=\"width:60px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_user'] . "<span></td>\n
    <td style=\"width:60px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_pass'] . "<span></td>\n
    <td style=\"width:110px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_full_address'] . "<span></td>\n
    <td style=\"width:100px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_floder'] . "<span></td>\n
    <td style=\"width:100px;\"><span style=\"font-size:11px;\">" . $lang_module['ftp_sub_address'] . "<span></td>\n
	<td style=\"width:20px;\" align=\"center\"><span style=\"font-size:11px;\">" . $lang_module['active'] . "<span></td>\n
	<td style=\"width:20px;\" align=\"center\"><span style=\"font-size:11px;\">" . $lang_module['feature'] . "<span></td>\n
</tr>\n
</thead>";
foreach( $ftpdata as $j => $data )
{
	$contents .= "<tbody class=\"second\">
	<tr>
		<td style=\"width:60px;\">
			<input style=\"width:100%;\" name=\"host" . $j . "\" type=\"text\" value=\"" . $data['host'] . "\" />
		</td>
		<td style=\"width:60px;\">
			<input style=\"width:100%;\" name=\"user" . $j . "\" type=\"text\" value=\"" . $data['user'] . "\" />
		</td>
		<td style=\"width:60px;\">
			<input style=\"width:100%;\" name=\"pass" . $j . "\" type=\"password\" value=\"" . $data['pass'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:100%;\" name=\"fulladdress" . $j . "\" type=\"text\" value=\"" . $data['fulladdress'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:100%;\" name=\"subpart" . $j . "\" type=\"text\" value=\"" . $data['subpart'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:100%;\" name=\"ftppart" . $j . "\" type=\"text\" value=\"" . $data['ftppart'] . "\" />
		</td>
		<td align=\"center\"><a href=\"" . $link_active . $data['id'] . "\" class=\"active\">" . $data['active'] . "</a>
		<td align=\"center\">
			<span class=\"delete_icon\">
				<a class=\"delfile\" href=\"" . $link_del . $data['id'] . "\">" . $lang_module['delete'] . "</a>
			</span>
		</td>
	</tr>
	</tbody>";
}
$contents .= "<tbody class=\"second\">
<tr>
	<td style=\"width:60px;\">
		<input style=\"width:100%;\" name=\"host" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:60px;\">
		<input style=\"width:100%;\" name=\"user" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:60px;\">
		<input style=\"width:100%;\" name=\"pass" . $newid . "\" type=\"password\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:100%;\" name=\"fulladdress" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:100%;\" name=\"subpart" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:100px;\">
		<input style=\"width:100%;\" name=\"ftppart" . $newid . "\" type=\"text\" />
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
</tbody>
</table>
<div style=\"text-align: center;\" colspan=\"2\">
<input type=\"submit\" value=\" " . $lang_module['save'] . "\">
<input type=\"hidden\" value=\"1\" name=\"save\">
<input type=\"hidden\" value=\"" . $newid . "\" name=\"newid\">
<input type=\"hidden\" value=\"" . $lastid . "\" name=\"lastid\">
</div>
</form>
<script type='text/javascript'>
	$(function(){		
		$('a[class=\"delfile\"]').click(function(event){
			event.preventDefault();
			if (confirm(\"" . $lang_module['ftp_del_confirm'] . "\")){
				var href = $(this).attr('href');
				$.ajax({
					type: 'POST',
					url: href,
					data: '',
					success: function(data)
					{
						alert(data);
						window.location = '" . $urlback . "';
					}
				});
			}
		});
		$('a[class=\"active\"]').click(function(event){
			event.preventDefault();
			if (confirm(\"" . $lang_module['active_confirm'] . "\")){
				var href = $(this).attr('href');
				$.ajax({
					type: 'POST',
					url: href,
					data: '',
					success: function(data)
					{
						window.location = '" . $urlback . "';
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