<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_stats`;");
E_C("CREATE TABLE `ecs_stats` (
  `access_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `visit_times` smallint(5) unsigned NOT NULL DEFAULT '1',
  `browser` varchar(60) NOT NULL DEFAULT '',
  `system` varchar(20) NOT NULL DEFAULT '',
  `language` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(30) NOT NULL DEFAULT '',
  `referer_domain` varchar(100) NOT NULL DEFAULT '',
  `referer_path` varchar(200) NOT NULL DEFAULT '',
  `access_url` varchar(255) NOT NULL DEFAULT '',
  KEY `access_time` (`access_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ecs_stats` values('1430070575','127.0.0.1','7','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/new3/install/index.php?lang=zh_cn&step=done','/new3/index.php');");
E_D("replace into `ecs_stats` values('1430074027','127.0.0.1','16','Safari 537.36','Windows NT','zh-CN,zh','LAN','','','/new3/index.php');");
E_D("replace into `ecs_stats` values('1438566591','127.0.0.1','35','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/install/index.php?lang=zh_cn&amp;step=done','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438566912','127.0.0.1','36','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438568794','127.0.0.1','38','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438570011','127.0.0.1','39','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438571306','127.0.0.1','40','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438571521','127.0.0.1','41','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438572352','127.0.0.1','42','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438576585','127.0.0.1','43','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/search.php?encode=YToyOntzOjg6ImtleXdvcmRzIjtzOjY6ImlQaG9uZSI7czoxODoic2VhcmNoX2VuY29kZV90aW1lIjtpOjE0Mzg1NzM4Mjc7fQ==','/mbsuning2015/search.php');");
E_D("replace into `ecs_stats` values('1438586878','127.0.0.1','45','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438752261','127.0.0.1','48','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438752537','127.0.0.1','49','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438753433','127.0.0.1','50','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438756087','127.0.0.1','51','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=21','/mbsuning2015/goods.php');");
E_D("replace into `ecs_stats` values('1438757847','127.0.0.1','52','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=132','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438760369','127.0.0.1','53','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=132','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438760443','127.0.0.1','54','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=132','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438761787','127.0.0.1','55','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438767046','192.168.1.57','1','Safari 537.36','Windows NT','zh-CN,zh','LAN','','','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438767086','192.168.1.228','1','Safari 600.7.12','Unknown','zh-cn','LAN','','','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438824207','127.0.0.1','56','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438850401','127.0.0.1','57','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438910097','127.0.0.1','59','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1438917765','127.0.0.1','62','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1441963868','127.0.0.1','14','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442192345','127.0.0.1','16','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442193063','127.0.0.1','17','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/','/mbsuning2015/category.php');");
E_D("replace into `ecs_stats` values('1442210956','127.0.0.1','19','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442211406','127.0.0.1','20','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442211553','127.0.0.1','21','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/flow.php','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442212363','127.0.0.1','22','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/','/mbsuning2015/flow.php');");
E_D("replace into `ecs_stats` values('1442216432','127.0.0.1','23','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/tag_cloud.php','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442217937','127.0.0.1','24','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/index.php','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442218576','127.0.0.1','25','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=21','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442278964','127.0.0.1','26','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442279024','127.0.0.1','27','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/','/mbsuning2015/category.php');");
E_D("replace into `ecs_stats` values('1442288148','127.0.0.1','28','Internet Explorer 7.0','Windows NT','zh-CN','LAN','','','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442293287','127.0.0.1','29','Internet Explorer 7.0','Windows NT','zh-CN','LAN','http://localhost','/mbsuning2015/search.php?encode=YToyOntzOjg6ImtleXdvcmRzIjtzOjY6ImlQaG9uZSI7czoxODoic2VhcmNoX2VuY29kZV90aW1lIjtpOjE0NDIyODgxNTQ7fQ==','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442302995','127.0.0.1','30','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/','/mbsuning2015/category.php');");
E_D("replace into `ecs_stats` values('1442303610','127.0.0.1','31','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=139','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442304901','127.0.0.1','32','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442309949','127.0.0.1','33','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/brand.php?id=6','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442365284','127.0.0.1','34','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442368928','127.0.0.1','35','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442369776','127.0.0.1','36','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=21','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442372033','127.0.0.1','38','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/category.php?id=21','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442373644','127.0.0.1','10','FireFox 38.0','Windows NT','zh-CN,zh','LAN','','','/mbsuning2015/goods.php');");
E_D("replace into `ecs_stats` values('1442380015','127.0.0.1','39','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/index.php','/mbsuning2015/category.php');");
E_D("replace into `ecs_stats` values('1442381687','127.0.0.1','40','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1442383258','127.0.0.1','41','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442384269','127.0.0.1','42','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015-2/index.php');");
E_D("replace into `ecs_stats` values('1442384992','127.0.0.1','43','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbsuning2015/admin/index.php?act=top','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442385319','127.0.0.1','44','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1442385522','127.0.0.1','45','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442385547','127.0.0.1','46','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1442385743','127.0.0.1','47','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbsuning2015/index.php');");
E_D("replace into `ecs_stats` values('1442386427','127.0.0.1','48','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1442387315','127.0.0.1','49','Safari 537.36','Windows NT','zh-CN,zh','LAN','http://localhost','/mbxiaomi2015/admin/index.php?act=top','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1442387883','127.0.0.1','11','FireFox 40.0','Windows NT','zh-CN,zh','LAN','','','/mbxiaomi2015/index.php');");
E_D("replace into `ecs_stats` values('1447172401','127.0.0.1','1','Safari 536.11','Windows NT','zh-CN,zh','LAN','','','/index.php');");
E_D("replace into `ecs_stats` values('1447172777','127.0.0.1','2','Safari 536.11','Windows NT','zh-CN,zh','LAN','http://xiaomi.nk6.cn','/admin/goods.php?act=list','/goods.php');");
E_D("replace into `ecs_stats` values('1447144509','127.0.0.1','3','Safari 536.11','Windows NT','zh-CN,zh','LAN','','','/index.php');");

require("../../inc/footer.php");
?>