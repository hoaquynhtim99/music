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
use NukeViet\Music\Shared\Categories;

$page_title = $nv_Lang->getModule('cat_manager');

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
        if (!isset($global_array_cat[$cat_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    foreach ($cat_ids as $cat_id) {
        // Xóa
        $sql = "DELETE FROM " . Resources::getTablePrefix() . "_categories WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_CAT', $cat_id . ':' . $global_array_cat[$cat_id]['cat_name'], $admin_info['userid']);
    }

    // Cập nhật lại thứ tự
    $sql = "SELECT cat_id FROM " . Resources::getTablePrefix() . "_categories ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET weight=" . $weight . " WHERE cat_id=" . $row['cat_id'];
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
        if (!isset($global_array_cat[$cat_id])) {
            AjaxRespon::setMessage('Wrong ID!!!')->respon();
        }
    }

    $status = $ajaction == 'active' ? 1 : 0;

    foreach ($cat_ids as $cat_id) {
        // Cập nhật trạng thái
        $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET status=" . $status . " WHERE cat_id=" . $cat_id;
        $db->query($sql);

        // Ghi nhật ký hệ thống
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_CAT', $cat_id . ':' . $global_array_cat[$cat_id]['cat_name'], $admin_info['userid']);
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

    if (!isset($global_array_cat[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }
    if ($new_weight < 1 or $new_weight > sizeof($global_array_cat)) {
        AjaxRespon::setMessage('Wrong Weight!!!')->respon();
    }

    $sql = "SELECT cat_id FROM " . Resources::getTablePrefix() . "_categories WHERE cat_id!=" . $cat_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET weight=" . $weight . " WHERE cat_id=" . $row['cat_id'];
        $db->query($sql);
    }

    $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET weight=" . $new_weight . " WHERE cat_id=" . $cat_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_WEIGHT_CAT', $cat_id . ':' . $global_array_cat[$cat_id]['cat_name'], $admin_info['userid']);

    AjaxRespon::setSuccess()->respon();
}

// Đánh dấu/Bỏ đánh dấu hiển thị ở trang album
if ($ajaction == 'activeinalbum' or $ajaction == 'unactiveinalbum') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $cat_id = $nv_Request->get_int('id', 'post', 0);

    if (!isset($global_array_cat[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $show_inalbum = $ajaction == 'activeinalbum' ? 1 : 0;
    $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET show_inalbum=" . $show_inalbum . " WHERE cat_id=" . $cat_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_CAT', $cat_id . ':' . $global_array_cat[$cat_id]['cat_name'], $admin_info['userid']);
    AjaxRespon::setSuccess()->respon();
}

// Đánh dấu/Bỏ đánh dấu hiển thị ở trang video
if ($ajaction == 'activeinvideo' or $ajaction == 'unactiveinvideo') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $cat_id = $nv_Request->get_int('id', 'post', 0);

    if (!isset($global_array_cat[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $show_invideo = $ajaction == 'activeinvideo' ? 1 : 0;
    $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET show_invideo=" . $show_invideo . " WHERE cat_id=" . $cat_id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_' . strtoupper($ajaction) . '_CAT', $cat_id . ':' . $global_array_cat[$cat_id]['cat_name'], $admin_info['userid']);
    AjaxRespon::setSuccess()->respon();
}

// Lấy thông tin
if ($ajaction == 'ajedit') {
    AjaxRespon::reset();
    if (!defined('NV_IS_AJAX')) {
        AjaxRespon::setMessage('Wrong URL!!!')->respon();
    }

    $cat_id = $nv_Request->get_int('id', 'post', 0);
    if (!isset($global_array_cat[$cat_id])) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    $array_cat = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_categories WHERE cat_id=" . $cat_id)->fetch();
    if (empty($array_cat)) {
        AjaxRespon::setMessage('Wrong ID!!!')->respon();
    }

    if (!empty($array_cat['resource_avatar']) and !nv_is_url($array_cat['resource_avatar']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array_cat['resource_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_avatar'];
    }
    if (!empty($array_cat['resource_cover']) and !nv_is_url($array_cat['resource_cover']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array_cat['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_cover'];
    }
    if (!empty($array_cat['resource_video']) and !nv_is_url($array_cat['resource_video']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_video'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array_cat['resource_video'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_cat['resource_video'];
    }

    $response = [];
    $response['resource_avatar'] = nv_unhtmlspecialchars($array_cat['resource_avatar']);
    $response['resource_cover'] = nv_unhtmlspecialchars($array_cat['resource_cover']);
    $response['resource_video'] = nv_unhtmlspecialchars($array_cat['resource_video']);
    $response['cat_name'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_name']);
    $response['cat_alias'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_alias']);
    $response['cat_absitetitle'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_absitetitle']);
    $response['cat_abintrotext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_abintrotext']);
    $response['cat_abkeywords'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_abkeywords']);
    $response['cat_mvsitetitle'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvsitetitle']);
    $response['cat_mvintrotext'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvintrotext']);
    $response['cat_mvkeywords'] = nv_unhtmlspecialchars($array_cat[NV_LANG_DATA . '_cat_mvkeywords']);

    $response_checkbox = [];
    $response_checkbox['show_inalbum'] = $array_cat['show_inalbum'];
    $response_checkbox['show_invideo'] = $array_cat['show_invideo'];

    AjaxRespon::set('data', $response);
    AjaxRespon::set('datacheckbox', $response_checkbox)->setSuccess()->respon();
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
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['resource_video'] = $nv_Request->get_title('resource_video', 'post', '');
    $array['show_inalbum'] = intval($nv_Request->get_bool('show_inalbum', 'post', false));
    $array['show_invideo'] = intval($nv_Request->get_bool('show_invideo', 'post', false));
    $array['cat_absitetitle'] = nv_substr($nv_Request->get_title('cat_absitetitle', 'post', ''), 0, 250);
    $array['cat_abintrotext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_abintrotext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_abkeywords'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_abkeywords', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_mvsitetitle'] = nv_substr($nv_Request->get_title('cat_mvsitetitle', 'post', ''), 0, 250);
    $array['cat_mvintrotext'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_mvintrotext', '', NV_ALLOWED_HTML_TAGS), ' ')));
    $array['cat_mvkeywords'] = trim(preg_replace('/\s[\s]+/iu', ' ', nv_nl2br($nv_Request->get_textarea('cat_mvkeywords', '', NV_ALLOWED_HTML_TAGS), ' ')));

    $array['cat_id'] = $nv_Request->get_int('id', 'post', 0);
    $array['submittype'] = nv_substr($nv_Request->get_title('submittype', 'post', ''), 0, 250);

    AjaxRespon::set('mode', nv_htmlspecialchars(change_alias(nv_strtolower($array['submittype']))));

    $array['cat_alias'] = empty($array['cat_alias']) ? change_alias($array['cat_name']) : change_alias($array['cat_alias']);

    if (!nv_is_url($array['resource_avatar']) and nv_is_file($array['resource_avatar'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['resource_avatar'] = substr($array['resource_avatar'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } elseif (!nv_is_url($array['resource_avatar'])) {
        $array['resource_avatar'] = '';
    }
    if (!nv_is_url($array['resource_cover']) and nv_is_file($array['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['resource_cover'] = substr($array['resource_cover'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } elseif (!nv_is_url($array['resource_cover'])) {
        $array['resource_cover'] = '';
    }
    if (!nv_is_url($array['resource_video']) and nv_is_file($array['resource_video'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['resource_video'] = substr($array['resource_video'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } elseif (!nv_is_url($array['resource_video'])) {
        $array['resource_video'] = '';
    }

    // Kiểm tra tồn tại alias
    $is_exists = 0;
    try {
        $sql = "SELECT COUNT(*) FROM " . Resources::getTablePrefix() . "_categories WHERE " . NV_LANG_DATA . "_cat_alias=:cat_alias" . ($array['cat_id'] ? " AND cat_id!=" . $array['cat_id'] : "");
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
        $array_old = $db->query("SELECT * FROM " . Resources::getTablePrefix() . "_categories WHERE cat_id=" . $array['cat_id'])->fetch();
        if (empty($array_old)) {
            $error_exists = true;
        }
    }

    if ($error_exists) {
        AjaxRespon::setMessage($nv_Lang->getModule('cat_err_exists'));
    } elseif (empty($array['cat_name'])) {
        AjaxRespon::setMessage($nv_Lang->getModule('cat_err_name'));
    } else {
        if ($array['cat_id']) {
            $sql = "UPDATE " . Resources::getTablePrefix() . "_categories SET
                resource_avatar=:resource_avatar,
                resource_cover=:resource_cover,
                resource_video=:resource_video,
                show_inalbum=" . $array['show_inalbum'] . ",
                show_invideo=" . $array['show_invideo'] . ",
                " . NV_LANG_DATA . "_cat_name=:cat_name,
                " . NV_LANG_DATA . "_cat_alias=:cat_alias,
                " . NV_LANG_DATA . "_cat_absitetitle=:cat_absitetitle,
                " . NV_LANG_DATA . "_cat_abintrotext=:cat_abintrotext,
                " . NV_LANG_DATA . "_cat_abkeywords=:cat_abkeywords,
                " . NV_LANG_DATA . "_cat_mvsitetitle=:cat_mvsitetitle,
                " . NV_LANG_DATA . "_cat_mvintrotext=:cat_mvintrotext,
                " . NV_LANG_DATA . "_cat_mvkeywords=:cat_mvkeywords,
                time_update=" . NV_CURRENTTIME . "
            WHERE cat_id=" . $array['cat_id'];
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
                $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
                $sth->bindParam(':resource_video', $array['resource_video'], PDO::PARAM_STR);
                $sth->bindParam(':cat_name', $array['cat_name'], PDO::PARAM_STR);
                $sth->bindParam(':cat_alias', $array['cat_alias'], PDO::PARAM_STR);
                $sth->bindParam(':cat_absitetitle', $array['cat_absitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_abintrotext', $array['cat_abintrotext'], PDO::PARAM_STR, strlen($array['cat_abintrotext']));
                $sth->bindParam(':cat_abkeywords', $array['cat_abkeywords'], PDO::PARAM_STR, strlen($array['cat_abkeywords']));
                $sth->bindParam(':cat_mvsitetitle', $array['cat_mvsitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_mvintrotext', $array['cat_mvintrotext'], PDO::PARAM_STR, strlen($array['cat_mvintrotext']));
                $sth->bindParam(':cat_mvkeywords', $array['cat_mvkeywords'], PDO::PARAM_STR, strlen($array['cat_mvkeywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_CAT', $array_old[NV_LANG_DATA . '_cat_name'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);

                AjaxRespon::setSuccess();
            } catch (PDOException $e) {
                AjaxRespon::setMessage($nv_Lang->getModule('error_save') . ' ' . $e->getMessage());
            }
        } else {
            $weight = $db->query("SELECT MAX(weight) FROM " . Resources::getTablePrefix() . "_categories")->fetchColumn();
            $weight++;

            // Xác định các field theo ngôn ngữ không có dữ liệu
            $langs = msGetModuleSetupLangs();
            $array_fname = $array_fvalue = [];
            foreach ($langs as $lang) {
                if ($lang != NV_LANG_DATA) {
                    $array_fname[] = $lang . '_cat_abintrotext';
                    $array_fname[] = $lang . '_cat_abkeywords';
                    $array_fname[] = $lang . '_cat_mvintrotext';
                    $array_fname[] = $lang . '_cat_mvkeywords';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                    $array_fvalue[] = '';
                }
            }
            $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
            $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

            $cat_code = Categories::creatUniqueCode();

            $sql = "INSERT INTO " . Resources::getTablePrefix() . "_categories (
                cat_code, resource_avatar, resource_cover, resource_video, time_add, show_inalbum, show_invideo, weight, status,
                " . NV_LANG_DATA . "_cat_name, " . NV_LANG_DATA . "_cat_alias,
                " . NV_LANG_DATA . "_cat_absitetitle, " . NV_LANG_DATA . "_cat_abintrotext, " . NV_LANG_DATA . "_cat_abkeywords,
                " . NV_LANG_DATA . "_cat_mvsitetitle, " . NV_LANG_DATA . "_cat_mvintrotext, " . NV_LANG_DATA . "_cat_mvkeywords" . $array_fname . "
            ) VALUES (
                :cat_code, :resource_avatar, :resource_cover, :resource_video, " . NV_CURRENTTIME . ", " . $array['show_inalbum'] . ", " . $array['show_invideo'] . ",
                " . $weight . ", 1, :cat_name, :cat_alias,
                :cat_absitetitle, :cat_abintrotext, :cat_abkeywords,
                :cat_mvsitetitle, :cat_mvintrotext, :cat_mvkeywords" . $array_fvalue . "
            )";
            try {
                $sth = $db->prepare($sql);
                $sth->bindParam(':cat_code', $cat_code, PDO::PARAM_STR);
                $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
                $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
                $sth->bindParam(':resource_video', $array['resource_video'], PDO::PARAM_STR);
                $sth->bindParam(':cat_name', $array['cat_name'], PDO::PARAM_STR);
                $sth->bindParam(':cat_alias', $array['cat_alias'], PDO::PARAM_STR);
                $sth->bindParam(':cat_absitetitle', $array['cat_absitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_abintrotext', $array['cat_abintrotext'], PDO::PARAM_STR, strlen($array['cat_abintrotext']));
                $sth->bindParam(':cat_abkeywords', $array['cat_abkeywords'], PDO::PARAM_STR, strlen($array['cat_abkeywords']));
                $sth->bindParam(':cat_mvsitetitle', $array['cat_mvsitetitle'], PDO::PARAM_STR);
                $sth->bindParam(':cat_mvintrotext', $array['cat_mvintrotext'], PDO::PARAM_STR, strlen($array['cat_mvintrotext']));
                $sth->bindParam(':cat_mvkeywords', $array['cat_mvkeywords'], PDO::PARAM_STR, strlen($array['cat_mvkeywords']));
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_CAT', $array['cat_name'], $admin_info['userid']);
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
$xtpl->assign('MAX_WEIGHT', sizeof($global_array_cat));
$xtpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);

$resource_avatar_path = msGetCurrentUploadFolder('song');
$resource_cover_path = msGetCurrentUploadFolder('album');
$resource_video_path = msGetCurrentUploadFolder('video');

$xtpl->assign('RESOURCE_AVATAR_PATH', $resource_avatar_path[0]);
$xtpl->assign('RESOURCE_AVATAR_CURRPATH', $resource_avatar_path[1]);
$xtpl->assign('RESOURCE_COVER_PATH', $resource_cover_path[0]);
$xtpl->assign('RESOURCE_COVER_CURRPATH', $resource_cover_path[1]);
$xtpl->assign('RESOURCE_VIDEO_PATH', $resource_video_path[0]);
$xtpl->assign('RESOURCE_VIDEO_CURRPATH', $resource_video_path[1]);

foreach ($global_array_cat as $row) {
    $row['time_add_time'] = nv_date('H:i', $row['time_add']);
    $row['time_update_time'] = $row['time_update'] ? nv_date('H:i', $row['time_update']) : '';
    $row['time_add'] = Utils::getFormatDateView($row['time_add']);
    $row['time_update'] = $row['time_update'] ? Utils::getFormatDateView($row['time_update']) : '';
    $row['show_inalbum'] = $row['show_inalbum'] ? ' checked="checked"' : '';
    $row['show_invideo'] = $row['show_invideo'] ? ' checked="checked"' : '';
    $row['status'] = $row['status'] ? ' checked="checked"' : '';
    $row['stat_albums'] = Utils::getFormatNumberView($row['stat_albums']);
    $row['stat_songs'] = Utils::getFormatNumberView($row['stat_songs']);
    $row['stat_videos'] = Utils::getFormatNumberView($row['stat_videos']);

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
