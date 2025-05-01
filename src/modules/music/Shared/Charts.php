<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Module\music\Shared;

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
        global $global_config;

        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set($global_config['statistics_timezone']);
        }
        $week = date('W');
        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set(NV_SITE_TIMEZONE_NAME);
        }

        return $week;
    }

    /**
     * Năm hiện tại
     *
     * @return string
     */
    public static function getCurrentYear()
    {
        global $global_config;

        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set($global_config['statistics_timezone']);
        }
        $year = date('Y');
        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set(NV_SITE_TIMEZONE_NAME);
        }

        return $year;
    }

    /**
     * Unix timestamp tại thứ 2 của tuần hiện tại
     *
     * @return number
     */
    public static function getCurrentTime()
    {
        global $global_config;

        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set($global_config['statistics_timezone']);
        }
        $time = mktime(0, 0, 0, date('n'), date('j'), date('Y')) - ((date('N') - 1) * 86400);
        if (NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone']) {
            date_default_timezone_set(NV_SITE_TIMEZONE_NAME);
        }

        return $time;
    }
}
