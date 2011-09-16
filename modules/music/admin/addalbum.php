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

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

// khoi tao
$contents = "";
$error = "";

//lay gia tri
$albumdata['name'] = filter_text_input( 'ten', 'post', '' );
$albumdata['tname'] = filter_text_input( 'tenthat', 'post', '' );
$albumdata['casi'] = filter_text_input( 'casi', 'get,post', '' );
$albumdata['casimoi'] = filter_text_input( 'casimoi', 'post', '' );
$albumdata['thumb'] = $nv_Request->get_string( 'thumb', 'post', '' );
$albumdata['upboi'] = filter_text_input( 'upboi', 'post', '' );
$albumdata['describe'] = $nv_Request->get_string( 'describe', 'post', '' );
$albumdata['listsong'] = filter_text_input( 'listsong', 'post', '' );

$albumdata['name'] = empty( $albumdata['name'] ) ? change_alias( $albumdata['tname'] ) : change_alias( $albumdata['name'] );

if ( $albumdata['casimoi'] != '')
{
	$albumdata['casi'] = change_alias( $albumdata['casimoi'] );
	newsinger( $albumdata['casi'], $albumdata['casimoi'] );
}

$allsinger = getallsinger();

// lay du lieu
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $id == 0 )
{
    $page_title = $lang_module['add_album'];
}
else
{
    $page_title = $lang_module['edit_album'];
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id;
	$resuilt = $db->sql_query( $sql );
	$row = $db->sql_fetchrow( $resuilt );
	
	if ( ! $nv_Request->get_int( 'edit', 'post', 0 ) == 1 )
	{
		$albumdata['name'] = $row['name'];
		$albumdata['tname'] = $row['tname'];
		$albumdata['casi'] = $row['casi'];
		$albumdata['thumb'] = $row['thumb'];
		$albumdata['upboi'] = $row['upboi'];
		$albumdata['describe'] = $row['describe'];
		
		$albumdata['listsong'] = $row['listsong'];
	}
}

//sua album
if ( ( ( $nv_Request->get_int( 'edit', 'post', 0 ) ) == 1 ) and ( $error == '' ) )
{
	foreach ( $albumdata as $key => $data  )
	{	
		$query = $db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `" . $key . "` = " . $db->dbescape( $data ) . " WHERE `id` =" . $id );
	}
	if ( $query ) 
	{
		if( ! empty( $albumdata['listsong'] ) )
		{
			$numsong = 0;
			if( ! empty( $albumdata['listsong'] ) )
			{
				$numsong = explode( ",", $albumdata['listsong'] );
				$numsong = count( $numsong );
			}
			
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_album` SET `numsong`=" . $numsong . " WHERE `id`=" . $id );
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $db->dbescape( $albumdata['name'] ) . " WHERE `id` IN(" . $albumdata['listsong'] . ") AND ( `album`='na' OR `album`='' )" );
		}
		
		nv_del_moduleCache( $module_name );
		updateSwhendelA( $row['name'], $albumdata['name'] );
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=album"); die();
	}
	else
	{
		$error = $lang_module['error_save'];
	}
}

// them album
if ( ( $nv_Request->get_int( 'add', 'post', 0 ) == 1 ) and ( $error == '' ) )
{
	foreach ( $albumdata as $data => $null )
	{
		if ( in_array( $data, array('casimoi', 'listsong') ) ) continue;
		if	( $null == '' & $data != "album" ) $error = $lang_module['error_album']; 
	}
	
	list( $existalbum ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `name`=" . $db->dbescape( $albumdata['name'] ) ) );
	
	if( $existalbum )
	{
		$error = $lang_module['error_exist_album'];
	}
	
	if ( $error == "" )
	{
		updatesinger( $albumdata['casi'], 'numalbum', '+1' );
		
		$numsong = 0;
		if( ! empty( $albumdata['listsong'] ) )
		{
			$numsong = explode( ",", $albumdata['listsong'] );
			$numsong = count( $numsong );
		}
		
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_album` 
		(
			`id`, `name`, `tname`, `casi`, `thumb`, `numview`, `upboi`, `describe`, `active`, `numsong`, `listsong`
		) 
		VALUES 
		( 
			NULL, 
			" . $db->dbescape( $albumdata['name'] ) . ", 
			" . $db->dbescape( $albumdata['tname'] ) . ", 
			" . $db->dbescape( $albumdata['casi'] ) . ", 
			" . $db->dbescape( $albumdata['thumb'] ) . ", 
			1 , 
			" . $db->dbescape( $albumdata['upboi'] ) . ",	
			" . $db->dbescape( $albumdata['describe'] ) . "	,
			1,
			" . $numsong . ",
			" . $db->dbescape( $albumdata['listsong'] ) . "
		)
		"; 
		if ( $db->sql_query_insert_id( $query ) ) 
		{ 
			$db->sql_freeresult();
			
			if( ! empty( $albumdata['listsong'] ) )
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `album`=" . $db->dbescape( $albumdata['name'] ) . " WHERE `id` IN(" . $albumdata['listsong'] . ") AND ( `album`='na' OR `album`='' )" );
			}
			
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&op=album"); die();
		} 
		else 
		{ 
			$error = $lang_module['error_save']; 
		} 

	}

}

// hien bao loi
if( $error )
{
	$contents .= "<div class=\"quote\" style=\"width: 780px;\">\n
					<blockquote class=\"error\">
						<span>".$error."</span>
					</blockquote>
				</div>\n
				<div class=\"clear\">
				</div>";
}

// Get list song
if( ! empty( $albumdata['listsong'] ) )
{
	$listsong = $albumdata['listsong'];
	$albumdata['listsong'] = array();
	
	$sql = "SELECT `id`, `tenthat` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` IN(" . $listsong . ")";
	$result = $db->sql_query( $sql );
	while( list( $songid, $songname ) = $db->sql_fetchrow( $result ) )
	{
		$albumdata['listsong'][$songid] = $songname;
	}
}
else
{
	$albumdata['listsong'] = array();
}

// noi dung
$contents .="
<form method=\"post\" name=\"add_pic\">
	<table class=\"tab1\">
		<thead>
			<tr>
				<td colspan=\"2\">
					".$lang_module['album_info']."
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					".$lang_module['album_name']."
				</td>
				<td class=\"fixbg\">
					<input id=\"idtitle\" name=\"tenthat\" style=\"width: 470px;\" value=\"".$albumdata['tname']."\" type=\"text\"><img height=\"16\" alt=\"\" onclick=\"get_alias('idtitle','res_get_alias');\" style=\"cursor: pointer; vertical-align: middle;\" width=\"16\" src=\"".NV_BASE_SITEURL."images/refresh.png\">
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
				".$lang_module['song_name_short']."
				</td>
				<td class=\"fixbg\">
					<input id=\"idalias\" name=\"ten\" style=\"width: 470px;\" value=\"".$albumdata['name']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
				".$lang_module['singer']."	
				</td>
				<td class=\"fixbg\">
					<select name=\"casi\">\n";
					foreach ( $allsinger as $key => $title )
					{
						$i= "";
						if ( $albumdata['casi'] == $key )
						$i = "selected=\"selected\"";
						$contents .= "<option ". $i ." value=\"".$key."\" >" . $title . "</option>\n";
					}
					$contents .= "</select>
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
				".$lang_module['singer_new']."	
				</td>
				<td class=\"fixbg\">
				<input id=\"singer_sortname\" name=\"casimoi\" style=\"width: 470px;\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					".$lang_module['thumb']."
				</td>
				<td class=\"fixbg\">
				<input id=\"thumb\" name=\"thumb\" style=\"width: 370px;\" value=\"".$albumdata['thumb']."\" type=\"text\" />
                <input name=\"select\" type=\"button\" value=\"".$lang_module['select']."\" />
				<script type=\"text/javascript\">			
				$(\"input[name=select]\").click(function()
				{
					var area = \"thumb\"; // return value area
					var path = \"".NV_UPLOADS_DIR . "/" . $module_name."/thumb\";
					nv_open_browse_file(\"".NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type=image", "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no'."\");
					return false;
				});
				</script>
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					".$lang_module['who_up']."
				</td>
				<td class=\"fixbg\">
				<input name=\"upboi\" style=\"width: 470px;\" value=\"".$albumdata['upboi']."\" type=\"text\" />
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\" style=\"width:150px\">
					".$lang_module['describle']."
				</td>
				<td class=\"fixbg\">";
				if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
				{
					$contents .= nv_aleditor( 'describe', '98%', '250px', $albumdata['describe'] );
				}
				else
				{
					$contents .= "<textarea style=\"width: 98%\" value=\"".$albumdata['describe']."\" name=\"describe\" id=\"describe\" cols=\"20\" rows=\"15\"></textarea>\n";
				}
				$contents .="
				</td>
			</tr>
			<tr>
				<td class=\"fixbg strong\">" . $lang_module['content_list']. "<br />
					<input type=\"hidden\" name=\"listsong\" value=\"" . implode( ",", array_keys( $albumdata['listsong'] ) ) . "\"/>
					<a href=\"javascript:void(0);\" id=\"selectsongtoadd\">" . $lang_module['song_add'] . "</a>
				</td>
				<td><div id=\"listsong-area\" class=\"fixbg\" style=\"max-height:200px;overflow:auto\">";
					if( ! empty( $albumdata['listsong'] ) )
					{
						$contents .= "<div style=\"width:300px\" class=\"fl\">" . implode( "</div><div style=\"width:300px\" class=\"fl\">", $albumdata['listsong'] ) . "</div><div class=\"clear\"></div>";
					}
				$contents .="</div></td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\" class=\"fixbg\">\n
					<input name=\"confirm\" value=\"".$lang_module['save']."\" type=\"submit\">\n";
					if ( $id == 0 ) 
						$contents .="<input type=\"hidden\" name=\"add\" id=\"add\" value=\"1\">\n";
					else
						$contents .="<input type=\"hidden\" name=\"edit\" id=\"edit\" value=\"1\">\n";
                    $contents .="<span name=\"notice\" style=\"float: right; padding-right: 50px; color: red; font-weight: bold;\"></span>\n
				</td>\n
			</tr>\n
		</tbody>\n
	</table>\n
</form>\n";
// neu khong co ten album thi tu dong tao
if ( empty( $albumdata['ten'] ) )
{
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= '$("#idtitle").change(function () {
                    get_alias(\'idtitle\', \'res_get_alias\');
                });';
    $contents .= "</script>\n";
}

$contents .= '<script type="text/javascript">
$(document).ready(function() 
{
	$("a#selectsongtoadd").click(function(){
		var songlist = $("input[name=listsong]").attr("value");
		nv_open_browse_file( "' . NV_BASE_ADMINURL . 'index.php?" + nv_name_variable + "=' . $module_name . '&" + nv_fc_variable + "=findsongtoalbum&songlist=" + songlist, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
</script>';

$page_title = $lang_module['sub_album'];

$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

if( $id )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_album` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
		
	if ( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
		
	$row = $db->sql_fetchrow( $result );
		
	$array_old = $array = array(
		"name" => $row['name'],  //
		"tname" => $row['tname'],  //
		"casi" => $row['casi'],  //
		"thumb" => $row['thumb'],  //
		"describe" => nv_editor_br2nl( $row['describe'] ),  //
		"listsong" => $row['listsong'] ? explode( $row['listsong'] ) : array()  //
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['sub_edit_album'];
}
else
{
	$array = array(
		"name" => '',  //
		"tname" => '',  //
		"casi" => '',  //
		"thumb" => '',  //
		"describe" => '',  //
		"listsong" => ''  //
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['sub_add_album'];
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['name'] = filter_text_input( 'name', 'post', '', 1, 255 );
	$array['tname'] = filter_text_input( 'tname', 'post', '', 1, 255 );
	$array['casi'] = filter_text_input( 'casi', 'post', '', 1, 255 );
	$array['thumb'] = filter_text_input( 'thumb', 'post', '', 1, 255 );
}

$xtpl = new XTemplate( "album_content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_name );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );

$xtpl->parse('main');
//$contents = $xtpl->text('main');

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
