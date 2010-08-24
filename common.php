<?php

require '../../class/class_core.php';
//require '../../function/function_home.php';
$discuz = & discuz_core::instance();
$discuz->init();
if(!defined('IN_DISCUZ') || !$_G['uid']) {
	exit('Access Denied');
}

/**
 * Init im user.
 * 	-id:
 * 	-nick:
 * 	-pic_url:
 * 	-show:
 *
 */
$user->id = $_G['uid'];
$user->nick = $_G['username'];
$user->pic_url = avatar($user->id, 'small', true);
$user->show = $_G['gp_show'] ? $_G['gp_show'] : "available";
complete_status(array($user));

/*
function member_status($uid){
	$data = DB::fetch_first("SELECT spacenote FROM ".DB::table('common_member_field_home')." WHERE uid='$uid'");
	return $data ? $data['spacenote'] : "";
}
 */

/**
 * Add status to member info.
 *
 * @param array $members the member list
 * @return 
 *
 */
function complete_status($members){
	if(!empty($members)){
		$num = count($members);
		$ids = array();
		$ob = array();
		for($i = 0; $i < $num; $i++){
			$m = $members[$i];
			$id = $m->id;
			$ids[] = $id;
			$ob[$id] = $m;
		}
		$ids = implode(",", $ids);
		$query = DB::query("SELECT uid, spacenote FROM ".DB::table('common_member_field_home')." WHERE uid IN ('$ids')");
		while($res = DB::fetch($query)) {
			$ob[$res['uid']]->status = $res['spacenote'];
		}
	}
	return $members;
}
