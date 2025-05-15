<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\QualitySong;

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\DBStruct as GDBStruct;

trait DBStruct
{
    use GDBStruct;

    /**
     * Các trường dữ liệu
     *
     */
    const FIELD_ID = 'quality_id';
    const FIELD_TIME_ADD = 'time_add';
    const FIELD_TIME_UPDATE = 'time_update';
    const FIELD_ONLINE_SUPPORTED = 'online_supported';
    const FIELD_IS_DEFAULT = 'is_default';
    const FIELD_WEIGHT = 'weight';
    const FIELD_STATUS = 'status';

    const LANG_FIELD_NAME = 'quality_name';
    const LANG_FIELD_ALIAS = 'quality_alias';

    /**
     * @param bool $full
     * @return string[]
     */
    private static function getBasicFields(bool $full = false)
    {
        $dLang = Config::getDefaultLang();
        $lang = Resources::getLangData();

        $sFields = [
            self::FIELD_ID,
            self::FIELD_TIME_ADD,
            self::FIELD_TIME_UPDATE,
            self::FIELD_ONLINE_SUPPORTED,
            self::FIELD_IS_DEFAULT,
            self::FIELD_WEIGHT,
            self::FIELD_STATUS
        ];

        $sFields[] = $lang . '_' . self::LANG_FIELD_NAME . ' ' . self::LANG_FIELD_NAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ALIAS . ' ' . self::LANG_FIELD_ALIAS;

        return $sFields;
    }

    /**
     * @return array
     */
    private static function getFullFields()
    {
        return self::getBasicFields(true);
    }

    /**
     * @return string[]
     */
    private static function buildObjectKeys()
    {
        return [
            self::FIELD_ID => self::TYPE_NUMBER,
            self::FIELD_TIME_ADD => self::TYPE_NUMBER,
            self::FIELD_TIME_UPDATE => self::TYPE_NUMBER,
            self::FIELD_ONLINE_SUPPORTED => self::TYPE_NUMBER,
            self::FIELD_IS_DEFAULT => self::TYPE_NUMBER,
            self::FIELD_WEIGHT => self::TYPE_NUMBER,
            self::FIELD_STATUS => self::TYPE_NUMBER,

            self::LANG_FIELD_NAME => self::TYPE_TEXT,
            self::LANG_FIELD_ALIAS => self::TYPE_TEXT
        ];
    }
}
