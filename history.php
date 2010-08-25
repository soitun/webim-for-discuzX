<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Get history message
 *
 * @get $type
 * @get $id
 *
 */

include_once('common.php');

$id = g("id");
$type = g("type");
if(empty($id) || empty($type)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty get $id or $type';
}else{
	echo json_encode(history($type, $id));
}
