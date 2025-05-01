<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Shared\Charts;

if (!nv_function_exists('nv_block_chart_songs')) {
    /**
     * nv_block_config_chart_songs()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @return
     */
    function nv_block_config_chart_songs($module, $data_block)
    {
        global $site_mods, $nv_Lang;

        $module_file = $site_mods[$module]['module_file'];
        $module_data = $site_mods[$module]['module_data'];
        $module_name = $module;

        require NV_ROOTDIR . '/modules/' . $module_file . '/init.php';

        if (!is_array($data_block['catids'])) {
            $data_block['catids'] = [];
        }

        $html = '';

        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('catids') . ':</label>';
        $html .= '<div class="col-sm-9">';

        foreach ($global_array_cat_chart as $cat_chart) {
            $checked = in_array($cat_chart['cat_id'], $data_block['catids']) ? ' checked="checked"' : '';
            $html .= '<div class="checkbox"><label><input type="checkbox" name="config_catids[]" value="' . $cat_chart['cat_id'] . '"' . $checked . '> ' . $cat_chart['cat_name'] . '</label></div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('numrows') . ':</label>';
        $html .= '<div class="col-sm-5"><input type="text" class="form-control" name="config_numrows" value="' . $data_block['numrows'] . '"/></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_chart_songs_submit()
     *
     * @param mixed $module
     * @return
     */
    function nv_block_config_chart_songs_submit($module)
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
        global $site_mods, $global_config, $nv_Cache, $db, $module_name, $client_info, $nv_Request;

        $module = $block_config['module'];

        if (!isset($site_mods[$module]) or empty($block_config['catids']) or empty($block_config['numrows'])) {
            return  '';
        }

        $module_theme = $site_mods[$module]['module_theme'];
        $call_jscss = false;

        if ($module_name == $module) {
            // Block hiển thị ở module music thì gọi các biến cần thiết
            global $global_array_cat_chart, $nv_Lang;
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

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_theme . '/block.chart_songs.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }
        $xtpl = new XTemplate('block.chart_songs.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_theme);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('MODULE_THEME', $module_theme);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('CONFIG', $block_config);
        $xtpl->assign('AJAX_URL', $client_info['selfurl']);
        $xtpl->assign('UNIQUEID', nv_genpass(6));

        // HTML trả về của block
        $contents = '';

        // Gọi CSS khi ở các module khác
        if ($call_jscss) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/css/' . $module_theme . '.css')) {
                $css_theme = $global_config['module_theme'];
            } else {
                $css_theme = 'default';
            }
            $xtpl->assign('TEMPLATE_CSS', $css_theme);
            $xtpl->parse('css');
            $contents .= $xtpl->text('css');
        }

        $cacheFile = NV_LANG_DATA . '_bchartsongs_' . $block_config['bid'] . '_' . implode('_', $block_config['catids']) . '_' . NV_CACHE_PREFIX . '.cache';
        $cacheTTL = 600; // Cache 10 phút
        $id_ajax = 0;
        if ($nv_Request->isset_request('getBlockChartSongTab', 'post')) {
            $cat_ajax = $nv_Request->get_title('cat_code', 'post', '');
            foreach ($block_config['catids'] as $cat_id) {
                if (isset($global_array_cat_chart[$cat_id]) and $global_array_cat_chart[$cat_id]['cat_code'] == $cat_ajax) {
                    $id_ajax = $cat_id;
                }
            }
        }

        if (!defined('NV_IS_ADMIN') and !$id_ajax and ($cache = $nv_Cache->getItem($module, $cacheFile, $cacheTTL)) != false) {
            // Load hết HTML của block từ cache khi không phải admin, không ajax
            $contents .= $cache;
        } else {
            if ($id_ajax) {
                // Ajax load thì chỉ lấy danh mục đang load khỏi loop nhiều
                $global_array_cat_chart = [$id_ajax => $global_array_cat_chart[$id_ajax]];
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
                            $xtpl->parse('main.chart_content.chart_empty');
                        } else {
                            /*
                             * Lấy BXH của những bài hát này tuần trước
                             * để đối chứng tăng, giảm bao nhiêu bậc
                             */
                            $chart_before = Charts::getCurrentTime() - (7 * 86400);
                            $sql = "SELECT object_id, summary_order FROM " . Resources::getTablePrefix() . "_charts
                            WHERE chart_time=" . $chart_before . " AND cat_id=" . $cat_chart['cat_id'] . " AND object_name='song' AND object_id IN(" . implode(',', array_keys($array_songs)) . ")";

                            $data_chart_before = [];
                            $result = $db->query($sql);
                            while ($row = $result->fetch()) {
                                $data_chart_before[$row['object_id']] = $row['summary_order'];
                            }

                            // Xác định ca sĩ
                            $array_singers = nv_get_artists($array_singer_ids);

                            foreach ($array_songs as $id => $row) {
                                if (!empty($row['singer_ids'])) {
                                    foreach ($row['singer_ids'] as $singer_id) {
                                        if (isset($array_singers[$singer_id])) {
                                            $row['singers'][$singer_id] = $array_singers[$singer_id];
                                            if (empty($row['resource_avatar']) and !empty($array_singers[$singer_id]['resource_avatar'])) {
                                                $row['resource_avatar'] = $array_singers[$singer_id]['resource_avatar'];
                                                $row['resource_mode'] = 'singer';
                                            }
                                        }
                                    }
                                }
                                $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);
                                $row['song_link_full'] = NV_MY_DOMAIN . nv_url_rewrite($row['song_link'], true);
                                $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode'], true);
                                $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode']);
                                $row['chart_order_show'] = str_pad($row['chart_order'], 2, '0', STR_PAD_LEFT);

                                $xtpl->assign('ROW', $row);

                                // Xuất ảnh lớn cho bài hát đầu tiên
                                if ($row['chart_order'] == 1) {
                                    $xtpl->parse('main.chart_content.chart_data.loop.image');
                                }

                                // Xử lý ca sĩ của bài hát
                                $num_singers = sizeof($row['singers']);

                                if ($num_singers > Config::getLimitSingersDisplayed()) {
                                    $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

                                    foreach ($row['singers'] as $singer) {
                                        $xtpl->assign('SINGER', $singer);
                                        $xtpl->parse('main.chart_content.chart_data.loop.va_singer.loop');
                                    }

                                    $xtpl->parse('main.chart_content.chart_data.loop.va_singer');
                                } elseif (!empty($row['singers'])) {
                                    $i = 0;
                                    foreach ($row['singers'] as $singer) {
                                        $i++;
                                        $xtpl->assign('SINGER', $singer);

                                        if ($i > 1) {
                                            $xtpl->parse('main.chart_content.chart_data.loop.show_singer.loop.separate');
                                        }
                                        $xtpl->parse('main.chart_content.chart_data.loop.show_singer.loop');
                                    }
                                    $xtpl->parse('main.chart_content.chart_data.loop.show_singer');
                                } else {
                                    $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
                                    $xtpl->parse('main.chart_content.chart_data.loop.no_singer');
                                }

                                // Xử lý tăng giảm thứ hạng
                                $order_offset_value = isset($data_chart_before[$row['song_id']]) ? ($data_chart_before[$row['song_id']] - $row['chart_order']) : 0;
                                if ($order_offset_value > 0) {
                                    // Tăng
                                    $xtpl->parse('main.chart_content.chart_data.loop.order_desc');
                                } elseif ($order_offset_value < 0) {
                                    // Giảm
                                    $xtpl->parse('main.chart_content.chart_data.loop.order_asc');
                                } else {
                                    // Không xác định hoặc giữ nguyên
                                    $xtpl->parse('main.chart_content.chart_data.loop.order_no');
                                }
                                if ($order_offset_value != 0) {
                                    $xtpl->assign('ORDER_NUM', abs($order_offset_value));
                                    $xtpl->parse('main.chart_content.chart_data.loop.order_num');
                                }

                                $xtpl->parse('main.chart_content.chart_data.loop');
                            }

                            $xtpl->parse('main.chart_content.chart_data');
                        }

                        $xtpl->parse('main.chart_content');

                        if ($id_ajax) {
                            // Break tại đây khi load ajax
                            include NV_ROOTDIR . '/includes/header.php';
                            echo $xtpl->text('main.chart_content');
                            include NV_ROOTDIR . '/includes/footer.php';
                        }
                    }

                    $xtpl->parse('main.cat_title');
                }
            }

            if ($number_tabs <= 0) {
                return '';
            }

            $xtpl->parse('main');
            $cache = $xtpl->text('main');
            $contents .= $cache;

            // Cache lại khi không là admin
            if (!defined('NV_IS_ADMIN')) {
                $nv_Cache->setItem($module, $cacheFile, $cache, $cacheTTL);
            }
        }

        return $contents;
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;
    if (isset($site_mods[$block_config['module']])) {
        $content = nv_block_chart_songs($block_config);
    }
}
