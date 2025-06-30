<?php

/**
 * @Project NUKEVIET MUSIC 5.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016-2025 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thursday, May 1, 2025 7:59:37 PM GMT+07:00
 */

// Thư mục chứa tệp nhạc cần quét, hệ thống sẽ quét cả thư mục con trong đó
$music_dir = '/e/usb/NHAC/02.NHAC TRE';

// Thư mục chứa nhạc của website
$upload_dir = '/d/webroot/www/nukeviet50/music.nukeviet50.local/src/uploads/music/dataup_default123/2025_06';

// Giá trị tương đối của thư mục chứa nhạc.
$upload_base_dir = '/uploads/music/dataup_default123/2025_06';

// Thư mục chứa ảnh của bài hát
$cover_dir = '/d/webroot/www/nukeviet50/music.nukeviet50.local/src/uploads/music/songs/2025_06';

// Giá trị tương đối của thư mục chứa ảnh bài hát
$cover_base_dir = '/uploads/music/songs/2025_06';

// Thông số API của site
define('MUSIC_API_URL', 'https://music.nukeviet50.local/api.php');
define('MUSIC_API_KEY', '...');
define('MUSIC_API_SECRET', '...');
