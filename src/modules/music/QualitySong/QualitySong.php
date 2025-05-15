<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\QualitySong;

use NukeViet\Module\music\ElementIType;
use NukeViet\Module\music\ElementTrait;

class QualitySong implements ElementIType
{
    use DBStruct;
    use ElementTrait;

    public function create(array $data = []): int|false
    {
        if (!empty($data)) {
            $this->loadFromArray($data);
        }

        return 0;
    }

    public function update(int $id = 0, array $data = []): int|false
    {
        if (!empty($data)) {
            $this->loadFromArray($data);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->data[self::FIELD_ID];
    }
}
