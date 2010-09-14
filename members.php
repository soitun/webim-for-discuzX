<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Get room members
 *
 * @get $ticket
 * @get $id
 *
 */

include_once('common.php');

$id = g("id");
if(empty($ticket) || empty($id)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty get $ticket or $id';
}else{
	$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
	$re = $im->members($id);
	if($re){
		echo json_encode($re);
	}else{
		header("HTTP/1.0 404 Not Found");
		echo "Not Found";
	}
}
