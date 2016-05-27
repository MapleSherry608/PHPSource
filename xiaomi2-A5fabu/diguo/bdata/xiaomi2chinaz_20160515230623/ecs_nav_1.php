<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_nav`;");
E_C("CREATE TABLE `ecs_nav` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ctype` varchar(10) DEFAULT NULL,
  `cid` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `ifshow` tinyint(1) NOT NULL,
  `vieworder` tinyint(1) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `ifshow` (`ifshow`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_nav` values('2','','0','选购中心','1','2','0','pick_out.php','top');");
E_D("replace into `ecs_nav` values('3','','0','我的账户','1','0','0','user.php','top');");
E_D("replace into `ecs_nav` values('4','c','21','电视 盒子','1','0','0','category.php?id=21','middle');");
E_D("replace into `ecs_nav` values('6','','0','标签云','1','5','6','tag_cloud.php','top');");
E_D("replace into `ecs_nav` values('7','','0','免责条款','1','1','0','article.php?id=1','bottom');");
E_D("replace into `ecs_nav` values('8','','0','隐私保护','1','2','0','article.php?id=2','bottom');");
E_D("replace into `ecs_nav` values('9','','0','咨询热点','1','3','0','article.php?id=3','bottom');");
E_D("replace into `ecs_nav` values('10','','0','联系我们','1','4','0','article.php?id=4','bottom');");
E_D("replace into `ecs_nav` values('11','','0','公司简介','1','5','0','article.php?id=5','bottom');");
E_D("replace into `ecs_nav` values('12','','0','批发方案','1','6','0','wholesale.php','bottom');");
E_D("replace into `ecs_nav` values('14','','0','配送方式','1','7','0','myship.php','bottom');");
E_D("replace into `ecs_nav` values('18','c','44','耳机 音箱','1','2','0','category.php?id=44','middle');");
E_D("replace into `ecs_nav` values('23','','0','报价单','1','6','0','quotation.php','top');");
E_D("replace into `ecs_nav` values('24','','0','团购','1','23','0','group_buy.php','middle');");
E_D("replace into `ecs_nav` values('26','c','132','手机 平板','1','1','0','category.php?id=132','middle');");
E_D("replace into `ecs_nav` values('33','','0','品牌专区','1','7','0','brand.php','middle');");

require("../../inc/footer.php");
?>