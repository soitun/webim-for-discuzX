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

//Find and insert data with utf8 client.
DB::query("SET NAMES utf8");

require 'config.php';

//Cache friend_groups;
$friend_groups = friend_group_list();
foreach($friend_groups as $k => $v){
	$friend_groups[$k] = to_utf8($v);
}

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
$user->pic_url = avatar($user->uid, 'small', true);
$user->show = gp('show') ? gp('show') : "available";
$user->url = "home.php?mod=space&uid=".$user->uid;

//Common $ticket

$ticket = gp('ticket');
if($ticket){
	$ticket = stripslashes($ticket);
}

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
			if ( $id ) {
				$ids[] = $id;
				$ob[$id] = $m;
			}
		}
		$ids = implode(",", $ids);
		$query = DB::query("SELECT uid, spacenote FROM ".DB::table('common_member_field_home')." WHERE uid IN ($ids)");
		while($res = DB::fetch($query)) {
			$ob[$res['uid']]->status = $res['spacenote'];
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
	$query = DB::query("SELECT f.fuid uid, f.fusername username, p.realname name, f.gid 
		FROM ".DB::table('home_friend')." f, ".DB::table('common_session')." s, ".DB::table('common_member_profile')." p
		WHERE f.uid='$user->uid' AND f.fuid = s.uid AND p.uid = s.uid 
		ORDER BY f.num DESC, f.dateline DESC");
	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"uid" => $value['uid'],
			"id" => $value['username'],
			"nick" => nick($value),
			"group" => $friend_groups[$value['gid']],
			"url" => "home.php?mod=space&uid=".$value['uid'],
			"pic_url" => avatar($value['uid'], 'small', true),
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

function buddy($names, $uids = null){
	global $friend_groups, $user;
	$where_name = "";
	$where_uid = "";
	if(!$names and !$uids)return array();
	if($names){
		$names = "'".implode("','", explode(",", $names))."'";
		$where_name = "m.username IN ($names)";
	}
	if($uids){
		$where_uid = "m.uid IN ($uids)";
	}
	$where_sql = $where_name && $where_uid ? "($where_name OR $where_uid)" : ($where_name ? $where_name : $where_uid);

	$list = array();
	$query = DB::query("SELECT m.uid, m.username, p.realname name, f.gid FROM ".DB::table('common_member')." m
		LEFT JOIN ".DB::table('home_friend')." f 
		ON f.fuid = m.uid AND f.uid = $user->uid 
		LEFT JOIN ".DB::table('common_member_profile')." p
		ON m.uid = p.uid 
		WHERE m.uid <> $user->uid AND $where_sql");
	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"uid" => $value['uid'],
			"id" => $value['username'],
			"nick" => nick($value),
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
	if(!$ids){
		$ids = DB::result_first("SELECT fid FROM ".DB::table("forum_groupuser")." WHERE uid=$user->uid");
	}
	$list = array();
	if(!$ids){
		return $list;
	}
	$where = "f.fid IN ($ids)";
	$query = DB::query("SELECT f.fid, f.name, ff.icon, ff.membernum, ff.description 
		FROM ".DB::table('forum_forum')." f 
		LEFT JOIN ".DB::table("forum_forumfield")." ff ON ff.fid=f.fid 
		WHERE f.type='sub' AND f.status=3 AND $where");

	while ($value = DB::fetch($query)){
		$list[] = (object)array(
			"fid" => $value['fid'],
			"id" => $value['fid'],
			"nick" => $value['name'],
			"url" => "forum.php?mod=group&fid=".$value['fid'],
			"pic_url" => get_groupimg($value['icon'], 'icon'),
			"status" => $value['description'],
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
			WHERE `type` = 'unicast' 
			AND ((`to`='$id' AND `from`='$user_id' AND `fromdel` != 1) 
			OR (`send` = 1 AND `from`='$id' AND `to`='$user_id' AND `todel` != 1))  
			ORDER BY timestamp DESC LIMIT 30");
		while ($value = DB::fetch($query)){
			array_unshift($list, log_item($value));
		}
	}elseif($type == "multicast"){
		$query = DB::query("SELECT * FROM ".DB::table('webim_histories')." 
			WHERE `to`='$id' AND `type`='multicast' AND send = 1 
			ORDER BY timestamp DESC LIMIT 30");
		while ($value = DB::fetch($query)){
			array_unshift($list, log_item($value));
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
		array_unshift($list, log_item($value));
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
		'to' => $value['to'],
		'nick' => $value['nick'],
		'from' => $value['from'],
		'style' => $value['style'],
		'body' => $value['body'],
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

function nick($sp) {
	global $_IMC;
	return (!$_IMC['show_realname']||empty($sp['name'])) ? $sp['username'] : $sp['name'];
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
