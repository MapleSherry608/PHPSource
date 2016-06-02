<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ecs_weixin_keywords`;");
E_C("CREATE TABLE `ecs_weixin_keywords` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `contents` text NOT NULL,
  `pic` varchar(80) NOT NULL,
  `pic_tit` varchar(80) NOT NULL,
  `desc` text NOT NULL,
  `pic_url` varchar(80) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=119 DEFAULT CHARSET=utf8");
E_D("replace into `ecs_weixin_keywords` values('90','帮助','help','1','乐儿：亲，如果想买【商品信息】里没有。\r\n输入【XX】XX表示您想购买东西的关键字\r\n如果您更喜欢传统的网页购物，请点击<a href=\"http://www.leerfa.com\">触屏版购物</a>\r\n--------其他帮助-------\r\n输入【积分规则】查看积分获取规则\r\n','','','','','129','1');");
E_D("replace into `ecs_weixin_keywords` values('109','帮助中文','帮助','1','乐儿：亲，如果想买【商品信息】里没有。\r\n输入【XX】XX表示您想购买东西的关键字\r\n如果您更喜欢传统的网页购物，请点击<a href=\"http://www.leerfa.com\">触屏版购物</a>\r\n--------其他帮助-------\r\n输入【积分规则】查看积分获取规则\r\n','','','','','1','1');");
E_D("replace into `ecs_weixin_keywords` values('91','你好','你好','1','乐儿：您好，我是聚天地之灵气，集万物之精华……（此处略去3万字）【乐儿发官方唯一客服】乐儿，有什们可以帮您的吗？\r\n','','','','','10','1');");
E_D("replace into `ecs_weixin_keywords` values('100','图文消息测试','图文消息','2','','4.jpg','图文消息的测试标题','资料显示，华数集团是由杭州文广集团、浙江广电集团等投资设立的大型国有文化传媒产业集团。在新媒体产业，华数集团旗下控股的上市公司华数传媒控股股份有限公司拥有上百万小时的数字媒体内容库、数千万台互联网电视终端，新媒体全业务运营牌照。','http://tech.sina.com.cn/i/2014-04-08/18199305530.shtml','71','1');");
E_D("replace into `ecs_weixin_keywords` values('105','文本消息测试','文本消息','1','近年来，公开选拔和竞争上岗作为干部人事制度改革的重要举措，在拓宽选人视野，打破论资排辈等不少方面积极作用明显。“但走向极端就会出现问题，比如一些地方规定公开选拔和竞争上岗人员必须达到干部任用的多少比例，甚至进一步绝对化为‘凡提必竞’。”中央党校教授辛鸣说。','','','','','66','1');");
E_D("replace into `ecs_weixin_keywords` values('106','刮刮卡','刮刮卡','2','','','刮刮卡','刮刮卡','http://wy.leerfa.com/index.php?g=Wap&m=Guajiang&a=index&token=miltbm1399438728&t','8','1');");
E_D("replace into `ecs_weixin_keywords` values('107','公司最新公告','公司公告','2','','1399434076105576057.jpg','诚招学生商家','诚招学生商家，乐儿发官方网店诚招学生商家，无论你是卖衣服的还是卖鞋子的，我们在这里免费招募网店商家，免费提供你的商品宣传单。期待合作，创业不易，精诚互助！【想成为学生供货商，请输入：高山流水】','','18','1');");
E_D("replace into `ecs_weixin_keywords` values('108','订阅号活动','订阅号活动','1','参与活动，请先关注订阅号：leerfa-dy\r\n------进行中---------\r\n活动一：刮刮卡 每人一次哦，100张刮刮卡，刮完就没了哦!中奖率10%哦\r\n------即将开始---------\r\n活动二：砸金蛋 \r\n------即将开始---------\r\n活动三：幸运大专轮\r\n------即将开始---------\r\n活动四：微调查','','','','','7','1');");
E_D("replace into `ecs_weixin_keywords` values('110','聊天回复','聊天','1','乐儿：亲，您是要跟我聊天吗？这不好吧？我爸比(程序猿)跟我说：\"我是我们公司的唯一的客服，每个人都需要我的帮助，没时间跟亲聊天的呢！偷偷告诉亲呦，爸比说，如果我聊多了会显得爸比IQ很低的样子哦。\" 嘻嘻！','','','','','1','1');");
E_D("replace into `ecs_weixin_keywords` values('111','高山流水','高山流水','1','乐儿：觅知音。\r\n有缘人，您好！乐儿等您等好久勒！\r\n有缘人，您是想成为供货商，首先请确定您是学生呦！\r\n如果您是学生的话请填写注册一下信息哦\r\n请确保信息真实行，不然爸比（程序猿）要联系不到您的哦\r\n\r\n<a href=\"http://wy.leerfa.com/index.php?m=Index&a=reg\">立即填写</a>','','','','','4','1');");
E_D("replace into `ecs_weixin_keywords` values('113','官方淘宝店','淘宝','1','http://shop110762202.taobao.com\r\n由于“一槽不容二马” 微信与淘宝已成水火，亲，请复制链接用其他的浏览器打开。','','','','','7','1');");
E_D("replace into `ecs_weixin_keywords` values('118','下载','下载','1','安卓APP下载\r\nhttp://www.leerfa.com/download/leerfa_app.apk','','','','','1','1');");
E_D("replace into `ecs_weixin_keywords` values('114','淘宝店','淘','1','http://shop110762202.taobao.com\r\n由于“一槽不容二马” 微信与淘宝已成水火，亲，请复制链接用其他的浏览器打开。','','','','','3','1');");
E_D("replace into `ecs_weixin_keywords` values('115','淘','淘宝店','1','http://shop110762202.taobao.com\r\n由于“一槽不容二马” 微信与淘宝已成水火，亲，请复制链接用其他的浏览器打开。\r\nhttp://auction1.paipai.com/02383B26000000000401000034330569','','','','','1','1');");
E_D("replace into `ecs_weixin_keywords` values('117','APP下载','APP下载','1','APP下载\r\nhttp://www.leerfa.com/download/leerfa_app.apk','','','','','2','1');");

require("../../inc/footer.php");
?>