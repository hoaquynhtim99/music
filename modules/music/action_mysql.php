<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');

$sql_drop_module = array();

global $op, $db, $global_config, $db_config;

// Lay nhung ngon ngu da co
$sql = "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . str_replace("_", "\_", $module_data) . "\_config\_%'";
$result = $db->query($sql);
$num_table = $result->rowCount();

$array_lang_module_setup = array(); // Cac ngon ngu da cai
$set_lang_data = ""; // Ngon ngu mac dinh

if ($num_table > 0) {
    // Cac ngon ngu da cai dat
    while ($item = $result->fetch()) {
        $array_lang_module_setup[] = str_replace($db_config['prefix'] . "_" . $module_data . "_config_", "", $item['name']);
    }

    // Ngon ngu mac dinh de copy du lieu
    if ($lang != $global_config['site_lang'] and in_array($global_config['site_lang'], $array_lang_module_setup)) {
        $set_lang_data = $global_config['site_lang'];
    } else {
        foreach ($array_lang_module_setup as $lang_i) {
            if ($lang != $lang_i) {
                $set_lang_data = $lang_i;
                break;
            }
        }
    }
}

// Xóa các trường dữ liệu khi xóa module ở ngôn ngữ đã cài
if (in_array($lang, $array_lang_module_setup) and $num_table > 1) {

} elseif ($op != "setup") {
    // Xóa hết bảng dữ liệu
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_categories";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs";
}

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_config_" . $lang;

$sql_create_module = $sql_drop_module;

// Danh mục bài hát, video, albums
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_categories (
  cat_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  cat_parentid smallint(4) unsigned NOT NULL DEFAULT '0',
  cat_level smallint(4) unsigned NOT NULL DEFAULT '0',
  cat_subs varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách các chủ đề con đã build theo weight',
  cat_weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thứ tự theo từng cấp độ',
  cat_sort smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thứ tự cho tất cả',
  cat_status tinyint(1) NOT NULL DEFAULT '0',
  stat_songs int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát',
  stat_albums int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số album',
  stat_videos int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số video',
  PRIMARY KEY (cat_id),
  KEY cat_parentid (cat_parentid),
  KEY cat_status (cat_status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_categories 
	ADD " . $lang . "_title varchar(250) NOT NULL DEFAULT '',
	ADD " . $lang . "_alias varchar(250) NOT NULL DEFAULT '',
	ADD " . $lang . "_introtext mediumtext NOT NULL,
	ADD " . $lang . "_keywords mediumtext NOT NULL
";

// Bảng bài hát
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs (
  song_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  song_code varchar(10) NOT NULL DEFAULT '' COMMENT 'Mã bài hát, dùng công khai',
  song_alias varchar(250) NOT NULL DEFAULT '' COMMENT 'Alias bài hát',
  song_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên đầy đủ bài hát',
  singer_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng 0,id1,id2,id3,...,0',
  author_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của nhạc sĩ có dạng 0,id1,id2,id3,...,0',
  album_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID album của bài hát',
  cat_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID thể loại chính của bài hát',
  cat_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID phụ của thể loại có dạng id1,id2,...',
  server_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID server chứa nhạc',
  file_path varchar(255) NOT NULL DEFAULT '' COMMENT 'Link đến file nhạc hoặc remote URL',
  file_bitrate bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bitrate của bài hát',
  file_size bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Kích thước file nhạc',
  file_duration bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian file nhạc',
  uploader_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  uploader_name varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_hit varchar(50) NOT NULL DEFAULT '' COMMENT 'Dữ liệu về độ HOT của bài hát',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  is_official tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Chính thức, 0: Thành viên đăng',
  status tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  PRIMARY KEY (song_id),
  UNIQUE KEY song_code (song_code),
  KEY song_alias (song_alias),
  KEY song_name (song_name),
  KEY singer_ids (singer_ids),
  KEY author_ids (author_ids),
  KEY album_id (album_id),
  KEY cat_id (cat_id),
  KEY cat_ids (cat_ids),
  KEY server_id (server_id),
  KEY uploader_id (uploader_id),
  KEY is_official (is_official),
  KEY status (status)
) ENGINE=MyISAM";

/*
// Album
$sql_create_module[] = " CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums (
  album_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  album_code varchar(10) NOT NULL DEFAULT '' COMMENT 'Mã album 8 ký tự',
  album_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên gọi của album',
  album_image varchar(250) NOT NULL DEFAULT '' COMMENT 'Ảnh bìa của album',
  album_description mediumtext NOT NULL COMMENT 'Mô tả album',
  song_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách id bài hát có dạng id1,id2,id3...',
  singer_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng 0,id1,id2,id3,...,0',
  uploader_id mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  uploader_name varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_numsongs smallint(4) NOT NULL DEFAULT '0' COMMENT 'Số bài hát',
  stat_hit varchar(50) NOT NULL DEFAULT '' COMMENT 'Thông tin HIT của album',
  status tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  addtime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  updatetime int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (album_id),
  UNIQUE KEY album_code (album_code),
  KEY album_name (album_name),
  KEY song_ids (song_ids),
  KEY singer_ids (singer_ids),
) ENGINE=MyISAM";

// Loi bai hat
$sql_create_module[] = " CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_lyrics (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  songid mediumint(8) unsigned NOT NULL DEFAULT '0',
  user varchar(255) NOT NULL DEFAULT '',
  body text NOT NULL,
  active smallint(2) unsigned NOT NULL DEFAULT '0',
  dt int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// Videoclip
$sql_create_module[] = " CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  tname varchar(255) NOT NULL DEFAULT '',
  casi varchar(255) NOT NULL DEFAULT '',
  nhacsi varchar(255) NOT NULL DEFAULT '',
  theloai mediumint(8) unsigned NOT NULL DEFAULT '0',
  listcat varchar(255) NOT NULL DEFAULT '',
  duongdan varchar(255) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  view mediumint(8) unsigned NOT NULL DEFAULT '0',
  active smallint(2) unsigned NOT NULL DEFAULT '0',
  dt int(11) unsigned NOT NULL DEFAULT '0',
  server mediumint(8) unsigned NOT NULL DEFAULT '0',
  binhchon mediumint(8) unsigned NOT NULL DEFAULT '0',
  hit varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// Ca si
$sql_create_module[] = " CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_singers (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  ten varchar(100) NOT NULL DEFAULT '',
  tenthat varchar(100) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  introduction text NOT NULL,
  numsong mediumint(8) unsigned NOT NULL DEFAULT '0',
  numalbum mediumint(8) unsigned NOT NULL DEFAULT '0',
  numvideo mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// Nhac si
$sql_create_module[] = " CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_authors (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  ten varchar(255) NOT NULL DEFAULT '',
  tenthat varchar(255) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  introduction text NOT NULL ,
  numsong mediumint(8) unsigned NOT NULL DEFAULT '0',
  numvideo mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

// Cau hinh FTP mac dinh
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_ftp 
(id, host, user, pass, fulladdress, subpart, ftppart, active) VALUES
(2, 'nhaccuatui', 'hoaquynhtim99', 'hoaquynhtim99', 'http://www.nhaccuatui.com/', 'bai-hat', '/', 1),
(3, 'zing', 'hoaquynhtim99', 'hoaquynhtim99', 'http://mp3.zing.vn/', 'bai-hat', '/', 1),
(4, 'nhacvui', 'hoaquynhtim99', 'hoaquynhtim99', 'http://hcm.nhac.vui.vn', '/', '/', 1),
(5, 'nhacso', 'hoaquynhtim99', 'hoaquynhtim99', 'http://nhacso.net/', 'nghe-nhac', '/', 1),
(6, 'zingclip', 'hoaquynhtim99', 'hoaquynhtim99', 'http://mp3.zing.vn/video-clip', '/', '/', 1),
(7, 'nctclip', 'hoaquynhtim99', 'hoaquynhtim99', 'http://www.nhaccuatui.com/video', '/', '/', 1)";
*/

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_config_" . $lang . " (
  config_name varchar(100) NOT NULL,
  config_value text NOT NULL,
  UNIQUE KEY config_name (config_name)
)ENGINE=MyISAM";

$default_config = array();
$default_config['type_main'] = '1';
$default_config['num_per_page'] = '10';
$default_config['files_dir'] = 'files';
$default_config['allow_currency'] = 'VND,USD';
$default_config['auto_comment'] = '0';
$default_config['allow_price'] = '0';
$default_config['discount'] = '25';
$default_config['payday'] = '5';
$default_config['equivalent'] = 'VND';
$default_config['rootemail'] = 'store@nukeviet.vn';
$default_config['remind_type'] = '0';
$default_config['remind_time'] = '0';
$default_config['forbidden_list'] = 'authors,database,extensions,language,modules,seotools,settings,siteinfo,themes,upload,webtools';
$default_config['shareport'] = 'none';
$default_config['addthis_pubid'] = '';

foreach ($default_config as $config_name => $config_value) {
    $sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_config_" . $lang . " (config_name, config_value) VALUES('" . $config_name . "', '" . $config_value . "')";
}
