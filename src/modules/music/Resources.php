<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music;

use NukeViet\Core\Database;

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
     * @var string Thư mục upload
     */
    private static $uploadDir = 'upload';

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
     * @var ?Database
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
     * Thư mục upload của module
     *
     * @var string
     */
    private static $moduleUpload = 'music';

    /**
     * Thông tin module hệ thống
     *
     * @var array
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
     * @param Database $db
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
     * @return true
     */
    public static function setUploadDir(string $string)
    {
        self::$uploadDir = $string;
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
     * @param string $string
     * @return boolean
     */
    public static function setModuleUpload(string $string)
    {
        self::$moduleUpload = $string;
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
     * @return Database|null
     */
    public static function getDb(): Database|null
    {
        return self::$db;
    }

    /**
     * Resources::getTablePrefix()
     *
     * @return string
     */
    public static function getTablePrefix(): string
    {
        return self::$dbPrefix . '_' . self::$siteMods[self::$moduleName]['module_data'];
    }

    /**
     * @return string
     */
    public static function getDbPrefix(): string
    {
        return self::$dbPrefix;
    }

    /**
     * Link trả về có dấu = cuối cùng
     *
     * @return string
     */
    public static function getModFullLinkEncode(): string
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&amp;' . self::$nameVariable . '=' . self::$moduleName . '&amp;' . self::$opVariable . '=';
    }

    /**
     * @return string
     */
    public static function getModLinkEncode(): string
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&amp;' . self::$nameVariable . '=' . self::$moduleName;
    }

    /**
     * Link trả về có dấu = cuối cùng
     *
     * @return string
     */
    public static function getModFullLink(): string
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&' . self::$nameVariable . '=' . self::$moduleName . '&' . self::$opVariable . '=';
    }

    /**
     * @return string
     */
    public static function getModLink(): string
    {
        return self::$baseSiteUrl . 'index.php?' . self::$langVariable . '=' . self::$langData . '&' . self::$nameVariable . '=' . self::$moduleName;
    }

    /**
     * @return string
     */
    public static function getModInfo(): array
    {
        return self::$siteMods[self::$moduleName];
    }

    /**
     * @return string
     */
    public static function getModData(): string
    {
        return self::$siteMods[self::$moduleName]['module_data'];
    }

    /**
     * @return string
     */
    public static function getModUpload(): string
    {
        return self::$siteMods[self::$moduleName]['module_upload'];
    }

    /**
     * Thư mục upload của module. Không có dấu / ở cuối
     *
     * @return string
     */
    public static function getModUploadDir(): string
    {
        return self::$uploadDir . '/' . self::getModUpload();
    }

    /**
     * Thư mục upload của module, không có dấu / ở cuối nối với base url ở đầu
     *
     * @return string
     */
    public static function getModUploadDirBase(): string
    {
        return self::$baseSiteUrl . self::$uploadDir . '/' . self::getModUpload();
    }
}
