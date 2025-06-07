<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Song;

use NukeViet\Module\music\Utils;

class Caption
{
    /**
     * @var string Tệp lời bài hát
     */
    private string $file;

    /**
     * @var string Tệp PDF lời bài hát
     */
    private string $pdf;

    /**
     * @var string Lời dạng HTML sẵn sàng hiển thị
     */
    private string $data;

    /**
     * @param string $file
     * @param string $pdf
     * @param string $data
     */
    public function __construct(string $file = '', string $pdf = '', string $data = '')
    {
        $this->file = $file;
        $this->pdf = $pdf;
        $this->data = Utils::nl2brEditor($data);
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPdf(): string
    {
        return $this->pdf;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}
