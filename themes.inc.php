<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 22:14:34 CST 2010
 *
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
function webim_scan_subdir( $dir ){
	$d = dir( $dir."/" );
	$dn = array();
	while ( false !== ( $f = $d->read() ) ) {
		if(is_dir($dir."/".$f) && $f!='.' && $f!='..') $dn[]=$f;
	}
	$d->close();
	return $dn;
}

//$sl = $scriptlang['webim'];
$tl = $templatelang['webim'];
$notice = "";

if($_G['gp_theme']){
	$theme = $_G['gp_theme'];
	DB::query("UPDATE ".DB::table('common_pluginvar')." SET value='$theme' WHERE pluginid='$pluginid' AND variable='theme'");
	updatecache('plugin');
	$notice = "<div id='notice'>".$tl['themes_success']."</div>";

}else{
	$res = DB::fetch_first("SELECT * FROM ".DB::table('common_pluginvar')." WHERE pluginid='$pluginid' AND variable='theme'");
	if($res){
		$theme = $res['value'];
	}else{
		$theme = 'base';
	}
}

echo $notice;
showtips($tl['themes_tips']);

$path = dirname(__FILE__).DIRECTORY_SEPARATOR."static".DIRECTORY_SEPARATOR."themes";
$files = webim_scan_subdir( $path );
$html = '<ul id="themes">';
foreach ($files as $k => $v){
	$t_path = $path.DIRECTORY_SEPARATOR.$v;
	if(is_dir($t_path) && is_file($t_path.DIRECTORY_SEPARATOR."jquery.ui.theme.css")){
		$cur = $v == $theme ? " class='current'" : "";
		$url = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=webim&pmod=themes&theme='.$v;
		$html .= "<li$cur><h4><a href='$url'>$v</a></h4><p><a href='$url'><img width=100 height=134 src='source/plugin/webim/static/themes/images/$v.png' alt='$v' title='$v'/></a></p></li>";
	}
}
$html .= '</ul>';
?>
<style type="text/css">
#notice{
	margin-top: 5px;
	padding: 10px;
	text-align: center;
	background: #FFFAF0;
	border: 1px solid #FFD700;
}
#themes{
	overflow: hidden;
	list-style: none;
	padding: 0;
	margin: 0;
	margin-top: 20px;
}
#themes li{
	float: left;
	padding: 10px;
}
#themes li h4{
	margin: 0 0 5px 0;
}
#themes li.current{
	background: yellow;
}
</style>
<?php echo $html;?>
