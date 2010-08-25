<?php

/**
 * Author: Hidden
 * Date: Tue Aug 24 17:35:20 CST 2010
 *
 * Save user setting
 *
 * @post $data
 *
 */
include_once('common.php');
$data = p('data');
if(empty($data)){
	header("HTTP/1.0 400 Bad Request");
	echo 'Empty post $data';
}else{
	DB::update('webim_settings', array('web' => $data), array('uid' => $user->uid));
	echo 'ok';
}

