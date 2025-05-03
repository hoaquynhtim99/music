<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_MAINFILE', true);
define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../../../src')));
define('NV_REPODIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../../..')));

require NV_REPODIR . '/vendor/autoload.php';
require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';

use Symfony\Component\Yaml\Yaml;

/**
 * Đọc các icon mới và xử lý lưu dạng
 * icon => [style1, style2]
 */
$fileIcons = NV_REPODIR . '/node_modules/@fortawesome/fontawesome-free/metadata/icons.yml';
$yaml = Yaml::parseFile($fileIcons);

$icons = [];
foreach ($yaml as $key => $row) {
    if (!isset($row['styles'])) {
        print_r($row);
        die("Lỗi không có styles\n");
    }
    if (!is_array($row['styles'])) {
        print_r($row);
        die("styles không array\n");
    }
    if (empty($row['styles'])) {
        print_r($row);
        die("styles rỗng\n");
    }

    $iconKey = 'fa-' . $key;
    if (isset($icons[$iconKey])) {
        print_r($row);
        die("icon đã tồn tại\n");
    }

    $styles = [];
    foreach ($row['styles'] as $style) {
        if (!in_array($style, ['solid', 'regular', 'brands'])) {
            print_r($row);
            die("styles không xác định\n");
        }

        $styles[] = 'fa-' . $style;
    }

    $itemIcons = [$iconKey];
    if (!empty($row['aliases']) and !empty($row['aliases']['names'])) {
        foreach ($row['aliases']['names'] as $alias) {
            $iconI = 'fa-' . $alias;
            if (!in_array($iconI, $itemIcons)) {
                $itemIcons[] = $iconI;
            }
        }
    }

    foreach ($itemIcons as $icon) {
        if (isset($icons[$icon])) {
            print_r($row);
            die("icon đã tồn tại\n");
        }
        $icons[$icon] = [
            'icon' => $iconKey,
            'styles' => $styles,
        ];
    }
}

$dir = NV_ROOTDIR . '/themes/admin_future/modules/music';
$files = nv_scandir($dir, '/\.tpl$/');
$replaced = 0;
$totalReplaced = 0;
$iconMappings = [
    'fa-times-circle-o' => 'fa-circle-xmark',
    'fa-file-archive-o' => 'fa-file-zipper',
    'fa-floppy-o' => 'fa-floppy-disk',
];
$khownClasses = [
    'fa-fw',
    'fa-rotate-90',
    'fa-rotate-180',
    'fa-rotate-270',
    'fa-pull-left',
    'fa-pull-right',
    'fa-spin',
    'fa'
];

$replacedData = [];

foreach ($files as $file) {
    $pathFile = $dir . '/' . $file;
    $content = file_get_contents($pathFile);
    $replaced = 0;

    echo "Processing: $file";

    // Xử lý icon
    $pattern = '/class[\s]*\=[\s]*(\"|\')[\s]*([^"\']+)[\s]*(\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced, $icons, $iconMappings, $khownClasses) {
        $haveFont = 0;
        $classes = explode(' ', $matches[2]);
        $newClasses = [];
        $iconName = '';
        foreach ($classes as $class) {
            if ($class == 'fa' or str_starts_with($class, 'fa-')) {
                $haveFont++;
            }
            if (in_array($class, ['fa-solid', 'fa-regular', 'fa-brands'])) {
                return $matches[0];
            }
            if (str_starts_with($class, 'fa-') and !in_array($class, $khownClasses)) {
                $class = $iconMappings[$class] ?? $class;
                if (preg_match('/^fa-([a-z0-9\-]+)-o$/i', $class, $m)) {
                    $class = 'fa-' . $m[1];
                }

                if (!isset($icons[$class])) {
                    print_r($matches);
                    die("Lỗi icon không có trong danh sách\n");
                }

                $iconName = $class;
            } elseif ($class != 'fa') {
                $newClasses[] = $class;
            }
        }
        if ($haveFont == 0) {
            return $matches[0];
        }

        // Xác định icon đầu tiên
        if (in_array('fa-solid', $icons[$iconName]['styles'])) {
            // Ưu tiên solid
            $newClasses = array_merge(['fa-solid', $icons[$iconName]['icon']], $newClasses);
        } else {
            $newClasses = array_merge([$icons[$iconName]['styles'][0], $icons[$iconName]['icon']], $newClasses);
        }
        $newClasses = array_filter(array_unique(array_map('trim', $newClasses)));
        $replaced++;

        return 'class=' . $matches[1] . implode(' ', $newClasses) . $matches[3];
    }, $content);

    if ($contentNew != $content) {
        $replacedData[] = [
            'old' => $content,
            'new' => $contentNew,
            'full_path' => $pathFile,
        ];
    }

    echo " => $replaced replacements\n";

    $totalReplaced += $replaced;
}

foreach ($replacedData as $data) {
    file_put_contents($data['full_path'], $data['new'], LOCK_EX);
}

echo "\nTotal replacements: $totalReplaced\n";
