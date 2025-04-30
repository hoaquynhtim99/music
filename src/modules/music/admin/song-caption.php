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

use NukeViet\Music\Resources;

$set_active_op = 'song-list';
$resource_path = msGetCurrentUploadFolder('lyric');

$song_id = $nv_Request->get_int('song_id', 'get', 0);
if (empty($song_id)) {
    nv_redirect_location(NV_ADMIN_MOD_FULLLINK . 'song-list');
}

// Lấy thông tin bài hát
$array_select_fields = nv_get_song_select_fields(true);
$sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_songs WHERE song_id=" . $song_id;
$result = $db->query($sql);
$row = $result->fetch();
if (empty($row)) {
    nv_redirect_location(NV_ADMIN_MOD_FULLLINK . 'song-list');
}
foreach ($array_select_fields[1] as $f) {
    if (empty($row[$f]) and !empty($row['default_' . $f])) {
        $row[$f] = $row['default_' . $f];
    }
    unset($row['default_' . $f]);
}

// Lấy lời bài hát
$exists_db = false;
$sql = "SELECT * FROM " . Resources::getTablePrefix() . "_songs_caption WHERE song_id=" . $song_id . " AND caption_lang=" . $db->quote(NV_LANG_DATA);
$result = $db->query($sql);
if ($result->rowCount()) {
    $array = $result->fetch();
    $array['caption_data'] = nv_editor_br2nl($array['caption_data']);
    $exists_db = true;
} else {
    $array = [
        'caption_file' => '',
        'caption_pdf' => '',
        'caption_data' => ''
    ];
}

$form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;song_id=' . $song_id;
$page_title = $nv_Lang->getModule('mana_cc') . ': ' . $row['song_name'];
$error = '';

if ($nv_Request->isset_request('submitform', 'post')) {
    $array['caption_file'] = $nv_Request->get_title('caption_file', 'post', '');
    $array['caption_pdf'] = $nv_Request->get_title('caption_pdf', 'post', '');
    $array['caption_data'] = $nv_Request->get_editor('caption_data', '', NV_ALLOWED_HTML_TAGS);

    // Xử lý qua các thông tin
    if (!nv_is_url($array['caption_file']) and nv_is_file($array['caption_file'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['caption_file'] = substr($array['caption_file'], strlen(NV_BASE_SITEURL . $resource_path[0] . '/'));
    } elseif (!nv_is_url($array['caption_file'])) {
        $array['caption_file'] = '';
    }
    if (!nv_is_url($array['caption_pdf']) and nv_is_file($array['caption_pdf'], NV_UPLOADS_DIR . '/' . $module_upload) === true) {
        $array['caption_pdf'] = substr($array['caption_pdf'], strlen(NV_BASE_SITEURL . $resource_path[0] . '/'));
    } elseif (!nv_is_url($array['caption_pdf'])) {
        $array['caption_pdf'] = '';
    }
    $array['caption_data'] = nv_editor_nl2br($array['caption_data']);

    // Lưu dữ liệu
    if ($exists_db) {
        // Cập nhật lại
        $sql = "UPDATE " . Resources::getTablePrefix() . "_songs_caption SET
            caption_file=:caption_file,
            caption_pdf=:caption_pdf,
            caption_data=:caption_data
        WHERE song_id=" . $song_id . " AND caption_lang=" . $db->quote(NV_LANG_DATA);

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':caption_file', $array['caption_file'], PDO::PARAM_STR);
            $sth->bindParam(':caption_pdf', $array['caption_pdf'], PDO::PARAM_STR);
            $sth->bindParam(':caption_data', $array['caption_data'], PDO::PARAM_STR, strlen($array['caption_data']));

            if (!$sth->execute()) {
                $error = $nv_Lang->getModule('error_save');
            }
        } catch (PDOException $e) {
            $error = $nv_Lang->getModule('error_save') . ' ' . $e->getMessage();
        }
    } else {
        // Thêm mới
        $sql = "INSERT INTO " . Resources::getTablePrefix() . "_songs_caption (
            song_id, caption_lang, caption_file, caption_pdf, caption_data, is_default, weight, status
        ) VALUES (
            " . $song_id . ", " . $db->quote(NV_LANG_DATA) . ", :caption_file, :caption_pdf, :caption_data, 1, 1, 1
        )";

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':caption_file', $array['caption_file'], PDO::PARAM_STR);
            $sth->bindParam(':caption_pdf', $array['caption_pdf'], PDO::PARAM_STR);
            $sth->bindParam(':caption_data', $array['caption_data'], PDO::PARAM_STR, strlen($array['caption_data']));

            if (!$sth->execute()) {
                $error = $nv_Lang->getModule('error_save');
            }
        } catch (PDOException $e) {
            $error = $nv_Lang->getModule('error_save') . ' ' . $e->getMessage();
        }
    }

    if (empty($error)) {
        // Ghi nhật ký
        if ($exists_db) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_SONG_CAPTION', $song_id . ':' . $row['song_name'], $admin_info['userid']);
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_SONG_CAPTION', $song_id . ':' . $row['song_name'], $admin_info['userid']);
        }

        // Xóa cache
        $nv_Cache->delMod($module_name);

        nv_redirect_location(NV_ADMIN_MOD_FULLLINK . $op . '&song_id=' . $song_id);
    } else {
        $array['caption_data'] = nv_editor_nl2br($array['caption_data']);
    }
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$array['caption_data'] = htmlspecialchars($array['caption_data']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['caption_data'] = nv_aleditor('caption_data', '100%', '300px', $array['caption_data']);
} else {
    $array['caption_data'] = '<textarea class="form-control" style="width:100%;height:300px" name="caption_data">' . $array['caption_data'] . '</textarea>';
}

if (!empty($array['caption_file']) and !nv_is_url($array['caption_file']) and nv_is_file(NV_BASE_SITEURL . $resource_path[0] . '/' . $array['caption_file'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['caption_file'] = NV_BASE_SITEURL . $resource_path[0] . '/' . $array['caption_file'];
}
if (!empty($array['caption_pdf']) and !nv_is_url($array['caption_pdf']) and nv_is_file(NV_BASE_SITEURL . $resource_path[0] . '/' . $array['caption_pdf'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['caption_pdf'] = NV_BASE_SITEURL . $resource_path[0] . '/' . $array['caption_pdf'];
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG_DATA_NAME', $language_array[NV_LANG_DATA]['name']);
$xtpl->assign('DATA', $array);

$xtpl->assign('RESOURCE_PATH', $resource_path[0]);
$xtpl->assign('RESOURCE_CURRPATH', $resource_path[1]);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
