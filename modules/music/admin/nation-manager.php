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

$page_title = $lang_module['nation_manager'];

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Thay đổi thứ tự
if ($ajaction == 'weight') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $nation_id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('value', 'post', 0);

    if (!isset($global_array_nation[$nation_id])) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }
    if ($new_weight < 1 or $new_weight > sizeof($global_array_nation)) {
        $ajaxRespon->setMessage('Wrong Weight!!!')->respon();
    }

    $sql = "SELECT nation_id FROM " . NV_MOD_TABLE . "_nations WHERE nation_id!=" . $nation_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . NV_MOD_TABLE . "_nations SET weight=" . $weight . " WHERE nation_id=" . $row['nation_id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . NV_MOD_TABLE . "_nations SET weight=" . $new_weight . " WHERE nation_id=" . $nation_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_WEIGHT_NATION', $nation_id . ':' . $global_array_nation[$nation_id]['nation_name'], $admin_info['userid']);

    $ajaxRespon->setSuccess()->respon();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_nation));

foreach ($global_array_nation as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = msFormatDateViews($row['time_add']);
    $row['time_update'] = $row['time_update'] ? msFormatDateViews($row['time_update']) : '';
    $row['stat_singers'] = msFormatNumberViews($row['stat_singers']);
    $row['stat_authors'] = msFormatNumberViews($row['stat_authors']);

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
