<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if (!defined('NV_IS_FILE_SITEINFO'))
    die('Stop!!!');

$lang_siteinfo = nv_get_lang_module($mod);

// So bai hat
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_numsong'], 'value' => $number);
}

// So video
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_video")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_numvideo'], 'value' => $number);
}

// So album
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_album")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_numalbum'], 'value' => $number);
}

// So binh luan cho bai hat
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_comment_song")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_commentsong'], 'value' => $number);
}

// So binh luan cho album
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_comment_album")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_commentalbum'], 'value' => $number);
}

// So bao loi chua doc
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_error")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_error'], 'value' => $number);
}

// So qua tang
$number = $db->query("SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "_gift")->fetchColumn();
if ($number > 0) {
    $siteinfo[] = array('key' => $lang_siteinfo['siteinfo_gift'], 'value' => $number);
}
