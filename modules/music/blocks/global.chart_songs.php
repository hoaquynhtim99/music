<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

use NukeViet\Music\Resources;

if (!nv_function_exists('nv_block_chart_songs')) {
    /**
     * nv_block_config_chart_songs()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_chart_songs($module, $data_block, $lang_block)
    {
        global $site_mods;

        $module_file = $site_mods[$module]['module_file'];
        $module_data = $site_mods[$module]['module_data'];
        $module_name = $module;

        require NV_ROOTDIR . '/modules/' . $module_file . '/init.php';

        if (!is_array($data_block['catids'])) {
            $data_block['catids'] = [];
        }

        $html = '';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['catids'] . ':</label>';
        $html .= '<div class="col-sm-18">';

        foreach ($global_array_cat_chart as $cat_chart) {
            $checked = in_array($cat_chart['cat_id'], $data_block['catids']) ? ' checked="checked"' : '';
            $html .= '<div class="checkbox"><label><input type="checkbox" name="config_catids[]" value="' . $cat_chart['cat_id'] . '"' . $checked . '> ' . $cat_chart['cat_name'] . '</label></div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numrows'] . ':</label>';
        $html .= '<div class="col-sm-9"><input type="text" class="form-control" name="config_numrows" value="' . $data_block['numrows'] . '"/></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_chart_songs_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_chart_songs_submit($module, $lang_block)
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['catids'] = $nv_Request->get_typed_array('config_catids', 'post', 'int', []);
        $return['config']['numrows'] = $nv_Request->get_int('config_numrows', 'post', 0);

        return $return;
    }

    /**
     * nv_block_chart_songs()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_chart_songs($block_config)
    {
        global $site_mods, $global_config, $nv_Cache, $db, $module_name;

        $module = $block_config['module'];

        if (!isset($site_mods[$module]) or empty($block_config['catids']) or empty($block_config['numrows'])) {
            return  '';
        }

        $module_theme = $site_mods[$module]['module_theme'];
        $call_jscss = false;

        if ($module_name == $module) {
            // Block hiển thị ở module music thì gọi các biến cần thiết
            global $global_array_cat_chart, $lang_module;
        } else {
            // Block hiển thị ở vị trí khác thì gọi tài nguyên ra
            global $module_file, $module_data;

            $backup_module_file = $module_file;
            $backup_module_data = $module_data;
            $backup_module_name = $module_name;

            $module_name = $module;
            $module_file = $site_mods[$module]['module_file'];
            $module_data = $site_mods[$module]['module_data'];;

            // Gọi file INIT
            require NV_ROOTDIR . '/modules/' . $module_file . '/init.php';

            // Ngôn ngữ
            require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';

            // Trả lại các biến để xử lý khi ra ngoài block
            $module_file = $backup_module_file;
            $module_data = $backup_module_data;
            $module_name = $backup_module_name;

            $call_jscss = true;
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_theme . '/block.chart_songs.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }
        $xtpl = new XTemplate('block.chart_songs.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_theme);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('MODULE_THEME', $module_theme);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('CONFIG', $block_config);

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

        $number_tabs = 0;

        foreach ($global_array_cat_chart as $cat_chart) {
            if (in_array($cat_chart['cat_id'], $block_config['catids'])) {
                $number_tabs++;

                $xtpl->assign('CAT', $cat_chart);

                if ($number_tabs == 1) {
                    $xtpl->parse('main.cat_title.active');

                    /*
                     * Tại TAB đầu tiên
                     * Lấy BXH hiện tại từ bảng TMP (tuần hiện tại)
                     */
                    $array_select_fields = nv_get_song_select_fields(false, 'tb2');
                    $where = [
                        "tb2.status=1",
                        "tb2.is_official=1",
                        "tb1.object_name='song'",
                        "tb1.cat_id=" . $cat_chart['cat_id']
                    ];
                    $db->sqlreset()->select(implode(', ', $array_select_fields[0]))->from(Resources::getTablePrefix() . "_chart_tmps tb1");
                    $db->join("INNER JOIN " . Resources::getTablePrefix() . "_songs tb2 ON tb1.object_id=tb2.song_id");
                    $db->where(implode(' AND ', $where));
                    $db->order('summary_scores DESC');
                    $db->limit($block_config['numrows']);
                    $sql = $db->sql();

                    $array_songs = $array_singer_ids = $array_singers = [];

                    $result = $db->query($db->sql());
                    $chart_order = 0;
                    while ($row = $result->fetch()) {
                        $chart_order++;
                        foreach ($array_select_fields[1] as $f) {
                            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                                $row[$f] = $row['default_' . $f];
                            }
                            unset($row['default_' . $f]);
                        }
                        $row['chart_order'] = $chart_order;
                        $row['singers'] = [];
                        $row['singer_ids'] = explode(',', $row['singer_ids']);
                        $row['song_link'] = '';
                        $row['song_link_full'] = '';
                        $row['resource_mode'] = !empty($row['resource_avatar']) ? 'song' : 'singer';
                        $row['tokend'] = md5($row['song_code'] . NV_CHECK_SESSION);

                        if (!empty($row['singer_ids'])) {
                            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
                        }

                        $array_songs[$row['song_id']] = $row;
                    }

                    if (empty($array_songs)) {
                        $xtpl->parse('main.chart_empty');
                    } else {
                        // Xác định ca sĩ
                        $array_singers = nv_get_artists($array_singer_ids);

                        // FIXME
                    }
                }
                $xtpl->parse('main.cat_title');
            }
        }

        if ($number_tabs <= 0) {
            return '';
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;
    if (isset($site_mods[$block_config['module']])) {
        $content = nv_block_chart_songs($block_config);
    }
}
