<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music\Video;

use NukeViet\Module\music\Db\Condition;
use NukeViet\Module\music\Db\Db;
use NukeViet\Module\music\Utils;

class DbLoader
{
    use DBStruct;

    /**
     * Đọc một video từ cơ sở dữ liệu
     *
     * @param int $id
     * @return Video|false
     */
    public static function get(int $id, bool $full = false): Video|false
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

        return new Video($row);
    }

    /**
     * Đọc nhiều video từ cơ sở dữ liệu
     *
     * @param int|string|array $ids
     * @return Video[]|array
     */
    public static function gets(int|string|array $ids, bool $full = false): array
    {
        $ids = Utils::arrayIntFromStrList($ids);
        if (empty($ids)) {
            return [];
        }

        $sql = new Db();
        $sql->setTable(self::getTableVideo());
        $sql->setField(self::getBasicFields($full));

        $condition = new Condition();
        $condition->add()->setField(self::FIELD_ID)->setOperator(Condition::OPERATOR_IN)->setArray($ids);
        $sql->setCondition($condition);

        $array = [];
        $result = $sql->select();
        while ($row = $result->fetch()) {
            $video = new Video($row);
            $array[$video->getId()] = $video;
        }
        $result->closeCursor();

        return $array;
    }
}
