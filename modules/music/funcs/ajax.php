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
use NukeViet\Music\Shared\Charts;
use NukeViet\Music\Shared\UserPlaylists;

$tokend = $nv_Request->get_title('tokend', 'post,get', '');

// Load lời bài hát
if ($nv_Request->isset_request('getSongLyric', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    $respon = [
        'status' => 'ERROR',
        'message' => '',
        'caption_text' => '',
        'caption_file' => '',
        'caption_file_ext' => ''
    ];

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        $respon['message'] = 'Access Denied!!!';
        nv_jsonOutput($respon);
    }

    $sql = "SELECT song_id FROM " . NV_MOD_TABLE . "_songs WHERE song_code=:song_code AND status=1";
    $sth = $db->prepare($sql);
    $sth->bindParam(':song_code', $song_code, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->rowCount()) {
        $song_id = $sth->fetchColumn();
        if (empty($song_id)) {
            $respon['message'] = 'Song not found!!!';
            nv_jsonOutput($respon);
        }

        $db->sqlreset()->from(NV_MOD_TABLE . "_songs_caption")->where("song_id=" . $song_id . " AND status=1");
        $db->select("caption_pdf, caption_data")->order("weight ASC")->limit(1);
        $row = $db->query($db->sql())->fetch();

        $respon['status'] = 'SUCCESS';

        if (empty($row) or empty($row['caption_data'])) {
            $respon['message'] = Config::getMsgNolyric();
        }
        if (!empty($row)) {
            $respon['caption_text'] = $row['caption_data'];

            if (!empty($row['caption_pdf']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf'])) {
                $respon['caption_file'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf'];
                $respon['caption_file_ext'] = nv_getextension(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/lyric/' . $row['caption_pdf']);
            } elseif (!empty($row['caption_pdf']) and nv_is_url($row['caption_pdf'])) {
                $respon['caption_file'] = $row['caption_pdf'];
                $respon['caption_file_ext'] = '';
            }
        }

        nv_jsonOutput($respon);
    }

    $respon['message'] = 'No Data Input!!!';
    nv_jsonOutput($respon);
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
    $row['cat_ids'] = explode(',', $row['cat_ids']);

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

        // Cập nhật bảng xếp hạng
        if (Config::getChartActive()) {
            $is_in_chart = [];
            foreach ($global_array_cat_chart as $_tmp) {
                $check = array_intersect($_tmp['cat_ids'], $row['cat_ids']);
                if (!empty($check)) {
                    $is_in_chart[] = $_tmp['cat_id'];
                }
            }
            if (!empty($is_in_chart)) {
                $chart_time = Charts::getCurrentTime();
                $chart_week = Charts::getCurrentWeek();
                $chart_year = Charts::getCurrentYear();

                foreach ($is_in_chart as $id_cat_chart) {
                    try {
                        $sql = "UPDATE " . NV_MOD_TABLE . "_chart_tmps SET share_hits=share_hits+1, summary_scores=summary_scores+" . Config::getChartShareRate() . "
                        WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='song' AND object_id=" . $row['song_id'];
                        if (!$db->exec($sql)) {
                            // Cập nhật không có thì thêm mới
                            $sql = "INSERT INTO " . NV_MOD_TABLE . "_chart_tmps (
                                chart_week, chart_year, chart_time, cat_id, object_name, object_id, share_hits, summary_scores
                            ) VALUES (
                                " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                                'song', " . $row['song_id'] . ", 1, " . Config::getChartShareRate() . "
                            )";
                            $db->query($sql);
                        }
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));
                    }
                }
            }
        }

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

// Chức năng tải bài hát
if ($nv_Request->isset_request('getDownloadSongHtml', 'post,get') or $nv_Request->isset_request('downloadSong', 'get')) {
    $song_code = $nv_Request->get_title('song_code', 'post,get', '');

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

    // Lấy các file của bài hát
    $array_resource = [];
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_songs_data WHERE song_id=" . $row['song_id'];
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $_row['link_download'] = NV_MOD_FULLLINK_AMP . $op . '&amp;downloadSong=1&amp;song_code=' . $row['song_code'] . '&amp;tokend=' . $tokend . '&amp;quality=' . $_row['quality_id'] . '&amp;download=1';
        $array_resource[$_row['quality_id']] = $_row;
    }

    // Thực hiện tải xuống
    if ($nv_Request->isset_request('download', 'get'))  {
        $quality_id = $nv_Request->get_int('quality', 'get', 0);
        if (isset($array_resource[$quality_id])) {
            // Thống kê số lượt tải
            $cookie_stat = $nv_Request->get_string($module_data . '_stat_song_downloads', 'cookie', '');
            $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
            if (!is_array($cookie_stat)) {
                $cookie_stat = [];
            }
            $timeout = NV_CURRENTTIME - (60 * 60); // Đếm tăng mỗi 1 tiếng

            if (!isset($cookie_stat[$row['song_code']]) or $cookie_stat[$row['song_code']] < $timeout) {
                // Cập nhật thống kê bài hát
                $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_downloads=stat_downloads+1 WHERE song_id=" . $row['song_id'];
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
                $nv_Request->set_Cookie($module_data . '_stat_song_downloads', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
            }

            $resource = $array_resource[$quality_id];

            // Chuyển hướng nếu liên kết ngoài
            if ($resource['resource_server_id'] < 0) {
                nv_redirect_location($resource['resource_path']);
            }

            // Xác định các ca sĩ của bài hát.
            $array_singer_ids = $array_singers = [];
            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            // Xác định ca sĩ
            $array_singers = nv_get_artists($array_singer_ids);

            if (!empty($row['singer_ids'])) {
                foreach ($row['singer_ids'] as $singer_id) {
                    if (isset($array_singers[$singer_id])) {
                        $row['singers'][$singer_id] = $array_singers[$singer_id];
                    }
                }
            }

            // Từ ca sĩ lấy ra tên file đẹp
            $song_full_name = $row['song_name'];
            $song_full_singer = [];

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                foreach ($row['singers'] as $singer) {
                    $song_full_singer[] = $singer['artist_name'];
                }
            } elseif (!empty($row['singers'])) {
                foreach ($row['singers'] as $singer) {
                    $song_full_singer[] = $singer['artist_name'];
                }
            } else {
                $song_full_singer[] = Config::getUnknowSinger();
            }

            $full_file_name = nv_substr(change_alias($song_full_name . '-' . implode('-', $song_full_singer)), 0, 200) . '.' . strtolower(nv_getextension($resource['resource_path']));
            $file_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $resource['resource_path'];;
            $file_folder = dirname($file_path);

            $download = new NukeViet\Files\Download($file_path, $file_folder, $full_file_name, true, 0);
            $download->download_file();
            die();
        }

        nv_redirect_location(NV_MOD_LINK);
    }

    $contents = nv_theme_popover_download_song($row, $array_resource);

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Chức năng tải video
if ($nv_Request->isset_request('getDownloadVideoHtml', 'post,get') or $nv_Request->isset_request('downloadVideo', 'get')) {
    $video_code = $nv_Request->get_title('video_code', 'post,get', '');

    if ($tokend !== md5($video_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    // Xác định video
    $array_select_fields = nv_get_video_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE status=1 AND video_code=" . $db->quote($video_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        nv_htmlOutput('Video not exists!!!');
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }

    // Lấy các file của video
    $array_resource = [];
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_videos_data WHERE video_id=" . $row['video_id'];
    $result = $db->query($sql);
    while ($_row = $result->fetch()) {
        $_row['link_download'] = NV_MOD_FULLLINK_AMP . $op . '&amp;downloadVideo=1&amp;video_code=' . $row['video_code'] . '&amp;tokend=' . $tokend . '&amp;quality=' . $_row['quality_id'] . '&amp;download=1';
        $array_resource[$_row['quality_id']] = $_row;
    }

    // Thực hiện tải xuống
    if ($nv_Request->isset_request('download', 'get'))  {
        $quality_id = $nv_Request->get_int('quality', 'get', 0);
        if (isset($array_resource[$quality_id])) {
            // Thống kê số lượt tải
            $cookie_stat = $nv_Request->get_string($module_data . '_stat_video_downloads', 'cookie', '');
            $cookie_stat = empty($cookie_stat) ? [] : json_decode($cookie_stat, true);
            if (!is_array($cookie_stat)) {
                $cookie_stat = [];
            }
            $timeout = NV_CURRENTTIME - (60 * 60); // Đếm tăng mỗi 1 tiếng

            if (!isset($cookie_stat[$row['video_code']]) or $cookie_stat[$row['video_code']] < $timeout) {
                // Cập nhật thống kê video
                $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET stat_downloads=stat_downloads+1 WHERE video_id=" . $row['video_id'];
                $db->query($sql);

                // Thêm vào cookie
                $cookie_stat[$row['video_code']] = NV_CURRENTTIME;

                // Chỉnh lại Cookie (Xóa bớt các phần tử hết hạn)
                foreach ($cookie_stat as $_key => $_val) {
                    if ($_val < $timeout) {
                        unset($cookie_stat[$_key]);
                    }
                }

                // Ghi lại cookie
                $nv_Request->set_Cookie($module_data . '_stat_video_downloads', json_encode($cookie_stat), NV_LIVE_COOKIE_TIME);
            }

            $resource = $array_resource[$quality_id];

            // Chuyển hướng nếu liên kết ngoài
            if ($resource['resource_server_id'] < 0) {
                nv_redirect_location($resource['resource_path']);
            }

            // Xác định các ca sĩ của video.
            $array_singer_ids = $array_singers = [];
            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            // Xác định ca sĩ
            $array_singers = nv_get_artists($array_singer_ids);

            if (!empty($row['singer_ids'])) {
                foreach ($row['singer_ids'] as $singer_id) {
                    if (isset($array_singers[$singer_id])) {
                        $row['singers'][$singer_id] = $array_singers[$singer_id];
                    }
                }
            }

            // Từ ca sĩ lấy ra tên file đẹp
            $video_full_name = $row['video_name'];
            $video_full_singer = [];

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                foreach ($row['singers'] as $singer) {
                    $video_full_singer[] = $singer['artist_name'];
                }
            } elseif (!empty($row['singers'])) {
                foreach ($row['singers'] as $singer) {
                    $video_full_singer[] = $singer['artist_name'];
                }
            } else {
                $video_full_singer[] = Config::getUnknowSinger();
            }

            $full_file_name = nv_substr(change_alias($video_full_name . '-' . implode('-', $video_full_singer)), 0, 200) . '.' . strtolower(nv_getextension($resource['resource_path']));
            $file_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . Config::getUploadsFolder() . '/' . $resource['resource_path'];;
            $file_folder = dirname($file_path);

            $download = new NukeViet\Files\Download($file_path, $file_folder, $full_file_name, true, 0);
            $download->download_file();
            die();
        }

        nv_redirect_location(NV_MOD_LINK);
    }

    $contents = nv_theme_popover_download_video($row, $array_resource);

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

/*
 * Thêm, bỏ album khỏi danh sách yêu thích
 * Không có cache trong này
 */
if ($nv_Request->isset_request('updateUserFavoriteAlbum', 'post')) {
    $album_code = $nv_Request->get_title('album_code', 'post', '');

    if ($tokend !== md5($album_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    if (!defined('NV_IS_USER')) {
        $respon['message'] = $lang_module['error_not_login'];
        nv_jsonOutput($respon);
    }

    // Xác định album
    $array_select_fields = nv_get_album_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_albums WHERE status=1 AND album_code=" . $db->quote($album_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        $respon['message'] = $lang_module['error_album_notexists'];
        nv_jsonOutput($respon);
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }
    $row['cat_ids'] = explode(',', $row['cat_ids']);

    $respon['status'] = 'SUCCESS';

    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_user_favorite_albums WHERE userid=" . $user_info['userid'] . " AND album_id=" . $row['album_id'];
    $array_favorite = $db->query($sql)->fetch();
    if (empty($array_favorite)) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_user_favorite_albums (userid, album_id, time_add) VALUES (" . $user_info['userid'] . ", " . $row['album_id'] . "," . NV_CURRENTTIME . ")";
        $db->query($sql);
        $respon['favorited'] = true;

        /*
         * Cập nhật bảng xếp hạng
         * Chỉ cập nhật lúc thêm lần đầu tiên, việc bỏ đi rồi thêm lại
         * sẽ không được tính điểm nữa
         */
        if (Config::getChartActive()) {
            $is_in_chart = [];
            foreach ($global_array_cat_chart as $_tmp) {
                $check = array_intersect($_tmp['cat_ids'], $row['cat_ids']);
                if (!empty($check)) {
                    $is_in_chart[] = $_tmp['cat_id'];
                }
            }
            if (!empty($is_in_chart)) {
                $chart_time = Charts::getCurrentTime();
                $chart_week = Charts::getCurrentWeek();
                $chart_year = Charts::getCurrentYear();

                foreach ($is_in_chart as $id_cat_chart) {
                    try {
                        $sql = "UPDATE " . NV_MOD_TABLE . "_chart_tmps SET like_hits=like_hits+1, summary_scores=summary_scores+" . Config::getChartLikeRate() . "
                        WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='album' AND object_id=" . $row['album_id'];
                        if (!$db->exec($sql)) {
                            // Cập nhật không có thì thêm mới
                            $sql = "INSERT INTO " . NV_MOD_TABLE . "_chart_tmps (
                                chart_week, chart_year, chart_time, cat_id, object_name, object_id, like_hits, summary_scores
                            ) VALUES (
                                " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                                'album', " . $row['album_id'] . ", 1, " . Config::getChartLikeRate() . "
                            )";
                            $db->query($sql);
                        }
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));
                    }
                }
            }
        }
    } elseif (empty($array_favorite['is_removed'])) {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_albums SET is_removed=1, time_removed=" . NV_CURRENTTIME . " WHERE userid=" . $user_info['userid'] . " AND album_id=" . $row['album_id'];
        $db->query($sql);
        $respon['favorited'] = false;
    } else {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_albums SET is_removed=0, time_removed=0 WHERE userid=" . $user_info['userid'] . " AND album_id=" . $row['album_id'];
        $db->query($sql);
        $respon['favorited'] = true;
    }

    // Cập nhật số lượt LIKE của album
    try {
        if ($respon['favorited']) {
            $sql = "UPDATE " . NV_MOD_TABLE . "_albums SET stat_likes=stat_likes+1 WHERE album_id=" . $row['album_id'];
        } else {
            $sql = "UPDATE " . NV_MOD_TABLE . "_albums SET stat_likes=stat_likes-1 WHERE album_id=" . $row['album_id'];
        }
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error(print_r($e, true));
    }

    nv_jsonOutput($respon);
}

/*
 * Thêm, bỏ video khỏi danh sách yêu thích
 * Không có cache trong này
 */
if ($nv_Request->isset_request('updateUserFavoriteVideo', 'post')) {
    $video_code = $nv_Request->get_title('video_code', 'post', '');

    if ($tokend !== md5($video_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    if (!defined('NV_IS_USER')) {
        $respon['message'] = $lang_module['error_not_login'];
        nv_jsonOutput($respon);
    }

    // Xác định video
    $array_select_fields = nv_get_video_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_videos WHERE status=1 AND video_code=" . $db->quote($video_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        $respon['message'] = $lang_module['error_video_notexists'];
        nv_jsonOutput($respon);
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }
    $row['cat_ids'] = explode(',', $row['cat_ids']);

    $respon['status'] = 'SUCCESS';

    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_user_favorite_videos WHERE userid=" . $user_info['userid'] . " AND video_id=" . $row['video_id'];
    $array_favorite = $db->query($sql)->fetch();
    if (empty($array_favorite)) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_user_favorite_videos (userid, video_id, time_add) VALUES (" . $user_info['userid'] . ", " . $row['video_id'] . "," . NV_CURRENTTIME . ")";
        $db->query($sql);
        $respon['favorited'] = true;

        /*
         * Cập nhật bảng xếp hạng
         * Chỉ cập nhật lúc thêm lần đầu tiên, việc bỏ đi rồi thêm lại
         * sẽ không được tính điểm nữa
         */
        if (Config::getChartActive()) {
            $is_in_chart = [];
            foreach ($global_array_cat_chart as $_tmp) {
                $check = array_intersect($_tmp['cat_ids'], $row['cat_ids']);
                if (!empty($check)) {
                    $is_in_chart[] = $_tmp['cat_id'];
                }
            }
            if (!empty($is_in_chart)) {
                $chart_time = Charts::getCurrentTime();
                $chart_week = Charts::getCurrentWeek();
                $chart_year = Charts::getCurrentYear();

                foreach ($is_in_chart as $id_cat_chart) {
                    try {
                        $sql = "UPDATE " . NV_MOD_TABLE . "_chart_tmps SET like_hits=like_hits+1, summary_scores=summary_scores+" . Config::getChartLikeRate() . "
                        WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='video' AND object_id=" . $row['video_id'];
                        if (!$db->exec($sql)) {
                            // Cập nhật không có thì thêm mới
                            $sql = "INSERT INTO " . NV_MOD_TABLE . "_chart_tmps (
                                chart_week, chart_year, chart_time, cat_id, object_name, object_id, like_hits, summary_scores
                            ) VALUES (
                                " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                                'video', " . $row['video_id'] . ", 1, " . Config::getChartLikeRate() . "
                            )";
                            $db->query($sql);
                        }
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));
                    }
                }
            }
        }
    } elseif (empty($array_favorite['is_removed'])) {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_videos SET is_removed=1, time_removed=" . NV_CURRENTTIME . " WHERE userid=" . $user_info['userid'] . " AND video_id=" . $row['video_id'];
        $db->query($sql);
        $respon['favorited'] = false;
    } else {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_videos SET is_removed=0, time_removed=0 WHERE userid=" . $user_info['userid'] . " AND video_id=" . $row['video_id'];
        $db->query($sql);
        $respon['favorited'] = true;
    }

    // Cập nhật số lượt LIKE của video
    try {
        if ($respon['favorited']) {
            $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET stat_likes=stat_likes+1 WHERE video_id=" . $row['video_id'];
        } else {
            $sql = "UPDATE " . NV_MOD_TABLE . "_videos SET stat_likes=stat_likes-1 WHERE video_id=" . $row['video_id'];
        }
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error(print_r($e, true));
    }

    nv_jsonOutput($respon);
}

/*
 * Thêm, bỏ bài hát khỏi danh sách yêu thích
 * Không có cache trong này
 */
if ($nv_Request->isset_request('updateUserFavoriteSong', 'post')) {
    $song_code = $nv_Request->get_title('song_code', 'post', '');

    if ($tokend !== md5($song_code . NV_CHECK_SESSION)) {
        nv_htmlOutput('Access Denied!!!');
    }

    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    if (!defined('NV_IS_USER')) {
        $respon['message'] = $lang_module['error_not_login'];
        nv_jsonOutput($respon);
    }

    // Xác định bài hát
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($song_code);
    $result = $db->query($sql);
    $row = $result->fetch();
    if (empty($row)) {
        $respon['message'] = $lang_module['error_song_notexists'];
        nv_jsonOutput($respon);
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }
    $row['cat_ids'] = explode(',', $row['cat_ids']);

    $respon['status'] = 'SUCCESS';

    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_user_favorite_songs WHERE userid=" . $user_info['userid'] . " AND song_id=" . $row['song_id'];
    $array_favorite = $db->query($sql)->fetch();
    if (empty($array_favorite)) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_user_favorite_songs (userid, song_id, time_add) VALUES (" . $user_info['userid'] . ", " . $row['song_id'] . "," . NV_CURRENTTIME . ")";
        $db->query($sql);
        $respon['favorited'] = true;

        /*
         * Cập nhật bảng xếp hạng
         * Chỉ cập nhật lúc thêm lần đầu tiên, việc bỏ đi rồi thêm lại
         * sẽ không được tính điểm nữa
         */
        if (Config::getChartActive()) {
            $is_in_chart = [];
            foreach ($global_array_cat_chart as $_tmp) {
                $check = array_intersect($_tmp['cat_ids'], $row['cat_ids']);
                if (!empty($check)) {
                    $is_in_chart[] = $_tmp['cat_id'];
                }
            }
            if (!empty($is_in_chart)) {
                $chart_time = Charts::getCurrentTime();
                $chart_week = Charts::getCurrentWeek();
                $chart_year = Charts::getCurrentYear();

                foreach ($is_in_chart as $id_cat_chart) {
                    try {
                        $sql = "UPDATE " . NV_MOD_TABLE . "_chart_tmps SET like_hits=like_hits+1, summary_scores=summary_scores+" . Config::getChartLikeRate() . "
                        WHERE chart_week=" . $chart_week . " AND chart_year=" . $chart_year . " AND cat_id=" . $id_cat_chart . " AND object_name='song' AND object_id=" . $row['song_id'];
                        if (!$db->exec($sql)) {
                            // Cập nhật không có thì thêm mới
                            $sql = "INSERT INTO " . NV_MOD_TABLE . "_chart_tmps (
                                chart_week, chart_year, chart_time, cat_id, object_name, object_id, like_hits, summary_scores
                            ) VALUES (
                                " . $chart_week . ", " . $chart_year . ", " . $chart_time . ", " . $id_cat_chart . ",
                                'song', " . $row['song_id'] . ", 1, " . Config::getChartLikeRate() . "
                            )";
                            $db->query($sql);
                        }
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));
                    }
                }
            }
        }
    } elseif (empty($array_favorite['is_removed'])) {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_songs SET is_removed=1, time_removed=" . NV_CURRENTTIME . " WHERE userid=" . $user_info['userid'] . " AND song_id=" . $row['song_id'];
        $db->query($sql);
        $respon['favorited'] = false;
    } else {
        $sql = "UPDATE " . NV_MOD_TABLE . "_user_favorite_songs SET is_removed=0, time_removed=0 WHERE userid=" . $user_info['userid'] . " AND song_id=" . $row['song_id'];
        $db->query($sql);
        $respon['favorited'] = true;
    }

    // Cập nhật số lượt LIKE của bài hát
    try {
        if ($respon['favorited']) {
            $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_likes=stat_likes+1 WHERE song_id=" . $row['song_id'];
        } else {
            $sql = "UPDATE " . NV_MOD_TABLE . "_songs SET stat_likes=stat_likes-1 WHERE song_id=" . $row['song_id'];
        }
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error(print_r($e, true));
    }

    nv_jsonOutput($respon);
}

// Load HTML nội dung thêm bài hát vào playlist
if ($nv_Request->isset_request('getAddSongToPLHtml', 'post')) {
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
    $row['tokend'] = md5($row['song_code'] . NV_CHECK_SESSION);

    $redirect = nv_get_redirect('post');
    if (empty($redirect)) {
        $redirect = nv_redirect_encrypt(nv_url_rewrite(NV_MOD_LINK, true));
    }

    // Lấy danh sách các playlist của thành viên nếu đã đăng nhập
    $array_playlist = [];
    $array_playlist_added = [];

    if (defined('NV_IS_USER')) {
        $db->sqlreset()->from(NV_MOD_TABLE . "_user_playlists")->where("userid=" . $user_info['userid']);
        $db->order("time_add DESC");

        $array_select_fields = nv_get_user_playlist_select_fields();
        $db->select(implode(', ', $array_select_fields[0]));

        $result = $db->query($db->sql());
        while ($_row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($_row[$f]) and !empty($_row['default_' . $f])) {
                    $_row[$f] = $_row['default_' . $f];
                }
                unset($_row['default_' . $f]);
            }

            $array_playlist[$_row['playlist_id']] = $_row;
        }

        // Xác định xem bài hát này đã thêm vào các playlist trên hay chưa
        if (!empty($array_playlist)) {
            $sql = "SELECT playlist_id FROM " . NV_MOD_TABLE . "_user_playlists_data WHERE song_id=" . $row['song_id'] . " AND playlist_id IN(" . implode(',', array_keys($array_playlist)) . ")";
            $array_playlist_added = $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    $contents = nv_theme_popover_add_song_to_playlist($row, $array_playlist, $array_playlist_added, $redirect);

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Tạo mới playlist, thêm bài hát đã chọn tự động vào playlist đã chọn
if ($nv_Request->isset_request('creatNewPlaylist', 'post')) {
    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    // Kiểm tra đăng nhập
    if (!defined('NV_IS_USER')) {
        $respon['message'] = $lang_module['error_not_login'];
        nv_jsonOutput($respon);
    }

    // Lấy request
    $request = [];
    $request['playlist_name'] = nv_substr($nv_Request->get_title('playlist_name', 'post', ''), 0, 200);
    $request['privacy'] = $nv_Request->get_int('privacy', 'post', 0);
    if ($request['privacy'] < 0 or $request['privacy'] > 1) {
        $request['privacy'] = 0;
    }
    $request['auto_add_song'] = intval($nv_Request->get_bool('auto_add_song', 'post', false));
    $request['song_code'] = nv_substr($nv_Request->get_title('song_code', 'post', ''), 0, 200);

    if (empty($request['playlist_name'])) {
        $respon['message'] = $lang_module['pl_error_name_new'];
        nv_jsonOutput($respon);
    }
    if ($request['auto_add_song']) {
        if ($tokend !== md5($request['song_code'] . NV_CHECK_SESSION)) {
            $respon['message'] = 'Access Denied!!!';
            nv_jsonOutput($respon);
        }

        // Xác định thông tin bài hát
        $array_select_fields = nv_get_song_select_fields(true);
        $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($request['song_code']);
        $result = $db->query($sql);
        $row = $result->fetch();
        if (empty($row)) {
            $respon['message'] = $lang_module['error_song_notexists'];
            nv_jsonOutput($respon);
        }
        foreach ($array_select_fields[1] as $f) {
            if (empty($row[$f]) and !empty($row['default_' . $f])) {
                $row[$f] = $row['default_' . $f];
            }
            unset($row['default_' . $f]);
        }
    }

    // Thêm playlist mới
    // Xác định các field theo ngôn ngữ không có dữ liệu
    $langs = msGetModuleSetupLangs();
    $array_fname = $array_fvalue = [];
    foreach ($langs as $lang) {
        if ($lang != NV_LANG_DATA) {
            $array_fname[] = $lang . '_playlist_introtext';
            $array_fvalue[] = '';
        }
    }
    $array_fname = $array_fname ? (', ' . implode(', ', $array_fname)) : '';
    $array_fvalue = $array_fvalue ? (', \'' . implode('\', \'', $array_fvalue) . '\'') : '';

    $sql = "INSERT INTO " . NV_MOD_TABLE . "_user_playlists (
        playlist_code, resource_avatar, resource_cover, userid, time_add, privacy, num_songs, " . NV_LANG_DATA . "_playlist_name,
        " . NV_LANG_DATA . "_playlist_introtext" . $array_fname . "
    ) VALUES (
        :playlist_code, '', '', " . $user_info['userid'] . ", " . NV_CURRENTTIME . ", " . $request['privacy'] . ", " . ($request['auto_add_song'] ? 1 : 0) . ",
        :playlist_name, ''" . $array_fvalue . "
    )";

    $array_insert = [];
    $array_insert['playlist_code'] = UserPlaylists::creatUniqueCode();
    $array_insert['playlist_name'] = $request['playlist_name'];

    $new_playlist_id = $db->insert_id($sql, 'playlist_id', $array_insert);
    if (!$new_playlist_id) {
        $respon['message'] = $lang_module['unknow_error'];
        nv_jsonOutput($respon);
    }

    // Thêm bài hát này vào playlist
    if ($request['auto_add_song']) {
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_user_playlists_data (playlist_id, song_id, weight, status) VALUES (
            " . $new_playlist_id . ", " . $row['song_id'] . ", 1, 1
        )";
        $db->query($sql);

        // Thông báo đã thêm bài hát vào và kết thúc
        $respon['status'] = 'SUCCESS';
        $respon['message'] = sprintf($lang_module['addtolist_new_success_add'], $row['song_name'], $request['playlist_name']);
        nv_jsonOutput($respon);
    }

    // Thông báo đã tạo playlist và kết thúc
    $respon['status'] = 'SUCCESS';
    $respon['message'] = sprintf($lang_module['pl_creat_success'], $request['playlist_name']);
    nv_jsonOutput($respon);
}

// Thêm, bỏ bài hát ở playlist đã có
if ($nv_Request->isset_request('togglePlaylistSong', 'post')) {
    $respon = [
        'status' => 'ERROR',
        'message' => ''
    ];

    // Kiểm tra đăng nhập
    if (!defined('NV_IS_USER')) {
        $respon['message'] = $lang_module['error_not_login'];
        nv_jsonOutput($respon);
    }

    // Lấy request
    $request = [];
    $request['is_add'] = intval($nv_Request->get_bool('is_add', 'post', false));
    $request['song_code'] = nv_substr($nv_Request->get_title('song_code', 'post', ''), 0, 200);
    $request['playlist_code'] = nv_substr($nv_Request->get_title('playlist_code', 'post', ''), 0, 200);

    if ($tokend !== md5($request['song_code'] . NV_CHECK_SESSION) or empty($request['playlist_code'])) {
        $respon['message'] = 'Access Denied!!!';
        nv_jsonOutput($respon);
    }

    // Xác định thông tin bài hát
    $array_select_fields = nv_get_song_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_songs WHERE status=1 AND song_code=" . $db->quote($request['song_code']);
    $result = $db->query($sql);
    $song = $result->fetch();
    if (empty($song)) {
        $respon['message'] = $lang_module['error_song_notexists'];
        nv_jsonOutput($respon);
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($song[$f]) and !empty($song['default_' . $f])) {
            $song[$f] = $song['default_' . $f];
        }
        unset($song['default_' . $f]);
    }

    // Xác định thông tin playlist
    $array_select_fields = nv_get_user_playlist_select_fields(true);
    $sql = "SELECT " . implode(', ', $array_select_fields[0]) . " FROM " . NV_MOD_TABLE . "_user_playlists WHERE userid=" . $user_info['userid'] . " AND playlist_code=" . $db->quote($request['playlist_code']);
    $result = $db->query($sql);
    $playlist = $result->fetch();
    if (empty($playlist)) {
        $respon['message'] = $lang_module['error_song_notexists'];
        nv_jsonOutput($respon);
    }
    foreach ($array_select_fields[1] as $f) {
        if (empty($playlist[$f]) and !empty($playlist['default_' . $f])) {
            $playlist[$f] = $playlist['default_' . $f];
        }
        unset($playlist['default_' . $f]);
    }

    if ($request['is_add']) {
        // Thêm bài hát vào playlist
        $sql = "INSERT IGNORE INTO " . NV_MOD_TABLE . "_user_playlists_data (
            playlist_id, song_id, weight, status
        ) VALUES (
            " . $playlist['playlist_id'] . ", " . $song['song_id'] . ", 0, 1
        )";
        $respon['message'] = sprintf($lang_module['addtolist_new_success_add'], $song['song_name'], $playlist['playlist_name']);
    } else {
        // Bỏ bài hát ra playlist
        $sql = "DELETE FROM " . NV_MOD_TABLE . "_user_playlists_data WHERE playlist_id=" . $playlist['playlist_id'] . " AND song_id=" . $song['song_id'];
        $respon['message'] = sprintf($lang_module['addtolist_remove_success'], $song['song_name'], $playlist['playlist_name']);
    }

    $db->query($sql);
    msUpdatePlaylistSongCountWeight($playlist['playlist_id']);

    $respon['status'] = 'SUCCESS';
    nv_jsonOutput($respon);
}

nv_redirect_location(NV_MOD_LINK);
