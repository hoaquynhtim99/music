<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

namespace NukeViet\Module\music;

use NukeViet\Api\ApiResult;

class AjaxRespon
{
    private static $jsonDefault = [
        'status' => 'error',
        'message' => '',
        'input' => '',
        'redirect' => ''
    ];

    private static $json = [];

    private static ?ApiResult $result = null;

    /**
     * @param \NukeViet\Api\ApiResult $result
     * @return static
     */
    public static function setResultHander(ApiResult $result)
    {
        self::$result = $result;
        return new static();
    }

    /**
     * AjaxRespon::setMessage()
     *
     * @param mixed $message
     * @return
     */
    public static function setMessage($message)
    {
        self::$json['message'] = $message;

        return new static();
    }

    /**
     * AjaxRespon::setPrintMessage()
     *
     * @param mixed $vars
     * @return
     */
    public static function setPrintMessage($vars)
    {
        self::$json['message'] = '<pre><code>' . print_r($vars, true) . '</code></pre>';

        return new static();
    }

    /**
     * AjaxRespon::setInput()
     *
     * @param mixed $input
     * @return
     */
    public static function setInput($input)
    {
        self::$json['input'] = $input;

        return new static();
    }

    /**
     * AjaxRespon::setRedirect()
     *
     * @param mixed $redirect
     * @return
     */
    public static function setRedirect($redirect)
    {
        self::$json['redirect'] = $redirect;

        return new static();
    }

    /**
     * AjaxRespon::setSuccess()
     *
     * @return
     */
    public static function setSuccess()
    {
        self::$json['status'] = 'ok';

        return new static();
    }

    /**
     * Thành công hay không
     *
     * @return bool
     */
    public static function isSuccess()
    {
        return (self::$json['status'] ?? '') == 'ok';
    }

    /**
     * AjaxRespon::setError()
     *
     * @return
     */
    public static function setError()
    {
        self::$json['status'] = 'error';

        return new static();
    }

    /**
     * AjaxRespon::set()
     *
     * @param mixed $key
     * @param mixed $value
     * @return
     */
    public static function set($key, $value)
    {
        self::$json[$key] = $value;

        return new static();
    }

    /**
     * AjaxRespon::reset()
     *
     * @return
     */
    public static function reset()
    {
        self::$json = self::$jsonDefault;

        return new static();
    }

    /**
     * AjaxRespon::respon()
     *
     * @return
     */
    public static function respon()
    {
        self::$json = array_merge(self::$jsonDefault, self::$json);

        if (self::$result) {
            self::$result->setMessage(self::$json['message']);
            return self::$result->getResult();
        }

        Utils::jsonOutput(self::$json);
    }
}
