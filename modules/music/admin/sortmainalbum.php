<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (c) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Aug 6, 2010 4:12:19 PM
 */

$new = $nv_Request->get_int('new', 'post', 0);
$old = $nv_Request->get_int('old', 'post', 0);

if($old != $new)
{
	changeorder($old, 0, 'main_album');

	if($old < $new)
	{
		for($i = $old; $i < $new; $i++)
		{
			changeorder($i + 1, $i, 'main_album');
		}
	}
	else
	{
		for($i = $old; $i > $new; $i--)
		{
			changeorder($i - 1, $i, 'main_album');
		}
	}
	changeorder(0, $new, 'main_album');
}

echo $lang_module['update_success'];
?>