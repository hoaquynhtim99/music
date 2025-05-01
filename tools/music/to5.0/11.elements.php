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

require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';

$dir = NV_ROOTDIR . '/themes/admin_future/modules/music';
$files = nv_scandir($dir, '/\.tpl$/');
$replaced = 0;
$totalReplaced = 0;

foreach ($files as $file) {
    $pathFile = $dir . '/' . $file;
    $content = file_get_contents($pathFile);
    $replaced = 0;

    echo "Processing: $file";

    // Nút btn-default sang btn-secondary
    $pattern = '/btn\-default( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return 'btn-secondary' . $matches[1];
    }, $content);

    // Xử lý form-horizontal
    $pattern = '/\<form([^\>]+)class\=\"form\-horizontal\"([^\>]*)\>(.*?)\<\/form\>/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $formHtml = '<form' . preg_replace('/ [ ]+/', ' ', $matches[1] . $matches[2]) . '>' . $matches[3] . '</form>';

        // Trong các form đó, tìm và xử lý
        // form-group sang row mb-3
        $count = 0;
        $formHtml = str_replace('form-group', 'row mb-3', $formHtml, $count);
        $replaced += $count;

        // control-label sang col-form-label text-sm-end
        $count = 0;
        $formHtml = str_replace('control-label', 'col-form-label text-sm-end', $formHtml, $count);
        $replaced += $count;

        return $formHtml;
    }, $contentNew);

    // Xử lý panel sang card
    $pattern = '/( |\"|\')panel( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'card' . $matches[2];
    }, $contentNew);

    $pattern = '/( |\"|\')panel\-body( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'card-body' . $matches[2];
    }, $contentNew);

    $pattern = '/( |\"|\')panel\-heading( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'card-header' . $matches[2];
    }, $contentNew);

    // help-block sang form-text
    $pattern = '/( |\"|\')help\-block( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'form-text' . $matches[2];
    }, $contentNew);

    // Select mà có class form-control thì đổi thành form-select
    $pattern = '/\<select([^\>]+)class\=\"([^\"]+)\"([^\>]*)\>/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $classes = explode(' ', $matches[2]);
        $newClasses = [];
        foreach ($classes as $class) {
            if ($class == 'form-control') {
                $newClasses[] = 'form-select';
                $replaced++;
            } else {
                $newClasses[] = $class;
            }
        }
        $newClasses = array_filter(array_unique(array_map('trim', $newClasses)));

        $html = '<select' . $matches[1];
        if (!empty($newClasses)) {
            $html .= 'class="' . implode(' ', $newClasses) . '"';
        }
        $html .= rtrim($matches[3]) . '>';

        return $html;
    }, $contentNew);

    // Xử lý dropdown
    $count = 0;
    $contentNew = str_replace('data-toggle="dropdown"', 'data-bs-toggle="dropdown"', $contentNew, $count);
    $replaced += $count;

    // Xử lý offset column
    $pattern = '/( |\"|\')col\-([a-z]{2})\-offset\-([0-9]+)( |\"|\')/';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'offset-' . $matches[2] . '-' . ceil($matches[3] / 2) . $matches[4];
    }, $contentNew);

    /**
     * Xử lý <div class="checkbox">
     * Nó phải đúng cú pháp
     * <div class="checkbox">
     *  <label>
     *   <input type="checkbox" name="checkbox" value="1" />
     *   Checkbox
     *  </label>
     * </div>
     */
    $pattern = '/\<div[\s]+class[\s]*\=[\s]*"[\s]*checkbox[\s]*"[\s]*\>([\n\r\s\t]*)\<label\>([\n\r\s\t]*)\<input([^\>]+)\>([\n\r\s\t]*)(.*?)([\n\r\s\t]*)\<\/label\>([\n\r\s\t]*)\<\/div\>/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        if (str_contains($matches[3], 'class=')) {
            die("Lỗi checkbox có class\n");
        }

        $matches[3] = ltrim($matches[3]);
        $inputId = 'check' . nv_genpass();
        if (preg_match('/id\=\"([^\"]+)\"/is', $matches[3], $m)) {
            $inputId = trim($m[1]);
        } else {
            $matches[3] = 'id="' . $inputId . '" ' . $matches[3];
        }

        $html = '<div class="form-check">' . $matches[1] . '<input class="form-check-input" ' . $matches[3] . '>' . $matches[1];
        $html .= '<label class="form-check-label" for="' . $inputId . '">' . trim($matches[5]) . '</label>';
        $html .= $matches[7] . '</div>';

        $replaced++;
        return $html;
    }, $contentNew);

    // btn-xs sang btn-sm
    $pattern = '/( |\"|\')btn\-xs( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'btn-sm' . $matches[2];
    }, $contentNew);

    // Xử lý table-responsive
    $pattern = '/\<div([^\>]+)class\=\"table\-responsive\"([^\>]*)\>([\n\r\s\t]*)\<table([^\>]+)class\=\"([^\"]+)\"([^\>]*)\>/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $classes = explode(' ', $matches[5]);
        if (!in_array('table-sticky', $classes)) {
            $classes[] = 'table-sticky';
        }
        $classes = implode(' ', $classes);
        $html = '<div class="table-responsive-lg">' . $matches[3] . '<table' . $matches[4] . 'class="' . $classes . '"' . $matches[6] . '>';
        $replaced++;
        return $html;
    }, $contentNew);

    // pull-left sang float-start
    $pattern = '/( |\"|\')pull\-left( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'float-start' . $matches[2];
    }, $contentNew);

    // pull-right sang float-end
    $pattern = '/( |\"|\')pull\-right( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'float-end' . $matches[2];
    }, $contentNew);

    // text-left sang text-start
    $pattern = '/( |\"|\')text\-left( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'text-start' . $matches[2];
    }, $contentNew);

    // text-right sang text-end
    $pattern = '/( |\"|\')text\-right( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $replaced++;
        return $matches[1] . 'text-end' . $matches[2];
    }, $contentNew);

    // Modal
    $count = 0;
    $contentNew = str_replace('data-backdrop="static"', 'data-bs-backdrop="static"', $contentNew, $count);
    $contentNew = str_replace('data-dismiss="modal"', 'data-bs-dismiss="modal"', $contentNew, $count);
    $replaced += $count;

    // Form
    $count = 0;
    $contentNew = str_replace('class="control-label"', 'class="form-label"', $contentNew, $count);
    $contentNew = str_replace('class="form-group"', 'class="mb-3"', $contentNew, $count);
    $replaced += $count;

    echo " => $replaced replacements\n";
    if ($contentNew != $content) {
        file_put_contents($pathFile, $contentNew, LOCK_EX);
    }

    $totalReplaced += $replaced;
}

echo "\nTotal replacements: $totalReplaced\n";
