<?php

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE ;

// lay thong tin the loai
function get_category()
{
	global $module_data, $db ;
	$category = array() ;
	$result = mysql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category " );
	while($rs = $db->sql_fetchrow($result))
	{
		$category[ $rs['id'] ] = $rs[ 'title' ] ;
	}
	return $category ;
}

?>