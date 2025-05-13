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
    const TABLE_SEPARATOR_CHARACTER = '_';
    const TABLE_ALBUM = 'albums';
    const TABLE_ARTIST = 'artists';
    const TABLE_CONFIG = 'config';
    const TABLE_CATEGORY = 'categories';
    const TABLE_NATION = 'nations';
    const TABLE_VIDEO = 'videos';

    const TYPE_NUMBER = 'integer';
    const TYPE_ARRAY = 'array';
    const TYPE_TEXT = 'string';

    /**
     * Bảng thể loại
     *
     * @return string
     */
    private static function getTableCategory()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_CATEGORY;
    }

    /**
     * Bảng quốc gia
     *
     * @return string
     */
    private static function getTableNation()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_NATION;
    }

    /**
     * Bảng cấu hình
     *
     * @return string
     */
    private static function getTableConfig()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_CONFIG;
    }

    /**
     * Bảng album
     *
     * @return string
     */
    private static function getTableAlbum()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_ALBUM;
    }

    /**
     * Bảng nghệ sĩ
     *
     * @return string
     */
    private static function getTableArtist()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_ARTIST;
    }

    /**
     * Bảng video
     *
     * @return string
     */
    private static function getTableVideo()
    {
        return Resources::getTablePrefix() . self::TABLE_SEPARATOR_CHARACTER . self::TABLE_VIDEO;
    }

    /**
     * @param string $type
     * @throws Exception
     * @return number|array|string
     */
    private static function getDefaultValue($type)
    {
        switch ($type) {
            case self::TYPE_NUMBER: return 0;
            case self::TYPE_ARRAY: return [];
            case self::TYPE_TEXT: return '';
        }
        throw new Exception('Wrong type!!!');
    }
}
