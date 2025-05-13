<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Category;

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\DBStruct as GDBStruct;

/**
 * Cấu trúc CSDL của danh mục
 *
 */
trait DBStruct
{
    use GDBStruct;

    /**
     * Các trường dữ liệu
     *
     */
    const FIELD_ID = 'cat_id';
    const FIELD_CODE = 'cat_code';
    const FIELD_RESOURCE_AVATAR = 'resource_avatar';
    const FIELD_RESOURCE_COVER = 'resource_cover';
    const FIELD_RESOURCE_VIDEO = 'resource_video';
    const FIELD_STAT_ALBUMS = 'stat_albums';
    const FIELD_STAT_SONGS = 'stat_songs';
    const FIELD_STAT_VIDEOS = 'stat_videos';
    const FIELD_TIME_ADD = 'time_add';
    const FIELD_TIME_UPDATE = 'time_update';
    const FIELD_SHOW_INALBUM = 'show_inalbum';
    const FIELD_SHOW_INVIDEO = 'show_invideo';
    const FIELD_WEIGHT = 'weight';
    const FIELD_STATUS = 'status';

    const LANG_FIELD_NAME = 'cat_name';
    const LANG_FIELD_ALIAS = 'cat_alias';
    const LANG_FIELD_ABSITETITLE = 'cat_absitetitle';
    const LANG_FIELD_ABINTROTEXT = 'cat_abintrotext';
    const LANG_FIELD_ABKEYWORDS = 'cat_abkeywords';
    const LANG_FIELD_MVSITETITLE = 'cat_mvsitetitle';
    const LANG_FIELD_MVINTROTEXT = 'cat_mvintrotext';
    const LANG_FIELD_MVKEYWORDS = 'cat_mvkeywords';

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
            self::FIELD_CODE,
            self::FIELD_RESOURCE_AVATAR,
            self::FIELD_RESOURCE_COVER,
            self::FIELD_RESOURCE_VIDEO,
            self::FIELD_STAT_ALBUMS,
            self::FIELD_STAT_SONGS,
            self::FIELD_STAT_VIDEOS,
            self::FIELD_TIME_ADD,
            self::FIELD_TIME_UPDATE,
            self::FIELD_SHOW_INALBUM,
            self::FIELD_SHOW_INVIDEO,
            self::FIELD_WEIGHT,
            self::FIELD_STATUS
        ];

        $sFields[] = $lang . '_' . self::LANG_FIELD_NAME . ' ' . self::LANG_FIELD_NAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ALIAS . ' ' . self::LANG_FIELD_ALIAS;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ABSITETITLE . ' ' . self::LANG_FIELD_ABSITETITLE;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ABINTROTEXT . ' ' . self::LANG_FIELD_ABINTROTEXT;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ABKEYWORDS . ' ' . self::LANG_FIELD_ABKEYWORDS;
        $sFields[] = $lang . '_' . self::LANG_FIELD_MVSITETITLE . ' ' . self::LANG_FIELD_MVSITETITLE;
        $sFields[] = $lang . '_' . self::LANG_FIELD_MVINTROTEXT . ' ' . self::LANG_FIELD_MVINTROTEXT;
        $sFields[] = $lang . '_' . self::LANG_FIELD_MVKEYWORDS . ' ' . self::LANG_FIELD_MVKEYWORDS;

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
            self::FIELD_CODE => self::TYPE_TEXT,
            self::FIELD_RESOURCE_AVATAR => self::TYPE_TEXT,
            self::FIELD_RESOURCE_COVER => self::TYPE_TEXT,
            self::FIELD_RESOURCE_VIDEO => self::TYPE_TEXT,
            self::FIELD_STAT_ALBUMS => self::TYPE_NUMBER,
            self::FIELD_STAT_SONGS => self::TYPE_NUMBER,
            self::FIELD_STAT_VIDEOS => self::TYPE_NUMBER,
            self::FIELD_TIME_ADD => self::TYPE_NUMBER,
            self::FIELD_TIME_UPDATE => self::TYPE_NUMBER,
            self::FIELD_SHOW_INALBUM => self::TYPE_NUMBER,
            self::FIELD_SHOW_INVIDEO => self::TYPE_NUMBER,
            self::FIELD_WEIGHT => self::TYPE_NUMBER,
            self::FIELD_STATUS => self::TYPE_NUMBER,

            self::LANG_FIELD_NAME => self::TYPE_TEXT,
            self::LANG_FIELD_ALIAS => self::TYPE_TEXT,
            self::LANG_FIELD_ABSITETITLE => self::TYPE_TEXT,
            self::LANG_FIELD_ABINTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_ABKEYWORDS => self::TYPE_TEXT,
            self::LANG_FIELD_MVSITETITLE => self::TYPE_TEXT,
            self::LANG_FIELD_MVINTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_MVKEYWORDS => self::TYPE_TEXT
        ];
    }
}
