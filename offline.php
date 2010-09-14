<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * User offline
 *
 * @post $ticket
 *
 */

include_once('common.php');
if(empty($ticket)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $ticket';
}else{
	$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
	echo $im->offline();
}

