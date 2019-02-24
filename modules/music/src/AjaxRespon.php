<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music;

class AjaxRespon
{
    private static $jsonDefault = [
        'status' => 'error',
        'message' => '',
        'input' => '',
        'redirect' => ''
    ];

    private static $json = [];

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
        Utils::jsonOutput(self::$json);
    }
}
