<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;
use NukeViet\Music\Resources;
use NukeViet\Music\Utils;

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
$xtpl->assign('UNIQUEID', nv_genpass(6));

// Dữ liệu tìm kiếm
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'post,get', ''); // Từ khóa
$array_search['cat_id'] = $nv_Request->get_int('cat_id', 'post,get', 0); // Thể loại
$array_search['submit'] = $nv_Request->get_int('submit', 'post,get', 0); // Submit form
$array_search['video_id_selected'] = Utils::arrayIntFromStrList($nv_Request->get_title('video_id_selected', 'post,get', '')); // Các video đã chọn

if ($array_search['submit']) {
    $base_url = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;submit=1';
    $per_page = 5;
    $page = Utils::getValidPage($nv_Request->get_int('page', 'get,post', 1), $per_page);

    $db->sqlreset()->from(Resources::getTablePrefix() . "_videos");

    $where = [];

    // Tìm theo từ khóa
    if (!empty($array_search['q'])) {
        $dblike = $db->dblikeescape($array_search['q']);
        $dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
        $where[] = "(
            " . NV_LANG_DATA . "_video_name LIKE '%" . $dblike . "%' OR
            " . NV_LANG_DATA . "_video_searchkey LIKE '%" . $dblikekey . "%' OR
            " . NV_LANG_DATA . "_video_introtext LIKE '%" . $dblike . "%' OR
            " . NV_LANG_DATA . "_video_keywords LIKE '%" . $dblike . "%'
        )";
        $base_url .= '&amp;q=' . urlencode($array_search['q']);
    }

    // Tìm theo thể loại
    if (isset($global_array_cat[$array_search['cat_id']])) {
        $where[] = "FIND_IN_SET(" . $array_search['cat_id'] . ", cat_ids)";
        $base_url .= '&amp;cat_id=' . $array_search['cat_id'];
    }

    // Loại trừ đi các video đã chọn
    if (!empty($array_search['video_id_selected'])) {
        $base_url .= '&amp;video_id_selected=' . implode(',', $array_search['video_id_selected']);
    }

    if (!empty($where)) {
        $db->where(implode(' AND ', $where));
    }

    $db->select("COUNT(*)");
    $all_pages = $db->query($db->sql())->fetchColumn();

    $db->order("video_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);

    $array_select_fields = nv_get_video_select_fields(true);
    $db->select(implode(', ', $array_select_fields[0]));

    $result = $db->query($db->sql());
    $array = $array_singer_ids = [];
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['authors'] = [];
        $row['author_ids'] = explode(',', $row['author_ids']);
        $row['singers'] = [];
        $row['singer_ids'] = explode(',', $row['singer_ids']);
        $row['cats'] = [];
        $row['cat_ids'] = explode(',', $row['cat_ids']);
        $row['video_link'] = '';
        $row['resource_mode'] = 'video';

        if (!empty($row['author_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['author_ids']);
        }
        if (!empty($row['singer_ids'])) {
            $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
        }

        $array[$row['video_id']] = $row;
    }

    // Xác định ca sĩ, chủ đề, đường dẫn video
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
        if (!empty($row['author_ids'])) {
            foreach ($row['author_ids'] as $author_id) {
                if (isset($array_singers[$author_id])) {
                    $row['authors'][$author_id] = $array_singers[$author_id];
                }
            }
        }
        foreach ($row['cat_ids'] as $cid) {
            if (isset($global_array_cat[$cid])) {
                $row['cats'][$cid] = $global_array_cat[$cid];
            }
        }
        $row['video_link'] = nv_get_detail_video_link($row, $row['singers']);
        $array[$id] = $row;
    }

    // Xuất các video ra
    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);

        // Ca sĩ
        $num_singers = sizeof($row['singers']);
        $array_singer_name = [];
        foreach ($row['singers'] as $singer) {
            $array_singer_name[] = $singer['artist_name'];
        }
        $array_singer_name = implode(', ', $array_singer_name);

        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('VA_SINGERS', Config::getVariousArtists());
            $xtpl->assign('VA_SINGERS_TITLE', $array_singer_name);
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

        // Ca sĩ dạng chuỗi
        if (!empty($array_singer_name)) {
            $xtpl->assign('ROW_SINGER', $array_singer_name);
        } else {
            $xtpl->assign('ROW_SINGER', Config::getUnknowSinger());
        }

        if (in_array($row['video_id'], $array_search['video_id_selected'])) {
            $xtpl->assign('ROW_SELECT1', 'true');
            $xtpl->assign('ROW_SELECT2', $nv_Lang->getModule('selected'));
        } else {
            $xtpl->assign('ROW_SELECT1', 'false');
            $xtpl->assign('ROW_SELECT2', $nv_Lang->getModule('select'));
        }

        $xtpl->parse('main.loop');
    }

    // Phân trang
    $generate_page = nv_generate_page($base_url, $all_pages, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
