<?php

/**
 * Author: Hidden
 * Date: Mon Aug 23 21:26:11 CST 2010
 *
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS cdb_webim_histories;
CREATE TABLE cdb_webim_histories (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`send` tinyint(1) DEFAULT NULL,
	`type` varchar(20) DEFAULT NULL,
	`to` varchar(50) NOT NULL,
	`from` varchar(50) NOT NULL,
	`nick` varchar(20) DEFAULT NULL COMMENT 'from nick',
	`body` text,
	`style` varchar(150) DEFAULT NULL,
	`timestamp` double DEFAULT NULL,
	`todel` tinyint(1) NOT NULL DEFAULT '0',
	`fromdel` tinyint(1) NOT NULL DEFAULT '0',
	`created_at` date DEFAULT NULL,
	`updated_at` date DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `todel` (`todel`),
	KEY `fromdel` (`fromdel`),
	KEY `timestamp` (`timestamp`),
	KEY `to` (`to`),
	KEY `from` (`from`),
	KEY `send` (`send`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS cdb_webim_settings;
CREATE TABLE cdb_webim_settings(
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`uid` mediumint(8) unsigned NOT NULL,
	`web` blob,
	`air` blob,
	`created_at` date DEFAULT NULL,
	`updated_at` date DEFAULT NULL,
	PRIMARY KEY (`id`) 
)ENGINE=MyISAM;

EOF;

runquery($sql);

$finish = TRUE;

?>
