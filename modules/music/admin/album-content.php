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
use NukeViet\Music\Config;
use NukeViet\Music\Utils;
use NukeViet\Music\Shared\Albums;

$set_active_op = 'album-list';

$album_id = $nv_Request->get_int('album_id', 'get', 0);

if ($album_id) {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;album_id=' . $album_id;
    $page_title = $lang_module['album_edit'];

    $array_select_fields = nv_get_album_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_albums WHERE album_id=" . $album_id;
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_ADMIN_MOD_FULLLINK . 'album-list');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    $row['cat_ids'] = Utils::arrayIntFromStrList($row['cat_ids']);
    $row['singer_ids'] = Utils::arrayIntFromStrList($row['singer_ids']);

    // Lấy các bài hát
    $row['song_ids'] = [];
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_albums_data WHERE album_id=" . $album_id . " ORDER BY weight ASC";
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $row['song_ids'][] = $_row['song_id'];
    }

    $array = $array_old = $row;

    $array['album_introtext'] = nv_br2nl($array['album_introtext']);
    $array['album_description'] = nv_editor_br2nl($array['album_description']);
} else {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op;
    $page_title = $lang_module['album_add'];
    $array = $array_old = [];
    $array['album_code'] = '';
    $array['cat_ids'] = [];
    $array['singer_ids'] = [];
    $array['release_year'] = 0;
    $array['resource_avatar'] = '';
    $array['resource_cover'] = '';
    $array['show_inhome'] = 1;
    $array['album_name'] = '';
    $array['album_alias'] = '';
    $array['album_introtext'] = '';
    $array['album_description'] = '';
    $array['album_keywords'] = '';
    $array['song_ids'] = [];

    $array_old['cat_ids'] = [];
    $array_old['singer_ids'] = [];
    $array_old['release_year'] = 0;
    $array_old['song_ids'] = [];
}

if ($nv_Request->isset_request('submit', 'post')) {
    AjaxRespon::reset();

    $array['cat_ids'] = $nv_Request->get_typed_array('cat_ids', 'post', 'int', []);
    $array['singer_ids'] = $nv_Request->get_typed_array('singer_ids', 'post', 'int', []);
    $array['release_year'] = $nv_Request->get_int('release_year', 'post', 0);
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['show_inhome'] = (int)$nv_Request->get_bool('show_inhome', 'post', false);
    $array['album_name'] = nv_substr($nv_Request->get_title('album_name', 'post', ''), 0, 250);
    $array['album_alias'] = nv_substr($nv_Request->get_title('album_alias', 'post', ''), 0, 250);
    $array['album_introtext'] = $nv_Request->get_textarea('album_introtext', '', NV_ALLOWED_HTML_TAGS);
    $array['album_description'] = $nv_Request->get_editor('album_description', '', NV_ALLOWED_HTML_TAGS);
    $array['album_keywords'] = $nv_Request->get_textarea('album_keywords', '', NV_ALLOWED_HTML_TAGS);
    $array['song_ids'] = $nv_Request->get_typed_array('song_ids', 'post', 'int', []);

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
    $array['album_alias'] = empty($array['album_alias']) ? change_alias($array['album_name']) : change_alias($array['album_alias']);
    $array['album_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['album_keywords'], ''))));

    // Nghệ sĩ hợp lệ
    $array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'])));
    $array_artists = [];
    if (!empty($array_artist_ids)) {
        $sql = "SELECT artist_id FROM " . NV_MOD_TABLE . "_artists WHERE artist_id IN(" . implode(',', $array_artist_ids) . ")";
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $array_artists[$row['artist_id']] = $row['artist_id'];
        }
    }

    $singer_ids = $array['singer_ids'];
    $array['singer_ids'] = [];
    foreach ($singer_ids as $_id) {
        if (isset($array_artists[$_id])) {
            $array['singer_ids'][] = $_id;
        }
    }

    // Năm phát hành
    if ($array['release_year'] < 0 or $array['release_year'] > 9999) {
        $array['release_year'] = 0;
    }

    // Các bài hát trong album
    $song_ids = $array['song_ids'];
    $array['song_ids'] = [];
    foreach ($song_ids as $song_id) {
        if ($db->query("SELECT song_id FROM " . NV_MOD_TABLE . "_songs WHERE song_id=" . $song_id)->fetchColumn()) {
            $array['song_ids'][] = $song_id;
        }
    }

    // Kiểm tra thông tin
    if (empty($array['cat_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['album_err_cats'])->respon();
    }
    if (empty($array['singer_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['album_err_singers'])->respon();
    }
    if (empty($array['album_name'])) {
        AjaxRespon::setInput('album_name')->setMessage($lang_module['error_require_field'])->respon();
    }

    // Chuyển một số thông tin để lưu vào CSDL
    $array['album_searchkey'] = Utils::getSearchKey($array['album_name']);
    $array['album_introtext'] = nv_nl2br($array['album_introtext']);
    $array['album_description'] = nv_editor_nl2br($array['album_description']);

    $check_db = '';
    $new_album_id = 0;

    // Lưu dữ liệu
    if ($album_id) {
        // Sửa
        $sql = "UPDATE " . NV_MOD_TABLE . "_albums SET
            cat_ids=" . $db->quote(implode(',', $array['cat_ids'])) . ",
            singer_ids=" . $db->quote(implode(',', $array['singer_ids'])) . ",
            release_year=" . $array['release_year'] . ",
            resource_avatar=:resource_avatar,
            resource_cover=:resource_cover,
            show_inhome=" . $array['show_inhome'] . ",
            " . NV_LANG_DATA . "_album_name=:album_name,
            " . NV_LANG_DATA . "_album_alias=:album_alias,
            " . NV_LANG_DATA . "_album_searchkey=:album_searchkey,
            " . NV_LANG_DATA . "_album_introtext=:album_introtext,
            " . NV_LANG_DATA . "_album_description=:album_description,
            " . NV_LANG_DATA . "_album_keywords=:album_keywords,
            time_update=" . NV_CURRENTTIME . "
        WHERE album_id=" . $album_id;

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':album_name', $array['album_name'], PDO::PARAM_STR);
            $sth->bindParam(':album_alias', $array['album_alias'], PDO::PARAM_STR);
            $sth->bindParam(':album_searchkey', $array['album_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':album_introtext', $array['album_introtext'], PDO::PARAM_STR, strlen($array['album_introtext']));
            $sth->bindParam(':album_description', $array['album_description'], PDO::PARAM_STR, strlen($array['album_description']));
            $sth->bindParam(':album_keywords', $array['album_keywords'], PDO::PARAM_STR, strlen($array['album_keywords']));

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
                $array_fname[] = $lang . '_album_introtext';
                $array_fname[] = $lang . '_album_description';
                $array_fname[] = $lang . '_album_keywords';
                $array_fvalue[] = '';
                $array_fvalue[] = '';
                $array_fvalue[] = '';
            }
        }
        $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
        $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

        $album_code = Albums::creatUniqueCode();

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_albums (
            album_code, cat_ids, singer_ids, release_year, resource_avatar, resource_cover, uploader_id, uploader_name, time_add, is_official, show_inhome, status,
            " . NV_LANG_DATA . "_album_name, " . NV_LANG_DATA . "_album_alias, " . NV_LANG_DATA . "_album_searchkey, " . NV_LANG_DATA . "_album_introtext,
            " . NV_LANG_DATA . "_album_description, " . NV_LANG_DATA . "_album_keywords" . $array_fname . "
        ) VALUES (
            :album_code, " . $db->quote(implode(',', $array['cat_ids'])) . ", " . $db->quote(implode(',', $array['singer_ids'])) . ",
            " . $array['release_year'] . ", :resource_avatar, :resource_cover,
            " . $admin_info['admin_id'] . ", " . $db->quote($admin_info['full_name']) . ", " . NV_CURRENTTIME . ", 1, " . $array['show_inhome'] . ", 1,
            :album_name, :album_alias, :album_searchkey, :album_introtext, :album_description, :album_keywords" . $array_fvalue . "
        )";

        $data_insert = [];
        $data_insert['album_code'] = $album_code;
        $data_insert['resource_avatar'] = $array['resource_avatar'];
        $data_insert['resource_cover'] = $array['resource_cover'];
        $data_insert['album_name'] = $array['album_name'];
        $data_insert['album_alias'] = $array['album_alias'];
        $data_insert['album_searchkey'] = $array['album_searchkey'];
        $data_insert['album_introtext'] = $array['album_introtext'];
        $data_insert['album_description'] = $array['album_description'];
        $data_insert['album_keywords'] = $array['album_keywords'];

        $new_album_id = $db->insert_id($sql, 'album_id', $data_insert);

        if (empty($new_album_id)) {
            $check_db = $lang_module['error_save'];
        } else {
            $album_id = $new_album_id;
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

    // Cập nhật lại thống kê thể loại
    $diff1 = array_diff($array_old['cat_ids'], $array['cat_ids']);
    foreach ($diff1 as $_id) {
        msUpdateCatStat($_id);
    }
    $diff2 = array_diff($array['cat_ids'], $array_old['cat_ids']);
    foreach ($diff2 as $_id) {
        msUpdateCatStat($_id);
    }

    // Các bài hát trong album: Xóa hết thêm lại
    $db->query("DELETE FROM " . NV_MOD_TABLE . "_albums_data WHERE album_id=" . $album_id);
    $weight = 0;
    foreach ($array['song_ids'] as $song_id) {
        $weight++;
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_albums_data (album_id, song_id, weight, status) VALUES (
            " . $album_id . ", " . $song_id . ", " . $weight . ", 1
        )";
        $db->query($sql);
    }

    // Ghi nhật ký
    if ($album_id and empty($new_album_id)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_ALBUM', $album_id . ':' . $array_old['album_name'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_ALBUM', $array['album_name'], $admin_info['userid']);
    }

    // Xóa cache
    $nv_Cache->delMod($module_name);

    // Chuyển về trang thêm mới
    $continue_add = ($nv_Request->get_int('submitcontinue', 'post', 0) and !$album_id);
    if ($continue_add) {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'album-content';
        AjaxRespon::set('redirectnow', true);
    } else {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'album-list';
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

$resource_avatar_path = msGetCurrentUploadFolder('album');
$resource_cover_path = msGetCurrentUploadFolder('album', 'cover');
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

$array['album_introtext'] = htmlspecialchars($array['album_introtext']);
$array['album_keywords'] = htmlspecialchars($array['album_keywords']);
$array['album_description'] = htmlspecialchars($array['album_description']);

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['album_description'] = nv_aleditor('album_description', '100%', '300px', $array['album_description']);
} else {
    $array['album_description'] = '<textarea class="form-control" style="width:100%;height:300px" name="album_description">' . $array['album_description'] . '</textarea>';
}
$array['show_inhome'] = $array['show_inhome'] ? ' checked="checked"' : '';

$array['release_year'] = $array['release_year'] ? $array['release_year'] : '';

if (!empty($array['resource_avatar']) and !nv_is_url($array['resource_avatar']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_avatar'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_avatar'];
}
if (!empty($array['resource_cover']) and !nv_is_url($array['resource_cover']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $array['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'];
}

$xtpl->assign('DATA', $array);

// Lấy thông tin các bài hát của album
$song_artist_ids = [];
$data_songs = [];
if (!empty($array['song_ids'])) {
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE song_id IN(" . implode(',', $array['song_ids']) . ")";
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $_row['singers'] = [];
        $_row['singer_ids'] = explode(',', $_row['singer_ids']);

        if (!empty($_row['singer_ids'])) {
            $song_artist_ids = array_merge_recursive($song_artist_ids, $_row['singer_ids']);
        }

        $data_songs[$_row['song_id']] = $_row;
    }
}

$array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'], $song_artist_ids)));
$array_artists = nv_get_artists($array_artist_ids);

// Xuất các bài hát của album
foreach ($array['song_ids'] as $song_id) {
    if (isset($data_songs[$song_id])) {
        $data_song = $data_songs[$song_id];
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
}

// Xuất các ca sĩ đã chọn
foreach ($array['singer_ids'] as $singer_id) {
    if (isset($array_artists[$singer_id]))  {
        $xtpl->assign('SINGER', $array_artists[$singer_id]);
        $xtpl->parse('main.singer');
    }
}

// Xuất thể loại
foreach ($global_array_cat as $cat) {
    $cat['selected'] = in_array($cat['cat_id'], $array['cat_ids']) ? ' selected="selected"' : '';
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat');
    $xtpl->parse('main.cat1');
}

// Xuất các quốc gia để chọn ca sĩ nhạc sĩ
foreach ($global_array_nation as $nation) {
    $xtpl->assign('NATION', $nation->toArray());
    $xtpl->parse('main.nation');
}

// Nút lưu và tiếp tục chỉ có khi thêm mới
if (!$album_id) {
    $xtpl->parse('main.save_continue');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
