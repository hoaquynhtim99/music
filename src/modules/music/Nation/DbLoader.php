<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Nation;

use NukeViet\Module\music\Db\Db;
use NukeViet\Module\music\Db\Order;

class DbLoader
{
    use DBStruct;

    /**
     * @return \NukeViet\Module\music\Nation\Nation[]
     */
    public static function loadAll()
    {
        $sql = new Db();
        $sql->setTable(self::getTableNation());
        $sql->setField(self::getBasicFields());

        $order = new Order();
        $order->add()->setField(self::$FIELD_WEIGHT)->setTypeAsc();

        $sql->setOrder($order);

        $array = [];
        $result = $sql->select();
        while ($row = $result->fetch()) {
            $nation = new Nation($row);
            $array[$nation->getId()] = $nation;
        }

        return $array;
    }
}
