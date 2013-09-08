<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2013 Freeware
 * @Createdate 08/09/2013
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

class nv_mod_music
{
	private $lang_data = '';
	private $mod_data = '';
	private $mod_name = '';
	private $mod_file = '';
	private $db = null;
	
	public $db_prefix = '';
	public $db_prefix_lang = "";
	public $table_prefix = "";
	
	public $setting = null;
	
	private $base_site_url = null;
	private $root_dir = null;
	private $upload_dir = null;
	
	private $language = array();

	public function __construct( $d = "", $n = "", $f = "", $lang = "" )
	{
		global $module_data, $module_name, $module_file, $db_config, $db, $lang_module;
		
		// Ten CSDL
		if( ! empty( $d ) ) $this->mod_data = $d;
		else $this->mod_data = $module_data;
		
		// Ten module
		if( ! empty( $n ) ) $this->mod_name = $n;
		else $this->mod_name = $module_name;
		
		// Ten thu muc
		if( ! empty( $f ) ) $this->mod_file = $f;
		else $this->mod_file = $module_file;
		
		// Ngon ngu
		if( ! empty( $lang ) ) $this->lang_data = $lang;
		else $this->lang_data = NV_LANG_DATA;
		
		$this->db_prefix = $db_config['prefix'];
		$this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
		$this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
		$this->db = $db;
		
		$this->setting = $this->get_setting();
		$this->setting['author_singer_defis'] = 'ft.'; // Tam thoi fix cung da
		
		$this->base_site_url = NV_BASE_SITEURL;
		$this->root_dir = NV_ROOTDIR;
		$this->upload_dir = NV_UPLOADS_DIR;
		$this->language = $lang_module;
	}
	
	private function handle_error( $messgae = '' )
	{
		trigger_error( "Error! " . ( $messgae ? ( string ) $messgae : "You are not allowed to access this feature now" ) . "!", 256 );
	}
	
	private function check_admin()
	{
		if( ! defined( 'NV_IS_MODADMIN' ) ) $this->handle_error();
	}
	
	private function db_cache( $sql, $id = '', $module_name = '' )
	{
		return nv_db_cache( $sql, $id, $module_name );
	}
	
	private function get_setting()
	{
		$setting = array();

		$sql = "SELECT * FROM `" . $this->table_prefix . "_setting`";
		$result = $this->db_cache( $sql, 'id' );

		if( ! empty( $result ) )
		{
			foreach( $result as $row )
			{
				if( in_array( $row['key'], array( "root_contain", "description" ) ) )
				{
					$setting[$row['key']] = $row['char'];
				}
				else
				{
					$setting[$row['key']] = $row['value'];
				}
			}
		}

		return $setting;
	}
	
	public function lang( $key )
	{
		return isset( $this->language[$key] ) ? $this->language[$key] : $key;
	}
	
	// Thay doi thu tu bai hat, ca si, video, album
	public function changeorder( $old, $new, $table )
	{
		$this->check_admin();
		return $this->db->sql_query( "UPDATE `" . $this->table_prefix . "_" . $table . "` SET `order` = " . $new . " WHERE `order` = " . $old );
	}
	
	// Cap nhat bai hat khi xoa, sua album
	public function updateSwhendelA( $id, $newid )
	{
		$this->check_admin();
		return $this->db->sql_query( "UPDATE `" . $this->table_prefix . "` SET `album`=" . $newid . " WHERE `album`=" . $id );
	}
	
	// Cap nhat bai hat, album, video khi xoa, sua ca si
	public function updatewhendelS( $id, $newid )
	{
		$this->check_admin();
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "` SET `casi`=" . $newid . " WHERE `casi`=" . $id );
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_album` SET `casi`=" . $newid . " WHERE `casi`=" . $id );
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_video` SET `casi`=" . $newid . " WHERE `casi`=" . $id );
	}
	
	// Cap nhat bai hat, album ,video khi xoa, sua nhac si
	public function updatewhendelA( $id, $newid )
	{
		$this->check_admin();
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "` SET `nhacsi`=" . $newid . " WHERE `nhacsi`=" . $id );
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_video` SET `nhacsi`=" . $newid . " WHERE `nhacsi`=" . $id );
	}
	
	// Lay nhac si tu id
	public function getauthorbyID( $id )
	{
		$this->check_admin();
		
		$author = array();
		$result = $this->db->sql_query( " SELECT * FROM " . $this->table_prefix . "_author WHERE id=" . $id );
		$author = $this->db->sql_fetchrow( $result );

		return $author;
	}
	
	// Cap nhat bai hat, video khi xoa, sua host nhac
	public function updatewhendelFTP( $server, $active )
	{
		$this->check_admin();

		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "` SET `active` = " . $active . " WHERE `server` = " . $server );
		$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_video` SET `active` = " . $active . " WHERE `server` = " . $server );
	}

	// Xuat duong dan nguoc lai
	public function admin_outputURL( $server, $inputurl )
	{
		$this->check_admin();
		
		$output = "";
		
		if( $server == 0 )
		{
			$output = $inputurl;
		}
		elseif( $server == 1 )
		{
			$output = $this->base_site_url . $this->upload_dir . "/" . $this->mod_name . "/" . $this->setting['root_contain'] . "/" . $inputurl;
		}
		else
		{
			$ftpdata = getFTP();
			foreach( $ftpdata as $id => $data )
			{
				if( $id == $server )
				{
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
		$sql = "SELECT * FROM `" . $this->table_prefix . "_ftp` ORDER BY `id` DESC";
		$result = $this->db_cache( $sql, 'id' );

		if( ! empty( $result ) )
		{
			foreach( $result as $row )
			{
				$ftpdata[$row['id']] = array(
					"id" => $row['id'],
					"host" => $row['host'],
					"user" => $row['user'],
					"pass" => $row['pass'],
					"fulladdress" => $row['fulladdress'],
					"subpart" => $row['subpart'],
					"ftppart" => $row['ftppart'],
					"active" => ( $row['active'] == 1 ) ? $this->lang('active_yes') : $this->lang('active_no')
				);
			}
		}
		return $ftpdata;
	}
	
	// Lay thong tin the loai
	public function get_category()
	{
		$category = array();

		$sql = "SELECT * FROM `" . $this->table_prefix . "_category` ORDER BY `weight` ASC";
		$result = $this->db_cache( $sql, 'id' );

		$category[0] = array(
			'id' => 0,
			'title' => $this->lang('unknow'),
			'keywords' => '',
			'description' => ''
		);
		
		if( ! empty( $result ) )
		{
			foreach( $result as $row )
			{
				$category[$row['id']] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'keywords' => $row['keywords'],
					'description' => $row['description']
				);
			}
		}
		
		return $category;
	}
	
	public function search_singer_id( $q, $limit = 0 )
	{
		// Gioi han khong qua lon
		$limit = $limit ? ( int ) $limit : 1000;
		$array = array();
		
		$sql = "SELECT `id` FROM `" . $this->table_prefix . "_singer` WHERE `tenthat` LIKE '%" . $this->db->dblikeescape( $q ) . "%' LIMIT 0," . $limit;
		$result = $this->db->sql_query( $sql );
		while( $row = $this->db->sql_fetch_assoc( $result ) )
		{
			$array[] = $row['id'];
		}
		
		return $array;
	}
	
	public function string2array( $string, $split_char = ',' )
	{
		return array_filter( array_unique( array_map( "trim", explode( ",", $string ) ) ) );
	}
	
	// Lay ca si tu id
	public function getsingerbyID( $id )
	{
		$singer = array();
		
		if( is_array( $id ) )
		{
			$result = $this->db->sql_query( " SELECT * FROM `" . $this->table_prefix . "_singer` WHERE `id` IN(" . implode( ",", $id ) . ")" );
			
			while( $row = $this->db->sql_fetch_assoc( $result ) )
			{
				$singer[$row['id']] = $row;
			}
		}
		else
		{
			$result = $this->db->sql_query( " SELECT * FROM `" . $this->table_prefix . "_singer` WHERE `id`=" . $id );
			$singer = $this->db->sql_fetch_assoc( $result );
		}

		return $singer;
	}
	
	public function build_author_singer_2string( $array, $string )
	{
		$id = $this->string2array( $string );
		
		$return = array();
		
		if( ! empty( $id ) )
		{
			foreach( $id as $_tmp )
			{
				if( isset( $array[$_tmp] ) )
				{
					$return[] = $array[$_tmp]['tenthat'];
				}
			}
		}
		
		if( empty( $return ) )
		{
			return $this->lang('unknow');
		}
		else
		{
			return implode( " " . $this->setting['author_singer_defis'] . " ", $return );
		}
	}
}

?>