<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/12/2010 09:47
 */

if (!defined('NV_SYSTEM')) die('Stop!!!'); 
define('NV_IS_MOD_MUSIC', true); 
require_once NV_ROOTDIR . "/modules/" . $module_name . '/fuc_gobal.php';
//require_once NV_ROOTDIR . "/modules/" . $module_name . '/class/audioinfo.class.php';
// lay url

function get_URL()
{
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

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
	global $module_data, $db;
	
	$ads = 0 ;
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ads";
	$query = $db->sql_query( $sql );
	$num = $db->sql_numrows($query);
	$rand = rand( 1, $num );
	while ( $row = $db->sql_fetchrow( $query ))
	{
		if ( $rand == $row['stt'] )
		{
			$ads = $row['link'];
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
$setting = setting_music();
$songURL = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/";
$downURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=down&id=";

?>