<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 29-03-2012 03:29
 */

if( ! defined( 'NV_IS_UPDATE' ) ) die( 'Stop!!!' );
 
$nv_update_config = array();

$nv_update_config['type'] = 1; // Kieu nang cap 1: Update; 2: Upgrade
$nv_update_config['packageID'] = 'NVUDMUSIC3402'; // ID goi cap nhat
$nv_update_config['formodule'] = "music"; // Cap nhat cho module nao, de trong neu la cap nhat NukeViet, ten thu muc module neu la cap nhat module

// Thong tin phien ban, tac gia, ho tro
$nv_update_config['release_date'] = 1363499027;
$nv_update_config['author'] = "Phan Tan Dung (phantandung92@gmail.com)";
$nv_update_config['support_website'] = "http://nukeviet.vn/phpbb/viewforum.php?f=118";
$nv_update_config['to_version'] = "3.4.02";
$nv_update_config['allow_old_version'] = array( "3.0.01", "3.1.00", "3.2.00", "3.3.00", "3.3.01", "3.4.01" );
$nv_update_config['update_auto_type'] = 1; // 0:Nang cap bang tay, 1:Nang cap tu dong, 2:Nang cap nua tu dong

$nv_update_config['lang'] = array();
$nv_update_config['lang']['vi'] = array();
$nv_update_config['lang']['en'] = array();

// Tiếng Việt
$nv_update_config['lang']['vi']['nv_up_author'] = 'Cập nhật nhạc sĩ cho bảng bài hát, video';
$nv_update_config['lang']['vi']['nv_up_singer'] = 'Cập nhật ca sĩ cho bảng bài hát, video, album';
$nv_update_config['lang']['vi']['nv_up_album'] = 'Cập nhật album cho bài hát';
$nv_update_config['lang']['vi']['nv_up_fomart'] = 'Cập nhật định dạng';
$nv_update_config['lang']['vi']['nv_up_statistics'] = 'Thêm trường để thống kê số lượng bài hát theo chủ đề bài hát, video';
$nv_update_config['lang']['vi']['nv_up_stasong'] = 'Thống kê số bài hát cho các chủ đề bài hát';
$nv_update_config['lang']['vi']['nv_up_stavideo'] = 'Thống kê số video cho các chủ đề video';
$nv_update_config['lang']['vi']['nv_up_albumhit'] = 'Thêm trường HIT vào album';
$nv_update_config['lang']['vi']['nv_up_maintype'] = 'Cập nhật cách hiển thị các album trên trang chủ';
$nv_update_config['lang']['vi']['nv_up_datatype'] = 'Cập nhật lại định dạng dữ liệu';
$nv_update_config['lang']['vi']['nv_up_nhaccuatui'] = 'Cập nhật FTP nhaccuatui.com';

$nv_update_config['lang']['vi']['nv_up_version'] = 'Cập nhật phiên bản';

// English
$nv_update_config['lang']['en']['nv_up_author'] = 'Update author for song, clip table';
$nv_update_config['lang']['en']['nv_up_singer'] = 'Update singer for song, clip, album table';
$nv_update_config['lang']['en']['nv_up_album'] = 'Update album for song';
$nv_update_config['lang']['en']['nv_up_fomart'] = 'Update format table';
$nv_update_config['lang']['en']['nv_up_statistics'] = 'Add to the statistics the number of songs on the theme song, video';
$nv_update_config['lang']['en']['nv_up_stasong'] = 'Statistics of the theme song for song';
$nv_update_config['lang']['en']['nv_up_stavideo'] = 'Statistics of video for video topics';
$nv_update_config['lang']['en']['nv_up_albumhit'] = 'Add to the album HIT';
$nv_update_config['lang']['en']['nv_up_maintype'] = 'Main view type';
$nv_update_config['lang']['en']['nv_up_datatype'] = 'Update data type';
$nv_update_config['lang']['en']['nv_up_nhaccuatui'] = 'Update FTP nhaccuatui.com';

$nv_update_config['lang']['en']['nv_up_version'] = 'Updated version';

// Require level: 0: Khong bat buoc hoan thanh; 1: Canh bao khi that bai; 2: Bat buoc hoan thanh neu khong se dung nang cap.
// r: Revision neu la nang cap site, phien ban neu la nang cap module

$nv_update_config['tasklist'] = array();
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_author', 'f' => 'nv_up_author' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_singer', 'f' => 'nv_up_singer' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_album', 'f' => 'nv_up_album' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_fomart', 'f' => 'nv_up_fomart' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_statistics', 'f' => 'nv_up_statistics' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_stasong', 'f' => 'nv_up_stasong' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_stavideo', 'f' => 'nv_up_stavideo' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_albumhit', 'f' => 'nv_up_albumhit' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.01', 'rq' => 2, 'l' => 'nv_up_maintype', 'f' => 'nv_up_maintype' );

$nv_update_config['tasklist'][] = array( 'r' => '3.4.02', 'rq' => 1, 'l' => 'nv_up_datatype', 'f' => 'nv_up_datatype' );
$nv_update_config['tasklist'][] = array( 'r' => '3.4.02', 'rq' => 1, 'l' => 'nv_up_nhaccuatui', 'f' => 'nv_up_nhaccuatui' );

$nv_update_config['tasklist'][] = array( 'r' => '3.4.02', 'rq' => 2, 'l' => 'nv_up_version', 'f' => 'nv_up_version' );

// Danh sach cac function
/*
Chuan hoa tra ve:
array(
	'status' =>
	'complete' => 
	'next' =>
	'link' =>
	'lang' =>
	'message' =>
);

status: Trang thai tien trinh dang chay
- 0: That bai
- 1: Thanh cong

complete: Trang thai hoan thanh tat ca tien trinh
- 0: Chua hoan thanh tien trinh nay
- 1: Da hoan thanh tien trinh nay

next:
- 0: Tiep tuc ham nay voi "link"
- 1: Chuyen sang ham tiep theo

link:
- NO
- Url to next loading

lang:
- ALL: Tat ca ngon ngu
- NO: Khong co ngon ngu loi
- LangKey: Ngon ngu bi loi vi,en,fr ...

message:
- Any message

Duoc ho tro boi bien $nv_update_baseurl de load lai nhieu lan mot function
Kieu cap nhat module duoc ho tro boi bien $old_module_version
*/

$array_lang_music_update = array();
// Lay danh sach ngon ngu
$result = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
while( list( $_tmp ) = $db->sql_fetchrow( $result ) )
{
	$array_lang_music_update[$_tmp] = array( "lang" => $_tmp, "mod" => array() );
	
	// Get all module of music
	$result1 = $db->sql_query( "SELECT `title`, `module_data` FROM `" . $db_config['prefix'] . "_" . $_tmp . "_modules` WHERE `module_file`='music'" );
	while( list( $_modt, $_modd ) = $db->sql_fetchrow( $result1 ) )
	{
		$array_lang_music_update[$_tmp]['mod'][] = array( "module_title" => $_modt, "module_data" => $_modd );
	}
}

function nv_up_author()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$array_author = array();
	
	// Lấy tất cả các nhạc sĩ
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_author";
			$sql = "SELECT * FROM `" . $table . "`";
			$check = $db->sql_query($sql);
			
			while( $row = $db->sql_fetchrow( $check ) )
			{
				$array_author[$lang][$module_info['module_data']][$row['ten']] = ( int ) $row['id'];
			}
		}
	}
	$db->sql_freeresult();

	// Cập nhật nhạc sĩ cho bảng bài hát, video
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "";

			$array_author[$lang][$module_info['module_data']]['na'] = 0;
			
			foreach( $array_author[$lang][$module_info['module_data']] as $alias => $id )
			{
				$db->sql_query( "UPDATE `" . $table . "` SET `nhacsi`='" . $id . "' WHERE `nhacsi`='" . $alias . "'" );
				$db->sql_query( "UPDATE `" . $table . "_video` SET `nhacsi`='" . $id . "' WHERE `nhacsi`='" . $alias . "'" );
			}
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_singer()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$array_singer = array();
	
	// Lấy tất cả các ca sĩ
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_singer";
			$sql = "SELECT * FROM `" . $table . "`";
			$check = $db->sql_query($sql);
			
			while( $row = $db->sql_fetchrow( $check ) )
			{
				$array_singer[$lang][$module_info['module_data']][$row['ten']] = ( int ) $row['id'];
			}
		}
	}
	$db->sql_freeresult();

	// Cập nhật ca sĩ cho bảng bài hát, video, album
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "";

			$array_singer[$lang][$module_info['module_data']]['ns'] = 0;
			
			foreach( $array_singer[$lang][$module_info['module_data']] as $alias => $id )
			{
				$db->sql_query( "UPDATE `" . $table . "` SET `casi`='" . $id . "' WHERE `casi`='" . $alias . "'" );
				$db->sql_query( "UPDATE `" . $table . "_video` SET `casi`='" . $id . "' WHERE `casi`='" . $alias . "'" );
				$db->sql_query( "UPDATE `" . $table . "_album` SET `casi`='" . $id . "' WHERE `casi`='" . $alias . "'" );
			}
		}
	}
	$db->sql_freeresult();

	return $return;
}

function nv_up_album()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	$array_album = array();
	// Lấy tất cả các album
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_album";
			$sql = "SELECT * FROM `" . $table . "`";
			$check = $db->sql_query($sql);
			
			while( $row = $db->sql_fetchrow( $check ) )
			{
				$array_album[$lang][$module_info['module_data']][$row['name']] = ( int ) $row['id'];
			}
		}
	}
	$db->sql_freeresult();

	// Cập nhật album cho bài hát
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "";

			$array_album[$lang][$module_info['module_data']][''] = 0;
			$array_album[$lang][$module_info['module_data']]['na'] = 0;
			
			foreach( $array_album[$lang][$module_info['module_data']] as $alias => $id )
			{
				$db->sql_query( "UPDATE `" . $table . "` SET `album`='" . $id . "' WHERE `album`='" . $alias . "'" );
			}
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_fomart()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Cập nhật định dạng
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "` CHANGE `casi` `casi` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "` CHANGE `nhacsi` `nhacsi` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "` CHANGE `album` `album` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_album` CHANGE `casi` `casi` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video` CHANGE `casi` `casi` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video` CHANGE `nhacsi` `nhacsi` MEDIUMINT(8) NOT NULL DEFAULT  '0'" );
			
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_album` DROP INDEX `name`" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_author` DROP INDEX `ten`" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_singer` DROP INDEX `ten`" );
		}
	}
	$db->sql_freeresult();

	return $return;
}

function nv_up_statistics()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Thêm trường để thống kê số lượng bài hát tho chủ đề bài hát, video
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_category` ADD `numsong` int(11) NOT NULL DEFAULT '0' AFTER `description`" );
			
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video_category` ADD `numvideo` int(11) NOT NULL DEFAULT '0' AFTER `description`" );
		}
	}
	$db->sql_freeresult();

	return $return;
}

function nv_up_stasong()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Thống kê số bài hát cho các chủ đề bài hát
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$sql = "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_category`";
			$check = $db->sql_query($sql);
			
			while( list( $id ) = $db->sql_fetchrow( $check ) )
			{
				$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_category` SET `numsong`=(SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "` WHERE `theloai`=" . $id . ") WHERE `id`=" . $id );
			}
		}
	}
	$db->sql_freeresult();

	return $return;
}

function nv_up_stavideo()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Thống kê số video cho các chủ đề video
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$sql = "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video_category`";
			$check = $db->sql_query($sql);
			
			while( list( $id ) = $db->sql_fetchrow( $check ) )
			{
				$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video_category` SET `numvideo`=(SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_video` WHERE `theloai`=" . $id . ") WHERE `id`=" . $id );
			}
		}
	}
	$db->sql_freeresult();

	return $return;
}

function nv_up_albumhit()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Thêm trường HIT vào album
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_album` ADD `hit` varchar(50) NOT NULL DEFAULT '' AFTER `addtime`" );
			
			$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_album` SET `hit`='0-" . NV_CURRENTTIME . "'" );
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_maintype()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			if( ! $db->sql_query( "REPLACE INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_setting` VALUES ( 20, 'type_main', '0', '' )" ) )
			{
				$return['status'] = 0;
				$return['complete'] = 0;
				return $return;
			}
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_datatype()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$TablePrefix = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'];
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_album` CHANGE `numsong` `numsong` INT(11) NOT NULL DEFAULT '0'" );
			
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_author` CHANGE `ten` `ten` VARCHAR(255) NOT NULL DEFAULT ''" );
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_author` CHANGE `tenthat` `tenthat` VARCHAR(255) NOT NULL DEFAULT ''" );
			
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_ftp` CHANGE `host` `host` VARCHAR(255) NOT NULL DEFAULT ''" );
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_ftp` CHANGE `user` `user` VARCHAR(255) NOT NULL DEFAULT ''" );
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_ftp` CHANGE `pass` `pass` VARCHAR(255) NOT NULL DEFAULT ''" );
			
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_playlist` CHANGE `view` `view` INT(11) UNSIGNED NOT NULL DEFAULT '0'" );
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_playlist` CHANGE `songdata` `songdata` MEDIUMTEXT NOT NULL" );
			
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_singer` CHANGE `ten` `ten` VARCHAR(255) NOT NULL DEFAULT ''" );
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_singer` CHANGE `tenthat` `tenthat` VARCHAR(255) NOT NULL DEFAULT ''" );
			
			$db->sql_query( "ALTER TABLE `" . $TablePrefix . "_video_category` CHANGE `title` `title` VARCHAR(255) NOT NULL DEFAULT ''" );
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_nhaccuatui()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$TablePrefix = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'];
			
			// Lay FTP nhaccuatui
			$sql = "SELECT * FROM `" . $TablePrefix . "_ftp` WHERE `host`='nhaccuatui'";
			$result = $db->sql_query( $sql );
			$ftp_nct = $db->sql_fetch_assoc( $result );
			
			// Cap nhat FTP nhaccuatui
			$db->sql_query( "UPDATE `" . $TablePrefix . "_ftp` SET `subpart`='subpart' WHERE `host`='nhaccuatui'" );
			
			if( ! empty( $ftp_nct ) )
			{
				$sql = "SELECT * FROM `" . $TablePrefix . "` WHERE `server`=" . $ftp_nct['id'];
				$result = $db->sql_query( $sql );
				while( $row = $db->sql_fetch_assoc( $result ) )
				{
					$row['duongdan'] = preg_replace( "/^\?M\=(.?)$/i", '/phan-tan-dung.${1}.html', $row['duongdan'] );
					$db->sql_query( "UPDATE `" . $TablePrefix . "` SET `duongdan`='" . $row['duongdan'] . "' WHERE `id`=" . $row['duongdan'] );
				}
			}
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_version()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_lang_music_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );

	// Cap nhat lai revision va version cua module
	foreach( $array_lang_music_update as $lang => $array_mod )
	{
		foreach( $array_mod['mod'] as $module_info )
		{
			$table = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_setting";
			$db->sql_query( "UPDATE `" . $table . "` SET `value`=352 WHERE `key`='revision'" );				
			$db->sql_query( "UPDATE `" . $table . "` SET `char`='3.4.02' WHERE `key`='version'" );				
		}
	}
	
	$mod_version = "3.4.02 1363499027";
	$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_setup_modules` SET `mod_version`='" . $mod_version . "', `author`='PHAN TAN DUNG (phantandung92@gmail.com)' WHERE `module_file`='music'" );
	
	nv_delete_all_cache();
	
	return $return;
}

?>