<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Shared;

class Charts implements ITypeShare
{
    /**
     *
     */
    public static function creatUniqueCode()
    {
    }

    /**
     * Tuần hiện tại của năm
     *
     * @return string
     */
    public static function getCurrentWeek()
    {
        return date('W');
    }

    /**
     * Năm hiện tại
     *
     * @return string
     */
    public static function getCurrentYear()
    {
        return date('Y');
    }

    /**
     * Unix timestamp tại thứ 2 của tuần hiện tại
     *
     * @return number
     */
    public static function getCurrentTime()
    {
        return (mktime(0, 0, 0, date('n'), date('j'), date('Y')) - ((date('N') - 1) * 86400));
    }
}
