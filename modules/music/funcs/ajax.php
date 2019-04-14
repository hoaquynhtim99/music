<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;

$tokend = $nv_Request->get_title('tokend', 'post', '');

// Load lời bài hát
if ($nv_Request->isset_request('getSongLyric', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    $sql = "SELECT " . NV_LANG_DATA . "_song_introtext lyric FROM " . NV_MOD_TABLE . "_songs WHERE song_code=:song_code AND status=1";
    $sth = $db->prepare($sql);
    $sth->bindParam(':song_code', $song_code, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount()) {
        $lyric = $sth->fetchColumn();
        if (empty($lyric)) {
            nv_htmlOutput(Config::getMsgNolyric());
        }
        nv_htmlOutput($lyric);
    }

    nv_htmlOutput('');
}

// Cập nhật số lượt chia sẻ bài hát
if ($nv_Request->isset_request('updateSongShares', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    // Xác định bài hát
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($song_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_htmlOutput('Song not exists!!!');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    $cookie_stat = $nv_Request->get_string($module_data . '_share_song', 'cookie', '');
    $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
    if (!is_array($cookie_stat)) {
        $cookie_stat = [];
    }
    $timeout = mktime(0, 0, 0, date("n", NV_CURRENTTIME), date("j", NV_CURRENTTIME), date("Y", NV_CURRENTTIME)); // Một ngày share một lần, qua ngày reset

    if (!isset($cookie_stat[$row['song_code']]) or $cookie_stat[$row['song_code']] < $timeout) {
        // Cập nhật số lượt chia sẻ
        $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_shares=stat_shares+1 WHERE song_id=" . $row['song_id'];
        $db->query($sql);

        // Thêm vào cookie
        $cookie_stat[$row['song_code']] = NV_CURRENTTIME;

        // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
        foreach ($cookie_stat as $_key => $_val) {
            if ($_val < $timeout) {
                unset($cookie_stat[$_key]);
            }
        }

        // Ghi lại cookie
        $nv_Request->set_Cookie($module_data . '_share_song', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
    }

    nv_htmlOutput("OK");
}

nv_redirect_location(NV_MOD_LINK);
