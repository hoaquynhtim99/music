<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC'))  {
    die('Stop!!!');
}

use NukeViet\Music\Config;

$page_title = Config::getFuncsSitetitle()->getVideo();
$key_words = Config::getFuncsKeywords()->getVideo();
$description = Config::getFuncsDescription()->getVideo();

// Các thẻ meta Open Graph
nv_get_fb_share_image();

$array = [];
$array_select_fields = nv_get_video_select_fields();
$array_singer_ids = $array_singers = [];
$catid = 0;
$catcode = '';
$catalias = '';
$request_catalias = '';
$generate_page = '';
$base_url = '';
$page = 1;
$all_pages = 0;
$per_page = 1;

$codePrefix = Config::getCodePrefix();

// Xử lý khi xem theo danh mục
if (isset($array_op[1])) {
    if (preg_match("/^([a-zA-Z0-9\-]+)\-" . nv_preg_quote($codePrefix->getCat()) . "([a-zA-Z0-9\-]+)$/", $array_op[1], $m)) {
        $catcode = $m[2];
        if (!isset($global_array_cat_alias[$catcode])) {
            nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-videos']);
        }

        $catid = $global_array_cat_alias[$catcode];
        $catalias = $global_array_cat[$catid]['cat_alias'];
        $request_catalias = $m[1];
        $base_url = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-videos'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode;
    } else {
        nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-videos']);
    }
}

// Xử lý phân trang
if (isset($array_op[2])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[2], $m)) {
        $page = intval($m[1]);
    }
    if ($page <= 1) {
        nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-videos'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode);
    }
}

// Chỉnh lại đường dẫn nếu Alias thay đổi hoặc đặt page sai
if (isset($array_op[3]) or $catalias != $request_catalias) {
    nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-videos'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode . ($page > 1 ? '/page-' . $page : ''));
}

foreach ($global_array_cat as $cat) {
    if (!empty($cat['status']) and !empty($cat['show_invideo']) and (empty($catid) or $cat['cat_id'] == $catid)) {
        $per_page = empty($catid) ? Config::getGirdVideosPercatNums() : Config::getGirdVideosIncatNums();
        $db->sqlreset()->from(NV_MOD_TABLE . "_videos")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $cat['cat_id'] . ", cat_ids)");

        if (!empty($catid)) {
            $db->select("COUNT(*)");
            $all_pages = $db->query($db->sql())->fetchColumn();
        }

        $db->order("video_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);
        $db->select(implode(', ', $array_select_fields[0]));

        $array_videos = [];
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

            $array_videos[$row['video_id']] = $row;
        }

        if (!empty($array_videos)) {
            $array[$cat['cat_id']] = array(
                'cat' => $cat,
                'videos' => $array_videos
            );
        }
    }
}

// Xử lý nếu tùy ý đặt giá trị page sai
if ($page > 1 and empty($array)) {
    nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-videos'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode);
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

foreach ($array as $id1 => $row1) {
    foreach ($row1['videos'] as $id => $row) {
        if (!empty($row['singer_ids'])) {
            foreach ($row['singer_ids'] as $singer_id) {
                if (isset($array_singers[$singer_id])) {
                    $row['singers'][$singer_id] = $array_singers[$singer_id];
                }
            }
        }
        $row['video_link'] = nv_get_detail_video_link($row, $row['singers']);
        $array[$id1]['videos'][$id] = $row;
    }
}

// Breadcrumb
$array_mod_title[] = array(
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => NV_MOD_FULLLINK_AMP . $module_info['alias']['list-videos']
);

// Phân trang, tiêu đề trang
if (!empty($catid)) {
    $page_title = $global_array_cat[$catid]['cat_mvsitetitle'];
    $key_words = $global_array_cat[$catid]['cat_mvkeywords'];
    $description = $global_array_cat[$catid]['cat_mvintrotext'];
    $generate_page = nv_alias_page($page_title, $base_url, $all_pages, $per_page, $page);

    $array_mod_title[] = array(
        'catid' => 0,
        'title' => $global_array_cat[$catid]['cat_name'],
        'link' => NV_MOD_FULLLINK_AMP . $module_info['alias']['list-videos'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode
    );
}

if ($page > 1) {
    $page_text = $lang_global['page'] . ' ' . number_format($page, 0, ',', '.');
    $page_title .= NV_TITLEBAR_DEFIS . $page_text;
    if (!empty($description)) {
        $description = nv_clean60($description, $global_config['description_length'] - 20);
        $description .= NV_TITLEBAR_DEFIS . $page_text;
    }
}

$contents = nv_theme_list_videos($array, !empty($catid), $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
