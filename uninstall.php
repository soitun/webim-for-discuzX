<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 21:27:07 CST 2010
 *
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE cdb_webim_settings;
DROP TABLE cdb_webim_histories;

EOF;

runquery($sql);

$finish = TRUE;
