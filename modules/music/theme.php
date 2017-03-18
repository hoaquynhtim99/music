<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

/**
 * nv_theme_gird_albums()
 * 
 * @param mixed $array
 * @return
 */
function nv_theme_gird_albums($array)
{
    global $module_file, $lang_module, $lang_global, $module_info, $global_array_config, $module_upload;

    $xtpl = new XTemplate('gird_albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array as $row) {
        $row['resource_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['resource_avatar'];
        
        $xtpl->assign('ROW', $row);
        
        $num_singers = sizeof($row['singers']);
        if ($num_singers > $global_array_config['limit_singers_displayed']) {
            $xtpl->assign('VA_SINGERS', $global_array_config['various_artists']);
            
            foreach ($row['singers'] as $singer) {
                $xtpl->assign('SINGER', $singer);
                $xtpl->parse('main.loop.va_singer.loop');
            }
            
            $xtpl->parse('main.loop.va_singer');
        } elseif (!empty($row['singers'])) {
            $i = 0;
            foreach ($row['singers'] as $singer) {
                $i++;
                $xtpl->assign('SINGER', $singer);
                
                if ($i > 1) {
                    $xtpl->parse('main.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.loop.show_singer.loop');
            }
            $xtpl->parse('main.loop.show_singer');
        } else {
            $xtpl->assign('UNKNOW_SINGER', $global_array_config['unknow_singer']);
            $xtpl->parse('main.loop.no_singer');
        }
        
        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_main()
 * 
 * @param mixed $content_albums
 * @param mixed $content_videos
 * @param mixed $content_singers
 * @param mixed $content_songs
 * @return
 */
function nv_theme_main($content_albums, $content_videos, $content_singers, $content_songs)
{
    global $module_file, $lang_module, $lang_global, $module_info, $global_array_config;
    
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    
    $contents = array();
    
    if (!empty($content_albums)) {
        $xtpl->assign('ALBUMS_LINK', NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums']);
        $xtpl->assign('ALBUMS_HTML', nv_theme_gird_albums($content_albums));
        $xtpl->parse('albums');
        $contents[$global_array_config['home_albums_weight']] = $xtpl->text('albums');
    }
    
    if (!empty($content_singers)) {
        $xtpl->parse('singers');
        $contents[$global_array_config['home_singers_weight']] = $xtpl->text('singers');
    }
    
    if (!empty($content_songs)) {
        $xtpl->parse('songs');
        $contents[$global_array_config['home_songs_weight']] = $xtpl->text('songs');
    }
    
    if (!empty($content_videos)) {
        $xtpl->parse('videos');
        $contents[$global_array_config['home_videos_weight']] = $xtpl->text('videos');
    }
    
    // Sắp xếp lại theo thứ tự cấu hình
    ksort($contents);
    return implode("\n", $contents);
}

function nv_theme_list_albums($array, $is_detail_cat = false, $generate_page)
{
    global $module_file, $lang_module, $lang_global, $module_info, $global_array_config;
    
    $xtpl = new XTemplate('list-albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array as $cat) {
        $xtpl->assign('CAT', $cat['cat']);
        $xtpl->assign('ALBUMS_HTML', nv_theme_gird_albums($cat['albums']));
        
        if (empty($is_detail_cat)) {
            $xtpl->assign('CAT_LINK', NV_MOD_FULLLINK_AMP . $module_info['alias']['list-albums'] . '/' . $cat['cat']['cat_alias'] . '-' . $global_array_config['code_prefix']['cat'] . $cat['cat']['cat_code']);
            $xtpl->parse('main.loopcat.cat_link');
        } else {
            $xtpl->parse('main.loopcat.cat_text');
        }
        
        $xtpl->parse('main.loopcat');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
