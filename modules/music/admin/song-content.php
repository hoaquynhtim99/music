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
use NukeViet\Music\Resources;
use NukeViet\Music\Utils;
use NukeViet\Music\Config;
use NukeViet\Music\Shared\Songs;

$set_active_op = 'song-list';

$song_id = $nv_Request->get_int('song_id', 'get', 0);

if ($song_id) {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op . '&amp;song_id=' . $song_id;
    $page_title = $lang_module['song_edit'];

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

    $row['cat_ids'] = Utils::arrayIntFromStrList($row['cat_ids']);
    $row['singer_ids'] = Utils::arrayIntFromStrList($row['singer_ids']);
    $row['author_ids'] = Utils::arrayIntFromStrList($row['author_ids']);

    $array = $array_old = $row;

    $array['song_introtext'] = nv_br2nl($array['song_introtext']);
} else {
    $form_action = NV_ADMIN_MOD_FULLLINK_AMP . $op;
    $page_title = $lang_module['song_add'];
    $array = $array_old = [];
    $array['song_code'] = '';
    $array['cat_ids'] = [];
    $array['singer_ids'] = [];
    $array['author_ids'] = [];
    $array['video_id'] = 0;
    $array['resource_avatar'] = '';
    $array['resource_cover'] = '';
    $array['show_inhome'] = 1;
    $array['song_name'] = '';
    $array['song_alias'] = '';
    $array['song_introtext'] = '';
    $array['song_keywords'] = '';

    $array_old['cat_ids'] = [];
    $array_old['singer_ids'] = [];
    $array_old['author_ids'] = [];
    $array_old['video_id'] = 0;
}

if ($nv_Request->isset_request('submit', 'post')) {
    AjaxRespon::reset();

    $array['cat_ids'] = $nv_Request->get_typed_array('cat_ids', 'post', 'int', []);
    $array['singer_ids'] = $nv_Request->get_typed_array('singer_ids', 'post', 'int', []);
    $array['author_ids'] = $nv_Request->get_typed_array('author_ids', 'post', 'int', []);
    $array['video_id'] = $nv_Request->get_int('video_id', 'post', 0);
    $array['resource_avatar'] = $nv_Request->get_title('resource_avatar', 'post', '');
    $array['resource_cover'] = $nv_Request->get_title('resource_cover', 'post', '');
    $array['show_inhome'] = (int)$nv_Request->get_bool('show_inhome', 'post', false);
    $array['song_name'] = nv_substr($nv_Request->get_title('song_name', 'post', ''), 0, 250);
    $array['song_alias'] = nv_substr($nv_Request->get_title('song_alias', 'post', ''), 0, 250);
    $array['song_introtext'] = $nv_Request->get_textarea('song_introtext', '', NV_ALLOWED_HTML_TAGS);
    $array['song_keywords'] = $nv_Request->get_textarea('song_keywords', '', NV_ALLOWED_HTML_TAGS);
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
    $array['song_alias'] = empty($array['song_alias']) ? change_alias($array['song_name']) : change_alias($array['song_alias']);
    $array['song_keywords'] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(nv_nl2br($array['song_keywords'], ''))));

    // Nghệ sĩ hợp lệ
    $array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'], $array['author_ids'])));
    $array_artists = [];
    if (!empty($array_artist_ids)) {
        $sql = "SELECT artist_id FROM " . Resources::getTablePrefix() . "_artists WHERE artist_id IN(" . implode(',', $array_artist_ids) . ")";
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

    // Video liên quan hợp lệ
    if ($array['video_id'] and !$db->query("SELECT video_id FROM " . Resources::getTablePrefix() . "_videos WHERE video_id=" . $array['video_id'])->fetchColumn()) {
        $array['video_id'] = 0;
    }

    // Các file bài hát tồn tại
    $resource_path = $array['resource_path'];
    $array['resource_path'] = [];
    foreach ($resource_path as $quality_id => $path) {
        if (isset($global_array_soquality[$quality_id])) {
            if (nv_is_file($path, NV_UPLOADS_DIR . '/' . $module_upload) === true) {
                $array['resource_path'][$quality_id] = [
                    'resource_path' => substr($path, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/')),
                    'resource_server_id' => 0
                ];
            } elseif (nv_is_url($path)) {
                $array['resource_path'][$quality_id] = [
                    'resource_path' => $path,
                    'resource_server_id' => -1
                ];
            }
        }
    }

    // Kiểm tra thông tin
    if (empty($array['cat_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['song_err_cats'])->respon();
    }
    if (empty($array['singer_ids'])) {
        AjaxRespon::setInput('')->setMessage($lang_module['song_err_singers'])->respon();
    }
    if (empty($array['song_name'])) {
        AjaxRespon::setInput('song_name')->setMessage($lang_module['error_require_field'])->respon();
    }

    // Chuyển một số thông tin để lưu vào CSDL
    $array['song_searchkey'] = Utils::getSearchKey($array['song_name']);
    $array['song_introtext'] = nv_nl2br($array['song_introtext']);

    $check_db = '';
    $new_song_id = 0;

    // Lưu dữ liệu
    if ($song_id) {
        // Sửa
        $sql = "UPDATE " . Resources::getTablePrefix() . "_songs SET
            cat_ids=" . $db->quote(implode(',', $array['cat_ids'])) . ",
            singer_ids=" . $db->quote(implode(',', $array['singer_ids'])) . ",
            author_ids=" . $db->quote(implode(',', $array['author_ids'])) . ",
            video_id=" . $array['video_id'] . ",
            resource_avatar=:resource_avatar,
            resource_cover=:resource_cover,
            show_inhome=" . $array['show_inhome'] . ",
            " . NV_LANG_DATA . "_song_name=:song_name,
            " . NV_LANG_DATA . "_song_alias=:song_alias,
            " . NV_LANG_DATA . "_song_searchkey=:song_searchkey,
            " . NV_LANG_DATA . "_song_introtext=:song_introtext,
            " . NV_LANG_DATA . "_song_keywords=:song_keywords,
            time_update=" . NV_CURRENTTIME . "
        WHERE song_id=" . $song_id;

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':resource_avatar', $array['resource_avatar'], PDO::PARAM_STR);
            $sth->bindParam(':resource_cover', $array['resource_cover'], PDO::PARAM_STR);
            $sth->bindParam(':song_name', $array['song_name'], PDO::PARAM_STR);
            $sth->bindParam(':song_alias', $array['song_alias'], PDO::PARAM_STR);
            $sth->bindParam(':song_searchkey', $array['song_searchkey'], PDO::PARAM_STR);
            $sth->bindParam(':song_introtext', $array['song_introtext'], PDO::PARAM_STR, strlen($array['song_introtext']));
            $sth->bindParam(':song_keywords', $array['song_keywords'], PDO::PARAM_STR, strlen($array['song_keywords']));

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
                $array_fname[] = $lang . '_song_introtext';
                $array_fname[] = $lang . '_song_keywords';
                $array_fvalue[] = '';
                $array_fvalue[] = '';
            }
        }
        $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
        $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

        $song_code = Songs::creatUniqueCode();

        $sql = "INSERT INTO " . Resources::getTablePrefix() . "_songs (
            song_code, cat_ids, singer_ids, author_ids, video_id, resource_avatar, resource_cover, uploader_id, uploader_name, time_add, is_official, show_inhome, status,
            " . NV_LANG_DATA . "_song_name, " . NV_LANG_DATA . "_song_alias, " . NV_LANG_DATA . "_song_searchkey, " . NV_LANG_DATA . "_song_introtext,
            " . NV_LANG_DATA . "_song_keywords" . $array_fname . "
        ) VALUES (
            :song_code, " . $db->quote(implode(',', $array['cat_ids'])) . ", " . $db->quote(implode(',', $array['singer_ids'])) . ",
            " . $db->quote(implode(',', $array['author_ids'])) . ", " . $array['video_id'] . ", :resource_avatar, :resource_cover,
            " . $admin_info['admin_id'] . ", " . $db->quote($admin_info['full_name']) . ", " . NV_CURRENTTIME . ", 1, " . $array['show_inhome'] . ", 1,
            :song_name, :song_alias, :song_searchkey, :song_introtext, :song_keywords" . $array_fvalue . "
        )";

        $data_insert = [];
        $data_insert['song_code'] = $song_code;
        $data_insert['resource_avatar'] = $array['resource_avatar'];
        $data_insert['resource_cover'] = $array['resource_cover'];
        $data_insert['song_name'] = $array['song_name'];
        $data_insert['song_alias'] = $array['song_alias'];
        $data_insert['song_searchkey'] = $array['song_searchkey'];
        $data_insert['song_introtext'] = $array['song_introtext'];
        $data_insert['song_keywords'] = $array['song_keywords'];

        $new_song_id = $db->insert_id($sql, 'song_id', $data_insert);

        if (empty($new_song_id)) {
            $check_db = $lang_module['error_save'];
        } else {
            $song_id = $new_song_id;
        }
    }

    if ($check_db !== '') {
        // Thất bại
        AjaxRespon::setMessage($check_db)->respon();
    }

    // Xóa các file bài hát và thêm lại
    $db->query("DELETE FROM " . Resources::getTablePrefix() . "_songs_data WHERE song_id=" . $song_id);
    foreach ($array['resource_path'] as $quality_id => $resource_path) {
        $db->query("INSERT INTO " . Resources::getTablePrefix() . "_songs_data (
            song_id, quality_id, resource_server_id, resource_path, resource_duration, status
        ) VALUES (
            " . $song_id . ", " . $quality_id . ", " . $resource_path['resource_server_id'] . ", " . $db->quote($resource_path['resource_path']) . ", 0, 1
        )");
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

    // Cập nhật random các bài hát
    msUpdateRandomSongs($diff1);
    msUpdateRandomSongs($diff2);

    // Set video liên quan của bài hát nếu chọn video liên quan
    if ($array_old['video_id'] != $array['video_id']) {
        // Set video liên quan có bài hát liên quan là bài hát này
        if ($array['video_id']) {
            $db->query("UPDATE " . Resources::getTablePrefix() . "_videos SET song_id=" . $song_id . " WHERE video_id=" . $array['video_id']);
        }

        // Dỡ bỏ bài hát liên quan của video trước đó
        if ($array_old['video_id']) {
            $db->query("UPDATE " . Resources::getTablePrefix() . "_videos SET song_id=0 WHERE video_id=" . $array_old['video_id']);
        }
    }

    // Ghi nhật ký
    if ($song_id and empty($new_song_id)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_SONG', $song_id . ':' . $array_old['song_name'], $admin_info['userid']);
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_SONG', $array['song_name'], $admin_info['userid']);
    }

    // Xóa cache
    $nv_Cache->delMod($module_name);

    // Chuyển về trang thêm mới
    $continue_add = ($nv_Request->get_int('submitcontinue', 'post', 0) and !$song_id);
    if ($continue_add) {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'song-content';
        AjaxRespon::set('redirectnow', true);
    } else {
        $redirect = NV_ADMIN_MOD_FULLLINK . 'song-list';
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

$resource_avatar_path = msGetCurrentUploadFolder('song');
$resource_cover_path = msGetCurrentUploadFolder('song', 'cover');
$resource_data_path = msGetCurrentUploadFolder('data');

$xtpl->assign('RESOURCE_AVATAR_PATH', $resource_avatar_path[0]);
$xtpl->assign('RESOURCE_AVATAR_CURRPATH', $resource_avatar_path[1]);
$xtpl->assign('RESOURCE_COVER_PATH', $resource_cover_path[0]);
$xtpl->assign('RESOURCE_COVER_CURRPATH', $resource_cover_path[1]);
$xtpl->assign('RESOURCE_DATA_PATH', $resource_data_path[0]);
$xtpl->assign('RESOURCE_DATA_CURRPATH', $resource_data_path[1]);

$array['song_introtext'] = htmlspecialchars($array['song_introtext']);
$array['song_keywords'] = htmlspecialchars($array['song_keywords']);

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
$data_video = [];
if (!empty($array['video_id'])) {
    $array_select_fields = nv_get_video_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_videos WHERE video_id=" . $array['video_id'];
    $data_video = $db->query($sql)->fetch();

    if (!empty($data_video)) {
        $data_video['singers'] = [];
        $data_video['singer_ids'] = explode(',', $data_video['singer_ids']);

        if (!empty($data_video['singer_ids'])) {
            $song_artist_ids = array_merge_recursive($song_artist_ids, $data_video['singer_ids']);
        }
    }
}

$array_artist_ids = array_filter(array_unique(array_merge_recursive($array['singer_ids'], $array['author_ids'], $song_artist_ids)));
$array_artists = nv_get_artists($array_artist_ids);

// Xuất thông tin video đã chọn
if (!empty($data_video)) {
    $xtpl->assign('VIDEO', $data_video);

    $singer_name = [];
    foreach ($data_video['singer_ids'] as $singer_id) {
        if (isset($array_artists[$singer_id])) {
            $singer_name[] = $array_artists[$singer_id]['artist_name'];
        }
    }
    $singer_name = implode(', ', $singer_name);
    // Ca sĩ dạng chuỗi
    if (!empty($singer_name)) {
        $xtpl->assign('VIDEO_SINGER', $singer_name);
    } else {
        $xtpl->assign('VIDEO_SINGER', Config::getUnknowSinger());
    }

    $xtpl->parse('main.video');
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

// Chất lượng bài hát, các file bài hát ứng theo chất lượng
$resource_paths = [];
if ($song_id) {
    $sql = "SELECT * FROM " . Resources::getTablePrefix() . "_songs_data WHERE song_id=" . $song_id;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if ($row['resource_server_id'] == 0) {
            $row['resource_path'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $row['resource_path'];
        }

        $resource_paths[$row['quality_id']] = [
            'resource_server_id' => $row['resource_server_id'],
            'resource_path' => $row['resource_path'],
            'resource_duration' => $row['resource_duration']
        ];
    }
}
foreach ($global_array_soquality as $soquality) {
    $soquality['quality_name'] = $soquality[NV_LANG_DATA . '_quality_name'];
    $xtpl->assign('SOQUALITY', $soquality);
    $xtpl->assign('RESOURCE_PATH', isset($resource_paths[$soquality['quality_id']]) ? $resource_paths[$soquality['quality_id']]['resource_path'] : '');
    $xtpl->parse('main.soquality');
}

// Xuất các quốc gia để chọn ca sĩ nhạc sĩ
foreach ($global_array_nation as $nation) {
    $xtpl->assign('NATION', $nation->toArray());
    $xtpl->parse('main.nation');
}

// Nút lưu và tiếp tục chỉ có khi thêm mới
if (!$song_id) {
    $xtpl->parse('main.save_continue');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
