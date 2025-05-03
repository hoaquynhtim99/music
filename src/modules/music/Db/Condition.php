<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Db;

use NukeViet\Module\music\Resources;

/**
 * Truy vấn CSDL
 *
 * @since 4.3.00
 */
class Condition
{
    const OPERATOR_EQUAL = '=';

    private $offset = -1;
    private $conditions = [];

    /**
     * Condition::add()
     *
     * @return Condition
     */
    public function add()
    {
        $this->offset++;
        $this->conditions[$this->offset] = [];
        return $this;
    }

    /**
     * Condition::setField()
     *
     * @param mixed $field
     * @return Condition
     */
    public function setField($field)
    {
        $this->conditions[$this->offset]['field'] = $field;
        return $this;
    }

    /**
     * Condition::setOperator()
     *
     * @param mixed $operator
     * @return Condition
     */
    public function setOperator($operator)
    {
        $this->conditions[$this->offset]['operator'] = $operator;
        return $this;
    }

    /**
     * Condition::setText()
     *
     * @param mixed $text
     * @return Condition
     */
    public function setText($text) {
        $db = Resources::getDb();
        $this->conditions[$this->offset]['value'] = $db->quote($text);
        return $this;
    }

    /**
     * Condition::toText()
     *
     * @return string
     */
    public function toText()
    {
        $text = array();
        foreach ($this->conditions as $row) {
            $text[] = $row['field'] . $row['operator'] . $row['value'];
        }
        return implode(' AND ', $text);
    }
}
