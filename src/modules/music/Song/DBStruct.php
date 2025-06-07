<?php

namespace NukeViet\Module\music\Song;

use NukeViet\Module\music\Config;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\DBStruct as GDBStruct;

/**
 * Cấu trúc CSDL của bài hát
 *
 */
trait DBStruct
{
    use GDBStruct;

    /**
     * Các trường dữ liệu
     *
     */
    const FIELD_ID = 'song_id';
    const FIELD_CODE = 'song_code';
    const FIELD_CAT_IDS = 'cat_ids';
    const FIELD_SINGER_IDS = 'singer_ids';
    const FIELD_AUTHOR_IDS = 'author_ids';
    const FIELD_ALBUM_IDS = 'album_ids';
    const FIELD_VIDEO_ID = 'video_id';
    const FIELD_RESOURCE_AVATAR = 'resource_avatar';
    const FIELD_RESOURCE_COVER = 'resource_cover';
    const FIELD_UPLOADER_ID = 'uploader_id';
    const FIELD_UPLOADER_NAME = 'uploader_name';
    const FIELD_STAT_VIEWS = 'stat_views';
    const FIELD_STAT_LIKES = 'stat_likes';
    const FIELD_STAT_COMMENTS = 'stat_comments';
    const FIELD_STAT_SHARES = 'stat_shares';
    const FIELD_STAT_DOWNLOADS = 'stat_downloads';
    const FIELD_STAT_HIT = 'stat_hit';
    const FIELD_TIME_ADD = 'time_add';
    const FIELD_TIME_UPDATE = 'time_update';
    const FIELD_IS_OFFICIAL = 'is_official';
    const FIELD_SHOW_INHOME = 'show_inhome';
    const FIELD_CAPTION_SUPPORTED = 'caption_supported';
    const FIELD_STATUS = 'status';

    const LANG_FIELD_NAME = 'song_name';
    const LANG_FIELD_ALIAS = 'song_alias';
    const LANG_FIELD_SEARCHKEY = 'song_searchkey';
    const LANG_FIELD_INTROTEXT = 'song_introtext';
    const LANG_FIELD_KEYWORDS = 'song_keywords';

    /**
     * @param boolean $full
     * @return string[]
     */
    private static function getBasicFields($full = false)
    {
        $fields = [
            self::FIELD_ID,
            self::FIELD_CODE,
            self::FIELD_CAT_IDS,
            self::FIELD_SINGER_IDS,
            self::FIELD_AUTHOR_IDS,
            self::FIELD_ALBUM_IDS,
            self::FIELD_VIDEO_ID,
            self::FIELD_RESOURCE_AVATAR,
            self::FIELD_RESOURCE_COVER,
            self::FIELD_UPLOADER_ID,
            self::FIELD_UPLOADER_NAME,
            self::FIELD_STAT_VIEWS,
            self::FIELD_STAT_LIKES,
            self::FIELD_STAT_COMMENTS,
            self::FIELD_STAT_SHARES,
            self::FIELD_STAT_DOWNLOADS,
            self::FIELD_STAT_HIT,
            self::FIELD_TIME_ADD,
            self::FIELD_TIME_UPDATE,
            self::FIELD_IS_OFFICIAL,
            self::FIELD_SHOW_INHOME,
            self::FIELD_CAPTION_SUPPORTED,
            self::FIELD_STATUS,
            self::LANG_FIELD_NAME,
            self::LANG_FIELD_ALIAS,
            self::LANG_FIELD_SEARCHKEY,
            self::LANG_FIELD_INTROTEXT,
            self::LANG_FIELD_KEYWORDS
        ];

        return $fields;
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
            self::FIELD_CAT_IDS => self::TYPE_ARRAY,
            self::FIELD_SINGER_IDS => self::TYPE_ARRAY,
            self::FIELD_AUTHOR_IDS => self::TYPE_ARRAY,
            self::FIELD_ALBUM_IDS => self::TYPE_ARRAY,
            self::FIELD_VIDEO_ID => self::TYPE_NUMBER,
            self::FIELD_RESOURCE_AVATAR => self::TYPE_TEXT,
            self::FIELD_RESOURCE_COVER => self::TYPE_TEXT,
            self::FIELD_UPLOADER_ID => self::TYPE_NUMBER,
            self::FIELD_UPLOADER_NAME => self::TYPE_TEXT,
            self::FIELD_STAT_VIEWS => self::TYPE_NUMBER,
            self::FIELD_STAT_LIKES => self::TYPE_NUMBER,
            self::FIELD_STAT_COMMENTS => self::TYPE_NUMBER,
            self::FIELD_STAT_SHARES => self::TYPE_NUMBER,
            self::FIELD_STAT_DOWNLOADS => self::TYPE_NUMBER,
            self::FIELD_STAT_HIT => self::TYPE_NUMBER,
            self::FIELD_TIME_ADD => self::TYPE_NUMBER,
            self::FIELD_TIME_UPDATE => self::TYPE_NUMBER,
            self::FIELD_IS_OFFICIAL => self::TYPE_NUMBER,
            self::FIELD_SHOW_INHOME => self::TYPE_NUMBER,
            self::FIELD_CAPTION_SUPPORTED => self::TYPE_TEXT,
            self::FIELD_STATUS => self::TYPE_NUMBER,
            self::LANG_FIELD_NAME => self::TYPE_TEXT,
            self::LANG_FIELD_ALIAS => self::TYPE_TEXT,
            self::LANG_FIELD_SEARCHKEY => self::TYPE_TEXT,
            self::LANG_FIELD_INTROTEXT => self::TYPE_TEXT,
            self::LANG_FIELD_KEYWORDS => self::TYPE_TEXT
        ];
    }
}
