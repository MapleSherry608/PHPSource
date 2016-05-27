<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_group_goods`;");
E_C("CREATE TABLE `ecs_group_goods` (
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `admin_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`parent_id`,`goods_id`,`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ecs_group_goods` values('134','141','100.00','1');");
E_D("replace into `ecs_group_goods` values('134','140','100.00','1');");
E_D("replace into `ecs_group_goods` values('134','139','100.00','1');");
E_D("replace into `ecs_group_goods` values('134','138','100.00','1');");
E_D("replace into `ecs_group_goods` values('134','137','100.00','1');");
E_D("replace into `ecs_group_goods` values('134','136','100.00','1');");

require("../../inc/footer.php");
?>