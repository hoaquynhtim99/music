<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music;

/**
 * Các thư viện xử lý khác
 * Các thư viện này sử dụng các hàm có sẵn của NukeViet
 *
 * @since 4.3.00
 */
class Utils
{
    /**
     * @param array $json
     * @throws Exception
     */
    public static function jsonOutput($json)
    {
        if (!function_exists('nv_jsonOutput')) {
            throw new Exception('Function not exists: nv_jsonOutput!!!');
        }
        nv_jsonOutput($json);
    }

    /**
     * @param string $title
     * @throws Exception
     * @return string
     */
    public static function getSearchKey($title)
    {
        if (!function_exists('change_alias')) {
            throw new Exception('Function not exists: change_alias!!!');
        }
        return ' ' . trim(str_replace('-', ' ', strtolower(change_alias($title)))) . ' ';
    }

    /**
     * @param string $title
     * @throws Exception
     * @return string
     */
    public static function getAlphabet($title)
    {
        if (!function_exists('nv_strtoupper') or !function_exists('change_alias')) {
            throw new Exception('Function not exists: nv_strtoupper, change_alias!!!');
        }
        $alphabet = substr(nv_strtoupper(change_alias($title)), 0, 1);
        if (!in_array($alphabet, Resources::ALPHABETS_DATA)) {
            return '';
        }
        return $alphabet;
    }

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
     * @param string $string
     * @param string $sp
     * @return array
     */
    public static function arrayIntFromStrList($string, $sp = ',')
    {
        if (empty($string)) {
            return [];
        }
        return array_filter(array_unique(array_map("trim", explode($sp, $string))));
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function addPrefixToArray(array $array, string $prefix)
    {
        return array_map(function ($arrayValues) use ($prefix) {
            return $prefix . $arrayValues;
        }, $array);
    }

    /**
     * @param array $array_queries
     * @return string
     */
    public static function buildSearchQuery($array_queries)
    {
        if (!empty($array_queries['genre'])) {
            $array_queries['genre'] = implode('-', $array_queries['genre']);
        } elseif (isset($array_queries['genre'])) {
            unset($array_queries['genre']);
        }
        return http_build_query($array_queries, '', '&amp;');
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
