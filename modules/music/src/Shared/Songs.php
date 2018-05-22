<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <phantandung92@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

namespace NukeViet\Music\Shared;

use NukeViet\Music\Resources;
use NukeViet\Music\Shared\ITypeShare;

class Songs implements ITypeShare
{
    public static function creatUniqueCode()
    {
        $db = Resources::getDb();

        $code = 'ssdsdfsdf';
    }
}
