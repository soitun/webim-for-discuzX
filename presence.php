<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Send user presence
 *
 * @post $ticket
 * @post $show
 * @post $status
 *
 */

include_once('common.php');

$show = p("show");
$status = p("status");
if(empty($ticket) || empty($show)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $ticket or $show';
}else{
	$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
	$re = $im->presence($show, $status);
	if($re != "ok"){
		header("HTTP/1.0 404 Not Found");
	}
	echo $re;
}
