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

$page_title = $lang_module['qso_manager'];

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $quality_ids = $nv_Request->get_title('id', 'post', '');
    $quality_ids = array_filter(array_unique(array_map('intval', explode(',', $quality_ids))));
    if (empty($quality_ids)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }
    foreach ($quality_ids as $quality_id) {
        if (!isset($global_array_quality[$quality_id])) {
            $ajaxRespon->setMessage('Wrong ID!!!')->respon();
        }
    }

    foreach ($quality_ids as $quality_id) {
        // Xóa
        $sql = "DELETE FROM " . NV_MOD_TABLE . "_qualitys WHERE quality_id=" . $quality_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_quality', $quality_id . ':' . $global_array_quality[$quality_id]['quality_name'], $admin_info['userid']);
    }

    // Cập nhật lại thứ tự
    $sql = "SELECT quality_id FROM " . NV_MOD_TABLE . "_qualitys ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . NV_MOD_TABLE . "_qualitys SET weight=" . $weight . " WHERE quality_id=" . $row['quality_id'];
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

    $quality_ids = $nv_Request->get_title('id', 'post', '');
    $quality_ids = array_filter(array_unique(array_map('intval', explode(',', $quality_ids))));
    if (empty($quality_ids)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }
    foreach ($quality_ids as $quality_id) {
        if (!isset($global_array_soquality[$quality_id])) {
            $ajaxRespon->setMessage('Wrong ID!!!')->respon();
        }
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($quality_ids as $quality_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . NV_MOD_TABLE . "_quality_song SET status=" . $status . " WHERE quality_id=" . $quality_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_QUALITY', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
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

    $quality_id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('value', 'post', 0);

    if (!isset($global_array_quality[$quality_id])) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }
    if ($new_weight < 1 or $new_weight > sizeof($global_array_quality)) {
        $ajaxRespon->setMessage('Wrong Weight!!!')->respon();
    }

    $sql = "SELECT quality_id FROM " . NV_MOD_TABLE . "_qualitys WHERE quality_id!=" . $quality_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . NV_MOD_TABLE . "_qualitys SET weight=" . $weight . " WHERE quality_id=" . $row['quality_id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . NV_MOD_TABLE . "_qualitys SET weight=" . $new_weight . " WHERE quality_id=" . $quality_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_WEIGHT_quality', $quality_id . ':' . $global_array_quality[$quality_id]['quality_name'], $admin_info['userid']);

    $ajaxRespon->setSuccess()->respon();
}

// Lấy thông tin
if ($ajaction == 'ajedit') {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $quality_id = $nv_Request->get_int('id', 'post', 0);
    if (!isset($global_array_quality[$quality_id])) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    $array_select_fields = nv_get_quality_select_fields(true);
    $array_quality = $db->query("SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_qualitys WHERE quality_id=" . $quality_id)->fetch();
    if (empty($array_quality)) {
        $ajaxRespon->setMessage('Wrong ID!!!')->respon();
    }

    $response = array();
    $response['quality_code'] = nv_unhtmlspecialchars($array_quality['quality_code']);
    $response['quality_name'] = nv_unhtmlspecialchars($array_quality['quality_name']);
    $response['quality_alias'] = nv_unhtmlspecialchars($array_quality['quality_alias']);
    $response['quality_introtext'] = nv_unhtmlspecialchars($array_quality['quality_introtext']);
    $response['quality_keywords'] = nv_unhtmlspecialchars($array_quality['quality_keywords']);

    $ajaxRespon->set('data', $response)->setSuccess()->respon();
}

// Thêm, sửa
if ($nv_Request->isset_request('ajaxrequest', 'get')) {
    $ajaxRespon->reset();
    if (!defined('NV_IS_AJAX')) {
        $ajaxRespon->setMessage('Wrong URL!!!')->respon();
    }

    $array = array();
    $array['quality_code'] = nv_substr($nv_Request->get_title('quality_code', 'post', ''), 0, 250);
    $array['quality_name'] = nv_substr($nv_Request->get_title('quality_name', 'post', ''), 0, 250);
    $array['quality_alias'] = nv_substr($nv_Request->get_title('quality_alias', 'post', ''), 0, 250);
    $array['quality_introtext'] = nv_substr($nv_Request->get_title('quality_introtext', 'post', ''), 0, 250);
    $array['quality_keywords'] = nv_substr($nv_Request->get_title('quality_keywords', 'post', ''), 0, 250);

    $array['quality_id'] = $nv_Request->get_int('id', 'post', 0);
    $array['submittype'] = nv_substr($nv_Request->get_title('submittype', 'post', ''), 0, 250);

    $ajaxRespon->set('mode', nv_htmlspecialchars(change_alias(nv_strtolower($array['submittype']))));

    $array['quality_alias'] = empty($array['quality_alias']) ? change_alias($array['quality_name']) : change_alias($array['quality_alias']);

    // Kiểm tra tồn tại mã
    $is_exists_code = 0;
    if (!empty($array['quality_code'])) {
        try {
            $sql = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_qualitys WHERE quality_code=:quality_code" . ($array['quality_id'] ? " AND quality_id!=" . $array['quality_id'] : "");
            $sth = $db->prepare($sql);
            $sth->bindParam(':quality_code', $array['quality_code'], PDO::PARAM_STR);
            $sth->execute();
            $is_exists_code = $sth->fetchColumn();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }

    // Kiểm tra tồn tại sửa
    $array_old = array();
    $error_exists = false;
    if (!empty($array['quality_id'])) {
        $array_select_fields = nv_get_quality_select_fields(true);
        $array_old = $db->query("SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_qualitys WHERE quality_id=" . $array['quality_id'])->fetch();
        if (empty($array_old)) {
            $error_exists = true;
        }
    }

    if ($error_exists) {
        $ajaxRespon->setMessage($lang_module['quality_err_exists']);
    } elseif (empty($array['quality_code'])) {
        $ajaxRespon->setMessage($lang_module['quality_err_code']);
    } elseif ($is_exists_code) {
        $ajaxRespon->setMessage($lang_module['quality_err_exists_code']);
    } elseif (!preg_match($global_array_rule['quality_code'], $array['quality_code'])) {
        $ajaxRespon->setMessage($lang_module['quality_err_rule_code']);
    } elseif (empty($array['quality_name'])) {
        $ajaxRespon->setMessage($lang_module['quality_err_name']);
    } else {
        if ($array['quality_id']) {
            $sql = "UPDATE " . NV_MOD_TABLE . "_qualitys SET
                quality_code=:quality_code,
                " . NV_LANG_DATA . "_quality_name=:quality_name,
                " . NV_LANG_DATA . "_quality_alias=:quality_alias,
                " . NV_LANG_DATA . "_quality_introtext=:quality_introtext,
                " . NV_LANG_DATA . "_quality_keywords=:quality_keywords,
                time_update=" . NV_CURRENTTIME . "
            WHERE quality_id=" . $array['quality_id'];
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':quality_code', $array['quality_code'], PDO::PARAM_STR);
                $sth->bindParam(':quality_name', $array['quality_name'], PDO::PARAM_STR);
                $sth->bindParam(':quality_alias', $array['quality_alias'], PDO::PARAM_STR);
                $sth->bindParam(':quality_introtext', $array['quality_introtext'], PDO::PARAM_STR, strlen($array['quality_introtext']));
                $sth->bindParam(':quality_keywords', $array['quality_keywords'], PDO::PARAM_STR, strlen($array['quality_keywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_quality', $array_old['quality_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                $ajaxRespon->setSuccess();
            } catch (PDOException $e) {
                $ajaxRespon->setMessage($lang_module['error_save'] . ' ' . $e->getMessage());
            }
        } else {
            $weight = $db->query("SELECT MAX(weight) FROM " . NV_MOD_TABLE . "_qualitys")->fetchColumn();
            $weight++;

            // Xác định các field theo ngôn ngữ không có dữ liệu
            $langs = msGetModuleSetupLangs();
            $array_fname = $array_fvalue = array();
            foreach ($langs as $lang) {
                if ($lang != NV_LANG_DATA) {
                    $array_fname[] = $lang . '_quality_introtext';
                    $array_fname[] = $lang . '_quality_keywords';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                }
            }
            $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
            $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

            $sql = "INSERT INTO " . NV_MOD_TABLE . "_qualitys (
                quality_code, time_add, weight, status,
                " . NV_LANG_DATA . "_quality_name, " . NV_LANG_DATA . "_quality_alias, " . NV_LANG_DATA . "_quality_introtext, " . NV_LANG_DATA . "_quality_keywords" . $array_fname . "
            ) VALUES (
                :quality_code, " . NV_CURRENTTIME . ", " . $weight . ", 1,
                :quality_name, :quality_alias, :quality_introtext, :quality_keywords" . $array_fvalue . "
            )";
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':quality_code', $array['quality_code'], PDO::PARAM_STR);
                $sth->bindParam(':quality_name', $array['quality_name'], PDO::PARAM_STR);
                $sth->bindParam(':quality_alias', $array['quality_alias'], PDO::PARAM_STR);
                $sth->bindParam(':quality_introtext', $array['quality_introtext'], PDO::PARAM_STR, strlen($array['quality_introtext']));
                $sth->bindParam(':quality_keywords', $array['quality_keywords'], PDO::PARAM_STR, strlen($array['quality_keywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_quality', $array['quality_name'], $admin_info['userid']);
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
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_soquality));

foreach ($global_array_soquality as $row) {
    if (empty($row[NV_LANG_DATA . '_quality_name']) and !empty($row[$global_array_config['default_language'] . '_quality_name'])) {
        $row['quality_name'] = $row[$global_array_config['default_language'] . '_quality_name'];
    } else {
        $row['quality_name'] = $row[NV_LANG_DATA . '_quality_name'];
    }

    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = msFormatDateViews($row['time_add']);
    $row['time_update'] = $row['time_update'] ? msFormatDateViews($row['time_update']) : '';
    $row['status'] = $row['status'] ? ' checked="checked"' : '';

    $xtpl->assign('ROW', $row);

    $array_action_key = array();
    $array_action_lang = array();
    $array_action_key[] = 'ajedit';
    $array_action_lang[] = $lang_global['edit'];

    if (!empty($row['online_supported'])) {
        $xtpl->parse('main.loop.online_supported');
    } else {
        $xtpl->parse('main.loop.online_notsupported');
        $array_action_key[] = 'setonlinesupported';
        $array_action_lang[] = $lang_module['action_set_online_supported'];
    }
    if (!empty($row['is_default'])) {
        $xtpl->parse('main.loop.is_default');
    } else {
        $xtpl->parse('main.loop.no_default');
        $array_action_key[] = 'setdefault';
        $array_action_lang[] = $lang_module['action_set_default'];
    }

    $array_action_key[] = 'delete';
    $array_action_lang[] = $lang_global['delete'];

    $xtpl->assign('ACTION_KEY', implode('|', $array_action_key));
    $xtpl->assign('ACTION_LANG', implode('|', $array_action_lang));

    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
