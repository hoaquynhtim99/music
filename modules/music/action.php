<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright Freeware
 * @createdate 05/12/2010 09:47
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die('Stop!!!');

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
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hot_singer`";
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
  `ten` varchar(255) NOT NULL,
  `tenthat` varchar(255) NOT NULL,
  `casi` varchar(255) NOT NULL,
  `nhacsi` VARCHAR( 50 ) NOT NULL DEFAULT 'na', 
  `album` varchar(255) NOT NULL,
  `theloai` int(10) unsigned NOT NULL,
  `duongdan` varchar(255) NOT NULL,
  `upboi` varchar(255) NOT NULL,
  `numview` mediumint(8) unsigned NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `bitrate` int(50) NOT NULL,
  `size` int(50) unsigned NOT NULL DEFAULT '0',
  `duration` int(50) unsigned NOT NULL DEFAULT '0',
  `server` int(255) NOT NULL DEFAULT '0',
  `userid` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
  `dt` INT( 11 ) NOT NULL DEFAULT '0',
  `binhchon` INT( 11 ) NOT NULL DEFAULT '0',
  `hit` VARCHAR( 50 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//2. album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album` (
 `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tname` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `casi` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `numview` mediumint(8) unsigned NOT NULL,
  `upboi` varchar(255) NOT NULL,
  `describe` mediumtext NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `numsong` INT( 255 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//3. The loai
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//4. Album HOT
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`albumid` MEDIUMINT( 8 ) NOT NULL ,
`stt` INT( 20 ) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//5. bon the loai tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`cid` mediumint(8) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//6. quang cao tren player
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `stt` int(100) unsigned NOT NULL,
  `link` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//7. binh luan bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dt` int(11) NOT NULL DEFAULT '0',
  `what` varchar(255) NOT NULL,
  `userid` MEDIUMINT( 8 ) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//8. binh luan album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dt` int(11) NOT NULL DEFAULT '0',
  `what` varchar(255) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//9. qua tang
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `who_send` varchar(255) NOT NULL,
  `who_receive` varchar(255) NOT NULL,
  `songid` mediumint(8) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `body` text NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//10. bao loi
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error` (
 `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `where` varchar(10) NOT NULL,
  `key` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//11. loi bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `songid` mediumint(8) unsigned NOT NULL,
  `user` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `active` int(2) NOT NULL DEFAULT '0',
  `dt` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//12. the loai hien thi tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `cid` int(255) NOT NULL,
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//13. album hien thi tren menu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_album` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(8) NOT NULL,
  `order` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `albumid` (`albumid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//14. block ca si hot
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hot_singer` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `stt` int(5) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `large_thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//15. playlist
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_playlist` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `keyname` varchar(255) NOT NULL,
  `singer` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `songdata` varchar(255) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `view` int(255) unsigned NOT NULL DEFAULT '0',
  `active` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8" ;

//16. video
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `tname` varchar(50) NOT NULL,
  `casi` varchar(50) NOT NULL,
  `nhacsi` VARCHAR( 50 ) NOT NULL DEFAULT 'na',
  `theloai` int(100) NOT NULL,
  `duongdan` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `view` mediumint(8) NOT NULL,
  `active` int(2) NOT NULL,
  `dt` int(11) NOT NULL,
  `server` INT( 255 ) NOT NULL DEFAULT '0',
  `binhchon` INT( 11 ) NOT NULL DEFAULT '0',
  `hit` VARCHAR( 50 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//17 the loai video
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_video_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//18. binh luan album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_video` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dt` int(11) NOT NULL,
  `what` varchar(255) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `active` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//19 cau hinh module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` int(10) NOT NULL DEFAULT '0',
  `char` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10";

//20 ca si
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_singer` (
`id` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ten` VARCHAR( 50 ) NOT NULL ,
`tenthat` VARCHAR( 50 ) NOT NULL ,
`thumb` VARCHAR( 255 ) NOT NULL ,
`introduction` TEXT NOT NULL ,
`numsong` INT( 255 ) NOT NULL DEFAULT '0',
`numalbum` INT( 255 ) NOT NULL DEFAULT '0',
`numvideo` INT( 255 ) NOT NULL DEFAULT '0',
UNIQUE (`ten`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10";

//21 nhac si
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_author` (
`id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ten` VARCHAR( 50 ) NOT NULL ,
`tenthat` VARCHAR( 50 ) NOT NULL ,
`thumb` VARCHAR( 255 ) NOT NULL ,
`introduction` TEXT NOT NULL ,
`numsong` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
`numvideo` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
UNIQUE (`ten`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10";

//22 FTP
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ftp` (
 `id` INT( 50 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `host` VARCHAR( 30 ) NOT NULL ,
 `user` VARCHAR( 30 ) NOT NULL ,
 `pass` VARCHAR( 30 ) NOT NULL ,
 `fulladdress` VARCHAR( 255 ) NOT NULL ,
 `subpart` VARCHAR( 255 ) NOT NULL ,
 `ftppart` VARCHAR( 255 ) NOT NULL,
 `active` int(2) NOT NULL,
 UNIQUE (`host`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10";

//22 FTP
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topsong` (
 `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `songid` INT( 255 ) NOT NULL DEFAULT '0',
 `dt` INT( 11 ) NOT NULL DEFAULT '0',
 `hit` INT( 11 ) NOT NULL DEFAULT '0',
 UNIQUE (`songid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

//1. them vao hot album
$i = 1 ;
while ( $i <= 9 )
{
	$sql_create_module[] ="
	INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` 
	(
	`id` ,
	`albumid` ,
	`stt`
	)
	VALUES 
	(
	'".$i."',  '0',  '".$i."'
	)";
	$i ++ ;
}

// them vao 4 the loai
$i = 1 ;
while ( $i <= 4 )
{
	$sql_create_module[] ="
	INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category` 
	(
	`id` ,
	`cid`
	)
	VALUES 
	(
	'".$i."',  '0'
	)";
	$i ++ ;
}

// them vao ca si hot
$i = 1 ;
while ( $i <= 3 )
{
	$sql_create_module[] ="
	INSERT INTO  `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hot_singer` 
	(
	`id` ,
	`stt`,
	`fullname`,
	`name`,
	`thumb`,
	`large_thumb`
	)
	VALUES 
	(
	'".$i."',  '".$i."' , '', '' ,'' ,''
	)";
	$i ++ ;
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
(14, 'playlist_max', 2, '')";

?>