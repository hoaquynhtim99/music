<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9-8-2010 14:43
 */
if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['4category_main'];
$contents = '' ;
$category = get_category() ;
// lay du lieu 
$ok = $nv_Request->get_int( 'save', 'post', 0 );
$ca = array();
$ca[1] = $nv_Request->get_int( 'c1', 'post', 0 );
$ca[2] = $nv_Request->get_int( 'c2', 'post', 0 );
$ca[3] = $nv_Request->get_int( 'c3', 'post', 0 );
$ca[4] = $nv_Request->get_int( 'c4', 'post', 0 );

if ( !$ok )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_4category ORDER BY id ";
	$result = mysql_query( $sql );
	$i = 1 ;
	while ($row = $db->sql_fetchrow($result))
	{
		$ca[$i] = $row['cid'];
		$i ++ ;
	}

}
else
{
	foreach ( $ca as $key => $data  )
	{	
		$query = mysql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_4category` SET `cid` = " . $db->dbescape( $data ) . " WHERE `id` =" . $key . "");
	}
}
$contents .= "
<form method=\"post\">\n
<table class=\"tab1\">\n
	<thead>\n
		<tr>\n
			<td width=\"200px\" align=\"center\">STT</td>\n
			<td>".$lang_module['category']."</td>\n
		</tr>\n
	</thead>\n
	<tbody>\n";
		foreach ( $ca as $id => $cid )
		{
			$contents .= "
				<tr>\n
				<td style=\"background: #eee;\">".$id."</td>\n			
				<td style=\"background: #eee;\">
					<select name=\"c".$id."\">\n";
						foreach ( $category as $key => $title )
						{
							$i= "";
							if ( $cid == $key )
							$i = "selected=\"selected\"";
							$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
						}
						$contents .= "
					</select>
				</td>\n
			</tr>\n";
		}
		$contents .= "
		<tr>\n
		<td colspan=\"2\" style=\"text-align:center;background: #eee;\">
			<input type=\"submit\" value=\"".$lang_module['save']."\" />
		</td>
		</tr>\n
	</tbody>\n
</table>\n
<input type=\"hidden\" name=\"save\" value=\"1\" />
</form>\n";

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>