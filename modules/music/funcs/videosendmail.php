<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
if ( $id > 0 )
{
        $result = "";
        $check = false;
        $checkss = $nv_Request->get_string( 'checkss', 'post', '' );
        if ( defined( 'NV_IS_ADMIN' ) )
        {
            $name = $admin_info['username'];
            $youremail = $admin_info['email'];
        }
        elseif ( defined( 'NV_IS_USER' ) )
        {
            $name = $user_info['username'];
            $youremail = $user_info['email'];
        }
        else
        {
            $name = filter_text_input( 'name', 'post', '', 1 );
            $youremail = filter_text_input( 'youremail', 'post', '' );
        }
        $to_mail = $content = "";
		
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id = ". $id ."";
		$query = $db->sql_query( $sql );
		$video = $db->sql_fetchrow( $query );

		
		if ( $nv_Request->get_int( 'send', 'post', 0 ) == 1 )
		{
			$link = "" . $global_config['site_url'] . "" . $mainURL . "=viewvideo/" . $id . "/" . $video['ten'];
			$link = "<a href=\"$link\">$link</a>\n";
            $nv_seccode = filter_text_input( 'nv_seccode', 'post', '' );
            $to_mail = filter_text_input( 'email', 'post', '' );
            $content = filter_text_input( 'content', 'post', '', 1 );
            $err_email = nv_check_valid_email( $to_mail );
            $err_youremail = nv_check_valid_email( $youremail );
            $err_name = "";
            $message = "";
            $success = "";
            if ( $global_config['gfx_chk'] > 0 and ! nv_capcha_txt( $nv_seccode ) )
            {
                $err_name = $lang_global['securitycodeincorrect'];
            }
            elseif ( empty( $name ) )
            {
                $err_name = $lang_module['sendmail_err_name'];
            }
            elseif ( empty( $err_email ) and empty( $err_youremail ) )
            {
                $subject = "".$lang_module['sendmail_welcome'].": ".$name;
                $message .= "".$lang_module['sendmail_welcome_1']." 
							<strong>" . $global_config['site_name'] . "</strong>
							".$lang_module['sendmail_welcome_2']."<br />
							<br />".$lang_module['video']."<strong> " . $video['tname'] . "</strong> ".$lang_module['sendmail_singer_show']." <strong> ".$video['casithat']." </strong> ".$lang_module['show_2'].".<br/>
							<br />".$lang_module['message'].": " . $content . "<br />
							<br /><strong>".$lang_module['sendmail_welcome_3'].": </strong><br />" . $link . "";
                $from = array( 
                    $name, $youremail 
                );
                $check = nv_sendmail( $from, $to_mail, $subject, $message );
                if ( $check )
                {
                    $success = "".$lang_module['send_mail_success']."<strong> " . $to_mail . "</strong>";
                }
                else
                {
                    $success = $lang_module['send_mail_err'];
                }
            }
            $result = array( 
                "err_name" => $err_name, 
				"err_email" => $err_email, 
				"err_yourmail" => $err_youremail, 
				"send_success" => $success, 
				"check" => $check 
            );
			}
        $sendmail = array( 
            "id" => $id, 
			"checkss" => md5( $id . session_id() . $global_config['sitekey'] ), 
			"v_name" => $name, 
			"video" => $video['tname'], 
			"singer" => $video['casithat'], 
			"v_mail" => $youremail, 
			"to_mail" => $to_mail, 
			"content" => $content, 
			"result" => $result, 
			"action" => "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=videosendmail&amp;id=" . $id 
        );
		
function sendmail_themme ( $sendmail )
{
    global $module_name, $module_info, $module_file, $global_config, $lang_module, $lang_global;
    $script = nv_html_site_js();
    $script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
    $script .= "<script type=\"text/javascript\">\n";
    $script .= "          $(document).ready(function(){\n";
    $script .= "            $(\"#sendmailForm\").validate();\n";
    $script .= "          });\n";
    $script .= "</script>\n";
    if ( NV_LANG_INTERFACE == 'vi' )
    {
        $script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/mudim.js\"></script>";
    }
    $sendmail['script'] = $script;
    $xtpl = new XTemplate( "videosendmail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'SENDMAIL', $sendmail );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
    if ( $global_config['gfx_chk'] > 0 )
    {
        $xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
        $xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
        $xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
        $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
        $xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
        $xtpl->parse( 'main.content.captcha' );
    }
    $xtpl->parse( 'main.content' );
    if ( ! empty( $sendmail['result'] ) )
    {
        $xtpl->assign( 'RESULT', $sendmail['result'] );
        $xtpl->parse( 'main.result' );
        if ( $sendmail['result']['check'] == true )
        {
            $xtpl->parse( 'main.close' );
        }
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}
$contents = sendmail_themme( $sendmail );
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );
}
Header( "Location: " . $global_config['site_url'] );
exit();

?>


