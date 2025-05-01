<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Db;

/**
 * Truy vấn CSDL
 *
 * @since 4.3.00
 */
class Order
{
    const MODE_DESC = 'DESC';
    const MODE_ASC = 'ASC';

    private $offset = -1;
    private $orders = [];

    /**
     * @return \NukeViet\Module\music\Db\Order
     */
    public function add()
    {
        $this->offset++;
        $this->orders[$this->offset] = [];
        return $this;
    }

    /**
     * @param string $field
     * @return \NukeViet\Module\music\Db\Order
     */
    public function setField($field)
    {
        $this->orders[$this->offset]['field'] = $field;
        return $this;
    }

    /**
     * @return \NukeViet\Module\music\Db\Order
     */
    public function setTypeDesc()
    {
        $this->orders[$this->offset]['type'] = self::MODE_DESC;
        return $this;
    }

    /**
     * @return \NukeViet\Module\music\Db\Order
     */
    public function setTypeAsc()
    {
        $this->orders[$this->offset]['type'] = self::MODE_ASC;
        return $this;
    }

    /**
     * @return string
     */
    public function toText()
    {
        $text = array();
        foreach ($this->orders as $row) {
            $text[] = $row['field'] . ' ' . $row['type'];
        }
        return implode(', ', $text);
    }
}
