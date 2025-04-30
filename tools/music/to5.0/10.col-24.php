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

foreach ($files as $file) {
    $pathFile = $dir . '/' . $file;
    $content = file_get_contents($pathFile);

    echo "Processing: $file";

    $replaced = 0;
    $pattern = '/col\-([a-z]{2})\-([0-9]+)( |\"|\')/is';
    $contentNew = preg_replace_callback($pattern, function($matches) use (&$replaced) {
        $screen = $matches[1];
        $col = $matches[2];
        $seprator = $matches[3];

        if (!in_array($screen, ['xs', 'sm', 'md', 'lg', 'xl'])) {
            return $matches[0];
        }
        if ($col < 1 or $col > 24) {
            return $matches[0];
        }

        $replaced++;

        $col = ceil($col / 2);
        if ($screen == 'xs') {
            return 'col-' . $col . $seprator;
        }
        return 'col-' . $screen . '-' . $col . $seprator;
    }, $content);

    echo " => $replaced replacements\n";

    if ($contentNew != $content) {
        file_put_contents($pathFile, $contentNew, LOCK_EX);
    }
}
