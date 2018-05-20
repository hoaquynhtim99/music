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
     * ajaxRespon::setPrintMessage()
     *
     * @param mixed $vars
     * @return
     */
    public function setPrintMessage($vars)
    {
        $this->json['message'] = '<pre><code>' . print_r($vars, true) . '</code></pre>';
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
     * ajaxRespon::set()
     *
     * @param mixed $key
     * @param mixed $value
     * @return
     */
    public function set($key, $value)
    {
        $this->json[$key] = $value;
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

define('NV_ADMIN_MOD_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_ADMIN_MOD_LINK_AMP', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
define('NV_ADMIN_MOD_FULLLINK', NV_ADMIN_MOD_LINK . '&' . NV_OP_VARIABLE . '=');
define('NV_ADMIN_MOD_FULLLINK_AMP', NV_ADMIN_MOD_LINK_AMP . '&amp;' . NV_OP_VARIABLE . '=');

/**
 * msGetCurrentUploadFolder()
 *
 * @param mixed $area
 * @param string $child
 * @return
 */
function msGetCurrentUploadFolder($area, $child = '')
{
    global $module_upload, $db;

    $folder_lev1 = '';
    $folder_lev2 = date('Y_m');
    if ($area == 'album') {
        if ($child == 'cover') {
            $folder_lev1 = 'albums_cover';
        } else {
            $folder_lev1 = 'albums';
        }
    } elseif ($area == 'artist') {
        if ($child == 'cover') {
            $folder_lev1 = 'artists_cover';
        } else {
            $folder_lev1 = 'artists';
        }
    } elseif ($area == 'song') {
        if ($child == 'cover') {
            $folder_lev1 = 'songs_cover';
        } else {
            $folder_lev1 = 'songs';
        }
    } elseif ($area == 'video') {
        if ($child == 'cover') {
            $folder_lev1 = 'videos_cover';
        } else {
            $folder_lev1 = 'videos';
        }
    }
    $upload_path = $upload_path_current = NV_UPLOADS_DIR . '/' . $module_upload;
    if (!empty($folder_lev1)) {
        $folder_path = array($folder_lev1, $folder_lev2);
        $i = 0;
        foreach ($folder_path as $path) {
            $i++;
            if (!file_exists(NV_ROOTDIR . '/' . $upload_path_current . '/' . $path)) {
                // Tạo thư mục mới
                $mkdir = nv_mkdir(NV_ROOTDIR . '/' . $upload_path_current, $path);
                if ($mkdir[0] > 0) {
                    try {
                        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . $upload_path_current . '/' . $path . "', 0)");
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
                    }
                }
            }
            if (file_exists(NV_ROOTDIR . '/' . $upload_path_current . '/' . $path)) {
                $upload_path_current .= '/' . $path;
                if ($i == 1) {
                    $upload_path .= '/' . $path;
                }
            } else {
                break;
            }
        }
    }

    return array($upload_path, $upload_path_current);
}

/**
 * msUpdateNationStat()
 *
 * @param mixed $nation_id
 * @return void
 */
function msUpdateNationStat($nation_id)
{
    global $db;

    $db->query("UPDATE " . NV_MOD_TABLE . "_nations SET stat_singers=(SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_artists WHERE (artist_type=0 OR artist_type=2) AND nation_id=" . $nation_id . ") WHERE nation_id=" . $nation_id);
    $db->query("UPDATE " . NV_MOD_TABLE . "_nations SET stat_authors=(SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_artists WHERE (artist_type=1 OR artist_type=2) AND nation_id=" . $nation_id . ") WHERE nation_id=" . $nation_id);
}

/**
 * msGetAlphabet()
 *
 * @param mixed $title
 * @return
 */
function msGetAlphabet($title)
{
    $array_alphabets = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $alphabet = substr(nv_strtoupper(change_alias($title)), 0, 1);
    if (!in_array($alphabet, $array_alphabets)) {
        return '';
    }
    return $alphabet;
}

/**
 * msGetSearchKey()
 *
 * @param mixed $title
 * @return
 */
function msGetSearchKey($title)
{
    return ' ' . trim(str_replace('-', ' ', strtolower(change_alias($title)))) . ' ';
}
