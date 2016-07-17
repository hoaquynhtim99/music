<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

// Lay album tu ten
function getalbumbyNAME($name)
{
    global $module_data, $db;

    $album = array();
    $result = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE name=" . $db->quote($name));
    $album = $result->fetch();

    return $album;
}

// Xuat duong dan day du
function outputURL($server, $inputurl)
{
    global $module_name, $classMusic;
    $output = "";
    if ($server == 0) {
        $output = $inputurl;
    } elseif ($server == 1) {
        $output = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $classMusic->setting['root_contain'] . "/" . $inputurl;
    } else {
        $ftpdata = getFTP();
        foreach ($ftpdata as $id => $data) {
            if ($id == $server) {
                if ($data['host'] == "nhaccuatui") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);
                        $cache = "";

                        if (preg_match("/\[FLASH\](.*?)\[\/FLASH\]/i", $output, $m)) {
                            $output = get_headers($m[1]);

                            foreach ($output as $tmp) {
                                if (preg_match("/^Location: (.*)/is", $tmp, $m)) {
                                    if (preg_match("/file\=(.*)\&ads\=/is", $tmp, $m)) {
                                        $output = simplexml_load_string(nv_get_URL_content($m[1]));
                                        $output = trim((string )$output->track->location);
                                        break;
                                    }
                                }
                            }
                        }

                        $cache = serialize($cache);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } elseif ($data['host'] == "zing") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);
                        $output = explode('<input type="hidden" id="_strNoAuto" value="', $output);

                        if (isset($output[1])) {
                            $output = explode('"', $output[1]);
                            $output = nv_get_URL_content($output[0]);
                            $output = explode("<urlSource>", $output);

                            if (isset($output[1])) {
                                $output = explode("</urlSource>", $output[1]);
                                $output = nv_unhtmlspecialchars($output[0]);
                            } else {
                                $output = "";
                            }
                        } else {
                            $output = "";
                        }

                        $cache = serialize($output);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } elseif ($data['host'] == "nhacvui") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);

                        unset($m);
                        $pattern = "/\'playlistfile\'\: \'(.*?)\'\,/i";
                        if (!empty($output) and preg_match($pattern, $output, $m)) {
                            $output = nv_get_URL_content("http://hcm.nhac.vui.vn" . trim($m[1]));
                            unset($m);
                            $pattern = "/\<jwplayer\:file\>\<\!\[CDATA\[(.*?)\]\]\>\<\/jwplayer\:file\>/i";
                            if (!empty($output) and preg_match($pattern, $output, $m)) {
                                $output = trim($m[1]);
                            } else {
                                $output = "";
                            }
                        } else {
                            $output = "";
                        }

                        $cache = serialize($output);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } elseif ($data['host'] == "nhacso") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);

                        $output = explode('embedPlaylistjs.swf?xmlPath=', $output);

                        if (isset($output[1])) {
                            $output = explode('&amp;', $output[1]);
                            $output = nv_get_URL_content($output[0]);

                            $output = explode("<mp3link><![CDATA[", $output);

                            if (isset($output[1])) {
                                $output = explode("]]></mp3link>", $output[1]);
                                $output = trim($output[0]);
                            } else {
                                $output = "";
                            }
                        } else {
                            $output = "";
                        }

                        $cache = serialize($output);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } elseif ($data['host'] == "zingclip") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_zingclip_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);

                        unset($m);
                        if (!preg_match("/\<input type\=\"hidden\" id\=\"\_strAuto\" value\=\"([^\"]+)\"[^\/]+\/\>/is", $output, $m)) {
                            $output = "";
                        } else {
                            $output = nv_get_URL_content($m[1]);
                            if (($xml = simplexml_load_string($output)) == false)
                                return "";
                            $output = (string )$xml->item->f480;
                        }

                        $cache = serialize($output);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } elseif ($data['host'] == "nctclip") {
                    $cache_file = NV_LANG_DATA . "_" . $module_name . "_link_nctclip_" . md5($server . $inputurl) . "_" . NV_CACHE_PREFIX . ".cache";

                    if (file_exists(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) {
                        if (((NV_CURRENTTIME - filemtime(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file)) > $classMusic->setting['del_cache_time_out']) and $classMusic->setting['del_cache_time_out'] != 0) {
                            nv_deletefile(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file);
                        }
                    }

                    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
                        $output = unserialize($cache);
                    } else {
                        $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                        $output = nv_get_URL_content($output);

                        if (!preg_match("/\<input id\=\"urlEmbedBlog\" type\=\"text\" readonly\=\"readonly\" value\=\"\[FLASH\](.*?)\[\/FLASH\]\" class\=\"link3\" \/\>/is", $output, $m)) {
                            $output = "";
                        } else {
                            $tmp = get_headers($m[1]);
                            $output = "";
                            foreach ($tmp as $_tmp) {
                                if (preg_match("/file\=(.*?)\&autostart\=/is", $_tmp, $m)) {
                                    $output = nv_get_URL_content($m[1]);
                                    if (($xml = simplexml_load_string($output)) == false)
                                        return "";
                                    $output = trim((string )$xml->track->location);
                                }
                            }
                        }

                        $cache = serialize($output);
                        $nv_Cache->setItem($module_name, $cache_file, $cache);
                    }
                } else {
                    $output = $data['fulladdress'] . $data['subpart'] . $inputurl;
                    break;
                }
            }
        }
    }
    return $output;
}

function nv_get_URL_content($target_url)
{
    global $global_config;

    require_once (NV_ROOTDIR . "/includes/class/geturl.class.php");

    $UrlGetContents = new NukeViet\Client\UrlGetContents($global_config);
    return $UrlGetContents->get($target_url);
}
