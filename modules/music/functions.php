<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011
 * @createdate 26/01/2011 10:10 AM
 */

if (!defined('NV_SYSTEM')) die('Stop!!!'); 
define('NV_IS_MOD_MUSIC', true); 
require_once NV_ROOTDIR . "/modules/" . $module_name . '/fuc_gobal.php';

// hien thi cac trang
function new_page ( $ts, $now_page, $link)
{
	$page = '' ;
	if($ts>1)
	{
		$page = "<div id=\"numpage\"><p>";
		if( $ts>5 && $now_page>3 )
		{
			$page .= "<a href=\"" . $link . "";
			$page .= "/1\" class=\"next\">&lt;&lt;</a> ... ";
		}
		if($now_page > 1)
		{
			$now_page_min = $now_page -1 ;
			$page .= "<a href=\"" . $link . "";
			$page .= "/".$now_page_min."\" class=\"next\">&lt;</a> ";
		}
		if($ts<=5)
		{
			$i=1;
			while($i <= $ts)
			{
				if($i==$now_page){$page .= "<b> ".$i." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "/".$i."\">".$i."</a> ";
				}
				$i++;	
			}
		}
		else if($now_page<=2)
		{
			$i=1;
			while($i <= 5){
			if($now_page==$i){$page .= "<b> ".$i." </b>";}
			else
			{
				$page .= "<a href=\"" . $link . "";
				$page .= "/".$i."\">".$i."</a> ";
			}
			$i++;	
			}
		}
		else if($now_page < ($ts - 2))
		{
			$i = 1;
			$j = $now_page -2;
			while($i<=5)
			{
				if($now_page==$j){$page .= "<b> ".$j." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "/".$j."\">".$j."</a> ";
				}
				$i++;
				$j++;
			}
		}
		else
		{
			$i=1;
			$j= $ts-4;
			while($i<=5)
			{
				if($now_page==$j){$page .= "<b> ".$j." </b>";}
				else
				{
					$page .= "<a href=\"" . $link . "";
					$page .= "/".$j."\">".$j."</a> ";
				}
				$i++;
				$j++;
			}
		}
		if($now_page<$ts)
		{
			$now_page_max = $now_page +1 ;
			$page .= " <a href=\"" . $link . "";
			$page .= "/".$now_page_max."\" class=\"next\">&gt;</a>";
		}
		if(($ts > 5)&&($now_page<($ts -2)))
		{
			$page .= " ... <a href=\"" . $link . "";
			$page .= "/".$ts."\" class=\"next\">&gt;&gt;</a>";
		}
		$page .= "</p></div>";
	}
	return $page;
}
// lay quang cao
function getADS()
{
	global $module_data, $global_config, $db;
	
	$ads = array() ;
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ads";
	$query = $db->sql_query( $sql );
	$num = $db->sql_numrows($query);
	$rand = rand( 1, $num );
	if( $num == 0 )
	{
		$ads['link'] = NV_BASE_SITEURL . "modules/" . $module_data . "/data/default.swf";
		$ads['url'] = $global_config['site_url'];
	}
	while ( $row = $db->sql_fetchrow( $query ))
	{
		if ( $rand == $row['stt'] )
		{
			$ads['link'] = $row['link'];
			$ads['url'] = $row['url'];
			break ;
		}
	}
	return $ads;
}
// lay 10 bai hat moi theo ten
function gettopsongbyalbumNAME( $name )
{
	global $module_data, $db ;

	$data = array() ;
	$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE album = \"" . $name . "\" AND `active` = 1 ORDER BY id DESC LIMIT 0,10 " );

	return $result ;
}
// update bai hat
function updateHIT_VIEW( $id, $where )
{
	global $module_data, $db ;
	( $where == "_video" )? ( $key = "view" ) : ( $key = "numview" );
	
	$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET " . $key . " = " . $key . "+1 WHERE `id` =" . $id );
	if ( $where == '' ) $data = getsongbyID( $id );
	else $data = getvideobyID( $id ); 
	
	$hitdata = explode ( "-", $data['hit'] );
	$hittime =  $hitdata[1];
	$hitnum = $hitdata[0];
	if ( ( NV_CURRENTTIME - $hittime ) > 864000 )
	{
		$hit = "0-" . NV_CURRENTTIME;
		$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET hit = " . $db->dbescape( $hit ) . " WHERE `id` =" . $id );
	}
	else
	{
		$newhit = $hitnum + 1;
		$hit = $newhit . "-" . $hittime;
		$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . $where . "` SET hit = " . $db->dbescape( $hit ) . " WHERE `id` =" . $id );
	}
	return;
}

$setting = setting_music();
$downURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=down&amp;id=";

?>