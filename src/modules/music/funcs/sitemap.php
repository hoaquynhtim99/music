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

use NukeViet\Module\music\Resources;

$array_allowed_sitemap = ['album', 'mv', 'singer'];
if ((!empty($array_op[1]) and !in_array($array_op[1], $array_allowed_sitemap)) or isset($array_op[2])) {
    nv_redirect_location(Resources::getModLink());
}
$cacheFilePrefix = !empty($array_op[1]) ? $array_op[1] : 'song';

$url = [];
$cacheFile = NV_LANG_DATA . '_sitemap_' . $cacheFilePrefix . '_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 7200;

if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $url = unserialize($cache);
} else {
    $array = $array_singer_ids = $array_singers = [];

    if (empty($array_op[1])) {
        // Sitemap bài hát
        $db->sqlreset()->from(Resources::getTablePrefix() . "_songs")->where("status=1 AND is_official=1");
        $db->order("song_id DESC")->limit(2000)->offset(0);

        $array_select_fields = nv_get_song_select_fields();
        $db->select(implode(', ', $array_select_fields[0]));

        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);
            $row['song_link'] = '';

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            $array[$row['song_id']] = $row;
        }
    } elseif ($array_op[1] == 'album') {
        // Sitemap album
        $array_select_fields = nv_get_album_select_fields();
        $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where("is_official=1 AND status=1");
        $db->order("album_id DESC")->offset(0)->limit(2000);
        $db->select(implode(', ', $array_select_fields[0]));

        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);
            $row['album_link'] = '';

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            $array[$row['album_id']] = $row;
        }
    } elseif ($array_op[1] == 'mv') {
        // Sitemap MV
        $array_select_fields = nv_get_video_select_fields();
        $db->sqlreset()->from(Resources::getTablePrefix() . "_videos")->where("is_official=1 AND status=1");
        $db->order("video_id DESC")->offset(0)->limit(2000);
        $db->select(implode(', ', $array_select_fields[0]));

        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singers'] = [];
            $row['singer_ids'] = explode(',', $row['singer_ids']);
            $row['video_link'] = '';

            if (!empty($row['singer_ids'])) {
                $array_singer_ids = array_merge_recursive($array_singer_ids, $row['singer_ids']);
            }

            $array[$row['video_id']] = $row;
        }
    } else {
        // Sitemap ca sĩ
        $array_select_fields = nv_get_artist_select_fields();
        $array_where = [];
        $array_where[] = 'status=1';

        $db->sqlreset()->from(Resources::getTablePrefix() . "_artists")->where(implode(' AND ', $array_where));
        $db->order("artist_id DESC")->offset(0)->limit(5000);
        $db->select(implode(', ', $array_select_fields[0]));

        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            foreach ($array_select_fields[1] as $f) {
                if (empty($row[$f]) and !empty($row['default_' . $f])) {
                    $row[$f] = $row['default_' . $f];
                }
                unset($row['default_' . $f]);
            }

            $row['singer_link'] = nv_get_view_singer_link($row);

            $url[] = [
                'link' => $row['singer_link'],
                'publtime' => $row['time_add'],
                'changefreq' => 'daily',
                'priority' => '0.8'
            ];
        }
    }

    // Build lại đường dẫn
    if (!empty($array_singer_ids)) {
        $array_singers = nv_get_artists($array_singer_ids);

        foreach ($array as $id => $row) {
            if (!empty($row['singer_ids'])) {
                foreach ($row['singer_ids'] as $singer_id) {
                    if (isset($array_singers[$singer_id])) {
                        $row['singers'][$singer_id] = $array_singers[$singer_id];
                    }
                }
            }

            if (empty($array_op[1])) {
                // Bài hát
                $row['song_link'] = nv_get_detail_song_link($row, $row['singers']);

                $url[] = [
                    'link' => $row['song_link'],
                    'publtime' => $row['time_add'],
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ];
            } elseif ($array_op[1] == 'album') {
                // Album
                $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);

                $url[] = [
                    'link' => $row['album_link'],
                    'publtime' => $row['time_add'],
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ];
            } elseif ($array_op[1] == 'mv') {
                // MV
                $row['video_link'] = nv_get_detail_video_link($row, $row['singers']);

                $url[] = [
                    'link' => $row['video_link'],
                    'publtime' => $row['time_add'],
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ];
            }
        }
    }

    if (!empty($url)) {
        $cache = serialize($url);
        $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
    }
}

nv_xmlSitemap_generate($url);
die();
