<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_touch_shop_config`;");
E_C("CREATE TABLE `ecs_touch_shop_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `store_range` varchar(255) NOT NULL DEFAULT '',
  `store_dir` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=909 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_touch_shop_config` values('1','0','shop_info','group','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('2','0','basic','group','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('3','0','display','group','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('7','0','goods','group','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('8','0','sms','group','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('101','1','shop_name','text','','','ECSHOP模板堂','1');");
E_D("replace into `ecs_touch_shop_config` values('102','1','shop_title','text','','','ECSHOP模板堂（www.ecmoban.com）专业ECSHOP模板制作 ECSHOP模板定制_ECSHOP模板修改_ECSHOP模板出售_ECSHOP模板案例','1');");
E_D("replace into `ecs_touch_shop_config` values('103','1','shop_desc','text','','','ECSHOP模板堂（www.ecmoban.com）专业ECSHOP模板制作 ECSHOP模板定制_ECSHOP模板修改_ECSHOP模板出售_ECSHOP模板案例','1');");
E_D("replace into `ecs_touch_shop_config` values('104','1','shop_keywords','text','','','ECSHOP模板堂（www.ecmoban.com）专业ECSHOP模板制作 ECSHOP模板定制_ECSHOP模板修改_ECSHOP模板出售_ECSHOP模板案例','1');");
E_D("replace into `ecs_touch_shop_config` values('118','1','shop_logo','file','','./themes/{\$template}/images/','','1');");
E_D("replace into `ecs_touch_shop_config` values('120','1','user_notice','textarea','','','用户中心公告！','1');");
E_D("replace into `ecs_touch_shop_config` values('122','1','shop_reg_closed','select','1,0','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('215','2','no_picture','file','','./data/common/images/','','1');");
E_D("replace into `ecs_touch_shop_config` values('218','2','stats_code','textarea','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('220','2','register_points','text','','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('312','3','top_number','text','','','10','1');");
E_D("replace into `ecs_touch_shop_config` values('313','3','history_number','text','','','5','1');");
E_D("replace into `ecs_touch_shop_config` values('314','3','comments_number','text','','','5','1');");
E_D("replace into `ecs_touch_shop_config` values('315','3','bought_goods','text','','','3','1');");
E_D("replace into `ecs_touch_shop_config` values('316','3','article_number','text','','','5','1');");
E_D("replace into `ecs_touch_shop_config` values('317','3','goods_name_length','text','','','7','1');");
E_D("replace into `ecs_touch_shop_config` values('319','3','page_size','text','','','10','1');");
E_D("replace into `ecs_touch_shop_config` values('320','3','sort_order_type','select','0,1,2','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('321','3','sort_order_method','select','0,1','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('322','3','show_order_type','select','0,1,2','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('323','3','attr_related_number','text','','','5','1');");
E_D("replace into `ecs_touch_shop_config` values('330','3','search_keywords','text','','','','0');");
E_D("replace into `ecs_touch_shop_config` values('332','3','related_goods_number','text','','','4','1');");
E_D("replace into `ecs_touch_shop_config` values('334','3','article_page_size','text','','','10','1');");
E_D("replace into `ecs_touch_shop_config` values('604','6','template','hidden','','','default','1');");
E_D("replace into `ecs_touch_shop_config` values('623','6','stylename','hidden','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('701','7','show_goodssn','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('702','7','show_brand','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('703','7','show_goodsweight','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('704','7','show_goodsnumber','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('705','7','show_addtime','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('706','7','goodsattr_style','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('707','7','show_marketprice','select','1,0','','1','1');");
E_D("replace into `ecs_touch_shop_config` values('801','8','sms_shop_mobile','text','','','','1');");
E_D("replace into `ecs_touch_shop_config` values('802','8','sms_order_placed','select','1,0','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('803','8','sms_order_payed','select','1,0','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('904','1','shop_url','text','','','http://localhost/mbsuning2015','1');");
E_D("replace into `ecs_touch_shop_config` values('905','1','show_asynclist','select','1,0','','0','1');");
E_D("replace into `ecs_touch_shop_config` values('906','8','sms_ecmoban_user','text','','','','0');");
E_D("replace into `ecs_touch_shop_config` values('907','8','sms_ecmoban_password','password','','','','0');");
E_D("replace into `ecs_touch_shop_config` values('908','8','sms_signin','select','1,0','','0','1');");

require("../../inc/footer.php");
?>