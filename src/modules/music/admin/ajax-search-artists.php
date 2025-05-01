<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Utils;

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);

// Dữ liệu tìm kiếm
$array_search = [];
$array_search['mode'] = $nv_Request->get_title('mode', 'post,get', ''); // Loại: singer hoặc author
$array_search['q'] = $nv_Request->get_title('q', 'post,get', ''); // Từ khóa
$array_search['nation_id'] = $nv_Request->get_int('nation_id', 'post,get', 0); // Quốc gia
$array_search['submit'] = $nv_Request->get_int('submit', 'post,get', 0); // Submit form
$array_search['artist_id_selected'] = Utils::arrayIntFromStrList($nv_Request->get_title('artist_id_selected', 'post,get', '')); // Các nghệ sĩ đã chọn

if ($array_search['submit']) {
    $base_url = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;submit=1';
    $per_page = 5;
    $page = Utils::getValidPage($nv_Request->get_int('page', 'get,post', 1), $per_page);

    $db->sqlreset()->from(Resources::getTablePrefix() . "_artists");

    $where = [];

    // Tìm theo từ khóa
    if (!empty($array_search['q'])) {
        $dblike = $db->dblikeescape($array_search['q']);
        $dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
        $where[] = "(
            " . NV_LANG_DATA . "_artist_name LIKE '%" . $dblike . "%' OR
            " . NV_LANG_DATA . "_artist_searchkey LIKE '%" . $dblikekey . "%'
        )";
        $base_url .= '&amp;q=' . urlencode($array_search['q']);
    }

    // Tìm nghệ sĩ theo quốc gia
    if (isset($global_array_nation[$array_search['nation_id']])) {
        $where[] = 'nation_id=' . $array_search['nation_id'];
        $base_url .= '&amp;nation_id=' . $array_search['nation_id'];
    }

    // Loại trừ đi các nghệ sĩ đã chọn
    if (!empty($array_search['artist_id_selected'])) {
        $base_url .= '&amp;artist_id_selected=' . implode(',', $array_search['artist_id_selected']);
    }

    // Tìm theo loại ca sĩ hay nhạc sĩ
    if ($array_search['mode'] == 'singer') {
        $where[] = 'artist_type IN(0,2)';
        $base_url .= '&amp;mode=singer';
    } else {
        $where[] = 'artist_type IN(1,2)';
        $base_url .= '&amp;mode=author';
    }

    if (!empty($where)) {
        $db->where(implode(' AND ', $where));
    }

    $db->select("COUNT(*)");
    $all_pages = $db->query($db->sql())->fetchColumn();

    $db->order("artist_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);

    $array_select_fields = nv_get_artist_select_fields(true);
    $db->select(implode(', ', $array_select_fields[0]));

    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }

        $row['artist_link'] = nv_get_view_singer_link($row);
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'singer', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'singer');
        $row['real_artist_type'] = isset($global_array_artist_type[$row['artist_type']]) ? $global_array_artist_type[$row['artist_type']] : 'N/A';

        $xtpl->assign('ROW', $row);

        if (in_array($row['artist_id'], $array_search['artist_id_selected'])) {
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
