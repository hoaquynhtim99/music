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
 * Chuẩn của các class đối tương
 */
interface ElementIType
{
    /**
     * Lưu đối tượng vào database
     *
     * @param array $data
     * @return int|false
     */
    public function create(array $data = []): int|false;

    /**
     * Cập nhật đối tượng trong database
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id = 0, array $data = []): int|false;

    /**
     * Lấy ID của đối tượng
     *
     * @return int
     */
    public function getId(): int;
}
