<?php
return "CREATE TABLE IF NOT EXISTS `m62_test_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(60) NOT NULL DEFAULT '',
  `setting_value` text NOT NULL,
  `serialized` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";