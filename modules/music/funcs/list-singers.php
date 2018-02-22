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

$page_title = $global_array_config['funcs_sitetitle']['singer'];
$key_words = $global_array_config['funcs_keywords']['singer'];
$description = $global_array_config['funcs_description']['singer'];

// Các thẻ meta Open Graph
nv_get_fb_share_image();

$array = array();
$array_select_fields = nv_get_artist_select_fields();
$nation_id = 0;
$nation_code = '';
$nation_alias = '';
$request_nation_alias = '';
$generate_page = '';
$base_url = '';
$page = 1;
$all_pages = 0;
$per_page = 1;
$alphabet = '';

// Xem quốc gia hoặc xem theo ký tự hoặc phân trang
if (isset($array_op[1])) {
    if (in_array($array_op[1], $array_alphabets)) {
        // Xem theo ký tự
        $alphabet = $array_op[1];
    } elseif (preg_match("/^page\-([0-9]{1,7})$/", $array_op[1], $m)) {
        // Phân trang
        $page = intval($m[1]);
        if ($page <= 1) {
            nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers']);
        }
    } else {
        if (preg_match("/^([a-zA-Z0-9\-]+)\-([a-zA-Z0-9\-]+)$/", $array_op[1], $m)) {
            $nation_code = $m[2];

            if (!isset($global_array_nation_alias[$nation_code])) {
                nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers']);
            }
            
            $nation_id = $global_array_nation_alias[$nation_code];
            $nation_alias = $global_array_nation[$nation_id]['nation_alias'];
            $request_nation_alias = $m[1];
        } else {
            nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers']);
        }
    }
}

// Xem theo ký tự lúc có quốc gia hoặc phân trang
if (isset($array_op[2])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[2], $m)) {
        $page = intval($m[1]);
        if ($page <= 1) {
            nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . ($alphabet ? '/' . $alphabet : ($nation_id ? '/' . $nation_alias . '-' . $nation_code : '')));
        }
    } elseif (in_array($array_op[2], $array_alphabets)) {
        $alphabet = $array_op[2];
    } else {
        nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . ($alphabet ? '/' . $alphabet : ($nation_id ? '/' . $nation_alias . '-' . $nation_code : '')));
    }
}

if (isset($array_op[3])) {
    if (preg_match("/^page\-([0-9]{1,7})$/", $array_op[3], $m)) {
        $page = intval($m[1]);
        if ($page <= 1) {
            nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . '/' . ($nation_id ? $nation_alias . '-' . $nation_code : '') . ($alphabet ? ($nation_id ? '/' : '') . $alphabet : ''));
        }
    } else {
        nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . '/' . ($nation_id ? $nation_alias . '-' . $nation_code : '') . ($alphabet ? ($nation_id ? '/' : '') . $alphabet : ''));
    }
}

// Chỉnh lại đường dẫn nếu Alias thay đổi hoặc đặt page sai
if (isset($array_op[4]) or $nation_alias != $request_nation_alias) {
    nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . '/' . ($nation_id ? $nation_alias . '-' . $nation_code : '') . ($alphabet ? ($nation_id ? '/' : '') . $alphabet : ''));
}

$global_array_config['gird_singers_nums'] = 36;

$base_url = NV_MOD_FULLLINK_AMP . $module_info['alias']['list-singers'] . '/' . ($nation_id ? $nation_alias . '-' . $nation_code : '') . ($alphabet ? ($nation_id ? '/' : '') . $alphabet : '');
$per_page = $global_array_config['gird_singers_nums'];
$array_where = array();
$array_where[] = 'status=1';
if ($nation_id) {
    $array_where[] = 'nation_id=' . $nation_id;
}
if ($alphabet) {
    $array_where[] = NV_LANG_DATA . "_artist_alphabet='" . $alphabet . "'";
}

$db->sqlreset()->from(NV_MOD_TABLE . "_artists")->where(implode(' AND ', $array_where));

$db->select("COUNT(*)");
$all_pages = $db->query($db->sql())->fetchColumn();

$db->order("artist_id DESC")->offset(($page - 1) * $per_page)->limit($per_page);        
$db->select(implode(', ', $array_select_fields[0]));

$array_singers = array();
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    foreach ($array_select_fields[1] as $f) {
        if (empty($row[$f]) and !empty($row['default_' . $f])) {
            $row[$f] = $row['default_' . $f];
        }
        unset($row['default_' . $f]);
    }
    
    $row['singer_link'] = nv_get_view_singer_link($row);
    
    $array_singers[$row['artist_id']] = $row;
}

// Xử lý nếu tùy ý đặt giá trị page sai
if ($page > 1 and empty($array_singers)) {
    nv_redirect_location(NV_MOD_FULLLINK . $module_info['alias']['list-singers'] . '/' . ($nation_id ? $nation_alias . '-' . $nation_code : '') . ($alphabet ? ($nation_id ? '/' : '') . $alphabet : ''));
}

// Breadcrumb
$array_mod_title[] = array(
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => NV_MOD_FULLLINK_AMP . $module_info['alias']['list-singers']
);

// Phân trang, tiêu đề trang
if (!empty($nation_id)) {
    $page_title = $lang_module['singers'] . ' ' . $global_array_nation[$nation_id]['nation_name'];
    if (!empty($key_words) and !empty($global_array_nation[$nation_id]['nation_keywords'])) {
        $key_words .= ', ';
    }
    $key_words .= $global_array_nation[$nation_id]['nation_keywords'];
    $description = $global_array_nation[$nation_id]['nation_introtext'];
    if (!empty($description)) {
        $description .= '. ';
    }
    $description .= $page_title;
    
    $array_mod_title[] = array(
        'catid' => 0,
        'title' => $global_array_nation[$nation_id]['nation_name'],
        'link' => NV_MOD_FULLLINK_AMP . $module_info['alias']['list-singers'] . '/' . $nation_alias . '-' . $nation_code
    );
}

if (!empty($alphabet)) {
    $page_title .= ' ' . sprintf($lang_module['singer_alphabet'], $alphabet);
    if (!empty($description)) {
        $description = nv_clean60($description, $global_config['description_length'] - 10);
        $description .= ' ' . sprintf($lang_module['singer_alphabet'], $alphabet);
    }
}

if ($page > 1) {
    $page_text = $lang_global['page'] . ' ' . number_format($page, 0, ',', '.');
    $page_title .= NV_TITLEBAR_DEFIS . $page_text;
    if (!empty($description)) {
        $description = nv_clean60($description, $global_config['description_length'] - 20);
        $description .= NV_TITLEBAR_DEFIS . $page_text;
    }
}

$generate_page = nv_alias_page($page_title, $base_url, $all_pages, $per_page, $page);

$contents = nv_theme_gird_singers($array_singers, $nation_id, $alphabet, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
