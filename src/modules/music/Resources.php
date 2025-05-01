<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Module\music;

/**
 * Các tài nguyên của hệ thống NukeViet cần có để module hoạt động
 * Load các tài nguyên này trước khi module có thể hoạt động
 *
 * @since 4.3.00
 */
class Resources implements Settings
{
    /**
     * Thư mục chứa site kết thúc bằng ký tự /
     *
     * @var string
     */
    private static $baseSiteUrl = '/';

    /**
     * Khóa biến $_GET ngôn ngữ
     *
     * @var string
     */
    private static $langVariable = 'language';

    /**
     * Khóa biến $_GET module
     *
     * @var string
     */
    private static $nameVariable = 'nv';

    /**
     * Khóa biến $_GET func của module
     *
     * @var string
     */
    private static $opVariable = 'op';

    /**
     * Ngôn ngữ giao diện đang xử lý.
     *
     * @var string
     */
    private static $langInterface = 'vi';

    /**
     * Ngôn ngữ CSDL đang xử lý.
     *
     * @var string
     */
    private static $langData = 'vi';

    /**
     * Ngôn ngữ CSDL đang xử lý.
     *
     * @var object
     */
    private static $db = null;

    /**
     * Đầu tố bảng dữ liệu của csdl site.
     *
     * @var string
     */
    private static $dbPrefix = '';

    /**
     * Tên module
     *
     * @var string
     */
    private static $moduleName = 'music';

    /**
     * Thông tin module hệ thống
     *
     * @var string
     */
    private static $siteMods = [];

    /**
     * Resources::setLangInterface()
     *
     * @param mixed $lang
     * @return
     */
    public static function setLangInterface($lang)
    {
        self::$langInterface = $lang;

        return true;
    }

    /**
     * Resources::setLangData()
     *
     * @param mixed $lang
     * @return
     */
    public static function setLangData($lang)
    {
        self::$langData = $lang;

        return true;
    }

    /**
     * Resources::setDb()
     *
     * @param mixed $db
     * @return
     */
    public static function setDb($db)
    {
        self::$db = $db;

        return true;
    }

    /**
     * @param string $prefix
     * @return boolean
     */
    public static function setDbPrefix($prefix)
    {
        self::$dbPrefix = $prefix;

        return true;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public static function setBaseSiteUrl(string $string)
    {
        self::$baseSiteUrl = $string;

        return true;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public static function setLangVariable(string $string)
    {
        self::$langVariable = $string;

        return true;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public static function setNameVariable(string $string)
    {
        self::$nameVariable = $string;

        return true;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public static function setOpVariable(string $string)
    {
        self::$opVariable = $string;

        return true;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public static function setModuleName(string $string)
    {
        self::$moduleName = $string;

        return true;
    }

    /**
     * @param array $array
     * @return boolean
     */
    public static function setSiteMods(array $array)
    {
        self::$siteMods = $array;

        return true;
    }

    /**
     * Resources::getLangInterface()
     *
     * @return
     */
    public static function getLangInterface()
    {
        return self::$langInterface;
    }

    /**
     * Resources::getLangData()
     *
     * @return
     */
    public static function getLangData()
    {
        return self::$langData;
    }

    /**
     * Resources::getDb()
     *
     * @return object
     */
    public static function getDb()
    {
        return self::$db;
    }

    /**
     * Resources::getTablePrefix()
     *
     * @return string
     */
    public static function getTablePrefix()
    {
        return self::$dbPrefix . '_' . self::$siteMods[self::$moduleName]['module_data'];
    }

    /**
     * @return string
     */
    public static function getDbPrefix()
    {
        return self::$dbPrefix;
    }

    /**
     * Link trả về có dấu = cuối cùng
     *
     * @return string
     */
    public static function getModFullLinkEncode()
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&amp;' . self::$nameVariable . '=' . self::$moduleName . '&amp;' . self::$opVariable . '=';
    }

    /**
     * @return string
     */
    public static function getModLinkEncode()
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&amp;' . self::$nameVariable . '=' . self::$moduleName;
    }

    /**
     * Link trả về có dấu = cuối cùng
     *
     * @return string
     */
    public static function getModFullLink()
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&' . self::$nameVariable . '=' . self::$moduleName . '&' . self::$opVariable . '=';
    }

    /**
     * @return string
     */
    public static function getModLink()
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&' . self::$nameVariable . '=' . self::$moduleName;
    }

    /**
     * @return array
     */
    public static function getModInfo()
    {
        return self::$siteMods[self::$moduleName];
    }

    /**
     * @return array
     */
    public static function getModData()
    {
        return self::$siteMods[self::$moduleName]['module_data'];
    }

    /**
     * @return array
     */
    public static function getModUpload()
    {
        return self::$siteMods[self::$moduleName]['module_upload'];
    }
}
