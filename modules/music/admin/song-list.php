<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['song_list'];

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $nation_ids = $nv_Request->get_title('id', 'post', '');
    $nation_ids = array_filter(array_unique(array_map('intval', explode(',', $nation_ids))));
    if (empty($nation_ids)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }
    foreach ($nation_ids as $nation_id) {
        if (!isset($global_array_nation[$nation_id])) {
            $ajaxRespon->setMessage('Wrong ID!!!')->respon();
        }
    }

    foreach ($nation_ids as $nation_id) {
        // Xóa
        $sql = "DELETE FROM " . NV_MOD_TABLE . "_nations WHERE nation_id=" . $nation_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_NATION', $nation_id . ':' . $global_array_nation[$nation_id]['nation_name'], $admin_info['userid']);
    }

    // Cập nhật lại thứ tự
    $sql = "SELECT nation_id FROM " . NV_MOD_TABLE . "_nations ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . NV_MOD_TABLE . "_nations SET weight=" . $weight . " WHERE nation_id=" . $row['nation_id'];
        $db->query($sql);
    }

    $nv_Cache->delMod($module_name);

    $ajaxRespon->setSuccess()->respon();
}

// Cho hoạt động/đình chỉ
if ($ajaction == 'active' or $ajaction == 'deactive') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $song_ids = $nv_Request->get_title('id', 'post', '');
    $song_ids = array_filter(array_unique(array_map('intval', explode(',', $song_ids))));
    if (empty($song_ids)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    // Xác định các bài hát
    $array_select_fields = nv_get_song_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE song_id IN(" . implode(',', $song_ids) . ")";
    $result = $db->query($sql);

    $array = array();
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        $array[$row['song_id']] = $row;
    }
    if (sizeof($array) != sizeof($song_ids)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($song_ids as $song_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET status=" . $status . " WHERE song_id=" . $song_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_SONG', $song_id . ':' . $array[$song_id]['song_name'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    $ajaxRespon->setSuccess()->respon();
}

$base_url = NV_ADMIN_MOD_FULLLINK_AMP . $op;
$per_page = 20;
$page = msGetValidPage($nv_Request->get_int('page', 'get', 1), $per_page);

// Dữ liệu tìm kiếm
$array_search = array();
$array_search['q'] = $nv_Request->get_title('q', 'get', ''); // Từ khóa
$array_search['c'] = $nv_Request->get_int('c', 'get', 0); // Thể loại
$array_search['f'] = $nv_Request->get_title('f', 'get', ''); // Từ
$array_search['t'] = $nv_Request->get_title('t', 'get', ''); // Đến

$db->sqlreset()->from(NV_MOD_TABLE . "_songs");

$where = array();
if (!empty($array_search['q'])) {
    $dblike = $db->dblikeescape($array_search['q']);
    $dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
    $where[] = "(
        " . NV_LANG_DATA . "_song_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_song_searchkey LIKE '%" . $dblikekey . "%' OR
        " . NV_LANG_DATA . "_song_introtext LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_song_keywords LIKE '%" . $dblike . "%'
    )";
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
}
if (!empty($array_search['c'])) {
    $where[] = "FIND_IN_SET(" . $array_search['c'] . ", cat_ids)";
    $base_url .= '&amp;c=' . $array_search['c'];
}
if (!empty($array_search['f'])) {
    $base_url .= '&amp;f=' . urlencode($array_search['f']);
    $stime = 0;
    if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $array_search['f'], $m)) {
        $stime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    }
    if ($stime > 0) {
        $where[] = "time_add>=" . $stime;
    }
}
if (!empty($array_search['t'])) {
    $base_url .= '&amp;t=' . urlencode($array_search['t']);
    $stime = 0;
    if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $array_search['t'], $m)) {
        $stime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    }
    if ($stime > 0) {
        $where[] = "time_add<=" . ($stime + 86399);
    }
}
if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$db->select("COUNT(*)");
$all_pages = $db->query($db->sql())->fetchColumn();

$db->order("song_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);

$array_select_fields = nv_get_song_select_fields(true);
$db->select(implode(', ', $array_select_fields[0]));

$result = $db->query($db->sql());
$array = $array_singer_ids = array();
while ($row = $result->fetch()) {
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    $row['authors'] = array();
    $row['author_ids'] = explode(',', $row['author_ids']);
    $row['singers'] = array();
    $row['singer_ids'] = explode(',', $row['singer_ids']);
    $row['cats'] = array();
    $row['cat_ids'] = explode(',', $row['cat_ids']);
    $row['song_link'] = '';
    $row['resource_mode'] = 'song';

    if (!empty($row['author_ids'])) {
        $array_singer_ids = array_merge_recursive($array_singer_ids, $row['author_ids']);
    }
    if (!empty($row['singer_ids'])) {
        $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
    }

    $array[$row['song_id']] = $row;
}

// Xác định ca sĩ, chủ đề, đường dẫn bài hát
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
    $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);
    $array[$id] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_nation));
$xtpl->assign('UNIQUEID', nv_genpass(6));
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);

// Xuất ra trình duyệt
foreach ($array as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = msFormatDateViews($row['time_add']);
    $row['time_update'] = $row['time_update'] ? msFormatDateViews($row['time_update']) : '';
    $row['stat_views'] = msFormatNumberViews($row['stat_views']);
    $row['stat_comments'] = msFormatNumberViews($row['stat_comments']);
    $row['state'] = $lang_module['song_status_' . $row['status']];

    $xtpl->assign('ROW', $row);

    // Ca sĩ
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

    // Xuất nhạc sĩ
    $num_authors = sizeof($row['authors']);
    if ($num_authors > $global_array_config['limit_authors_displayed']) {
        $xtpl->assign('VA_AUTHORS', $global_array_config['various_artists_authors']);

        foreach ($row['authors'] as $author) {
            $xtpl->assign('AUTHOR', $author);
            $xtpl->parse('main.loop.va_author.loop');
        }

        $xtpl->parse('main.loop.va_author');
    } elseif (!empty($row['authors'])) {
        $i = 0;
        foreach ($row['authors'] as $author) {
            $i++;
            $xtpl->assign('AUTHOR', $author);

            if ($i > 1) {
                $xtpl->parse('main.loop.show_author.loop.separate');
            }
            $xtpl->parse('main.loop.show_author.loop');
        }
        $xtpl->parse('main.loop.show_author');
    } else {
        $xtpl->assign('UNKNOW_AUTHOR', $global_array_config['unknow_author']);
        $xtpl->parse('main.loop.no_author');
    }

    // Xuất thể loại
    $num_cats = sizeof($row['cats']);
    if ($num_cats > 0) {
        $i = 0;
        foreach ($row['cats'] as $cat) {
            $i++;
            $xtpl->assign('CAT', $cat);

            if ($i > 1) {
                $xtpl->parse('main.loop.show_cat.loop.separate');
            }
            $xtpl->parse('main.loop.show_cat.loop');
        }
        $xtpl->parse('main.loop.show_cat');
    } else {
        $xtpl->assign('UNKNOW_CAT', $global_array_config['unknow_cat']);
        $xtpl->parse('main.loop.no_cat');
    }

    if (empty($row['status'])) {
        $xtpl->assign('ACTION_STATUS', 'active');
        $xtpl->assign('LANG_STATUS', $lang_module['action_active']);
    } else {
        $xtpl->assign('ACTION_STATUS', 'deactive');
        $xtpl->assign('LANG_STATUS', $lang_module['action_deactive']);
    }

    $xtpl->parse('main.loop');
}

// Xuất thể loại
foreach ($global_array_cat as $cat) {
    $cat['selected'] = $cat['cat_id'] == $array_search['c'] ? ' selected="selected"' : '';
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

// Phân trang
$generate_page = nv_generate_page($base_url, $all_pages, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
