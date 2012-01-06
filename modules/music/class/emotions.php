<?php

/* *
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 10:12 AM
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

function m_emotions_replace( $data )
{
	global $module_name,  $module_file, $module_info;
	
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

		$data = str_replace( $x, "<img style=\"vertical-align:middle\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/emoticons/yahoo/" . $a . ".gif\" />", $data );
	}
	return $data;
}

function m_htmlchars( $str ) 
{
	return str_replace(
		array('&', '<', '>', '"', chr(92), chr(39)),
		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),
		$str
	);
}

?>