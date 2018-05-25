<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Shared;

use NukeViet\Music\Utils;
use NukeViet\Music\Resources;
use NukeViet\Music\Artist\DataFields;
use NukeViet\Music\Db\Db;
use NukeViet\Music\Db\Condition;

class Artists implements ITypeShare
{
    /**
     * Artists::creatUniqueCode()
     *
     * @return
     */
    public static function creatUniqueCode()
    {
        $sql = new Db();
        $sql->setTable(self::_getTable());
        $sql->setField(DataFields::FIELD_ID);

        while (true) {
            $code = strtolower(Utils::genCode(Resources::ARTIST_CODE_LENGTH));
            $condition = new Condition();
            $condition->add()->setField(DataFields::FIELD_CODE)->setOperator(Condition::OPERATOR_EQUAL)->setText($code);
            $sql->setCondition($condition);
            if (!$sql->select()->fetchColumn()) {
                break;
            }
        }

        return $code;
    }

    /**
     * Artists::_getTable()
     *
     * @return
     */
    private static function _getTable()
    {
        return Resources::getTablePrefix() . Resources::TABLE_SEPARATOR_CHARACTER . Resources::TABLE_ARTIST;
    }
}
