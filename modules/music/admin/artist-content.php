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

use NukeViet\Music\Shared\Artists;
use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Resources;
use NukeViet\Music\Utils;

$set_active_op = 'artist-list';

$artist_id = $nv_Request->get_int('artist_id', 'get', 0);

if ($artist_id) {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;artist_id=' . $artist_id;
    $page_title = $lang_module['artist_edit'];

    $array_select_fields = nv_get_artist_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_artists WHERE artist_id=" . $artist_id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_ADMIN_MOD_FULLLINK . 'artist-list');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    $array = $array_old = $row;
    $array['author_prize'] = nv_br2nl($array['author_prize']);
    $array['author_introtext'] = nv_br2nl($array['author_introtext']);
    $array['author_info'] = nv_editor_br2nl($array['author_info']);
    $array['singer_prize'] = nv_br2nl($array['singer_prize']);
    $array['singer_introtext'] = nv_br2nl($array['singer_introtext']);
    $array['singer_info'] = nv_editor_br2nl($array['singer_info']);
} else {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op;
    $page_title = $lang_module['artist_add'];

    $array = $array_old = [];

    $array['artist_birthday'] = 0;
    $array['artist_birthday_lev'] = 0;
    $array['nation_id'] = 0;
    $array['show_inhome'] = 0;
    $array['author_prize'] = '';
    $array['author_introtext'] = '';
    $array['author_info'] = '';
    $array['singer_prize'] = '';
    $array['singer_introtext'] = '';
    $array['singer_info'] = '';
    $array_old['nation_id'] = 0;

    // Loại nghệ sĩ lấy từ URL
    $artist_type = $nv_Request->get_int('artist_type', 'get', 0);
    if (!isset($global_array_artist_type[$artist_type])) {
        $artist_type = 0;
    }
    $array['artist_type'] = $artist_type;
}

if ($nv_Request->isset_request('submitform', 'post')) {
    AjaxRespon::reset();

    $array['artist_type'] = $nv_Request->get_int('artist_type', 'post', 0);
    $array['artist_birthday'] = $nv_Request->get_title('artist_birthday', 'post', '');
    $array['artist_birthday_lev'] = $nv_Request->get_int('artist_birthday_lev', 'post', 0);
    $array['nation_id'] = $nv_Request->get_int('nation_id', 'post', 0);
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['show_inhome'] = (int)$nv_Request->get_bool('show_inhome', 'post', false);
    $array['artist_name'] = nv_substr($nv_Request->get_title('artist_name', 'post', ''), 0, 250);
    $array['artist_alias'] = nv_substr($nv_Request->get_title('artist_alias', 'post', ''), 0, 250);
    $array['artist_realname'] = nv_substr($nv_Request->get_title('artist_realname', 'post', ''), 0, 255);
    $array['artist_hometown'] = nv_substr($nv_Request->get_title('artist_hometown', 'post', ''), 0, 255);
    $array['singer_nickname'] = nv_substr($nv_Request->get_title('singer_nickname', 'post', ''), 0, 255);
    $array['singer_prize'] = $nv_Request->get_textarea('singer_prize', '', NV_ALLOWED_HTML_TAGS);
    $array['singer_introtext'] = $nv_Request->get_textarea('singer_introtext', '', NV_ALLOWED_HTML_TAGS);
    $array['singer_keywords'] = $nv_Request->get_textarea('singer_keywords', '', NV_ALLOWED_HTML_TAGS);
    $array['singer_info'] = $nv_Request->get_editor('singer_info', '', NV_ALLOWED_HTML_TAGS);
    $array['author_nickname'] = nv_substr($nv_Request->get_title('author_nickname', 'post', ''), 0, 255);
    $array['author_prize'] = $nv_Request->get_textarea('author_prize', '', NV_ALLOWED_HTML_TAGS);
    $array['author_introtext'] = $nv_Request->get_textarea('author_introtext', '', NV_ALLOWED_HTML_TAGS);
    $array['author_keywords'] = $nv_Request->get_textarea('author_keywords', '', NV_ALLOWED_HTML_TAGS);
    $array['author_info'] = $nv_Request->get_editor('author_info', '', NV_ALLOWED_HTML_TAGS);

    // Xử lý qua các thông tin
    if (!isset($global_array_artist_type[$array['artist_type']])) {
        $array['artist_type'] = current(array_keys($global_array_artist_type));
    }
    if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $array['artist_birthday'], $m)) {
        $array['artist_birthday'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $array['artist_birthday'] = 0;
    }
    if ($array['artist_birthday_lev'] < 0 or $array['artist_birthday_lev'] > 3) {
        $array['artist_birthday_lev'] = 0;
    }
    if (!empty($array['nation_id']) and !isset($global_array_nation[$array['nation_id']])) {
        $array['nation_id'] = current(array_keys($global_array_nation));
    }
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
    $array['artist_alias'] = empty($array['artist_alias']) ? change_alias($array['artist_name']) : change_alias($array['artist_alias']);
    $array['singer_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['singer_keywords'], ''))));
    $array['author_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['author_keywords'], ''))));

    // Kiểm tra thông tin
    if (empty($array['artist_name'])) {
        AjaxRespon::setInput('artist_name')->setMessage($lang_module['error_require_field'])->respon();
    }

    // Chuyển một số thông tin để lưu vào CSDL
    $array['artist_alphabet'] = Utils::getAlphabet($array['artist_name']);
    $array['artist_searchkey'] = Utils::getSearchKey($array['artist_name']);
    $array['author_prize'] = nv_nl2br($array['author_prize']);
    $array['author_introtext'] = nv_nl2br($array['author_introtext']);
    $array['author_info'] = nv_editor_nl2br($array['author_info']);
    $array['singer_prize'] = nv_nl2br($array['singer_prize']);
    $array['singer_introtext'] = nv_nl2br($array['singer_introtext']);
    $array['singer_info'] = nv_editor_nl2br($array['singer_info']);

    $check_db = '';

    // Lưu dữ liệu
    if ($artist_id) {
        // Sửa
        $sql = "UPDATE " . Resources::getTablePrefix() . "_artists SET
            artist_type=" . $array['artist_type'] . ",
            artist_birthday=" . $array['artist_birthday'] . ",
            artist_birthday_lev=" . $array['artist_birthday_lev'] . ",
            nation_id=" . $array['nation_id'] . ",
            resource_avatar=:resource_avatar,
            resource_cover=:resource_cover,
            show_inhome=" . $array['show_inhome'] . ",
            " . NV_LANG_DATA . "_artist_name=:artist_name,
            " . NV_LANG_DATA . "_artist_alias=:artist_alias,
            " . NV_LANG_DATA . "_artist_alphabet=:artist_alphabet,
            " . NV_LANG_DATA . "_artist_searchkey=:artist_searchkey,
            " . NV_LANG_DATA . "_singer_nickname=:singer_nickname,
            " . NV_LANG_DATA . "_author_nickname=:author_nickname,
            " . NV_LANG_DATA . "_author_prize=:author_prize,
            " . NV_LANG_DATA . "_author_info=:author_info,
            " . NV_LANG_DATA . "_author_introtext=:author_introtext,
            " . NV_LANG_DATA . "_author_keywords=:author_keywords,
            " . NV_LANG_DATA . "_artist_realname=:artist_realname,
            " . NV_LANG_DATA . "_artist_hometown=:artist_hometown,
            " . NV_LANG_DATA . "_singer_prize=:singer_prize,
            " . NV_LANG_DATA . "_singer_info=:singer_info,
            " . NV_LANG_DATA . "_singer_introtext=:singer_introtext,
            " . NV_LANG_DATA . "_singer_keywords=:singer_keywords,
            time_update=" . NV_CURRENTTIME . "
        WHERE artist_id=" . $artist_id;

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':artist_name', $array['artist_name'], PDO::PARAM_STR);
            $sth->bindParam(':artist_alias', $array['artist_alias'], PDO::PARAM_STR);
            $sth->bindParam(':artist_alphabet', $array['artist_alphabet'], PDO::PARAM_STR);
            $sth->bindParam(':artist_searchkey', $array['artist_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':singer_nickname', $array['singer_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_nickname', $array['author_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_prize', $array['author_prize'], PDO::PARAM_STR, strlen($array['author_prize']));
            $sth->bindParam(':author_info', $array['author_info'], PDO::PARAM_STR, strlen($array['author_info']));
            $sth->bindParam(':author_introtext', $array['author_introtext'], PDO::PARAM_STR, strlen($array['author_introtext']));
            $sth->bindParam(':author_keywords', $array['author_keywords'], PDO::PARAM_STR, strlen($array['author_keywords']));
            $sth->bindParam(':artist_realname', $array['artist_realname'], PDO::PARAM_STR);
            $sth->bindParam(':artist_hometown', $array['artist_hometown'], PDO::PARAM_STR);
            $sth->bindParam(':singer_prize', $array['singer_prize'], PDO::PARAM_STR, strlen($array['singer_prize']));
            $sth->bindParam(':singer_info', $array['singer_info'], PDO::PARAM_STR, strlen($array['singer_info']));
            $sth->bindParam(':singer_introtext', $array['singer_introtext'], PDO::PARAM_STR, strlen($array['singer_introtext']));
            $sth->bindParam(':singer_keywords', $array['singer_keywords'], PDO::PARAM_STR, strlen($array['singer_keywords']));

            if (!$sth->execute()) {
                $check_db = $lang_module['error_save'];
            }
        } catch (PDOException $e) {
            $check_db = $lang_module['error_save'] . ' ' . $e->getMessage();
        }
    } else {
        // Thêm
        // Xác định các field theo ngôn ngữ không có dữ liệu
        $langs = msGetModuleSetupLangs();
        $array_fname = $array_fvalue = [];
        foreach ($langs as $lang) {
            if ($lang != NV_LANG_DATA) {
                $array_fname[] = $lang . '_author_prize';
                $array_fname[] = $lang . '_author_info';
                $array_fname[] = $lang . '_author_introtext';
                $array_fname[] = $lang . '_author_keywords';
                $array_fname[] = $lang . '_singer_prize';
                $array_fname[] = $lang . '_singer_info';
                $array_fname[] = $lang . '_singer_introtext';
                $array_fname[] = $lang . '_singer_keywords';
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

        $artist_code = Artists::creatUniqueCode();

        $sql = "INSERT INTO " . Resources::getTablePrefix() . "_artists (
            artist_code, artist_type, artist_birthday, artist_birthday_lev, nation_id, resource_avatar, resource_cover, time_add, show_inhome, status,
            " . NV_LANG_DATA . "_artist_name, " . NV_LANG_DATA . "_artist_alias, " . NV_LANG_DATA . "_artist_alphabet, " . NV_LANG_DATA . "_artist_searchkey,
            " . NV_LANG_DATA . "_singer_nickname, " . NV_LANG_DATA . "_author_nickname, " . NV_LANG_DATA . "_author_prize, " . NV_LANG_DATA . "_author_info,
            " . NV_LANG_DATA . "_author_introtext, " . NV_LANG_DATA . "_author_keywords, " . NV_LANG_DATA . "_artist_realname, " . NV_LANG_DATA . "_artist_hometown,
            " . NV_LANG_DATA . "_singer_prize, " . NV_LANG_DATA . "_singer_info, " . NV_LANG_DATA . "_singer_introtext, " . NV_LANG_DATA . "_singer_keywords" . $array_fname . "
        ) VALUES (
            :artist_code, " . $array['artist_type'] . ", " . $array['artist_birthday'] . ", " . $array['artist_birthday_lev'] . ",
            " . $array['nation_id'] . ", :resource_avatar, :resource_cover, " . NV_CURRENTTIME . ", " . $array['show_inhome'] . ", 1,
            :artist_name, :artist_alias, :artist_alphabet, :artist_searchkey, :singer_nickname, :author_nickname,
            :author_prize, :author_info, :author_introtext, :author_keywords, :artist_realname, :artist_hometown,
            :singer_prize, :singer_info, :singer_introtext, :singer_keywords" . $array_fvalue . "
        )";

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':artist_code', $artist_code, PDO::PARAM_STR);
            $sth->bindParam(':artist_name', $array['artist_name'], PDO::PARAM_STR);
            $sth->bindParam(':artist_alias', $array['artist_alias'], PDO::PARAM_STR);
            $sth->bindParam(':artist_alphabet', $array['artist_alphabet'], PDO::PARAM_STR);
            $sth->bindParam(':artist_searchkey', $array['artist_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':singer_nickname', $array['singer_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_nickname', $array['author_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_prize', $array['author_prize'], PDO::PARAM_STR, strlen($array['author_prize']));
            $sth->bindParam(':author_info', $array['author_info'], PDO::PARAM_STR, strlen($array['author_info']));
            $sth->bindParam(':author_introtext', $array['author_introtext'], PDO::PARAM_STR, strlen($array['author_introtext']));
            $sth->bindParam(':author_keywords', $array['author_keywords'], PDO::PARAM_STR, strlen($array['author_keywords']));
            $sth->bindParam(':artist_realname', $array['artist_realname'], PDO::PARAM_STR);
            $sth->bindParam(':artist_hometown', $array['artist_hometown'], PDO::PARAM_STR);
            $sth->bindParam(':singer_prize', $array['singer_prize'], PDO::PARAM_STR, strlen($array['singer_prize']));
            $sth->bindParam(':singer_info', $array['singer_info'], PDO::PARAM_STR, strlen($array['singer_info']));
            $sth->bindParam(':singer_introtext', $array['singer_introtext'], PDO::PARAM_STR, strlen($array['singer_introtext']));
            $sth->bindParam(':singer_keywords', $array['singer_keywords'], PDO::PARAM_STR, strlen($array['singer_keywords']));

            if (!$sth->execute()) {
                $check_db = $lang_module['error_save'];
            }
        } catch (PDOException $e) {
            $check_db = $lang_module['error_save'] . ' ' . $e->getMessage();
        }
    }

    if ($check_db !== '') {
        // Thất bại
        AjaxRespon::setMessage($check_db)->respon();
    }

    // Cập nhật lại thống kê quốc gia
    if ($array['nation_id'] != $array_old['nation_id']) {
        if ($array['nation_id']) {
            msUpdateNationStat($array['nation_id']);
        }
        if ($array_old['nation_id']) {
            msUpdateNationStat($array_old['nation_id']);
        }
    }

    // Ghi nhật ký
    if ($artist_id) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_ARTIST', $artist_id . ':' . $array_old['artist_name'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_ARTIST', $array['artist_name'], $admin_info['userid']);
    }

    // Xóa cache
    $nv_Cache->delMod($module_name);

    // Chuyển về trang thêm mới
    $continue_add = ($nv_Request->get_int('submitcontinue', 'post', 0) and !$artist_id);
    if ($continue_add) {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'artist-content';
        AjaxRespon::set('redirectnow', true);
    } else {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'artist-list';
    }

    AjaxRespon::setSuccess()->setMessage($lang_module['success_save'])->setRedirect($redirect)->respon();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
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

$resource_avatar_path = msGetCurrentUploadFolder('artist');
$resource_cover_path = msGetCurrentUploadFolder('artist', 'cover');

$xtpl->assign('RESOURCE_AVATAR_PATH', $resource_avatar_path[0]);
$xtpl->assign('RESOURCE_AVATAR_CURRPATH', $resource_avatar_path[1]);
$xtpl->assign('RESOURCE_COVER_PATH', $resource_cover_path[0]);
$xtpl->assign('RESOURCE_COVER_CURRPATH', $resource_cover_path[1]);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$array['author_prize'] = htmlspecialchars($array['author_prize']);
$array['author_introtext'] = htmlspecialchars($array['author_introtext']);
$array['author_info'] = htmlspecialchars($array['author_info']);
$array['singer_prize'] = htmlspecialchars($array['singer_prize']);
$array['singer_introtext'] = htmlspecialchars($array['singer_introtext']);
$array['singer_info'] = htmlspecialchars($array['singer_info']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['singer_info'] = nv_aleditor('singer_info', '100%', '300px', $array['singer_info']);
    $array['author_info'] = nv_aleditor('author_info', '100%', '300px', $array['author_info']);
} else {
    $array['singer_info'] = '<textarea class="form-control" style="width:100%;height:300px" name="singer_info">' . $array['singer_info'] . '</textarea>';
    $array['author_info'] = '<textarea class="form-control" style="width:100%;height:300px" name="author_info">' . $array['author_info'] . '</textarea>';
}

$array['artist_birthday'] = $array['artist_birthday'] ? nv_date('d-m-Y', $array['artist_birthday']) : '';
$array['show_inhome'] = $array['show_inhome'] ? ' checked="checked"' : '';

if (!empty($array['resource_avatar']) and !nv_is_url($array['resource_avatar']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'];
}
if (!empty($array['resource_cover']) and !nv_is_url($array['resource_cover']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'];
}

$xtpl->assign('DATA', $array);

// Xuất loại
foreach ($global_array_artist_type as $_key => $_val) {
    $artist_type = array(
        'key' => $_key,
        'title' => $_val,
        'selected' => $_key == $array['artist_type'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('ARTIST_TYPE', $artist_type);
    $xtpl->parse('main.artist_type');
}

// Hiển thị ngày sinh
for ($i = 0; $i <= 3; ++$i) {
    $artist_birthday_lev = array(
        'key' => $i,
        'title' => $lang_module['artist_birthday_lev' . $i],
        'selected' => $i == $array['artist_birthday_lev'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('ARTIST_BIRTHDAY_LEV', $artist_birthday_lev);
    $xtpl->parse('main.artist_birthday_lev');
}

// Quốc gia
foreach ($global_array_nation as $nation) {
    $nation = $nation->toArray();
    $nation['selected'] = $nation['nation_id'] == $array['nation_id'] ? ' selected="selected"' : '';
    $xtpl->assign('NATION', $nation);
    $xtpl->parse('main.nation');
}

// Nút lưu và tiếp tục chỉ có khi thêm mới
if (!$artist_id) {
    $xtpl->parse('main.save_continue');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
