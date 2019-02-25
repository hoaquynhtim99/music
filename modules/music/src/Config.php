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
use NukeViet\Music\Config\CodePrefix;
use NukeViet\Music\Config\FuncsDescription;
use NukeViet\Music\Config\FuncsKeywords;
use NukeViet\Music\Config\FuncsSitetitle;
use NukeViet\Music\Config\OpAliasPrefix;
use NukeViet\Music\Config\SingerTabsAlias;

class Config
{
    use DBStruct;

    private const DEFAULT_LANG = 'default_language';
    private const DETAIL_SONG_ALBUMS_NUMS = 'detail_song_albums_nums';
    private const DETAIL_SONG_VIDEOS_NUMS = 'detail_song_videos_nums';
    private const LIMIT_AUTHORS_DISPLAYED = 'limit_authors_displayed';
    private const LIMIT_SINGERS_DISPLAYED = 'limit_singers_displayed';
    private const VARIOUS_ARTISTS = 'various_artists';
    private const VARIOUS_ARTISTS_AUTHORS = 'various_artists_authors';
    private const UNKNOW_AUTHOR = 'unknow_author';
    private const UNKNOW_SINGER = 'unknow_singer';
    private const UNKNOW_CAT = 'unknow_cat';
    private const SHAREPORT = 'shareport';
    private const ADDTHIS_PUBID = 'addthis_pubid';
    private const UPLOADS_FOLDER = 'uploads_folder';
    private const MSG_NOLYRIC = 'msg_nolyric';
    private const VIEW_SINGER_SHOW_HEADER = 'view_singer_show_header';
    private const VIEW_SINGER_HEADTEXT_LENGTH = 'view_singer_headtext_length';
    private const GIRD_ALBUMS_PERCAT_NUMS = 'gird_albums_percat_nums';
    private const GIRD_ALBUMS_INCAT_NUMS = 'gird_albums_incat_nums';
    private const GIRD_SINGERS_NUMS = 'gird_singers_nums';
    private const GIRD_VIDEOS_PERCAT_NUMS = 'gird_videos_percat_nums';
    private const GIRD_VIDEOS_INCAT_NUMS = 'gird_videos_incat_nums';
    private const VIEW_SINGER_MAIN_NUM_ALBUMS = 'view_singer_main_num_albums';
    private const VIEW_SINGER_DETAIL_NUM_ALBUMS = 'view_singer_detail_num_albums';
    private const VIEW_SINGER_MAIN_NUM_SONGS = 'view_singer_main_num_songs';
    private const VIEW_SINGER_DETAIL_NUM_SONGS = 'view_singer_detail_num_songs';
    private const VIEW_SINGER_MAIN_NUM_VIDEOS = 'view_singer_main_num_videos';
    private const VIEW_SINGER_DETAIL_NUM_VIDEOS = 'view_singer_detail_num_videos';

    private const AUTO_OPTIMIZE_ARTIST_NAME = 'auto_optimize_artist_name';
    private const AUTO_OPTIMIZE_VIDEO_NAME = 'auto_optimize_video_name';
    private const AUTO_OPTIMIZE_SONG_NAME = 'auto_optimize_song_name';
    private const AUTO_OPTIMIZE_ALBUM_NAME = 'auto_optimize_album_name';

    private const DEFAULT_ALBUM_AVATAR = 'res_default_album_avatar';
    private const DEFAULT_SINGER_AVATAR = 'res_default_singer_avatar';
    private const DEFAULT_AUTHOR_AVATAR = 'res_default_author_avatar';
    private const DEFAULT_VIDEO_AVATAR = 'res_default_video_avatar';

    private const CODE_PREFIX = 'code_prefix';
    private const OP_ALIAS_PREFIX = 'op_alias_prefix';
    private const VIEW_SINGER_TABS_ALIAS = 'view_singer_tabs_alias';
    private const FUNCS_SITETITLE = 'funcs_sitetitle';
    private const FUNCS_KEYWORDS = 'funcs_keywords';
    private const FUNCS_DESCRIPTION = 'funcs_description';

    private const FB_SHARE_IMAGE = 'fb_share_image';
    private const FB_SHARE_IMAGE_WITDH = 'fb_share_image_witdh';
    private const FB_SHARE_IMAGE_HEIGHT = 'fb_share_image_height';
    private const FB_SHARE_IMAGE_MIME = 'fb_share_image_mime';

    private const HOME_ALBUMS_DISPLAY = 'home_albums_display';
    private const HOME_ALBUMS_NUMS = 'home_albums_nums';
    private const HOME_SINGERS_DISPLAY = 'home_singers_display';
    private const HOME_SINGERS_NUMS = 'home_singers_nums';
    private const HOME_SONGS_DISPLAY = 'home_songs_display';
    private const HOME_SONGS_NUMS = 'home_songs_nums';
    private const HOME_VIDEOS_DISPLAY = 'home_videos_display';
    private const HOME_VIDEOS_NUMS = 'home_videos_nums';

    private const HOME_ALBUMS_WEIGHT = 'home_albums_weight';
    private const HOME_SINGERS_WEIGHT = 'home_singers_weight';
    private const HOME_SONGS_WEIGHT = 'home_songs_weight';
    private const HOME_VIDEOS_WEIGHT = 'home_videos_weight';

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
                    self::$configData[$m[1]] = [];
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

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDefaultAlbumAvatar()
    {
        return self::get(self::DEFAULT_ALBUM_AVATAR);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDefaultSingerAvatar()
    {
        return self::get(self::DEFAULT_SINGER_AVATAR);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDefaultAuthorAvatar()
    {
        return self::get(self::DEFAULT_AUTHOR_AVATAR);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDefaultVideoAvatar()
    {
        return self::get(self::DEFAULT_VIDEO_AVATAR);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getLimitSingersDisplayed()
    {
        return self::get(self::LIMIT_SINGERS_DISPLAYED);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getLimitAuthorsDisplayed()
    {
        return self::get(self::LIMIT_AUTHORS_DISPLAYED);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getVariousArtists()
    {
        return self::get(self::VARIOUS_ARTISTS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getVariousArtistsAuthors()
    {
        return self::get(self::VARIOUS_ARTISTS_AUTHORS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getUnknowSinger()
    {
        return self::get(self::UNKNOW_SINGER);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getUnknowCat()
    {
        return self::get(self::UNKNOW_CAT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getUnknowAuthor()
    {
        return self::get(self::UNKNOW_AUTHOR);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getFbShareImage()
    {
        return self::get(self::FB_SHARE_IMAGE);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getFbShareImageWidth()
    {
        return self::get(self::FB_SHARE_IMAGE_WITDH);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getFbShareImageHeight()
    {
        return self::get(self::FB_SHARE_IMAGE_HEIGHT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getFbShareImageMime()
    {
        return self::get(self::FB_SHARE_IMAGE_MIME);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeAlbumsDisplay()
    {
        return self::get(self::HOME_ALBUMS_DISPLAY);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeAlbumsNums()
    {
        return self::get(self::HOME_ALBUMS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSingersDisplay()
    {
        return self::get(self::HOME_SINGERS_DISPLAY);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSingersNums()
    {
        return self::get(self::HOME_SINGERS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSongsDisplay()
    {
        return self::get(self::HOME_SONGS_DISPLAY);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSongsNums()
    {
        return self::get(self::HOME_SONGS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeVideosDisplay()
    {
        return self::get(self::HOME_VIDEOS_DISPLAY);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeVideosNums()
    {
        return self::get(self::HOME_VIDEOS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeAlbumsWeight()
    {
        return self::get(self::HOME_ALBUMS_WEIGHT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSingersWeight()
    {
        return self::get(self::HOME_SINGERS_WEIGHT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeSongsWeight()
    {
        return self::get(self::HOME_SONGS_WEIGHT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getHomeVideosWeight()
    {
        return self::get(self::HOME_VIDEOS_WEIGHT);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerShowHeader()
    {
        return self::get(self::VIEW_SINGER_SHOW_HEADER);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerHeadtextLength()
    {
        return self::get(self::VIEW_SINGER_HEADTEXT_LENGTH);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getGirdAlbumsPercatNums()
    {
        return self::get(self::GIRD_ALBUMS_PERCAT_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getGirdAlbumsIncatNums()
    {
        return self::get(self::GIRD_ALBUMS_INCAT_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getGirdSingersNums()
    {
        return self::get(self::GIRD_SINGERS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getGirdVideosPercatNums()
    {
        return self::get(self::GIRD_VIDEOS_PERCAT_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getGirdVideosIncatNums()
    {
        return self::get(self::GIRD_VIDEOS_INCAT_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerMainNumAlbums()
    {
        return self::get(self::VIEW_SINGER_MAIN_NUM_ALBUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerDetailNumAlbums()
    {
        return self::get(self::VIEW_SINGER_DETAIL_NUM_ALBUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerMainNumSongs()
    {
        return self::get(self::VIEW_SINGER_MAIN_NUM_SONGS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerDetailNumSongs()
    {
        return self::get(self::VIEW_SINGER_DETAIL_NUM_SONGS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerMainNumVideos()
    {
        return self::get(self::VIEW_SINGER_MAIN_NUM_VIDEOS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getViewSingerDetailNumVideos()
    {
        return self::get(self::VIEW_SINGER_DETAIL_NUM_VIDEOS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDetailSongAlbumsNums()
    {
        return self::get(self::DETAIL_SONG_ALBUMS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getUploadsFolder()
    {
        return self::get(self::UPLOADS_FOLDER);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getDetailSongVideosNums()
    {
        return self::get(self::DETAIL_SONG_VIDEOS_NUMS);
    }

    /**
     * @return \NukeViet\Music\string~array
     */
    public static function getMsgNolyric()
    {
        return self::get(self::MSG_NOLYRIC);
    }

    /**
     * @return \NukeViet\Music\Config\CodePrefix
     */
    public static function getCodePrefix()
    {
        return new CodePrefix(self::get(self::CODE_PREFIX));
    }

    /**
     * @return \NukeViet\Music\Config\FuncsDescription
     */
    public static function getFuncsDescription()
    {
        return new FuncsDescription(self::get(self::FUNCS_DESCRIPTION));
    }

    /**
     * @return \NukeViet\Music\Config\FuncsKeywords
     */
    public static function getFuncsKeywords()
    {
        return new FuncsKeywords(self::get(self::FUNCS_KEYWORDS));
    }

    /**
     * @return \NukeViet\Music\Config\FuncsSitetitle
     */
    public static function getFuncsSitetitle()
    {
        return new FuncsSitetitle(self::get(self::FUNCS_SITETITLE));
    }

    /**
     * @return \NukeViet\Music\Config\OpAliasPrefix
     */
    public static function getOpAliasPrefix()
    {
        return new OpAliasPrefix(self::get(self::OP_ALIAS_PREFIX));
    }

    /**
     * @return \NukeViet\Music\Config\SingerTabsAlias
     */
    public static function getSingerTabsAlias()
    {
        return new SingerTabsAlias(self::get(self::VIEW_SINGER_TABS_ALIAS));
    }

    /**
     * @param mixed $var
     */
    public static function setDetailSongAlbumsNums($var)
    {
        self::$configData[self::DETAIL_SONG_ALBUMS_NUMS] = intval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setDetailSongVideosNums($var)
    {
        self::$configData[self::DETAIL_SONG_VIDEOS_NUMS] = intval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setLimitAuthorsDisplayed($var)
    {
        self::$configData[self::LIMIT_AUTHORS_DISPLAYED] = intval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setVariousArtistsAuthors($var)
    {
        self::$configData[self::VARIOUS_ARTISTS_AUTHORS] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setUnknowAuthor($var)
    {
        self::$configData[self::UNKNOW_AUTHOR] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setUnknowCat($var)
    {
        self::$configData[self::UNKNOW_CAT] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setShareport($var)
    {
        self::$configData[self::SHAREPORT] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setAddthisPubid($var)
    {
        self::$configData[self::ADDTHIS_PUBID] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setUploadsFolder($var)
    {
        self::$configData[self::UPLOADS_FOLDER] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setMsgNolyric($var)
    {
        self::$configData[self::MSG_NOLYRIC] = strval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setAutoOptimizeArtistName($var)
    {
        self::$configData[self::AUTO_OPTIMIZE_ARTIST_NAME] = boolval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setAutoOptimizeVideoName($var)
    {
        self::$configData[self::AUTO_OPTIMIZE_VIDEO_NAME] = boolval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setAutoOptimizeSongName($var)
    {
        self::$configData[self::AUTO_OPTIMIZE_SONG_NAME] = boolval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setAutoOptimizeAlbumName($var)
    {
        self::$configData[self::AUTO_OPTIMIZE_ALBUM_NAME] = boolval($var);
    }

    /**
     * @param mixed $var
     */
    public static function setGirdSingersNums($var)
    {
        self::$configData[self::GIRD_SINGERS_NUMS] = intval($var);
    }
}
