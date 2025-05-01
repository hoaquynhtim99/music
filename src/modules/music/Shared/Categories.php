<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Shared;

use NukeViet\Module\music\Utils;
use NukeViet\Module\music\Resources;
use NukeViet\Module\music\Category\DataFields;
use NukeViet\Module\music\Db\Db;
use NukeViet\Module\music\Db\Condition;

class Categories implements ITypeShare
{
    /**
     * Categories::creatUniqueCode()
     *
     * @return
     */
    public static function creatUniqueCode()
    {
        $sql = new Db();
        $sql->setTable(self::_getTable());
        $sql->setField(DataFields::FIELD_ID);

        while (true) {
            $code = strtolower(Utils::genCode(Resources::CATEGORY_CODE_LENGTH));
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
     * Categories::_getTable()
     *
     * @return
     */
    private static function _getTable()
    {
        return Resources::getTablePrefix() . Resources::TABLE_SEPARATOR_CHARACTER . Resources::TABLE_CATEGORY;
    }
}
