<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
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
     * @return void
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
     * @return
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
     * @return
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
     * @return
     */
    public function setText($text) {
        $db = Resources::getDb();
        $this->conditions[$this->offset]['value'] = $db->quote($text);
        return $this;
    }

    /**
     * Condition::toText()
     *
     * @return
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
