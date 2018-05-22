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

use NukeViet\Music\Utils;

$page_title = $lang_module['nation_manager'];

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

// Lấy thông tin
if ($ajaction == 'ajedit') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $nation_id = $nv_Request->get_int('id', 'post', 0);
    if (!isset($global_array_nation[$nation_id])) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    $array_select_fields = nv_get_nation_select_fields(true);
    $array_nation = $db->query("SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_nations WHERE nation_id=" . $nation_id)->fetch();
    if (empty($array_nation)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    $response = array();
    $response['nation_code'] = nv_unhtmlspecialchars($array_nation['nation_code']);
    $response['nation_name'] = nv_unhtmlspecialchars($array_nation['nation_name']);
    $response['nation_alias'] = nv_unhtmlspecialchars($array_nation['nation_alias']);
    $response['nation_introtext'] = nv_unhtmlspecialchars($array_nation['nation_introtext']);
    $response['nation_keywords'] = nv_unhtmlspecialchars($array_nation['nation_keywords']);

    $ajaxRespon->set('data', $response)->setSuccess()->respon();
}

// Thêm, sửa
if ($nv_Request->isset_request('ajaxrequest', 'get')) {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $array = array();
    $array['nation_code'] = nv_substr($nv_Request->get_title('nation_code', 'post', ''), 0, 250);
    $array['nation_name'] = nv_substr($nv_Request->get_title('nation_name', 'post', ''), 0, 250);
    $array['nation_alias'] = nv_substr($nv_Request->get_title('nation_alias', 'post', ''), 0, 250);
    $array['nation_introtext'] = nv_substr($nv_Request->get_title('nation_introtext', 'post', ''), 0, 250);
    $array['nation_keywords'] = nv_substr($nv_Request->get_title('nation_keywords', 'post', ''), 0, 250);

    $array['nation_id'] = $nv_Request->get_int('id', 'post', 0);
    $array['submittype'] = nv_substr($nv_Request->get_title('submittype', 'post', ''), 0, 250);

    $ajaxRespon->set('mode', nv_htmlspecialchars(change_alias(nv_strtolower($array['submittype']))));

    $array['nation_alias'] = empty($array['nation_alias']) ? change_alias($array['nation_name']) : change_alias($array['nation_alias']);

    // Kiểm tra tồn tại mã
    $is_exists_code = 0;
    if (!empty($array['nation_code'])) {
        try {
            $sql = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_nations WHERE nation_code=:nation_code" . ($array['nation_id'] ? " AND nation_id!=" . $array['nation_id'] : "");
            $sth = $db->prepare($sql);
            $sth->bindParam(':nation_code', $array['nation_code'], PDO::PARAM_STR);
            $sth->execute();
            $is_exists_code = $sth->fetchColumn();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }

    // Kiểm tra tồn tại sửa
    $array_old = array();
    $error_exists = false;
    if (!empty($array['nation_id'])) {
        $array_select_fields = nv_get_nation_select_fields(true);
        $array_old = $db->query("SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_nations WHERE nation_id=" . $array['nation_id'])->fetch();
        if (empty($array_old)) {
            $error_exists = true;
        }
    }

    if ($error_exists) {
        $ajaxRespon->setMessage($lang_module['nation_err_exists']);
    } elseif (empty($array['nation_code'])) {
        $ajaxRespon->setMessage($lang_module['nation_err_code']);
    } elseif ($is_exists_code) {
        $ajaxRespon->setMessage($lang_module['nation_err_exists_code']);
    } elseif (!preg_match($global_array_rule['nation_code'], $array['nation_code'])) {
        $ajaxRespon->setMessage($lang_module['nation_err_rule_code']);
    } elseif (empty($array['nation_name'])) {
        $ajaxRespon->setMessage($lang_module['nation_err_name']);
    } else {
        if ($array['nation_id']) {
            $sql = "UPDATE " . NV_MOD_TABLE . "_nations SET
                nation_code=:nation_code,
                " . NV_LANG_DATA . "_nation_name=:nation_name,
                " . NV_LANG_DATA . "_nation_alias=:nation_alias,
                " . NV_LANG_DATA . "_nation_introtext=:nation_introtext,
                " . NV_LANG_DATA . "_nation_keywords=:nation_keywords,
                time_update=" . NV_CURRENTTIME . "
            WHERE nation_id=" . $array['nation_id'];
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':nation_code', $array['nation_code'], PDO::PARAM_STR);
                $sth->bindParam(':nation_name', $array['nation_name'], PDO::PARAM_STR);
                $sth->bindParam(':nation_alias', $array['nation_alias'], PDO::PARAM_STR);
                $sth->bindParam(':nation_introtext', $array['nation_introtext'], PDO::PARAM_STR, strlen($array['nation_introtext']));
                $sth->bindParam(':nation_keywords', $array['nation_keywords'], PDO::PARAM_STR, strlen($array['nation_keywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_NATION', $array_old['nation_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                $ajaxRespon->setSuccess();
            } catch (PDOException $e) {
                $ajaxRespon->setMessage($lang_module['error_save'] . ' ' . $e->getMessage());
            }
        } else {
            $weight = $db->query("SELECT MAX(weight) FROM " . NV_MOD_TABLE . "_nations")->fetchColumn();
            $weight++;

            // Xác định các field theo ngôn ngữ không có dữ liệu
            $langs = msGetModuleSetupLangs();
            $array_fname = $array_fvalue = array();
            foreach ($langs as $lang) {
                if ($lang != NV_LANG_DATA) {
                    $array_fname[] = $lang . '_nation_introtext';
                    $array_fname[] = $lang . '_nation_keywords';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                }
            }
            $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
            $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

            $sql = "INSERT INTO " . NV_MOD_TABLE . "_nations (
                nation_code, time_add, weight, status,
                " . NV_LANG_DATA . "_nation_name, " . NV_LANG_DATA . "_nation_alias, " . NV_LANG_DATA . "_nation_introtext, " . NV_LANG_DATA . "_nation_keywords" . $array_fname . "
            ) VALUES (
                :nation_code, " . NV_CURRENTTIME . ", " . $weight . ", 1,
                :nation_name, :nation_alias, :nation_introtext, :nation_keywords" . $array_fvalue . "
            )";
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':nation_code', $array['nation_code'], PDO::PARAM_STR);
                $sth->bindParam(':nation_name', $array['nation_name'], PDO::PARAM_STR);
                $sth->bindParam(':nation_alias', $array['nation_alias'], PDO::PARAM_STR);
                $sth->bindParam(':nation_introtext', $array['nation_introtext'], PDO::PARAM_STR, strlen($array['nation_introtext']));
                $sth->bindParam(':nation_keywords', $array['nation_keywords'], PDO::PARAM_STR, strlen($array['nation_keywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_NATION', $array['nation_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                $ajaxRespon->setSuccess();
            } catch (PDOException $e) {
                $ajaxRespon->setMessage($lang_module['error_save'] . ' ' . $e->getMessage());
            }
        }
    }

    $ajaxRespon->respon();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_nation));
$xtpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);

foreach ($global_array_nation as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    $row['stat_singers'] = Utils::getFormatNumberView($row['stat_singers']);
    $row['stat_authors'] = Utils::getFormatNumberView($row['stat_authors']);

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
