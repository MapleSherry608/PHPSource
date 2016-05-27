/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weixinpay

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-01-17 10:01:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sk_admin
-- ----------------------------
DROP TABLE IF EXISTS `sk_admin`;
CREATE TABLE `sk_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `intro` text NOT NULL,
  `user` varchar(50) NOT NULL DEFAULT '',
  `pwd` varchar(32) NOT NULL DEFAULT '',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_admin
-- ----------------------------

-- ----------------------------
-- Table structure for sk_order
-- ----------------------------
DROP TABLE IF EXISTS `sk_order`;
CREATE TABLE `sk_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `out_trade_no` char(255) DEFAULT NULL,
  `trade_no` char(255) DEFAULT '0',
  `pay_shop` char(255) DEFAULT NULL,
  `pay_mall` int(5) DEFAULT '0',
  `pay_type` char(255) DEFAULT NULL,
  `pay_price` int(10) DEFAULT '0' COMMENT '价格 单位分',
  `user_name` char(255) DEFAULT NULL,
  `user_tel` char(255) DEFAULT NULL,
  `user_message` char(255) DEFAULT NULL,
  `openid` char(255) DEFAULT NULL,
  `s_time` int(10) DEFAULT '0',
  `f_time` int(10) DEFAULT '0',
  `ok` int(1) DEFAULT '0' COMMENT '0未付 1已付',
  PRIMARY KEY (`id`),
  KEY `out_trade_no` (`out_trade_no`),
  KEY `trade_no` (`trade_no`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_order
-- ----------------------------

-- ----------------------------
-- Table structure for sk_pay
-- ----------------------------
DROP TABLE IF EXISTS `sk_pay`;
CREATE TABLE `sk_pay` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `val` text,
  `ok` int(2) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_pay
-- ----------------------------
INSERT INTO `sk_pay` VALUES ('1', '微信支付', 'weixin', 'a:4:{s:6:\"mch_id\";a:2:{s:4:\"name\";s:17:\"mch_id(商户号)\";s:3:\"val\";s:5:\"12102\";}s:5:\"AppId\";a:2:{s:4:\"name\";s:15:\"AppId(应用ID)\";s:3:\"val\";s:6:\"wxf71d\";}s:9:\"AppSecret\";a:2:{s:4:\"name\";s:23:\"AppSecret(应用密钥)\";s:3:\"val\";s:11:\"fecbbcf7f24\";}s:3:\"Key\";a:2:{s:4:\"name\";s:20:\"Key密钥(API密钥)\";s:3:\"val\";s:7:\"473c5e3\";}}', '1');
INSERT INTO `sk_pay` VALUES ('2', '支付宝支付', 'alipay', 'N;', '1');
INSERT INTO `sk_pay` VALUES ('3', '京东支付 (银行卡无须开网银)', 'jdpay', 'N;', '0');
INSERT INTO `sk_pay` VALUES ('4', '云支付 (含微信,支付宝,银行卡,信用卡,充值卡)', 'yunpay', 'N;', '0');

-- ----------------------------
-- Table structure for sk_wx_user
-- ----------------------------
DROP TABLE IF EXISTS `sk_wx_user`;
CREATE TABLE `sk_wx_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `score` int(10) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int(2) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `privilege` varchar(255) DEFAULT NULL,
  `unionid` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sk_wx_user
-- ----------------------------
