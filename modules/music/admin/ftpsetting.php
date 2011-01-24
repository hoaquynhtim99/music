<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) { die( 'Stop!!!' ); }

$page_title = $lang_module['ftpsetting'];
$contents = '';
$error = '';

$ftpdata = array();
$link_del = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del&where=_ftp&id=";
$urlback = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ftpsetting";
$link_active = "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&where=_ftp&id=";

if ( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
{
	$newid = $nv_Request->get_int( 'newid', 'post', 0 );
	$lastid = $nv_Request->get_int( 'lastid', 'post', 0 );
	for ( $i = 1; $i <= $newid ; $i++ )
	{
		if ( ( filter_text_input( 'host' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'user' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'pass' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'fulladdress' . $i . '', 'post', '' ) == '' ) || ( filter_text_input( 'subpart' . $i . '', 'post', '' ) == '' ) ) continue;
		$ftpdata[$i] = array(
			"host" => filter_text_input( 'host' . $i . '', 'post', '' ),
			"user" => filter_text_input( 'user' . $i . '', 'post', '' ),
			"pass" => filter_text_input( 'pass' . $i . '', 'post', '' ),
			"fulladdress" => $nv_Request->get_string( 'fulladdress' . $i . '', 'post', '' ),
			"subpart" => $nv_Request->get_string( 'subpart' . $i . '', 'post', '' )
		);
	}
	foreach ( $ftpdata as $i => $data  )
	{
		if ( $i > $lastid )
		{
			$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` ( `id`, `host`, `user`, `pass`, `fulladdress`, `subpart`, `active` ) VALUES ( " . $i . ", " . $db->dbescape( $data['host'] ) . ", " . $db->dbescape( $data['user'] ) . ", " . $db->dbescape( $data['pass'] ) . ", " . $db->dbescape( $data['fulladdress'] ) . ", " . $db->dbescape( $data['subpart'] ) . ", 1 ) "; 
			if ( $db->sql_query_insert_id( $query ) ) 
			{ 
				$db->sql_freeresult();
			}
			else
			{
				$error .= $lang_module['error_save'];
				break;
			}
		}
		else
		{
			foreach ( $data as $key => $value )
			{
				if ( ! $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` SET `" . $key . "` = " . $db->dbescape( $value ) . " WHERE `id` = " . $i . "  LIMIT 1 " ))
				{
					$error .= $lang_module['error_save'];
					break;				
				}
			}
		}
	}
}
$ftpdata = array();
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_ftp` ORDER BY id ASC";
$resuilt = $db->sql_query( $sql );
$newid = 1;
$lastid = 0;
while ( $row = $db->sql_fetchrow( $resuilt ) )
{
	if ( $newid <= $row['id'] ) 
	{
		$newid = $row['id'] + 1;
		$lastid = $row['id'];
	}
	$ftpdata[$row['id']] = array(
			"id" => $row['id'],
			"host" => $row['host'],
			"user" => $row['user'],
			"pass" => $row['pass'],
			"fulladdress" => $row['fulladdress'],
			"subpart" => $row['subpart'],
			"active" => ( $row['active'] == 1 )? $lang_module['active_yes'] : $lang_module['active_no']
		);
}
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

$contents .= "<form method=\"post\">";
$contents .= "<table class=\"tab1\">
<thead>
<tr>
    <td style=\"width:100px;\">" . $lang_module['ftp_host'] . "</td>
    <td style=\"width:110px;\">" . $lang_module['ftp_user'] . "</td>
    <td style=\"width:110px;\">" . $lang_module['ftp_pass'] . "</td>
    <td style=\"width:110px;\">" . $lang_module['ftp_full_address'] . "</td>
    <td style=\"width:110px;\">" . $lang_module['ftp_sub_address'] . "</td>
	<td width=\"40px\" align=\"center\">" . $lang_module['active'] . "</td>
	<td width=\"50px\" align=\"center\">" . $lang_module['feature'] . "</td>
</tr>
</thead>";
foreach ( $ftpdata as $j => $data )
{
	$contents .=
	"<tbody class=\"second\">
	<tr>
		<td style=\"width:100px;\">
			<input style=\"width:100px;\" name=\"host" . $j . "\" type=\"text\" value=\"" . $data['host'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:110px;\" name=\"user" . $j . "\" type=\"text\" value=\"". $data['user'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:110px;\" name=\"pass" . $j . "\" type=\"password\" value=\"". $data['pass'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:110px;\" name=\"fulladdress" . $j . "\" type=\"text\" value=\"". $data['fulladdress'] . "\" />
		</td>
		<td style=\"width:110px;\">
			<input style=\"width:110px;\" name=\"subpart" . $j . "\" type=\"text\" value=\"". $data['subpart'] . "\" />
		</td>
		<td style=\"width:110px;\" align=\"center\"><a href=\"" . $link_active . $data['id'] . "\" class=\"active\">". $data['active'] . "</a>
		<td style=\"width:110px;\" align=\"center\">
			<span class=\"delete_icon\">
				<a class=\"delfile\" href=\"" . $link_del . $data['id'] . "\">" . $lang_module['delete'] . "</a>
			</span>
		</td>
	</tr>
	</tbody>";
}
$contents .=
"<tbody class=\"second\">
<tr>
	<td style=\"width:100px;\">
		<input style=\"width:100px;\" name=\"host" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:110px;\" name=\"user" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:110px;\" name=\"pass" . $newid . "\" type=\"password\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:110px;\" name=\"fulladdress" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:110px;\">
		<input style=\"width:110px;\" name=\"subpart" . $newid . "\" type=\"text\" />
	</td>
	<td style=\"width:110px;\">
	</td>
	<td style=\"width:110px;\">
	</td>
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
	$(function()
	{		
		$('a[class=\"delfile\"]').click(function(event)
		{
			event.preventDefault();
			if (confirm(\"" . $lang_module['ftp_del_confirm'] . "\"))
			{
				var href = $(this).attr('href');
				$.ajax(
				{
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
		$('a[class=\"active\"]').click(function(event)
		{
			event.preventDefault();
			if (confirm(\"" . $lang_module['active_confirm'] . "\"))
			{
				var href = $(this).attr('href');
				$.ajax(
				{
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
</script>
";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>