<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\QualitySong;

use NukeViet\Module\music\Db\Condition;
use NukeViet\Module\music\Db\Db;
use NukeViet\Module\music\Db\Order;
use NukeViet\Module\music\Utils;

class DbLoader
{
    use DBStruct;

    /**
     * @return QualitySong[]
     */
    public static function loadAll(): array
    {
        $sql = new Db();
        $sql->setTable(self::getTableQualitySong());
        $sql->setField(self::getBasicFields());

        $order = new Order();
        $order->add()->setField(self::FIELD_WEIGHT)->setTypeAsc();

        $sql->setOrder($order);

        $array = [];
        $result = $sql->select();
        while ($row = $result->fetch()) {
            $qualitysong = new QualitySong($row);
            $array[$qualitysong->getId()] = $qualitysong;
        }

        return $array;
    }

    /**
     * Đọc một chất lượng bài hát từ cơ sở dữ liệu
     *
     * @param int $id
     * @return QualitySong|false
     */
    public static function get(int $id, bool $full = false): QualitySong|false
    {
        $sql = new Db();
        $sql->setTable(self::getTableArtist());
        $sql->setField(self::getBasicFields($full));

        $condition = new Condition();
        $condition->add()->setField(self::FIELD_ID)->setOperator(Condition::OPERATOR_EQUAL)->setInt($id);
        $sql->setCondition($condition);

        $row = $sql->select()->fetch();
        if (empty($row)) {
            return false;
        }

        return new QualitySong($row);
    }

    /**
     * Đọc nhiều chất lượng bài hát từ cơ sở dữ liệu
     *
     * @param int|string|array $ids
     * @return QualitySong[]|array
     */
    public static function gets(int|string|array $ids, bool $full = false): array
    {
        $ids = Utils::arrayIntFromStrList($ids);
        if (empty($ids)) {
            return [];
        }

        $sql = new Db();
        $sql->setTable(self::getTableQualitySong());
        $sql->setField(self::getBasicFields($full));

        $condition = new Condition();
        $condition->add()->setField(self::FIELD_ID)->setOperator(Condition::OPERATOR_IN)->setArray($ids);
        $sql->setCondition($condition);

        $array = [];
        $result = $sql->select();
        while ($row = $result->fetch()) {
            $qualitysong = new QualitySong($row);
            $array[$qualitysong->getId()] = $qualitysong;
        }
        $result->closeCursor();

        return $array;
    }
}
