<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2013 Freeware
 * @Createdate 08/09/2013
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

class nv_mod_music
{
    private $mod_data = '';
    private $mod_name = '';
    private $mod_file = '';
    private $db = null;

    public $db_prefix = '';
    public $db_prefix_lang = "";
    public $table_prefix = "";

    public $setting = null;

    private $base_site_url = null;
    private $base_admin_url = null;

    private $root_dir = null;
    private $upload_dir = null;
    private $currenttime = null;

    public $language = array();
    public $glanguage = array();

    private $js_data = array();

    private $lang_variable = null;
    private $lang_data = null;
    private $name_variable = null;
    private $op_variable = null;

    public function __construct($d = "", $n = "", $f = "", $lang = "", $get_lang = false)
    {
        global $module_data, $module_name, $module_file, $db_config, $db, $lang_global, $my_head;

        // Ten CSDL
        if (!empty($d))
            $this->mod_data = $d;
        else
            $this->mod_data = $module_data;

        // Ten module
        if (!empty($n))
            $this->mod_name = $n;
        else
            $this->mod_name = $module_name;

        // Ten thu muc
        if (!empty($f))
            $this->mod_file = $f;
        else
            $this->mod_file = $module_file;

        // Ngon ngu
        if (!empty($lang))
            $this->lang_data = $lang;
        else
            $this->lang_data = NV_LANG_DATA;

        $this->lang_variable = NV_LANG_VARIABLE;
        $this->name_variable = NV_NAME_VARIABLE;
        $this->op_variable = NV_OP_VARIABLE;

        $this->db_prefix = $db_config['prefix'];
        $this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
        $this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
        $this->db = $db;

        $this->setting = $this->get_setting();

        $this->base_site_url = NV_BASE_SITEURL;
        $this->base_admin_url = NV_BASE_ADMINURL;
        $this->root_dir = NV_ROOTDIR;
        $this->upload_dir = NV_UPLOADS_DIR;
        $this->currenttime = NV_CURRENTTIME;

        // Ngon ngu
        if ($get_lang === false) {
            global $lang_module;
        } else {
            $file_lang_path = $this->root_dir . "/modules/" . $this->mod_file . "/language/";
            $file_lang_name = defined('NV_ADMIN') ? "admin_" . $this->lang_data . ".php" : $this->lang_data . ".php";
            if (is_file($file_lang_path . $file_lang_name)) {
                include ($file_lang_path . $file_lang_name);
            } else {
                $lang_module = array();
            }
        }
        $this->language = $lang_module;
        $this->glanguage = $lang_global;

        $this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
        $this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
        $this->js_data['jquery.ui.core'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.core.min.js\"></script>\n";

        $this->js_data['jquery.ui.sortable'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.sortable.min.js\"></script>\n";

        $this->js_data['jquery.tipsy'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.tipsy.js\"></script>\n";
        $this->js_data['jquery.tipsy'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/tipsy.css\" rel=\"stylesheet\" />\n";

        $this->js_data['jquery.autosize'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.autosize.js\"></script>\n";

        $this->js_data['shadowbox'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/shadowbox/shadowbox.js\"></script>\n";
        $this->js_data['shadowbox'][] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->base_site_url . "js/shadowbox/shadowbox.css\" />\n";
        $this->js_data['shadowbox'][] = "<script type=\"text/javascript\">Shadowbox.init();</script>\n";

        $this->js_data['root_admin'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/admin.js\"></script>\n";
        $this->js_data['root_site'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/user.js\"></script>\n";

        // Khoi tao JS module neu khong phai la admin
        if (!defined("NV_ADMIN")) {
            $my_head .= "<script type=\"text/javascript\">";
            $my_head .= "NVMS = {};";
            $my_head .= "NVMS.data = {};";
            $my_head .= "NVMS.data.siteRoot = '" . $this->base_site_url . "';";
            $my_head .= "NVMS.data.langVar = '" . $this->lang_variable . "';";
            $my_head .= "NVMS.data.langSite = '" . $this->lang_data . "';";
            $my_head .= "NVMS.data.nameVar = '" . $this->name_variable . "';";
            $my_head .= "NVMS.data.opVar = '" . $this->op_variable . "';";
            $my_head .= "NVMS.data.module = '" . $this->mod_name . "';";
            $my_head .= "</script>\n";
        }
    }

    private function handle_error($messgae = '')
    {
        trigger_error("Error! " . ($messgae ? (string )$messgae : "You are not allowed to access this feature now") . "!", 256);
    }

    private function check_admin()
    {
        if (!defined('NV_ADMIN'))
            $this->handle_error();
    }

    private function nl2br($string)
    {
        return nv_nl2br($string);
    }

    private function db_cache($sql, $id = '', $module_name = '')
    {
        return $nv_Cache->db($sql, $id, $module_name);
    }

    private function del_cache($module_name)
    {
        return $nv_Cache->delMod($module_name);
    }

    private function get_setting()
    {
        $setting = array();

        $sql = "SELECT * FROM " . $this->table_prefix . "_setting";
        $result = $this->db_cache($sql, 'key');

        if (!empty($result)) {
            foreach ($result as $row) {
                $setting[$row['key']] = $row['value'];
            }
        }

        return $setting;
    }

    private function sortArrayFromArrayKeys($keys, $array)
    {
        $return = array();

        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $return[$key] = $array[$key];
            }
        }
        return $return;
    }

    private function checkJqueryPlugin($numargs, $arg_list)
    {
        $return = array();
        for ($i = 0; $i < $numargs; $i++) {
            if (isset($this->js_data[$arg_list[$i]])) {
                if ($arg_list[$i] == 'jquery.ui.sortable')
                    $return['jquery.ui.core'] = implode("", $this->js_data['jquery.ui.core']);
                $return[$arg_list[$i]] = implode("", $this->js_data[$arg_list[$i]]);
            }
        }
        return $return;
    }

    public function getLink($lev = 1, $opval = "", $arg = array(), $encode = true, $isAdmin = false)
    {
        $return = array();

        // Ky tu phan giua
        if ($encode == true) {
            $andItem = "&amp;";
        } else {
            $andItem = "&";
        }

        // Item co ban
        if ($isAdmin == true) {
            $this->check_admin(); // Kiem tra tu cach admin
            $return[] = $this->base_admin_url . "index.php?" . $this->name_variable . "=" . $this->mod_name;
        } else {
            $return[] = $this->base_site_url . "index.php?" . $this->lang_variable . "=" . $this->lang_data;
        }

        if ($lev >= 2 and $isAdmin == false) {
            $return[] = $this->name_variable . "=" . $this->mod_name;
        }

        if ($lev >= 3 and !empty($opval)) {
            $return[] = $this->op_variable . "=" . (string )$opval;
        }

        if (!empty($arg)) {
            foreach ($arg as $key => $val) {
                $return[] = (string )$key . "=" . (string )$val;
            }
        }

        return implode($andItem, $return);
    }

    public function callJqueryPlugin()
    {
        global $my_head;

        $return = $this->checkJqueryPlugin(func_num_args(), func_get_args());

        if (!empty($return)) {
            if (empty($my_head)) {
                $my_head = implode("", $return);
            } else {
                $my_head .= implode("", $return);
            }
        }
    }

    public function getJqueryPlugin()
    {
        $return = $this->checkJqueryPlugin(func_num_args(), func_get_args());
        return implode("", $return);
    }

    public function lang($key)
    {
        return isset($this->language[$key]) ? $this->language[$key] : $key;
    }

    public function glang($key)
    {
        return isset($this->glanguage[$key]) ? $this->glanguage[$key] : $key;
    }

    // Cap nhat bai hat, video khi xoa, sua host nhac
    public function updatewhendelFTP($server, $active)
    {
        $this->check_admin();

        $this->db->sql_query("UPDATE " . $this->table_prefix . " SET active = " . $active . " WHERE server = " . $server);
        $this->db->sql_query("UPDATE " . $this->table_prefix . "_video SET active = " . $active . " WHERE server = " . $server);
    }

    // Xuat duong dan nguoc lai
    public function admin_outputURL($server, $inputurl)
    {
        $this->check_admin();

        $output = "";

        if ($server == 0) {
            $output = $inputurl;
        } elseif ($server == 1) {
            $output = $this->base_site_url . $this->upload_dir . "/" . $this->mod_name . "/" . $this->setting['root_contain'] . "/" . $inputurl;
        } else {
            $ftpdata = $this->getFTP();
            foreach ($ftpdata as $id => $data) {
                if ($id == $server) {
                    $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                    break;
                }
            }
        }

        return $output;
    }

    // Lay thong tin ftp cua host nhac
    public function getFTP()
    {
        $ftpdata = array();
        $sql = "SELECT * FROM " . $this->table_prefix . "_ftp ORDER BY id DESC";
        $result = $this->db_cache($sql, 'id');

        if (!empty($result)) {
            foreach ($result as $row) {
                $ftpdata[$row['id']] = array(
                    "id" => $row['id'],
                    "host" => $row['host'],
                    "user" => $row['user'],
                    "pass" => $row['pass'],
                    "fulladdress" => $row['fulladdress'],
                    "subpart" => $row['subpart'],
                    "ftppart" => $row['ftppart'],
                    "status" => $row['active'],
                    "active" => ($row['active'] == 1) ? $this->lang('active_yes') : $this->lang('active_no'));
            }
        }
        return $ftpdata;
    }

    // Lay thong tin the loai bai hat
    public function get_category()
    {
        $category = array();

        $sql = "SELECT * FROM " . $this->table_prefix . "_category ORDER BY weight ASC";
        $result = $this->db_cache($sql, 'id');

        $category[0] = array(
            'id' => 0,
            'title' => $this->lang('unknow'),
            'keywords' => '',
            'description' => '');

        if (!empty($result)) {
            foreach ($result as $row) {
                $category[$row['id']] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'keywords' => $row['keywords'],
                    'description' => $row['description']);
            }
        }

        return $category;
    }

    // Lay thong tin the loai videoclip
    public function get_videocategory()
    {
        $category = array();

        $sql = "SELECT * FROM " . $this->table_prefix . "_video_category ORDER BY weight ASC";
        $result = $this->db_cache($sql, 'id');

        $category[0] = array(
            'id' => 0,
            'title' => $this->lang('unknow'),
            'keywords' => '',
            'description' => '');

        if (!empty($result)) {
            foreach ($result as $row) {
                $category[$row['id']] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'keywords' => $row['keywords'],
                    'description' => $row['description']);
            }
        }

        return $category;
    }

    public function search_singer_id($q, $limit = 0)
    {
        // Gioi han khong qua lon
        $limit = $limit ? (int)$limit : 100;
        $array = array();

        $sql = "SELECT id FROM " . $this->table_prefix . "_singer WHERE tenthat LIKE '%" . $this->db->dblikeescape($q) . "%' LIMIT 0," . $limit;
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetch_assoc($result)) {
            $array[] = $row['id'];
        }

        return $array;
    }

    public function search_author_id($q, $limit = 0)
    {
        // Gioi han khong qua lon
        $limit = $limit ? (int)$limit : 100;
        $array = array();

        $sql = "SELECT id FROM " . $this->table_prefix . "_author WHERE tenthat LIKE '%" . $this->db->dblikeescape($q) . "%' LIMIT 0," . $limit;
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetch_assoc($result)) {
            $array[] = $row['id'];
        }

        return $array;
    }

    public function search_song_id($q, $limit = 0)
    {
        // Gioi han khong qua lon
        $limit = $limit ? (int)$limit : 100;
        $array = array();

        $sql = "SELECT id FROM " . $this->table_prefix . " WHERE tenthat LIKE '%" . $this->db->dblikeescape($q) . "%' LIMIT 0," . $limit;
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetch_assoc($result)) {
            $array[] = $row['id'];
        }

        return $array;
    }

    public function string2array($string, $split_char = ',')
    {
        return array_filter(array_unique(array_map("trim", explode(",", $string))));
    }

    // Lay thong tin thanh vien
    public function getuserbyID($id)
    {
        $users = array();

        if (is_array($id)) {
            $result = $this->db->sql_query(" SELECT userid, username, full_name FROM " . $this->db_prefix . "_users WHERE userid IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $row['full_name'] = $row['full_name'] == '' ? $row['username'] : $row['full_name'];
                $users[$row['userid']] = $row;
            }
        } else {
            $result = $this->db->sql_query(" SELECT userid, username, full_name FROM " . $this->db_prefix . "_users WHERE userid=" . $id);
            $users = $this->db->sql_fetch_assoc($result);
            $users['full_name'] = $users['full_name'] == '' ? $users['username'] : $users['full_name'];
        }

        return $users;
    }

    // Lay ca si tu id
    public function getsingerbyID($id, $sort = false)
    {
        $singer = array();

        if (is_array($id)) {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_singer WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $singer[$row['id']] = $row;
            }

            if ($sort === true)
                $singer = $this->sortArrayFromArrayKeys($id, $singer);
        } else {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_singer WHERE id=" . $id);
            $singer = $this->db->sql_fetch_assoc($result);
        }

        return $singer;
    }

    // Lay nhac si tu id
    public function getauthorbyID($id, $sort = false)
    {
        $authors = array();

        if (is_array($id)) {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_author WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $authors[$row['id']] = $row;
            }

            if ($sort === true)
                $authors = $this->sortArrayFromArrayKeys($id, $authors);
        } else {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_author WHERE id=" . $id);
            $authors = $this->db->sql_fetchrow($result);
        }

        return $authors;
    }

    // Lay song tu id
    public function getsongbyID($id, $sort = false)
    {
        $songs = array();

        if (is_array($id)) {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . " WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $songs[$row['id']] = $row;
            }

            if ($sort === true)
                $songs = $this->sortArrayFromArrayKeys($id, $songs);
        } else {
            $result = $this->db->sql_query("SELECT * FROM " . $this->table_prefix . " WHERE id=" . $id);
            $songs = $this->db->sql_fetch_assoc($result);
        }

        return $songs;
    }

    // Lay video tu id
    public function getvideobyID($id, $sort = false)
    {
        $videoclips = array();

        if (is_array($id)) {
            $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_video WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $videoclips[$row['id']] = $row;
            }

            if ($sort === true)
                $videoclips = $this->sortArrayFromArrayKeys($id, $videoclips);
        } else {
            $result = $this->db->sql_query("SELECT * FROM " . $this->table_prefix . "_video WHERE id=" . $id);
            $videoclips = $this->db->sql_fetch_assoc($result);
        }

        return $videoclips;
    }

    // Lay album tu id
    public function getalbumbyID($id, $sort = false)
    {
        $albums = array();

        if (is_array($id)) {
            $result = $this->db->sql_query("SELECT * FROM " . $this->table_prefix . "_album WHERE id IN(" . implode(",", $id) . ")");

            while ($row = $this->db->sql_fetch_assoc($result)) {
                $albums[$row['id']] = $row;
            }

            if ($sort === true)
                $albums = $this->sortArrayFromArrayKeys($id, $albums);
        } else {
            $result = $this->db->sql_query("SELECT * FROM " . $this->table_prefix . "_album WHERE id=" . $id);
            $albums = $this->db->sql_fetchrow($result);
        }

        return $albums;
    }

    // Lay ca si tu ten
    public function getsingerbyName($name)
    {
        $singer = array();
        $result = $this->db->sql_query(" SELECT * FROM " . $this->table_prefix . "_singer WHERE tenthat=" . $this->db->dbescape($name));
        $singer = $this->db->sql_fetch_assoc($result);

        return $singer;
    }

    // Lay nhac si tu ten
    public function getauthorbyName($name)
    {
        $author = array();
        $result = $this->db->sql_query("SELECT * FROM " . $this->table_prefix . "_author WHERE tenthat=" . $this->db->dbescape($name));
        $author = $this->db->sql_fetch_assoc($result);

        return $author;
    }

    public function build_author_singer_2string($array, $string)
    {
        $id = $this->string2array($string);

        $return = array();

        if (!empty($id)) {
            foreach ($id as $_tmp) {
                if (isset($array[$_tmp])) {
                    $return[] = $array[$_tmp]['tenthat'];
                }
            }
        }

        if (empty($return)) {
            return $this->lang('unknow');
        } else {
            return implode(" " . $this->setting['author_singer_defis'] . " ", $return);
        }
    }

    public function build_album_2tring($array, $string)
    {
        $id = $this->string2array($string);

        $return = array();

        if (!empty($id)) {
            foreach ($id as $_tmp) {
                if (isset($array[$_tmp])) {
                    $return[] = $array[$_tmp]['tname'];
                }
            }
        }

        if (empty($return)) {
            return $this->lang('unknow');
        } else {
            return implode(", ", $return);
        }
    }

    public function build_categories_2tring($array, $string)
    {
        $id = $this->string2array($string);

        $return = array();

        if (!empty($id)) {
            foreach ($id as $_tmp) {
                if (isset($array[$_tmp])) {
                    $return[] = $array[$_tmp]['title'];
                }
            }
        }

        if (empty($return)) {
            return $this->lang('unknow');
        } else {
            return implode(", ", $return);
        }
    }

    public function build_user_2tring($array, $string, $default = '')
    {
        $id = $this->string2array($string);

        $return = array();

        if (!empty($id)) {
            foreach ($id as $_tmp) {
                if (isset($array[$_tmp])) {
                    $return[] = $array[$_tmp]['full_name'];
                }
            }
        }

        if (empty($return)) {
            return $default == '' ? $this->lang('unknow') : $default;
        } else {
            return implode(", ", $return);
        }
    }

    // Them moi mot ca si
    public function newsinger($name, $tname)
    {
        $sql = "INSERT INTO " . $this->table_prefix . "_singer ( id, ten, tenthat, thumb, introduction, numsong, numalbum, numvideo ) VALUES ( NULL, " . $this->db->dbescape($name) . ", " . $this->db->dbescape($tname) . ", '', '', 0, 0, 0 )";

        $newid = $this->db->sql_query_insert_id($sql);

        if ($newid) {
            $this->db->sql_freeresult();
            $this->del_cache($this->mod_name);
            return $newid;
        }

        return false;
    }

    // Them moi mot nhac si
    public function newauthor($name, $tname)
    {
        $sql = "INSERT INTO " . $this->table_prefix . "_author ( id, ten, tenthat, thumb, introduction, numsong, numvideo) VALUES ( NULL, " . $this->db->dbescape($name) . ", " . $this->db->dbescape($tname) . ", '', '', 0, 0 )";

        $newid = $this->db->sql_query_insert_id($sql);

        if ($newid) {
            $this->db->sql_freeresult();
            $this->del_cache($this->mod_name);
            return $newid;
        }

        return false;
    }

    public function build_query_singer_author($input)
    {
        if (empty($input)) {
            $input = array(0);
        }

        return "0," . implode(",", $input) . ",0";
    }

    public function fix_cat_song($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $_id) {
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . " WHERE theloai=" . $_id . " OR listcat LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_category SET numsong=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);
        }
    }

    public function fix_cat_video($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $_id) {
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . "_video WHERE theloai=" . $_id . " OR listcat LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_video_category SET numvideo=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);
        }
    }

    public function fix_singer($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $_id) {
            // Bai hat
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . " WHERE casi LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_singer SET numsong=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);

            // Album
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . "_album WHERE casi LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_singer SET numalbum=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);

            // Video
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . "_video WHERE casi LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_singer SET numvideo=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);
        }
    }

    public function fix_author($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $_id) {
            // Bai hat
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . " WHERE nhacsi LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_author SET numsong=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);

            // Video
            $sql = "SELECT COUNT(*) FROM " . $this->table_prefix . "_video WHERE nhacsi LIKE '%," . $_id . ",%'";
            $result = $this->db->sql_query($sql);
            list($num) = $this->db->sql_fetchrow($result);

            $sql = "UPDATE " . $this->table_prefix . "_author SET numvideo=" . $num . " WHERE id=" . $_id;
            $this->db->sql_query($sql);
        }
    }

    public function fix_album($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        foreach ($id as $_id) {
            // Lay bai hat cu
            $sql = "SELECT listsong FROM " . $this->table_prefix . "_album WHERE id=" . $_id;
            $result = $this->db->sql_query($sql);
            list($list_song) = $this->db->sql_fetchrow($result);
            $list_song = array_filter(array_unique(explode(',', $list_song)));

            // Lay cau hinh cua cac bai hat them vao danh sach bai hat cua album neu co
            $sql = "SELECT id FROM " . $this->table_prefix . " WHERE album=" . $_id;
            $result = $this->db->sql_query($sql);
            while ($row = $this->db->sql_fetchrow($result)) {
                if (!in_array($row['id'], $list_song)) {
                    $list_song[] = $row['id'];
                }
            }

            // Cap nhat lai so bai hat va danh sach bai hat
            $this->db->sql_query("UPDATE " . $this->table_prefix . "_album SET listsong='" . implode(',', $list_song) . "', numsong=" . sizeof($list_song) . " WHERE id=" . $_id);
        }
    }

    public function edit_song($array_old, $array)
    {
        $array_old['casi'] = empty($array_old['casi']) ? array(0) : $array_old['casi'];
        $array_old['nhacsi'] = empty($array_old['nhacsi']) ? array(0) : $array_old['nhacsi'];
        $array_old['theloai'] = !isset($array_old['theloai']) ? 0 : $array_old['theloai'];
        $array_old['lyric'] = !isset($array_old['lyric']) ? "" : $array_old['lyric'];
        $array_old['listcat'] = !isset($array_old['listcat']) ? array() : (array )$array_old['listcat'];

        $array['ten'] = !isset($array['ten']) ? "" : $array['ten'];
        $array['tenthat'] = !isset($array['tenthat']) ? "" : $array['tenthat'];
        $array['casi'] = empty($array['casi']) ? array(0) : $array['casi'];
        $array['nhacsi'] = empty($array['nhacsi']) ? array(0) : $array['nhacsi'];
        $array['album'] = !isset($array['album']) ? 0 : $array['album'];
        $array['theloai'] = !isset($array['theloai']) ? 0 : $array['theloai'];
        $array['listcat'] = !isset($array['listcat']) ? array() : $array['listcat'];
        $array['duongdan'] = !isset($array['duongdan']) ? "" : $array['duongdan'];
        $array['bitrate'] = !isset($array['bitrate']) ? 0 : $array['bitrate'];
        $array['size'] = !isset($array['size']) ? 0 : $array['size'];
        $array['duration'] = !isset($array['duration']) ? 0 : $array['duration'];
        $array['server'] = !isset($array['server']) ? 1 : $array['server'];
        $array['userid'] = !isset($array['userid']) ? 1 : $array['userid'];
        $array['upboi'] = !isset($array['upboi']) ? "N/A" : $array['upboi'];
        $array['lyric'] = !isset($array['lyric']) ? "" : $array['lyric'];
        $array['lyric_id'] = !isset($array['lyric_id']) ? 0 : $array['lyric_id'];
        $array['id'] = !isset($array['id']) ? 0 : $array['id'];
        $array['is_official'] = !isset($array['is_official']) ? 0 : $array['is_official'];

        $sql = "UPDATE " . $this->table_prefix . " SET 
			ten=" . $this->db->dbescape($array['ten']) . ", 
			tenthat=" . $this->db->dbescape($array['tenthat']) . ", 
			casi='" . $this->build_query_singer_author($array['casi']) . "', 
			nhacsi='" . $this->build_query_singer_author($array['nhacsi']) . "', 
			album=" . $array['album'] . ", 
			theloai=" . $this->db->dbescape($array['theloai']) . ", 
			listcat='" . $this->build_query_singer_author($array['listcat']) . "', 
			duongdan=" . $this->db->dbescape($array['duongdan']) . ", 
			bitrate=" . $array['bitrate'] . " ,
			size=" . $array['size'] . " ,
			duration=" . $array['duration'] . ",
			server=" . $array['server'] . ",
			userid=" . $array['userid'] . ",
			upboi=" . $this->db->dbescape($array['upboi']) . ",
			is_official=" . $array['is_official'] . "
		WHERE id=" . $array['id'];

        $check_update = $this->db->sql_query($sql);

        if ($check_update) {
            // Cap nhat chu de
            $array_cat_update = array_unique(array_filter(array_merge_recursive($array_old['listcat'], array($array_old['theloai']), $array['listcat'], array($array['theloai']))));
            $this->fix_cat_song($array_cat_update);

            // Cap nhat ca si
            $array_singer_update = array_unique(array_filter(array_merge_recursive($array_old['casi'], $array['casi'])));
            $this->fix_singer($array_singer_update);

            // Cap nhat nhac si
            $array_author_update = array_unique(array_filter(array_merge_recursive($array_old['nhacsi'], $array['nhacsi'])));
            $this->fix_author($array_author_update);

            // Cap nhat album
            if ($array_old['album'] != $array['album'])
                $this->fix_album(array($array_old['album'], $array['album']));

            if ($array['lyric'] != $array_old['lyric']) {
                $array['lyric'] = $this->nl2br(strip_tags($array['lyric']), "<br />");

                if ($array['lyric_id']) {
                    $this->db->sql_query("UPDATE " . $this->table_prefix . "_lyric SET body=" . $this->db->dbescape($array['lyric']) . " WHERE id=" . $array['lyric_id']);
                } else {
                    $sql = "INSERT INTO " . $this->table_prefix . "_lyric ( id, songid, user, body, active, dt ) VALUES(
						NULL,
						" . $array['id'] . ",
						" . $this->db->dbescape($array['upboi']) . ",
						" . $this->db->dbescape($array['lyric']) . ",
						1,
						" . $this->currenttime . "
					)";
                    $this->db->sql_query($sql);
                }
            }

            $this->db->sql_freeresult();
        }

        return $check_update;
    }

    public function new_song($array)
    {
        $array['ten'] = !isset($array['ten']) ? "" : $array['ten'];
        $array['tenthat'] = !isset($array['tenthat']) ? "" : $array['tenthat'];
        $array['casi'] = empty($array['casi']) ? array(0) : $array['casi'];
        $array['nhacsi'] = empty($array['nhacsi']) ? array(0) : $array['nhacsi'];
        $array['album'] = !isset($array['album']) ? 0 : $array['album'];
        $array['theloai'] = !isset($array['theloai']) ? 0 : $array['theloai'];
        $array['listcat'] = !isset($array['listcat']) ? array() : $array['listcat'];
        $array['duongdan'] = !isset($array['duongdan']) ? "" : $array['duongdan'];
        $array['upboi'] = !isset($array['upboi']) ? "N/A" : $array['upboi'];
        $array['bitrate'] = !isset($array['bitrate']) ? "0" : $array['bitrate'];
        $array['size'] = !isset($array['size']) ? "0" : $array['size'];
        $array['duration'] = !isset($array['duration']) ? "0" : $array['duration'];
        $array['server'] = !isset($array['server']) ? "1" : $array['server'];
        $array['userid'] = !isset($array['userid']) ? "1" : $array['userid'];
        $array['hit'] = !isset($array['hit']) ? "0-" . NV_CURRENTTIME : $array['hit'];
        $array['lyric'] = !isset($array['lyric']) ? "" : $array['lyric'];
        $array['lyric_id'] = !isset($array['lyric_id']) ? 0 : (int)$array['lyric_id'];
        $array['is_official'] = !isset($array['is_official']) ? 0 : (int)$array['is_official'];

        $sql = "INSERT INTO " . $this->table_prefix . " VALUES (
			NULL, 
			" . $this->db->dbescape($array['ten']) . ", 
			" . $this->db->dbescape($array['tenthat']) . ", 
			'" . $this->build_query_singer_author($array['casi']) . "', 
			'" . $this->build_query_singer_author($array['nhacsi']) . "', 
			" . $array['album'] . ", 
			" . $this->db->dbescape($array['theloai']) . ", 
			'" . $this->build_query_singer_author($array['listcat']) . "', 
			" . $this->db->dbescape($array['duongdan']) . ", 
			" . $this->db->dbescape($array['upboi']) . " ,
			0,
			1,
			" . $this->db->dbescape($array['bitrate']) . " ,
			" . $this->db->dbescape($array['size']) . " ,
			" . $this->db->dbescape($array['duration']) . ",
			" . $array['server'] . ",
			" . $array['userid'] . ",
			" . NV_CURRENTTIME . ",
			0,
			" . $this->db->dbescape($array['hit']) . ",
			" . $array['is_official'] . "
		)";

        $songid = $this->db->sql_query_insert_id($sql);

        if ($songid) {
            $this->fix_cat_song(array_unique(array_filter(array_merge_recursive(array($array['theloai']), $array['listcat']))));
            $this->fix_singer($array['casi']);
            $this->fix_author($array['nhacsi']);
            $this->fix_album($array['album']);

            if (!empty($array['lyric'])) {
                $array['lyric'] = $this->nl2br(strip_tags($array['lyric']), "<br />");
                $sql = "INSERT INTO " . $this->table_prefix . "_lyric ( id, songid, user, body, active, dt ) VALUES(
					NULL,
					" . $songid . ",
					" . $this->db->dbescape($array['upboi']) . ",
					" . $this->db->dbescape($array['lyric']) . ",
					1,
					" . $this->currenttime . "
				)";
                $this->db->sql_query($sql);
            }

            $this->db->sql_freeresult();
        }

        return $songid;
    }

    // Tao duong dan tu mot chuoi
    public function creatURL($inputurl)
    {
        $songdata = array();
        if (preg_match('/^(ht|f)tp:\/\//', $inputurl)) {
            $ftpdata = $this->getFTP();
            $str_inurl = str_split($inputurl);
            $no_ftp = true;

            foreach ($ftpdata as $id => $data) {
                $this_host = $data['fulladdress'] . $data['subpart'];
                $str_check = str_split($this_host);
                $check_ok = false;
                foreach ($str_check as $stt => $char) {
                    if ($char != $str_inurl[$stt]) {
                        $check_ok = false;
                        break;
                    }
                    $check_ok = true;
                }
                if ($check_ok) {
                    $lu = strlen($this_host);
                    $songdata['duongdan'] = substr($inputurl, $lu);
                    $songdata['server'] = $id;
                    $no_ftp = false;
                    break;
                }
            }
            if ($no_ftp) {
                $songdata['duongdan'] = $inputurl;
                $songdata['server'] = 0;
            }
        } else {
            $lu = strlen($this->base_site_url . $this->upload_dir . "/" . $this->mod_name . "/" . $this->setting['root_contain'] . "/");
            $songdata['duongdan'] = substr($inputurl, $lu);
            $songdata['server'] = 1;
        }
        return $songdata;
    }

    // Xoa cac binh luan
    public function delcomment($delwwhat, $where)
    {
        $sql = "DELETE FROM " . $this->table_prefix . "_comment_" . $delwwhat . " WHERE what=" . $where;
        return $this->db->sql_query($sql);
    }

    // Xoa cac loi bai hat
    public function dellyric($songid)
    {
        $sql = "DELETE FROM " . $this->table_prefix . "_lyric WHERE songid=" . $songid;
        $result = $this->db->sql_query($sql);
        return $result;
    }

    // Xoa cac bao loi
    public function delerror($where, $key)
    {
        $sql = "DELETE FROM " . $this->table_prefix . "_error WHERE where= '" . $where . "' AND sid=" . $key;
        return $this->db->sql_query($sql);
    }

    // Xoa cac qua tang am nhac
    function delgift($songid)
    {
        $sql = "DELETE FROM " . $this->table_prefix . "_gift WHERE songid =" . $songid;
        return $this->db->sql_query($sql);
    }

    // Xoa file nhac va file video
    public function unlinkSV($server, $url)
    {
        if ($server == 1) {
            @unlink($this->root_dir . "/" . $this->upload_dir . "/" . $this->mod_name . "/" . $this->setting['root_contain'] . "/" . $url);
        } elseif ($server != 0) {
            $ftpdata = $this->getFTP();

            if (!isset($ftpdata[$server]))
                return;

            if (in_array($ftpdata[$server]['host'], array(
                'nhaccuatui',
                'zing',
                'nhacvui',
                'nhacso',
                'zingclip',
                'nctclip')))
                return;

            require_once ($this->root_dir . "/modules/" . $this->mod_file . "/class/ftp.class.php");
            $ftp = new FTP();
            if ($ftp->connect($ftpdata[$server]['host'])) {
                if ($ftp->login($ftpdata[$server]['user'], $ftpdata[$server]['pass'])) {
                    $ftp->delete($ftpdata[$server]['ftppart'] . $ftpdata[$server]['subpart'] . $url);
                }
                $ftp->disconnect();
            }
        }
    }

    public function convertfromBytes($size)
    {
        return nv_convertfromBytes($size);
    }

    public function convert_bitrate($bitrate)
    {
        return round($bitrate / 1000) . " kbs";
    }

    public function convert_duration($duration)
    {
        return (int)($duration / 60) . ":" . round($duration % 60);
    }

    public function build_query_search_id($id, $field, $logic = 'OR')
    {
        if (empty($id))
            return $field . "=''";

        if (is_array($id)) {
            $query = array();
            foreach ($id as $_id) {
                $query[] = $field . " LIKE '%," . $_id . ",%'";
            }
            $query = implode(" " . $logic . " ", $query);
        } else {
            $query = $field . " LIKE '%," . $id . ",%'";
        }

        return $query;
    }
}
