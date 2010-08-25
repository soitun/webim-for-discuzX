<?php

require 'lib/webim.class.php';
require '../../class/class_core.php';
require '../../function/function_friend.php';
require '../../function/function_group.php';
$discuz = & discuz_core::instance();
$discuz->init();
if(!defined('IN_DISCUZ') || !$_G['uid']) {
	exit('Access Denied');
}
//Cache friend_groups;
$friend_groups = friend_group_list();

/**
 * Init im user.
 * 	-uid:
 * 	-id:
 * 	-nick:
 * 	-pic_url:
 * 	-show:
 *
 */
$user->uid = $_G['uid'];
$user->id = to_utf8($_G['username']);
$user->nick = to_utf8($_G['username']);
$user->pic_url = avatar($user->id, 'small', true);
$user->show = gp('show') ? gp('show') : "available";
$user->url = "home.php?mod=space&uid=".$user->uid;
//complete_status(array($user));


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
			$id = $m->uid;
			$ids[] = $id;
			$ob[$id] = $m;
		}
		$ids = implode(",", $ids);
		$query = DB::query("SELECT uid, spacenote FROM ".DB::table('common_member_field_home')." WHERE uid IN ($ids)");
		while($res = DB::fetch($query)) {
			$ob[$res['uid']]->status = to_utf8($res['spacenote']);
		}
	}
	return $members;
}

/**
 * Online buddy list.
 *
 */
function online_buddy(){
	global $friend_groups, $user;
	$list = array();
	$query = DB::query("SELECT f.fuid, f.fusername, f.gid FROM ".DB::table('home_friend')." f, ".DB::table('common_session')." s
		WHERE f.uid='$user->uid' AND f.fuid = s.uid ORDER BY f.num DESC, f.dateline DESC");
	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"uid" => $value['fuid'],
			"id" => to_utf8($value['fusername']),
			"nick" => to_utf8($value['fusername']),
			"group" => $friend_groups[$value['gid']],
			"url" => "home.php?mod=space&uid=".$value['fuid'],
			"pic_url" => avatar($value['fuid'], 'small', true),
		);
	}
	return $list;
}

/**
 * Get buddy list from given ids
 * $ids:
 *
 * Example:
 * 	buddy('admin,webim,test');
 *
 */
function buddy($ids){
	global $friend_groups, $user;
	$ids = "'".implode("','", explode(",", $ids))."'";
	$list = array();
	$query = DB::query("SELECT m.uid, m.username, f.gid FROM ".DB::table('common_member')." m
		LEFT JOIN (SELECT * FROM ".DB::table('home_friend')." WHERE uid = $user->uid) f ON f.fuid = m.uid
		WHERE m.username IN ($ids) AND m.uid <> $user->uid");
	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"uid" => $value['uid'],
			"id" => to_utf8($value['username']),
			"nick" => to_utf8($value['username']),
			"group" => $value['gid'] ? $friend_groups[$value['gid']] : "stranger",
			"url" => "home.php?mod=space&uid=".$value['uid'],
			"pic_url" => avatar($value['uid'], 'small', true),
		);
	}
	return $list;
}

/**
 * Get room list
 * $ids: Get all user rooms if not given.
 *
 */

function room($ids=null){
	global $user;
	if($ids){
		$ids = "'".implode("','", explode(",", $ids))."'";
		$where = "f.fid = ($ids)";
	}else{
		$where = "f.fid IN (SELECT fid FROM ".DB::table("forum_groupuser")." WHERE uid=$user->uid)";
	}
	$list = array();
	$query = DB::query("SELECT f.fid, f.name, ff.icon, ff.membernum, ff.description 
		FROM ".DB::table('forum_forum')." f 
		LEFT JOIN ".DB::table("forum_forumfield")." ff ON ff.fid=f.fid 
		WHERE f.type='sub' AND f.status=3 AND $where");

	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"fid" => $value['fid'],
			"id" => $value['fid'],
			"nick" => to_utf8($value['name']),
			"url" => "forum.php?mod=group&fid=".$value['fid'],
			"pic_url" => get_groupimg($value['icon'], 'icon'),
			"status" => to_utf8($value['description']),
			"count" => 0,
			"all_count" => $value['membernum'],
			"blocked" => false,
		);
	}
	return $list;
}

/**
 * Get history message
 *
 * @param string $type unicast or multicast
 * @param string $id
 *
 * Example:
 * 	history('unicast', 'webim');
 * 	history('multicast', '36');
 *
 */

function history($type, $id){
	global $user;
	$user_id = $user->id;
	$list = array();
	if($type == "unicast"){
		$query = DB::query("SELECT * FROM ".DB::table('webim_histories')." 
			WHERE `send` = 1 AND `type` = 'unicast' 
			AND ((`to`='$id' AND `from`='$user_id' AND `fromdel` != 1) 
			OR (`from`='$id' AND `to`='$user_id' AND `todel` != 1))  
			ORDER BY timestamp DESC LIMIT 30");
		while ($value = DB::fetch($query)){
			$list[] = log_item($value);
		}
	}elseif($type == "multicast"){
		$query = DB::query("SELECT * FROM ".DB::table('webim_histories')." 
			WHERE `to`='$id' AND `type`='multicast' AND send = 1 
			ORDER BY timestamp DESC LIMIT 30");
		while ($value = DB::fetch($query)){
			$list[] = log_item($value);
		}
	}else{
	}
	return $list;
}

/**
 * Get new message
 *
 */

function new_message() {
	global $user;
	$id = $user->id;
	$list = array();
	$query = DB::query("SELECT * FROM ".DB::table('webim_histories')." 
		WHERE `to`='$id' and send = 0 
		ORDER BY timestamp DESC LIMIT 100");
	while ($value = DB::fetch($query)){
		$list[] = log_item($value);
	}
	return $list;
}

/**
 * mark the new message as read.
 *
 */

function new_message_to_histroy() {
	global $user;
	DB::update("webim_histories", array("send" => 1), array("to" => $user->id, "send" => 0));
}

function log_item($value){
	return (object)array(
		'to' => to_utf8($value['to']),
		'nick' => to_utf8($value['nick']),
		'from' => to_utf8($value['from']),
		'style' => $value['style'],
		'body' => to_utf8($value['body']),
		'type' => $value['type'],
		'timestamp' => $value['timestamp']
	);
}

/**
 * Get user setting
 *
 */

function setting(){
	global $user;
	$data = DB::fetch_first("SELECT web FROM ".DB::table('webim_settings')." WHERE uid = $user->uid");
	if($data){
		return json_decode($data['web']);
	}else{
		DB::insert('webim_settings', array('uid' => $user->uid, 'web' => '{}'));
		return new stdClass();
	}
}

function to_utf8($s){
	if(CHARSET == 'utf-8') {
		return $s;
	} else {
		return  _iconv(CHARSET,'utf-8',$s);
	}
}

function from_utf8($s){
	if(CHARSET == 'utf-8') {
		return $s;
	} else {
		return  _iconv('utf-8',CHARSET,$s);
	}
}

function ids_array($ids){
	return ($ids===NULL || $ids==="") ? array() : (is_array($ids) ? array_unique($ids) : array_unique(explode(",", $ids)));
}
