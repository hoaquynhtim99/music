<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Music\Resources;

$page_title = $nv_Lang->getModule('mymusic_playlist_manager');
$description = $key_words = 'no';

$nv_BotManager->setNoIndex()->setFollow();

// Yêu cầu đăng nhập thành viên
if (!defined('NV_IS_USER')) {
    $url = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($client_info['selfurl']), true);
    nv_redirect_location($url);
}

// Lấy playlist
$playlist_code = $nv_Request->get_title('code', 'get', '');
if (empty($playlist_code)) {
    nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['mymusic']);
}
$array_select_fields = nv_get_user_playlist_select_fields(true);

$sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . Resources::getTablePrefix() . "_user_playlists WHERE playlist_code=:playlist_code";
$sth = $db->prepare($sql);
$sth->bindParam(':playlist_code', $playlist_code, PDO::PARAM_STR);
$sth->execute();

if ($sth->rowCount() != 1) {
    nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['mymusic']);
}

$array_playlist = $sth->fetch();
foreach ($array_select_fields[1] as $f) {
    if (empty($array_playlist[$f]) and !empty($array_playlist['default_' . $f])) {
        $array_playlist[$f] = $array_playlist['default_' . $f];
    }
    unset($array_playlist['default_' . $f]);
}
$array_playlist['playlist_link'] = nv_get_detail_playlist_link($array_playlist);
$array_playlist['manager_link'] = Resources::getModFullLinkEncode() . $module_info['alias']['manager-playlist'] . '&amp;code=' . $array_playlist['playlist_code'];
$array_playlist['tokend'] = md5(NV_CHECK_SESSION . $array_playlist['playlist_code']);
$array_playlist['form_playlist_introtext'] = nv_htmlspecialchars($array_playlist['playlist_introtext']);

// Xóa playlist
if ($nv_Request->isset_request('delete', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');

    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    if ($tokend !== $array_playlist['tokend']) {
        $respon['message'] = 'Access Denied!!!';
        nv_jsonOutput($respon);
    }

    // Xóa playlist
    $sql = "DELETE FROM " . Resources::getTablePrefix() . "_user_playlists WHERE playlist_id=" . $array_playlist['playlist_id'];
    $db->query($sql);

    // Xóa các bài hát trong playlist
    $sql = "DELETE FROM " . Resources::getTablePrefix() . "_user_playlists_data WHERE playlist_id=" . $array_playlist['playlist_id'];
    $db->query($sql);

    $respon['status'] = 'SUCCESS';
    nv_jsonOutput($respon);
}

/*
 * Chỉnh sửa các thành phần của playlist
 * - Tiêu đề
 * - Mô tả
 */
if ($nv_Request->isset_request('updateField', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    $field = $nv_Request->get_title('field', 'post', '');
    $value = nv_substr($nv_Request->get_title('value', 'post', ''), 0, 250);

    $respon = [
        'status' => 'ERROR',
        'message' => '',
        'value' => ''
    ];
    $array_fields = [
        'title' => NV_LANG_DATA . '_playlist_name',
        'introtext' => NV_LANG_DATA . '_playlist_introtext',
    ];

    if ($tokend !== $array_playlist['tokend'] or !isset($array_fields[$field])) {
        $respon['message'] = 'Access Denied!!!';
        nv_jsonOutput($respon);
    }

    if (empty($value)) {
        $respon['message'] = $nv_Lang->getModule('error_value_empty');
        nv_jsonOutput($respon);
    }

    // Cập nhật
    $sql = "UPDATE " . Resources::getTablePrefix() . "_user_playlists SET " . $array_fields[$field] . "=:value, time_update=" . NV_CURRENTTIME . " WHERE playlist_id=" . $array_playlist['playlist_id'];
    $sth = $db->prepare($sql);
    $sth->bindParam(':value', $value, PDO::PARAM_STR);
    $sth->execute();

    $respon['value'] = $value;
    $respon['status'] = 'SUCCESS';
    nv_jsonOutput($respon);
}

$array_mod_title[] = [
    'catid' => 1,
    'title' => $module_info['funcs'][$module_info['alias']['mymusic']]['func_custom_name'],
    'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic']
];
$array_mod_title[] = [
    'catid' => 2,
    'title' => $nv_Lang->getModule('mymusic_playlist'),
    'link' => Resources::getModFullLinkEncode() . $module_info['alias']['mymusic'] . '/playlist'
];
$array_mod_title[] = [
    'catid' => 3,
    'title' => $array_playlist['playlist_name'],
    'link' => $array_playlist['manager_link']
];

$contents = nv_theme_manager_playlist($array_playlist);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
