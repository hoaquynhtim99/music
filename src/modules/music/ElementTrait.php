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
 * Các đối tượng như Category, Nation, Song,... đều có chung các thuộc tính và phương thức này
 */
trait ElementTrait
{
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
}
