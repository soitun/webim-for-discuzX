<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * User online
 *
 * @post $show
 * @post $buddy_ids
 * @post $room_ids
 *
 * Extend:
 *
 * @post $stranger_ids
 *
 */

include_once('common.php');
require 'config.php';

$im = new WebIM($user, null, $_IMC['domain'], $_IMC['apikey'], $_IMC['host'], $_IMC['port']);
$im_buddies = array();//For online.
$im_rooms = array();//For online.


$cache_buddies = array();//For find.
$cache_rooms = array();//For find.

$active_buddies = ids_array(gp('buddy_ids'));
$active_rooms = ids_array(gp('room_ids'));

$new_messages = new_message();
$online_buddies = online_buddy();
$rooms = room();
$setting = setting();
$blocked_rooms = $setting && is_array($setting->blocked_rooms) ? $setting->blocked_rooms : array();

$buddies_with_info = array();//Buddy with info.

//Active buddy who send a new message.
$count = count($new_messages);
for($i = 0; $i < $count; $i++){
	$active_buddies[] = $new_messages[$i]->from;
}

//Find im_buddies
foreach($online_buddies as $k => $v){
	$id = $v->id;
	$im_buddies[] = $id;
	$buddies_with_info[] = $id;
	$cache_buddies[$id] = $v;
}

//Get active buddies info.
$buddies_without_info = array();
foreach($active_buddies as $k => $v){
	if(!in_array($v, $buddies_with_info)){
		$buddies_without_info[] = $v;
	}
}
if(!empty($buddies_without_info)){
	foreach(buddy(implode(",", $buddies_without_info)) as $k => $v){
		$id = $v->id;
		$im_buddies[] = $id;
		$cache_buddies[$id] = $v;
	}
}

//Find im_rooms except blocked.
foreach($rooms as $k => $v){
	$id = $v->id;
	if(in_array($id, $blocked_rooms)){
		$v->blocked = true;
	}else{
		$v->blocked = false;
		$im_rooms[] = $id;
	}
	$cache_rooms[$id] = $v;
}

//===============Online===============

$data = $im->online(implode(",", $im_buddies), implode(",", $im_rooms));

if($data->success){
	$data->new_messages = $new_messages;
	$show_buddies = array();//For output.

	//Add room online info.
	foreach($data->rooms as $k => $v){
		$id = $v->id;
		$cache_rooms[$id]->count = $v->count;
	}
	//Show all rooms.
	$data->rooms = $rooms;

}else{
	header("HTTP/1.0 404 Not Found");
	echo json_encode($data->error_msg);
}

var_export($data);
//print_r($new_messages);
print_r($active_buddies);
print_r($active_rooms);
print_r($im_buddies);
print_r($im_rooms);
//print_r($rooms);
//print_r($online_buddies);
//print_r($rooms);

//print_r(history("unicast", "webim"));
//print_r(history("multicast", "36"));
//print_r(new_message());
//new_message_to_histroy();


