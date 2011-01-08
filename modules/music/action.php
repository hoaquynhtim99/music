<?php
if(!defined('NV_IS_FILE_MODULES'))
	die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hot_singer`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_album`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_category`";

$sql_create_module = $sql_drop_module;

// bang bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ten` varchar(255) NOT NULL,
  `tenthat` varchar(255) NOT NULL,
  `casi` varchar(255) NOT NULL,
  `casithat` varchar(255) NOT NULL,
  `album` varchar(255) NOT NULL,
  `theloai` int(10) unsigned NOT NULL,
  `duongdan` varchar(255) NOT NULL,
  `upboi` varchar(255) NOT NULL,
  `numview` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ten` (`ten`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tname` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `casi` varchar(255) NOT NULL,
  `casithat` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `numview` mediumint(8) unsigned NOT NULL,
  `upboi` varchar(255) NOT NULL,
  `describe` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// The loai
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// Album HOT
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album_hot` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`albumid` MEDIUMINT( 8 ) NOT NULL ,
`stt` INT( 20 ) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// 4 the loai tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_4category` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cid` MEDIUMINT( 8 ) NOT NULL DEFAULT  '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// quang cao tren player
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_ads` (
`id` INT( 100 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`stt` INT( 100 ) UNSIGNED NOT NULL ,
`link` VARCHAR( 255 ) NOT NULL,
`name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// binh luan bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_song` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `what` varchar(255) NOT NULL,
  `userid` MEDIUMINT( 8 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// binh luan album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `what` varchar(255) NOT NULL,
  `userid` MEDIUMINT( 8 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// qua tang
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_gift` (
`id` INT( 255 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`who_send` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`who_receive` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`songid` MEDIUMINT( 8 ) NOT NULL ,
`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// bao loi
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error` (
`id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// loi bai hat
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lyric` (
`id` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`songid` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
`user` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// the loai hien thi tren trang chu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_category` (
`id` INT( 255 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cid` INT( 255 ) NOT NULL ,
`order` INT( 255 ) NOT NULL ,
UNIQUE (
`cid`
)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// album hien thi tren menu
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_main_album` (
`id` INT( 255 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`albumid` MEDIUMINT( 8 ) NOT NULL ,
`order` INT( 255 ) NOT NULL ,
UNIQUE (
`albumid`
)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// block ca si hot
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hot_singer` (
`id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`stt` INT( 5 ) NOT NULL ,
`fullname` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`name` VARCHAR( 50 ) NOT NULL ,
`thumb` VARCHAR( 255 ) NOT NULL ,
`large_thumb` VARCHAR( 255 ) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

// them vao hot album
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


?>