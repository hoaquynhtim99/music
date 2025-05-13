<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Nation;

use NukeViet\Module\music\ElementIType;
use NukeViet\Module\music\ElementTrait;
use NukeViet\Module\music\Shared\Nations;

class Nation implements ElementIType
{
    use DBStruct;
    use ElementTrait;

    public function updateStat()
    {
        return Nations::updateStat($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->data[self::FIELD_ID];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data[self::LANG_FIELD_NAME];
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->data[self::LANG_FIELD_ALIAS];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->data[self::FIELD_CODE];
    }

    /**
     * @return string
     */
    public function getIntrotext()
    {
        return $this->data[self::LANG_FIELD_INTROTEXT];
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->data[self::LANG_FIELD_KEYWORD];
    }

    /**
     * @return int
     */
    public function getTimeAdd()
    {
        return (int) $this->data[self::FIELD_ADDTIME];
    }

    /**
     * @return int
     */
    public function getTimeUpdate()
    {
        return (int) $this->data[self::FIELD_UPDATETIME];
    }

    /**
     * @return int
     */
    public function getStatSingers()
    {
        return (int) $this->data[self::FIELD_SINGER_STAT];
    }

    /**
     * @return int
     */
    public function getStatAuthors()
    {
        return (int) $this->data[self::FIELD_AUTHOR_STAT];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    public function setName(string $name): Nation
    {
        $this->data[self::LANG_FIELD_NAME] = $name;
        return $this;
    }

    public function setAlias()
    {
        //
    }

    public function create(array $data = []): int|false
    {
        return 0;
    }

    public function update(int $id = 0, array $data = []): int|false
    {
        return 0;
    }
}
