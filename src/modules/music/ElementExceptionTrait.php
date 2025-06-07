<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music;

/**
 * Lỗi chung cho các đối tượng trong module Music
 */
trait ElementExceptionTrait
{
    /**
     * @param string $field
     * @param string $code
     */
    public function __construct(string $field, string $code)
    {
        $message = json_encode(['field' => $field, 'code' => $code], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        parent::__construct($message);
    }
}
