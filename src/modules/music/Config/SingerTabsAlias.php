<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Module\music\Config;

use NukeViet\Module\music\Exception;

class SingerTabsAlias
{
    private $data = [];

    const PROFILE = 'profile';
    const ALBUM = 'album';
    const VIDEO = 'video';
    const SONG = 'song';

    private $allKeys = [
        self::PROFILE,
        self::ALBUM,
        self::VIDEO,
        self::SONG
    ];

    /**
     * @param array|string $data
     * @throws Exception
     */
    public function __construct($data)
    {
        if (!is_array($data)) {
            throw new Exception('Wrong data!!!');
        }
        $this->data = $data;
    }

    /**
     * @param string $key
     * @throws Exception
     * @return string~array
     */
    private function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        throw new Exception('Wrong config data!!!');
    }

    /**
     * @param string $key
     * @throws Exception
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getTabByKey($key)
    {
        if (empty($key) or !isset($this->data[$key])) {
            throw new Exception('Wrong key!!!');
        }
        return $this->get($key);
    }

    /**
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getAlbum()
    {
        return $this->get(self::ALBUM);
    }

    /**
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getVideo()
    {
        return $this->get(self::VIDEO);
    }

    /**
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getSong()
    {
        return $this->get(self::SONG);
    }

    /**
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getProfile()
    {
        return $this->get(self::PROFILE);
    }

    /**
     * @return array|array[]|string[]
     */
    public function getAllTabs()
    {
        return $this->data;
    }
}
