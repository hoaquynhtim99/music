<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Tạo mới bài hát
 *
 * @package NukeViet\Module\music\Api
 * @author PHAN TAN DUNG <writeblabla@gmail.com>
 * @copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @access public
 */
class SongCreate implements IApi
{
    private $result;

    /**
     * @return int
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * @return string
     */
    public static function getCat()
    {
        return 'Song';
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db, $nv_Request, $nv_Lang, $global_config, $language_array;
        global $module_name, $module_info, $module_data, $module_file, $module_upload, $op, $admin_info;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $module_upload = $module_info['module_upload'];
        $op = 'song-content';
        $admin_id = Api::getAdminId();
        $admin_lev = Api::getAdminLev();
        $site_mods = nv_site_mods();

        $admin_info = [
            'admin_id' => $admin_id,
            'userid' => $admin_id,
            'full_name' => Api::getAdminName()
        ];

        !defined('NV_IS_MODADMIN') && define('NV_IS_MODADMIN', true);
        !defined('NV_ADMIN') && define('NV_ADMIN', true);

        $_POST['song_id'] = 0;
        $_POST['submitform'] = 1;

        require NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
        require NV_ROOTDIR . '/modules/' . $module_file . '/admin/' . $op . '.php';

        return $this->result->getResult();
    }
}
