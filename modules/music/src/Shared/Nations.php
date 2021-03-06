<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Shared;

use NukeViet\Music\Utils;
use NukeViet\Music\Resources;
use NukeViet\Music\Nation\DBStruct;
use NukeViet\Music\Nation\Nation;
use NukeViet\Music\Db\Db;
use NukeViet\Music\Db\Condition;

class Nations implements ITypeShare
{
    use DBStruct;

    /**
     * Nations::creatUniqueCode()
     *
     * @return
     */
    public static function creatUniqueCode()
    {
        $sql = new Db();
        $sql->setTable(self::_getTable());
        $sql->setField(self::$FIELD_ID);

        while (true) {
            $code = strtolower(Utils::genCode(Resources::NATION_CODE_LENGTH));
            $condition = new Condition();
            $condition->add()->setField(self::$FIELD_CODE)->setOperator(Condition::OPERATOR_EQUAL)->setText($code);
            $sql->setCondition($condition);
            if (!$sql->select()->fetchColumn()) {
                break;
            }
        }

        return $code;
    }

    public static function updateStat(Nation $nation)
    {
        return $nation->getId();
    }
}
