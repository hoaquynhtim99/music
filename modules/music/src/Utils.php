<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music;

/**
 * Các thư viện xử lý khác
 *
 * @since 4.3.00
 */
class Utils
{
    /**
     * Utils::getFormatNumberView()
     *
     * @param mixed $input
     * @return
     */
    public static function getFormatNumberView($input)
    {
        if (Resources::getLangInterface() == 'vi') {
            return number_format($input, 0, ',', '.');
        }
        return number_format($input, 0, '.', ',');
    }

    /**
     * Utils::getFormatDateView()
     *
     * @param mixed $input
     * @return
     */
    public static function getFormatDateView($input)
    {
        $funcName = function_exists('nv_date') ? 'nv_date' : 'date';
        if (Resources::getLangInterface() == 'vi') {
            return $funcName('d M, Y', $input);
        }
        return $funcName('M d, Y', $input);
    }

    /**
     * Utils::getValidPage()
     *
     * @param mixed $page
     * @param mixed $per_page
     * @return
     */
    public static function getValidPage($page, $per_page)
    {
        if ($page < 1 or $page > self::_maxPage($per_page)) {
            return 1;
        }
        return $page;
    }

    /**
     * Utils::genCode()
     *
     * @param integer $length
     * @return
     */
    public static function genCode($length = 8)
    {
        if (!function_exists('nv_genpass')) {
            throw new Exception('No function nv_genpass');
        }
        return nv_genpass($length);
    }

    /**
     * Utils::_maxPage()
     *
     * @param mixed $per_page
     * @return
     */
    private static function _maxPage($per_page)
    {
        if ($per_page < 1) {
            return 1;
        }
        return round(2147483647 / $per_page);
    }
}
