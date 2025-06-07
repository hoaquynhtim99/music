<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Song;

use NukeViet\Module\music\Artist\DbLoader as DbLoaderArtist;
use NukeViet\Module\music\Category\DbLoader as DbLoaderCategory;
use NukeViet\Module\music\QualitySong\DbLoader as DbLoaderQuality;
use NukeViet\Module\music\Video\DbLoader as DbLoaderVideo;
use NukeViet\Module\music\ElementIType;
use NukeViet\Module\music\ElementTrait;
use NukeViet\Module\music\ErrorCodes;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Utils;

class Song implements ElementIType
{
    use DBStruct;
    use ElementTrait;

    /**
     * Các tệp bài hát
     *
     * @var ?Resource[]
     */
    private $resource = null;

    /**
     * Lời bài hát
     *
     * @var ?Caption
     */
    private $caption = null;

    /**
     * Xử lý và kiểm tra lỗi trước khi đưa vào DB
     *
     * @throws \NukeViet\Module\music\Song\Exception
     * @return void
     */
    protected function _validData()
    {
        $cats = DbLoaderCategory::loadAll();

        $this->data[self::FIELD_CAT_IDS] = array_intersect($this->data[self::FIELD_CAT_IDS], array_keys($cats));

        // Kiểm tra tệp tin tồn tại
        $checkFiles = [
            self::FIELD_RESOURCE_AVATAR,
            self::FIELD_RESOURCE_COVER,
        ];
        foreach ($checkFiles as $field) {
            if (!Utils::isUrl($this->data[$field]) and Utils::isFile($this->data[$field], Resources::getModUploadDir()) === true) {
                $this->data[$field] = substr($this->data[$field], strlen(Resources::getModUploadDirBase() . '/'));
            } elseif (!Utils::isUrl($this->data[$field])) {
                $this->data[$field] = '';
            }
        }

        $this->data[self::LANG_FIELD_ALIAS] = empty($this->data[self::LANG_FIELD_ALIAS]) ? Utils::getSlug($this->data[self::LANG_FIELD_NAME]) : Utils::getSlug($this->data[self::LANG_FIELD_ALIAS]);
        $this->data[self::LANG_FIELD_KEYWORDS] = trim(preg_replace('/\s[\s]+/u', ' ', strip_tags(Utils::nl2br(self::LANG_FIELD_KEYWORDS, ''))));

        // Nghệ sĩ hợp lệ
        $array_artist_ids = array_filter(array_unique(array_merge_recursive($this->data[self::FIELD_SINGER_IDS], $this->data[self::FIELD_AUTHOR_IDS])));
        $array_artists = DbLoaderArtist::gets($array_artist_ids);

        $singer_ids = $this->data[self::FIELD_SINGER_IDS];
        $author_ids = $this->data[self::FIELD_AUTHOR_IDS];
        $this->data[self::FIELD_SINGER_IDS] = $this->data[self::FIELD_AUTHOR_IDS] = [];
        foreach ($singer_ids as $_id) {
            if (isset($array_artists[$_id]) and !in_array($_id, $this->data[self::FIELD_SINGER_IDS])) {
                $this->data[self::FIELD_SINGER_IDS][] = $_id;
            }
        }
        foreach ($author_ids as $_id) {
            if (isset($array_artists[$_id]) and !in_array($_id, $this->data[self::FIELD_AUTHOR_IDS])) {
                $this->data[self::FIELD_AUTHOR_IDS][] = $_id;
            }
        }

        // Video liên quan hợp lệ
        if ($this->data[self::FIELD_VIDEO_ID] and !DbLoaderVideo::get($this->data[self::FIELD_VIDEO_ID])) {
            $this->data[self::FIELD_VIDEO_ID] = 0;
        }

        // Lỗi không có danh mục
        if (empty($this->data[self::FIELD_CAT_IDS])) {
            throw new Exception(self::FIELD_CAT_IDS, ErrorCodes::ERROR_SONG_CAT_EMPTY);
        }

        // Lỗi không có ca sĩ
        if (empty($this->data[self::FIELD_SINGER_IDS])) {
            throw new Exception(self::FIELD_SINGER_IDS, ErrorCodes::ERROR_SONG_SINGER_EMPTY);
        }

        // Lỗi không có tên bài hát
        if (empty($this->data[self::LANG_FIELD_NAME])) {
            throw new Exception(self::LANG_FIELD_NAME, ErrorCodes::ERROR_SONG_NAME_EMPTY);
        }

        $this->data[self::LANG_FIELD_SEARCHKEY] = Utils::getSearchKey($this->data[self::LANG_FIELD_NAME]);
        $this->data[self::LANG_FIELD_INTROTEXT] = Utils::nl2br($this->data[self::LANG_FIELD_INTROTEXT]);
    }

    /**
     * Đặt các tệp bài hát
     *
     * @param array $resources Key là ID chất lượng, value là đường dẫn đến tệp tin bắt đầu bằng NV_BASE_SITEURL
     * @throws Exception
     * @return Song
     */
    public function setResources(array $resources): Song
    {
        $qualities = DbLoaderQuality::loadAll();
        foreach ($resources as $quality_id => $path) {
            if (!isset($qualities[$quality_id])) {
                throw new Exception('', ErrorCodes::ERROR_SONG_QUALITY_NOT_FOUND);
            }

            if (Utils::isUrl($path)) {
                $this->resource[$quality_id] = new Resource($path, Resource::SERVER_REMOTE);
            } elseif (Utils::isFile($path, Resources::getModUploadDir()) === true) {
                $this->resource[$quality_id] = new Resource(substr($path, strlen(Resources::getModUploadDirBase() . '/')), Resource::SERVER_LOCAL);
            }
        }

        return $this;
    }

    /**
     * Đặt lời bài hát
     *
     * @param string $file Đường dẫn đến tệp tin bắt đầu bằng NV_BASE_SITEURL
     * @param string $pdf Đường dẫn đến tệp tin bắt đầu bằng NV_BASE_SITEURL
     * @param string $data HTML của lời bài hát
     * @return Song
     */
    public function setCaption(string $file = '', string $pdf = '', string $data = ''): Song
    {
        if (!Utils::isUrl($file) and Utils::isFile($file, Resources::getModUploadDir()) === true) {
            $file = substr($file, strlen(Resources::getModUploadDirBase() . '/'));
        } elseif (!Utils::isUrl($file)) {
            $file = '';
        }
        if (!Utils::isUrl($pdf) and Utils::isFile($pdf, Resources::getModUploadDir()) === true) {
            $pdf = substr($pdf, strlen(Resources::getModUploadDirBase() . '/'));
        } elseif (!Utils::isUrl($pdf)) {
            $pdf = '';
        }

        $this->caption = new Caption($file, $pdf, $data);
        return $this;
    }

    public function create(array $data = []): int|false
    {
        if (!empty($data)) {
            $this->loadFromArray($data);
        }
        $this->_validData();

        return 0;
    }

    public function update(int $id = 0, array $data = []): int|false
    {
        if (!empty($data)) {
            $this->loadFromArray($data);
        }
        $id > 0 && $this->setId($id);
        $this->_validData();

        return 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->data[self::FIELD_ID];
    }

    /**
     * @param int $id
     * @return Song
     */
    public function setId(int $id): Song
    {
        $this->data[self::FIELD_ID] = $id;
        return $this;
    }
}
