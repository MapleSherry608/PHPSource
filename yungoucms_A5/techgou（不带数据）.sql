-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-04-19 11:31:20
-- 服务器版本： 10.1.8-MariaDB
-- PHP Version: 5.3.29-upupw

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `go_admin`
--

CREATE TABLE IF NOT EXISTS `go_admin` (
  `uid` tinyint(3) unsigned NOT NULL,
  `mid` tinyint(3) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  `userpass` char(32) NOT NULL,
  `useremail` varchar(100) DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `logintime` int(10) unsigned DEFAULT NULL,
  `loginip` varchar(15) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `go_admin`
--

INSERT INTO `go_admin` (`uid`, `mid`, `username`, `userpass`, `useremail`, `addtime`, `logintime`, `loginip`) VALUES
(1, 0, 'techgou', '7077418c0bac9c976510143d5323ece8', NULL, NULL, 1461035769, '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `go_ad_area`
--

CREATE TABLE IF NOT EXISTS `go_ad_area` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `width` smallint(6) unsigned DEFAULT NULL,
  `height` smallint(6) unsigned DEFAULT NULL,
  `des` varchar(255) DEFAULT NULL,
  `checked` tinyint(1) DEFAULT '0' COMMENT '1表示通过'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='广告位';

--
-- 转存表中的数据 `go_ad_area`
--

INSERT INTO `go_ad_area` (`id`, `title`, `width`, `height`, `des`, `checked`) VALUES
(1, '首页750*60', 750, 60, '图片广告', 1),
(2, '&lt;div&gt;1&lt;/div&gt;', 750, 60, 'sd', 1);

-- --------------------------------------------------------

--
-- 表的结构 `go_ad_data`
--

CREATE TABLE IF NOT EXISTS `go_ad_data` (
  `id` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` char(10) DEFAULT NULL COMMENT 'code,text,img',
  `content` text,
  `checked` tinyint(1) DEFAULT '0' COMMENT '1表示通过',
  `addtime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='广告';

-- --------------------------------------------------------

--
-- 表的结构 `go_article`
--

CREATE TABLE IF NOT EXISTS `go_article` (
  `id` int(10) unsigned NOT NULL COMMENT '文章id',
  `cateid` char(30) NOT NULL COMMENT '文章父ID',
  `author` char(20) DEFAULT NULL,
  `title` char(100) NOT NULL COMMENT '标题',
  `title_style` varchar(100) DEFAULT NULL,
  `thumb` varchar(3) DEFAULT NULL,
  `picarr` text,
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` mediumtext COMMENT '内容',
  `hit` int(10) unsigned DEFAULT '0',
  `order` tinyint(3) unsigned DEFAULT NULL,
  `posttime` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  `url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_article`
--

INSERT INTO `go_article` (`id`, `cateid`, `author`, `title`, `title_style`, `thumb`, `picarr`, `keywords`, `description`, `content`, `hit`, `order`, `posttime`, `url`) VALUES
(1, '2', 'hll', '了解网站', '', '', 'a:2:{i:0;s:33:"photo/20130902/41484375056924.jpg";i:1;s:33:"photo/20130902/26578125056924.jpg";}', '', '', '<p>	</p><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">幸运1元购是一种新型的网购模式，只需1元就有可能买到一件商品。<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;">幸运1元</span>购把一件商品平分成若干“等份”出售，每份1元，当一件商品所有“等份”售出后抽出一名幸运者，该幸运者即可获得此商品。</p><h3 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">规则：</h3><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal; height: 10px;"><br/></p><p>1、每件商品参考市场价平分成相应“等份”，每份1元，1份对应1个云购码。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p>2、同一件商品可以购买多次或一次购买多份。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p>3、当一件商品所有“等份”全部售出后计算出“幸运码”，拥有“幸运码”者即可获得此商品。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p>4、幸运云购码计算方式：</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p style="padding: 0px 0px 0px 36px; margin-top: 0px; margin-bottom: 0px; text-indent: -1em;">1）取该商品最后购买时间前网站所有商品100条购买时间记录（限时揭晓商品取截止时间前网站所有商品100条购买时间记录）。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 1.6em;">2）时间按时、分、秒、毫秒依次排列组成一组数值。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; height: 10px;"><br/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 1.6em;">3）将这100组数值之和除以商品总需参与人次后取余数，余数加上10,000,001即为“幸运码”。</p><h3 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">流程：</h3><p><strong style="padding: 0px; margin: 0px;">1、挑选商品</strong></p><p style="padding: 0px; margin-bottom: 15px;">分类浏览或直接搜索商品，点击“立即幸运购”。</p><p><strong style="padding: 0px; margin: 0px;">2、支付1元</strong></p><p style="padding: 0px; margin-bottom: 10px;">通过在线支付平台，支付1元即购买1人次，获得1个“云购码”。同一件商品可购买多次或一次购买多份，购买的“云购码”越多，获得商品的几率越大。</p><p><strong style="padding: 0px; margin: 0px;">3、揭晓获得者</strong></p><p style="padding: 0px; margin-bottom: 15px;">当一件商品达到总参与人次，抽出1名商品获得者，幸运1元购网会通过手机短信或邮件通知您领取商品。</p><h3 style="padding: 0px 0px 0px 22px; margin: 0px; font-size: 14px;">注：</h3><p style="padding: 0px; margin-bottom: 10px; text-indent: 1.6em;">1）商品揭晓后您可登录&quot;我的幸运购&quot;查询详情，未获得商品的用户不会收到短信或邮件通知；</p><p style="padding: 0px; margin-bottom: 10px; text-indent: 1.6em;">2）商品揭晓后，请及时登录&quot;我的幸运购&quot;完善个人资料，以便我们能够准确无误地为您配送商品。</p><p style="padding: 0px; margin-bottom: 10px; text-indent: 1.6em;">3）所有已揭晓商品均不给予退款</p><p><strong style="padding: 0px; margin: 0px;">4、晒单分享</strong><br style="padding: 0px; margin: 0px;"/></p><p style="padding: 0px; margin-bottom: 0px;">晒出您收到的商品实物图片甚至您的靓照，说出您的云购心得，让大家一起分享您的快乐。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px;">在您收到商品后，您只需登录网站完成晒单，并通过审核，即可获得400-1500福分奖励。在您成功晒单后，您的晒单会出现在网站&quot;晒单分享&quot;区，与大家分享喜悦。</p><p><br/></p>', 1, 1, 1375862513, NULL),
(2, '2', 'hll', '常见问题', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p><br/></p><p><br/></p><h4 class="mat0" style="padding: 0px; margin: 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">1、怎样查看我参与的商品有没有中奖？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">每件商品开奖结果公布之后，登录网站，进入&quot;我的用户中心&quot;，在&quot;我中奖的商品&quot;中即可查询中奖情况。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">2、我获得了商品，我还需要支付其他费用吗？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">不需要支付其他任何费用。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">3、当我获得商品以后我该做什么？</h4><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">在您获得商品后您会收到站内信、短信和电子邮件的通知。在这之后，您必须在“我的<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px;">用户</span>中心”正确填写、真实的收货地址，完善或确认您的个人信息。我们会在您获得商品后3个工作日内通过电话方式与您取得联系。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">4、商品是正品吗？怎么保证？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">我们承诺，所有商品100%正品，可享受厂家所提供的全国联保服务，享受商品的保修、换货和退货的义务（国家三包政策）。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">5、如何晒单分享？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">在您收到商品后，登录网站，进入&quot;我的<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px;">用户</span>中心&quot;，在“晒单分享”区发布晒单信息，通过审核后，您还可获得400-1500福分奖励。在您成功晒单后，您的晒单会出现在网站“晒单分享”区，与大家分享喜悦。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">6、我收到的商品可以换货或者退货吗？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">非质量问题，不在三包范围内，不给予退换货。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">7、参与1元云购需要注意什么？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">请务必正确填写真实有效的联系电话、收货地址以便在您中奖时能及时与您取得联系。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">8、网上银行充值未及时到帐怎么办？</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">网上支付未及时到帐可能有以下几个原因造成：</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">第一，由于网速或者支付接口等问题，支付数据没有及时传送到支付系统造成的；</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">第二，网速过慢，数据传输超时，使银行后台支付信息不能成功对接，导致银行交易成功而支付后台显示失败；</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">第三，在网上支付如果使用某些防火墙软件，有时会屏蔽银行接口的弹出窗口，这时会造成在银行那边被扣费，但在我们网站上显示尚没支付。但请您放心，每天我们都会根据银行系统的帐务明细清单对前一天的订单进行逐笔核对，如遇问题订单，我们会做手工添加。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">建议反馈</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">如您对我们“帮助中心”的说明有任何疑问或建议请<a href="http://help.1yyg.com/htm-contactus.html" style="padding: 0px; margin: 0px; text-decoration: none; color: rgb(102, 102, 102); word-break: break-all; outline: none;">告诉我们</a></p><p><br/></p>', 0, 3, 1375862591, NULL),
(3, '2', 'hll', '服务协议', '', '', 'a:0:{}', '', '', '<p>	</p><p style="margin-top:5px;margin-bottom:5px"><span style="font-family: Arial">&nbsp;</span></p><p style=";margin-bottom:0;text-indent:32px;line-height:30px"><span style="font-size:14px;color:#666666">欢迎您访问并使用充满互动乐趣的购物网站</span><span style="font-size:14px;font-family:Tahoma;color:#666666">-</span><span style="font-size:14px;color:#666666">科技狗云购，作为为用户提供全新、有趣购物模式的互联网公司，科技狗云购通过在线网站为您提供各项相关服务。当使用科技狗云购的各项具体服务时，您和科技狗云购都将受到本服务协议所产生的制约，科技狗云购会不断推出新的服务，因此所有服务都将受此服务条款的制约。请您在注册前务必认真阅读此服务协议的内容并确认，如有任何疑问，应向科技狗云购咨询。一旦您确认本服务协议后，本服务协议即在用户和科技狗云购之间产生法律效力。您在注册过程中点击</span><span style="font-size:14px;font-family:Tahoma;color:#666666">“</span><span style="font-size:14px;color:#666666">同意以下条款提交注册信息</span><span style="font-size:14px;font-family:Tahoma;color:#666666">”</span><span style="font-size:14px;color:#666666">按钮即表示您完全接受本协议中的全部条款。随后按照页面给予的提示完成全部的注册步骤。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">科技狗云购将可能不定期的修改本服务协议的有关条款，并保留在必要时对此协议中的所有条款进行随时修改的权利。一旦协议内容有所修改，科技狗云购将会在网站重要页面或社区的醒目位置第一时间给予通知。如果您继续使用科技狗云购的服务，则视为您受协议的改动内容。如果不同意本站对协议内容所做的修改，科技狗云购会及时取消您的服务使用权限。本站保留随时修改或中断服务而不需告知用户的权利。本站行使修改或中断服务的权利，不需对用户或第三方负责。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">一、用户注册</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、用户注册是指用户登录科技狗云购，按要求填写相关信息并确认同意本服务协议的过程。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、科技狗云购用户必须是具有完全民事行为能力的自然人，或者是具有合法经营资格的实体组织。无民事行为能力人、限制民事行为能力人以及无经营或特定经营资格的组织不得注册为科技狗云购用户或超过其民事权利或行为能力范围与科技狗云购进行交易，如与科技狗云购进行交易的，则服务协议自始无效，科技狗云购有权立即停止与该用户的交易、注销该用户账户，并有权要求其承担相应法律责任。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">二、用户的帐号，密码和安全性</span></h4><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">用户一旦注册成功，成为本站的合法用户。用户将对用户名和密码安全负全部责任。此外，每个用户都要对以其用户名进行的所有活动和事件负全责。用户若发现任何非法使用用户帐号或存在安全漏洞的情况，请立即通告本站。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">三、科技狗云购原则</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、释义</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">幸运购码：指科技狗云购网用户成功参与幸运购之后获得的随机分配编码。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">幸运幸运购码：指某件商品所有编码售出后，科技狗云购网根据规则计算出的一个编码，持有该编码的用户即可获得该商品。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、科技狗云购网承诺遵循以下的原则运营网站，确保所有用户在科技狗云购网中享受同等的权利与义务。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">平等原则：用户和科技狗云购在交易过程中具有同等的法律地位。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">自由原则：用户享有自愿向科技狗云购参与购买商品的权利，任何人不得非法干预。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">公平原则：用户和科技狗云购行使权利、履行义务应当遵循公平原则。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">诚实信用原则：用户和科技狗云购行使权利、履行义务应当遵循诚实信用原则。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">履行义务原则：用户向科技狗云购参与商品分享购买时，用户和科技狗云购皆有有义务根据本服务协议的约定完成该等交易（法律或本协议禁止的交易除外）</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、用户知悉，除本协议另有约定外，用户无论是否获得商品，参与幸运购的资金均用于帮助他人，不能退回；用户完全了解参与科技狗云购存在的风险，科技狗云购网无法保证用户参与幸运购一定会获得商品。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">四、用户的权利和义务</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、用户有权拥有其在科技狗云购的用户名及密码，并用该用户名和密码登录科技狗云购参与商品购买。用户不得以任何形式转让或授权他人使用自己的科技狗云购用户名。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、用户有权根据本协议的规定以及科技狗云购网站上发布的相关规则在科技狗云购上查询商品信息、发表使用体验、参与商品讨论、邀请关注好友、上传商品图片、参加科技狗云购的有关活动，以及享受科技狗云购提供的其它信息服务</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、用户有义务在注册时提供自己的真实资料，并保证诸如电子邮件地址、联系电话、联系地址、邮政编码等内容的有效性及真实性，保证科技狗云购可以通过上述联系方式与用户本人进行联系。同时，用户也有义务在相关资料发生变更时及时更新有关注册资料。用户保证不以他人资料在科技狗云购进行注册和参与商品分享购买。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">、用户应当保证在科技狗云购参与商品分享购买时遵守诚实信用原则，不扰乱网上交易的正常秩序。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">5</span><span style="font-size:14px;color:#666666">、用户在成为科技狗云购的会员后，可按科技狗云购的福分规则享受福分获得。累积福分可用于福分商城中的相应福分商品兑换。福分规则连同与该规则相关的条款和条件，构成用户与科技狗云购之间的完整协议。接受本协议，即表明接受福分规则中的条款和条件。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">6</span><span style="font-size:14px;color:#666666">、用户享有言论自由权利；并拥有适度修改、删除自己发表的文章的权利用户不得在科技狗云购发表包含以下内容的言论：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1) </span><span style="font-size:14px;color:#666666">反对宪法所确定的基本原则，煽动、抗拒、破坏宪法和法律、行政法规实施的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2) </span><span style="font-size:14px;color:#666666">煽动颠覆国家政权，推翻社会主义制度，煽动、分裂国家，破坏国家统一的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3) </span><span style="font-size:14px;color:#666666">损害国家荣誉和利益的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4) </span><span style="font-size:14px;color:#666666">煽动民族仇恨、民族歧视，破坏民族团结的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">5) </span><span style="font-size:14px;color:#666666">任何包含对种族、性别、宗教、地域内容等歧视的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">6) </span><span style="font-size:14px;color:#666666">捏造或者歪曲事实，散布谣言，扰乱社会秩序的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">7) </span><span style="font-size:14px;color:#666666">宣扬封建迷信、邪教、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">8) </span><span style="font-size:14px;color:#666666">公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">9) </span><span style="font-size:14px;color:#666666">损害国家机关信誉的；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">10) </span><span style="font-size:14px;color:#666666">其他违反宪法和法律行政法规的。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">7</span><span style="font-size:14px;color:#666666">、用户在发表使用体验、讨论图片等，除遵守本条款外，还应遵守该讨论区的相关规定。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">8</span><span style="font-size:14px;color:#666666">、未经科技狗云购同意，禁止用户在网站发布任何形式的广告。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">五、科技狗云购的权利和义务</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、科技狗云购有义务在现有技术上维护整个网上交易平台的正常运行，并努力提升和改进技术，使用户网上交易活动得以顺利进行；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、对用户在注册和使用科技狗云购网上交易平台中所遇到的与交易或注册有关的问题及反映的情况，科技狗云购应及时作出回复；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、对于用户在科技狗云购网站上作出下列行为的，科技狗云购有权作出删除相关信息、终止提供服务等处理，而无须征得用户的同意：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1) </span><span style="font-size:14px;color:#666666">科技狗云购有权对用户的注册信息及购买行为进行查阅，发现注册信息或购买行为中存在任何问题的，有权向用户发出询问及要求改正的通知或者作出删除等处理；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2) </span><span style="font-size:14px;color:#666666">用户违反本协议规定或有违反法律法规和地方规章的行为的，科技狗云购有权停止传输并删除其信息，禁止用户发言，注销用户账户并按照相关法律规定向相关主管部门进行披露。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3) </span><span style="font-size:14px;color:#666666">对于用户在科技狗云购进行的下列行为，科技狗云购有权对用户采取删除其信息、禁止用户发言、注销用户账户等限制性措施：包括发布或以电子邮件或以其他方式传送存在恶意、虚假和侵犯他人人身财产权利内容的信息，进行与分享购物无关或不是以分享购物为目的的活动，恶意注册、签到、评论等方式试图扰乱正常购物秩序，将有关干扰、破坏或限制任何计算机软件、硬件或通讯设备功能的软件病毒或其他计算机代码、档案和程序之资料，加以上载、发布、发送电子邮件或以其他方式传送，干扰或破坏科技狗云购网站和服务或与科技狗云购网站和服务相连的服务器和网络，或发布其他违反公共利益或可能严重损害科技狗云购和其它用户合法利益的信息。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">、用户在此免费授予科技狗云购永久性的独家使用权</span><span style="font-size:14px;font-family:Tahoma;color:#666666">(</span><span style="font-size:14px;color:#666666">并有权对该权利进行再授权</span><span style="font-size:14px;font-family:Tahoma;color:#666666">)</span><span style="font-size:14px;color:#666666">，使科技狗云购有权在全球范围内</span><span style="font-size:14px;font-family:Tahoma;color:#666666">(</span><span style="font-size:14px;color:#666666">全部或部分地</span><span style="font-size:14px;font-family:Tahoma;color:#666666">)</span><span style="font-size:14px;color:#666666">使用、复制、修订、改写、发布、翻译和展示用户公示于科技狗云购网站的各类信息，或制作其派生作品，和或以现在已知或日后开发的任何形式、媒体或技术，将上述信息纳入其它作品内。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">5</span><span style="font-size:14px;color:#666666">、对于科技狗云购网络平台已上架商品，科技狗云购有权根据市场变动修改商品价格，而无需提前通知客户。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">6</span><span style="font-size:14px;color:#666666">、科技狗云购分享购物模式，秉着双方自愿原则，分享购物存在风险，科技狗云购不对抽取的</span><span style="font-size:14px;font-family:Tahoma;color:#666666">“</span><span style="font-size:14px;color:#666666">幸运编号</span><span style="font-size:14px;font-family:Tahoma;color:#666666">”</span><span style="font-size:14px;color:#666666">结果承担责任，望所有用户谨慎参与。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">7</span><span style="font-size:14px;color:#666666">、</span><span style="font-size:14px;font-family:Tahoma;color:#666666">90</span><span style="font-size:14px;color:#666666">天未达到</span><span style="font-size:14px;font-family:Tahoma;color:#666666">“</span><span style="font-size:14px;color:#666666">总需参与人次</span><span style="font-size:14px;font-family:Tahoma;color:#666666">”</span><span style="font-size:14px;color:#666666">的商品，用户可通过客服申请退款，所退款项将在</span><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">个工作日内，退还至幸运购账户中。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">六、配送及费用</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">科技狗云购将会把产品送到您所指定的送货地址。全国免费配送（港澳台地区除外）。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">请清楚准确地填写您的真实姓名、送货地址及联系方式。因如下情况造成配送延迟或无法配送等，本站将不承担责任：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、客户提供错误信息和不详细的地址；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、货物送达无人签收，由此造成的重复配送所产生的费用及相关的后果。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、不可抗力，例如：自然灾害、交通戒严、突发战争等。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">七、商品缺货规则</span></h4><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">由于市场变化及各种以合理商业努力难以控制的因素的影响，科技狗云购网无法承诺用户所获得的商品都会有货；用户获得的商品或服务如果发生缺货，协议双方均无权取消该交易，科技狗云购网将通过有效方式通知用户进行换货，用户可选择换购本商城同等价位的商品（一件或多件），或选择补差价换购高价位商品。</span></p><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">科技狗云购网可对即将上市的商品或服务进行预售登记，科技狗云购网会在商品或者服务正式上市之后尽最大努力在最快时间内给商品获得者安排发货，预售登记并不做交易处理，不构成要约。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">八、责任限制</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、用户理解并同意，在使用科技狗云购网服务的过程中，可能会遇到不可抗力等风险因素使科技狗云购网服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，科技狗云购网将努力在第一时间与相关单位配合，及时进行修复，但是由此给用户造成的损失，科技狗云购网将在法律允许的范围内免责。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、用户理解并同意，科技狗云购网不能随时预见和防范法律、技术以及其他不可控的风险，对以下情形之一导致的服务中断或受阻，科技狗云购网不承担责任：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">）大规模病毒、木马或其他恶意程序、黑客攻击的破坏；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">）用户或科技狗云购网的电脑软件、系统、硬件和通信线路出现故障；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">）用户操作不当；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">）用户通过非科技狗云购网授权的方式使用服务；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">5</span><span style="font-size:14px;color:#666666">）政府管制等原因可能导致的服务中断、数据丢失以及其他的损失和风险。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">（</span><span style="font-size:14px;font-family:Tahoma;color:#666666">6</span><span style="font-size:14px;color:#666666">）其他科技狗云购网无法控制或合理预见的情形。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、在法律法规所允许的限度内，因使用科技狗云购服务而引起的任何损害或经济损失，科技狗云购承担的全部责任均不超过用户所购买的与该索赔有关的商品价格。这些责任限制条款将在法律所允许的最大限度内适用，并在用户资格被撤销或终止后仍继续有效。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">九、网络服务内容的所有权</span></h4><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">本站定义的网络服务内容包括：文字、软件、声音、图片、录象、图表、广告中的全部内容；电子邮件的全部内容；本站为用户提供的其他信息。所有这些内容受版权、商标、标签和其它财产所有权法律的保护。所以，用户只能在本站和广告商授权下才能使用这些内容，而不能擅自复制、再造这些内容、或创造与内容有关的派生产品。本站所有的文章版权归原文作者和本站共同所有，任何人需要转载本站的文章，必须征得原文作者或本站授权。</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">十、用户隐私制度</span></h4><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">我们不会向任何第三方提供，出售，出租，分享和交易用户的个人信息。当在以下情况下，用户的个人信息将部分或全部被善意披露：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、经用户同意，向第三方披露；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、如用户是合资格的知识产权投诉人并已提起投诉，应被投诉人要求，向被投诉人披露，以便双方处理可能的权利纠纷；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、根据法律的有关规定，或者行政或司法机构的要求，向第三方或者行政、司法机构披露；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">、如果用户出现违反中国有关法律或者网站政策的情况，需要向第三方披露；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">5</span><span style="font-size:14px;color:#666666">、为提供你所要求的产品和服务，而必须和第三方分享用户的个人信息；</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">6</span><span style="font-size:14px;color:#666666">、其它本站根据法律或者网站政策认为合适的披露。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><h4 style="margin: 30px 0 0;line-height: 30px"><span style="font-size:14px;color:#666666">十一、法律管辖和适用</span></h4><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、本协议的订立、执行和解释及争议的解决均应适用中国法律。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、如发生本站服务条款与中国法律相抵触时，则这些条款将完全按法律规定重新解释，而其它合法条款则依旧保持对用户产生法律效力和影响。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、本协议的规定是可分割的，如本协议任何规定被裁定为无效或不可执行，该规定可被删除而其余条款应予以执行。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">、如双方就本协议内容或其执行发生任何争议，双方应尽力友好协商解决；协商不成时，任何一方均可向本站所在地的人民法院提起诉讼。</span></p><p>&nbsp;</p><p><br/></p>', 0, 0, 1375862644, NULL),
(4, '3', 'hll', '购保障体系', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p><br/></p><h4 class="mat0" style="padding: 0px; margin: 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">100%正品保证</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;"><span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>精心挑选优质服务品牌商家，保障全场商品100%品牌正品。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">100%公平公正</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">整个过程是完全透明，您可以随时查看每件商品参与人数，参与次数，参与名单及中奖信息等记录。</p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">全国免费快递</h4><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;"><span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>承诺全场所有商品全国免费快递。（港澳台地区除外）</p><p><br/></p>', 0, 0, 1375862690, NULL),
(5, '3', 'hll', '正品保障', '', '', 'a:0:{}', '', '', '<p>	</p><p style="margin-top:5px;margin-bottom:5px"><span style="font-family: Arial"></span></p><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">科技狗云购严格控制供应渠道，全部商品均从品牌官方以及品牌经销商直接采购供货，并取得品牌官方网络销售授权书，如果您认为幸运购的商品是假货，并能提供国家相关质检机构的证明文件，经确认后，在返还商品金额的同时并提供假一赔十服务保障。为了保障您的利益，对幸运购的商品，做如下说明：</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">1</span><span style="font-size:14px;color:#666666">、科技狗云购对所有商品均保证正品行货，正规渠道发货，所有商品都可以享受生产厂家的全国联保服务，按照国家三包政策，针对所售商品履行保修、换货和退货的义务。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">2</span><span style="font-size:14px;color:#666666">、出现国家三包所规定的功能性故障时，经由生产厂家指定或特约售后服务中心检测确认故障属实，您可以选择换货或者维修；超过</span><span style="font-size:14px;font-family:Tahoma;color:#666666">15</span><span style="font-size:14px;color:#666666">日且在保修期内，您只能在保修期内享受免费维修服务。为了不耽误您使用，缩短故障商品的维修时间，我们建议您直接联系生产厂家售后服务中心进行处理。您也可以直接在商品的保修卡中查找该商品对应的全国各地生产厂家售后服务中心联系处理。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">3</span><span style="font-size:14px;color:#666666">、科技狗云购真诚提醒广大幸运者在您收到商品的时候，请尽量亲自签收并当面拆箱验货，如果有问题</span><span style="font-size:14px;font-family:Tahoma;color:#666666">(</span><span style="font-size:14px;color:#666666">运输途中的损坏</span><span style="font-size:14px;font-family:Tahoma;color:#666666">)</span><span style="font-size:14px;color:#666666">请不要签收</span><span style="font-size:14px;font-family:Tahoma;color:#666666">!</span><span style="font-size:14px;color:#666666">与快递员交涉，拒签，退回</span><span style="font-size:14px;font-family:Tahoma;color:#666666">!</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">4</span><span style="font-size:14px;color:#666666">、在收到商品后发现有质量问题，请您不要私自处理，妥善保留好原包装，第一时间联系科技狗云购客服人员，由科技狗云购同发货商城协商在</span><span style="font-size:14px;font-family:Tahoma;color:#666666">48</span><span style="font-size:14px;color:#666666">小时内解决。如有破损或丢失，我们将无法为您办理退货。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">如对协商处理结果存在异议，请您自行到当地生产厂家售后服务中心进行检测，并开据正规检测报告（对于有些生产厂家售后服务中心无法提供检测报告的，需提供维修检验单据），如果检测报告确认属于质量问题，然后将检测报告、问题商品及完整包装附件，一并返还发货商城办理换货手续，产生的相关费用由科技狗云购追究相关责任方承担。</span></p><p style="margin: 0 0 0;line-height: 30px"><span style="font-size:14px;font-family:Tahoma;color:#666666">&nbsp;</span></p><p style="margin: 0 0 0;text-indent: 32px;line-height: 30px"><span style="font-size:14px;color:#666666">科技狗云购上的电子产品及配件因为生成工艺或仓储物流原因，可能会存在收到或使用过程中出现故障的几率，科技狗云购不能保证所有的商品都没有故障，但我们保证所售商品都是全新正品行货，能够提供正规的售后保障。我们保证商品的正规进货渠道和质量，如果您对收到的商品质量表示怀疑，请提供生产厂家或官方出具的书面鉴定，我们会按照国家法律规定予以处理。但对于任何欺诈性行为，科技狗云购将保留依法追究法律责任的权利。本规则最终解释权由科技狗云购所有。</span></p><p>&nbsp;</p><p><br/></p>', 0, 0, 1375862702, NULL),
(6, '3', 'hll', '安全支付', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p><span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;"><span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>严格遵循网络购物的安全准则，通过支付宝、财付通等安全度高的支付方式，充分保证您在线支付的安全性。</span></p>', 0, 0, 1375862712, NULL),
(7, '4', 'hll', '商品配送', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">在您获得商品后，<span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>将在第一时间内免费为你寄出，一般采用签约快递，均为服务好，覆盖网点非常广泛的全国性快递公司或者邮政的EMS，以最大限度保证商品安全。如快递公司无法达到的地方，则使用邮政EMS为您寄送商品。</p><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">若遇商品暂时缺货或者是有其他方面的问题，<span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>客服将会及时与您沟通处理。</p><p><br/></p>', 0, 0, 1375862725, NULL),
(8, '4', 'hll', '配送费用', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p><span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px;">所有商品全国免费配送。（港澳地区除外）</span></p>', 0, 0, 1375862737, NULL),
(9, '4', 'hll', '商品验货与签收', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p class="bottom-space16px" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">1、您在签收商品时请慎重，请尽量不要让人代签，并务必先仔细检查商品（外包装是否被开封、商品是否破损、配件是否缺失、功能是否正常），确保无误后再签收，以免产生不必要的纠纷。若有任何疑问，请及时拨打客服电话反馈信息。若因用户未仔细检查商品即签收后产生的纠纷，<span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>概不负责，仅承担协调处理的义务。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal; height: 10px;"><br/></p><p class="bottom-space16px" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">2、若因您的地址填写错误、联系方式填写有误等情况造成商品无法完成投递或被退回，所产生的额外费用及后果由用户负责。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal; height: 10px;"><br/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">3、如因不可抗拒的自然原因（地震、洪水等等）所造成的商品配送延迟，<span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>不承担责任。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal; height: 10px;"><br/></p><h4 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">温馨提醒</h4><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">若您或您的委托人已签收，则说明订单商品正确无误且不存在影响使用的因素，<span style="font-size:14px;font-family:宋体;color:#666666">科技狗云购</span>有权不受理因包装或商品破损、商品错漏发、商品表面质量问题、商品附带品及赠品少发为由的换货申请。</p><p><br/></p>', 0, 0, 1375862747, NULL),
(10, '4', 'hll', '长时间未收到商品', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">长时间未收到商品可能出现的问题：</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">1、请您确保您的收货地址、邮编、电话、Email地址等各项信息的准确性，以便商品及时、准确地发出。</p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">2、配送过程中如果我们联络您的时间超过7天未得到回复，此商品将被默认为您已经放弃。</p><p><br/></p>', 0, 0, 1375862760, NULL),
(13, '2', 'hll', '联系我们', '', '', 'a:0:{}', '', '', '<p>	</p><p><span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px;">商务合作</span><br/></p><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;"><span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;">幸运1元购</span>拥有国内庞大的消费群体及专业高效的电子商务平台，诚意邀请各品牌供应商与我们达成商务合作，共同创造中国电子商务的美好明天。</p><h3 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">市场推广</h3><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">随着<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;">幸运1元购</span>发展壮大以及全国各地市场的开拓，欢迎拥有市场推广、广告合作资源的您与我们携手共进，共同发展。 携手共进。</p><h3 style="padding: 0px; margin: 30px 0px 0px; font-size: 14px; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; line-height: 30px; white-space: normal;">媒体关注</h3><p class="textindent" style="padding: 0px; margin-top: 0px; margin-bottom: 0px; text-indent: 2em; color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; white-space: normal;">随着<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;">幸运1元购</span>的发展，欢迎各类媒体前来沟通指导，同时欢迎各类内容合作策划传播，你们的关注和支持，采访以及报道，将成为<span style="color: rgb(102, 102, 102); font-family: Tahoma, Arial, 宋体, Helvetica, sans-serif; font-size: 14px; line-height: 30px; text-indent: 28px;">幸运1元购</span>成长历程不可或缺的一部分。</p><p><br/></p>', 61, 1, 1408798466, NULL),
(14, '16', 'hll', '购物公益', '', '', 'a:0:{}', '', '', '<p>	</p><p><br/></p><p>一、幸运基金是幸运1元购创始人发起成立的以公益事业为主要方向的爱心基金。幸运基金本着“我为人人，人人为我”的社会责任，向需要帮助的困难人们提供爱心捐助。</p><p><br/></p><p>二、每位在幸运1元购进行分享购物的朋友，您的每次参与都将是为我们的公益事业做出一份贡献。当您每参与1人次幸运购，将由1元云购出资为幸运基金筹款0.01元，所筹款项将全部用于幸运基金。</p><p><br/></p><p>三、幸运基金将会以第1种途径或第2种途径进行使用：<br/>1、幸运1元购全体员工将组织向身边的公益事业进行捐赠与关怀活动。活动内容包括：资金、所需用品以及探望与协助等，每次捐赠与关怀活动结束后幸运基金将公布活动详情以及基金详细使用报告。<br/>2、幸运基金通过腾讯公益或壹基金等公益组织进行爱心捐赠。</p><p><br/></p><p>四、包括云购基金的捐赠活动，我们不定期开展内部全体员工对身边更多公益事业或实时公益事业进行爱心捐赠的社会活动。</p><p>&nbsp; &nbsp; 我们还将不定期邀请幸运者参与并见证我们的基金社会活动，共同为我们的社会责任付出一份爱心与力量。当活动启动前我们会将活动进行公告，您可自愿或自行组织参与，组成云购网大家庭，共同开启活动之行。凡参与社会活动的幸运者均能获得幸运1元购为您精心准备的公益爱心礼品一份。</p><p><br/></p>', 11, 1, 1414361199, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `go_brand`
--

CREATE TABLE IF NOT EXISTS `go_brand` (
  `id` int(10) unsigned NOT NULL,
  `cateid` varchar(255) DEFAULT NULL COMMENT '所属栏目ID',
  `status` varchar(255) DEFAULT 'Y' COMMENT '显示隐藏',
  `name` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT '1',
  `thumb` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='品牌表';

--
-- 转存表中的数据 `go_brand`
--

INSERT INTO `go_brand` (`id`, `cateid`, `status`, `name`, `order`, `thumb`, `url`) VALUES
(1, '13,21', 'Y', '联想', 16, NULL, NULL),
(2, '5,17', 'Y', '诺基亚', 1, NULL, NULL),
(3, '5,17,19,13,21', 'Y', '苹果', 1, NULL, NULL),
(4, '5,17', 'Y', '三星', 14, NULL, NULL),
(6, '5,19,17,13,21', 'Y', '小米', 1, NULL, NULL),
(8, '5', 'Y', 'HTC', 15, NULL, NULL),
(11, '6', 'Y', '三星', 1, NULL, NULL),
(13, '5,18', 'Y', '尼康', 3, NULL, NULL),
(14, '5,17', 'Y', '魅族(MEIZU)', 1, NULL, NULL),
(15, '5,19,6,23,24', 'Y', '飞利浦', 1, NULL, NULL),
(16, '5,17,19', 'Y', '华为（HUAWEI）', 1, NULL, NULL),
(17, '18,5', 'Y', '佳能（Canon）', 1, NULL, NULL),
(18, '5,19', 'Y', '捷波朗（Jabra）', 1, NULL, NULL),
(19, '5,19', 'Y', '亿觅（emie）', 1, NULL, NULL),
(20, '19,5', 'Y', '铁三角(audio-technica) ', 1, NULL, NULL),
(21, '13,20', 'Y', '闪迪（SanDisk）', 1, NULL, NULL),
(22, '13,20', 'Y', '其他', 1, NULL, NULL),
(23, '13,21', 'Y', '微软（Microsoft）', 1, NULL, NULL),
(24, '13,20', 'Y', '金士顿（Kingston）', 1, NULL, NULL),
(25, '13,22,29,14', 'Y', '360', 1, NULL, NULL),
(26, '13,20', 'Y', '希捷（Seagate）', 1, NULL, NULL),
(27, '20,13', 'Y', '罗技（Logitech）', 1, NULL, NULL),
(28, '24,6', 'Y', '史努比（Snoopy）', 1, NULL, NULL),
(29, '24,6', 'Y', '美的（Midea）', 1, NULL, NULL),
(30, '6,24', 'Y', '先锋（Singfun）', 1, NULL, NULL),
(31, '24,6,12,27', 'Y', '奔腾（POVOS）', 1, NULL, NULL),
(32, '24,6', 'Y', '苏泊尔（Supor）', 1, NULL, NULL),
(33, '24,6', 'Y', '公牛（BULL）', 1, NULL, NULL),
(34, '12,27', 'Y', '飞科（FLYCO）', 1, NULL, NULL),
(35, '24,6', 'Y', '小熊（Bear）', 1, NULL, NULL),
(36, '6,24', 'Y', '九阳（Joyoung ）', 1, NULL, NULL),
(37, '12,27', 'Y', '清扬（CLEAR）', 1, NULL, NULL),
(38, '12,27', 'Y', '曼秀雷敦（Mentholatum）', 1, NULL, NULL),
(39, '12,25', 'Y', '碧欧泉（Biotherm）', 1, NULL, NULL),
(40, '26,12', 'Y', '兰蔻（Lancome）', 1, NULL, NULL),
(41, '12,25', 'Y', '伊丽莎白雅顿', 1, NULL, NULL),
(42, '12,26,25', 'Y', '欧莱雅（LOREAL ）', 1, NULL, NULL),
(43, '14,28', 'Y', '永恒印记', 1, NULL, NULL),
(44, '29,14', 'Y', '天梭（Tissot）', 1, NULL, NULL),
(45, '14,28', 'Y', '中国农业银行', 1, NULL, NULL),
(46, '15,30', 'Y', '阿曼达', 1, NULL, NULL),
(47, '15,30', 'Y', '嘉诗图', 1, NULL, NULL),
(48, '15,30', 'Y', '茅台（MOUTAI）', 1, NULL, NULL),
(49, '15,30', 'Y', '瑞兹德比纳', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `go_caches`
--

CREATE TABLE IF NOT EXISTS `go_caches` (
  `id` int(11) unsigned NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_caches`
--

INSERT INTO `go_caches` (`id`, `key`, `value`) VALUES
(1, 'member_name_key', 'admin,administrator,科技狗云购官方'),
(2, 'shopcodes_table', '1'),
(3, 'goods_count_num', '34617'),
(4, 'template_mobile_reg', '你好,如非本人操作，可不用理会！你的注册验证码是:000000'),
(5, 'template_mobile_shop', '恭喜你科技狗云购用户！您在科技狗云购网购买的商品已揭晓,获得的幸运码为：00000000 请登陆网站查看详情！请尽快联系管理员发货！'),
(6, 'template_email_reg', '<table width="600" cellpadding="0" cellspacing="0" style="border: #dddddd 1px solid; padding: 20px 0;">\n<tbody>\n<tr>\n<td>\n<table width="100%" align="center" cellpadding="0" cellspacing="0" style="border-bottom: #ff6600 2px solid; padding-bottom: 12px;">\n<tbody>\n<tr>\n<td style="line-height:22px; padding-left:20px;">\n<a href="http://www.techgou.com" target="_blank" style=" font-size:32px;color:#ff7700; text-decoration:none;"><b>科技狗云购</b></a></td>\n<td align="right" style="font-size: 12px; padding-right: 20px; padding-top: 30px;">\n<a href="http://www.techgou.com" target="_blank" style="color: #2AF; text-decoration: none;">首页</a>\n<b style="width: 1px; height: 10px; vertical-align: -1px; font-size: 1px; background: #CACACA; display: inline-block; margin: 0 5px;"></b>\n<a href="http://www.techgou.com?/member/home" target="_blank" style="color: #22aaff; text-decoration: none;">我的科技狗云购</a>\n<b style="width: 1px; height: 10px; vertical-align: -1px; font-size: 1px; background: #CACACA; display: inline-block; margin: 0 5px;"></b>\n<a href="http://www.techgou.com?/help/1" target="_blank" style="color: #22aaff; text-decoration: none;">帮助中心</a></td>\n</tr>\n</tbody>\n</table>\n<table width="100%" align="center" cellpadding="0" cellspacing="0" style="padding: 0 20px;">\n<tbody>\n<tr>\n<td style="font-size: 14px; color: #333333; height: 40px; line-height: 40px; padding-top: 10px;">\n<b style="color: #333333; font-family: Arial;"> </b></td>\n</tr>\n<tr>\n<td style="font-size: 12px; color: #333333; line-height: 22px;">\n<p style="text-indent: 2em; padding: 0; margin: 0;">亲爱的用户您好！感谢您注册科技狗云购。</p></td>\n</tr>\n<tr>\n<td style="font-size: 12px; color: #333333; line-height: 22px;">\n<p style="text-indent: 2em; padding: 0; margin: 0;">请在24小时内激活注册邮件，点击连接激活邮件：</p></td>\n</tr>\n<tr>\n</tr>\n<tr>\n<td width="525" style="font-size: 12px; padding-top: 5px; word-break: break-all; word-wrap: break-word;">\n<a href="#" target="_blank" style="font-family: Arial; color: #22aaff;">{地址}</a></td>\n</tr>\n</tbody>\n</table>\n<table width="100%" align="center" cellpadding="0" cellspacing="0" style="margin-top: 60px;">\n<tbody>\n<tr>\n<td style="font-size: 12px; color: #777777; line-height: 22px; border-bottom: #22aaff 2px solid; padding-bottom: 8px; padding-left: 20px;">此邮件由系统自动发出，请勿回复！</td>\n</tr>\n<tr>\n<td style="font-size: 12px; color: #333333; line-height: 22px; padding: 8px 20px 0;">感谢您对科技狗云购（<a href="#" target="_blank" style="color: #22aaff; font-family: Arial;">http://www.techgou.com</a>）的支持，祝您好运！</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<table cellpadding="0" cellspacing="0" width="600"> <tbody> <tr> <td align="center" style="font-size:12px; color:#999; line-height:30px">Copyright © 2015 - 2016, 版权所有 www.techgou.com 豫ICP备15025669号-1</td>\n</tr>\n</tbody>\n</table>'),
(7, 'template_email_shop', '恭喜您：{用户名}，你在科技狗云购网购买的商品：{商品名称} 已揭晓，揭晓码是:{中奖码}'),
(8, 'pay_bank_type', 'tenpay'),
(9, 'template_mobile_pwd', '你好,你现在正在找回密码，你的验证码是【000000】。'),
(10, 'template_email_pwd', '请在24小时内激活邮件，点击连接激活邮件：{地址}');

-- --------------------------------------------------------

--
-- 表的结构 `go_category`
--

CREATE TABLE IF NOT EXISTS `go_category` (
  `cateid` smallint(6) unsigned NOT NULL COMMENT '栏目id',
  `parentid` smallint(6) DEFAULT NULL COMMENT '父ID',
  `channel` tinyint(4) NOT NULL DEFAULT '0',
  `model` tinyint(1) DEFAULT NULL COMMENT '栏目模型',
  `name` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `catdir` char(20) DEFAULT NULL COMMENT '英文名',
  `url` varchar(255) DEFAULT NULL,
  `info` text,
  `order` smallint(6) unsigned DEFAULT '1' COMMENT '排序',
  `cids` varchar(100) DEFAULT NULL,
  `html` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='栏目表';

--
-- 转存表中的数据 `go_category`
--

INSERT INTO `go_category` (`cateid`, `parentid`, `channel`, `model`, `name`, `catdir`, `url`, `info`, `order`, `cids`, `html`) VALUES
(1, 0, 0, 2, '帮助', 'help', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";N;s:7:"content";N;s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(2, 1, 0, 2, '新手指南', 'xinshouzhinan', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:22:"article_list.list.html";s:13:"template_show";s:22:"article_show.help.html";}', 1, NULL, 0),
(3, 1, 0, 2, '购物保障', 'yunbaozhang', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:22:"article_list.list.html";s:13:"template_show";s:22:"article_show.help.html";}', 1, NULL, 0),
(4, 1, 0, 2, '商品配送', 'peisong', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:22:"article_list.list.html";s:13:"template_show";s:22:"article_show.help.html";}', 1, NULL, 0),
(5, 0, 0, 1, '手机数码', 'shoujishuma', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 6, NULL, 0),
(6, 0, 0, 1, '家用电器', 'jiayongdianqi', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 4, NULL, 0),
(7, 0, 0, -1, '新手指南', 'newbie', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:22:"single_web.newbie.html";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(8, 0, 0, -1, '合作专区', 'business', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:24:"single_web.business.html";s:7:"content";s:34:"<p>输入栏目内容...567678</p>";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(9, 0, 0, -1, '公益基金', 'fund', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:20:"single_web.fund.html";s:7:"content";s:28:"<p>输入栏目内容...</p>";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(10, 0, 0, -1, '网站QQ群', 'qqgroup', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:23:"single_web.qqgroup.html";s:7:"content";s:40:"PHA+6L6T5YWl5qCP55uu5YaF5a65Li4uPC9wPg==";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(11, 0, 0, -1, '邀请注册', 'pleasereg', '', 'a:7:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:25:"single_web.pleasereg.html";s:7:"content";s:28:"<p>输入栏目内容...</p>";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";}', 1, NULL, 0),
(12, 0, 0, 1, '化妆个护', 'huazhuanggehu', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 3, NULL, 0),
(13, 0, 0, 1, '电脑办公', 'diannaobangong', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 5, NULL, 0),
(14, 0, 0, 1, '钟表首饰', 'zhongbiao', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 2, NULL, 0),
(15, 0, 0, 1, '其他商品', 'qita', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 1, NULL, 0),
(16, 1, 0, 2, '网站基金', 'fund', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:22:"article_list.list.html";s:13:"template_show";s:22:"article_show.help.html";}', 1, NULL, 0),
(17, 5, 0, 1, '手机', 'shouji', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(18, 5, 0, 1, '数码影像', 'shumayingxiang', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 1, NULL, 0),
(19, 5, 0, 1, '时尚影音', 'shishangyingyin', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(20, 13, 0, 1, '电脑配件', 'diannaopeijian', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(21, 13, 0, 1, '外设产品', 'waishechanpin', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(22, 13, 0, 1, '网络产品', 'wangluochanpin', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(23, 6, 0, 1, ' 大电器', 'dadianqi', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(24, 6, 0, 1, '生活电器', 'shenghuodianqi', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(25, 12, 0, 1, '面部护理', 'mianbuhuli', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(26, 12, 0, 1, '香水', 'xiangshui', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(27, 12, 0, 1, '个人护理', 'gerenhuli', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(28, 14, 0, 1, '珠宝首饰', 'zhubiaoshoushi', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(29, 14, 0, 1, '钟表手表', 'zhongbiaoshoubiao', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";N;s:13:"template_show";N;}', 1, NULL, 0),
(30, 15, 0, 1, '酒类', 'qiche', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:0:"";s:13:"template_show";s:0:"";}', 1, NULL, 0),
(31, 0, 0, 1, 'vvvvvv', 'zxc', '', 'a:9:{s:5:"thumb";s:0:"";s:3:"des";s:0:"";s:8:"template";s:0:"";s:7:"content";s:0:"";s:10:"meta_title";s:0:"";s:13:"meta_keywords";s:0:"";s:16:"meta_description";s:0:"";s:13:"template_list";s:20:"goods_list.list.html";s:13:"template_show";s:20:"goods_show.show.html";}', 1, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `go_config`
--

CREATE TABLE IF NOT EXISTS `go_config` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `value` mediumtext,
  `zhushi` text
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_config`
--

INSERT INTO `go_config` (`id`, `name`, `value`, `zhushi`) VALUES
(1, 'web_name', '科技狗云购系统', '网站名'),
(2, 'web_key', '科技狗', '网站关键字'),
(3, 'web_des', '科技狗云购系统', '网站介绍'),
(4, 'web_path', 'http://www.techgou.com', '网站地址'),
(5, 'templates_edit', '1', '是否允许在线编辑模板'),
(6, 'templates_name', 'yungou', '当前模板方案'),
(7, 'charset', 'utf-8', '网站字符集'),
(8, 'timezone', 'Asia/Shanghai', '网站时区'),
(9, 'error', '1', '1、保存错误日志到 cache/error_log.php | 0、在页面直接显示'),
(10, 'gzip', '0', '是否Gzip压缩后输出,服务器没有gzip请不要启用'),
(11, 'lang', 'zh-cn', '网站语言包'),
(12, 'cache', '3600', '默认缓存时间'),
(13, 'web_off', '1', '网站是否开启'),
(14, 'web_off_text', '网站关闭。升级中....', '关闭原因'),
(15, 'tablepre', 'QCNf', NULL),
(16, 'index_name', '?', '隐藏首页文件名'),
(17, 'expstr', '/', 'url分隔符号'),
(18, 'admindir', 'admin', '后台管理文件夹'),
(19, 'qq', '544254520', 'qq'),
(20, 'cell', '239556205', '联系电话'),
(21, 'web_logo', 'banner/20160419/98728000034928.png', 'logo'),
(22, 'web_copyright', 'Copyright © 2015 - 2016, 版权所有 豫ICP备15025669号', '版权'),
(23, 'web_name_two', '科技狗云购', '短网站名'),
(24, 'qq_qun', '239556205', 'QQ群'),
(25, 'goods_end_time', '30', '开奖动画秒数(单位秒)');

-- --------------------------------------------------------

--
-- 表的结构 `go_czk`
--

CREATE TABLE IF NOT EXISTS `go_czk` (
  `id` int(11) NOT NULL,
  `czknum` varchar(12) NOT NULL COMMENT '充值卡号码',
  `password` varchar(12) NOT NULL COMMENT '充值卡密码',
  `mianzhi` int(11) NOT NULL COMMENT '面值',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '使用状态',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '充值类型 1一次性 2永久'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_czk`
--

INSERT INTO `go_czk` (`id`, `czknum`, `password`, `mianzhi`, `status`, `type`) VALUES
(1, '7364827321', '4672346123', 1, 1, 1),
(12, '8723075846', '8763097463', 1, 1, 1),
(13, '12341234', '1234', 55, 0, 1),
(14, '1', '1', 162108000, 0, 1),
(15, '123', '123', 99, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `go_egglotter_award`
--

CREATE TABLE IF NOT EXISTS `go_egglotter_award` (
  `award_id` int(11) NOT NULL COMMENT 'id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `user_name` varchar(11) DEFAULT NULL COMMENT '用户名字',
  `rule_id` int(11) DEFAULT NULL COMMENT '活动ID',
  `subtime` int(11) DEFAULT NULL COMMENT '中奖时间',
  `spoil_id` int(11) DEFAULT NULL COMMENT '奖品等级'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_egglotter_rule`
--

CREATE TABLE IF NOT EXISTS `go_egglotter_rule` (
  `rule_id` int(11) NOT NULL,
  `rule_name` varchar(200) DEFAULT NULL,
  `starttime` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `subtime` int(11) DEFAULT NULL COMMENT '活动编辑时间',
  `lotterytype` int(11) DEFAULT NULL COMMENT '抽奖按币分类',
  `lotterjb` int(11) DEFAULT NULL COMMENT '每一次抽奖使用的金币',
  `ruledesc` text COMMENT '规则介绍',
  `startusing` tinyint(4) DEFAULT NULL COMMENT '启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_egglotter_spoil`
--

CREATE TABLE IF NOT EXISTS `go_egglotter_spoil` (
  `spoil_id` int(11) NOT NULL,
  `rule_id` int(11) DEFAULT NULL,
  `spoil_name` text COMMENT '名称',
  `spoil_jl` int(11) DEFAULT NULL COMMENT '机率',
  `spoil_dj` int(11) DEFAULT NULL,
  `urlimg` varchar(200) DEFAULT NULL,
  `subtime` int(11) DEFAULT NULL COMMENT '提交时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_fund`
--

CREATE TABLE IF NOT EXISTS `go_fund` (
  `id` int(10) unsigned NOT NULL,
  `fund_off` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `fund_money` decimal(10,2) unsigned NOT NULL,
  `fund_count_money` decimal(12,2) DEFAULT NULL COMMENT '云购基金'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_fund`
--

INSERT INTO `go_fund` (`id`, `fund_off`, `fund_money`, `fund_count_money`) VALUES
(1, 1, '0.01', '346.17');

-- --------------------------------------------------------

--
-- 表的结构 `go_link`
--

CREATE TABLE IF NOT EXISTS `go_link` (
  `id` int(10) unsigned NOT NULL COMMENT '友情链接ID',
  `type` tinyint(1) unsigned NOT NULL COMMENT '链接类型',
  `name` char(20) NOT NULL COMMENT '名称',
  `logo` varchar(250) NOT NULL COMMENT '图片',
  `url` varchar(50) NOT NULL COMMENT '地址'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_link`
--

INSERT INTO `go_link` (`id`, `type`, `name`, `logo`, `url`) VALUES
(1, 1, 'hll', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `go_member`
--

CREATE TABLE IF NOT EXISTS `go_member` (
  `uid` int(10) unsigned NOT NULL,
  `username` char(20) NOT NULL COMMENT '用户名',
  `email` varchar(50) DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(11) DEFAULT NULL COMMENT '用户手机',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `user_ip` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `qianming` varchar(255) DEFAULT NULL COMMENT '用户签名',
  `groupid` tinyint(4) unsigned DEFAULT '0' COMMENT '用户权限组',
  `addgroup` varchar(255) DEFAULT NULL COMMENT '用户加入的圈子组1|2|3',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '账户金额',
  `emailcode` char(21) DEFAULT '-1' COMMENT '邮箱认证码',
  `mobilecode` char(21) DEFAULT '-1' COMMENT '手机认证码',
  `passcode` char(21) DEFAULT '-1' COMMENT '找会密码认证码-1,1,码',
  `reg_key` varchar(100) DEFAULT NULL COMMENT '注册参数',
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `jingyan` int(10) unsigned DEFAULT '0',
  `yaoqing` int(10) unsigned DEFAULT NULL,
  `band` varchar(255) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  `login_time` int(10) unsigned DEFAULT '0',
  `sign_in_time` mediumint(8) NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `sign_in_date` char(10) NOT NULL DEFAULT '' COMMENT '上次签到日期',
  `sign_in_time_all` mediumint(8) NOT NULL DEFAULT '0' COMMENT '总签到次数',
  `auto_user` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_account`
--

CREATE TABLE IF NOT EXISTS `go_member_account` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(1) DEFAULT NULL COMMENT '充值1/消费-1',
  `pay` char(20) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '详情',
  `money` mediumint(8) NOT NULL DEFAULT '0' COMMENT '金额',
  `time` char(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员账户明细';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_addmoney_record`
--

CREATE TABLE IF NOT EXISTS `go_member_addmoney_record` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `code` char(20) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL,
  `pay_type` char(10) NOT NULL,
  `status` char(20) NOT NULL,
  `time` int(10) NOT NULL,
  `score` int(10) unsigned DEFAULT NULL,
  `scookies` text COMMENT '购物车cookie'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_member_band`
--

CREATE TABLE IF NOT EXISTS `go_member_band` (
  `b_id` int(10) unsigned NOT NULL,
  `b_uid` int(10) DEFAULT NULL COMMENT '用户ID',
  `b_type` char(10) DEFAULT NULL COMMENT '绑定登陆类型',
  `b_code` varchar(100) DEFAULT NULL COMMENT '返回数据1',
  `b_data` varchar(100) DEFAULT NULL COMMENT '返回数据2',
  `b_time` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_member_cashout`
--

CREATE TABLE IF NOT EXISTS `go_member_cashout` (
  `id` int(11) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `username` varchar(20) NOT NULL COMMENT '开户人',
  `bankname` varchar(255) NOT NULL COMMENT '银行名称',
  `branch` varchar(255) NOT NULL COMMENT '支行',
  `money` decimal(8,0) NOT NULL DEFAULT '0' COMMENT '申请提现金额',
  `time` char(20) NOT NULL COMMENT '申请时间',
  `banknumber` varchar(50) NOT NULL COMMENT '银行帐号',
  `linkphone` varchar(100) NOT NULL COMMENT '联系电话',
  `auditstatus` tinyint(4) NOT NULL COMMENT '1审核通过',
  `procefees` decimal(8,2) NOT NULL COMMENT '手续费',
  `reviewtime` char(20) NOT NULL COMMENT '审核通过时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员账户明细';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_del`
--

CREATE TABLE IF NOT EXISTS `go_member_del` (
  `uid` int(10) unsigned NOT NULL,
  `username` char(20) NOT NULL COMMENT '用户名',
  `email` varchar(50) DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(11) DEFAULT NULL COMMENT '用户手机',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `user_ip` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `qianming` varchar(255) DEFAULT NULL COMMENT '用户签名',
  `groupid` tinyint(4) unsigned DEFAULT '0' COMMENT '用户权限组',
  `addgroup` varchar(255) DEFAULT NULL COMMENT '用户加入的圈子组1|2|3',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '账户金额',
  `emailcode` char(21) DEFAULT '-1' COMMENT '邮箱认证码',
  `mobilecode` char(21) DEFAULT '-1' COMMENT '手机认证码',
  `passcode` char(21) DEFAULT '-1' COMMENT '找会密码认证码-1,1,码',
  `reg_key` varchar(100) DEFAULT NULL COMMENT '注册参数',
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `jingyan` int(10) unsigned DEFAULT '0',
  `yaoqing` int(10) unsigned DEFAULT NULL,
  `band` varchar(255) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  `login_time` int(10) unsigned DEFAULT '0',
  `sign_in_time` mediumint(8) NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `sign_in_date` char(10) NOT NULL DEFAULT '' COMMENT '上次签到日期',
  `sign_in_time_all` mediumint(8) NOT NULL DEFAULT '0' COMMENT '总签到次数'
) ENGINE=MyISAM AUTO_INCREMENT=13565 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_dizhi`
--

CREATE TABLE IF NOT EXISTS `go_member_dizhi` (
  `id` tinyint(4) unsigned NOT NULL COMMENT 'id',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `sheng` varchar(15) DEFAULT NULL COMMENT '省',
  `shi` varchar(15) DEFAULT NULL COMMENT '市',
  `xian` varchar(15) DEFAULT NULL COMMENT '县',
  `jiedao` varchar(255) DEFAULT NULL COMMENT '街道地址',
  `youbian` mediumint(8) DEFAULT NULL COMMENT '邮编',
  `shouhuoren` varchar(15) DEFAULT NULL COMMENT '收货人',
  `mobile` char(11) DEFAULT NULL COMMENT '手机',
  `qq` char(11) DEFAULT NULL COMMENT 'QQ',
  `tell` varchar(15) DEFAULT NULL COMMENT '座机号',
  `default` char(1) DEFAULT 'N' COMMENT '是否默认',
  `time` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员地址表';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_go_record`
--

CREATE TABLE IF NOT EXISTS `go_member_go_record` (
  `id` int(10) unsigned NOT NULL,
  `code` char(20) DEFAULT NULL COMMENT '订单号',
  `code_tmp` tinyint(3) unsigned DEFAULT NULL COMMENT '相同订单',
  `username` varchar(30) NOT NULL,
  `uphoto` varchar(255) DEFAULT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '会员id',
  `shopid` int(6) unsigned NOT NULL COMMENT '商品id',
  `shopname` varchar(255) NOT NULL COMMENT '商品名',
  `shopqishu` smallint(6) NOT NULL DEFAULT '0' COMMENT '期数',
  `gonumber` smallint(5) unsigned DEFAULT NULL COMMENT '购买次数',
  `goucode` longtext NOT NULL COMMENT '云购码',
  `moneycount` decimal(10,2) NOT NULL,
  `huode` char(50) NOT NULL DEFAULT '0' COMMENT '中奖码',
  `pay_type` char(10) DEFAULT NULL COMMENT '付款方式',
  `ip` varchar(255) DEFAULT NULL,
  `status` char(30) DEFAULT NULL COMMENT '订单状态',
  `company_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `company_code` char(20) DEFAULT NULL,
  `company` char(10) DEFAULT NULL,
  `time` char(21) NOT NULL COMMENT '购买时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='云购记录表';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_group`
--

CREATE TABLE IF NOT EXISTS `go_member_group` (
  `groupid` tinyint(4) unsigned NOT NULL,
  `name` char(15) NOT NULL COMMENT '会员组名',
  `jingyan_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '需要的经验值',
  `jingyan_end` int(10) NOT NULL,
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `type` char(1) NOT NULL DEFAULT 'N' COMMENT '是否是系统组'
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='会员权限组';

--
-- 转存表中的数据 `go_member_group`
--

INSERT INTO `go_member_group` (`groupid`, `name`, `jingyan_start`, `jingyan_end`, `icon`, `type`) VALUES
(1, '云购新手', 0, 500, NULL, 'N'),
(2, '云购小将', 501, 1000, NULL, 'N'),
(3, '云购中将', 1001, 3000, NULL, 'N'),
(4, '云购上将', 3001, 6000, NULL, 'N'),
(5, '云购大将', 6001, 20000, NULL, 'N'),
(6, '云购将军', 20001, 40000, NULL, 'N');

-- --------------------------------------------------------

--
-- 表的结构 `go_member_message`
--

CREATE TABLE IF NOT EXISTS `go_member_message` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(1) DEFAULT '0' COMMENT '消息来源,0系统,1私信',
  `sendid` int(10) unsigned DEFAULT '0' COMMENT '发送人ID',
  `sendname` char(20) DEFAULT NULL COMMENT '发送人名',
  `content` varchar(255) DEFAULT NULL COMMENT '发送内容',
  `time` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员消息表';

-- --------------------------------------------------------

--
-- 表的结构 `go_member_recodes`
--

CREATE TABLE IF NOT EXISTS `go_member_recodes` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(1) NOT NULL COMMENT '收取1//充值-2/提现-3',
  `content` varchar(255) NOT NULL COMMENT '详情',
  `shopid` int(11) DEFAULT NULL COMMENT '商品id',
  `money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `time` char(20) NOT NULL,
  `ygmoney` decimal(8,2) NOT NULL COMMENT '云购金额',
  `cashoutid` int(11) DEFAULT NULL COMMENT '申请提现记录表id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员账户明细';

-- --------------------------------------------------------

--
-- 表的结构 `go_model`
--

CREATE TABLE IF NOT EXISTS `go_model` (
  `modelid` smallint(5) unsigned NOT NULL,
  `name` char(10) NOT NULL,
  `table` char(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='模型表';

--
-- 转存表中的数据 `go_model`
--

INSERT INTO `go_model` (`modelid`, `name`, `table`) VALUES
(1, '云购模型', 'shoplist'),
(2, '文章模型', 'article');

-- --------------------------------------------------------

--
-- 表的结构 `go_navigation`
--

CREATE TABLE IF NOT EXISTS `go_navigation` (
  `cid` smallint(6) unsigned NOT NULL,
  `parentid` smallint(6) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` char(10) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT 'Y' COMMENT '显示/隐藏',
  `order` smallint(6) unsigned DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_navigation`
--

INSERT INTO `go_navigation` (`cid`, `parentid`, `name`, `type`, `url`, `status`, `order`) VALUES
(1, 0, '所有商品', 'index', '/goods_list', 'N', 2),
(2, 0, '新手指南', 'index', '/single/newbie', 'Y', 2),
(3, 0, '圈子社区', 'index', '/group', 'Y', 2),
(4, 0, '关于我们', 'foot', '/help/1', 'Y', 1),
(5, 0, '隐私声明', 'foot', '/help/12', 'Y', 1),
(6, 0, '合作专区', 'foot', '/single/business', 'Y', 1),
(7, 0, '友情链接', 'foot', '/link', 'Y', 1),
(8, 0, '联系我们', 'foot', '/help/13', 'Y', 1),
(10, 0, '晒单分享', 'index', '/go/shaidan/', 'Y', 1),
(12, 0, '最新揭晓', 'index', '/goods_lottery', 'N', 1),
(13, 0, '邀请有奖', 'index', '/single/pleasereg', 'Y', 1),
(16, 0, '最新揭晓', 'index', '/goods_lottery', 'Y', 1);

-- --------------------------------------------------------

--
-- 表的结构 `go_pay`
--

CREATE TABLE IF NOT EXISTS `go_pay` (
  `pay_id` int(11) unsigned NOT NULL,
  `pay_name` char(20) NOT NULL,
  `pay_class` char(20) NOT NULL,
  `pay_type` tinyint(3) NOT NULL,
  `pay_thumb` varchar(255) DEFAULT NULL,
  `pay_des` text,
  `pay_start` tinyint(4) NOT NULL,
  `pay_key` text,
  `pay_mobile` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_pay`
--

INSERT INTO `go_pay` (`pay_id`, `pay_name`, `pay_class`, `pay_type`, `pay_thumb`, `pay_des`, `pay_start`, `pay_key`, `pay_mobile`) VALUES
(1, '财付通', 'tenpay', 1, 'photo/cft.gif', '腾讯财付通	', 1, 'a:2:{s:2:"id";a:2:{s:4:"name";s:19:"财付通商户号:";s:3:"val";s:0:"";}s:3:"key";a:2:{s:4:"name";s:16:"财付通密钥:";s:3:"val";s:0:"";}}', 0),
(2, '易宝支付', 'yeepay', 1, 'photo/20130929/93656812450898.jpg', '易宝支付', 1, 'a:2:{s:2:"id";a:2:{s:4:"name";s:16:"易宝商户号:";s:3:"val";s:0:"";}s:3:"key";a:2:{s:4:"name";s:13:"易宝密钥:";s:3:"val";s:0:"";}}', 0),
(3, '汇潮支付', 'ecpss', 1, 'photo/20130929/ecpss.jpg', '汇潮支付', 1, 'a:2:{s:2:"id";a:2:{s:4:"name";s:16:"汇潮商户号:";s:3:"val";s:0:"";}s:3:"key";a:2:{s:4:"name";s:13:"汇潮密钥:";s:3:"val";s:0:"";}}', 0),
(4, '支付宝', 'alipay', 1, 'photo/20130929/82028078450752.jpg', '支付宝支付', 1, 'a:3:{s:2:"id";a:2:{s:4:"name";s:19:"支付宝商户号:";s:3:"val";s:0:"";}s:3:"key";a:2:{s:4:"name";s:16:"支付宝密钥:";s:3:"val";s:0:"";}s:4:"user";a:2:{s:4:"name";s:16:"支付宝账号:";s:3:"val";s:0:"";}}', 0),
(5, '手机支付宝', 'wapalipay', 1, 'photo/20130929/82028078450752.jpg', '手机支付宝支付\n', 1, 'a:3:{s:2:"id";a:2:{s:4:"name";s:19:"支付宝商户号:";s:3:"val";s:0:"";}s:3:"key";a:2:{s:4:"name";s:16:"支付宝密钥:";s:3:"val";s:0:"";}s:4:"user";a:2:{s:4:"name";s:16:"支付宝账号:";s:3:"val";s:0:"";}}', 1),
(8, '微信扫码支付', 'weixin', 1, 'photo/weixin.gif', '微信扫码支付', 1, 'a:2:{s:2:"id";a:2:{s:4:"name";s:9:"商户号";s:3:"val";s:10:"1231679402";}s:3:"key";a:2:{s:4:"name";s:6:"密匙";s:3:"val";s:18:"wx437065b08d7f245c";}}', 0);

-- --------------------------------------------------------

--
-- 表的结构 `go_position`
--

CREATE TABLE IF NOT EXISTS `go_position` (
  `pos_id` int(11) unsigned NOT NULL,
  `pos_model` tinyint(3) unsigned NOT NULL,
  `pos_name` varchar(30) NOT NULL,
  `pos_num` tinyint(3) unsigned NOT NULL,
  `pos_maxnum` tinyint(3) unsigned NOT NULL,
  `pos_this_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pos_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_position_data`
--

CREATE TABLE IF NOT EXISTS `go_position_data` (
  `id` int(10) unsigned NOT NULL,
  `con_id` int(10) unsigned NOT NULL,
  `mod_id` tinyint(3) unsigned NOT NULL,
  `mod_name` char(20) NOT NULL,
  `pos_id` int(10) unsigned NOT NULL,
  `pos_data` mediumtext NOT NULL,
  `pos_order` int(10) unsigned NOT NULL DEFAULT '1',
  `pos_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_qqset`
--

CREATE TABLE IF NOT EXISTS `go_qqset` (
  `id` int(11) NOT NULL,
  `qq` varchar(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `qqurl` varchar(250) DEFAULT NULL,
  `full` varchar(6) DEFAULT NULL COMMENT '是否已满',
  `subtime` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_qqset`
--

INSERT INTO `go_qqset` (`id`, `qq`, `name`, `type`, `province`, `city`, `county`, `qqurl`, `full`, `subtime`) VALUES
(18, '239556205', '科技狗技术交流群', '直属群', '省份', '地级市', '市、县级市', 'http://jq.qq.com/?_wv=1027&amp;k=297H7vM', '未满', 1461035402);

-- --------------------------------------------------------

--
-- 表的结构 `go_quanzi`
--

CREATE TABLE IF NOT EXISTS `go_quanzi` (
  `id` tinyint(3) unsigned NOT NULL COMMENT 'ID',
  `title` char(15) NOT NULL COMMENT '标题',
  `img` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `chengyuan` mediumint(8) unsigned DEFAULT '0' COMMENT '成员数',
  `tiezi` mediumint(8) unsigned DEFAULT '0' COMMENT '帖子数',
  `guanli` mediumint(8) unsigned NOT NULL COMMENT '管理员',
  `jinhua` smallint(5) unsigned DEFAULT NULL COMMENT '精华帖',
  `jianjie` varchar(255) DEFAULT '暂无介绍' COMMENT '简介',
  `gongao` varchar(255) DEFAULT '暂无' COMMENT '公告',
  `jiaru` char(1) DEFAULT 'Y' COMMENT '申请加入',
  `glfatie` char(1) DEFAULT 'N' COMMENT '发帖权限',
  `time` int(11) NOT NULL COMMENT '时间',
  `huifu` char(1) NOT NULL DEFAULT 'Y',
  `shenhe` char(1) DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_quanzi_hueifu`
--

CREATE TABLE IF NOT EXISTS `go_quanzi_hueifu` (
  `id` int(10) unsigned NOT NULL COMMENT 'ID',
  `tzid` int(11) DEFAULT NULL COMMENT '帖子ID匹配',
  `hueifu` text COMMENT '回复内容',
  `hueiyuan` varchar(255) DEFAULT NULL COMMENT '会员',
  `hftime` int(11) DEFAULT NULL COMMENT '时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_quanzi_tiezi`
--

CREATE TABLE IF NOT EXISTS `go_quanzi_tiezi` (
  `id` int(10) unsigned NOT NULL COMMENT 'ID',
  `qzid` int(10) unsigned DEFAULT NULL COMMENT '圈子ID匹配',
  `hueiyuan` varchar(255) DEFAULT NULL COMMENT '会员信息',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `neirong` text COMMENT '内容',
  `hueifu` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '回复',
  `dianji` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `zhiding` char(1) DEFAULT 'N' COMMENT '置顶',
  `jinghua` char(1) DEFAULT 'N' COMMENT '精华',
  `zuihou` varchar(255) DEFAULT NULL COMMENT '最后回复',
  `time` int(10) unsigned DEFAULT NULL COMMENT '时间',
  `tiezi` int(10) unsigned NOT NULL DEFAULT '0',
  `shenhe` char(1) NOT NULL DEFAULT 'Y'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_recom`
--

CREATE TABLE IF NOT EXISTS `go_recom` (
  `id` int(10) NOT NULL COMMENT '推荐位id',
  `img` varchar(50) DEFAULT NULL COMMENT '推荐位图片',
  `title` varchar(30) DEFAULT NULL COMMENT '推荐位标题',
  `link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_send`
--

CREATE TABLE IF NOT EXISTS `go_send` (
  `id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `shoptitle` varchar(200) NOT NULL,
  `send_type` tinyint(4) NOT NULL,
  `send_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_shaidan`
--

CREATE TABLE IF NOT EXISTS `go_shaidan` (
  `sd_id` int(10) unsigned NOT NULL COMMENT '晒单id',
  `sd_userid` int(10) unsigned DEFAULT NULL COMMENT '用户ID',
  `sd_shopid` int(10) unsigned DEFAULT NULL COMMENT '商品ID',
  `sd_qishu` int(10) DEFAULT NULL COMMENT '商品期数',
  `sd_ip` varchar(255) DEFAULT NULL,
  `sd_title` varchar(255) DEFAULT NULL COMMENT '晒单标题',
  `sd_thumbs` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `sd_content` text COMMENT '晒单内容',
  `sd_photolist` text COMMENT '晒单图片',
  `sd_zhan` int(10) unsigned DEFAULT '0' COMMENT '点赞',
  `sd_ping` int(10) unsigned DEFAULT '0' COMMENT '评论',
  `sd_time` int(10) unsigned DEFAULT NULL COMMENT '晒单时间',
  `sd_shopsid` int(10) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='晒单';

-- --------------------------------------------------------

--
-- 表的结构 `go_shaidan_hueifu`
--

CREATE TABLE IF NOT EXISTS `go_shaidan_hueifu` (
  `id` int(10) unsigned NOT NULL,
  `sdhf_id` int(11) NOT NULL COMMENT '晒单ID',
  `sdhf_userid` int(11) DEFAULT NULL COMMENT '晒单回复会员ID',
  `sdhf_content` text COMMENT '晒单回复内容',
  `sdhf_time` int(11) DEFAULT NULL,
  `sdhf_username` char(20) DEFAULT NULL,
  `sdhf_img` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_shopcodes_1`
--

CREATE TABLE IF NOT EXISTS `go_shopcodes_1` (
  `id` int(10) unsigned NOT NULL,
  `s_id` int(10) unsigned NOT NULL,
  `s_cid` smallint(5) unsigned NOT NULL,
  `s_len` smallint(5) DEFAULT NULL,
  `s_codes` text,
  `s_codes_tmp` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_shoplist`
--

CREATE TABLE IF NOT EXISTS `go_shoplist` (
  `id` int(10) unsigned NOT NULL COMMENT '商品id',
  `sid` int(10) unsigned NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) unsigned DEFAULT NULL COMMENT '所属栏目ID',
  `brandid` smallint(6) unsigned DEFAULT NULL COMMENT '所属品牌ID',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `title_style` varchar(100) DEFAULT NULL,
  `title2` varchar(100) DEFAULT NULL COMMENT '副标题',
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `yunjiage` decimal(4,2) unsigned DEFAULT '1.00' COMMENT '云购人次价格',
  `zongrenshu` int(10) unsigned DEFAULT '0' COMMENT '总需人数',
  `canyurenshu` int(10) unsigned DEFAULT '0' COMMENT '已参与人数',
  `shenyurenshu` int(10) unsigned DEFAULT NULL,
  `def_renshu` int(10) unsigned DEFAULT '0',
  `qishu` smallint(6) unsigned DEFAULT '0' COMMENT '期数',
  `maxqishu` smallint(5) unsigned DEFAULT '1' COMMENT ' 最大期数',
  `thumb` varchar(255) DEFAULT NULL,
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品内容详情',
  `codes_table` char(20) DEFAULT NULL,
  `xsjx_time` int(10) unsigned DEFAULT NULL,
  `pos` tinyint(4) unsigned DEFAULT NULL COMMENT '是否推荐',
  `renqi` tinyint(4) unsigned DEFAULT '0' COMMENT '是否人气商品0否1是',
  `time` int(10) unsigned DEFAULT NULL COMMENT '时间',
  `order` int(10) unsigned DEFAULT '1',
  `q_uid` int(10) unsigned DEFAULT NULL COMMENT '中奖人ID',
  `q_user` text COMMENT '中奖人信息',
  `q_user_code` char(20) DEFAULT NULL COMMENT '中奖码',
  `q_content` mediumtext COMMENT '揭晓内容',
  `q_counttime` char(20) DEFAULT NULL COMMENT '总时间相加',
  `q_end_time` char(20) DEFAULT NULL COMMENT '揭晓时间',
  `q_showtime` char(1) DEFAULT 'N' COMMENT 'Y/N揭晓动画',
  `renqipos` tinyint(4) unsigned DEFAULT '0',
  `newpos` tinyint(4) unsigned DEFAULT NULL,
  `bannershop` tinyint(4) unsigned DEFAULT NULL,
  `posthumb` varchar(255) DEFAULT NULL,
  `zhiding` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品表';

-- --------------------------------------------------------

--
-- 表的结构 `go_shoplist_del`
--

CREATE TABLE IF NOT EXISTS `go_shoplist_del` (
  `id` int(10) unsigned NOT NULL,
  `sid` int(10) NOT NULL COMMENT '同一个商品',
  `cateid` smallint(6) unsigned DEFAULT NULL,
  `brandid` smallint(6) unsigned DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `title_style` varchar(100) DEFAULT NULL,
  `title2` varchar(100) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00',
  `yunjiage` decimal(4,2) unsigned DEFAULT '1.00',
  `zongrenshu` int(10) unsigned DEFAULT '0',
  `canyurenshu` int(10) unsigned DEFAULT '0',
  `shenyurenshu` int(10) unsigned DEFAULT NULL,
  `def_renshu` int(10) unsigned DEFAULT '0',
  `qishu` smallint(6) unsigned DEFAULT '0',
  `maxqishu` smallint(5) unsigned DEFAULT '1',
  `thumb` varchar(255) DEFAULT NULL,
  `picarr` text,
  `content` mediumtext,
  `codes_table` char(20) DEFAULT NULL,
  `xsjx_time` int(10) unsigned DEFAULT NULL,
  `pos` tinyint(4) unsigned DEFAULT NULL,
  `renqi` tinyint(4) unsigned DEFAULT '0',
  `time` int(10) unsigned DEFAULT NULL,
  `order` int(10) unsigned DEFAULT '1',
  `q_uid` int(10) unsigned DEFAULT NULL,
  `q_user` text COMMENT '中奖人信息',
  `q_user_code` char(20) DEFAULT NULL,
  `q_content` mediumtext,
  `q_counttime` char(20) DEFAULT NULL,
  `q_end_time` char(20) DEFAULT NULL,
  `q_showtime` char(1) DEFAULT 'N' COMMENT 'Y/N揭晓动画'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_slide`
--

CREATE TABLE IF NOT EXISTS `go_slide` (
  `id` int(10) unsigned NOT NULL,
  `img` varchar(50) DEFAULT NULL COMMENT '幻灯片',
  `title` varchar(30) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

--
-- 转存表中的数据 `go_slide`
--

INSERT INTO `go_slide` (`id`, `img`, `title`, `link`) VALUES
(1, 'banner/20141025/76813093234932.jpg', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `go_template`
--

CREATE TABLE IF NOT EXISTS `go_template` (
  `template_name` char(25) NOT NULL,
  `template` char(25) NOT NULL,
  `des` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `go_template`
--

INSERT INTO `go_template` (`template_name`, `template`, `des`) VALUES
('cart.cartlist.html', 'yungou', '购物车列表'),
('cart.pay.html', 'yungou', '购物车付款'),
('cart.paysuccess.html', 'yungou', '购物车支付成功页面'),
('group.index.html', 'yungou', '圈子首页'),
('group.list.html', 'yungou', '圈子列表页'),
('group.nei.html', 'yungou', '圈子内容'),
('group.right.html', 'yungou', '圈子右边'),
('help.help.html', 'yungou', '帮助页面'),
('index.autolottery.html', 'yungou', '限时揭晓'),
('index.buyrecord.html', 'yungou', '云购记录'),
('index.buyrecordbai.html', 'yungou', '最新云购记录'),
('index.dataserver.html', 'yungou', '已揭晓商品'),
('index.detail.html', 'yungou', '晒单详情'),
('index.footer.html', 'yungou', '底部'),
('index.glist.html', 'yungou', '所有商品'),
('index.header.html', 'yungou', '头部'),
('index.index.html', 'yungou', '首页'),
('index.item.html', 'yungou', '商品展示页'),
('index.lottery.html', 'yungou', '最新揭晓'),
('index.shaidan.html', 'yungou', '晒单页面'),
('link.link.html', 'yungou', '友情链接'),
('member.address.html', 'yungou', '会员地址添加'),
('member.cashout.html', 'yungou', '提现申请'),
('member.commissions.html', 'yungou', '佣金明细'),
('member.index.html', 'yungou', '会员首页'),
('member.invitefriends.html', 'yungou', '邀请好友'),
('member.joingroup.html', 'yungou', '加入的圈子'),
('member.left.html', 'yungou', '会员中心左边页面'),
('member.mailchecking.html', 'yungou', '邮箱认证'),
('member.mobilechecking.htm', 'yungou', '手机认证'),
('member.mobilesuccess.html', 'yungou', '手机认证成功'),
('member.modify.html', 'yungou', '会员'),
('member.orderlist.html', 'yungou', '会员资料'),
('member.password.html', 'yungou', '会员修改密码'),
('member.photo.html', 'yungou', '会员修改头像'),
('member.qqclock.html', 'yungou', '会员QQ绑定'),
('member.record.html', 'yungou', '提现记录'),
('member.sendsuccess.html', 'yungou', '邮箱验证发送'),
('member.sendsuccess2.html', 'yungou', '邮箱验证发送2'),
('member.shezhi.html', 'yungou', '资料选项卡'),
('member.singleinsert.html', 'yungou', '会员添加晒单'),
('member.singlelist.html', 'yungou', '会员晒单'),
('member.singleupdate.html', 'yungou', '晒单修改'),
('member.topic.html', 'yungou', '圈子话题'),
('member.userbalance.html', 'yungou', '账户明细'),
('member.userbuydetail.html', 'yungou', '云购记录'),
('member.userbuylist.html', 'yungou', '云购记录'),
('member.userfufen.html', 'yungou', '会员福分'),
('member.userrecharge.html', 'yungou', '账户充值'),
('search.search.html', 'yungou', '搜索'),
('single_web.business.html', 'yungou', '单页_合作专区'),
('single_web.fund.html', 'yungou', '单页_云购基金'),
('single_web.newbie.html', 'yungou', '单页_新手指南'),
('single_web.pleasereg.html', 'yungou', '单页_邀请'),
('single_web.qqgroup.html', 'yungou', '单页_QQ'),
('system.message.html', 'yungou', '系统消息提示'),
('us.index.html', 'yungou', '个人主页'),
('us.left.html', 'yungou', '个人主页左边'),
('us.tab.html', 'yungou', '个人主页选项'),
('us.userbuy.html', 'yungou', '个人主页云购记录'),
('us.userpost.html', 'yungou', '个人主页云购记录'),
('us.userraffle.html', 'yungou', '个人主页云购记录'),
('user.emailcheck.html', 'yungou', '邮箱验证'),
('user.emailok.html', 'yungou', '邮箱验证成功'),
('user.findemailcheck.html', 'yungou', '找回密码'),
('user.finderror.html', 'yungou', '邮箱验证已过期'),
('user.findmobilecheck.html', 'yungou', '手机验证'),
('user.findok.html', 'yungou', '手机验证成功'),
('user.findpassword.html', 'yungou', '重置密码'),
('user.login.html', 'yungou', '会员登录'),
('user.mobilecheck.html', 'yungou', '手机验证'),
('user.register.html', 'yungou', '会员注册'),
('vote.show.html', 'yungou', '投票内容页'),
('vote.show_total.html', 'yungou', '投票列表'),
('vote.vote.html', 'yungou', '投票主页'),
('article_list.list.html', 'yungou', ''),
('article_show.help.html', 'yungou', ''),
('article_show.show.html', 'yungou', ''),
('goods_list.list.html', 'yungou', ''),
('goods_show.show.html', 'yungou', ''),
('index.go_record_ifram.htm', 'yungou', ''),
('index.item_animation.html', 'yungou', ''),
('index.item_contents.html', 'yungou', ''),
('index.itemifram.html', 'yungou', ''),
('index.qq.html', 'yungou', ''),
('index.qualification.html', 'yungou', ''),
('member.mobilechecking.htm', 'yungou', ''),
('member.qiandao.html', 'yungou', ''),
('member.userrecharge(', 'yungou', ''),
('<a style="color:#0c0;font', 'yungou', ''),
('single_web.android.html', 'yungou', ''),
('single_web.app.html', 'yungou', ''),
('single_web.app2.html', 'yungou', ''),
('single_web.business', 'yungou', ''),
('single_web.desktop.html', 'yungou', ''),
('single_web.iPhone.html', 'yungou', ''),
('single_web.intro.html', 'yungou', ''),
('single_web.pleasereg', 'yungou', ''),
('single_web.touch.html', 'yungou', ''),
('single_web.weixin.html', 'yungou', '');

-- --------------------------------------------------------

--
-- 表的结构 `go_vote_activer`
--

CREATE TABLE IF NOT EXISTS `go_vote_activer` (
  `id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `ip` char(20) DEFAULT NULL,
  `subtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_vote_option`
--

CREATE TABLE IF NOT EXISTS `go_vote_option` (
  `option_id` int(11) NOT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `option_title` varchar(100) DEFAULT NULL,
  `option_number` int(11) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_vote_subject`
--

CREATE TABLE IF NOT EXISTS `go_vote_subject` (
  `vote_id` int(11) NOT NULL,
  `vote_title` varchar(100) DEFAULT NULL,
  `vote_starttime` int(11) DEFAULT NULL,
  `vote_endtime` int(11) DEFAULT NULL,
  `vote_sendtime` int(11) DEFAULT NULL,
  `vote_description` text,
  `vote_allowview` tinyint(1) DEFAULT NULL,
  `vote_allowguest` tinyint(1) DEFAULT NULL,
  `vote_interval` int(11) DEFAULT '0',
  `vote_enabled` tinyint(1) DEFAULT NULL,
  `vote_number` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `go_wap`
--

CREATE TABLE IF NOT EXISTS `go_wap` (
  `id` int(10) unsigned NOT NULL,
  `img` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `color` varchar(30) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `go_wap`
--

INSERT INTO `go_wap` (`id`, `img`, `title`, `link`, `color`) VALUES
(1, 'banner/20140715/46594684432883.jpg', '小米平板', '', '#CCCCCC');

-- --------------------------------------------------------

--
-- 表的结构 `ke_order`
--

CREATE TABLE IF NOT EXISTS `ke_order` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `out_trade_no` char(30) NOT NULL,
  `statu` tinyint(1) NOT NULL,
  `mktime` char(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `ke_order`
--

INSERT INTO `ke_order` (`id`, `uid`, `money`, `out_trade_no`, `statu`, `mktime`) VALUES
(1, 1, '10.00', '', 0, '2015-01-16 06:17:02'),
(2, 1, '10.00', 'Tpay1421360252', 0, '2015-01-16 06:17:32'),
(3, 1, '10.00', 'Apay1421360260', 0, '2015-01-16 06:17:40'),
(4, 391, '50.00', 'Apay1422001043', 0, '2015-01-23 16:17:23'),
(5, 391, '50.00', 'Tpay1422001076', 0, '2015-01-23 16:17:56'),
(6, 392, '10.00', 'Apay1422257081', 0, '2015-01-26 15:24:41'),
(7, 392, '10.00', 'Tpay1422257118', 0, '2015-01-26 15:25:18'),
(8, 392, '50.00', 'Apay1422425807', 0, '2015-01-28 14:16:47'),
(9, 392, '50.00', 'Tpay1422425825', 0, '2015-01-28 14:17:05'),
(10, 695, '10.00', 'Tpay1423277864', 0, '2015-02-07 10:57:44'),
(11, 695, '50.00', 'Apay1423277891', 0, '2015-02-07 10:58:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `go_admin`
--
ALTER TABLE `go_admin`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `go_ad_area`
--
ALTER TABLE `go_ad_area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checked` (`checked`);

--
-- Indexes for table `go_ad_data`
--
ALTER TABLE `go_ad_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `go_article`
--
ALTER TABLE `go_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cateid` (`cateid`);

--
-- Indexes for table `go_brand`
--
ALTER TABLE `go_brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `order` (`order`);

--
-- Indexes for table `go_caches`
--
ALTER TABLE `go_caches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`);

--
-- Indexes for table `go_category`
--
ALTER TABLE `go_category`
  ADD PRIMARY KEY (`cateid`),
  ADD KEY `name` (`name`),
  ADD KEY `order` (`order`);

--
-- Indexes for table `go_config`
--
ALTER TABLE `go_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `go_czk`
--
ALTER TABLE `go_czk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_egglotter_award`
--
ALTER TABLE `go_egglotter_award`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `go_egglotter_rule`
--
ALTER TABLE `go_egglotter_rule`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `go_egglotter_spoil`
--
ALTER TABLE `go_egglotter_spoil`
  ADD PRIMARY KEY (`spoil_id`);

--
-- Indexes for table `go_fund`
--
ALTER TABLE `go_fund`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_link`
--
ALTER TABLE `go_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `go_member`
--
ALTER TABLE `go_member`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `go_member_account`
--
ALTER TABLE `go_member_account`
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `go_member_addmoney_record`
--
ALTER TABLE `go_member_addmoney_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_member_band`
--
ALTER TABLE `go_member_band`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `b_uid` (`b_uid`);

--
-- Indexes for table `go_member_cashout`
--
ALTER TABLE `go_member_cashout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`username`);

--
-- Indexes for table `go_member_del`
--
ALTER TABLE `go_member_del`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `go_member_dizhi`
--
ALTER TABLE `go_member_dizhi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `go_member_go_record`
--
ALTER TABLE `go_member_go_record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `shopid` (`shopid`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `go_member_group`
--
ALTER TABLE `go_member_group`
  ADD PRIMARY KEY (`groupid`),
  ADD KEY `jingyan` (`jingyan_start`);

--
-- Indexes for table `go_member_message`
--
ALTER TABLE `go_member_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `go_member_recodes`
--
ALTER TABLE `go_member_recodes`
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `go_model`
--
ALTER TABLE `go_model`
  ADD PRIMARY KEY (`modelid`);

--
-- Indexes for table `go_navigation`
--
ALTER TABLE `go_navigation`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `status` (`status`),
  ADD KEY `order` (`order`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `go_pay`
--
ALTER TABLE `go_pay`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `go_position`
--
ALTER TABLE `go_position`
  ADD PRIMARY KEY (`pos_id`),
  ADD KEY `pos_id` (`pos_id`),
  ADD KEY `pos_model` (`pos_model`);

--
-- Indexes for table `go_position_data`
--
ALTER TABLE `go_position_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_qqset`
--
ALTER TABLE `go_qqset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_quanzi`
--
ALTER TABLE `go_quanzi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_quanzi_hueifu`
--
ALTER TABLE `go_quanzi_hueifu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_quanzi_tiezi`
--
ALTER TABLE `go_quanzi_tiezi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_recom`
--
ALTER TABLE `go_recom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_send`
--
ALTER TABLE `go_send`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `send_type` (`send_type`);

--
-- Indexes for table `go_shaidan`
--
ALTER TABLE `go_shaidan`
  ADD PRIMARY KEY (`sd_id`),
  ADD KEY `sd_userid` (`sd_userid`),
  ADD KEY `sd_shopid` (`sd_shopid`),
  ADD KEY `sd_zhan` (`sd_zhan`),
  ADD KEY `sd_ping` (`sd_ping`),
  ADD KEY `sd_time` (`sd_time`);

--
-- Indexes for table `go_shaidan_hueifu`
--
ALTER TABLE `go_shaidan_hueifu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_shopcodes_1`
--
ALTER TABLE `go_shopcodes_1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `s_id` (`s_id`),
  ADD KEY `s_cid` (`s_cid`),
  ADD KEY `s_len` (`s_len`);

--
-- Indexes for table `go_shoplist`
--
ALTER TABLE `go_shoplist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `renqi` (`renqi`),
  ADD KEY `order` (`yunjiage`),
  ADD KEY `q_uid` (`q_uid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `shenyurenshu` (`shenyurenshu`),
  ADD KEY `q_showtime` (`q_showtime`);

--
-- Indexes for table `go_shoplist_del`
--
ALTER TABLE `go_shoplist_del`
  ADD PRIMARY KEY (`id`),
  ADD KEY `renqi` (`renqi`),
  ADD KEY `order` (`yunjiage`),
  ADD KEY `q_uid` (`q_uid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `shenyurenshu` (`shenyurenshu`),
  ADD KEY `q_showtime` (`q_showtime`);

--
-- Indexes for table `go_slide`
--
ALTER TABLE `go_slide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `img` (`img`);

--
-- Indexes for table `go_template`
--
ALTER TABLE `go_template`
  ADD KEY `template` (`template`);

--
-- Indexes for table `go_vote_activer`
--
ALTER TABLE `go_vote_activer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `go_vote_option`
--
ALTER TABLE `go_vote_option`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `go_vote_subject`
--
ALTER TABLE `go_vote_subject`
  ADD PRIMARY KEY (`vote_id`);

--
-- Indexes for table `go_wap`
--
ALTER TABLE `go_wap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ke_order`
--
ALTER TABLE `ke_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `go_admin`
--
ALTER TABLE `go_admin`
  MODIFY `uid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `go_ad_area`
--
ALTER TABLE `go_ad_area`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `go_ad_data`
--
ALTER TABLE `go_ad_data`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `go_article`
--
ALTER TABLE `go_article`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章id',AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `go_brand`
--
ALTER TABLE `go_brand`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `go_caches`
--
ALTER TABLE `go_caches`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `go_category`
--
ALTER TABLE `go_category`
  MODIFY `cateid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目id',AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `go_config`
--
ALTER TABLE `go_config`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `go_czk`
--
ALTER TABLE `go_czk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `go_egglotter_award`
--
ALTER TABLE `go_egglotter_award`
  MODIFY `award_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `go_egglotter_rule`
--
ALTER TABLE `go_egglotter_rule`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_egglotter_spoil`
--
ALTER TABLE `go_egglotter_spoil`
  MODIFY `spoil_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_link`
--
ALTER TABLE `go_link`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '友情链接ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `go_member`
--
ALTER TABLE `go_member`
  MODIFY `uid` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_member_addmoney_record`
--
ALTER TABLE `go_member_addmoney_record`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_member_band`
--
ALTER TABLE `go_member_band`
  MODIFY `b_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_member_cashout`
--
ALTER TABLE `go_member_cashout`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_member_del`
--
ALTER TABLE `go_member_del`
  MODIFY `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13565;
--
-- AUTO_INCREMENT for table `go_member_dizhi`
--
ALTER TABLE `go_member_dizhi`
  MODIFY `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `go_member_go_record`
--
ALTER TABLE `go_member_go_record`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_member_group`
--
ALTER TABLE `go_member_group`
  MODIFY `groupid` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `go_member_message`
--
ALTER TABLE `go_member_message`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_model`
--
ALTER TABLE `go_model`
  MODIFY `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `go_navigation`
--
ALTER TABLE `go_navigation`
  MODIFY `cid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `go_pay`
--
ALTER TABLE `go_pay`
  MODIFY `pay_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `go_position`
--
ALTER TABLE `go_position`
  MODIFY `pos_id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_position_data`
--
ALTER TABLE `go_position_data`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_qqset`
--
ALTER TABLE `go_qqset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `go_quanzi`
--
ALTER TABLE `go_quanzi`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `go_quanzi_hueifu`
--
ALTER TABLE `go_quanzi_hueifu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `go_quanzi_tiezi`
--
ALTER TABLE `go_quanzi_tiezi`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `go_recom`
--
ALTER TABLE `go_recom`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '推荐位id';
--
-- AUTO_INCREMENT for table `go_send`
--
ALTER TABLE `go_send`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_shaidan`
--
ALTER TABLE `go_shaidan`
  MODIFY `sd_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '晒单id';
--
-- AUTO_INCREMENT for table `go_shaidan_hueifu`
--
ALTER TABLE `go_shaidan_hueifu`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_shopcodes_1`
--
ALTER TABLE `go_shopcodes_1`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_shoplist`
--
ALTER TABLE `go_shoplist`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id';
--
-- AUTO_INCREMENT for table `go_shoplist_del`
--
ALTER TABLE `go_shoplist_del`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_slide`
--
ALTER TABLE `go_slide`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `go_vote_activer`
--
ALTER TABLE `go_vote_activer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_vote_option`
--
ALTER TABLE `go_vote_option`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_vote_subject`
--
ALTER TABLE `go_vote_subject`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `go_wap`
--
ALTER TABLE `go_wap`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ke_order`
--
ALTER TABLE `ke_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
