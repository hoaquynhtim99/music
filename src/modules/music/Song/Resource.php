<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Song;

class Resource
{
    /**
     * @var string Đường dẫn tương đối đến tệp tin
     */
    private string $path;

    private int $server_id;

    const SERVER_REMOTE = -1;
    const SERVER_LOCAL = 0;

    /**
     * @param string $path
     * @param int $server_id
     */
    public function __construct(string $path, int $server_id)
    {
        $this->path = $path;
        $this->server_id = $server_id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getServerId(): int
    {
        return $this->server_id;
    }

    /**
     * @param string $path
     * @return Resource
     */
    public function setPath(string $path): Resource
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param int $server_id
     * @return Resource
     */
    public function setServerId(int $server_id): Resource
    {
        $this->server_id = $server_id;
        return $this;
    }
}
