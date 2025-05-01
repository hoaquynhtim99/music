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

use NukeViet\Module\music\AjaxRespon;
use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Utils;

$page_title = $nv_Lang->getModule('album_list');

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $album_ids = $nv_Request->get_title('id', 'post', '');
    $album_ids = array_filter(array_unique(array_map('intval', explode(',', $album_ids))));
    if (empty($album_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $array_select_fields = nv_get_album_select_fields();

    foreach ($album_ids as $album_id) {
        $album = $db->query("SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_albums WHERE album_id=" . $album_id)->fetch();
        if (!empty($album)) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($album[$f]) and !empty($album['default_' . $f])) {
                    $album[$f] = $album['default_' . $f];
                }
                unset($album['default_' . $f]);
            }

            // Xóa album
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_albums WHERE album_id=" . $album_id;
            $db->query($sql);

            // Xóa bài hát trong album
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_albums_data WHERE album_id=" . $album_id;
            $db->query($sql);

            // Xóa khỏi danh sách yêu thích của thành viên
            $sql = "DELETE FROM " . Resources::getTablePrefix() . "_user_favorite_albums WHERE album_id=" . $album_id;
            $db->query($sql);

            // Cập nhật lại thống kê thể loại
            $album['cat_ids'] = Utils::arrayIntFromStrList($album['cat_ids']);
            foreach ($album['cat_ids'] as $cat_id) {
                msUpdateCatStat($cat_id);
            }

            // Cập nhật ca sĩ
            $album['singer_ids'] = Utils::arrayIntFromStrList($album['singer_ids']);
            foreach ($album['singer_ids'] as $singer_id) {
                msUpdateArtistStat($singer_id, true);
            }

            // Cập nhật random các album
            msUpdateRandomAlbums($album['cat_ids']);

            // Ghi nhật ký hệ thống
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_ALBUM', $nation_id . ':' . $album['nation_name'], $admin_info['userid']);
        }
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

    $album_ids = $nv_Request->get_title('id', 'post', '');
    $album_ids = array_filter(array_unique(array_map('intval', explode(',', $album_ids))));
    if (empty($album_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    // Xác định các album
    $array_select_fields = nv_get_album_select_fields();
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_albums WHERE album_id IN(" . implode(',', $album_ids) . ")";
    $result = $db->query($sql);

    $array = [];
    while ($row = $result->fetch()) {
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
        $array[$row['album_id']] = $row;
    }
    if (sizeof($array) != sizeof($album_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($album_ids as $album_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . Resources::getTablePrefix() . "_albums SET status=" . $status . " WHERE album_id=" . $album_id;
        $db->query($sql);

        $album = $array[$album_id];

        // Cập nhật lại thống kê thể loại
        $album['cat_ids'] = Utils::arrayIntFromStrList($album['cat_ids']);
        foreach ($album['cat_ids'] as $cat_id) {
            msUpdateCatStat($cat_id);
        }

        // Cập nhật ca sĩ
        $album['singer_ids'] = Utils::arrayIntFromStrList($album['singer_ids']);
        foreach ($album['singer_ids'] as $singer_id) {
            msUpdateArtistStat($singer_id, true);
        }

        // Cập nhật random các album
        msUpdateRandomAlbums($album['cat_ids']);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_ALBUM', $album_id . ':' . $array[$album_id]['album_name'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    AjaxRespon::setSuccess()->respon();
}

$base_url = NV_ADMIN_MOD_FULLLINK_AMP . $op;
$per_page = 20;
$page = Utils::getValidPage($nv_Request->get_int('page', 'get', 1), $per_page);

// Dữ liệu tìm kiếm
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', ''); // Từ khóa
$array_search['c'] = $nv_Request->get_int('c', 'get', 0); // Thể loại
$array_search['f'] = $nv_Request->get_title('f', 'get', ''); // Từ
$array_search['t'] = $nv_Request->get_title('t', 'get', ''); // Đến

$db->sqlreset()->from(Resources::getTablePrefix() . "_albums");

$where = [];
if (!empty($array_search['q'])) {
    $dblike = $db->dblikeescape($array_search['q']);
    $dblikekey = $db->dblikeescape(str_replace('-', ' ', strtolower(change_alias($array_search['q']))));
    $where[] = "(
        " . NV_LANG_DATA . "_album_name LIKE '%" . $dblike . "%' OR
        " . NV_LANG_DATA . "_album_searchkey LIKE '%" . $dblikekey . "%'
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

$db->offset(($page - 1) * $per_page)->limit($per_page);

/*
 * Sắp xếp kết quả tốt nhất (dạng đơn giản) nếu tìm từ khóa
 * Nếu không thì sắp bài mới đăng lên đầu
 */
if (!empty($array_search['q'])) {
    $db->order("CASE
        WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "' THEN 1
        WHEN " . NV_LANG_DATA . "_album_name LIKE '" . $dblike . "%' THEN 2
        WHEN " . NV_LANG_DATA . "_album_name LIKE '%" . $dblike . "' THEN 4
        ELSE 3
    END ASC");
} else {
    $db->order("time_add DESC");
}

$array_select_fields = nv_get_album_select_fields(true);
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

    $row['singers'] = [];
    $row['singer_ids'] = explode(',', $row['singer_ids']);
    $row['cats'] = [];
    $row['cat_ids'] = explode(',', $row['cat_ids']);
    $row['album_link'] = '';

    if (!empty($row['singer_ids'])) {
        $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
    }

    $array[$row['album_id']] = $row;
}

// Xác định ca sĩ, chủ đề, đường dẫn bài hát
$array_singers = nv_get_artists($array_singer_ids);

foreach ($array as $id => $row) {
    if (!empty($row['singer_ids'])) {
        foreach ($row['singer_ids'] as $singer_id) {
            if (isset($array_singers[$singer_id])) {
                $row['singers'][$singer_id] = $array_singers[$singer_id];
            }
        }
    }
    foreach ($row['cat_ids'] as $cid) {
        if (isset($global_array_cat[$cid])) {
            $row['cats'][$cid] = $global_array_cat[$cid];
        }
    }
    $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
    $array[$id] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
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
$xtpl->assign('LINK_ADD', NV_ADMIN_MOD_FULLLINK_AMP . 'album-content');

// Xuất ra trình duyệt
foreach ($array as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    $row['stat_views'] = Utils::getFormatNumberView($row['stat_views']);
    $row['stat_comments'] = Utils::getFormatNumberView($row['stat_comments']);
    $row['state'] = $nv_Lang->getModule('status_' . $row['status']);
    $row['url_edit'] = NV_ADMIN_MOD_FULLLINK_AMP . 'album-content&amp;album_id=' . $row['album_id'];
    $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'album', true);
    $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'album');
    $row['release_year'] = empty($row['release_year']) ? '&nbsp;' : ($nv_Lang->getModule('year') . ': ' . $row['release_year']);

    $xtpl->assign('ROW', $row);

    // Ca sĩ
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
        $xtpl->assign('UNKNOW_CAT', Config::getUnknowCat());
        $xtpl->parse('main.loop.no_cat');
    }

    if (empty($row['status'])) {
        $xtpl->assign('ACTION_STATUS', 'active');
        $xtpl->assign('LANG_STATUS', $nv_Lang->getModule('action_active'));
    } else {
        $xtpl->assign('ACTION_STATUS', 'deactive');
        $xtpl->assign('LANG_STATUS', $nv_Lang->getModule('action_deactive'));
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
