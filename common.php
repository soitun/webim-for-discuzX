<?php

require 'lib/webim.class.php';
require '../../class/class_core.php';
include_once('../../function/function_friend.php');
include_once('../../function/function_group.php');
$discuz = & discuz_core::instance();
$discuz->init();
if(!defined('IN_DISCUZ') || !$_G['uid']) {
	exit('Access Denied');
}
//Cache friend_groups;
$friend_groups = friend_group_list();
//DISCUZ_ROOT
//CHARSET

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
$user->id = $_G['username'];
$user->nick = $_G['username'];
$user->pic_url = avatar($user->id, 'small', true);
$user->show = $_G['gp_show'] ? $_G['gp_show'] : "available";
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
	$query = DB::query("SELECT f.fuid, f.fusername, f.gid FROM ".DB::table('home_friend')." f, ".DB::table('common_session')." s
		WHERE f.uid='$user->uid' AND f.fuid = s.uid ORDER BY f.num DESC, f.dateline DESC");
	while ($value = DB::fetch($query)){
		$list[$value['fuid']] = (object)array(
			"uid" => $value['fuid'],
			"id" => $value['fusername'],
			"nick" => $value['fusername'],
			"group" => $friend_groups[$value['gid']],
			"url" => "home.php?mod=space&uid=".$value['fuid'],
			"pic_url" => avatar($value['fuid'], 'small', true),
		);
	}
	return $list;
}

/**
 * Get buddy list from given ids
 * $ids: 'admin,webim'
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
		$list[$value['uid']] = (object)array(
			"uid" => $value['uid'],
			"id" => $value['username'],
			"nick" => $value['username'],
			"group" => $value['gid'] ? $friend_groups[$value['gid']] : "stranger",
			"url" => "home.php?mod=space&uid=".$value['uid'],
			"pic_url" => avatar($value['uid'], 'small', true),
		);
	}
	return $list;
}

/**
 * Get room list
 * $id: 
 *
 */

function room($id=null){
	global $user;
	if($id){
		$where = "f.fid = $id";
	}else{
		$where = "f.fid IN (SELECT fid FROM ".DB::table("forum_groupuser")." WHERE uid=$user->uid)";
	}
	$list = array();
	$query = DB::query("SELECT f.fid, f.name, ff.icon, ff.membernum, ff.description 
		FROM ".DB::table('forum_forum')." f 
		LEFT JOIN ".DB::table("forum_forumfield")." ff ON ff.fid=f.fid 
		WHERE f.type='sub' AND f.status=3 AND $where");

	while ($value = DB::fetch($query)){
		$list[$value['fid']] = (object)array(
			"fid" => $value['fid'],
			"id" => $value['fid'],
			"nick" => $value['name'],
			"url" => "forum.php?mod=group&fid=".$value['fid'],
			"pic_url" => get_groupimg($value['icon'], 'icon'),
			"status" => $value['description'],
			"all_count" => $value['membernum'],
		);
	}
	return $list;
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

