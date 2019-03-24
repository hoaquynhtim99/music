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

//use NukeViet\Music\Shared\videos;
use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Utils;

$set_active_op = 'video-list';

$video_id = $nv_Request->get_int('video_id', 'get', 0);

if ($video_id) {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;video_id=' . $video_id;
    $page_title = $lang_module['video_edit'];

    $array_select_fields = nv_get_video_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE video_id=" . $video_id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_ADMIN_MOD_FULLLINK . 'video-list');
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
    $page_title = $lang_module['video_add'];
    $array = $array_old = [];
    $array['video_code'] = '';
    $array['cat_ids'] = [];
    $array['singer_ids'] = [];
    $array['author_ids'] = [];
    $array['song_id'] = 0;
    $array['resource_avatar'] = '';
    $array['resource_cover'] = '';
    $array['show_inhome'] = 1;
    $array['video_name'] = '';
    $array['video_alias'] = '';
    $array['video_introtext'] = '';
    $array['video_keywords'] = '';
}

if ($nv_Request->isset_request('submit', 'post')) {
    AjaxRespon::reset();

    $array['video_type'] = $nv_Request->get_int('video_type', 'post', 0);
    $array['video_birthday'] = $nv_Request->get_title('video_birthday', 'post', '');
    $array['video_birthday_lev'] = $nv_Request->get_int('video_birthday_lev', 'post', 0);
    $array['nation_id'] = $nv_Request->get_int('nation_id', 'post', 0);
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['show_inhome'] = (int)$nv_Request->get_bool('show_inhome', 'post', false);
    $array['video_name'] = nv_substr($nv_Request->get_title('video_name', 'post', ''), 0, 250);
    $array['video_alias'] = nv_substr($nv_Request->get_title('video_alias', 'post', ''), 0, 250);
    $array['video_realname'] = nv_substr($nv_Request->get_title('video_realname', 'post', ''), 0, 255);
    $array['video_hometown'] = nv_substr($nv_Request->get_title('video_hometown', 'post', ''), 0, 255);
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
    if (!isset($global_array_video_type[$array['video_type']])) {
        $array['video_type'] = current(array_keys($global_array_video_type));
    }
    if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $array['video_birthday'], $m)) {
        $array['video_birthday'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $array['video_birthday'] = 0;
    }
    if ($array['video_birthday_lev'] < 0 or $array['video_birthday_lev'] > 3) {
        $array['video_birthday_lev'] = 0;
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
    $array['video_alias'] = empty($array['video_alias']) ? change_alias($array['video_name']) : change_alias($array['video_alias']);
    $array['singer_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['singer_keywords'], ''))));
    $array['author_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['author_keywords'], ''))));

    // Kiểm tra thông tin
    if (empty($array['video_name'])) {
        AjaxRespon::setInput('video_name')->setMessage($lang_module['error_require_field'])->respon();
    }

    // Chuyển một số thông tin để lưu vào CSDL
    $array['video_alphabet'] = Utils::getAlphabet($array['video_name']);
    $array['video_searchkey'] = Utils::getSearchKey($array['video_name']);
    $array['author_prize'] = nv_nl2br($array['author_prize']);
    $array['author_introtext'] = nv_nl2br($array['author_introtext']);
    $array['author_info'] = nv_editor_nl2br($array['author_info']);
    $array['singer_prize'] = nv_nl2br($array['singer_prize']);
    $array['singer_introtext'] = nv_nl2br($array['singer_introtext']);
    $array['singer_info'] = nv_editor_nl2br($array['singer_info']);

    $check_db = '';

    // Lưu dữ liệu
    if ($video_id) {
        // Sửa
        $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET
            video_type=" . $array['video_type'] . ",
            video_birthday=" . $array['video_birthday'] . ",
            video_birthday_lev=" . $array['video_birthday_lev'] . ",
            nation_id=" . $array['nation_id'] . ",
            resource_avatar=:resource_avatar,
            resource_cover=:resource_cover,
            show_inhome=" . $array['show_inhome'] . ",
            " . NV_LANG_DATA . "_video_name=:video_name,
            " . NV_LANG_DATA . "_video_alias=:video_alias,
            " . NV_LANG_DATA . "_video_alphabet=:video_alphabet,
            " . NV_LANG_DATA . "_video_searchkey=:video_searchkey,
            " . NV_LANG_DATA . "_singer_nickname=:singer_nickname,
            " . NV_LANG_DATA . "_author_nickname=:author_nickname,
            " . NV_LANG_DATA . "_author_prize=:author_prize,
            " . NV_LANG_DATA . "_author_info=:author_info,
            " . NV_LANG_DATA . "_author_introtext=:author_introtext,
            " . NV_LANG_DATA . "_author_keywords=:author_keywords,
            " . NV_LANG_DATA . "_video_realname=:video_realname,
            " . NV_LANG_DATA . "_video_hometown=:video_hometown,
            " . NV_LANG_DATA . "_singer_prize=:singer_prize,
            " . NV_LANG_DATA . "_singer_info=:singer_info,
            " . NV_LANG_DATA . "_singer_introtext=:singer_introtext,
            " . NV_LANG_DATA . "_singer_keywords=:singer_keywords,
            time_update=" . NV_CURRENTTIME . "
        WHERE video_id=" . $video_id;

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':video_name', $array['video_name'], PDO::PARAM_STR);
            $sth->bindParam(':video_alias', $array['video_alias'], PDO::PARAM_STR);
            $sth->bindParam(':video_alphabet', $array['video_alphabet'], PDO::PARAM_STR);
            $sth->bindParam(':video_searchkey', $array['video_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':singer_nickname', $array['singer_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_nickname', $array['author_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_prize', $array['author_prize'], PDO::PARAM_STR, strlen($array['author_prize']));
            $sth->bindParam(':author_info', $array['author_info'], PDO::PARAM_STR, strlen($array['author_info']));
            $sth->bindParam(':author_introtext', $array['author_introtext'], PDO::PARAM_STR, strlen($array['author_introtext']));
            $sth->bindParam(':author_keywords', $array['author_keywords'], PDO::PARAM_STR, strlen($array['author_keywords']));
            $sth->bindParam(':video_realname', $array['video_realname'], PDO::PARAM_STR);
            $sth->bindParam(':video_hometown', $array['video_hometown'], PDO::PARAM_STR);
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

        $video_code = videos::creatUniqueCode();

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_videos (
            video_code, video_type, video_birthday, video_birthday_lev, nation_id, resource_avatar, resource_cover, time_add, show_inhome, status,
            " . NV_LANG_DATA . "_video_name, " . NV_LANG_DATA . "_video_alias, " . NV_LANG_DATA . "_video_alphabet, " . NV_LANG_DATA . "_video_searchkey,
            " . NV_LANG_DATA . "_singer_nickname, " . NV_LANG_DATA . "_author_nickname, " . NV_LANG_DATA . "_author_prize, " . NV_LANG_DATA . "_author_info,
            " . NV_LANG_DATA . "_author_introtext, " . NV_LANG_DATA . "_author_keywords, " . NV_LANG_DATA . "_video_realname, " . NV_LANG_DATA . "_video_hometown,
            " . NV_LANG_DATA . "_singer_prize, " . NV_LANG_DATA . "_singer_info, " . NV_LANG_DATA . "_singer_introtext, " . NV_LANG_DATA . "_singer_keywords" . $array_fname . "
        ) VALUES (
            :video_code, " . $array['video_type'] . ", " . $array['video_birthday'] . ", " . $array['video_birthday_lev'] . ",
            " . $array['nation_id'] . ", :resource_avatar, :resource_cover, " . NV_CURRENTTIME . ", " . $array['show_inhome'] . ", 1,
            :video_name, :video_alias, :video_alphabet, :video_searchkey, :singer_nickname, :author_nickname,
            :author_prize, :author_info, :author_introtext, :author_keywords, :video_realname, :video_hometown,
            :singer_prize, :singer_info, :singer_introtext, :singer_keywords" . $array_fvalue . "
        )";

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':video_code', $video_code, PDO::PARAM_STR);
            $sth->bindParam(':video_name', $array['video_name'], PDO::PARAM_STR);
            $sth->bindParam(':video_alias', $array['video_alias'], PDO::PARAM_STR);
            $sth->bindParam(':video_alphabet', $array['video_alphabet'], PDO::PARAM_STR);
            $sth->bindParam(':video_searchkey', $array['video_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':singer_nickname', $array['singer_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_nickname', $array['author_nickname'], PDO::PARAM_STR);
            $sth->bindParam(':author_prize', $array['author_prize'], PDO::PARAM_STR, strlen($array['author_prize']));
            $sth->bindParam(':author_info', $array['author_info'], PDO::PARAM_STR, strlen($array['author_info']));
            $sth->bindParam(':author_introtext', $array['author_introtext'], PDO::PARAM_STR, strlen($array['author_introtext']));
            $sth->bindParam(':author_keywords', $array['author_keywords'], PDO::PARAM_STR, strlen($array['author_keywords']));
            $sth->bindParam(':video_realname', $array['video_realname'], PDO::PARAM_STR);
            $sth->bindParam(':video_hometown', $array['video_hometown'], PDO::PARAM_STR);
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
    if ($video_id) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_video', $video_id . ':' . $array_old['video_name'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_video', $array['video_name'], $admin_info['userid']);
    }

    // Xóa cache
    $nv_Cache->delMod($module_name);

    // Chuyển về trang thêm mới
    $continue_add = ($nv_Request->get_int('submitcontinue', 'post', 0) and !$video_id);
    if ($continue_add) {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'video-content';
        AjaxRespon::set('redirectnow', true);
    } else {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'video-list';
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
$xtpl->assign('LINK_ADD_ARTIST_SINGER', NV_ADMIN_MOD_FULLLINK_AMP . 'artist-content&amp;artist_type=0');
$xtpl->assign('LINK_ADD_ARTIST_AUTHOR', NV_ADMIN_MOD_FULLLINK_AMP . 'artist-content&amp;artist_type=1');

$resource_avatar_path = msGetCurrentUploadFolder('video');
$resource_cover_path = msGetCurrentUploadFolder('video', 'cover');
$resource_data_path = msGetCurrentUploadFolder('data');

$xtpl->assign('RESOURCE_AVATAR_PATH', $resource_avatar_path[0]);
$xtpl->assign('RESOURCE_AVATAR_CURRPATH', $resource_avatar_path[1]);
$xtpl->assign('RESOURCE_COVER_PATH', $resource_cover_path[0]);
$xtpl->assign('RESOURCE_COVER_CURRPATH', $resource_cover_path[1]);
$xtpl->assign('RESOURCE_DATA_PATH', $resource_data_path[0]);
$xtpl->assign('RESOURCE_DATA_CURRPATH', $resource_data_path[1]);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$array['video_introtext'] = htmlspecialchars($array['video_introtext']);
$array['video_keywords'] = htmlspecialchars($array['video_keywords']);

$array['show_inhome'] = $array['show_inhome'] ? ' checked="checked"' : '';

if (!empty($array['resource_avatar']) and !nv_is_url($array['resource_avatar']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'];
}
if (!empty($array['resource_cover']) and !nv_is_url($array['resource_cover']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'];
}

$xtpl->assign('DATA', $array);

// Xuất danh mục
foreach ($global_array_cat as $cat) {
    $cat['selected'] = in_array($cat['cat_id'], $array['cat_ids']) ? ' selected="selected"' : '';
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
}

// Chất lượng video
foreach ($global_array_mvquality as $mvquality) {
    $mvquality['quality_name'] = $mvquality[NV_LANG_DATA . '_quality_name'];
    $xtpl->assign('MVQUALITY', $mvquality);
    $xtpl->parse('main.mvquality');
}

// Xuất các quốc gia để chọn ca sĩ nhạc sĩ
foreach ($global_array_nation as $nation) {
    $xtpl->assign('NATION', $nation->toArray());
    $xtpl->parse('main.nation');
}

// Nút lưu và tiếp tục chỉ có khi thêm mới
if (!$video_id) {
    $xtpl->parse('main.save_continue');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
