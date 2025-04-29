<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Nation;

use NukeViet\Music\Db\Db;
use NukeViet\Music\Db\Order;

class DbLoader
{
    use DBStruct;

    /**
     * @return \NukeViet\Music\Nation\Nation[]
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
