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

use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Resources;
use NukeViet\Music\Utils;
use NukeViet\Music\Shared\ChartCategories;

$page_title = $nv_Lang->getModule('chart_manager');

$ajaction = $nv_Request->get_title('ajaction', 'post', '');

// Xóa
if ($ajaction == 'delete') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $cat_ids = $nv_Request->get_title('id', 'post', '');
    $cat_ids = array_filter(array_unique(array_map('intval', explode(',', $cat_ids))));
    if (empty($cat_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    foreach ($cat_ids as $cat_id) {
        if (!isset($global_array_cat_chart[$cat_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    foreach ($cat_ids as $cat_id) {
        // Xóa
        $sql = "DELETE FROM " . Resources::getTablePrefix() . "_chart_categories WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Xóa dữ liệu BXH
        $sql = "DELETE FROM " . Resources::getTablePrefix() . "_charts WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Xóa dữ liệu BXH tạm thời
        $sql = "DELETE FROM " . Resources::getTablePrefix() . "_chart_tmps WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_CAT_CHART', $cat_id . ':' . $global_array_cat_chart[$cat_id]['cat_name'], $admin_info['userid']);
    }

    // Cập nhật lại thứ tự
    $sql = "SELECT cat_id FROM " . Resources::getTablePrefix() . "_chart_categories ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_categories SET weight=" . $weight . " WHERE cat_id=" . $row['cat_id'];
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

    $cat_ids = $nv_Request->get_title('id', 'post', '');
    $cat_ids = array_filter(array_unique(array_map('intval', explode(',', $cat_ids))));
    if (empty($cat_ids)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    foreach ($cat_ids as $cat_id) {
        if (!isset($global_array_cat_chart[$cat_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($cat_ids as $cat_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_categories SET status=" . $status . " WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_CAT_CHART', $cat_id . ':' . $global_array_cat_chart[$cat_id]['cat_name'], $admin_info['userid']);
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

    $cat_id = $nv_Request->get_int('id', 'post', 0);
    $new_weight = $nv_Request->get_int('value', 'post', 0);

    if (!isset($global_array_cat_chart[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    if ($new_weight < 1 or $new_weight > sizeof($global_array_cat_chart)) {
        AjaxRespon::setMessage('Wrong Weight!!!')->respon();
    }

    $sql = "SELECT cat_id FROM " . Resources::getTablePrefix() . "_chart_categories WHERE cat_id!=" . $cat_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_categories SET weight=" . $weight . " WHERE cat_id=" . $row['cat_id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_categories SET weight=" . $new_weight . " WHERE cat_id=" . $cat_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_WEIGHT_CAT_CHART', $cat_id . ':' . $global_array_cat_chart[$cat_id]['cat_name'], $admin_info['userid']);

    AjaxRespon::setSuccess()->respon();
}

// Lấy thông tin
if ($ajaction == 'ajedit') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $cat_id = $nv_Request->get_int('id', 'post', 0);
    if (!isset($global_array_cat_chart[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $array_cat = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_chart_categories WHERE cat_id=" . $cat_id)->fetch();
    if (empty($array_cat)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    if (!empty($array_cat['resource_cover']) and !nv_is_url($array_cat['resource_cover']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array_cat['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_cover'];
    }

    $response = [];
    $response['resource_cover'] = nv_unhtmlspecialchars($array_cat['resource_cover']);
    $response['cat_name'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_name']);
    $response['cat_alias'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_alias']);

    $response['cat_absitetitle'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_absitetitle']);
    $response['cat_abintrotext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_abintrotext']);
    $response['cat_abkeywords'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_abkeywords']);
    $response['cat_abbodytext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_abbodytext']);
    $response['cat_mvsitetitle'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvsitetitle']);
    $response['cat_mvintrotext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvintrotext']);
    $response['cat_mvkeywords'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvkeywords']);
    $response['cat_mvbodytext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvbodytext']);
    $response['cat_sositetitle'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_sositetitle']);
    $response['cat_sointrotext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_sointrotext']);
    $response['cat_sokeywords'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_sokeywords']);
    $response['cat_sobodytext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_sobodytext']);

    $array_cat['cat_ids'] = Utils::arrayIntFromStrList($array_cat['cat_ids']);

    $response_checkbox = [];
    $response_checkboxid = [];
    foreach ($global_array_cat as $cat) {
        $response_checkboxid['cat_ids_' . $cat['cat_id']] = in_array($cat['cat_id'], $array_cat['cat_ids']) ? 1 : 0;
    }

    AjaxRespon::set('data', $response);
    AjaxRespon::set('datacheckboxid', $response_checkboxid)->setSuccess()->respon();
}

// Thêm, sửa
if ($nv_Request->isset_request('ajaxrequest', 'get')) {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $array = [];
    $array['cat_name'] = nv_substr($nv_Request->get_title('cat_name', 'post', ''), 0, 250);
    $array['cat_alias'] = nv_substr($nv_Request->get_title('cat_alias', 'post', ''), 0, 250);
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['cat_ids'] = $nv_Request->get_typed_array('cat_ids', 'post', 'int', []);
    $array['cat_absitetitle'] = nv_substr($nv_Request->get_title('cat_absitetitle', 'post', ''), 0, 250);
    $array['cat_abintrotext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_abintrotext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_abkeywords'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_abkeywords', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_abbodytext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_abbodytext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_mvsitetitle'] = nv_substr($nv_Request->get_title('cat_mvsitetitle', 'post', ''), 0, 250);
    $array['cat_mvintrotext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_mvintrotext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_mvkeywords'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_mvkeywords', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_mvbodytext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_mvbodytext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_sositetitle'] = nv_substr($nv_Request->get_title('cat_sositetitle', 'post', ''), 0, 250);
    $array['cat_sointrotext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_sointrotext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_sokeywords'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_sokeywords', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_sobodytext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_sobodytext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_id'] = $nv_Request->get_int('id', 'post', 0);
    $array['submittype'] = nv_substr($nv_Request->get_title('submittype', 'post', ''), 0, 250);

    AjaxRespon::set('mode', nv_htmlspecialchars(change_alias(nv_strtolower($array['submittype']))));

    $array['cat_alias'] = empty($array['cat_alias']) ? change_alias($array['cat_name']) : change_alias($array['cat_alias']);
    $array['cat_ids'] = array_intersect($array['cat_ids'], array_keys($global_array_cat));

    if (!nv_is_url($array['resource_cover']) and nv_is_file($array['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['resource_cover'] = substr($array['resource_cover'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } elseif (!nv_is_url($array['resource_cover'])) {
        $array['resource_cover'] = '';
    }

    // Kiểm tra tồn tại alias
    $is_exists = 0;
    try {
        $sql = "SELECT COUNT(*) FROM " . Resources::getTablePrefix() . "_chart_categories WHERE " . NV_LANG_DATA . "_cat_alias=:cat_alias" . ($array['cat_id'] ? " AND cat_id!=" . $array['cat_id'] : "");
        $sth = $db->prepare($sql);
        $sth->bindParam(':cat_alias', $array['cat_alias'], PDO::PARAM_STR);
        $sth->execute();
        $is_exists = $sth->fetchColumn();
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    // Kiểm tra tồn tại sửa
    $array_old = [];
    $error_exists = false;
    if (!empty($array['cat_id'])) {
        $array_old = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_chart_categories WHERE cat_id=" . $array['cat_id'])->fetch();
        if (empty($array_old)) {
            $error_exists = true;
        }
    }

    if ($error_exists) {
        AjaxRespon::setMessage($nv_Lang->getModule('chart_err_exists'));
    } elseif (empty($array['cat_ids'])) {
        AjaxRespon::setMessage($nv_Lang->getModule('chart_err_cat_ids'));
    }  elseif (empty($array['cat_name'])) {
        AjaxRespon::setMessage($nv_Lang->getModule('chart_err_name'));
    } else {
        if ($array['cat_id']) {
            $sql = "UPDATE " . Resources::getTablePrefix() . "_chart_categories SET
                resource_cover=:resource_cover,
                cat_ids='" . implode(',', $array['cat_ids']) . "',
                " . NV_LANG_DATA . "_cat_name=:cat_name,
                " . NV_LANG_DATA . "_cat_alias=:cat_alias,
                " . NV_LANG_DATA . "_cat_absitetitle=:cat_absitetitle,
                " . NV_LANG_DATA . "_cat_abintrotext=:cat_abintrotext,
                " . NV_LANG_DATA . "_cat_abkeywords=:cat_abkeywords,
                " . NV_LANG_DATA . "_cat_abbodytext=:cat_abbodytext,
                " . NV_LANG_DATA . "_cat_mvsitetitle=:cat_mvsitetitle,
                " . NV_LANG_DATA . "_cat_mvintrotext=:cat_mvintrotext,
                " . NV_LANG_DATA . "_cat_mvkeywords=:cat_mvkeywords,
                " . NV_LANG_DATA . "_cat_mvbodytext=:cat_mvbodytext,
                " . NV_LANG_DATA . "_cat_sositetitle=:cat_sositetitle,
                " . NV_LANG_DATA . "_cat_sointrotext=:cat_sointrotext,
                " . NV_LANG_DATA . "_cat_sokeywords=:cat_sokeywords,
                " . NV_LANG_DATA . "_cat_sobodytext=:cat_sobodytext,
                time_update=" . NV_CURRENTTIME . "
            WHERE cat_id=" . $array['cat_id'];
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
                $sth->bindParam(':cat_name', $array['cat_name'], PDO::PARAM_STR);
                $sth->bindParam(':cat_alias', $array['cat_alias'], PDO::PARAM_STR);
                $sth->bindParam(':cat_absitetitle', $array['cat_absitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_abintrotext', $array['cat_abintrotext'], PDO::PARAM_STR, strlen($array['cat_abintrotext']));
                $sth->bindParam(':cat_abkeywords', $array['cat_abkeywords'], PDO::PARAM_STR, strlen($array['cat_abkeywords']));
                $sth->bindParam(':cat_abbodytext', $array['cat_abbodytext'], PDO::PARAM_STR, strlen($array['cat_abbodytext']));
                $sth->bindParam(':cat_mvsitetitle', $array['cat_mvsitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_mvintrotext', $array['cat_mvintrotext'], PDO::PARAM_STR, strlen($array['cat_mvintrotext']));
                $sth->bindParam(':cat_mvkeywords', $array['cat_mvkeywords'], PDO::PARAM_STR, strlen($array['cat_mvkeywords']));
                $sth->bindParam(':cat_mvbodytext', $array['cat_mvbodytext'], PDO::PARAM_STR, strlen($array['cat_mvbodytext']));
                $sth->bindParam(':cat_sositetitle', $array['cat_sositetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_sointrotext', $array['cat_sointrotext'], PDO::PARAM_STR, strlen($array['cat_sointrotext']));
                $sth->bindParam(':cat_sokeywords', $array['cat_sokeywords'], PDO::PARAM_STR, strlen($array['cat_sokeywords']));
                $sth->bindParam(':cat_sobodytext', $array['cat_sobodytext'], PDO::PARAM_STR, strlen($array['cat_sobodytext']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_CAT_CHART', $array_old[NV_LANG_DATA . '_cat_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                AjaxRespon::setSuccess();
            } catch (PDOException $e) {
                AjaxRespon::setMessage($nv_Lang->getModule('error_save') . ' ' . $e->getMessage());
            }
        } else {
            $weight = $db->query("SELECT MAX(weight) FROM " . Resources::getTablePrefix() . "_chart_categories")->fetchColumn();
            $weight++;

            // Xác định các field theo ngôn ngữ không có dữ liệu
            $langs = msGetModuleSetupLangs();
            $array_fname = $array_fvalue = [];
            foreach ($langs as $lang) {
                if ($lang != NV_LANG_DATA) {
                    $array_fname[] = $lang . '_cat_abintrotext';
                    $array_fname[] = $lang . '_cat_abkeywords';
                    $array_fname[] = $lang . '_cat_abbodytext';
                    $array_fname[] = $lang . '_cat_mvintrotext';
                    $array_fname[] = $lang . '_cat_mvkeywords';
                    $array_fname[] = $lang . '_cat_mvbodytext';
                    $array_fname[] = $lang . '_cat_sointrotext';
                    $array_fname[] = $lang . '_cat_sokeywords';
                    $array_fname[] = $lang . '_cat_sobodytext';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                }
            }
            $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
            $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

            $cat_code = ChartCategories::creatUniqueCode();

            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_chart_categories (
                cat_code, resource_cover, time_add, cat_ids, weight, status,
                " . NV_LANG_DATA . "_cat_name, " . NV_LANG_DATA . "_cat_alias,
                " . NV_LANG_DATA . "_cat_absitetitle, " . NV_LANG_DATA . "_cat_abintrotext, " . NV_LANG_DATA . "_cat_abkeywords, " . NV_LANG_DATA . "_cat_abbodytext,
                " . NV_LANG_DATA . "_cat_mvsitetitle, " . NV_LANG_DATA . "_cat_mvintrotext, " . NV_LANG_DATA . "_cat_mvkeywords, " . NV_LANG_DATA . "_cat_mvbodytext,
                " . NV_LANG_DATA . "_cat_sositetitle, " . NV_LANG_DATA . "_cat_sointrotext, " . NV_LANG_DATA . "_cat_sokeywords, " . NV_LANG_DATA . "_cat_sobodytext" . $array_fname . "
            ) VALUES (
                :cat_code, :resource_cover, " . NV_CURRENTTIME . ", '" . implode(',', $array['cat_ids']) . "',
                " . $weight . ", 1, :cat_name, :cat_alias,
                :cat_absitetitle, :cat_abintrotext, :cat_abkeywords, :cat_abbodytext,
                :cat_mvsitetitle, :cat_mvintrotext, :cat_mvkeywords, :cat_mvbodytext,
                :cat_sositetitle, :cat_sointrotext, :cat_sokeywords, :cat_sobodytext" . $array_fvalue . "
            )";
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':cat_code', $cat_code, PDO::PARAM_STR);
                $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
                $sth->bindParam(':cat_name', $array['cat_name'], PDO::PARAM_STR);
                $sth->bindParam(':cat_alias', $array['cat_alias'], PDO::PARAM_STR);
                $sth->bindParam(':cat_absitetitle', $array['cat_absitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_abintrotext', $array['cat_abintrotext'], PDO::PARAM_STR, strlen($array['cat_abintrotext']));
                $sth->bindParam(':cat_abkeywords', $array['cat_abkeywords'], PDO::PARAM_STR, strlen($array['cat_abkeywords']));
                $sth->bindParam(':cat_abbodytext', $array['cat_abbodytext'], PDO::PARAM_STR, strlen($array['cat_abbodytext']));
                $sth->bindParam(':cat_mvsitetitle', $array['cat_mvsitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_mvintrotext', $array['cat_mvintrotext'], PDO::PARAM_STR, strlen($array['cat_mvintrotext']));
                $sth->bindParam(':cat_mvkeywords', $array['cat_mvkeywords'], PDO::PARAM_STR, strlen($array['cat_mvkeywords']));
                $sth->bindParam(':cat_mvbodytext', $array['cat_mvbodytext'], PDO::PARAM_STR, strlen($array['cat_mvbodytext']));
                $sth->bindParam(':cat_sositetitle', $array['cat_sositetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_sointrotext', $array['cat_sointrotext'], PDO::PARAM_STR, strlen($array['cat_sointrotext']));
                $sth->bindParam(':cat_sokeywords', $array['cat_sokeywords'], PDO::PARAM_STR, strlen($array['cat_sokeywords']));
                $sth->bindParam(':cat_sobodytext', $array['cat_sobodytext'], PDO::PARAM_STR, strlen($array['cat_sobodytext']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_CAT_CHART', $array['cat_name'], $admin_info['userid']);
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
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_cat_chart));
$xtpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);

$resource_cover_path = msGetCurrentUploadFolder('');

$xtpl->assign('RESOURCE_COVER_PATH', $resource_cover_path[0]);
$xtpl->assign('RESOURCE_COVER_CURRPATH', $resource_cover_path[1]);

foreach ($global_array_cat_chart as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    $row['status'] = $row['status'] ? ' checked="checked"' : '';

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

// Xuất các thể loại
foreach ($global_array_cat as $cat) {
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
