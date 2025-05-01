<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Module\music\Shared;

use NukeViet\Module\music\Utils;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Db\Db;
use NukeViet\Module\music\Db\Condition;
use NukeViet\Module\music\Song\DataFields;

class Songs implements ITypeShare
{
    /**
     * Songs::creatUniqueCode()
     *
     * @return
     */
    public static function creatUniqueCode()
    {
        $sql = new Db();
        $sql->setTable(self::_getTable());
        $sql->setField(DataFields::FIELD_ID);

        while (true) {
            $code = strtolower(Utils::genCode(Resources::SONG_CODE_LENGTH));
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
     * Songs::_getTable()
     *
     * @return
     */
    private static function _getTable()
    {
        return Resources::getTablePrefix() . Resources::TABLE_SEPARATOR_CHARACTER . Resources::TABLE_SONG;
    }
}
