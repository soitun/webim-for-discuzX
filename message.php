<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Send message to other.
 *
 * @post $ticket
 * @post $type
 * @post $offline
 * @post $to
 * @post $body
 * @post $style
 *
 */

include_once('common.php');
$type = gp("type");
$offline = gp("offline");
$to = gp("to");
$body = gp("body");
$style = gp("style");
if(empty($ticket) || empty($type) || empty($to) || empty($body)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $ticket or $type or $to or $body';
}else{
	$send = $offline == "true" || $offline == "1" ? 0 : 1;
	DB::insert("webim_histories", array(
		"send" => $send,
		"type" => $type,
		"to" => $to,
		"from" => $user->id,
		"nick" => $user->nick,
		"body" => $body,
		"style" => $style,
		"timestamp" => microtime(true)*1000,
	));
	if($send == 1){
		$im = new WebIM($user, $ticket, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
		$im->message($type, $to, $body, $style);
	}
	echo callback( "ok" );
}
