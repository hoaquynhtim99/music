<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];

global $op, $db, $global_config, $db_config;

$array_lang_module_setup = []; // Những ngôn ngữ mà module này đã cài đặt vào (Bao gồm cả ngôn ngữ đang thao tác)
$num_module_exists = 0; // Số ngôn ngữ đã cài (Bao gồm cả ngôn ngữ đang thao tác)
$set_lang_data = ''; // Ngôn ngữ mặc định sẽ copy các cột vào

// Xác định các ngôn ngữ đã cài đặt
$_sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1";
$_result = $db->query($_sql);
$array_lang_setup = [];
while ($_row = $_result->fetch()) {
    $array_lang_setup[$_row['lang']] = $_row['lang'];
}

// Xác định các ngôn ngữ đã cài module
foreach ($array_lang_setup as $_lang) {
    $is_setup = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $_lang . "_modules WHERE module_data=" . $db->quote($module_data))->fetchColumn();
    if ($is_setup and $op != 'setup') {
        $array_lang_module_setup[$_lang] = $_lang;
    }
}

// Xác định ngôn ngữ mặc định sẽ copy các cột vào
if ($lang != $global_config['site_lang'] and in_array($global_config['site_lang'], $array_lang_module_setup)) {
    $set_lang_data = $global_config['site_lang'];
} else {
    foreach ($array_lang_module_setup as $_lang) {
        if ($lang != $_lang) {
            $set_lang_data = $_lang;
            break;
        }
    }
}

// Tính toán số module đã cài (Bao gồm cả ngôn ngữ đang thao tác)
$num_module_exists = sizeof($array_lang_module_setup);

// Xóa các trường dữ liệu khi xóa module ở ngôn ngữ đã cài (có từ 2 module đã cài trở lên)
if (in_array($lang, $array_lang_module_setup) and $num_module_exists > 1) {
    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_categories
      DROP " . $lang . "_cat_name,
      DROP " . $lang . "_cat_alias,
      DROP " . $lang . "_cat_absitetitle,
      DROP " . $lang . "_cat_abintrotext,
      DROP " . $lang . "_cat_abkeywords,
      DROP " . $lang . "_cat_mvsitetitle,
      DROP " . $lang . "_cat_mvintrotext,
      DROP " . $lang . "_cat_mvkeywords
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_nations
      DROP " . $lang . "_nation_name,
      DROP " . $lang . "_nation_alias,
      DROP " . $lang . "_nation_introtext,
      DROP " . $lang . "_nation_keywords
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_artists
      DROP " . $lang . "_artist_name,
      DROP " . $lang . "_artist_alias,
      DROP " . $lang . "_artist_alphabet,
      DROP " . $lang . "_artist_searchkey,
      DROP " . $lang . "_artist_realname,
      DROP " . $lang . "_artist_hometown,
      DROP " . $lang . "_singer_nickname,
      DROP " . $lang . "_singer_prize,
      DROP " . $lang . "_singer_info,
      DROP " . $lang . "_singer_introtext,
      DROP " . $lang . "_singer_keywords,
      DROP " . $lang . "_author_nickname,
      DROP " . $lang . "_author_prize,
      DROP " . $lang . "_author_info,
      DROP " . $lang . "_author_introtext,
      DROP " . $lang . "_author_keywords,
      DROP INDEX " . $lang . "_artist_alphabet,
      DROP INDEX " . $lang . "_artist_searchkey
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_song
      DROP " . $lang . "_quality_name,
      DROP " . $lang . "_quality_alias,
      DROP INDEX " . $lang . "_quality_alias
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_video
      DROP " . $lang . "_quality_name,
      DROP " . $lang . "_quality_alias,
      DROP INDEX " . $lang . "_quality_alias
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_songs
      DROP " . $lang . "_song_name,
      DROP " . $lang . "_song_alias,
      DROP " . $lang . "_song_searchkey,
      DROP " . $lang . "_song_introtext,
      DROP " . $lang . "_song_keywords,
      DROP INDEX " . $lang . "_song_searchkey
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_albums
      DROP " . $lang . "_album_name,
      DROP " . $lang . "_album_alias,
      DROP " . $lang . "_album_searchkey,
      DROP " . $lang . "_album_introtext,
      DROP " . $lang . "_album_description,
      DROP " . $lang . "_album_keywords,
      DROP INDEX " . $lang . "_album_searchkey
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_videos
      DROP " . $lang . "_video_name,
      DROP " . $lang . "_video_alias,
      DROP " . $lang . "_video_searchkey,
      DROP " . $lang . "_video_introtext,
      DROP " . $lang . "_video_keywords,
      DROP INDEX " . $lang . "_video_searchkey
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_config
      DROP config_value_" . $lang . "
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_user_playlists
      DROP " . $lang . "_playlist_name,
      DROP " . $lang . "_playlist_introtext
    ";

    $sql_drop_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_chart_categories
      DROP " . $lang . "_cat_name,
      DROP " . $lang . "_cat_alias,
      DROP " . $lang . "_cat_absitetitle,
      DROP " . $lang . "_cat_abintrotext,
      DROP " . $lang . "_cat_abkeywords,
      DROP " . $lang . "_cat_abbodytext,
      DROP " . $lang . "_cat_mvsitetitle,
      DROP " . $lang . "_cat_mvintrotext,
      DROP " . $lang . "_cat_mvkeywords,
      DROP " . $lang . "_cat_mvbodytext,
      DROP " . $lang . "_cat_sositetitle,
      DROP " . $lang . "_cat_sointrotext,
      DROP " . $lang . "_cat_sokeywords,
      DROP " . $lang . "_cat_sobodytext
    ";
} elseif ($op != "setup") {
    // Xóa hết bảng dữ liệu
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_categories";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_nations";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_artists";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_quality_song";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_quality_video";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_caption";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_data";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_random";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums_data";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums_random";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos_data";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos_random";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_config";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_statistics";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_playlists";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_albums";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_videos";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_songs";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_charts";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_chart_categories";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_chart_tmps";
}

$sql_create_module = $sql_drop_module;

// Danh mục nói chung
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_categories (
  cat_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  cat_code varchar(4) NOT NULL COMMENT 'Ký tự A-Z0-9. Độ dài 4 ký tự.',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar mặc định cho ca sĩ, bài hát',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover mặc định',
  resource_video varchar(255) NOT NULL COMMENT 'Ảnh mặc định cho video',
  stat_albums int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số album',
  stat_songs int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát',
  stat_videos int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số video',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  show_inalbum tinyint(1) unsigned NOT NULL DEFAULT '0',
  show_invideo tinyint(1) unsigned NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (cat_id),
  UNIQUE KEY cat_code (cat_code),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_categories
    ADD " . $lang . "_cat_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_absitetitle varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_abintrotext text NOT NULL,
    ADD " . $lang . "_cat_abkeywords text NOT NULL,
    ADD " . $lang . "_cat_mvsitetitle varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_mvintrotext text NOT NULL,
    ADD " . $lang . "_cat_mvkeywords text NOT NULL
";

// Quốc gia: Áp dụng cho ca sĩ nhạc sĩ
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_nations (
  nation_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  nation_code varchar(4) NOT NULL COMMENT 'Ký tự A-Z0-9. Độ dài 4 ký tự.',
  stat_singers int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số ca sĩ',
  stat_authors int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số nhạc sĩ',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (nation_id),
  UNIQUE KEY nation_code (nation_code),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_nations
    ADD " . $lang . "_nation_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_nation_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_nation_introtext text NOT NULL,
    ADD " . $lang . "_nation_keywords text NOT NULL
";

// Bảng ca+nhạc sĩ
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_artists (
  artist_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  artist_code varchar(5) NOT NULL COMMENT 'Ký tự A-Z0-9. Độ dài 5 ký tự.',
  artist_type smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0: Ca sĩ, 1: Nhạc sĩ, 2: Ca+Nhạc sĩ',
  artist_birthday int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Sinh nhật',
  artist_birthday_lev smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '1,2,3',
  nation_id smallint(4) unsigned NOT NULL DEFAULT '0',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover',
  stat_singer_albums int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số albums của ca sĩ',
  stat_singer_songs int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát của ca sĩ',
  stat_singer_videos int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số video của ca sĩ',
  stat_author_songs int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát của nhạc sĩ',
  stat_author_videos int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số video của nhạc sĩ',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  show_inhome tinyint(1) NOT NULL DEFAULT '0',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (artist_id),
  UNIQUE KEY artist_code (artist_code),
  KEY artist_type (artist_type),
  KEY nation_id (nation_id),
  KEY show_inhome (show_inhome),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_artists
    ADD " . $lang . "_artist_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_artist_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_artist_alphabet varchar(1) NOT NULL DEFAULT '',
    ADD " . $lang . "_artist_searchkey varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_artist_realname varchar(255) NOT NULL DEFAULT '',
    ADD " . $lang . "_artist_hometown varchar(255) NOT NULL DEFAULT '',
    ADD " . $lang . "_singer_nickname varchar(255) NOT NULL DEFAULT '',
    ADD " . $lang . "_singer_prize text NOT NULL,
    ADD " . $lang . "_singer_info mediumtext NOT NULL,
    ADD " . $lang . "_singer_introtext text NOT NULL,
    ADD " . $lang . "_singer_keywords text NOT NULL,
    ADD " . $lang . "_author_nickname varchar(255) NOT NULL DEFAULT '',
    ADD " . $lang . "_author_prize text NOT NULL,
    ADD " . $lang . "_author_info mediumtext NOT NULL,
    ADD " . $lang . "_author_introtext text NOT NULL,
    ADD " . $lang . "_author_keywords text NOT NULL,
    ADD INDEX " . $lang . "_artist_alphabet (" . $lang . "_artist_alphabet),
    ADD INDEX " . $lang . "_artist_searchkey (" . $lang . "_artist_searchkey)
";

// Bảng chất lượng bài hát
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_quality_song (
  quality_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  online_supported tinyint(1) NOT NULL DEFAULT '0',
  is_default tinyint(1) NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (quality_id),
  KEY is_default (is_default),
  KEY online_supported (online_supported),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_song
    ADD " . $lang . "_quality_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_quality_alias varchar(250) NOT NULL DEFAULT ''
";

// Bảng chất lượng video
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_quality_video (
  quality_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  online_supported tinyint(1) NOT NULL DEFAULT '0',
  is_default tinyint(1) NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (quality_id),
  KEY is_default (is_default),
  KEY online_supported (online_supported),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_video
    ADD " . $lang . "_quality_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_quality_alias varchar(250) NOT NULL DEFAULT ''
";

// Bảng bài hát
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs (
  song_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  song_code varchar(8) NOT NULL DEFAULT '' COMMENT 'Mã bài hát, dùng công khai',
  cat_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID phụ của thể loại có dạng id1,id2,...',
  singer_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng id1,id2,id3,...',
  author_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của nhạc sĩ có dạng id1,id2,id3,...',
  album_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách album của bài hát có dạng id1,id2,id3,...',
  video_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID video của bài hát',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover',
  uploader_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  uploader_name varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_comments int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  stat_shares int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  stat_downloads int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt tải',
  stat_hit int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Dữ liệu về độ HOT của bài hát',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  is_official tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Chính thức, 0: Thành viên đăng',
  show_inhome tinyint(1) NOT NULL DEFAULT '0',
  caption_supported varchar(250) NOT NULL DEFAULT '' COMMENT 'Các ngôn ngữ hỗ trợ lời karaoke có dạng vi,en,fr,...',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  PRIMARY KEY (song_id),
  UNIQUE KEY song_code (song_code),
  KEY singer_ids (singer_ids),
  KEY author_ids (author_ids),
  KEY album_ids (album_ids),
  KEY video_id (video_id),
  KEY cat_ids (cat_ids),
  KEY uploader_id (uploader_id),
  KEY is_official (is_official),
  KEY show_inhome (show_inhome),
  KEY caption_supported (caption_supported),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_songs
    ADD " . $lang . "_song_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_song_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_song_searchkey varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_song_introtext text NOT NULL,
    ADD " . $lang . "_song_keywords text NOT NULL,
    ADD INDEX " . $lang . "_song_searchkey (" . $lang . "_song_searchkey)
";

// Bảng lưu lời bài hát dạng karaoke
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_caption (
  song_id int(11) unsigned NOT NULL,
  caption_lang varchar(10) NOT NULL,
  caption_file varchar(255) NOT NULL DEFAULT '' COMMENT 'File lời bài hát dạng hỗ trợ chạy lyric',
  caption_pdf varchar(255) NOT NULL DEFAULT '' COMMENT 'File lời bài hát dạng xem trực tuyến',
  caption_data text NOT NULL COMMENT 'Dữ liệu dạng text',
  is_default tinyint(1) NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY song_id (song_id, caption_lang),
  KEY is_default (is_default),
  KEY status (status)
) ENGINE=MyISAM";

// Bảng lưu đường dẫn file nhạc theo định dạng
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_data (
  song_id int(11) unsigned NOT NULL,
  quality_id smallint(4) NOT NULL,
  resource_server_id smallint(4) NOT NULL DEFAULT '0' COMMENT '-1 là link ngoài, 0 là local, > 0 là fileserver',
  resource_path varchar(255) NOT NULL DEFAULT '',
  resource_duration int(11) NOT NULL DEFAULT '0' COMMENT 'Thời lượng',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  UNIQUE KEY id (song_id, quality_id),
  KEY status (status)
) ENGINE=MyISAM";

/*
 * Bảng lưu ID bài hát để lấy random các bài hát khi nghe một bài hát
 * Nguyên tắc: Mỗi thể loại lưu 1000 bài hát mới nhất
 * Mục đích: Tăng tốc truy vấn.
 * Bài hát trong này là public, nghĩa là chỉ cần lấy ra, không quan tâm đến bài hát chính thức hay do thành viên upload,
 * việc control này do chức năng trong quản trị xử lý khi thêm, sửa bài hát
 *
 * Thử nghiệm dữ liệu: 65.000 rows nếu random ở bảng songs tốn ~600ms thì random bảng này tốn ~5ms (tức tốc độ nhanh hơn khoảng 100 lần!!!)
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_songs_random (
  song_id int(11) unsigned NOT NULL,
  cat_id int(11) unsigned NOT NULL,
  PRIMARY KEY id (song_id, cat_id)
) ENGINE=MyISAM";

// Bảng albums
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums (
  album_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  album_code varchar(8) NOT NULL DEFAULT '' COMMENT 'Mã bài hát, dùng công khai',
  cat_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID phụ của thể loại có dạng id1,id2,...',
  singer_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng id1,id2,id3,...',
  release_year smallint(4) NOT NULL DEFAULT '0' COMMENT 'Năm phát hành',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover',
  uploader_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  uploader_name varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_comments int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  stat_shares int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  stat_hit int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Dữ liệu về độ HOT của bài hát',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  is_official tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Chính thức, 0: Thành viên đăng',
  show_inhome tinyint(1) NOT NULL DEFAULT '0',
  num_songs smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát trong album',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  PRIMARY KEY (album_id),
  UNIQUE KEY album_code (album_code),
  KEY singer_ids (singer_ids),
  KEY cat_ids (cat_ids),
  KEY uploader_id (uploader_id),
  KEY is_official (is_official),
  KEY show_inhome (show_inhome),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_albums
    ADD " . $lang . "_album_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_album_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_album_searchkey varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_album_introtext text NOT NULL,
    ADD " . $lang . "_album_description mediumtext NOT NULL,
    ADD " . $lang . "_album_keywords text NOT NULL,
    ADD INDEX " . $lang . "_album_searchkey (" . $lang . "_album_searchkey)
";

// Bảng lưu bài hát trong album
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums_data (
  album_id int(11) unsigned NOT NULL,
  song_id int(11) unsigned NOT NULL,
  weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  UNIQUE KEY id (album_id, song_id),
  KEY status (status)
) ENGINE=MyISAM";

/*
 * Bảng lưu ID album để lấy random các album khi nghe một album
 * Nguyên tắc tương tự cách random bài hát
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_albums_random (
  album_id int(11) unsigned NOT NULL,
  cat_id int(11) unsigned NOT NULL,
  PRIMARY KEY id (album_id, cat_id)
) ENGINE=MyISAM";

// Bảng video
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos (
  video_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  video_code varchar(8) NOT NULL DEFAULT '' COMMENT 'Mã bài hát, dùng công khai',
  cat_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID phụ của thể loại có dạng id1,id2,...',
  singer_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng id1,id2,id3,...',
  author_ids varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của nhạc sĩ có dạng id1,id2,id3,...',
  song_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID bài hát liên quan',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover',
  uploader_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người đăng',
  uploader_name varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_comments int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  stat_shares int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  stat_downloads int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt tải',
  stat_hit int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Dữ liệu về độ HOT của bài hát',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  is_official tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Chính thức, 0: Thành viên đăng',
  show_inhome tinyint(1) NOT NULL DEFAULT '0',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  PRIMARY KEY (video_id),
  UNIQUE KEY video_code (video_code),
  KEY singer_ids (singer_ids),
  KEY author_ids (author_ids),
  KEY song_id (song_id),
  KEY cat_ids (cat_ids),
  KEY uploader_id (uploader_id),
  KEY is_official (is_official),
  KEY show_inhome (show_inhome),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_videos
    ADD " . $lang . "_video_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_video_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_video_searchkey varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_video_introtext text NOT NULL,
    ADD " . $lang . "_video_keywords text NOT NULL,
    ADD INDEX " . $lang . "_video_searchkey (" . $lang . "_video_searchkey)
";

// Bảng lưu đường dẫn video theo định dạng
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos_data (
  video_id int(11) unsigned NOT NULL,
  quality_id smallint(4) NOT NULL,
  resource_server_id smallint(4) NOT NULL DEFAULT '0' COMMENT '-1 là link ngoài, 0 là local, > 0 là fileserver',
  resource_path varchar(255) NOT NULL DEFAULT '',
  resource_duration int(11) NOT NULL DEFAULT '0' COMMENT 'Thời lượng',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  UNIQUE KEY id (video_id, quality_id),
  KEY status (status)
) ENGINE=MyISAM";

/*
 * Bảng lưu ID video để lấy random các video khi xem một video
 * Nguyên tắc tương tự cách random bài hát
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_videos_random (
  video_id int(11) unsigned NOT NULL,
  cat_id int(11) unsigned NOT NULL,
  PRIMARY KEY id (video_id, cat_id)
) ENGINE=MyISAM";

/*
 * Bảng lưu bảng xếp hạng bài hát, album, video chính thức
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_charts (
  chart_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  chart_week smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Tuần',
  chart_year smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Năm',
  chart_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp tại thứ 2 của tuần',
  cat_id smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thể loại ví dụ Việt Nam, Âu Mĩ, Hàn Quốc',
  object_name varchar(10) NOT NULL DEFAULT '' COMMENT 'Loại xếp hạng: song,album,video',
  object_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID đối tượng',
  view_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt nghe',
  view_rate double unsigned NOT NULL DEFAULT '0' COMMENT 'Trọng số điểm nghe',
  like_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt yêu thích',
  like_rate double NOT NULL DEFAULT '0' COMMENT 'Trọng số yêu thích',
  comment_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt bình luận',
  comment_rate double NOT NULL DEFAULT '0' COMMENT 'Trọng số bình luận',
  share_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  share_rate double NOT NULL DEFAULT '0' COMMENT 'Trọng số chia sẻ',
  summary_scores double NOT NULL DEFAULT '0' COMMENT 'Tổng số điểm quy đổi',
  summary_order smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Xếp hạng từ 1 đến 100',
  PRIMARY KEY chart_id (chart_id),
  KEY chart_week (chart_week),
  KEY chart_year (chart_year),
  KEY chart_time (chart_time),
  KEY cat_id (cat_id),
  KEY object_name (object_name),
  KEY object_id (object_id),
  KEY summary_scores (summary_scores),
  KEY summary_order (summary_order)
) ENGINE=MyISAM";

/*
 * Bảng lưu bảng xếp hạng bài hát, album, video lưu tạm trong một tuần
 * Sang tuần sau thì đưa vào bảng chính và xóa toàn bộ để bắt đầu một thống kê mới
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_chart_tmps (
  chart_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  chart_week smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Tuần',
  chart_year smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Năm',
  chart_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Unix timestamp tại thứ 2 của tuần',
  cat_id smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thể loại ví dụ Việt Nam, Âu Mĩ, Hàn Quốc',
  object_name varchar(10) NOT NULL DEFAULT '' COMMENT 'Loại xếp hạng: song,album,video',
  object_id int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID đối tượng',
  view_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt nghe',
  like_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt yêu thích',
  comment_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt bình luận',
  share_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  summary_scores double NOT NULL DEFAULT '0' COMMENT 'Tổng số điểm quy đổi',
  PRIMARY KEY chart_id (chart_id),
  KEY chart_week (chart_week),
  KEY chart_year (chart_year),
  KEY chart_time (chart_time),
  KEY cat_id (cat_id),
  KEY object_name (object_name),
  KEY object_id (object_id),
  KEY summary_scores (summary_scores)
) ENGINE=MyISAM";

// Các loại bảng xếp hạng âm nhạc ví dụ: Việt Nam, Âu Mĩ, Hàn Quốc
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_chart_categories (
  cat_id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  cat_code varchar(2) NOT NULL COMMENT 'Ký tự A-Z0-9. Độ dài 2 ký tự.',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover mặc định',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
  cat_ids text NOT NULL COMMENT 'ID các thể loại thuộc BXH, phân cách bởi dấu phảy',
  weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
  status smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (cat_id),
  UNIQUE KEY cat_code (cat_code),
  KEY status (status)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_chart_categories
    ADD " . $lang . "_cat_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_alias varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_absitetitle varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_abintrotext text NOT NULL,
    ADD " . $lang . "_cat_abkeywords text NOT NULL,
    ADD " . $lang . "_cat_abbodytext text NOT NULL,
    ADD " . $lang . "_cat_mvsitetitle varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_mvintrotext text NOT NULL,
    ADD " . $lang . "_cat_mvkeywords text NOT NULL,
    ADD " . $lang . "_cat_mvbodytext text NOT NULL,
    ADD " . $lang . "_cat_sositetitle varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_cat_sointrotext text NOT NULL,
    ADD " . $lang . "_cat_sokeywords text NOT NULL,
    ADD " . $lang . "_cat_sobodytext text NOT NULL
";

// Bảng cấu hình
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_config (
  config_name varchar(100) NOT NULL,
  config_value_default varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY config_name (config_name)
)ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_config ADD config_value_" . $lang . " varchar(255) NULL DEFAULT NULL";

$default_config = [];
$default_config['home_albums_display'] = '1';
$default_config['home_singers_display'] = '1';
$default_config['home_songs_display'] = '1';
$default_config['home_videos_display'] = '1';
$default_config['home_albums_weight'] = '1';
$default_config['home_singers_weight'] = '4';
$default_config['home_songs_weight'] = '3';
$default_config['home_videos_weight'] = '2';
$default_config['home_albums_nums'] = '12';
$default_config['home_singers_nums'] = '9';
$default_config['home_songs_nums'] = '24';
$default_config['home_videos_nums'] = '12';
$default_config['limit_singers_displayed'] = '3';
$default_config['various_artists'] = 'Various Artists';
$default_config['unknow_singer'] = 'Unknow Singer';
$default_config['arr_code_prefix_singer'] = 'at';
$default_config['arr_code_prefix_playlist'] = 'pl';
$default_config['arr_code_prefix_album'] = 'ab';
$default_config['arr_code_prefix_video'] = 'mv';
$default_config['arr_code_prefix_cat'] = 'gr';
$default_config['arr_code_prefix_song'] = 'so';
$default_config['arr_op_alias_prefix_song'] = '';
$default_config['arr_op_alias_prefix_album'] = 'album-';
$default_config['arr_op_alias_prefix_video'] = '';
$default_config['arr_op_alias_prefix_playlist'] = 'playlist-';
$default_config['gird_albums_percat_nums'] = '12';
$default_config['gird_albums_incat_nums'] = '24';
$default_config['gird_videos_percat_nums'] = '12';
$default_config['gird_videos_incat_nums'] = '24';
$default_config['view_singer_show_header'] = '1';
$default_config['view_singer_headtext_length'] = '150';
$default_config['arr_view_singer_tabs_alias_song'] = 'bai-hat';
$default_config['arr_view_singer_tabs_alias_album'] = 'album';
$default_config['arr_view_singer_tabs_alias_video'] = 'video';
$default_config['arr_view_singer_tabs_alias_profile'] = 'tieu-su';
$default_config['view_singer_main_num_songs'] = '10';
$default_config['view_singer_main_num_videos'] = '12';
$default_config['view_singer_main_num_albums'] = '12';
$default_config['view_singer_detail_num_songs'] = '30';
$default_config['view_singer_detail_num_videos'] = '24';
$default_config['view_singer_detail_num_albums'] = '24';
$default_config['arr_funcs_sitetitle_album'] = 'Album mới, album hot nhiều ca sỹ';
$default_config['arr_funcs_keywords_album'] = 'album, album moi, album hot';
$default_config['arr_funcs_description_album'] = 'Album mới , album hot tuyển chọn các ca sỹ Việt Nam và quốc tế.';
$default_config['arr_funcs_sitetitle_video'] = 'Video, MV nhạc chọn lọc';
$default_config['arr_funcs_keywords_video'] = 'video, mv, video nhac, mv nhac';
$default_config['arr_funcs_description_video'] = 'Video, MV mới nhất tuyển chọn MV chất lượng cao của các ca sỹ trong nước và quốc tế.';
$default_config['arr_funcs_sitetitle_singer'] = 'Nghệ sĩ, ca sĩ Việt Nam và quốc tế';
$default_config['arr_funcs_keywords_singer'] = 'ca sy, nghe sy, profile nghe sy';
$default_config['arr_funcs_description_singer'] = 'Nghệ sĩ, ca sĩ thông tin các nghệ sỹ, ca sỹ trong nước và quốc tế';
$default_config['fb_share_image'] = 'fb-share.jpg';
$default_config['fb_share_image_witdh'] = '1910';
$default_config['fb_share_image_height'] = '1000';
$default_config['fb_share_image_mime'] = 'image/jpeg';
$default_config['res_default_album_avatar'] = 'albums/album-art-cover.jpg';
$default_config['res_default_singer_avatar'] = 'artists/singer-art.jpg';
$default_config['res_default_author_avatar'] = 'artists/singer-art.jpg';
$default_config['res_default_video_avatar'] = 'videos/video-art-cover.jpg';

foreach ($default_config as $config_name => $config_value) {
    $sql_create_module[] = "INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_config (config_name, config_value_default) VALUES('" . $config_name . "', '" . $config_value . "')";
}

/*
 * Bảng thống kê lượt nghe: Đối tượng bài hát, album, video. Đối với mỗi đối tượng thống kê theo
 * - Toàn bộ => stat_type = all
 * - Theo năm stat_type = year, stat_val = 2019, 2018...
 * - Theo tháng stat_type = month, stat_val = 201901, 201902 => 201912
 * - Theo ngày stat_type = day, stat_val = 20190101, 20190101 => 20190131
 *
 * Nguyên tắc:
 * Kiểu thống kê toàn bộ được thêm vào ngay khi cài đặt sau đó dùng lệnh UPDATE để set tăng lên dần
 * Vào ngày đầu mỗi tháng (hoặc bất cứ lúc nào mà chưa có dữ liệu), insert toàn bộ các ngày trong
 * tháng, tháng (INSERT IGNORE), năm (INSERT IGNORE) đó vào CSDL rồi cũng UPDATE lên để giảm số câu lệnh chạy mỗi lần
 */
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_statistics (
  stat_obj varchar(20) NOT NULL COMMENT 'song|album|video',
  stat_type varchar(20) NOT NULL COMMENT 'year|month|day...',
  stat_val int(11) unsigned NOT NULL DEFAULT '0' COMMENT '2019|04|...',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  stat_count int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY id (stat_obj, stat_type, stat_val)
) ENGINE=MyISAM";

// Playlist của thành viên
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_playlists (
  playlist_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  playlist_code varchar(8) NOT NULL DEFAULT '' COMMENT 'Mã playlist, dùng công khai',
  resource_avatar varchar(255) NOT NULL COMMENT 'Avatar',
  resource_cover varchar(255) NOT NULL COMMENT 'Cover',
  userid int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'ID người tạo',
  stat_views int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  stat_likes int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  stat_comments int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
  stat_shares int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt chia sẻ',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật cuối',
  privacy smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Cá nhân, 1: Public',
  num_songs smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát trong playlist',
  PRIMARY KEY (playlist_id),
  UNIQUE KEY playlist_code (playlist_code),
  KEY userid (userid),
  KEY privacy (privacy)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_user_playlists
    ADD " . $lang . "_playlist_name varchar(250) NOT NULL DEFAULT '',
    ADD " . $lang . "_playlist_introtext text NOT NULL
";

// Bảng lưu bài hát trong playlist của thành viên
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_playlists_data (
  playlist_id int(11) unsigned NOT NULL,
  song_id int(11) unsigned NOT NULL,
  weight smallint(4) NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
  status smallint(4) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  UNIQUE KEY id (playlist_id, song_id),
  KEY status (status)
) ENGINE=MyISAM";

// Bảng các album yêu thích của thành viên
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_albums (
  userid int(11) unsigned NOT NULL COMMENT 'ID thành viên',
  album_id int(11) unsigned NOT NULL COMMENT 'ID album',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  is_removed tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Đã loại bỏ',
  time_removed int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian loại bỏ',
  PRIMARY KEY id (userid, album_id),
  KEY is_removed (is_removed)
) ENGINE=MyISAM";

// Bảng các video yêu thích của thành viên
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_videos (
  userid int(11) unsigned NOT NULL COMMENT 'ID thành viên',
  video_id int(11) unsigned NOT NULL COMMENT 'ID video',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  is_removed tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Đã loại bỏ',
  time_removed int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian loại bỏ',
  PRIMARY KEY id (userid, video_id),
  KEY is_removed (is_removed)
) ENGINE=MyISAM";

// Bảng các bài hát yêu thích của thành viên
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_user_favorite_songs (
  userid int(11) unsigned NOT NULL COMMENT 'ID thành viên',
  song_id int(11) unsigned NOT NULL COMMENT 'ID bài hát',
  time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  is_removed tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Đã loại bỏ',
  time_removed int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian loại bỏ',
  PRIMARY KEY id (userid, song_id),
  KEY is_removed (is_removed)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES('song', 'all', 0, " . NV_CURRENTTIME . ", 0)";
$sql_create_module[] = "INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES('album', 'all', 0, " . NV_CURRENTTIME . ", 0)";
$sql_create_module[] = "INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_statistics (stat_obj, stat_type, stat_val, time_update, stat_count) VALUES('video', 'all', 0, " . NV_CURRENTTIME . ", 0)";

// Bình luận
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '4')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'view_comm', '6')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '4')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'adminscomm', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sortcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'perpagecomm', '5')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timeoutcomm', '360')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowattachcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'alloweditorcomm', '0')";

// Copy dữ liệu vào các bảng cần fill
if (!empty($set_lang_data)) {
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_artists SET
        " . $lang . "_artist_alphabet = " . $set_lang_data . "_artist_alphabet,
        " . $lang . "_artist_searchkey = " . $set_lang_data . "_artist_searchkey
    ";
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_songs SET
        " . $lang . "_song_searchkey = " . $set_lang_data . "_song_searchkey
    ";
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_albums SET
        " . $lang . "_album_searchkey = " . $set_lang_data . "_album_searchkey
    ";
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_videos SET
        " . $lang . "_video_searchkey = " . $set_lang_data . "_video_searchkey
    ";
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_quality_song SET
        " . $lang . "_quality_alias = " . $set_lang_data . "_quality_alias
    ";
    $sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_quality_video SET
        " . $lang . "_quality_alias = " . $set_lang_data . "_quality_alias
    ";
}

// Thêm khóa UNIQUE vào các bảng, các khóa này chạy sau khi copy dữ liệu
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_song
    ADD UNIQUE " . $lang . "_quality_alias (" . $lang . "_quality_alias)
";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_quality_video
    ADD UNIQUE " . $lang . "_quality_alias (" . $lang . "_quality_alias)
";
