<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if ( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

if ( $sys_info['allowed_set_time_limit'] )
{
    set_time_limit( 0 );
}

$temp_extract_dir = 'install/updatemusic';

$ftp_check_login = 0;
if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) == 1 )
{
    $ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
    $ftp_port = intval( $global_config['ftp_port'] );
    $ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
    $ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
    $ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
    // set up basic connection
    $conn_id = ftp_connect( $ftp_server, $ftp_port );
    // login with username and password
    $login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
    if ( ( ! $conn_id ) || ( ! $login_result ) )
    {
        $ftp_check_login = 3;
    }
    elseif ( ftp_chdir( $conn_id, $ftp_path ) )
    {
        $ftp_check_login = 1;
    }
    else
    {
        $ftp_check_login = 2;
    }
}

function nv_site_path_update ( $path )
{
    if ( preg_match( "/^uploads\/banners\/(.*)$/i", $path, $matches ) )
    {
        //Thu muc uploads banner
        return NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^admin\/editors\/(.*)$/i", $path, $matches ) )
    {
        //Ten thu muc editors
        return NV_EDITORSDIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^admin\/(.*)$/i", $path, $matches ) )
    {
        return NV_ADMINDIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^uploads\/(.*)$/i", $path, $matches ) )
    {
        //Thu muc uploads
        return NV_UPLOADS_DIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^files\/(.*)$/i", $path, $matches ) )
    {
        //Thu muc files
        return NV_FILES_DIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^data\/(.*)$/i", $path, $matches ) )
    {
        //Ten thu muc luu data
        return NV_DATADIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^files\/(.*)$/i", $path, $matches ) )
    {
        return NV_FILES_DIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^sess\/(.*)$/i", $path, $matches ) )
    {
        //Thu muc chua sessions
        return NV_SESSION_SAVE_PATH . '/' . $matches[1];
    }
    elseif ( preg_match( "/^tmp\/(.*)$/i", $path, $matches ) )
    {
        //Thu muc chua cac file tam thoi
        return NV_TEMP_DIR . '/' . $matches[1];
    }
    elseif ( preg_match( "/^cache\/(.*)$/i", $path, $matches ) )
    {
        //Ten thu muc cache
        return NV_CACHEDIR . '/' . $matches[1];
    }
    return $path;
}

$lang_module['revision'];

global $module_setting;
$module_setting = setting_music();

$revision = isset( $module_setting['revision'] ) ? intval( $module_setting['revision'] ) : 0;
$version = $module_setting['version'];

$step = ( isset( $_GET['step'] ) ) ? intval( $_GET['step'] ) : 0;
$checkss = ( isset( $_GET['checkss'] ) ) ? trim( $_GET['checkss'] ) : '';
if ( empty( $step ) )
{
    if ( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) )
    {
        $step = 3;
    }
    else
    {
        $step = 1;
    }
}

$nextstep = $step + 1;

if ( $step == 1 or $step == 2 )
{    
    $contents = '<center><br /><br /><br /><b>' . $lang_module['autoupdate_get_error'] . '</b><br /></center>';
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}
elseif ( $step == 3 and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) )
{
    if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' ) )
    {
        nv_copyfile( NV_ROOTDIR . '/language/index.html', NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' );
    }
    if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' ) )
    {
        nv_copyfile( NV_ROOTDIR . '/language/.htaccess', NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' );
    }
    	
    define( 'NV_AUTO_UPDATE_MUSIC', true );
    $update_info = array();
    $add_files = array();
    $edit_files = array();
    $delete_files = array();
    require_once ( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' );
	
    if ( $checkss != md5( $step . $global_config['sitekey'] . session_id() ) )
    {
        $contents = '<br />';
        $contents .= '<div id="message_31" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/></div>';
        $contents .= '<div id="step_31" >' . $lang_module['autoupdate_form_upload'];
        $contents .= '<center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate_check_file'] . '"/><center></div>';
        $contents .= '<script type="text/javascript">
        		 $(function(){
        		 	$("input[name=install_content_overwrite]").click(function(){
        		 		$("#message_31").show();
				 		$("#step_31").html("");
				 		$("#step_31").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $step . '&checkss=' . md5( $step . $global_config['sitekey'] . session_id() ) . '",function(){
							$("#message_31").hide();
							});
					});
        		 });
			</script>';
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }
    else
    {
        $user_edit_file = array();
        $check_files = array_merge( $edit_files, $delete_files );
        if ( ! empty( $check_files ) )
        {
            asort( $check_files );
            foreach ( $check_files as $file )
            {
                $file_p = nv_site_path_update( $file );
                $cur_file = NV_ROOTDIR . '/' . $file_p;
                $old_file = NV_ROOTDIR . '/' . $temp_extract_dir . '/old/' . $file;
                if ( ! file_exists( $cur_file ) or ! file_exists( $old_file ) )
                {
                    $user_edit_file[] = $file_p;
                }
                elseif ( md5_file( $cur_file ) != md5_file( $old_file ) )
                {
                    $user_edit_file[] = $file_p;
                }
            }
        }
        if ( ! empty( $user_edit_file ) )
        {
            $contents .= '<br /><br /><b>' . $lang_module['autoupdate_change'] . ':</b>';
            $contents .= '<br /> ' . implode( "<br />", $user_edit_file );
            $contents .= '<br /><br />' . $lang_module['autoupdate_overwrite'];
        }
        else
        {
            $contents .= '<br />' . $lang_module['autoupdate_click_update'];
        }
        $contents .= '<br /><br />' . $lang_module['autoupdate_backupfile'] . ': <br /><br /><b>' . NV_LOGS_DIR . '/data_logs/backup_update_music_' . date( 'Y_m_d' ) . '_' . md5( $global_config['sitekey'] . session_id() ) . '.zip</b>';
        $contents .= '<br /><br />';
        $contents .= '<div id="message_32" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/></div>';
        $contents .= '<br /><div id="step_32" ><center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate'] . '"/><center></div>';
        $contents .= '<script type="text/javascript">
        		 $(function(){
        		 	$("input[name=install_content_overwrite]").click(function(){
        		 		if(confirm("' . $lang_module['autoupdate_confirm'] . '")){
	        		 		$("#message_32").show();
					 		$("#step_32").html("");
					 		$("#step_32").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '",function(){
								$("#message_32").hide();
								});
        					}
					});
        		 });
			</script>';
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo $contents;
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }
}
elseif ( $step == 4 and md5( $step . $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/old/' ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' ) )
{
    define( 'NV_AUTO_UPDATE_MUSIC', true );
    $update_info = array();
    $add_files = array();
    $edit_files = array();
    $delete_files = array();
    require_once ( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' );
    	
    // backup file
    $error_backup = false;
    $backup_files = array_merge( $edit_files, $delete_files );
    if ( ! empty( $backup_files ) )
    {
        $zip_file_backup = array();
        foreach ( $backup_files as $file_i )
        {
            $file_p = nv_site_path_update( $file_i );
            if ( is_file( NV_ROOTDIR . '/' . $file_p ) )
            {
                $zip_file_backup[] = NV_ROOTDIR . '/' . $file_p;
            }
        }
				
        if ( ! empty( $zip_file_backup ) )
        {
            $file_src = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/backup_update_music_' . date( 'Y_m_d' ) . '_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';
            
            require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
            $zip = new PclZip( $file_src );
            $return = $zip->add( $zip_file_backup, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
            if ( empty( $return ) )
            {
                $check_backup = true;
            }
        }
    }
	
    if ( $error_backup )
    {
        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_backupfile_error'] . ':' . NV_LOGS_DIR . '/data_logs</b>';
    }
    else
    {
        $move_files = array_merge( $add_files, $edit_files );	
        if ( ! empty( $move_files ) )
        {
            // create new folder
            $error_create_folder = array();
			
			
            foreach ( $move_files as $file_i )
            {
                $file_p = nv_site_path_update( $file_i );
                $cp = "";
                $e = explode( "/", $file_p );
                foreach ( $e as $p )
                {
                    if ( ! empty( $p ) and is_dir( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $cp . $p ) and ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
                    {
                        if ( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, $cp . $p ) ) )
                        {
                            @mkdir( NV_ROOTDIR . '/' . $cp . $p );
                        }
                        if ( ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
                        {
                            $error_create_folder[] = $cp . $p;
                        }
                    }
                    $cp .= $p . '/';
                }
            }
			
            if ( ! empty( $error_create_folder ) )
            {
                $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_create_folder'] . ':</b>';
                $contents .= '<br /> ' . implode( "<br />", $error_create_folder );
                if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                {
                    $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                }
            }
            else
            {
                //Move file";
                $error_move_folder = array();
                foreach ( $move_files as $file_i )
                {
                    if ( is_file( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i ) )
                    {
                        $file_p = nv_site_path_update( $file_i );
                        if ( file_exists( NV_ROOTDIR . '/' . $file_p ) )
                        {
                            if ( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, $file_p ) ) )
                            {
                                nv_deletefile( NV_ROOTDIR . '/' . $file_p );
                            }
                        }
                        if ( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/new/' . $file_i, $file_p ) ) )
                        {
                            @rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i, NV_ROOTDIR . '/' . $file_p );
                        }
                        if ( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i ) )
                        {
                            $error_move_folder[] = $file_i;
                        }
                    }
                }
                if ( ! empty( $error_move_folder ) )
                {
                    $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_move_file'] . ':</b>';
                    $contents .= '<br /> ' . implode( "<br />", $error_move_folder );
                    if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                    {
                        $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                    }
                }
                else
                {
                    global $error_contents;
                    $error_contents = array();
                    
                    $update_data = true;
                    if ( nv_function_exists( 'nv_update_module_music' ) )
                    {
                        $update_data = nv_update_module_music();
                    }
                    if ( $update_data )
                    {
                        if ( isset( $update_info['revision']['to'] ) )
                        {
                            $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_setting` SET `value` ='" . $update_info['revision']['to'] . "' WHERE `key` = 'revision'" );
                        }

                        $del = nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
						
                        nv_del_moduleCache( $module_name );
                        foreach ( $delete_files as $file_i )
                        {
                            $file_p = nv_site_path_update( $file_i );
                            if ( file_exists( NV_ROOTDIR . '/' . $file_p ) )
                            {
                                nv_deletefile( NV_ROOTDIR . '/' . $file_p );
                            }
                        }
                        
                        if ( $del[0] == 1 )
                        {
                            $msg = $lang_module['autoupdate_complete'];
                        }
                        else
                        {
                            $msg = $lang_module['autoupdate_complete_error_del_file'];
                        }
                        $contents .= '<br /><br /><b>' . $msg . '</b>';
                    }
                    else
                    {
                        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_complete_file'] . '.</b>';
                        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_data'] . ':</b><br />' . implode( "<br />", $error_contents );
                    }
                }
            }
        }
    }
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $step > 2 )
{
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo '<div style="text-align:center;color:red">' . $lang_module['autoupdate_error_dir_update'] . '</div>';
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
    exit();
}

?>