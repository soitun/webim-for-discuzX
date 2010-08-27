<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Leave a room
 *
 * @post $ticket
 * @post $id
 *
 */

include_once('common.php');

$id = p("id");
if(empty($ticket) || empty($id)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $ticket or $id';
}else{
	require 'config.php';
	$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
	$re = $im->leave($id);
	if($re != "ok"){
		header("HTTP/1.0 404 Not Found");
	}
	echo $re;
}
