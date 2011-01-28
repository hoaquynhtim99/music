<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 05/12/2010 09:47
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

function m_emotions_array() 
{
	return array(
		6 => '>:D<',		18 => '#:-S',				36 => '<:-P',		42 => ':-SS',
		48 => '<):)',		50 => '3:-O',				51 => ':(|)',		53 => '@};-',
		55 => '**==',		56 => '(~~)',				58 => '*-:)',		63 => '[-O<',
		67 => ':)>-',		77 => '^:)^',				106 => ':-??',		25 => 'O:)',
		26 => ':-B',		28 => 'I-)',				29 => '8-|',		30 => 'L-)',
		31 => ':-&',		32 => ':-$',				33 => '[-(',		34 => ':O)',
		35 => '8-}',		7 => ':-/',					37 => '(:|',		38 => '=P~',
		39 => ':-?',		40 => '#-O',				41 => '=D>',		9 => ':">',
		43 => '@-)',		44 => ':^O',				45 => ':-W',		46 => ':-<',
		47 => '>:P',		11 => array(':*',':-*'),	49 => ':@)',		12 => '=((',
		13 => ':-O',		52 => '~:>',				16 => 'B-)',		54 => '%%-',
		17 => ':-S',		5 => ';;)',					57 => '~O)',		19 => '>:)',
		59 => '8-X',		60 => '=:)',				61 => '>-)',		62 => ':-L',
		20 => ':((',		64 => '$-)',				65 => ':-"',		66 => 'B-(',
		21 => ':))',		68 => '[-X',				69 => '\:D/',		70 => '>:/',
		71 => ';))',		72 => 'O->',				73 => 'O=>',		74 => 'O-+',
		75 => '(%)',		76 => ':-@',				23 => '/:)',		78 => ':-J',
		79 => '(*)',		100 => ':)]',				101 => ':-C',		102 => '~X(',
		103 => ':-H',		104 => ':-T',				105 => '8->',		24 => '=))',
		107 => '%-(',		108 => ':O3',				1 => array(':)',':-)'),		2 => array(':(',':-('),
		3 => array(';)',';-)'),		22 => array(':|',':-|'),		14 => array('X(','X-('),		15 => array(':>',':->'),
		8 => array(':X',':-X'),		4 => array(':D',':-D'),		27 => '=;',		10 => array(':P',':-P'),
	);
}
function show_emotions( )
{
	global $module_name;
	$emotions = m_emotions_array();
	$data = '
	<table width="100%" border="0"><tr>';
	$i = 0;
	foreach ( $emotions as $name => $value ) 
	{
		$i ++;
		if ( is_array( $value ) ) $value = $value[0];

		$data .= '
		<td style="float:left;width:30px">
		<a href="javascript:void(0);" title=\'' . htmlspecialchars_decode( $value ) . '\'>
		<img src="' . NV_BASE_SITEURL . 'modules/' . $module_name . '/class/emoticons/' . $name . '.gif" boder="0"/>
		</a>
		</td>';
		if ($i % 9 == 0)
			$data .= '</tr>';
	}
	if ($i % 9 !== 0) 
	{
		$data .= '</tr>';
	}
	$data .= '</table>';
	return $data;
}
function m_emotions_replace( $data )
{
	global $module_name;
	$emotions = m_emotions_array();
	foreach ( $emotions as $a => $b ) 
	{
		$x = array();
		if ( is_array( $b ) ) 
		{
			for ( $i=0; $i < count( $b ); $i++ ) 
			{
				$b[$i] = m_htmlchars( $b[$i] );
				$x[] = $b[$i];
				$v = strtolower( $b[$i] );
				if ( $v != $b[$i] ) $x[] = $v;
			}
		}
		else 
		{
			$b = m_htmlchars( $b );
			$x[] = $b;
			$v = strtolower( $b );
			if ( $v != $b ) $x[] = $v;
		}
		$p = '';
		for ( $u=0; $u < strlen( $x[0] ); $u++ ) 
		{
			$ord = ord( $x[0][$u] );
			if ( $ord < 65 && $ord > 90 ) $p .= '&#'.$ord.';';
			else $p .= $x[0][$u];
		}

		$data = str_replace( $x, "<img style=\"float:left;\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_name . "/class/emoticons/" . $a . ".gif\" />", $data );
	}
	return $data;
}
function m_htmlchars($str) {
	return str_replace(
		array('&', '<', '>', '"', chr(92), chr(39)),
		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),
		$str
	);
}
?>