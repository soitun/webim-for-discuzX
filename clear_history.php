<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Clear history message
 *
 * @post $id
 *
 */

include_once('common.php');

$id = gp("id");
if(empty($id)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $id';
}else{
	DB::update("webim_histories", array("fromdel" => 1, "type" => "unicast"), array("from" => $user->id, "to" => $id));
	DB::update("webim_histories", array("todel" => 1, "type" => "unicast"), array("to" => $user->id, "from" => $id));
	DB::delete("webim_histories", array("todel" => 1, "fromdel" => 1));
	echo "ok";
}
