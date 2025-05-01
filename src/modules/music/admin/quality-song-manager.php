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

use NukeViet\Module\music\AjaxRespon;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Utils;
use NukeViet\Module\music\Config;

$page_title = $nv_Lang->getModule('qso_manager');

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $quality_ids = $nv_Request->get_title('id', 'post', '');
    $quality_ids = array_filter(array_unique(array_map('intval', explode(',', $quality_ids))));
    if (empty($quality_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    foreach ($quality_ids as $quality_id) {
        if (!isset($global_array_soquality[$quality_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    foreach ($quality_ids as $quality_id) {
        // Xóa
        $sql = "DELETE FROM " . Resources::getTablePrefix() . "_quality_song WHERE quality_id=" . $quality_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_QUALITY_SONG', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
    }

    // Cập nhật lại thứ tự
    $sql = "SELECT quality_id FROM " . Resources::getTablePrefix() . "_quality_song ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET weight=" . $weight . " WHERE quality_id=" . $row['quality_id'];
        $db->query($sql);
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

    $quality_ids = $nv_Request->get_title('id', 'post', '');
    $quality_ids = array_filter(array_unique(array_map('intval', explode(',', $quality_ids))));
    if (empty($quality_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    foreach ($quality_ids as $quality_id) {
        if (!isset($global_array_soquality[$quality_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($quality_ids as $quality_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET status=" . $status . " WHERE quality_id=" . $quality_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_QUALITY_SONG', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    AjaxRespon::setSuccess()->respon();
}

// Thay đổi thứ tự
if ($ajaction == 'weight') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $quality_id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('value', 'post', 0);

    if (!isset($global_array_soquality[$quality_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    if ($new_weight < 1 or $new_weight > sizeof($global_array_soquality)) {
        AjaxRespon::setMessage('Wrong Weight!!!')->respon();
    }

    $sql = "SELECT quality_id FROM " . Resources::getTablePrefix() . "_quality_song WHERE quality_id!=" . $quality_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET weight=" . $weight . " WHERE quality_id=" . $row['quality_id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET weight=" . $new_weight . " WHERE quality_id=" . $quality_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_WEIGHT_QUALITY_SONG', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);

    AjaxRespon::setSuccess()->respon();
}

// Đánh dấu/Bỏ đánh dấu hỗ trợ nghe trực tuyến
if ($ajaction == 'setonlinesupported' or $ajaction == 'unsetonlinesupported') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $quality_id = $nv_Request->get_int('id', 'post', 0);

    if (!isset($global_array_soquality[$quality_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $online_supported = $ajaction == 'setonlinesupported' ? 1 : 0;
    $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET online_supported=" . $online_supported . " WHERE quality_id=" . $quality_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_QUALITY_SONG', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
    AjaxRespon::setSuccess()->respon();
}

// Đánh dấu mặc định khi nghe
if ($ajaction == 'setdefault') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $quality_id = $nv_Request->get_int('id', 'post', 0);

    if (!isset($global_array_soquality[$quality_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET is_default=0";
    $db->query($sql);
    $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET is_default=1 WHERE quality_id=" . $quality_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_QUALITY_SONG', $quality_id . ':' . $global_array_soquality[$quality_id][NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
    AjaxRespon::setSuccess()->respon();
}

// Lấy thông tin
if ($ajaction == 'ajedit') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $quality_id = $nv_Request->get_int('id', 'post', 0);
    if (!isset($global_array_soquality[$quality_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $array_quality = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_quality_song WHERE quality_id=" . $quality_id)->fetch();
    if (empty($array_quality)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $response = array();
    $response['quality_name'] = nv_unhtmlspecialchars($array_quality[NV_LANG_DATA . '_quality_name']);
    $response['quality_alias'] = nv_unhtmlspecialchars($array_quality[NV_LANG_DATA . '_quality_alias']);

    $response_checkbox = array();
    $response_checkbox['online_supported'] = $array_quality['online_supported'];
    $response_checkbox['is_default'] = $array_quality['is_default'];

    AjaxRespon::set('data', $response);
    AjaxRespon::set('datacheckbox', $response_checkbox)->setSuccess()->respon();
}

// Thêm, sửa
if ($nv_Request->isset_request('ajaxrequest', 'get')) {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $array = array();
    $array['quality_name'] = nv_substr($nv_Request->get_title('quality_name', 'post', ''), 0, 250);
    $array['quality_alias'] = nv_substr($nv_Request->get_title('quality_alias', 'post', ''), 0, 250);
    $array['online_supported'] = intval($nv_Request->get_bool('online_supported', 'post', false));
    $array['is_default'] = intval($nv_Request->get_bool('is_default', 'post', false));

    $array['quality_id'] = $nv_Request->get_int('id', 'post', 0);
    $array['submittype'] = nv_substr($nv_Request->get_title('submittype', 'post', ''), 0, 250);

    AjaxRespon::set('mode', nv_htmlspecialchars(change_alias(nv_strtolower($array['submittype']))));

    $array['quality_alias'] = empty($array['quality_alias']) ? change_alias($array['quality_name']) : change_alias($array['quality_alias']);

    // Kiểm tra tồn tại alias
    $is_exists = 0;
    try {
        $sql = "SELECT COUNT(*) FROM " . Resources::getTablePrefix() . "_quality_song WHERE " . NV_LANG_DATA . "_quality_alias=:quality_alias" . ($array['quality_id'] ? " AND quality_id!=" . $array['quality_id'] : "");
        $sth = $db->prepare($sql);
        $sth->bindParam(':quality_alias', $array['quality_alias'], PDO::PARAM_STR);
        $sth->execute();
        $is_exists = $sth->fetchColumn();
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    // Kiểm tra tồn tại sửa
    $array_old = array();
    $error_exists = false;
    if (!empty($array['quality_id'])) {
        $array_old = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_quality_song WHERE quality_id=" . $array['quality_id'])->fetch();
        if (empty($array_old)) {
            $error_exists = true;
        }
    }

    if ($error_exists) {
        AjaxRespon::setMessage($nv_Lang->getModule('qso_err_exists'));
    } elseif (empty($array['quality_name'])) {
        AjaxRespon::setMessage($nv_Lang->getModule('qso_err_name'));
    } else {
        if ($array['quality_id']) {
            $sql = "UPDATE " . Resources::getTablePrefix() . "_quality_song SET
                online_supported=" . $array['online_supported'] . ",
                is_default=" . $array['is_default'] . ",
                " . NV_LANG_DATA . "_quality_name=:quality_name,
                " . NV_LANG_DATA . "_quality_alias=:quality_alias,
                time_update=" . NV_CURRENTTIME . "
            WHERE quality_id=" . $array['quality_id'];
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':quality_name', $array['quality_name'], PDO::PARAM_STR);
                $sth->bindParam(':quality_alias', $array['quality_alias'], PDO::PARAM_STR);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_QUALITY_SONG', $array_old[NV_LANG_DATA . '_quality_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                AjaxRespon::setSuccess();
            } catch (PDOException $e) {
                AjaxRespon::setMessage($nv_Lang->getModule('error_save') . ' ' . $e->getMessage());
            }
        } else {
            $weight = $db->query("SELECT MAX(weight) FROM " . Resources::getTablePrefix() . "_quality_song")->fetchColumn();
            $weight++;

            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_quality_song (
                online_supported, is_default, time_add, weight, status,
                " . NV_LANG_DATA . "_quality_name, " . NV_LANG_DATA . "_quality_alias
            ) VALUES (
                " . $array['online_supported'] . ", " . $array['is_default'] . ", " . NV_CURRENTTIME . ",
                " . $weight . ", 1, :quality_name, :quality_alias
            )";
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':quality_name', $array['quality_name'], PDO::PARAM_STR);
                $sth->bindParam(':quality_alias', $array['quality_alias'], PDO::PARAM_STR);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_QUALITY_SONG', $array['quality_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                AjaxRespon::setSuccess();
            } catch (PDOException $e) {
                AjaxRespon::setMessage($nv_Lang->getModule('error_save') . ' ' . $e->getMessage());
            }
        }
    }

    AjaxRespon::respon();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_soquality));
$xtpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);

$default_language = Config::getDefaultLang();

foreach ($global_array_soquality as $row) {
    if (empty($row[NV_LANG_DATA . '_quality_name']) and !empty($row[$default_language . '_quality_name'])) {
        $row['quality_name'] = $row[$default_language . '_quality_name'];
    } else {
        $row['quality_name'] = $row[NV_LANG_DATA . '_quality_name'];
    }

    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    $row['status'] = $row['status'] ? ' checked="checked"' : '';

    $xtpl->assign('ROW', $row);

    $array_action_key = array();
    $array_action_lang = array();
    $array_action_key[] = 'ajedit';
    $array_action_lang[] = $nv_Lang->getGlobal('edit');

    if (!empty($row['online_supported'])) {
        $xtpl->parse('main.loop.online_supported');
        $array_action_key[] = 'unsetonlinesupported';
        $array_action_lang[] = $nv_Lang->getModule('action_unset_online_supported');
    } else {
        $xtpl->parse('main.loop.online_notsupported');
        $array_action_key[] = 'setonlinesupported';
        $array_action_lang[] = $nv_Lang->getModule('action_set_online_supported');
    }
    if (!empty($row['is_default'])) {
        $xtpl->parse('main.loop.is_default');
    } else {
        $xtpl->parse('main.loop.no_default');
        $array_action_key[] = 'setdefault';
        $array_action_lang[] = $nv_Lang->getModule('action_set_default');
    }

    $array_action_key[] = 'delete';
    $array_action_lang[] = $nv_Lang->getGlobal('delete');

    $xtpl->assign('ACTION_KEY', implode('|', $array_action_key));
    $xtpl->assign('ACTION_LANG', implode('|', $array_action_lang));

    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
