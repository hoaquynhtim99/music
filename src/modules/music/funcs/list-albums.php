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

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;

$page_title = Config::getFuncsSitetitle()->getAlbum();
$key_words = Config::getFuncsKeywords()->getAlbum();
$description = Config::getFuncsDescription()->getAlbum();

// Các thẻ meta Open Graph
nv_get_fb_share_image();

$array = [];
$array_select_fields = nv_get_album_select_fields();
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
            nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['list-albums']);
        }

        $catid = $global_array_cat_alias[$catcode];
        $catalias = $global_array_cat[$catid]['cat_alias'];
        $request_catalias = $m[1];
        $base_url = Resources::getModFullLinkEncode() . $module_info['alias']['list-albums'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode;
    } else {
        nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['list-albums']);
    }
}

// Xử lý phân trang
if (isset($array_op[2])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[2], $m)) {
        $page = intval($m[1]);
    }
    if ($page <= 1) {
        nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['list-albums'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode);
    }
}

// Chỉnh lại đường dẫn nếu Alias thay đổi hoặc đặt page sai
if (isset($array_op[3]) or $catalias != $request_catalias) {
    nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['list-albums'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode . ($page > 1 ? '/page-' . $page : ''));
}

foreach ($global_array_cat as $cat) {
    if (!empty($cat['status']) and !empty($cat['show_inalbum']) and (empty($catid) or $cat['cat_id'] == $catid)) {
        $per_page = empty($catid) ? Config::getGirdAlbumsPercatNums() : Config::getGirdAlbumsIncatNums();
        $db->sqlreset()->from(Resources::getTablePrefix() . "_albums")->where("is_official=1 AND status=1 AND FIND_IN_SET(" . $cat['cat_id'] . ", cat_ids)");

        if (!empty($catid)) {
            $db->select("COUNT(album_id)");
            $all_pages = $db->query($db->sql())->fetchColumn();
        }

        $db->order("album_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);
        $db->select(implode(', ', $array_select_fields[0]));

        $array_albums = [];
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

            $array_albums[$row['album_id']] = $row;
        }

        if (!empty($array_albums)) {
            $array[$cat['cat_id']] = array(
                'cat' => $cat,
                'albums' => $array_albums
            );
        }
    }
}

// Xử lý nếu tùy ý đặt giá trị page sai
if ($page > 1 and empty($array)) {
    nv_redirect_location(Resources::getModFullLink() . $module_info['alias']['list-albums'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode);
}

// Xác định ca sĩ
$array_singers = nv_get_artists($array_singer_ids);

foreach ($array as $id1 => $row1) {
    foreach ($row1['albums'] as $id => $row) {
        if (!empty($row['singer_ids'])) {
            foreach ($row['singer_ids'] as $singer_id) {
                if (isset($array_singers[$singer_id])) {
                    $row['singers'][$singer_id] = $array_singers[$singer_id];
                }
            }
        }
        $row['album_link'] = nv_get_detail_album_link($row, $row['singers']);
        $array[$id1]['albums'][$id] = $row;
    }
}

// Breadcrumb
$array_mod_title[] = array(
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => Resources::getModFullLinkEncode() . $module_info['alias']['list-albums']
);

// Phân trang, tiêu đề trang
if (!empty($catid)) {
    $page_title = $global_array_cat[$catid]['cat_absitetitle'];
    $key_words = $global_array_cat[$catid]['cat_abkeywords'];
    $description = $global_array_cat[$catid]['cat_abintrotext'];
    $generate_page = nv_alias_page($page_title, $base_url, $all_pages, $per_page, $page);

    $array_mod_title[] = array(
        'catid' => 0,
        'title' => $global_array_cat[$catid]['cat_name'],
        'link' => Resources::getModFullLinkEncode() . $module_info['alias']['list-albums'] . '/' . $catalias . '-' . $codePrefix->getCat() . $catcode
    );
}

if ($page > 1) {
    $page_text = $nv_Lang->getGlobal('page') . ' ' . number_format($page, 0, ',', '.');
    $page_title .= NV_TITLEBAR_DEFIS . $page_text;
    if (!empty($description)) {
        $description = nv_clean60($description, $global_config['description_length'] - 20);
        $description .= NV_TITLEBAR_DEFIS . $page_text;
    }
}

$contents = nv_theme_page_list_albums($array, !empty($catid), $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
