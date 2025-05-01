<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Module\music\Nation;

use NukeViet\Module\music\Shared\Nations;

class Nation
{
    use DBStruct;

    private $data = [];
    private $dataType = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->dataType = self::buildObjectKeys();
        foreach ($this->dataType as $key => $dataType) {
            $this->data[$key] = self::getDefaultValue($dataType);
        }
        $this->loadFromArray($data);
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function loadFromArray($data)
    {
        foreach ($data as $key => $value) {
            if (isset($this->dataType[$key])) {
                if (gettype($value) !== $this->dataType[$key]) {
                    throw new Exception('Wrong type for ' . $key . '!!!');
                }
                $this->data[$key] = $value;
            }
        }
    }

    public function updateStat()
    {
        return Nations::updateStat($this);
    }

    /**
     * @return number
     */
    public function getId()
    {
        return $this->data[self::$FIELD_ID];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data[self::$LANG_FIELD_NAME];
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->data[self::$LANG_FIELD_ALIAS];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->data[self::$FIELD_CODE];
    }

    /**
     * @return string
     */
    public function getIntrotext()
    {
        return $this->data[self::$LANG_FIELD_INTROTEXT];
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->data[self::$LANG_FIELD_KEYWORD];
    }

    /**
     * @return integer
     */
    public function getTimeAdd()
    {
        return $this->data[self::$FIELD_ADDTIME];
    }

    /**
     * @return integer
     */
    public function getTimeUpdate()
    {
        return $this->data[self::$FIELD_UPDATETIME];
    }

    /**
     * @return integer
     */
    public function getStatSingers()
    {
        return $this->data[self::$FIELD_SINGER_STAT];
    }

    /**
     * @return integer
     */
    public function getStatAuthors()
    {
        return $this->data[self::$FIELD_AUTHOR_STAT];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    public function setName()
    {
        //
    }

    public function setAlias()
    {
        //
    }
}
