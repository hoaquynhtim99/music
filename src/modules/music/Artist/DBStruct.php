<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Artist;

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
    const FIELD_ID = 'artist_id';
    const FIELD_CODE = 'artist_code';
    const FIELD_TYPE = 'artist_type';
    const FIELD_BIRTHDAY = 'artist_birthday';
    const FIELD_BIRTHDAY_LEV = 'artist_birthday_lev';
    const FIELD_NATION_ID = 'nation_id';
    const FIELD_RESOURCE_AVATAR = 'resource_avatar';
    const FIELD_RESOURCE_COVER = 'resource_cover';
    const FIELD_STAT_SINGER_ALBUMS = 'stat_singer_albums';
    const FIELD_STAT_SINGER_SONGS = 'stat_singer_songs';
    const FIELD_STAT_SINGER_VIDEOS = 'stat_singer_videos';
    const FIELD_STAT_AUTHOR_SONGS = 'stat_author_songs';
    const FIELD_STAT_AUTHOR_VIDEOS = 'stat_author_videos';
    const FIELD_TIME_ADD = 'time_add';
    const FIELD_TIME_UPDATE = 'time_update';
    const FIELD_SHOW_INHOME = 'show_inhome';
    const FIELD_STATUS = 'status';

    const LANG_FIELD_NAME = 'artist_name';
    const LANG_FIELD_ALIAS = 'artist_alias';
    const LANG_FIELD_ALPHABET = 'artist_alphabet';
    const LANG_FIELD_SEARCHKEY = 'artist_searchkey';
    const LANG_FIELD_REALNAME = 'artist_realname';
    const LANG_FIELD_HOMETOWN = 'artist_hometown';
    const LANG_FIELD_SINGER_NICKNAME = 'singer_nickname';
    const LANG_FIELD_SINGER_PRIZE = 'singer_prize';
    const LANG_FIELD_SINGER_INFO = 'singer_info';
    const LANG_FIELD_SINGER_INTROTEXT = 'singer_introtext';
    const LANG_FIELD_SINGER_KEYWORDS = 'singer_keywords';
    const LANG_FIELD_AUTHOR_NICKNAME = 'author_nickname';
    const LANG_FIELD_AUTHOR_PRIZE = 'author_prize';
    const LANG_FIELD_AUTHOR_INFO = 'author_info';
    const LANG_FIELD_AUTHOR_INTROTEXT = 'author_introtext';
    const LANG_FIELD_AUTHOR_KEYWORDS = 'author_keywords';

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
            self::FIELD_TYPE,
            self::FIELD_BIRTHDAY,
            self::FIELD_BIRTHDAY_LEV,
            self::FIELD_NATION_ID,
            self::FIELD_RESOURCE_AVATAR,
            self::FIELD_RESOURCE_COVER,
            self::FIELD_STAT_SINGER_ALBUMS,
            self::FIELD_STAT_SINGER_SONGS,
            self::FIELD_STAT_SINGER_VIDEOS,
            self::FIELD_STAT_AUTHOR_SONGS,
            self::FIELD_STAT_AUTHOR_VIDEOS,
            self::FIELD_TIME_ADD,
            self::FIELD_TIME_UPDATE,
            self::FIELD_SHOW_INHOME,
            self::FIELD_STATUS
        ];

        $sFields[] = $lang . '_' . self::LANG_FIELD_NAME . ' ' . self::LANG_FIELD_NAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ALIAS . ' ' . self::LANG_FIELD_ALIAS;
        $sFields[] = $lang . '_' . self::LANG_FIELD_ALPHABET . ' ' . self::LANG_FIELD_ALPHABET;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SEARCHKEY . ' ' . self::LANG_FIELD_SEARCHKEY;
        $sFields[] = $lang . '_' . self::LANG_FIELD_REALNAME . ' ' . self::LANG_FIELD_REALNAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_HOMETOWN . ' ' . self::LANG_FIELD_HOMETOWN;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SINGER_NICKNAME . ' ' . self::LANG_FIELD_SINGER_NICKNAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SINGER_PRIZE . ' ' . self::LANG_FIELD_SINGER_PRIZE;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SINGER_INFO . ' ' . self::LANG_FIELD_SINGER_INFO;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SINGER_INTROTEXT . ' ' . self::LANG_FIELD_SINGER_INTROTEXT;
        $sFields[] = $lang . '_' . self::LANG_FIELD_SINGER_KEYWORDS . ' ' . self::LANG_FIELD_SINGER_KEYWORDS;
        $sFields[] = $lang . '_' . self::LANG_FIELD_AUTHOR_NICKNAME . ' ' . self::LANG_FIELD_AUTHOR_NICKNAME;
        $sFields[] = $lang . '_' . self::LANG_FIELD_AUTHOR_PRIZE . ' ' . self::LANG_FIELD_AUTHOR_PRIZE;
        $sFields[] = $lang . '_' . self::LANG_FIELD_AUTHOR_INFO . ' ' . self::LANG_FIELD_AUTHOR_INFO;
        $sFields[] = $lang . '_' . self::LANG_FIELD_AUTHOR_INTROTEXT . ' ' . self::LANG_FIELD_AUTHOR_INTROTEXT;
        $sFields[] = $lang . '_' . self::LANG_FIELD_AUTHOR_KEYWORDS . ' ' . self::LANG_FIELD_AUTHOR_KEYWORDS;

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
            self::FIELD_TYPE => self::TYPE_NUMBER,
            self::FIELD_BIRTHDAY => self::TYPE_NUMBER,
            self::FIELD_BIRTHDAY_LEV => self::TYPE_NUMBER,
            self::FIELD_NATION_ID => self::TYPE_NUMBER,
            self::FIELD_RESOURCE_AVATAR => self::TYPE_TEXT,
            self::FIELD_RESOURCE_COVER => self::TYPE_TEXT,
            self::FIELD_STAT_SINGER_ALBUMS => self::TYPE_NUMBER,
            self::FIELD_STAT_SINGER_SONGS => self::TYPE_NUMBER,
            self::FIELD_STAT_SINGER_VIDEOS => self::TYPE_NUMBER,
            self::FIELD_STAT_AUTHOR_SONGS => self::TYPE_NUMBER,
            self::FIELD_STAT_AUTHOR_VIDEOS => self::TYPE_NUMBER,
            self::FIELD_TIME_ADD => self::TYPE_NUMBER,
            self::FIELD_TIME_UPDATE => self::TYPE_NUMBER,
            self::FIELD_SHOW_INHOME => self::TYPE_NUMBER,
            self::FIELD_STATUS => self::TYPE_NUMBER,

            self::LANG_FIELD_NAME => self::TYPE_TEXT,
            self::LANG_FIELD_ALIAS => self::TYPE_TEXT,
            self::LANG_FIELD_ALPHABET => self::TYPE_TEXT,
            self::LANG_FIELD_SEARCHKEY => self::TYPE_TEXT,
            self::LANG_FIELD_REALNAME => self::TYPE_TEXT,
            self::LANG_FIELD_HOMETOWN => self::TYPE_TEXT,
            self::LANG_FIELD_SINGER_NICKNAME => self::TYPE_TEXT,
            self::LANG_FIELD_SINGER_PRIZE => self::TYPE_TEXT,
            self::LANG_FIELD_SINGER_INFO => self::TYPE_TEXT,
            self::LANG_FIELD_SINGER_INTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_SINGER_KEYWORDS => self::TYPE_TEXT,
            self::LANG_FIELD_AUTHOR_NICKNAME => self::TYPE_TEXT,
            self::LANG_FIELD_AUTHOR_PRIZE => self::TYPE_TEXT,
            self::LANG_FIELD_AUTHOR_INFO => self::TYPE_TEXT,
            self::LANG_FIELD_AUTHOR_INTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_AUTHOR_KEYWORDS => self::TYPE_TEXT
        ];
    }
}
