<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Utils;

$page_title = $lang_module['artist_list'];

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $artist_ids = $nv_Request->get_title('id', 'post', '');
    $artist_ids = array_filter(array_unique(array_map('intval', explode(',', $artist_ids))));
    if (empty($artist_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $array_artists = nv_get_artists($artist_ids);
    if (sizeof($array_artists) != sizeof($artist_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    foreach ($artist_ids as $artist_id) {
        // Xóa
        $sql = "DELETE FROM " . NV_MOD_TABLE . "_artists WHERE artist_id=" . $artist_id;
        $db->query($sql);

        // Cập nhật lại quốc gia
        if (!empty($array_artists[$artist_id]['nation_id'])) {
            msUpdateNationStat($array_artists[$artist_id]['nation_id']);
        }

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_ARTIST', $artist_id . ':' . $array_artists[$artist_id]['artist_name'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);

    AjaxRespon::setSuccess()->respon();
}

// Cho hoạt động/đình chỉ
if ($ajaction == 'active' or $ajaction == 'deactive') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $artist_ids = $nv_Request->get_title('id', 'post', '');
    $artist_ids = array_filter(array_unique(array_map('intval', explode(',', $artist_ids))));
    if (empty($artist_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    // Xác định các bài hát
    $array_select_fields = nv_get_artist_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_artists WHERE artist_id IN(" . implode(',', $artist_ids) . ")";
    $result = $db->query($sql);

    $array = array();
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        $array[$row['artist_id']] = $row;
    }
    if (sizeof($array) != sizeof($artist_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($artist_ids as $artist_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . NV_MOD_TABLE . "_artists SET status=" . $status . " WHERE artist_id=" . $artist_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_ARTIST', $artist_id . ':' . $array[$artist_id]['artist_name'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    AjaxRespon::setSuccess()->respon();
}

$base_url = NV_ADMIN_MOD_FULLLINK_AMP . $op;
$per_page = 20;
$page = Utils::getValidPage($nv_Request->get_int('page', 'get', 1), $per_page);

// Dữ liệu tìm kiếm
$array_search = array();
$array_search['q'] = $nv_Request->get_title('q', 'get', ''); // Từ khóa
$array_search['tp'] = $nv_Request->get_int('tp', 'get', -1); // Thể loại
$array_search['f'] = $nv_Request->get_title('f', 'get', ''); // Từ
$array_search['t'] = $nv_Request->get_title('t', 'get', ''); // Đến

$db->sqlreset()->from(NV_MOD_TABLE . "_artists");

$where = array();
if (!empty($array_search['q'])) {
    $dblike = $db->dblikeescape($array_search['q']);
    $dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
    $where[] = "(
        " . NV_LANG_DATA . "_artist_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_artist_searchkey LIKE '%" . $dblikekey . "%'
    )";
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
}
if (isset($global_array_artist_type[$array_search['tp']])) {
    $where[] = "artist_type=" . $array_search['tp'];
    $base_url .= '&amp;tp=' . $array_search['tp'];
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

$db->order("artist_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);

$array_select_fields = nv_get_artist_select_fields(true);
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

    $row['artist_link'] = nv_get_view_singer_link($row);
    $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'singer', true);
    $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'singer');
    $row['real_artist_type'] = isset($global_array_artist_type[$row['artist_type']]) ? $global_array_artist_type[$row['artist_type']] : 'N/A';

    $array[$row['artist_id']] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
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
$xtpl->assign('LINK_ADD', NV_ADMIN_MOD_FULLLINK_AMP . 'artist-content');

// Xuất ra trình duyệt
foreach ($array as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    //$row['stat_views'] = Utils::getFormatNumberView($row['stat_views']);
    $row['state'] = $lang_module['status_' . $row['status']];
    $row['url_edit'] = NV_ADMIN_MOD_FULLLINK_AMP . 'artist-content&amp;artist_id=' . $row['artist_id'];

    $xtpl->assign('ROW', $row);

    if (empty($row['status'])) {
        $xtpl->assign('ACTION_STATUS', 'active');
        $xtpl->assign('LANG_STATUS', $lang_module['action_active']);
    } else {
        $xtpl->assign('ACTION_STATUS', 'deactive');
        $xtpl->assign('LANG_STATUS', $lang_module['action_deactive']);
    }

    $xtpl->parse('main.loop');
}

// Xuất loại
foreach ($global_array_artist_type as $_key => $_val) {
    $artist_type = array(
        'key' => $_key,
        'title' => $_val,
        'selected' => $_key == $array_search['tp'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('ARTIST_TYPE', $artist_type);
    $xtpl->parse('main.artist_type');
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
