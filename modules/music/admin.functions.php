<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

define('NV_IS_MUSIC_ADMIN', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$allow_func = array('main', 'config', 'view');

/**
 * Class ajaxRespon
 *
 * @package NUKEVIET MUSIC
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @version 1.0
 * @access public
 */
class ajaxRespon
{
    private $jsonDefault = array(
        'status' => 'error',
        'message' => '',
        'input' => '',
        'redirect' => ''
    );

    private $json = array();

    /**
     * ajaxRespon::__construct()
     *
     * @return
     */
    public function __construct()
    {
        $this->json = $this->jsonDefault;
    }

    /**
     * ajaxRespon::setMessage()
     *
     * @param mixed $message
     * @return
     */
    public function setMessage($message)
    {
        $this->json['message'] = $message;
        return $this;
    }

    /**
     * ajaxRespon::setInput()
     *
     * @param mixed $input
     * @return
     */
    public function setInput($input)
    {
        $this->json['input'] = $input;
        return $this;
    }

    /**
     * ajaxRespon::setRedirect()
     *
     * @param mixed $redirect
     * @return
     */
    public function setRedirect($redirect)
    {
        $this->json['redirect'] = $redirect;
        return $this;
    }

    /**
     * ajaxRespon::setSuccess()
     *
     * @return
     */
    public function setSuccess()
    {
        $this->json['status'] = 'ok';
        return $this;
    }

    /**
     * ajaxRespon::setError()
     *
     * @return
     */
    public function setError()
    {
        $this->json['status'] = 'error';
        return $this;
    }

    /**
     * ajaxRespon::reset()
     *
     * @return
     */
    public function reset()
    {
        $this->json = $this->jsonDefault;
        return $this;
    }

    /**
     * ajaxRespon::respon()
     *
     * @return
     */
    public function respon()
    {
        nv_jsonOutput($this->json);
    }
}

$ajaxRespon = new ajaxRespon();
