<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

use NukeViet\Music\Resources;

if (!nv_function_exists('nv_block_music_search')) {
    /**
     * nv_block_music_search()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_music_search($block_config)
    {
        global $site_mods, $global_config, $nv_Cache, $db, $module_name, $client_info, $nv_Request;

        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return  '';
        }

        $module_theme = $site_mods[$module]['module_theme'];
        $call_jscss = false;

        if ($module_name == $module) {
            // Block hiển thị ở module music thì gọi các biến cần thiết
            global $lang_module;
        } else {
            // Block hiển thị ở vị trí khác thì gọi tài nguyên ra
            $backup_module_name = $module_name;

            $module_name = $module;

            // Gọi file INIT
            require NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/init.php';

            // Ngôn ngữ
            require NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php';

            // Trả lại các biến để xử lý khi ra ngoài block
            $module_name = $backup_module_name;

            $call_jscss = true;
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_theme . '/block.chart_videos.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }
        $xtpl = new XTemplate('block.search.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_theme);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('MODULE_THEME', $module_theme);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('CONFIG', $block_config);
        $xtpl->assign('MODULE_NAME', $module);

        // Gọi CSS khi ở các module khác
        if ($call_jscss) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/css/' . $module_theme . '.css')) {
                $css_theme = $global_config['module_theme'];
            } else {
                $css_theme = 'default';
            }
            $xtpl->assign('TEMPLATE_CSS', $css_theme);
            $xtpl->parse('main.css');
        }

        if (!$global_config['rewrite_enable']) {
            $form_action = NV_BASE_SITEURL . 'index.php';
            $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
            $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
            $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
            $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
            $xtpl->assign('MODULE_NAME', $module);
            $xtpl->parse('main.no_rewrite');
        } else {
            $form_action = Resources::getModFullLinkEncode() . 'search';
        }
        $xtpl->assign('TOKEND', NV_CHECK_SESSION);
        $xtpl->assign('FORM_ACTION', $form_action);
        $xtpl->assign('Q', $nv_Request->get_title('q', 'get', ''));

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;
    if (isset($site_mods[$block_config['module']])) {
        $content = nv_block_music_search($block_config);
    }
}
