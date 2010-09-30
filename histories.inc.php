<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 22:14:34 CST 2010
 *
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once( dirname( __FILE__ ) . '/' . 'common.php' );

//$sl = $scriptlang['webim'];
$tl = $templatelang['webim'];
$notice = "";

if( $_G['gp_period'] ){

	switch ( $_G['gp_period'] ) {
	case 'weekago':
		$ago = 7*24*60*60;break;
	case 'monthago':
		$ago = 30*24*60*60;break;
	case '3monthago':
		$ago = 3*30*24*60*60;break;
	default:
		$ago = 0;
	}
	$ago = ( time() - $ago ) * 1000;
	$imdb->query( $imdb->prepare( "DELETE FROM $imdb->webim_histories WHERE `timestamp` < %s", $ago ) );
}

$count = $imdb->get_var( $imdb->prepare( "SELECT count(*) FROM $imdb->webim_histories" ) );

//showtips($tl['histories_tips']);
$t = $tl['histories_num'] ? $tl['histories_num'] : "The number of histories";
echo "<p>$t: $count</p>";
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=webim&pmod=histories');
showtableheader();

$clear_all = $tl['clear_all'] ? $tl['clear_all'] : "Clear up all histories";
$clear_weekago = $tl['clear_weekago'] ? $tl['clear_weekago'] : "Clear up the histories of a week ago";
$clear_monthago = $tl['clear_monthago'] ? $tl['clear_monthago'] : "Clear up the histories of a month ago";
$clear_3monthago = $tl['clear_3monthago'] ? $tl['clear_3monthago'] : "Clear up the histories of three months ago";

showsetting('', array( 'period', array( 
	array( "all", $clear_all ),
	array( "weekago", $clear_weekago ),
	array( "monthago", $clear_monthago ),
	array( "3monthago", $clear_3monthago ),
) ), "", 'select');
showsubmit('submit');
showtablefooter();
showformfooter();

?>

