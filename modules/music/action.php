<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright @2011 Freeware
 * @createdate 05/12/2010 09:47
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_video`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_playlist`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting_home`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_singer`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_author`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ftp`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topsong`";

$sql_create_module = $sql_drop_module;

// Bang bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ten` varchar(255) NOT NULL DEFAULT '' COMMENT 'Alias bài hát',
  `tenthat` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên đầy đủ bài hát',
  `casi` varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của ca sĩ có dạng 0,id1,id2,id3,...,0',
  `nhacsi` varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách ID của nhạc sĩ có dạng 0,id1,id2,id3,...,0',
  `album` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID album của bài hát',
  `theloai` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID thể loại chính của bài hát',
  `listcat` varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách ID phụ của thể loại có dạng id1,id2,...',
  `duongdan` varchar(255) NOT NULL DEFAULT '' COMMENT 'Link đến file nhạc',
  `upboi` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người đăng bài hát nếu là bài hát do khách upload',
  `numview` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt xem',
  `active` smallint(2) NOT NULL DEFAULT '0' COMMENT '0: Đang tạm dừng, 1: Đang hoạt động',
  `bitrate` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bitrate của bài hát',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Kích thước file nhạc',
  `duration` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian file nhạc',
  `server` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID server chứa nhạc',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID thành viên đăng bài hát hày',
  `dt` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  `binhchon` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt like',
  `hit` varchar(50) NOT NULL DEFAULT '' COMMENT 'Dữ liệu về độ HOT của bài hát',
  `is_official` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Chính thức, 0: Thành viên đăng',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Alias của album',
  `tname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên đầy đủ của album',
  `casi` varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách ca sĩ của album có dạng 0,id1,id2,...,0',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'Đường dẫn ảnh',
  `numview` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số lượt nghe',
  `upboi` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người tạo album',
  `describe` mediumtext NOT NULL COMMENT 'Mô tả album',
  `active` smallint(2) NOT NULL DEFAULT '0' COMMENT '0: Ngừng hoạt động, 1: Hoạt động',
  `numsong` int(11) NOT NULL DEFAULT '0' COMMENT 'Số bài hát',
  `listsong` mediumtext NOT NULL COMMENT 'Danh sách id bài hát có dạng id1,id2,id3...',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian tạo',
  `hit` varchar(50) NOT NULL DEFAULT '' COMMENT 'Thông tin HIT của album',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// The loai
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên thể loại',
  `keywords` mediumtext NOT NULL COMMENT 'Từ khóa của thể loại',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả thể loại',
  `numsong` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bài hát',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM";

// Home Setting
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting_home` (
  `object_type` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Loại: 0 - Album, 1 - Videoclip',
  `object_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT 'ID album hoặc video',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'Thứ tự',
  UNIQUE KEY `object` (`object_type`, `object_id`)
) ENGINE=MyISAM";

// Quang cao tren player
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `stt` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'Số thứ tự',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'Đường dẫn quảng cáo',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Tiêu đề quảng cáo',
  `url` varchar( 255 ) NOT NULL DEFAULT '' COMMENT 'Đường dẫn file ảnh, flash',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Binh luan bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên người bình luận',
  `body` text NOT NULL COMMENT 'Nội dung bình luận',
  `dt` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bình luận',
  `what` varchar(255) NOT NULL DEFAULT '' COMMENT 'ID bài hát bình chọn',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'ID thành viên bình luận',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Binh luan album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album` (
  `id`mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  `what` varchar(255) NOT NULL DEFAULT '',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Qua tang
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift` (
  `id`mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `who_send` varchar(255) NOT NULL DEFAULT '',
  `who_receive` varchar(255) NOT NULL DEFAULT '',
  `songid` mediumint(8) NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `body` text NOT NULL,
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Bao loi
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `where` varchar(10) NOT NULL DEFAULT '',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `status` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid`(`userid`)
) ENGINE=MyISAM";

// Loi bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `songid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Playlist
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_playlist` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `keyname` varchar(255) NOT NULL DEFAULT '',
  `singer` varchar(255) NOT NULL DEFAULT 'ns',
  `message` mediumtext NOT NULL,
  `songdata` mediumtext NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `view` int(11) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Videoclip
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `tname` varchar(255) NOT NULL DEFAULT '',
  `casi` varchar(255) NOT NULL DEFAULT '',
  `nhacsi` varchar(255) NOT NULL DEFAULT '',
  `theloai` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listcat` varchar(255) NOT NULL DEFAULT '',
  `duongdan` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `view` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  `server` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `binhchon` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hit` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// The loai video
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` mediumtext NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `numvideo` int(11) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM";

// Binh luan album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_video` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  `what` varchar(255) NOT NULL DEFAULT '',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Ca si
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_singer` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ten` varchar(100) NOT NULL DEFAULT '',
  `tenthat` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `introduction` text NOT NULL,
  `numsong` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `numalbum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `numvideo` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// Nhac si
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_author` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ten` varchar(255) NOT NULL DEFAULT '',
  `tenthat` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `introduction` text NOT NULL ,
  `numsong` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `numvideo` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

// FTP
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ftp` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(30) NOT NULL DEFAULT '',
  `user` varchar(30) NOT NULL DEFAULT '',
  `pass` varchar(30) NOT NULL DEFAULT '',
  `fulladdress` varchar(255) NOT NULL DEFAULT '',
  `subpart` varchar(255) NOT NULL DEFAULT '',
  `ftppart` varchar(255) NOT NULL DEFAULT '',
  `active` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `host` (`host`)
) ENGINE=MyISAM";

// Cau hinh FTP mac dinh
$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ftp` 
(`id`, `host`, `user`, `pass`, `fulladdress`, `subpart`, `ftppart`, `active`) VALUES
(2, 'nhaccuatui', 'hoaquynhtim99', 'hoaquynhtim99', 'http://www.nhaccuatui.com/', 'bai-hat', '/', 1),
(3, 'zing', 'hoaquynhtim99', 'hoaquynhtim99', 'http://mp3.zing.vn/', 'bai-hat', '/', 1),
(4, 'nhacvui', 'hoaquynhtim99', 'hoaquynhtim99', 'http://hcm.nhac.vui.vn', '/', '/', 1),
(5, 'nhacso', 'hoaquynhtim99', 'hoaquynhtim99', 'http://nhacso.net/', 'nghe-nhac', '/', 1),
(6, 'zingclip', 'hoaquynhtim99', 'hoaquynhtim99', 'http://mp3.zing.vn/video-clip', '/', '/', 1),
(7, 'nctclip', 'hoaquynhtim99', 'hoaquynhtim99', 'http://www.nhaccuatui.com/video', '/', '/', 1)";

// Bang xep hang
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topsong` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `songid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `songid` (`songid`)
) ENGINE=MyISAM";

// Cau hinh module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (
  `key` varchar(60) NOT NULL DEFAULT '' COMMENT 'Khóa cấu hình',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Giá trị được thiết lập',
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM";

// Them vao hot album
$i = 1;
while( $i <= 9 )
{
	$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` (`id`,`albumid`,`stt`) VALUES ('" . $i . "', '0', '" . $i . "')";
	$i ++;
}

// Them vao cau hinh
$sql_create_module[] = "INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (`key`, `value`) VALUES
('who_comment', '0'),
('auto_comment', '0'),
('root_contain', 'data'),
('who_lyric', '0'),
('auto_lyric', '0'),
('who_gift', '0'),
('auto_gift', '0'),
('auto_album', '0'),
('who_download', '0'),
('upload_max', '2'),
('who_upload', '0'),
('auto_upload', '0'),
('default_server', '1'),
('playlist_max', '2'),
('del_cache_time_out', '21600'),
('num_blocktab', '10'),
('description', 'The Professional Module Music For Nukeviet 3.x, Developed By Phan Tan Dung - phantandung92@gmail.com' ),
('type_main', '0'),
('author_singer_defis', 'ft.'),
('alias_listen_song', 'bai-hat'),
('alias_view_album', 'album'),
('alias_view_videoclip', 'video-clip')
";

?>