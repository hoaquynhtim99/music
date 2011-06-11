<?php

/**
 * @Project NUKE-VIET MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011
 * @Createdate 11-06-2011 12:39
 */
 
define( 'NV_ADMIN', true );

require_once ( str_replace( '\\\\', '/', dirname( __file__ ) ) . '/mainfile.php' );
require_once ( NV_ROOTDIR . "/includes/core/admin_functions.php" );

// Get module news version
require_once ( NV_ROOTDIR . "/modules/music/version.php" );
$new_version = $module_version['version'];

define ( 'NV_MUSIC_TABLE', $db_config['prefix'] . "_" . NV_LANG_DATA . "_music" );

// Get old module version
$result = $db->sql_query( "SELECT `char` FROM `" . NV_MUSIC_TABLE . "_setting` WHERE `key`='version'" );
list ( $old_version ) = $db->sql_fetchrow( $result );

if ( empty ( $old_version ) ) $old_version = "3.1.00";

$is_exit = false;
$break_update = false;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>NukeViet Music Update</title>
<style type="text/css">
body {
	font-family: Tahoma;
	font-size: 12pt;
}
a {
	color: #003366;
}
.authorinfo {
	position: absolute;
	width: 100%;
	background-color: #FFFFFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	bottom: 0px;
	right: auto;
	left: 0px;
}
a:visited {
	color: #003366;
}
a:active {
	color: #003366;
}
a:hover {
	color: #336699;
}
.style1 {
	border-collapse: collapse;
}
.style2 {
	font-family: Tahoma;
	font-size: 12pt;
}
.style3 {
	text-align: right;
}
</style>
<meta content="NukeViet, CMS, Phan Tan Dung" name="keywords" />
<meta content="NukeViet Music Update" name="description" />
</head>

<body style="color: #333333; margin: 100px; background-color: #E6E6E6">

<table cellpadding="0" class="style1" style="width: 100%">
	<tr>
		<td style="width: 233px">
		<img alt="Update Module Music" class="style2" height="91" src="images/logo.png" width="203" /></td>
		<td class="style2">
		<h1>NukeViet Module Music Update</h1>
		</td>
	</tr>
	<tr>
		<td class="style2" colspan="2">
		<table cellpadding="0" class="style1" style="width: 100%">
<?php

if ( defined( "NV_IS_GODADMIN" ) )
{
    
    if ( nv_version_compare( $new_version, "3.1.00" ) < 0 )
    {
		echo ('
				<tr>
					<td class="style3" style="width: 47px">
					<img alt="ok" height="12" src="images/error.png" width="12" /></td>
					<td style="width: 7px">&nbsp;</td>
					<td>You must upgrade your module to version 3.1.00 to upgrade. Click <a onclick="window.open(this.href);return false;" href="http://nukeviet.vn/phpbb/viewforum.php?f=118" title="Go forum">here</a> to see more detail</td>
				</tr>
		');
		$is_exit = true;
    }
	elseif ( nv_version_compare( $new_version, $old_version ) == 0 )
	{
		echo ('
				<tr>
					<td class="style3" style="width: 47px">
					<img alt="ok" height="12" src="images/ok.png" width="12" /></td>
					<td style="width: 7px">&nbsp;</td>
					<td>Your module is the latest version. Click <a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=music" title="Go forum">here</a> to visit your module</td>
				</tr>
		');
		$is_exit = true;
	}
	
	if ( ! $is_exit  )
	{
		echo ('
			<tr>
				<td class="style3" style="width: 47px">
				<img alt="ok" height="12" src="images/ok.png" width="12" /></td>
				<td style="width: 7px">&nbsp;</td>
				<td>Check version ok: Old ' . $old_version . ' New ' . $new_version . '</td>
			</tr>
		');

		if ( nv_version_compare( $old_version, "3.1.00" ) == 0 )
		{
			echo ('
				<tr>
					<td class="style3" style="width: 47px">&nbsp;</td>
					<td style="width: 7px">&nbsp;</td>
					<td><h3>Update from : 3.1.00</h3></td>
				</tr>
			');
			
			$sql = "INSERT INTO `" . NV_MUSIC_TABLE . "_setting` VALUES ( 15, 'del_cache_time_out', 259200, '' ), ( 16, 'version', 0, '" . $new_version . "' )";
			if ( $db->sql_query( $sql ) )
			{
				$info = "ok";
			}
			else
			{
				$info = "error";
				$is_exit = true;
				$break_update = true;
			}
			
			echo ('
				<tr>
					<td class="style3" style="width: 47px">
					<img alt="' . $info . '" height="12" src="images/' . $info . '.png" width="12" /></td>
					<td style="width: 7px">&nbsp;</td>
					<td>' . nv_unhtmlspecialchars ( $sql ) . '</td>
				</tr>
			');
		}
		
		
		if ( $break_update )
		{
			echo ('
					<tr>
						<td class="style3" style="width: 47px">
						<img alt="error" height="12" src="images/error.png" width="12" /></td>
						<td style="width: 7px">&nbsp;</td>
						<td>There are some error while update module so this action is break.</td>
					</tr>
			');
		}
		else
		{
			echo ('
					<tr>
						<td class="style3" style="width: 47px">
						<img alt="ok" height="12" src="images/ok.png" width="12" /></td>
						<td style="width: 7px">&nbsp;</td>
						<td>Module successfully upgraded, click <a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=music" title="Visit module">here</a> to return to visit the new music module</td>
					</tr>
			');
			
			nv_del_moduleCache( 'music' );
			//@unlink ( NV_ROOTDIR . "/update_module_music.php" );
			//@rename ( NV_ROOTDIR . "/modules/cargosmanage/action.php", NV_ROOTDIR . "/modules/cargosmanage/action_1.php" );
		}
    }
}
else
{
    echo ('
			<tr>
				<td class="style3" style="width: 47px">
				<img alt="ok" height="12" src="images/error.png" width="12" /></td>
				<td style="width: 7px">&nbsp;</td>
				<td>You must login as supper administrator to update module music. Click <a href="' . NV_BASE_ADMINURL . 'index.php" title="Admin login">here</a> to login</td>
			</tr>
	');
}
?>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<div class="authorinfo">
	<div style="text-align:center">
		<div colspan="3" style="background-color:#fff; padding:10px;font-family: arial;font-size: 11pt;position:relative">
			Module Music for NukeViet lastest version&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Powered by: <strong>Phan Tan Dung</strong>, email: <a href="mailto:phantandung92@gmaio.com" title="Send email to author">phantandung92@gmail.com</a><br />
			Go to <a href="http://nukeviet.vn/phpbb/viewforum.php?f=118" title="Visit forum">Forum</a> for more information. Thank you for using this module.
		</div>
	</div>
</div>
</body>
</html>