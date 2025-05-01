<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Config;

use NukeViet\Module\music\Exception;

class CodePrefix
{
    private $data = [];

    const SINGER = 'singer';
    const PLAYLIST = 'playlist';
    const ALBUM = 'album';
    const VIDEO = 'video';
    const SONG = 'song';
    const CAT = 'cat';

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
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getSinger()
    {
        return $this->get(self::SINGER);
    }

    /**
     * @return \NukeViet\Module\music\Config\string~array
     */
    public function getPlaylist()
    {
        return $this->get(self::PLAYLIST);
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
    public function getCat()
    {
        return $this->get(self::CAT);
    }
}
