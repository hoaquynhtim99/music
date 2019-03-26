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

use NukeViet\Music\AjaxRespon;
use NukeViet\Music\Utils;
use NukeViet\Music\Config;
use NukeViet\Music\Shared\Videos;

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

    $row['cat_ids'] = Utils::arrayIntFromStrList($row['cat_ids']);
    $row['singer_ids'] = Utils::arrayIntFromStrList($row['singer_ids']);
    $row['author_ids'] = Utils::arrayIntFromStrList($row['author_ids']);

    $array = $array_old = $row;

    $array['video_introtext'] = nv_br2nl($array['video_introtext']);
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

    $array_old['cat_ids'] = [];
    $array_old['singer_ids'] = [];
    $array_old['author_ids'] = [];
    $array_old['song_id'] = 0;
}

if ($nv_Request->isset_request('submit', 'post')) {
    AjaxRespon::reset();

    $array['cat_ids'] = $nv_Request->get_typed_array('cat_ids', 'post', 'int', []);
    $array['singer_ids'] = $nv_Request->get_typed_array('singer_ids', 'post', 'int', []);
    $array['author_ids'] = $nv_Request->get_typed_array('author_ids', 'post', 'int', []);
    $array['song_id'] = $nv_Request->get_int('song_id', 'post', 0);
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['show_inhome'] = (int)$nv_Request->get_bool('show_inhome', 'post', false);
    $array['video_name'] = nv_substr($nv_Request->get_title('video_name', 'post', ''), 0, 250);
    $array['video_alias'] = nv_substr($nv_Request->get_title('video_alias', 'post', ''), 0, 250);
    $array['video_introtext'] = $nv_Request->get_textarea('video_introtext', '', NV_ALLOWED_HTML_TAGS);
    $array['video_keywords'] = $nv_Request->get_textarea('video_keywords', '', NV_ALLOWED_HTML_TAGS);
    $array['resource_path'] = $nv_Request->get_typed_array('resource_path', 'post', 'title', []);

    // Xử lý qua các thông tin
    $array['cat_ids'] = array_intersect($array['cat_ids'], array_keys($global_array_cat));
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
    $array['video_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['video_keywords'], ''))));

    // Nghệ sĩ hợp lệ
    $array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'], $array['author_ids'])));
    $array_artists = [];
    if (!empty($array_artist_ids)) {
        $sql = "SELECT artist_id FROM " . NV_MOD_TABLE . "_artists WHERE artist_id IN(" . implode(',', $array_artist_ids) . ")";
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $array_artists[$row['artist_id']] = $row['artist_id'];
        }
    }

    $singer_ids = $array['singer_ids'];
    $author_ids = $array['author_ids'];
    $array['singer_ids'] = $array['author_ids'] = [];
    foreach ($singer_ids as $_id) {
        if (isset($array_artists[$_id])) {
            $array['singer_ids'][] = $_id;
        }
    }
    foreach ($author_ids as $_id) {
        if (isset($array_artists[$_id])) {
            $array['author_ids'][] = $_id;
        }
    }

    // Bài hát liên quan hợp lệ
    if ($array['song_id'] and !$db->query("SELECT song_id FROM " . NV_MOD_TABLE . "_songs WHERE song_id=" . $array['song_id'])->fetchColumn()) {
        $array['song_id'] = 0;
    }

    // Các file video tồn tại
    $resource_path = $array['resource_path'];
    $array['resource_path'] = [];
    foreach ($resource_path as $quality_id => $path) {
        if (isset($global_array_mvquality[$quality_id]) and nv_is_file($path, NV_UPLOADS_DIR . '/' . $module_upload) === true) {
            $array['resource_path'][$quality_id] = substr($path, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/'));
        }
    }

    // Kiểm tra thông tin
    if (empty($array['cat_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['video_err_cats'])->respon();
    }
    if (empty($array['singer_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['video_err_singers'])->respon();
    }
    if (empty($array['video_name'])) {
        AjaxRespon::setInput('video_name')->setMessage($lang_module['error_require_field'])->respon();
    }

    // Chuyển một số thông tin để lưu vào CSDL
    $array['video_searchkey'] = Utils::getSearchKey($array['video_name']);
    $array['video_introtext'] = nv_nl2br($array['video_introtext']);

    $check_db = '';

    // Lưu dữ liệu
    if ($video_id) {
        // Sửa
        $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET
            cat_ids=" . $db->quote(implode(',', $array['cat_ids'])) . ",
            singer_ids=" . $db->quote(implode(',', $array['singer_ids'])) . ",
            author_ids=" . $db->quote(implode(',', $array['author_ids'])) . ",
            song_id=" . $array['song_id'] . ",
            resource_avatar=:resource_avatar,
            resource_cover=:resource_cover,
            show_inhome=" . $array['show_inhome'] . ",
            " . NV_LANG_DATA . "_video_name=:video_name,
            " . NV_LANG_DATA . "_video_alias=:video_alias,
            " . NV_LANG_DATA . "_video_searchkey=:video_searchkey,
            " . NV_LANG_DATA . "_video_introtext=:video_introtext,
            " . NV_LANG_DATA . "_video_keywords=:video_keywords,
            time_update=" . NV_CURRENTTIME . "
        WHERE video_id=" . $video_id;

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':video_name', $array['video_name'], PDO::PARAM_STR);
            $sth->bindParam(':video_alias', $array['video_alias'], PDO::PARAM_STR);
            $sth->bindParam(':video_searchkey', $array['video_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':video_introtext', $array['video_introtext'], PDO::PARAM_STR, strlen($array['video_introtext']));
            $sth->bindParam(':video_keywords', $array['video_keywords'], PDO::PARAM_STR, strlen($array['video_keywords']));

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
                $array_fname[] = $lang . '_video_introtext';
                $array_fname[] = $lang . '_video_keywords';
                $array_fvalue[] = '';
                $array_fvalue[] = '';
            }
        }
        $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
        $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

        $video_code = Videos::creatUniqueCode();

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_videos (
            video_code, cat_ids, singer_ids, author_ids, song_id, resource_avatar, resource_cover, uploader_id, uploader_name, time_add, is_official, show_inhome, status,
            " . NV_LANG_DATA . "_video_name, " . NV_LANG_DATA . "_video_alias, " . NV_LANG_DATA . "_video_searchkey, " . NV_LANG_DATA . "_video_introtext,
            " . NV_LANG_DATA . "_video_keywords" . $array_fname . "
        ) VALUES (
            :video_code, " . $db->quote(implode(',', $array['cat_ids'])) . ", " . $db->quote(implode(',', $array['singer_ids'])) . ",
            " . $db->quote(implode(',', $array['author_ids'])) . ", " . $array['song_id'] . ", :resource_avatar, :resource_cover,
            " . $admin_info['admin_id'] . ", " . $db->quote($admin_info['full_name']) . ", " . NV_CURRENTTIME . ", 1, " . $array['show_inhome'] . ", 1,
            :video_name, :video_alias, :video_searchkey, :video_introtext, :video_keywords" . $array_fvalue . "
        )";

        $data_insert = [];
        $data_insert['video_code'] = $video_code;
        $data_insert['resource_avatar'] = $array['resource_avatar'];
        $data_insert['resource_cover'] = $array['resource_cover'];
        $data_insert['video_name'] = $array['video_name'];
        $data_insert['video_alias'] = $array['video_alias'];
        $data_insert['video_searchkey'] = $array['video_searchkey'];
        $data_insert['video_introtext'] = $array['video_introtext'];
        $data_insert['video_keywords'] = $array['video_keywords'];

        $new_video_id = $db->insert_id($sql, 'video_id', $data_insert);

        if (empty($new_video_id)) {
            $check_db = $lang_module['error_save'];
        } else {
            $video_id = $new_video_id;
        }
    }

    if ($check_db !== '') {
        // Thất bại
        AjaxRespon::setMessage($check_db)->respon();
    }

    // Cập nhật lại thống kê ca sĩ, nhạc sĩ
    $diff1 = array_diff($array_old['singer_ids'], $array['singer_ids']);
    foreach ($diff1 as $_id) {
        msUpdateArtistStat($_id);
    }
    $diff2 = array_diff($array['singer_ids'], $array_old['singer_ids']);
    foreach ($diff2 as $_id) {
        msUpdateArtistStat($_id);
    }
    $diff3 = array_diff($array_old['author_ids'], $array['author_ids']);
    foreach ($diff3 as $_id) {
        msUpdateArtistStat($_id, false);
    }
    $diff4 = array_diff($array['author_ids'], $array_old['author_ids']);
    foreach ($diff4 as $_id) {
        msUpdateArtistStat($_id, false);
    }

    // Cập nhật lại thống kê thể loại
    $diff1 = array_diff($array_old['cat_ids'], $array['cat_ids']);
    foreach ($diff1 as $_id) {
        msUpdateCatStat($_id);
    }
    $diff2 = array_diff($array['cat_ids'], $array_old['cat_ids']);
    foreach ($diff2 as $_id) {
        msUpdateCatStat($_id);
    }

    // Set video liên quan của bài hát nếu chọn bài hát liên quan
    if ($array_old['song_id'] != $array['song_id']) {
        // Set bài hát liên quan có video liên quan là video này
        if ($array['song_id']) {
            $db->query("UPDATE " . NV_MOD_TABLE . "_songs SET video_id=" . $video_id . " WHERE song_id=" . $array['song_id']);
        }

        // Dỡ bỏ video liên quan của bài hát trước đó
        if ($array_old['song_id']) {
            $db->query("UPDATE " . NV_MOD_TABLE . "_songs SET video_id=0 WHERE song_id=" . $array_old['song_id']);
        }
    }

    // Ghi nhật ký
    if ($video_id) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_VIDEO', $video_id . ':' . $array_old['video_name'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_VIDEO', $array['video_name'], $admin_info['userid']);
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

// Lấy thông tin bài hát liên quan đã chọn
$song_artist_ids = [];
$data_song = [];
if (!empty($array['song_id'])) {
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE song_id=" . $array['song_id'];
    $data_song = $db->query($sql)->fetch();

    if (!empty($data_song)) {
        $data_song['singers'] = [];
        $data_song['singer_ids'] = explode(',', $data_song['singer_ids']);

        if (!empty($data_song['singer_ids'])) {
            $song_artist_ids = array_merge_recursive($song_artist_ids, $data_song['singer_ids']);
        }
    }
}

$array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'], $array['author_ids'], $song_artist_ids)));
$array_artists = nv_get_artists($array_artist_ids);

// Xuất thông tin bài hát đã chọn
if (!empty($data_song)) {
    $xtpl->assign('SONG', $data_song);

    $singer_name = [];
    foreach ($data_song['singer_ids'] as $singer_id) {
        if (isset($array_artists[$singer_id])) {
            $singer_name[] = $array_artists[$singer_id]['artist_name'];
        }
    }
    $singer_name = implode(', ', $singer_name);
    // Ca sĩ dạng chuỗi
    if (!empty($singer_name)) {
        $xtpl->assign('SONG_SINGER', $singer_name);
    } else {
        $xtpl->assign('SONG_SINGER', Config::getUnknowSinger());
    }

    $xtpl->parse('main.song');
}

// Xuất các ca sĩ đã chọn
foreach ($array['singer_ids'] as $singer_id) {
    if (isset($array_artists[$singer_id]))  {
        $xtpl->assign('SINGER', $array_artists[$singer_id]);
        $xtpl->parse('main.singer');
    }
}

// Xuất các nhạc sĩ đã chọn
foreach ($array['author_ids'] as $author_id) {
    if (isset($array_artists[$author_id]))  {
        $xtpl->assign('AUTHOR', $array_artists[$author_id]);
        $xtpl->parse('main.author');
    }
}

// Xuất thể loại
foreach ($global_array_cat as $cat) {
    $cat['selected'] = in_array($cat['cat_id'], $array['cat_ids']) ? ' selected="selected"' : '';
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
    $xtpl->parse('main.cat1');
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
