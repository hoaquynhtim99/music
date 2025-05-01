<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music;

/**
 * Cấu trúc CSDL
 *
 */
trait DBStruct
{
    /**
     * Các bảng dữ liệu
     *
     */
    private static $TABLE_SEPARATOR_CHARACTER = '_';
    private static $TABLE_ALBUM = 'albums';
    private static $TABLE_ARTIST = 'artists';
    private static $TABLE_CONFIG = 'config';
    private static $TABLE_CATEGORY = 'categories';
    private static $TABLE_NATION = 'nations';

    private static $TYPE_NUMBER = 'integer';
    private static $TYPE_ARRAY = 'array';
    private static $TYPE_TEXT = 'string';

    /**
     * Bảng quốc gia
     *
     * @return string
     */
    private static function getTableNation()
    {
        return Resources::getTablePrefix() . self::$TABLE_SEPARATOR_CHARACTER . self::$TABLE_NATION;
    }

    /**
     * Bảng cấu hình
     *
     * @return string
     */
    private static function getTableConfig()
    {
        return Resources::getTablePrefix() . self::$TABLE_SEPARATOR_CHARACTER . self::$TABLE_CONFIG;
    }

    /**
     * Bảng album
     *
     * @return string
     */
    private static function getTableAlbum()
    {
        return Resources::getTablePrefix() . self::$TABLE_SEPARATOR_CHARACTER . self::$TABLE_ALBUM;
    }

    /**
     * @param string $type
     * @throws Exception
     * @return number|array|string
     */
    private static function getDefaultValue($type)
    {
        switch ($type) {
            case self::$TYPE_NUMBER: return 0;
            case self::$TYPE_ARRAY: return [];
            case self::$TYPE_TEXT: return '';
        }
        throw new Exception('Wrong type!!!');
    }
}
