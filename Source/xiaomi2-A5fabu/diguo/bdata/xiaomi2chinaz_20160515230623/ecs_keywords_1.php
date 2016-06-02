<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_keywords`;");
E_C("CREATE TABLE `ecs_keywords` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `searchengine` varchar(20) NOT NULL DEFAULT '',
  `keyword` varchar(90) NOT NULL DEFAULT '',
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`,`searchengine`,`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ecs_keywords` values('2015-08-03','ecshop','婴儿推车','4');");
E_D("replace into `ecs_keywords` values('2015-08-03','ecshop','花露水','2');");
E_D("replace into `ecs_keywords` values('2015-08-03','ecshop','iPhone','2');");
E_D("replace into `ecs_keywords` values('2015-08-03','ecshop','ipad','6');");
E_D("replace into `ecs_keywords` values('2015-09-11','ecshop','ipad','1');");
E_D("replace into `ecs_keywords` values('2015-09-14','ecshop','母婴用品','1');");
E_D("replace into `ecs_keywords` values('2015-09-14','ecshop','dasdsad','1');");
E_D("replace into `ecs_keywords` values('2015-09-14','ecshop','fsgdgfd','1');");
E_D("replace into `ecs_keywords` values('2015-09-14','ecshop','wwwww','1');");
E_D("replace into `ecs_keywords` values('2015-09-15','ecshop','防守打法','1');");
E_D("replace into `ecs_keywords` values('2015-09-15','ecshop','iPhone','1');");

require("../../inc/footer.php");
?>