<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Join a room
 *
 * @post $ticket
 * @post $id
 *
 */

include_once('common.php');

$ticket = p("ticket");
$id = p("id");
if(empty($ticket) || empty($id)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $ticket or $id';
}else{
	$room = room($id)[0];
	if($room){
		require 'config.php';
		$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
		$re = $im->join($id);
		if($re){
			$room->count = $re->count;
			echo json_encode($room);
		}else{
			header("HTTP/1.0 404 Not Found");
			echo "Con't join this room right now";
		}
	}else{
		header("HTTP/1.0 404 Not Found");
		echo "Con't found this room";
	}
}
