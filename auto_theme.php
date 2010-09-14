<?php
include_once('common.php');

$theme_colors = array (
	'black-tie' => '333333',
	'blitzer' => 'cc0000',
	'cupertino' => 'deedf7',
	'dark-hive' => '444444',
	'dot-luv' => '0b3e6f',
	'eggplant' => '30273a',
	'excite-bike' => 'f9f9f9',
	'flick' => 'dddddd',
	'hot-sneaks' => '35414f',
	'humanity' => 'cb842e',
	'le-frog' => '3a8104',
	'mint-choc' => '453326',
	'overcast' => 'dddddd',
	'pepper-grinder' => 'ffffff',
	'redmond' => '5c9ccc',
	'smoothness' => 'cccccc',
	'south-street' => 'ece8da',
	'start' => '2191c0',
	'sunny' => '817865',
	'swanky-purse' => '261803',
	'trontastic' => '9fda58',
	'ui-darkness' => '333333',
	'ui-lightness' => 'f6a828',
	'vader' => '888888',
);


if ( is_array($_G['style']['extstyle']) ){
	if (!empty($_G['cookie']['extstyle']) ) {
		$style = $_G['cookie']['extstyle'];
		foreach( $_G['style']['extstyle'] as $s ) {
			if ( $style == $s[0] ) {
				$color = substr($s[2], 1);
				break;
			}
		}
	}else {
		$color = substr($_G['style']['menubgcolor'], 1);
	}			
	$theme_colors_ar = array();
	foreach ( $theme_colors as $k => $v ){
		$theme_colors_ar[] = array($k, $v, color_distance($v, $color));
	}
	usort($theme_colors_ar, cmp_theme_color);
	$theme = $theme_colors_ar[0][0];
}


function cmp_theme_color($a, $b){
	return $a[2] > $b[2] ? 1 : ($a[2] > $b[2] ? -1 : 0);
}

function color_distance($a, $b){
	//http://en.wikipedia.org/wiki/Color_difference
	$s1 = pow(hexdec(substr($a, 0, 2)) - hexdec(substr($b, 0, 2)), 2);
	$s2 = pow(hexdec(substr($a, 2, 2)) - hexdec(substr($b, 2, 2)), 2);
	$s3 = pow(hexdec(substr($a, 4, 2)) - hexdec(substr($b, 4, 2)), 2);
	return sqrt($s1 + $s2 + $s3);
}
