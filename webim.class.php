<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 22:25:15 CST 2010
 *
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_webim {

	function global_footer() {
		global $_G;
		if(!$_G['uid']) {
			return;
		}
		$config = $_G['cache']['plugin']['webim'];
		$theme = empty($config['theme']) ? 'base' : $config['theme'];
		$local = empty($config['local']) ? 'zh-CN' : $config['local'];
		return <<<EOF
		<link href="source/plugin/webim/static/webim.uchome.min.css" media="all" type="text/css" rel="stylesheet"/>
		<link href="source/plugin/webim/static/themes/{$theme}/jquery.ui.theme.css" media="all" type="text/css" rel="stylesheet"/>
		<script src="source/plugin/webim/static/webim.uchome.min.js" type="text/javascript"></script>
		<script src="source/plugin/webim/static/i18n/webim-{$local}.js" type="text/javascript"></script>
		<script src="source/plugin/webim/custom.js.php" type="text/javascript"></script>
EOF;
	}
}

