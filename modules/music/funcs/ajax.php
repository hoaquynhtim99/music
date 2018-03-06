<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC'))
    die('Stop!!!');

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
            nv_htmlOutput($global_array_config['msg_nolyric']);
        }
        nv_htmlOutput($lyric);
    }

    nv_htmlOutput('');
}

nv_redirect_location(NV_MOD_LINK);
