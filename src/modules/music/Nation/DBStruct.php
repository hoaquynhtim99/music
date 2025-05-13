<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Nation;

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\DBStruct as GDBStruct;

/**
 * Cấu trúc CSDL
 *
 */
trait DBStruct
{
    use GDBStruct;

    /**
     * Các trường dữ liệu
     *
     */
    const FIELD_ID = 'nation_id';
    const FIELD_CODE = 'nation_code';
    const FIELD_SINGER_STAT = 'stat_singers';
    const FIELD_AUTHOR_STAT = 'stat_authors';
    const FIELD_ADDTIME = 'time_add';
    const FIELD_UPDATETIME = 'time_update';
    const FIELD_STATUS = 'status';
    const FIELD_WEIGHT = 'weight';

    const LANG_FIELD_NAME = 'nation_name';
    const LANG_FIELD_ALIAS = 'nation_alias';
    const LANG_FIELD_INTROTEXT = 'nation_introtext';
    const LANG_FIELD_KEYWORD = 'nation_keywords';

    /**
     * @param boolean $full
     * @return string[]
     */
    private static function getBasicFields($full = false)
    {
        $dLang = Config::getDefaultLang();
        $lang = Resources::getLangData();

        $sFields = [
            self::FIELD_ID,
            self::FIELD_CODE,
            self::FIELD_SINGER_STAT,
            self::FIELD_AUTHOR_STAT,
            self::FIELD_ADDTIME,
            self::FIELD_UPDATETIME,
            self::FIELD_STATUS,
            self::FIELD_WEIGHT
        ];

        $sFields[] = $lang . '_' . self::LANG_FIELD_NAME . ' ' . self::LANG_FIELD_NAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ALIAS . ' ' . self::LANG_FIELD_ALIAS;
        $sFields[] = $lang . '_' . self::LANG_FIELD_INTROTEXT . ' ' . self::LANG_FIELD_INTROTEXT;
        $sFields[] = $lang . '_' . self::LANG_FIELD_KEYWORD . ' ' . self::LANG_FIELD_KEYWORD;

        if ($lang != $dLang) {
            $keys = self::getBasicLangField($full);
            foreach ($keys as $key) {
                $sFields[] = $dLang . '_' . $key . ' default_' . $key;
            }
        }

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
     * @param boolean $full
     * @return string[]
     */
    private static function getBasicLangField($full = false)
    {
        $lFields = [
            self::LANG_FIELD_NAME,
            self::LANG_FIELD_ALIAS,
            self::LANG_FIELD_INTROTEXT,
            self::LANG_FIELD_KEYWORD
        ];
        return $lFields;
    }

    /**
     * @return string[]
     */
    private static function getFullLangField()
    {
        return self::getBasicLangField(true);
    }

    /**
     * @return string[]
     */
    private static function buildObjectKeys()
    {
        return [
            self::FIELD_ID => self::TYPE_NUMBER,
            self::FIELD_CODE => self::TYPE_TEXT,
            self::FIELD_SINGER_STAT => self::TYPE_NUMBER,
            self::FIELD_AUTHOR_STAT => self::TYPE_NUMBER,
            self::FIELD_ADDTIME => self::TYPE_NUMBER,
            self::FIELD_UPDATETIME => self::TYPE_NUMBER,
            self::FIELD_WEIGHT => self::TYPE_NUMBER,
            self::FIELD_STATUS => self::TYPE_NUMBER,
            self::LANG_FIELD_NAME => self::TYPE_TEXT,
            self::LANG_FIELD_ALIAS => self::TYPE_TEXT,
            self::LANG_FIELD_INTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_KEYWORD => self::TYPE_TEXT
        ];
    }
}
