<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 22:14:34 CST 2010
 *
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if($_G['gp_theme']){
	$theme = $_G['gp_theme'];
	DB::query("UPDATE ".DB::table('common_pluginvar')." SET value='$theme' WHERE pluginid='$pluginid' AND variable='theme'");
}else{
	$res = DB::fetch_first("SELECT * FROM ".DB::table('common_pluginvar')." WHERE pluginid='$pluginid' AND variable='theme'");
	if($res){
		$theme = $res['value'];
	}
}

//$Plang = $scriptlang['myrepeats'];

//if($_G['gp_op'] == 'lock') 
showtips($templatelang['webim']['notice']);
showformheader('plugins&operation=config&do='.$pluginid.'&identifier=webim&pmod=themes');
showtableheader();
showsetting('主题', 'theme', $theme, 'text');
showsubmit('submit');
showtablefooter();
showformfooter();

?>
