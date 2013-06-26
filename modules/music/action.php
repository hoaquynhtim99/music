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
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_video`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_playlist`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_singer`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_author`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ftp`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topsong`";

$sql_create_module = $sql_drop_module;

//1. bang bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ten` varchar(255) NOT NULL DEFAULT '',
  `tenthat` varchar(255) NOT NULL DEFAULT '',
  `casi` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `nhacsi` mediumint(8) unsigned NOT NULL DEFAULT '0', 
  `album` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `theloai` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listcat` varchar(255) NOT NULL DEFAULT '',
  `duongdan` varchar(255) NOT NULL DEFAULT '',
  `upboi` varchar(255) NOT NULL DEFAULT '',
  `numview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) NOT NULL DEFAULT '0',
  `bitrate` bigint(20) unsigned NOT NULL DEFAULT '0',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `duration` bigint(20) unsigned NOT NULL DEFAULT '0',
  `server` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  `binhchon` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hit` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//2. album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `tname` varchar(255) NOT NULL DEFAULT '',
  `casi` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `numview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `upboi` varchar(255) NOT NULL DEFAULT '',
  `describe` mediumtext NOT NULL,
  `active` smallint(2) NOT NULL DEFAULT '0',
  `numsong` int(11) NOT NULL DEFAULT '0',
  `listsong` mediumtext NOT NULL,
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `hit` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//3. The loai
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` mediumtext NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `numsong` int(11) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM";

//4. Album HOT
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` mediumint( 8 ) NOT NULL DEFAULT '0',
  `stt`mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//5. bon the loai tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//6. quang cao tren player
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `stt` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar( 255 ) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//7. binh luan bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song` (
  `id`mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  `what` varchar(255) NOT NULL DEFAULT '',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//8. binh luan album
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

//9. qua tang
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

//10. bao loi
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

//11. loi bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `songid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `active` smallint(2) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//12. the loai hien thi tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=MyISAM";

//13. album hien thi tren menu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_album` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(8) NOT NULL DEFAULT '0',
  `order` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `albumid` (`albumid`)
) ENGINE=MyISAM";

//15. playlist
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

//16. video
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `tname` varchar(255) NOT NULL DEFAULT '',
  `casi` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `nhacsi` mediumint(8) unsigned NOT NULL DEFAULT '0',
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

//17 the loai video
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

//18. binh luan album
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

//19 cau hinh module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL DEFAULT '',
  `value` bigint(20) NOT NULL DEFAULT '0',
  `char` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";

//20 ca si
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

//21 nhac si
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

//22 FTP
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

//22 FTP
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topsong` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `songid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dt` int(11) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `songid` (`songid`)
) ENGINE=MyISAM";

//1. them vao hot album
$i = 1;
while( $i <= 9 )
{
	$sql_create_module[] = "
	INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` 
	(
	`id` ,
	`albumid` ,
	`stt`
	)
	VALUES 
	(
	'" . $i . "',  '0',  '" . $i . "'
	)";
	$i++;
}

// them vao cau hinh
$sql_create_module[] = "INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (`id`, `key`, `value`, `char`) VALUES
(1, 'who_comment', 0, ''),
(2, 'auto_comment', 0, ''),
(3, 'root_contain', 0, 'data'),
(4, 'who_lyric', 0, ''),
(5, 'auto_lyric', 0, ''),
(6, 'who_gift', 0, ''),
(7, 'auto_gift', 0, ''),
(8, 'auto_album', 0, ''),
(9, 'who_download', 0, ''),
(10, 'upload_max', 2, ''),
(11, 'who_upload', 0, ''),
(12, 'auto_upload', 0, ''),
(13, 'default_server', 1, ''),
(14, 'playlist_max', 2, ''),
(15, 'del_cache_time_out', 21600, ''),
(16, 'version', 0, '3.3.01'),
(17, 'revision', 284, ''),
(18, 'num_blocktab', 10, ''),
(19, 'description', 0, 'The Professional Module Music For Nukeviet 3.x, Developed By Phan Tan Dung - phantandung92@gmail.com' ),
(20, 'type_main', 0, '')
";

?>