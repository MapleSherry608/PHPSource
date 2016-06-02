<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_admin_log`;");
E_C("CREATE TABLE `ecs_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_admin_log` values('1','1438571850','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('2','1438582720','1','添加商品分类: 生活日用','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('3','1438582727','1','添加商品分类: 精品家电','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('4','1438582740','1','添加商品分类: 厨房电器','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('5','1438761779','1','编辑商品: Apple iPhone 6（16GB）土豪金 移动联通电信4G手机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('6','1438761809','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('7','1438762540','1','编辑商品: Apple iPhone 6（16GB）土豪金 移动联通电信4G手机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('8','1438762736','1','编辑商品: Apple iPhone 6（16GB）土豪金 移动联通电信4G手机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('9','1438762840','1','编辑商品: Apple iPhone 6（16GB）土豪金 移动联通电信4G手机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('10','1438763203','1','编辑商品: 尼康数码单反相机 D3200（18-55Ⅱ）+8G卡+原装包','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('11','1438763219','1','编辑商品: 尼康数码单反相机 D3200（18-55Ⅱ）+8G卡+原装包','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('12','1438763300','1','编辑商品: 尼康数码单反相机 D3200（18-55Ⅱ）+8G卡+原装包','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('13','1438763328','1','编辑商品: 尼康数码单反相机 D3200（18-55Ⅱ）+8G卡+原装包','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('14','1438763431','1','编辑商品: 尼康数码单反相机 D3200（18-55Ⅱ）+8G卡+原装包','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('15','1438763728','1','编辑商品: 华硕手机Zenfone ZE551 1.8G 4G/16G 金','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('16','1438763746','1','编辑商品: 华硕手机Zenfone ZE551 1.8G 4G/16G 金','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('17','1438763807','1','编辑商品: 华硕手机Zenfone ZE551 1.8G 4G/16G 金','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('18','1438763839','1','编辑商品: 华硕手机Zenfone ZE551 1.8G 4G/16G 金','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('19','1438764119','1','编辑商品: 小米 4 2GB内存版 白色 移动4G手机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('20','1438765599','1','编辑商品: 卡西欧(CASIO) EX-TR550 数码相机（粉）','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('21','1438765636','1','编辑商品: 卡西欧(CASIO) EX-TR550 数码相机（粉）','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('22','1438851354','1','添加商品: zdsgdg','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('23','1438853559','1','回收商品: zdsgdg','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('24','1442195573','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('25','1442199343','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('26','1442280606','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('27','1442304289','1','编辑商品分类: 手机 平板','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('28','1442304304','1','编辑商品分类: 电视 盒子','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('29','1442304330','1','编辑商品分类: 路由器 智能硬件','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('30','1442304352','1','编辑商品分类: 移动电源 插线板','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('31','1442304407','1','编辑商品分类: 耳机 音箱','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('32','1442304431','1','编辑商品分类: 电池 存储卡','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('33','1442304454','1','编辑商品分类: 保护壳 后盖','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('34','1442304478','1','编辑商品分类: 贴膜 其他配件','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('35','1442304519','1','编辑商品分类: 米兔 服装','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('36','1442304559','1','编辑商品分类: 背包 周边','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('37','1442304898','1','编辑商品: 小米电视2 40英寸','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('38','1442304930','1','编辑商品: 小米电视2 40英寸','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('39','1442305048','1','编辑商品: 小米平板','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('40','1442305336','1','编辑商品: 10000mAh 小米移动电源','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('41','1442305427','1','编辑商品: 小米手环 全新白色LED提示灯版','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('42','1442305481','1','编辑商品: 小米盒子 4K超高清网络机顶盒','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('43','1442305620','1','编辑商品: 小米头戴式耳机 50mm大尺寸金属振膜 手机直推高保真音质','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('44','1442306944','1','添加商品: 经典版米兔','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('45','1442307051','1','添加商品: 二次元男生服饰','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('46','1442307109','1','添加商品: 变形金刚皮革纹保护壳','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('47','1442307183','1','添加商品: 小蚁运动相机 边玩边录边拍，手机随时分享','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('48','1442307321','1','添加商品: 小米电视2S48英寸','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('49','1442386133','1','添加商品: 75cm超级米兔','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('50','1442386182','1','添加商品: 小米T恤 留声机','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('51','1442386258','1','添加商品: 小米足球米兔T恤 100%纯棉布料，吸汗透气亲肤经典版型，舒适圆领，印花不褪色','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('52','1442386711','1','编辑广告: 首页左侧广告','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('53','1442387027','1','编辑广告位置: 商店公告下广告','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('54','1442387046','1','编辑广告: 商店公告下广告','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('55','1442387417','1','编辑广告位置: 商店公告下广告','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('56','1442387723','1','删除广告: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('57','1442387757','1','添加广告: 商店公告下广告','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('58','1447172481','1','删除权限管理: bjgonghuo1','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('59','1447172485','1','删除权限管理: shhaigonghuo1','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('60','1447172513','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('61','1447172550','1','编辑商店设置: ','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('62','1447144504','1','还原数据库备份: 备份时间2015-11-10 16:32:34','127.0.0.1');");
E_D("replace into `ecs_admin_log` values('63','1463289999','1','还原数据库备份: 备份时间2015-11-11 01:28:16','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('64','1463290700','1','编辑商店设置: ','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('65','1463291115','1','编辑商店设置: ','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('66','1463291247','1','编辑商店设置: ','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('67','1463295259','1','编辑广告: 商店公告下广告','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('68','1463295404','1','编辑商店设置: ','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('69','1463295486','1','删除友情链接: ','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('70','1463295541','1','编辑友情链接: 微信商城三级分销设计','0.0.0.0');");
E_D("replace into `ecs_admin_log` values('71','1463295581','1','编辑友情链接: 慧通网络','0.0.0.0');");

require("../../inc/footer.php");
?>