<?php

/*
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Get buddies info
 *
 * @get $ids
 *
 */

include_once('common.php');

$ids = g("ids");
if(empty($ids)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty get $ids';
}else{
	$buddies = buddy($ids);
	complete_status($buddies);
	echo callback($buddies);
}
