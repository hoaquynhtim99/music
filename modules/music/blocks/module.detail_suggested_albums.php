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

use NukeViet\Music\Config;
use NukeViet\Music\Resources;

if (!nv_function_exists('nv_block_detail_suggested_albums')) {
    /**
     * nv_block_config_detail_suggested_albums()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_detail_suggested_albums($module, $data_block, $lang_block)
    {
        $html = '<div class="form-group">';
        $html .= '  <div class="col-sm-18 col-sm-offset-6">';
        $html .= '    <span class="text-info">' . $lang_block['blocknote'] . '</span>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['numrows'] . ':</label>';
        $html .= '  <div class="col-sm-9">';
        $html .= '    <input type="number" name="config_numrows" value="' . $data_block['numrows'] . '" class="form-control">';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['showtypecat'] . ':</label>';
        $html .= '  <div class="col-sm-9">';
        $html .= '    <select name="config_showtypecat" class="form-control">';

        for ($i = 0; $i <= 1; $i++) {
            $html .= '    <option value="' . $i . '"' . ($i == $data_block['showtypecat'] ? ' selected="selected"' : '') . '>' . $lang_block['showtypecat' . $i] . '</option>';
        }

        $html .= '    </select>';
        $html .= '  </div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '  <label class="control-label col-sm-6">' . $lang_block['showtypesinger'] . ':</label>';
        $html .= '  <div class="col-sm-9">';
        $html .= '    <select name="config_showtypesinger" class="form-control">';

        for ($i = 0; $i <= 1; $i++) {
            $html .= '    <option value="' . $i . '"' . ($i == $data_block['showtypesinger'] ? ' selected="selected"' : '') . '>' . $lang_block['showtypesinger' . $i] . '</option>';
        }

        $html .= '    </select>';
        $html .= '  </div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_detail_suggested_albums_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_detail_suggested_albums_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['numrows'] = $nv_Request->get_int('config_numrows', 'post', 0);
        $return['config']['showtypecat'] = $nv_Request->get_int('config_showtypecat', 'post', 0);
        $return['config']['showtypesinger'] = $nv_Request->get_int('config_showtypesinger', 'post', 0);

        if ($return['config']['numrows'] < 1 or $return['config']['numrows'] > 100) {
            $return['config']['numrows'] = 15;
        }
        if ($return['config']['showtypecat'] < 0 or $return['config']['showtypecat'] > 1) {
            $return['config']['showtypecat'] = 0;
        }
        if ($return['config']['showtypesinger'] < 0 or $return['config']['showtypesinger'] > 1) {
            $return['config']['showtypesinger'] = 0;
        }

        return $return;
    }

    /**
     * nv_block_detail_suggested_albums()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_detail_suggested_albums($block_config)
    {
        global $op;

        if ($op != 'detail-album') {
            return '';
        }

        global $module_info, $lang_module, $lang_global, $db, $ms_detail_data;

        $xtpl = new XTemplate('block_detail_suggested_albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);
        $xtpl->assign('UNIQUEID', nv_genpass(6));

        // Lấy các album cùng thể loại mà bài đang nghe không có thể loại nào thì kết thúc
        if (!empty($block_config['showtypecat']) and empty($ms_detail_data['cat_ids'])) {
            return '';
        }
        // Lấy các album cùng ca sĩ mà bài đang nghe không có ca sĩ nào thì kết thúc
        if (!empty($block_config['showtypesinger']) and empty($ms_detail_data['singer_ids'])) {
            return '';
        }

        if (empty($block_config['showtypesinger'])) {
            // Random các album với hiệu suất cao
            $where = [];
            $where[] = "album_id!=" . $ms_detail_data['album_id'];
            if (!empty($block_config['showtypecat'])) {
                reset($ms_detail_data['cat_ids']);
                $where[] = "cat_id=" . current($ms_detail_data['cat_ids']);
            }

            $db->sqlreset()->select('album_id')->from(Resources::getTablePrefix() . "_albums_random");
            $db->where(implode(' AND ', $where));
            $db->order('RAND()');
            $db->limit($block_config['numrows']);

            $array_albumids = $db->query($db->sql())->fetchAll(PDO::FETCH_COLUMN);
            if (empty($array_albumids)) {
                return '';
            }

            $db->sqlreset()->from(Resources::getTablePrefix() . "_albums");
            $db->where("album_id IN(" . implode(',', $array_albumids) . ")");
        } else {
            // Random các album trên toàn bộ dữ liệu, hiệu suất thấp
            $where = [];
            $where[] = "album_id!=" . $ms_detail_data['album_id'];
            $where[] = "status=1";
            $where[] = "is_official=1";

            // Cùng thể loại
            if (!empty($block_config['showtypecat'])) {
                reset($ms_detail_data['cat_ids']);
                $where[] = "FIND_IN_SET(" . current($ms_detail_data['cat_ids']) . ", cat_ids)";
            }

            // Cùng ca sĩ
            if (!empty($block_config['showtypesinger'])) {
                reset($ms_detail_data['singer_ids']);
                $where[] = "FIND_IN_SET(" . current($ms_detail_data['singer_ids']) . ", singer_ids)";
            }

            $db->sqlreset()->from(Resources::getTablePrefix() . "_albums");
            $db->where(implode(' AND ', $where));
            $db->order('RAND()');
            $db->limit($block_config['numrows']);
        }

        $array_select_fields = nv_get_album_select_fields();
        $db->select(implode(', ', $array_select_fields[0]));

        $array = $array_singer_ids = $array_singers = [];
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);
            $row['album_link'] = '';
            $row['resource_mode'] = 'album';

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            $array[$row['album_id']] = $row;
        }

        // Xác định ca sĩ
        $array_singers = nv_get_artists($array_singer_ids);

        foreach ($array as $id => $row) {
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
            $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);

            $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode'], true);
            $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode']);

            $xtpl->assign('ROW', $row);

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

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
                $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
                $xtpl->parse('main.loop.no_singer');
            }

            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $module = $block_config['module'];
    $content = nv_block_detail_suggested_albums($block_config);
}
