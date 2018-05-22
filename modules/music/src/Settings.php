<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music;

class Settings
{
    /**
     * Loại
     *
     */
    const TYPE_ALBUM = 'album';
    const TYPE_SONG = 'song';
    const TYPE_VIDEO = 'video';
    const TYPE_ARTIST = 'artist';
    const TYPE_CATEGORY = 'cat';

    /**
     * Các thiết lập về bài hát
     *
     */
    const SONG_CODE_LENGTH = 5;

    /**
     * Các thiết lập về bài hát
     *
     */
    const CATEGORY_CODE_LENGTH = 4;

    /**
     * Các thiết lập về nghệ sĩ
     *
     */
    const ARTIST_CODE_LENGTH = 5;

    /**
     * Các bảng dữ liệu
     *
     */
    const TABLE_SEPARATOR_CHARACTER = '_';
    const TABLE_ARTIST = 'artists';
    const TABLE_CATEGORY = 'categories';
}
