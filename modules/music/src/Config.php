<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music;

use NukeViet\Music\Db\Db;

class Config
{
    use DBStruct;

    private const DEFAULT_LANG = 'default_language';

    private static $configData = [];

    /**
     * @param string $key
     * @throws Exception
     * @return string~array
     */
    private static function get($key)
    {
        if (isset(self::$configData[$key])) {
            return self::$configData[$key];
        }
        throw new Exception('Config not loaded!!!');
    }

    /**
     * @param array $data
     */
    public static function loadConfig($data)
    {
        self::$configData = $data;
    }

    /**
     * @return array
     */
    public static function getAllConfig()
    {
        $sql = new Db();
        $sql->setTable(self::getTableConfig());
        $sql->setField('*');

        // Các cấu hình trong CSDL
        $result = $sql->select();
        $lang_data = Resources::getLangData();
        self::$configData = [];

        while ($row = $result->fetch()) {
            if ($row['config_value_' . $lang_data] === null) {
                self::$configData[$row['config_name']] = $row['config_value_default'];
            } else {
                self::$configData[$row['config_name']] = $row['config_value_' . $lang_data];
            }

            if (preg_match('/^arr\_([a-zA-Z0-9\_]+)\_(singer|playlist|album|video|cat|song|profile)$/', $row['config_name'], $m)) {
                if (!isset(self::$configData[$m[1]])) {
                    self::$configData[$m[1]] = array();
                }
                self::$configData[$m[1]][$m[2]] = self::$configData[$row['config_name']];
                unset(self::$configData[$row['config_name']]);
            }
        }

        // Xác định ngôn ngữ mặc định của module
        self::$configData['default_language'] = $lang_data;
        $sql = new Db();
        $sql->setTable(self::getTableAlbum());
        $result = $sql->showColumns();

        $start_check = false;
        while ($row = $result->fetch()) {
            if ($row['field'] == 'status') {
                $start_check = true;
            } elseif ($start_check and preg_match("/^([a-z]{2})\_/", $row['field'], $m)) {
                self::$configData['default_language'] = $m[1];
            }
        }

        return self::$configData;
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDefaultLang()
    {
        return self::get(self::DEFAULT_LANG);
    }
}
