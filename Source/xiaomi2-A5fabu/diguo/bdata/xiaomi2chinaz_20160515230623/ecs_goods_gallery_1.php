<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_goods_gallery`;");
E_C("CREATE TABLE `ecs_goods_gallery` (
  `img_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `img_url` varchar(255) NOT NULL DEFAULT '',
  `img_desc` varchar(255) NOT NULL DEFAULT '',
  `thumb_url` varchar(255) NOT NULL DEFAULT '',
  `img_original` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`img_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=313 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_goods_gallery` values('295','139','images/201508/goods_img/139_P_1438763747502.jpg','','images/201508/thumb_img/139_thumb_P_1438763747924.jpg','images/201508/source_img/139_P_1438763747432.jpg');");
E_D("replace into `ecs_goods_gallery` values('292','140','images/201508/goods_img/140_P_1438763220435.jpg','','images/201508/thumb_img/140_thumb_P_1438763220549.jpg','images/201508/source_img/140_P_1438763220528.jpg');");
E_D("replace into `ecs_goods_gallery` values('288','141','images/201508/goods_img/141_P_1438762737964.jpg','','images/201508/thumb_img/141_thumb_P_1438762737948.jpg','images/201508/source_img/141_P_1438762737753.jpg');");
E_D("replace into `ecs_goods_gallery` values('287','141','images/201508/goods_img/141_P_1438762737725.jpg','','images/201508/thumb_img/141_thumb_P_1438762737987.jpg','images/201508/source_img/141_P_1438762737618.jpg');");
E_D("replace into `ecs_goods_gallery` values('291','140','images/201508/goods_img/140_P_1438763219385.jpg','','images/201508/thumb_img/140_thumb_P_1438763219296.jpg','images/201508/source_img/140_P_1438763219705.jpg');");
E_D("replace into `ecs_goods_gallery` values('294','139','images/201508/goods_img/139_P_1438763747225.jpg','','images/201508/thumb_img/139_thumb_P_1438763747859.jpg','images/201508/source_img/139_P_1438763746925.jpg');");
E_D("replace into `ecs_goods_gallery` values('299','134','images/201509/goods_img/134_P_1442304930062.jpg','','images/201509/thumb_img/134_thumb_P_1442304930583.jpg','images/201509/source_img/134_P_1442304930068.jpg');");
E_D("replace into `ecs_goods_gallery` values('286','141','images/201508/goods_img/141_P_1438761779633.jpg','','images/201508/thumb_img/141_thumb_P_1438761779896.jpg','images/201508/source_img/141_P_1438761779040.jpg');");
E_D("replace into `ecs_goods_gallery` values('289','140','images/201508/goods_img/140_P_1438763203749.jpg','','images/201508/thumb_img/140_thumb_P_1438763203531.jpg','images/201508/source_img/140_P_1438763203087.jpg');");
E_D("replace into `ecs_goods_gallery` values('290','140','images/201508/goods_img/140_P_1438763219488.jpg','','images/201508/thumb_img/140_thumb_P_1438763219835.jpg','images/201508/source_img/140_P_1438763219185.jpg');");
E_D("replace into `ecs_goods_gallery` values('293','139','images/201508/goods_img/139_P_1438763729923.jpg','','images/201508/thumb_img/139_thumb_P_1438763729405.jpg','images/201508/source_img/139_P_1438763728323.jpg');");
E_D("replace into `ecs_goods_gallery` values('296','138','images/201508/goods_img/138_P_1438764119199.jpg','','images/201508/thumb_img/138_thumb_P_1438764119193.jpg','images/201508/source_img/138_P_1438764119386.jpg');");
E_D("replace into `ecs_goods_gallery` values('301','137','images/201509/goods_img/137_P_1442305336030.png','','images/201509/thumb_img/137_thumb_P_1442305336054.jpg','images/201509/source_img/137_P_1442305336066.png');");
E_D("replace into `ecs_goods_gallery` values('300','136','images/201509/goods_img/136_P_1442305049073.png','','images/201509/thumb_img/136_thumb_P_1442305049493.jpg','images/201509/source_img/136_P_1442305048480.png');");
E_D("replace into `ecs_goods_gallery` values('302','139','images/201509/goods_img/139_P_1442305427032.png','','images/201509/thumb_img/139_thumb_P_1442305427520.jpg','images/201509/source_img/139_P_1442305427887.png');");
E_D("replace into `ecs_goods_gallery` values('303','140','images/201509/goods_img/140_P_1442305481203.png','','images/201509/thumb_img/140_thumb_P_1442305481298.jpg','images/201509/source_img/140_P_1442305481700.png');");
E_D("replace into `ecs_goods_gallery` values('304','141','images/201509/goods_img/141_P_1442305620774.png','','images/201509/thumb_img/141_thumb_P_1442305620764.jpg','images/201509/source_img/141_P_1442305620713.png');");
E_D("replace into `ecs_goods_gallery` values('305','143','images/201509/goods_img/143_P_1442306944656.jpg','','images/201509/thumb_img/143_thumb_P_1442306944358.jpg','images/201509/source_img/143_P_1442306944329.jpg');");
E_D("replace into `ecs_goods_gallery` values('306','144','images/201509/goods_img/144_P_1442307051233.jpg','','images/201509/thumb_img/144_thumb_P_1442307051868.jpg','images/201509/source_img/144_P_1442307051609.jpg');");
E_D("replace into `ecs_goods_gallery` values('307','145','images/201509/goods_img/145_P_1442307109608.jpg','','images/201509/thumb_img/145_thumb_P_1442307109225.jpg','images/201509/source_img/145_P_1442307109825.jpg');");
E_D("replace into `ecs_goods_gallery` values('308','146','images/201509/goods_img/146_P_1442307183265.png','','images/201509/thumb_img/146_thumb_P_1442307183194.jpg','images/201509/source_img/146_P_1442307183307.png');");
E_D("replace into `ecs_goods_gallery` values('309','147','images/201509/goods_img/147_P_1442307321250.png','','images/201509/thumb_img/147_thumb_P_1442307321798.jpg','images/201509/source_img/147_P_1442307321764.png');");
E_D("replace into `ecs_goods_gallery` values('310','148','images/201509/goods_img/148_P_1442386133972.jpg','','images/201509/thumb_img/148_thumb_P_1442386133760.jpg','images/201509/source_img/148_P_1442386133226.jpg');");
E_D("replace into `ecs_goods_gallery` values('311','149','images/201509/goods_img/149_P_1442386182101.jpg','','images/201509/thumb_img/149_thumb_P_1442386182218.jpg','images/201509/source_img/149_P_1442386182032.jpg');");
E_D("replace into `ecs_goods_gallery` values('312','150','images/201509/goods_img/150_P_1442386258823.jpg','','images/201509/thumb_img/150_thumb_P_1442386258151.jpg','images/201509/source_img/150_P_1442386258519.jpg');");

require("../../inc/footer.php");
?>