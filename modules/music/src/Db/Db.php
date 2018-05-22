<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Db;

use NukeViet\Music\Resources;

/**
 * Truy vấn CSDL
 *
 * @since 4.3.00
 */
class Db
{
    private $connection = null;

    private $table = '';
    private $fields = [];
    private $conditions = null;

    /**
     * Db::__construct()
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = Resources::getDb();
    }

    /**
     * Db::setField()
     *
     * @param mixed $fields
     * @return void
     */
    public function setField($fields) {
        if (!empty($fields)) {
            if (is_array($fields)) {
                $this->fields = array_merge_recursive($this->fields, $fields);
            } else {
                $this->fields[] = $fields;
            }
        }
    }

    /**
     * Db::setTable()
     *
     * @param mixed $table
     * @return void
     */
    public function setTable($table) {
        $this->table = $table;
    }

    /**
     * Db::setCondition()
     *
     * @param mixed $condition
     * @return void
     */
    public function setCondition(Condition $condition) {
        $this->conditions = $condition;
    }

    /**
     * Db::query()
     *
     * @return
     */
    public function query()
    {
        return $this->connection->query($this->_getSql());
    }

    /**
     * Db::select()
     *
     * @return
     */
    public function select()
    {
        return $this->connection->query('SELECT ' . $this->_getSql());
    }

    /**
     * Db::_getSql()
     *
     * @return
     */
    private function _getSql()
    {
        $sql = '';

        if (!empty($this->fields)) {
            $sql .= implode(', ', array_unique(array_filter($this->fields)));
        }
        if (!empty($this->table)) {
            $sql .= ' FROM ' . $this->table;
        }
        if (!is_null($this->conditions)) {
            $sql .= ' WHERE ' . $this->conditions->toText();
        }

        return $sql;
    }
}
