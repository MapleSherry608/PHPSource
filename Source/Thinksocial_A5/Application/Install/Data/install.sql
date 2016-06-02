/*Table structure for table `sx_action` */

DROP TABLE IF EXISTS `sx_action`;

CREATE TABLE `sx_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1:系统；2:用户',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `score` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '积分数量 -数为减 正数为加',
  `deposit` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '金额数量 -数为减 正数为加',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

/*Data for the table `sx_action` */

LOCK TABLES `sx_action` WRITE;

insert  into `sx_action`(`id`,`name`,`title`,`remark`,`rule`,`log`,`type`,`status`,`update_time`,`score`,`deposit`) values (1,'user_login','用户登录','积分+10，每天一次','','[userid|get_admin]在[time|time_format]登录了后台',1,0,1451960431,'0.00','0.00'),(2,'add_article','发布文章','积分+5，每天上限5次','table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5','',2,0,1380173180,'0.00','0.00'),(3,'review','评论','评论积分+1，无限制','table:member|field:score|condition:uid={$self}|rule:score+1','',2,0,1383285646,'0.00','0.00'),(4,'add_document','发表文档','积分+10，每天上限5次','table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5','[membid|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。',2,0,1386139726,'0.00','0.00'),(5,'add_document_topic','发表讨论','积分+5，每天上限10次','table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10','',1,0,1448859979,'0.00','0.00'),(6,'update_config','更新配置','新增或修改或删除配置','','',1,0,1383294988,'0.00','0.00'),(7,'update_model','更新模型','新增或修改模型','','',1,0,1383295057,'0.00','0.00'),(12,'memb_login','粉丝登入','积分+10，每天一次','table:member|field:score,deposit|condition:id={$self} AND status>-1 AND deposit + {$deposit} >= 0 ,id={$self} AND status>-1 AND deposit + {$deposit} >= 0  |rule:score+{$score},deposit+{$deposit}|cycle:24|max:1;','[membid|get_nickname]在[time|time_format]登录了系统',1,0,1453087975,'10.00','0.00'),(13,'user_add_member','添加用户','管理员后台添加用户','','管理员[user|get_nickname]在[time|time_format]时，通过后台添加了用户！',1,0,1445830182,'0.00','0.00'),(14,'user_save_member','修改用户信息','管理员通过后台修改用户','','管理员[user|get_nickname]在[time|time_format]时，通过后台修改了用户信息！',1,0,1445846214,'0.00','0.00'),(15,'user_add_deposit','金额充值','管理员通过后台为用户充值金额！','','管理员“[user|get_nickname]”在“[time|time_format]”时，通过后台为ID号为“[membid]”的会员充值了“[record]”美元！操作的模块为“[model]”',1,0,1445995997,'1.20','10.00'),(16,'user_upde_membpwd','修改会员密码','管理员通过后台为用户充值金额！','','管理员[user|get_nickname]在[time|time_format]时，通过后台修改了id为 “[record]”的用户的密码信息！修改的模块为“[model]”',1,0,1445996040,'0.00','0.00'),(17,'user_add_score','积分充值','管理员通过后台充值积分','','管理员“[user|get_nickname]”在“[time|time_format]”时，为后台为ID号为“[membid]”的会员充值了“[record]”积分！操作模块为“[model]”',1,0,1445995967,'0.00','0.00'),(18,'user_reduce_deposit','手动减金额','管理员通过后台手动减少用户的充值金额！','','管理员“[user|get_nickname]”在“[time|time_format]”时，通过后台为ID号为“[membid]”的会员充值了“[record]”美元！修改的模块为“[model]”',1,0,1445996196,'0.00','0.00'),(19,'user_reduce_score','后台减积分','管理员通过后台手动减少用户充值的积分数','','管理员“[user|get_nickname]”在“[time|time_format]”时，为后台为ID号为“[membid]”的会员充值了“[record]”积分！操作模块为“[model]”',1,0,1449022035,'0.00','0.00');

UNLOCK TABLES;

/*Table structure for table `sx_action_log` */

DROP TABLE IF EXISTS `sx_action_log`;

CREATE TABLE `sx_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `membid` int(13) NOT NULL DEFAULT '0' COMMENT '执行的用户id',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行操作的管理员id（当用户进行调用的时候管理员id可以为空）',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  `ischeck` varchar(50) NOT NULL DEFAULT '' COMMENT '是否做过积分更变 如果有就记录下积分日志id以","为分隔符',
  `issystem` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型 0.标注前台 1.标注后台 2.标注模块操作',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=7488 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

/*Data for the table `sx_action_log` */

LOCK TABLES `sx_action_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_active_category` */

DROP TABLE IF EXISTS `sx_active_category`;

CREATE TABLE `sx_active_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

/*Data for the table `sx_active_category` */

LOCK TABLES `sx_active_category` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_active_order` */

DROP TABLE IF EXISTS `sx_active_order`;

CREATE TABLE `sx_active_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `weid` int(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `from_user` varchar(200) NOT NULL,
  `uid` int(10) NOT NULL,
  `ordersn` varchar(200) NOT NULL,
  `price` double NOT NULL,
  `status` tinyint(2) NOT NULL,
  `transid` varchar(30) NOT NULL COMMENT '微信支付单号',
  `paytype` tinyint(1) DEFAULT NULL COMMENT '1为余额，2为在线，3为到付',
  `active_method` tinyint(2) NOT NULL,
  `apply_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8;

/*Data for the table `sx_active_order` */

LOCK TABLES `sx_active_order` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_activity` */

DROP TABLE IF EXISTS `sx_activity`;

CREATE TABLE `sx_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '官方活动Id',
  `hinge` varchar(100) NOT NULL COMMENT '关键字',
  `first_cate_id` int(6) NOT NULL COMMENT '分类id',
  `second_cate_id` int(6) NOT NULL COMMENT '二级分类',
  `title` varchar(200) NOT NULL COMMENT '活动标题',
  `province` varchar(120) NOT NULL COMMENT '身份',
  `city` varchar(120) NOT NULL COMMENT '城市',
  `district` varchar(120) NOT NULL COMMENT '地区',
  `detailaddress` varchar(200) NOT NULL COMMENT '活动详细地址地点',
  `lng` decimal(10,2) NOT NULL COMMENT '经度',
  `lat` decimal(10,2) NOT NULL COMMENT '纬度',
  `initiator` varchar(200) NOT NULL COMMENT '活动发起人',
  `scancodeiden` int(6) NOT NULL COMMENT '扫码标识',
  `wechatmaxnum` int(6) NOT NULL COMMENT '再报人数',
  `content` text NOT NULL COMMENT '活动详情',
  `conver_pic` varchar(500) NOT NULL COMMENT '活动图片 （列表页） 缩略图',
  `movement_pic` varchar(500) NOT NULL COMMENT '活动封面 （详情页） 详细图',
  `active_begin_time` int(18) NOT NULL COMMENT '活动开始时间',
  `active_end_time` int(18) NOT NULL COMMENT '活动结束时间',
  `start_time` int(18) NOT NULL COMMENT '报名时间(开始)',
  `end_time` int(18) NOT NULL COMMENT '报名时间(结束)',
  `add_time` int(18) NOT NULL COMMENT '添加活动时间',
  `if_fee` tinyint(2) NOT NULL COMMENT '是否收费',
  `if_auditing` tinyint(2) NOT NULL COMMENT '是否需要审核',
  `max_acount` int(3) NOT NULL COMMENT '限定报名人数',
  `if_persion` tinyint(2) NOT NULL COMMENT '是否显示成人儿童',
  `active_fee` double NOT NULL COMMENT '费用',
  `child_fee` double NOT NULL COMMENT '儿童价',
  `if_show_pic` tinyint(2) NOT NULL COMMENT '是否显示上传图片',
  `if_pic` tinyint(2) NOT NULL COMMENT '图片是否必选',
  `shareDesc` varchar(50) NOT NULL COMMENT '分享活动详情描述内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COMMENT='官方发起活动';

/*Data for the table `sx_activity` */

LOCK TABLES `sx_activity` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_addons` */

DROP TABLE IF EXISTS `sx_addons`;

CREATE TABLE `sx_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 COMMENT='插件表';

/*Data for the table `sx_addons` */

LOCK TABLES `sx_addons` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_apply_info` */

DROP TABLE IF EXISTS `sx_apply_info`;

CREATE TABLE `sx_apply_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '报名信息表Id',
  `info_name` varchar(100) NOT NULL COMMENT '名称',
  `info_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型,1:文本,2:下拉,3:单选,4:多选',
  `isRequired` tinyint(2) NOT NULL COMMENT '是否必选,0:否,1:是',
  `ordernumber` int(10) NOT NULL COMMENT '排序',
  `addTime` int(11) NOT NULL COMMENT '添加时间',
  `active_id` int(10) NOT NULL COMMENT '对应活动ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COMMENT='报名信息表';

/*Data for the table `sx_apply_info` */

LOCK TABLES `sx_apply_info` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_attribute` */

DROP TABLE IF EXISTS `sx_attribute`;

CREATE TABLE `sx_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL,
  `validate_time` tinyint(1) unsigned NOT NULL,
  `error_info` varchar(100) NOT NULL,
  `validate_type` varchar(25) NOT NULL,
  `auto_rule` varchar(100) NOT NULL,
  `auto_time` tinyint(1) unsigned NOT NULL,
  `auto_type` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1037 DEFAULT CHARSET=utf8 COMMENT='模型属性表';

/*Data for the table `sx_attribute` */

LOCK TABLES `sx_attribute` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_auth_group` */

DROP TABLE IF EXISTS `sx_auth_group`;

CREATE TABLE `sx_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sx_auth_group` */

LOCK TABLES `sx_auth_group` WRITE;

insert  into `sx_auth_group`(`id`,`module`,`type`,`title`,`description`,`status`,`rules`) values (1,'admin',1,'默认用户组','注册用户',1,'8,17,32,35,36,41,61,63,65,66,80,81,212,217,218,219,220,221,223,224,225,226,227,228,230,234,239,240,241,242,243,248,251,255,256,257,258,259,268,269,270,271,272,273,274,275,276,280,281,284,285,286,287,289,290,291,292,295,296');

UNLOCK TABLES;

/*Table structure for table `sx_auth_group_access` */

DROP TABLE IF EXISTS `sx_auth_group_access`;

CREATE TABLE `sx_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sx_auth_group_access` */

LOCK TABLES `sx_auth_group_access` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_auth_rule` */

DROP TABLE IF EXISTS `sx_auth_rule`;

CREATE TABLE `sx_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=306 DEFAULT CHARSET=utf8;

/*Data for the table `sx_auth_rule` */

LOCK TABLES `sx_auth_rule` WRITE;

insert  into `sx_auth_rule`(`id`,`module`,`type`,`name`,`title`,`status`,`condition`) values (1,'admin',2,'Admin/Index/index','首页',-1,''),(2,'admin',2,'Admin/Article/mydocument','内容',-1,''),(3,'admin',2,'Admin/User/index','用户',-1,''),(4,'admin',2,'Admin/Addons/index','扩展',-1,''),(5,'admin',2,'Admin/Config/group','系统',-1,''),(7,'admin',1,'Admin/article/add','新增',-1,''),(8,'admin',1,'Admin/Article/edit','添加文章',1,''),(9,'admin',1,'Admin/article/setStatus','改变状态',-1,''),(10,'admin',1,'Admin/article/update','保存',-1,''),(11,'admin',1,'Admin/article/autoSave','保存草稿',-1,''),(12,'admin',1,'Admin/article/move','移动',-1,''),(13,'admin',1,'Admin/article/copy','复制',-1,''),(14,'admin',1,'Admin/article/paste','粘贴',-1,''),(15,'admin',1,'Admin/article/permit','还原',-1,''),(16,'admin',1,'Admin/article/clear','清空',-1,''),(17,'admin',1,'Admin/Article/index','文章管理',1,''),(18,'admin',1,'Admin/article/recycle','回收站',-1,''),(19,'admin',1,'Admin/User/addaction','新增用户行为',-1,''),(20,'admin',1,'Admin/User/editaction','编辑用户行为',-1,''),(21,'admin',1,'Admin/User/saveAction','保存用户行为',-1,''),(22,'admin',1,'Admin/User/setStatus','变更行为状态',-1,''),(23,'admin',1,'Admin/User/changeStatus?method=forbidUser','禁用会员',-1,''),(24,'admin',1,'Admin/User/changeStatus?method=resumeUser','启用会员',-1,''),(25,'admin',1,'Admin/User/changeStatus?method=deleteUser','删除会员',-1,''),(26,'admin',1,'Admin/User/index','用户信息',-1,''),(27,'admin',1,'Admin/User/action','用户行为',-1,''),(28,'admin',1,'Admin/AuthManager/changeStatus?method=deleteGroup','删除',-1,''),(29,'admin',1,'Admin/AuthManager/changeStatus?method=forbidGroup','禁用',-1,''),(30,'admin',1,'Admin/AuthManager/changeStatus?method=resumeGroup','恢复',-1,''),(31,'admin',1,'Admin/AuthManager/createGroup','新增',-1,''),(32,'admin',1,'Admin/AuthManager/editgroup','编辑用户组',1,''),(33,'admin',1,'Admin/AuthManager/writeGroup','保存用户组',-1,''),(34,'admin',1,'Admin/AuthManager/group','授权',-1,''),(35,'admin',1,'Admin/AuthManager/access','访问授权',1,''),(36,'admin',1,'Admin/AuthManager/user','成员授权',1,''),(37,'admin',1,'Admin/AuthManager/removeFromGroup','解除授权',-1,''),(38,'admin',1,'Admin/AuthManager/addToGroup','保存成员授权',-1,''),(39,'admin',1,'Admin/AuthManager/category','分类授权',-1,''),(40,'admin',1,'Admin/AuthManager/addToCategory','保存分类授权',-1,''),(41,'admin',1,'Admin/AuthManager/index','权限管理',1,''),(42,'admin',1,'Admin/Addons/create','添加插件',1,''),(43,'admin',1,'Admin/Addons/checkForm','检测创建',-1,''),(44,'admin',1,'Admin/Addons/preview','预览',-1,''),(45,'admin',1,'Admin/Addons/build','快速生成插件',-1,''),(46,'admin',1,'Admin/Addons/config','插件配置',1,''),(47,'admin',1,'Admin/Addons/disable','禁用',-1,''),(48,'admin',1,'Admin/Addons/enable','启用',-1,''),(49,'admin',1,'Admin/Addons/install','安装',-1,''),(50,'admin',1,'Admin/Addons/uninstall','卸载',-1,''),(51,'admin',1,'Admin/Addons/saveconfig','更新配置',-1,''),(52,'admin',1,'Admin/Addons/adminList','插件后台列表',-1,''),(53,'admin',1,'Admin/Addons/execute','评论审核',-1,''),(54,'admin',1,'Admin/Addons/index','插件管理',1,''),(55,'admin',1,'Admin/Addons/hooks','钩子管理',1,''),(56,'admin',1,'Admin/model/add','新增',-1,''),(57,'admin',1,'Admin/model/edit','编辑',-1,''),(58,'admin',1,'Admin/model/setStatus','改变状态',-1,''),(59,'admin',1,'Admin/model/update','保存数据',-1,''),(60,'admin',1,'Admin/Model/index','模型管理',-1,''),(61,'admin',1,'Admin/Config/edit','编辑配置项',1,''),(62,'admin',1,'Admin/Config/del','删除',-1,''),(63,'admin',1,'Admin/Config/add','添加配置项',1,''),(64,'admin',1,'Admin/Config/save','保存',-1,''),(65,'admin',1,'Admin/Config/group','网站设置',1,''),(66,'admin',1,'Admin/Config/index','配置管理',1,''),(67,'admin',1,'Admin/Channel/add','新增',-1,''),(68,'admin',1,'Admin/Channel/edit','编辑',-1,''),(69,'admin',1,'Admin/Channel/del','删除',-1,''),(70,'admin',1,'Admin/Channel/index','导航管理',-1,''),(71,'admin',1,'Admin/Category/edit','编辑',-1,''),(72,'admin',1,'Admin/Category/add','新增',-1,''),(73,'admin',1,'Admin/Category/remove','删除',-1,''),(74,'admin',1,'Admin/Category/index','分类管理',-1,''),(75,'admin',1,'Admin/file/upload','上传控件',-1,''),(76,'admin',1,'Admin/file/uploadPicture','上传图片',-1,''),(77,'admin',1,'Admin/file/download','下载',-1,''),(94,'admin',1,'Admin/AuthManager/modelauth','模型授权',-1,''),(79,'admin',1,'Admin/article/batchOperate','导入',-1,''),(80,'admin',1,'Admin/Database/index?type=export','备份数据库',1,''),(81,'admin',1,'Admin/Database/index?type=import','还原数据库',1,''),(82,'admin',1,'Admin/Database/export','备份',-1,''),(83,'admin',1,'Admin/Database/optimize','优化表',-1,''),(84,'admin',1,'Admin/Database/repair','修复表',-1,''),(86,'admin',1,'Admin/Database/import','恢复',-1,''),(87,'admin',1,'Admin/Database/del','删除',-1,''),(88,'admin',1,'Admin/User/add','新增用户',-1,''),(89,'admin',1,'Admin/Attribute/index','属性管理',-1,''),(90,'admin',1,'Admin/Attribute/add','新增',-1,''),(91,'admin',1,'Admin/Attribute/edit','编辑',-1,''),(92,'admin',1,'Admin/Attribute/setStatus','改变状态',-1,''),(93,'admin',1,'Admin/Attribute/update','保存数据',-1,''),(95,'admin',1,'Admin/AuthManager/addToModel','保存模型授权',-1,''),(96,'admin',1,'Admin/Category/move','移动',-1,''),(97,'admin',1,'Admin/Category/merge','合并',-1,''),(98,'admin',1,'Admin/Config/menu','后台菜单管理',-1,''),(99,'admin',1,'Admin/Article/mydocument','内容',-1,''),(100,'admin',1,'Admin/Menu/index','菜单管理',-1,''),(101,'admin',1,'Admin/other','其他',-1,''),(102,'admin',1,'Admin/Menu/add','新增',-1,''),(103,'admin',1,'Admin/Menu/edit','编辑',-1,''),(104,'admin',1,'Admin/Think/lists?model=article','文章管理',-1,''),(105,'admin',1,'Admin/Think/lists?model=download','下载管理',-1,''),(106,'admin',1,'Admin/Think/lists?model=config','配置管理',-1,''),(107,'admin',1,'Admin/Action/actionlog','行为日志',-1,''),(108,'admin',1,'Admin/User/updatePassword','修改密码',-1,''),(109,'admin',1,'Admin/User/updateNickname','修改昵称',-1,''),(110,'admin',1,'Admin/action/edit','查看行为日志',-1,''),(205,'admin',1,'Admin/think/add','新增数据',-1,''),(111,'admin',2,'Admin/article/index','文档列表',-1,''),(112,'admin',2,'Admin/article/add','新增',-1,''),(113,'admin',2,'Admin/article/edit','编辑',-1,''),(114,'admin',2,'Admin/article/setStatus','改变状态',-1,''),(115,'admin',2,'Admin/article/update','保存',-1,''),(116,'admin',2,'Admin/article/autoSave','保存草稿',-1,''),(117,'admin',2,'Admin/article/move','移动',-1,''),(118,'admin',2,'Admin/article/copy','复制',-1,''),(119,'admin',2,'Admin/article/paste','粘贴',-1,''),(120,'admin',2,'Admin/article/batchOperate','导入',-1,''),(121,'admin',2,'Admin/article/recycle','回收站',-1,''),(122,'admin',2,'Admin/article/permit','还原',-1,''),(123,'admin',2,'Admin/article/clear','清空',-1,''),(124,'admin',2,'Admin/User/add','新增用户',-1,''),(125,'admin',2,'Admin/User/action','用户行为',-1,''),(126,'admin',2,'Admin/User/addAction','新增用户行为',-1,''),(127,'admin',2,'Admin/User/editAction','编辑用户行为',-1,''),(128,'admin',2,'Admin/User/saveAction','保存用户行为',-1,''),(129,'admin',2,'Admin/User/setStatus','变更行为状态',-1,''),(130,'admin',2,'Admin/User/changeStatus?method=forbidUser','禁用会员',-1,''),(131,'admin',2,'Admin/User/changeStatus?method=resumeUser','启用会员',-1,''),(132,'admin',2,'Admin/User/changeStatus?method=deleteUser','删除会员',-1,''),(133,'admin',2,'Admin/AuthManager/index','权限管理',-1,''),(134,'admin',2,'Admin/AuthManager/changeStatus?method=deleteGroup','删除',-1,''),(135,'admin',2,'Admin/AuthManager/changeStatus?method=forbidGroup','禁用',-1,''),(136,'admin',2,'Admin/AuthManager/changeStatus?method=resumeGroup','恢复',-1,''),(137,'admin',2,'Admin/AuthManager/createGroup','新增',-1,''),(138,'admin',2,'Admin/AuthManager/editGroup','编辑',-1,''),(139,'admin',2,'Admin/AuthManager/writeGroup','保存用户组',-1,''),(140,'admin',2,'Admin/AuthManager/group','授权',-1,''),(141,'admin',2,'Admin/AuthManager/access','访问授权',-1,''),(142,'admin',2,'Admin/AuthManager/user','成员授权',-1,''),(143,'admin',2,'Admin/AuthManager/removeFromGroup','解除授权',-1,''),(144,'admin',2,'Admin/AuthManager/addToGroup','保存成员授权',-1,''),(145,'admin',2,'Admin/AuthManager/category','分类授权',-1,''),(146,'admin',2,'Admin/AuthManager/addToCategory','保存分类授权',-1,''),(147,'admin',2,'Admin/AuthManager/modelauth','模型授权',-1,''),(148,'admin',2,'Admin/AuthManager/addToModel','保存模型授权',-1,''),(149,'admin',2,'Admin/Addons/create','创建',-1,''),(150,'admin',2,'Admin/Addons/checkForm','检测创建',-1,''),(151,'admin',2,'Admin/Addons/preview','预览',-1,''),(152,'admin',2,'Admin/Addons/build','快速生成插件',-1,''),(153,'admin',2,'Admin/Addons/config','设置',-1,''),(154,'admin',2,'Admin/Addons/disable','禁用',-1,''),(155,'admin',2,'Admin/Addons/enable','启用',-1,''),(156,'admin',2,'Admin/Addons/install','安装',-1,''),(157,'admin',2,'Admin/Addons/uninstall','卸载',-1,''),(158,'admin',2,'Admin/Addons/saveconfig','更新配置',-1,''),(159,'admin',2,'Admin/Addons/adminList','插件后台列表',-1,''),(160,'admin',2,'Admin/Addons/execute','URL方式访问插件',-1,''),(161,'admin',2,'Admin/Addons/hooks','钩子管理',-1,''),(162,'admin',2,'Admin/Model/index','模型管理',-1,''),(163,'admin',2,'Admin/model/add','新增',-1,''),(164,'admin',2,'Admin/model/edit','编辑',-1,''),(165,'admin',2,'Admin/model/setStatus','改变状态',-1,''),(166,'admin',2,'Admin/model/update','保存数据',-1,''),(167,'admin',2,'Admin/Attribute/index','属性管理',-1,''),(168,'admin',2,'Admin/Attribute/add','新增',-1,''),(169,'admin',2,'Admin/Attribute/edit','编辑',-1,''),(170,'admin',2,'Admin/Attribute/setStatus','改变状态',-1,''),(171,'admin',2,'Admin/Attribute/update','保存数据',-1,''),(172,'admin',2,'Admin/Config/index','配置管理',-1,''),(173,'admin',2,'Admin/Config/edit','编辑',-1,''),(174,'admin',2,'Admin/Config/del','删除',-1,''),(175,'admin',2,'Admin/Config/add','新增',-1,''),(176,'admin',2,'Admin/Config/save','保存',-1,''),(177,'admin',2,'Admin/Menu/index','菜单管理',-1,''),(178,'admin',2,'Admin/Channel/index','导航管理',-1,''),(179,'admin',2,'Admin/Channel/add','新增',-1,''),(180,'admin',2,'Admin/Channel/edit','编辑',-1,''),(181,'admin',2,'Admin/Channel/del','删除',-1,''),(182,'admin',2,'Admin/Category/index','分类管理',-1,''),(183,'admin',2,'Admin/Category/edit','编辑',-1,''),(184,'admin',2,'Admin/Category/add','新增',-1,''),(185,'admin',2,'Admin/Category/remove','删除',-1,''),(186,'admin',2,'Admin/Category/move','移动',-1,''),(187,'admin',2,'Admin/Category/merge','合并',-1,''),(188,'admin',2,'Admin/Database/index?type=export','备份数据库',-1,''),(189,'admin',2,'Admin/Database/export','备份',-1,''),(190,'admin',2,'Admin/Database/optimize','优化表',-1,''),(191,'admin',2,'Admin/Database/repair','修复表',-1,''),(192,'admin',2,'Admin/Database/index?type=import','还原数据库',-1,''),(193,'admin',2,'Admin/Database/import','恢复',-1,''),(194,'admin',2,'Admin/Database/del','删除',-1,''),(195,'admin',2,'Admin/other','其他',-1,''),(196,'admin',2,'Admin/Menu/add','新增',-1,''),(197,'admin',2,'Admin/Menu/edit','编辑',-1,''),(198,'admin',2,'Admin/Think/lists?model=article','应用',-1,''),(199,'admin',2,'Admin/Think/lists?model=download','下载管理',-1,''),(200,'admin',2,'Admin/Think/lists?model=config','应用',-1,''),(201,'admin',2,'Admin/Action/actionlog','行为日志',-1,''),(202,'admin',2,'Admin/User/updatePassword','修改密码',-1,''),(203,'admin',2,'Admin/User/updateNickname','修改昵称',-1,''),(204,'admin',2,'Admin/action/edit','查看行为日志',-1,''),(206,'admin',1,'Admin/think/edit','编辑数据',-1,''),(207,'admin',1,'Admin/Menu/import','导入',-1,''),(208,'admin',1,'Admin/Model/generate','生成',-1,''),(209,'admin',1,'Admin/Addons/addHook','新增钩子',-1,''),(210,'admin',1,'Admin/Addons/edithook','添加钩子',1,''),(211,'admin',1,'Admin/Article/sort','文档排序',-1,''),(212,'admin',1,'Admin/Config/sort','配置管理排序',1,''),(213,'admin',1,'Admin/Menu/sort','排序',-1,''),(214,'admin',1,'Admin/Channel/sort','排序',-1,''),(215,'admin',1,'Admin/Category/operate/type/move','移动',-1,''),(216,'admin',1,'Admin/Category/operate/type/merge','合并',-1,''),(217,'admin',1,'Admin/System/index','菜单管理',1,''),(218,'admin',1,'Admin/System/addMenu','添加菜单',1,''),(219,'admin',1,'Admin/Reply/upMenu','修改自定义菜单',1,''),(220,'admin',1,'Admin/Reply/welcome','欢迎语',1,''),(221,'admin',1,'Admin/Reply/menuList','自定义菜单',1,''),(222,'admin',1,'Admin/','商城设置',-1,''),(223,'admin',1,'Admin/Member/index','用户列表',1,''),(224,'admin',1,'Admin/Member/group','用户组',1,''),(225,'admin',1,'Admin/Member/level','用户等级',1,''),(226,'admin',1,'Admin/Member/add','新增用户',1,''),(227,'admin',1,'Admin/Member/addGroup','新增用户组',1,''),(228,'admin',1,'Admin/Member/addLevel','新增用户等级',1,''),(229,'admin',1,'Admin/Member/addMembPer','添加功能',1,''),(230,'admin',1,'Admin/Contact/activeList','活动配置',1,''),(231,'admin',1,'Admin/Shop/goods','基础模块',1,''),(232,'admin',1,'Admin/Order/list','订单管理',1,''),(233,'admin',1,'Admin/Shop/category','商品分类管理',1,''),(234,'admin',1,'Admin/Contact/addActive','添加活动',1,''),(235,'admin',1,'Admin/Shop/dispatch','配送方式',1,''),(236,'admin',1,'Admin/Shop/adv','幻灯片管理',1,''),(237,'admin',1,'Admin/Shop/notice','公告管理',1,''),(238,'admin',1,'Admin/Shop/comment','评价管理',1,''),(239,'admin',1,'Admin/Contact/applyList','报名管理',1,''),(240,'admin',1,'Admin/Reply/textReply','文字回复',1,''),(241,'admin',1,'Admin/Contact/statistics','报名统计',1,''),(242,'admin',1,'Admin/Reply/addTextReply','添加文字回复',1,''),(243,'admin',1,'Admin/Contact/editActive','编辑活动',1,''),(244,'admin',1,'Admin/Member/action','积分配置',1,''),(245,'admin',1,'Admin/Member/actionlog','用户行为日志',1,''),(246,'admin',1,'Admin/Member/edit','日志详细',1,''),(247,'admin',1,'Admin/Member/editAction','添加用户行为',1,''),(248,'admin',1,'Admin/System/payment','支付配置',1,''),(249,'admin',1,'Admin/ShopIndex/index','商城入口',-1,''),(250,'admin',1,'Admin/Member/listDetail','变更明细',1,''),(251,'admin',1,'Admin/Reply/addMenu','添加自定义菜单',1,''),(252,'admin',1,'Admin/Sysset/index','商城设置',1,''),(253,'admin',1,'Admin/Member/depositDetail','金额明细',1,''),(254,'admin',1,'Admin/Member/scoreDetail','积分明细',1,''),(255,'admin',1,'Admin/Reply/addImgReply','添加图文回复',1,''),(256,'admin',1,'Admin/Reply/sendMessage','发送客服消息',1,''),(257,'admin',1,'Admin/Article/siteCate','文章分类',1,''),(258,'admin',1,'Admin/Reply/sendMesPage','发送信息',1,''),(259,'admin',1,'Admin/Article/editCate','添加文章分类',1,''),(260,'admin',1,'Admin/Sysset/follow','引导及分享设置',1,''),(261,'admin',1,'Admin/Sysset/notice','提醒及模板消息设置',1,''),(262,'admin',1,'Admin/Sysset/trade','交易设置',1,''),(263,'admin',1,'Admin/Sysset/pay','支付方式设置',1,''),(264,'admin',1,'Admin/Sysset/template','模板设置',1,''),(265,'admin',1,'Admin/Sysset/member','会员设置',1,''),(266,'admin',1,'Admin/Sysset/category','分类层级设置',1,''),(267,'admin',1,'Admin/Sysset/contact','联系方式设置',1,''),(268,'admin',1,'Admin/Research/researchList','有赏众帮',-1,''),(269,'admin',1,'Admin/Research/detailResearch','有赏众帮详情',1,''),(270,'admin',1,'Admin/Research/discussList','评论管理',-1,''),(271,'admin',1,'Admin/Research/detailDiscuss','评论详情',1,''),(272,'admin',1,'Admin/Research/analysis','分析报告',-1,''),(273,'admin',1,'Admin/Research/analysisDetails','报告详情',1,''),(274,'admin',1,'Admin/Refuel/refuelList','加油管理',-1,''),(275,'admin',1,'Admin/Refuel/refuelDetail','加油详情',-1,''),(276,'admin',1,'Admin/System/edit','编辑菜单',1,''),(277,'admin',1,'Admin/Member/mfield','会员字段管理',1,''),(278,'admin',1,'Admin/Member/savefield','修改会员字段',1,''),(279,'admin',1,'Admin/Member/membPerson','个人中心管理',1,''),(280,'admin',1,'Admin/Account/addAccount','公众号添加',1,''),(281,'admin',1,'Admin/Account/serviceIn','常用服务接入',1,''),(282,'admin',1,'Admin/Addons/addonlist','插件列表',1,''),(283,'admin',1,'Admin/Member/statistics','粉丝统计',1,''),(284,'admin',1,'Admin/Reply/imgReply','图文回复',1,''),(285,'admin',1,'Admin/Account/account','公众号设置',1,''),(286,'admin',2,'Admin/IntroIndex/index','首页',1,''),(287,'admin',2,'Admin/Member/index','用户中心',1,''),(288,'admin',2,'Admin/Shop/goods','商城模块',1,''),(289,'admin',1,'Admin/Reply/fans','粉丝列表',1,''),(290,'admin',2,'Admin/System/index','系统',1,''),(291,'admin',2,'Admin/Reply/textReply','微信',1,''),(292,'admin',2,'Admin/Research/researchList','社交',-1,''),(293,'admin',2,'Admin/Addons/addonlist','其它功能',1,''),(294,'admin',1,'Admin/Shop/index','商城入口',1,''),(295,'admin',1,'Admin/PunchAdmin/punchClockList','动态审核',1,''),(296,'admin',1,'Admin/PunchAdmin/punchComment','评论审核',1,''),(297,'admin',1,'Admin/plugin/designer','酒店管理',-1,''),(298,'admin',1,'Admin/plugin/integral','积分商城',-1,''),(299,'admin',1,'Admin/Member/addUser','添加管理员',1,''),(300,'admin',1,'Admin/Member/userList','管理员列表',1,''),(301,'admin',2,'Admin/Contact/activeList','社交管理',1,''),(302,'admin',1,'Admin/supplier/index','供应商管理',-1,''),(303,'admin',1,'Admin/Shop/supplier','供应商管理',1,''),(304,'admin',1,'Admin/Contact/cateList','活动分类',1,''),(305,'admin',1,'Admin/Contact/addCategory','添加分类',1,'');

UNLOCK TABLES;

/*Table structure for table `sx_basic_reply` */

DROP TABLE IF EXISTS `sx_basic_reply`;

CREATE TABLE `sx_basic_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `content` varchar(1000) NOT NULL COMMENT '回复内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='文本回复表';

/*Data for the table `sx_basic_reply` */

LOCK TABLES `sx_basic_reply` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_chats_record` */

DROP TABLE IF EXISTS `sx_chats_record`;

CREATE TABLE `sx_chats_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `flag` tinyint(3) unsigned NOT NULL COMMENT '发送者 1粉丝 2客服',
  `openid` varchar(32) NOT NULL COMMENT '粉丝openid',
  `msgtype` varchar(15) NOT NULL COMMENT '消息类型',
  `content` varchar(10000) NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=457 DEFAULT CHARSET=utf8 COMMENT='客服消息记录表';

/*Data for the table `sx_chats_record` */

LOCK TABLES `sx_chats_record` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_check_bill` */

DROP TABLE IF EXISTS `sx_check_bill`;

CREATE TABLE `sx_check_bill` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `membid` int(13) NOT NULL COMMENT '用户id',
  `type` int(13) NOT NULL COMMENT '账单类型 1增加 2.减少',
  `credittype` varchar(20) NOT NULL COMMENT '操作的类型 “score”标示积分 “deposit”标示余额',
  `num` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '更变额度.',
  `operator` int(13) NOT NULL COMMENT '操作员',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  `remark` varchar(500) NOT NULL COMMENT '备注（用来批注该操作的详细信息）',
  `model` varchar(100) DEFAULT NULL COMMENT '进行操作的模型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=884 DEFAULT CHARSET=utf8 COMMENT='账单明细表（用于记录用户积分和余额的出入明细）（该表请不要直接操作）';

/*Data for the table `sx_check_bill` */

LOCK TABLES `sx_check_bill` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_config` */

DROP TABLE IF EXISTS `sx_config`;

CREATE TABLE `sx_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型 0.数字 1.字符 2.文本 3.数组 4.枚举',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组 0.不分组 1.基础 2.内容 3.用户 4.系统',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `hide` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否隐藏 0.表示否 1表示是（该字段用于标注该配置项是否在后台配置列表中显示出来）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

/*Data for the table `sx_config` */

LOCK TABLES `sx_config` WRITE;

insert  into `sx_config`(`id`,`name`,`type`,`title`,`group`,`extra`,`remark`,`create_time`,`update_time`,`status`,`value`,`sort`,`hide`) values (1,'WEB_SITE_TITLE',1,'网站标题',1,'','网站标题前台显示标题',1378898976,1379235274,1,'这里填你的网站标题，比如搜雪',2,0),(2,'WEB_SITE_DESCRIPTION',2,'网站描述',1,'','网站搜索引擎描述',1378898976,1379235841,1,'网站描述，比如说搜雪是一款开发引擎',15,0),(3,'WEB_SITE_KEYWORD',2,'网站关键字',1,'','网站搜索引擎关键字',1378898976,1381390100,1,'关键词放在这里，做seo化有关',23,0),(4,'WEB_SITE_CLOSE',4,'关闭站点',1,'0:关闭,1:开启','站点关闭后其他用户不能访问，管理员可以正常访问',1378898976,1379235296,1,'1',17,0),(9,'CONFIG_TYPE_LIST',3,'配置类型列表',4,'','主要用于数据解析和页面表单的生成',1378898976,1379235348,1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举',4,0),(10,'WEB_SITE_ICP',1,'网站备案号',1,'','设置在网站底部显示的备案号，如“沪ICP备12007941号-2',1378900335,1379235859,1,'填写贵站的ipc证号',27,0),(11,'DOCUMENT_POSITION',3,'文档推荐位',2,'','文档推荐位，推荐到多个位置KEY值相加即可',1379053380,1379235329,1,'1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐',1,0),(12,'DOCUMENT_DISPLAY',3,'文档可见性',2,'','文章可见性仅影响前台显示，后台不收影响',1379056370,1379235322,1,'0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见',11,0),(13,'COLOR_STYLE',4,'后台色系',1,'default_color:默认\r\nblue_color:紫罗兰','后台颜色风格',1379122533,1379235904,1,'blue_color',30,0),(20,'CONFIG_GROUP_LIST',3,'配置分组',4,'','配置分组',1379228036,1384418383,1,'1:基本\r\n2:内容\r\n3:用户\r\n4:系统',16,0),(21,'HOOKS_TYPE',3,'钩子的类型',4,'','类型 1-用于扩展显示内容，2-用于扩展业务处理',1379313397,1379313407,1,'1:视图\r\n2:控制器',19,0),(22,'AUTH_CONFIG',3,'Auth配置',4,'','自定义Auth.class.php类配置',1379409310,1379409564,1,'AUTH_ON:1\r\nAUTH_TYPE:2',26,0),(23,'OPEN_DRAFTBOX',4,'是否开启草稿功能',2,'0:关闭草稿功能\r\n1:开启草稿功能\r\n','新增文章时的草稿功能配置',1379484332,1379484591,1,'1',20,0),(24,'DRAFT_AOTOSAVE_INTERVAL',0,'自动保存草稿时间',2,'','自动保存草稿的时间间隔，单位：秒',1379484574,1386143323,1,'60',3,0),(25,'LIST_ROWS',0,'后台每页记录数',2,'','后台数据每页显示记录数',1379503896,1452234370,1,'20',31,0),(26,'USER_ALLOW_REGISTER',4,'是否允许用户注册',3,'0:关闭注册\r\n1:允许注册','是否开放用户注册',1379504487,1379504580,1,'1',7,0),(27,'CODEMIRROR_THEME',4,'预览插件的CodeMirror主题',4,'3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight','详情见CodeMirror官网',1379814385,1384740813,1,'ambiance',9,0),(28,'DATA_BACKUP_PATH',0,'数据库备份根路径',0,'','路径必须以 / 结尾',1381482411,1447049768,1,'./Runtime/Data/',18,0),(29,'DATA_BACKUP_PART_SIZE',0,'数据库备份卷大小',4,'','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M',1381482488,1381729564,1,'20971520',22,0),(30,'DATA_BACKUP_COMPRESS',4,'数据库备份文件是否启用压缩',4,'0:不压缩\r\n1:启用压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数',1381713345,1381729544,1,'1',29,0),(31,'DATA_BACKUP_COMPRESS_LEVEL',4,'数据库备份文件压缩级别',4,'1:普通\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效',1381713408,1381713408,1,'9',32,0),(32,'DEVELOP_MODE',4,'开启开发者模式',4,'0:关闭\r\n1:开启','是否开启开发者模式',1383105995,1383291877,1,'1',24,0),(33,'ALLOW_VISIT',3,'不受限控制器方法',0,'','',1386644047,1386644741,1,'0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture',5,0),(34,'DENY_VISIT',3,'超管专限控制器方法',0,'','仅超级管理员可访问的控制器方法',1386644141,1386644659,1,'0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree',6,0),(35,'REPLY_LIST_ROWS',0,'回复列表每页条数',2,'','',1386645376,1387178083,1,'10',8,0),(36,'ADMIN_ALLOW_IP',2,'后台允许访问IP',4,'','多个用逗号分隔，如果不配置表示不限制IP访问',1387165454,1387165553,1,'',13,0),(37,'SHOW_PAGE_TRACE',4,'是否显示页面Trace',4,'0:关闭\r\n1:开启','是否显示页面Trace信息',1387165685,1387165685,1,'0',21,0),(38,'REMITTANCEIC',2,'汇款账号',0,'','',1447046900,1447048586,1,'工商银行：132685649415411',10,0),(39,'POUNDAGERATIO',0,'手续费比例',0,'','手续费收取的比例',1447223424,1447229958,1,'0.02',12,0),(40,'MEMB_DEFAULR_GROUPID',0,'用户默认分组',2,'','默认用户分组',1379503896,1380427745,1,'1',25,0),(41,'MEMB_DEFAULR_LEVELID',0,'用户等级分组',2,'','用户等级分组',1379503896,1380427745,1,'1',28,0),(42,'COLUMN_SITE_NAME',0,'',0,'','',0,0,0,'',14,0),(43,'EVERYDAYSCORE',0,'为自己加油积分',0,'','',1450165017,1450165276,1,'10',0,0),(44,'ALIPAY',3,'支付宝支付',4,'支付宝支付相关参数配饰','',1450233288,1450233288,1,'t:\raccount:\rpartner:\rsecret:\r',0,0),(45,'CREDIT',3,'余额支付',4,'','余额支付参数',1450233375,1456195580,1,'switch:0\r\n',0,0),(46,'WECHAT',3,'微信支付',4,'','微信支付参数配置',1450233681,1456195602,1,'version:2\raccount:\rappid:\rappSecret:\rpartner:\rpartnerKey:\rmchid:\rpaySignKey:\rapikey:\r',0,0),(47,'GIVEFRINDSCORE',0,'给好友加油',0,'','',1450683882,1450683882,1,'1',0,0),(48,'SHARESCORE',0,'分享好友增加积分',0,'','',1451552273,1451552273,1,'10',0,0),(49,'ADDONS_TITLE',3,'插件配置',0,'','',1455598404,1455598442,1,'0:社交咨询\r\n1:账号管理\r\n2:互动类\r\n3:教育行业\r\n4:其他功能\r\n',0,0);

UNLOCK TABLES;

/*Table structure for table `sx_core_attachment` */

DROP TABLE IF EXISTS `sx_core_attachment`;

CREATE TABLE `sx_core_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=211 DEFAULT CHARSET=utf8;

/*Data for the table `sx_core_attachment` */

LOCK TABLES `sx_core_attachment` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_core_paylog` */

DROP TABLE IF EXISTS `sx_core_paylog`;

CREATE TABLE `sx_core_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付自增id',
  `type` varchar(20) NOT NULL COMMENT '支付类型wechat,alipay,credit,cash  微信，支付宝，余额，现金',
  `transid` int(11) NOT NULL COMMENT '支付单号',
  `openid` varchar(40) NOT NULL COMMENT 'openid',
  `tid` varchar(64) NOT NULL COMMENT '支付订单id',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `status` tinyint(4) NOT NULL COMMENT '支付状态',
  `module` varchar(50) NOT NULL COMMENT '模块',
  `tag` varchar(2000) NOT NULL COMMENT '相关参数序列化 （暂时可为微信订单码）transaction_id',
  `is_usecard` tinyint(3) unsigned NOT NULL COMMENT '是否使用会员卡',
  `card_type` tinyint(3) unsigned NOT NULL COMMENT '会员卡类型',
  `card_id` varchar(50) NOT NULL COMMENT '会员卡id',
  `card_fee` decimal(10,2) unsigned NOT NULL COMMENT '使用金额',
  `encrypt_code` varchar(100) NOT NULL COMMENT '优惠券',
  `uniontid` varchar(64) NOT NULL COMMENT '优惠券id',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `ceshi` varchar(255) DEFAULT '' COMMENT '测试微信返回数据',
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_tid` (`tid`),
  KEY `idx_uniacid` (`transid`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

/*Data for the table `sx_core_paylog` */

LOCK TABLES `sx_core_paylog` WRITE;

insert  into `sx_core_paylog`(`plid`,`type`,`transid`,`openid`,`tid`,`fee`,`status`,`module`,`tag`,`is_usecard`,`card_type`,`card_id`,`card_fee`,`encrypt_code`,`uniontid`,`createtime`,`ceshi`) values (62,'',0,'977','SH201602271252494181','45.00',0,'zxin_shop','',0,0,'','0.00','','',0,''),(41,'wechat',2147483647,'oUFFVv-ObkzyFzbElG-evCXbLBHg','20160302100821170','0.01',1,'Activity','a:16:{s:5:\"appid\";s:18:\"wxe2fface4450d8cda\";s:9:\"bank_type\";s:3:\"CFT\";s:8:\"cash_fee\";s:1:\"1\";s:8:\"fee_type\";s:3:\"CNY\";s:12:\"is_subscribe\";s:1:\"Y\";s:6:\"mch_id\";s:10:\"1268063401\";s:9:\"nonce_str\";s:8:\"nCSdIc9s\";s:6:\"openid\";s:28:\"oUFFVv-ObkzyFzbElG-evCXbLBHg\";s:12:\"out_trade_no\";s:17:\"20160302100821170\";s:11:\"result_code\";s:7:\"SUCCESS\";s:11:\"return_code\";s:7:\"SUCCESS\";s:4:\"sign\";s:32:\"6DC47711CF9C51950661BFB328C7C61B\";s:8:\"time_end\";s:14:\"20160302100854\";s:9:\"total_fee\";s:1:\"1\";s:10:\"trade_type\";s:5:\"JSAPI\";s:14:\"transaction_id\";s:28:\"1002390878201603023662020357\";}',0,0,'','0.00','','',1456884501,''),(59,'',0,'987','SH201603021509382965','30.00',0,'zxin_shop','',0,0,'','0.00','','',0,''),(54,'',0,'987','SH201603030951243848','9.90',0,'zxin_shop','',0,0,'','0.00','','',0,''),(61,'',0,'926','SH201603041616126249','614.90',0,'zxin_shop','',0,0,'','0.00','','',0,''),(60,'alipay',0,'oUFFVv-ObkzyFzbElG-evCXbLBHg','20160307141150174','100.00',0,'Activity','',0,0,'','0.00','','',1457331110,''),(42,'alipay',0,'oUFFVv-ObkzyFzbElG-evCXbLBHg','20160302101051171','0.01',1,'Activity','a:22:{s:12:\"payment_type\";s:1:\"1\";s:7:\"subject\";s:21:\"横店半程马拉松\";s:8:\"trade_no\";s:28:\"2016030221001004020202606855\";s:11:\"buyer_email\";s:11:\"15505710459\";s:10:\"gmt_create\";s:19:\"2016-03-02 10:11:14\";s:11:\"notify_type\";s:17:\"trade_status_sync\";s:8:\"quantity\";s:1:\"1\";s:12:\"out_trade_no\";s:17:\"20160302101051171\";s:9:\"seller_id\";s:16:\"2088411612214192\";s:11:\"notify_time\";s:19:\"2016-03-02 10:11:15\";s:4:\"body\";s:6:\"alipay\";s:12:\"trade_status\";s:13:\"TRADE_SUCCESS\";s:19:\"is_total_fee_adjust\";s:1:\"N\";s:9:\"total_fee\";s:4:\"0.01\";s:11:\"gmt_payment\";s:19:\"2016-03-02 10:11:14\";s:12:\"seller_email\";s:15:\"bgt@baguatan.cn\";s:5:\"price\";s:4:\"0.01\";s:8:\"buyer_id\";s:16:\"2088712011575023\";s:9:\"notify_id\";s:34:\"a4d8df906556077b5bca02df7342ebbg5m\";s:10:\"use_coupon\";s:1:\"N\";s:9:\"sign_type\";s:3:\"MD5\";s:4:\"sign\";s:32:\"0cb6d5a04ef344af75d12950f2d30645\";}',0,0,'','0.00','','',1456884651,''),(63,'',0,'946','SH201603171109286639','54.90',0,'zxin_shop','',0,0,'','0.00','','',0,'');

UNLOCK TABLES;

/*Table structure for table `sx_file` */

DROP TABLE IF EXISTS `sx_file`;

CREATE TABLE `sx_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文件表';

/*Data for the table `sx_file` */

LOCK TABLES `sx_file` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_hooks` */

DROP TABLE IF EXISTS `sx_hooks`;

CREATE TABLE `sx_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `sx_hooks` */

LOCK TABLES `sx_hooks` WRITE;

insert  into `sx_hooks`(`id`,`name`,`description`,`type`,`update_time`,`addons`) values (1,'pageHeader','页面header钩子，一般用于加载插件CSS文件和代码',1,1463539390,''),(2,'pageFooter','页面footer钩子，一般用于加载插件JS文件和JS代码',1,1463539390,'ReturnTop'),(3,'documentEditForm','添加编辑表单的 扩展内容钩子',1,1463539390,'Attachment'),(4,'documentDetailAfter','文档末尾显示',1,1463539390,'Attachment,SocialComment'),(5,'documentDetailBefore','页面内容前显示用钩子',1,1463539390,''),(6,'documentSaveComplete','保存文档数据后的扩展钩子',2,1463539390,'Attachment'),(7,'documentEditFormContent','添加编辑表单的内容显示钩子',1,1463539390,'Editor'),(8,'adminArticleEdit','后台内容编辑页编辑器',1,1463539390,'EditorForAdmin'),(13,'AdminIndex','首页小格子个性化显示',1,1463539390,'SiteStat,SystemInfo,DevTeam'),(14,'topicComment','评论提交方式扩展钩子。',1,1463539390,'Editor'),(16,'app_begin','应用开始',2,1463539390,''),(17,'Punchcard','打卡',2,1463539390,'Punchcard');

UNLOCK TABLES;

/*Table structure for table `sx_info_property` */

DROP TABLE IF EXISTS `sx_info_property`;

CREATE TABLE `sx_info_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '报名信息类型属性表Id',
  `value` varchar(100) NOT NULL COMMENT '属性值',
  `text` varchar(100) NOT NULL COMMENT '显示值',
  `addTime` int(11) NOT NULL COMMENT '添加时间',
  `apply_info_id` int(10) NOT NULL COMMENT '对应报名信息ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='报名信息类型属性表';

/*Data for the table `sx_info_property` */

LOCK TABLES `sx_info_property` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_keyword` */

DROP TABLE IF EXISTS `sx_keyword`;

CREATE TABLE `sx_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `keyword` varchar(100) NOT NULL COMMENT '关键词',
  `token` varchar(100) NOT NULL COMMENT 'Token',
  `addon` varchar(255) NOT NULL COMMENT '关键词所属插件',
  `aim_id` int(10) unsigned NOT NULL COMMENT '插件表里的ID值',
  `cTime` int(10) NOT NULL COMMENT '创建时间',
  `keyword_length` int(10) unsigned DEFAULT '0' COMMENT '关键词长度',
  `keyword_type` tinyint(2) DEFAULT '0' COMMENT '匹配类型',
  `extra_text` text COMMENT '文本扩展',
  `extra_int` int(10) DEFAULT NULL COMMENT '数字扩展',
  `request_count` int(10) NOT NULL DEFAULT '0' COMMENT '请求数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword_token` (`keyword`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=470 DEFAULT CHARSET=utf8;

/*Data for the table `sx_keyword` */

LOCK TABLES `sx_keyword` WRITE;

insert  into `sx_keyword`(`id`,`keyword`,`token`,`addon`,`aim_id`,`cTime`,`keyword_length`,`keyword_type`,`extra_text`,`extra_int`,`request_count`) values (469,'考试拉','533672300e803','Exam',1,1397057270,9,0,'',0,0);

UNLOCK TABLES;

/*Table structure for table `sx_member` */

DROP TABLE IF EXISTS `sx_member`;

CREATE TABLE `sx_member` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `referrerid` int(13) unsigned DEFAULT NULL COMMENT '推荐人id',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `openid` varchar(50) DEFAULT '' COMMENT '微信openid',
  `gender` tinyint(1) DEFAULT '0' COMMENT '性别(0:保密 1:男 2:女)',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机或电话',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `qq` varchar(30) NOT NULL DEFAULT '' COMMENT 'qq',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '身份证证件号码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '账号状态 0:禁用,1:正常',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `paypwd` varchar(50) NOT NULL DEFAULT '' COMMENT '支付密码',
  `groupid` int(13) unsigned NOT NULL DEFAULT '0' COMMENT '分组 0表示未分组（对应用户分组表）',
  `levelid` int(13) unsigned NOT NULL DEFAULT '0' COMMENT '级别 0表示无等级（对应用户等级表）',
  `deposit` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账户存款（余额）',
  `score` decimal(13,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  `locktime` int(13) NOT NULL COMMENT '最后一次锁定时间',
  `lockip` varchar(30) NOT NULL DEFAULT '0.0.0.0' COMMENT '最后一次登入的ip',
  `login` int(13) NOT NULL DEFAULT '0' COMMENT '登入次数',
  `logintime` int(13) NOT NULL COMMENT '最后一次登入时间',
  `followed` tinyint(1) DEFAULT '1' COMMENT '是否关注',
  `birth_date` varchar(255) DEFAULT NULL COMMENT '出生日期',
  `constellation` varbinary(5) DEFAULT NULL COMMENT '星座',
  `zodiac` varchar(50) DEFAULT NULL COMMENT '生肖',
  `telephone` varchar(15) DEFAULT NULL COMMENT '固定电话',
  `studentid` varchar(50) DEFAULT NULL COMMENT '学号',
  `grade` varchar(10) DEFAULT NULL COMMENT '班级',
  `address` varchar(50) DEFAULT NULL COMMENT '详细地址',
  `zipcode` varchar(50) DEFAULT NULL COMMENT '邮编',
  `nationality` varchar(30) DEFAULT NULL COMMENT '国籍',
  `resideprovince` varchar(100) DEFAULT NULL COMMENT '居住省份',
  `residecity` varchar(100) DEFAULT NULL COMMENT '居住城市',
  `residedist` varchar(100) DEFAULT NULL COMMENT '居住行政区/县',
  `graduateschool` varchar(50) DEFAULT NULL COMMENT '毕业学校',
  `company` varchar(50) DEFAULT NULL COMMENT '公司',
  `education` varchar(10) DEFAULT NULL COMMENT '学历',
  `occupation` varchar(30) DEFAULT NULL COMMENT '职业',
  `position` varchar(30) DEFAULT NULL COMMENT '职位',
  `revenue` varchar(30) DEFAULT NULL COMMENT '年收入',
  `emotion` varchar(30) DEFAULT NULL COMMENT '情感状态',
  `alipay` varchar(30) DEFAULT NULL COMMENT '支付宝',
  `msn` varchar(50) DEFAULT NULL COMMENT 'msn',
  `taobao` varchar(50) DEFAULT NULL COMMENT '淘宝账号',
  `bloodtype` varchar(10) DEFAULT NULL COMMENT '血型',
  `height` varchar(10) DEFAULT NULL COMMENT '身高',
  `weight` varchar(50) DEFAULT NULL COMMENT '体重',
  `site` text COMMENT '主页',
  `bio` text COMMENT '自我介绍',
  `interest` text COMMENT '兴趣爱好',
  `birthyear` varchar(255) DEFAULT NULL COMMENT '年',
  `birthmonth` varchar(255) DEFAULT NULL COMMENT '月',
  `birthday` varchar(255) DEFAULT NULL COMMENT '日',
  `weixin` varchar(255) DEFAULT NULL COMMENT '微信',
  `noticeset` text COMMENT '消息提醒',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户基础信息表';

/*Data for the table `sx_member` */

LOCK TABLES `sx_member` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_member_enlarge` */

DROP TABLE IF EXISTS `sx_member_enlarge`;

CREATE TABLE `sx_member_enlarge` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `mfield` varchar(30) NOT NULL COMMENT '字段标识(拥有唯一性)',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1.显示 0隐藏',
  `sort` int(13) NOT NULL DEFAULT '0' COMMENT '排序',
  `showname` varchar(100) NOT NULL COMMENT '显示的名称',
  `updatetime` int(13) unsigned NOT NULL COMMENT '更新时间',
  `desc` varchar(500) NOT NULL COMMENT '字段备注',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '字段类型 0.文本框 1.选择框 2.文本域 3.图片框 4.日期框 5.单选按钮 6.复选按钮',
  `control` text COMMENT '类型控制 json数据',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_membid` (`mfield`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8 COMMENT='用户衍生信息表';

/*Data for the table `sx_member_enlarge` */

LOCK TABLES `sx_member_enlarge` WRITE;

insert  into `sx_member_enlarge`(`id`,`mfield`,`status`,`sort`,`showname`,`updatetime`,`desc`,`type`,`control`) values (123,'realname',0,1,'真实姓名',1463539390,'真实姓名',0,'{\"status\":1,\"type\":0}'),(124,'nickname',1,0,'用户昵称',1463539390,'用户昵称',0,'{\"status\":1,\"type\":0}'),(126,'gender',1,0,'性别',1463539390,'性别(0:保密 1:男 2:女)',1,'{\"status\":1,\"type\":1,\"option\":[{\"oname\":\"\\u4fdd\\u5bc6\",\"ovalue\":\"0\"},{\"oname\":\"\\u7537\",\"ovalue\":\"1\"},{\"oname\":\"\\u5973\",\"ovalue\":\"2\"}]}'),(127,'mobile',1,0,'手机',1463539390,'手机或电话',0,'{\"status\":1,\"type\":0}'),(128,'email',0,0,'电子邮箱',1463539390,'电子邮箱',0,'{\"status\":1,\"type\":0}'),(129,'qq',0,0,'qq',1463539390,'qq',0,'{\"status\":1,\"type\":0}'),(130,'idcard',0,0,'身份证号',1463539390,'身份证证件号码',0,'{\"status\":1,\"type\":0}'),(138,'avatar',0,0,'头像',1463539390,'头像',3,'{\"status\":0,\"type\":3}'),(145,'birth_date',1,0,'出生日期',1463539390,'出生日期',4,'{\"status\":1,\"type\":4}'),(146,'constellation',0,0,'星座',1463539390,'星座',0,'{\"status\":1,\"type\":0}'),(147,'zodiac',0,0,'生肖',1463539390,'生肖',0,'{\"status\":1,\"type\":0}'),(148,'telephone',0,0,'固定电话',1463539390,'固定电话',0,'{\"status\":1,\"type\":0}'),(149,'studentid',0,0,'学号',1463539390,'学号',0,'{\"status\":1,\"type\":0}'),(150,'grade',0,0,'班级',1463539390,'班级',0,'{\"status\":1,\"type\":0}'),(151,'address',0,0,'详细地址',1463539390,'详细地址',0,'{\"status\":1,\"type\":0}'),(152,'zipcode',0,0,'邮编',1463539390,'邮编',0,'{\"status\":1,\"type\":0}'),(153,'nationality',0,0,'国籍',1463539390,'国籍',0,'{\"status\":1,\"type\":0}'),(154,'resideprovince',0,0,'居住省份',1463539390,'居住省份',0,'{\"status\":1,\"type\":0}'),(155,'residecity',0,0,'居住城市',1463539390,'居住城市',0,'{\"status\":1,\"type\":0}'),(156,'residedist',0,0,'行政区/县',1463539390,'居住行政区/县',0,'{\"status\":1,\"type\":0}'),(157,'graduateschool',0,0,'毕业学校',1463539390,'毕业学校',0,'{\"status\":1,\"type\":0}'),(158,'company',0,0,'公司',1463539390,'公司',0,'{\"status\":1,\"type\":0}'),(159,'education',0,0,'学历',1463539390,'学历',0,'{\"status\":1,\"type\":0}'),(160,'occupation',0,0,'职业',1463539390,'职业',0,'{\"status\":1,\"type\":0}'),(161,'position',0,0,'职位',1463539390,'职位',0,'{\"status\":1,\"type\":0}'),(162,'revenue',0,0,'年收入',1463539390,'年收入',0,'{\"status\":1,\"type\":0}'),(163,'emotion',0,0,'情感状态',1463539390,'情感状态',0,'{\"status\":1,\"type\":0}'),(164,'alipay',0,0,'支付宝',1463539390,'支付宝',0,'{\"status\":1,\"type\":0}'),(165,'msn',0,0,'msn',1463539390,'msn',0,'{\"status\":1,\"type\":0}'),(166,'taobao',0,0,'淘宝账号',1463539390,'淘宝账号',0,'{\"status\":1,\"type\":0}'),(167,'bloodtype',0,0,'血型',1463539390,'血型',0,'{\"status\":1,\"type\":0}'),(168,'height',0,0,'身高',1463539390,'身高',0,'{\"status\":1,\"type\":0}'),(169,'weight',0,0,'体重',1463539390,'体重',0,'{\"status\":1,\"type\":0}'),(170,'site',0,0,'主页',1463539390,'主页',0,'{\"status\":1,\"type\":0}'),(171,'bio',0,0,'自我介绍',1463539390,'自我介绍',2,'{\"status\":1,\"type\":2}'),(172,'interest',0,0,'兴趣爱好',1463539390,'兴趣爱好',2,'{\"status\":1,\"type\":2}');

UNLOCK TABLES;

/*Table structure for table `sx_member_fans` */

DROP TABLE IF EXISTS `sx_member_fans`;

CREATE TABLE `sx_member_fans` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `membid` int(13) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) DEFAULT NULL COMMENT '用户的唯一身份ID',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '粉丝昵称',
  `follow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否订阅 1关注 2取消关注',
  `followtime` int(10) NOT NULL DEFAULT '0' COMMENT '订阅时间',
  `unfollowtime` int(10) DEFAULT NULL COMMENT '取消订阅时间',
  `tag` varchar(1500) DEFAULT NULL COMMENT '微信返回过来的信息经过serialize()加密在经过base64_encode()函数加密后保存到这个字段',
  `updatetime` int(10) DEFAULT NULL COMMENT '粉丝信息最后更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='粉丝表';

/*Data for the table `sx_member_fans` */

LOCK TABLES `sx_member_fans` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_member_groups` */

DROP TABLE IF EXISTS `sx_member_groups`;

CREATE TABLE `sx_member_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '等级状态 -1禁用,0：隐藏,1：正常',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `sx_member_groups` */

LOCK TABLES `sx_member_groups` WRITE;

insert  into `sx_member_groups`(`id`,`title`,`sort`,`status`,`createtime`) values (1,'默认用户组',0,1,1463539390);

UNLOCK TABLES;

/*Table structure for table `sx_member_level` */

DROP TABLE IF EXISTS `sx_member_level`;

CREATE TABLE `sx_member_level` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '会员级别',
  `grade` int(13) NOT NULL COMMENT '会员数字等级标示',
  `levelicon` varchar(225) NOT NULL DEFAULT '' COMMENT '级别图标',
  `createtime` int(13) NOT NULL DEFAULT '0',
  `status` tinyint(2) DEFAULT '1' COMMENT '等级状态 -1禁用,0：隐藏,1：正常',
  `sort` int(13) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_level_grade` (`grade`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

/*Data for the table `sx_member_level` */

LOCK TABLES `sx_member_level` WRITE;

insert  into `sx_member_level`(`id`,`title`,`grade`,`levelicon`,`createtime`,`status`,`sort`) values (1,'注册会员',1,'',0,1,0);

UNLOCK TABLES;

/*Table structure for table `sx_member_personal` */

DROP TABLE IF EXISTS `sx_member_personal`;

CREATE TABLE `sx_member_personal` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '显示的图标',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '显示名称',
  `url` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '外部访问链接地址',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `sort` int(13) NOT NULL COMMENT '排序',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sx_member_personal` */

LOCK TABLES `sx_member_personal` WRITE;

insert  into `sx_member_personal`(`id`,`icon`,`name`,`url`,`status`,`sort`,`createtime`) values (1,'./Public/Home/Images/iconfont-dingdan.png','我 的 订 单','',1,0,1463539390),(2,'./Public/Home/Images/iconfont-shouhuodizhi.png','收 货 地 址','',1,0,1463539390),(3,'./Public/Home/Images/iconfont-bf-foot.png','+ U 足 迹','',1,0,1463539390);

UNLOCK TABLES;

/*Table structure for table `sx_member_public` */

DROP TABLE IF EXISTS `sx_member_public`;

CREATE TABLE `sx_member_public` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `public_name` varchar(50) NOT NULL COMMENT '名称',
  `description` varchar(50) NOT NULL DEFAULT '' COMMENT '描述',
  `public_id` varchar(100) NOT NULL COMMENT '原始id',
  `headface_url` varchar(255) NOT NULL COMMENT '公众号头像',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '二维码',
  `appid` varchar(255) NOT NULL COMMENT 'AppID',
  `secret` varchar(255) NOT NULL COMMENT 'AppSecret',
  `encodingaeskey` varchar(255) DEFAULT NULL COMMENT 'EncodingAESKey',
  `level` int(10) NOT NULL DEFAULT '1' COMMENT '1订阅号 2服务号 3认证订阅号 4认证服务号',
  `token` varchar(100) NOT NULL COMMENT 'Token',
  `welcome` int(20) NOT NULL DEFAULT '0' COMMENT '欢迎语',
  `subscribeurl` varchar(255) NOT NULL DEFAULT '' COMMENT '公号官方欢迎页面',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '登录密码',
  `fakeid` int(50) NOT NULL COMMENT 'fakeid',
  `access_token` varchar(1000) DEFAULT NULL COMMENT '从微信公众平台获取的tocken',
  `jsapi_ticket` varchar(1000) DEFAULT NULL COMMENT '微信JS-SDK访问票据',
  `menuset` text NOT NULL COMMENT '微信菜单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;

/*Data for the table `sx_member_public` */

LOCK TABLES `sx_member_public` WRITE;

insert  into `sx_member_public`(`id`,`public_name`,`description`,`public_id`,`headface_url`,`qrcode`,`appid`,`secret`,`encodingaeskey`,`level`,`token`,`welcome`,`subscribeurl`,`username`,`password`,`fakeid`,`access_token`,`jsapi_ticket`,`menuset`) values (150,'玩聚部落','独玩不如众玩，独乐不如众乐。《玩聚部落》创立于2006年,前身为浙江电台新闻台自驾先锋,系全国汽车俱','','','','','','',4,'',0,'http://mp.weixin.qq.com/s?__biz=MzIxMDAzOTYzMg==&mid=400282238&idx=1&sn=03b167ffe9d8df6aa13a7936d8ddd7c3#wechat_redirect','','',0,'',NULL,'');

UNLOCK TABLES;

/*Table structure for table `sx_menus` */

DROP TABLE IF EXISTS `sx_menus`;

CREATE TABLE `sx_menus` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT '菜单主键ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名',
  `pid` int(6) NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `sort` int(6) NOT NULL DEFAULT '0' COMMENT '菜单排序',
  `ico` varchar(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接路径',
  `group` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单分组',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `menutype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0.系统菜单 1.插件菜单',
  `addonsurl` varchar(50) DEFAULT NULL COMMENT '插件地址',
  `identify` varchar(50) DEFAULT NULL COMMENT '插件标识',
  `tip` varchar(500) NOT NULL COMMENT '提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8;

/*Data for the table `sx_menus` */

LOCK TABLES `sx_menus` WRITE;

insert  into `sx_menus`(`id`,`title`,`pid`,`sort`,`ico`,`url`,`group`,`status`,`menutype`,`addonsurl`,`identify`,`tip`) values (1,'用户中心',0,2,'fa fa-user-md','Member/index','核心模块',1,0,NULL,NULL,''),(83,'图文回复',9,1,'fa fa-external-link','Reply/imgReply','基本功能',1,0,NULL,NULL,''),(3,'商城模块',0,3,'fa fa-shopping-cart','Shop/goods','核心模块',1,0,NULL,NULL,''),(4,'系统',0,1,'fa fa-globe','System/index','系统配置',1,0,NULL,NULL,''),(5,'菜单管理',4,0,'fa fa-navicon','System/index','系统设置',1,0,NULL,NULL,''),(6,'备份数据库',4,0,'fa fa-database','Database/index?type=export','数据库管理',1,0,NULL,NULL,''),(7,'还原数据库',4,0,'fa fa-database','Database/index?type=import','数据库管理',1,0,NULL,NULL,''),(8,'添加菜单',5,0,NULL,'System/addMenu','',1,0,NULL,NULL,''),(9,'微信',0,5,'fa fa-wechat','Reply/textReply','接口配置',1,0,NULL,NULL,''),(80,'修改自定义菜单',9,0,NULL,'Reply/upMenu','',1,0,NULL,NULL,''),(12,'欢迎语',9,0,'fa fa-external-link','Reply/welcome','高级功能',1,0,NULL,NULL,''),(14,'自定义菜单',9,0,'fa fa-external-link','Reply/menuList','高级功能',1,0,NULL,NULL,''),(67,'公众号设置',9,1,'fa fa-gear','Account/account','接口配置',1,0,NULL,NULL,''),(36,'用户列表',1,0,NULL,'Member/index','会员管理',1,0,NULL,NULL,''),(37,'用户组',1,0,NULL,'Member/group','会员管理',1,0,NULL,NULL,''),(38,'用户等级',1,0,NULL,'Member/level','会员管理',1,0,NULL,NULL,''),(39,'新增用户',36,0,NULL,'Member/add','',1,0,NULL,NULL,''),(40,'新增用户组',37,0,NULL,'Member/addGroup','',1,0,NULL,NULL,''),(41,'新增用户等级',38,0,NULL,'Member/addLevel','',1,0,NULL,NULL,''),(44,'社交管理',0,4,'fa fa-comment','Contact/activeList','核心模块',1,0,NULL,NULL,''),(120,'添加功能',119,0,'fa fa-external-link','Member/addMembPer','',1,0,NULL,NULL,''),(48,'活动配置',44,0,'fa fa-external-link','Contact/activeList','活动管理',1,0,NULL,NULL,''),(49,'基础模块',3,0,'fa fa-external-link','Shop/goods','主要业务',1,0,NULL,NULL,''),(50,'订单管理',3,0,'','Order/list','主要业务',1,0,NULL,NULL,''),(53,'商品分类管理',49,0,NULL,'Shop/category','',1,0,NULL,NULL,''),(51,'添加活动',48,0,'fa fa-external-link','Contact/addActive','',1,0,NULL,NULL,''),(54,'配送方式',49,0,NULL,'Shop/dispatch','',1,0,NULL,NULL,''),(55,'幻灯片管理',49,0,NULL,'Shop/adv','',1,0,NULL,NULL,''),(56,'公告管理',49,0,NULL,'Shop/notice','',1,0,NULL,NULL,''),(57,'评价管理',49,0,NULL,'Shop/comment','',1,0,NULL,NULL,''),(71,'报名管理',44,0,'fa fa-external-link','Contact/applyList','活动管理',1,0,NULL,NULL,''),(66,'文字回复',9,0,'fa fa-plus','Reply/textReply','基本功能',1,0,NULL,NULL,''),(69,'报名统计',48,0,'fa fa-external-link','Contact/statistics','',1,0,NULL,NULL,''),(68,'添加文字回复',9,0,NULL,'Reply/addTextReply','',1,0,NULL,NULL,''),(87,'配置管理',4,0,'fa fa-external-link','Config/index','系统设置',1,0,NULL,NULL,''),(70,'编辑活动',48,0,'fa fa-external-link','Contact/editActive','',1,0,NULL,NULL,''),(72,'积分配置',1,0,'fa fa-external-link','Member/action','积分管理',1,0,NULL,NULL,''),(73,'用户行为日志',1,0,'fa fa-external-link','Member/actionlog','积分管理',1,0,NULL,NULL,''),(74,'日志详细',73,0,'fa fa-external-link','Member/edit','积分管理',1,0,NULL,NULL,''),(75,'添加用户行为',72,0,'fa fa-external-link','Member/editAction','',1,0,NULL,NULL,''),(121,'支付配置',4,0,'fa fa-external-link','System/payment','系统设置',1,0,NULL,NULL,''),(77,'商城入口',3,0,'fa fa-external-link-square','Shop/index','微信入口',1,0,NULL,NULL,''),(78,'变更明细',1,0,'fa fa-external-link','Member/listDetail','积分管理',1,0,NULL,NULL,''),(79,'添加自定义菜单 ',9,0,NULL,'Reply/addMenu','',1,0,NULL,NULL,''),(86,'商城设置',3,0,NULL,'Sysset/index','主要业务',1,0,NULL,NULL,''),(81,'金额明细',78,0,'fa fa-external-link','Member/depositDetail','',1,0,NULL,NULL,''),(82,'积分明细',78,0,'fa fa-external-link','Member/scoreDetail','',1,0,NULL,NULL,''),(84,'添加图文回复',9,0,NULL,'Reply/addImgReply','',1,0,NULL,NULL,''),(88,'粉丝列表',9,3,'fa fa-external-link','Reply/fans','粉丝管理',1,0,NULL,NULL,''),(89,'发送客服消息',9,0,NULL,'Reply/sendMessage','',1,0,NULL,NULL,''),(90,'编辑配置项',87,0,'fa fa-external-link','Config/edit','',1,0,NULL,NULL,''),(91,'配置管理排序',87,0,'fa fa-external-link','Config/sort','',1,0,NULL,NULL,''),(92,'网站设置',4,0,'fa fa-external-link','Config/group','系统设置',1,0,NULL,NULL,''),(93,'添加配置项',87,0,'fa fa-external-link','Config/add','',1,0,NULL,NULL,''),(94,'文章管理',125,0,'fa fa-external-link','Article/index','文章管理',1,0,NULL,NULL,''),(95,'文章分类',125,0,'fa fa-external-link','Article/siteCate','文章管理',1,0,NULL,NULL,''),(96,'添加文章',94,0,'fa fa-external-link','Article/edit','',1,0,NULL,NULL,''),(97,'发送信息',9,0,NULL,'Reply/sendMesPage','',1,0,NULL,NULL,''),(98,'添加文章分类',95,0,'fa fa-external-link','Article/editCate','',1,0,NULL,NULL,''),(99,'引导及分享设置',86,0,NULL,'Sysset/follow','',1,0,NULL,NULL,''),(100,'提醒及模板消息设置',86,0,NULL,'Sysset/notice','',1,0,NULL,NULL,''),(101,'交易设置',86,0,NULL,'Sysset/trade','',1,0,NULL,NULL,''),(102,'支付方式设置',86,0,NULL,'Sysset/pay','',1,0,NULL,NULL,''),(103,'模板设置',86,0,NULL,'Sysset/template','',1,0,NULL,NULL,''),(104,'会员设置',86,0,NULL,'Sysset/member','',1,0,NULL,NULL,''),(105,'分类层级设置',86,0,NULL,'Sysset/category','',1,0,NULL,NULL,''),(106,'联系方式设置',86,0,NULL,'Sysset/contact','',1,0,NULL,NULL,''),(108,'有赏众帮详情',107,0,NULL,'Research/detailResearch','',1,0,NULL,NULL,''),(110,'评论详情',109,0,NULL,'Research/detailDiscuss','',1,0,NULL,NULL,''),(149,'添加管理员',148,0,'fa fa-external-link','Member/addUser','',1,0,NULL,NULL,''),(112,'报告详情',111,0,'fa fa-external-link','Research/analysisDetails','',1,0,NULL,NULL,''),(148,'管理员列表',1,0,'fa fa-external-link','Member/userList','管理员',1,0,NULL,NULL,''),(114,'首页',0,0,'fa fa-bank','IntroIndex/index','',1,0,NULL,NULL,''),(116,'编辑菜单',5,0,'fa fa-external-link','System/edit','',1,0,NULL,NULL,''),(117,'会员字段管理',1,0,'fa fa-external-link','Member/mfield','会员管理',1,0,NULL,NULL,''),(118,'修改会员字段',117,0,'fa fa-external-link','Member/savefield','',1,0,NULL,NULL,''),(119,'个人中心管理',1,0,'fa fa-external-link','Member/membPerson','会员管理',1,0,NULL,NULL,''),(122,'公众号添加',9,0,NULL,'Account/addAccount','',1,0,NULL,NULL,''),(133,'常用服务接入',9,0,'fa fa-external-link','Account/serviceIn','高级功能',1,0,NULL,NULL,''),(125,'其它功能',0,6,'fa fa-qrcode','Addons/addonlist','扩展功能',1,0,NULL,NULL,''),(126,'插件管理',125,1,'fa fa-external-link','Addons/index','扩展',1,0,NULL,NULL,''),(127,'插件配置',126,0,'fa fa-external-link','Addons/config','',1,0,NULL,NULL,''),(128,'钩子管理',125,2,'fa fa-external-link','Addons/hooks','扩展',1,0,NULL,NULL,''),(131,'插件列表',125,0,'fa fa-external-link','Addons/addonlist','扩展',1,1,NULL,NULL,''),(137,'添加插件',126,0,'fa fa-external-link','Addons/create','',1,0,NULL,NULL,''),(139,'添加钩子',128,0,'fa fa-external-link','Addons/edithook','',1,0,NULL,NULL,''),(153,'供应商管理',49,0,'fa fa-external-link','Shop/supplier','',1,0,NULL,NULL,''),(141,'粉丝统计',9,0,'fa fa-external-link','Member/statistics','粉丝管理',1,0,NULL,NULL,''),(142,'权限管理',1,0,'fa fa-external-link','AuthManager/index','管理员',1,0,NULL,NULL,''),(143,'访问授权',142,0,'fa fa-external-link','AuthManager/access','',1,0,NULL,NULL,''),(144,'成员授权',142,0,'fa fa-external-link','AuthManager/user','',1,0,NULL,NULL,''),(145,'编辑用户组',142,0,'fa fa-external-link','AuthManager/editgroup','',1,0,NULL,NULL,''),(146,'动态审核',44,0,'fa fa-external-link','PunchAdmin/punchClockList','动态',1,0,NULL,NULL,''),(147,'评论审核',44,0,'fa fa-external-link','PunchAdmin/punchComment','动态',1,0,NULL,NULL,''),(154,'活动分类',44,0,'fa fa-external-link','Contact/cateList','活动管理',1,0,NULL,NULL,''),(155,'添加分类',154,0,'fa fa-external-link','Contact/addCategory','',1,0,NULL,NULL,'');

UNLOCK TABLES;

/*Table structure for table `sx_message_log` */

DROP TABLE IF EXISTS `sx_message_log`;

CREATE TABLE `sx_message_log` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `openid` varchar(50) NOT NULL COMMENT 'openid',
  `createtime` int(10) NOT NULL COMMENT '发送时间',
  `content` varchar(1000) NOT NULL COMMENT '内容',
  `type` varchar(10) NOT NULL COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7760 DEFAULT CHARSET=utf8;

/*Data for the table `sx_message_log` */

LOCK TABLES `sx_message_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_model` */

DROP TABLE IF EXISTS `sx_model`;

CREATE TABLE `sx_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8 COMMENT='文档模型表';

/*Data for the table `sx_model` */

LOCK TABLES `sx_model` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_news_reply` */

DROP TABLE IF EXISTS `sx_news_reply`;

CREATE TABLE `sx_news_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rid` int(10) unsigned NOT NULL COMMENT '规则id',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `thumb` varchar(255) NOT NULL COMMENT '图片路径',
  `content` text NOT NULL COMMENT '回复内容',
  `url` varchar(255) NOT NULL COMMENT '点击链接',
  `displayorder` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COMMENT='图文回复表';

/*Data for the table `sx_news_reply` */

LOCK TABLES `sx_news_reply` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_picture` */

DROP TABLE IF EXISTS `sx_picture`;

CREATE TABLE `sx_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

/*Data for the table `sx_picture` */

LOCK TABLES `sx_picture` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_property` */

DROP TABLE IF EXISTS `sx_property`;

CREATE TABLE `sx_property` (
  `uuid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性表Id',
  `user_info` varchar(100) NOT NULL COMMENT '用户信息',
  `user_value` varchar(100) NOT NULL COMMENT '用户值',
  `signup_id` int(10) NOT NULL COMMENT '对应的活动报名Id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=406 DEFAULT CHARSET=utf8 COMMENT='活动报名属性表';

/*Data for the table `sx_property` */

LOCK TABLES `sx_property` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_punch_clock` */

DROP TABLE IF EXISTS `sx_punch_clock`;

CREATE TABLE `sx_punch_clock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 -1.已删除 , 0.被禁用 1 正常 2 未审核 （默认为正常）',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `imglist` varchar(5000) NOT NULL COMMENT '上传的图片信息 多张图片路径用“，”隔开',
  `membid` int(11) unsigned NOT NULL COMMENT '粉丝ID',
  `punch_date` int(13) NOT NULL COMMENT '打卡时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Data for the table `sx_punch_clock` */

LOCK TABLES `sx_punch_clock` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_punch_comment` */

DROP TABLE IF EXISTS `sx_punch_comment`;

CREATE TABLE `sx_punch_comment` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `membid` int(13) NOT NULL COMMENT '用户ID',
  `activeid` int(13) DEFAULT NULL COMMENT '运动ID',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 -1.已删除 , 0.被禁用 1 正常 2 未审核 （默认为正常）',
  `punchid` int(13) DEFAULT NULL COMMENT '打卡标识ID',
  `commenttype` tinyint(2) NOT NULL COMMENT '1、点赞 2、评论',
  `content` varchar(1000) NOT NULL COMMENT '评论类容',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Data for the table `sx_punch_comment` */

LOCK TABLES `sx_punch_comment` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_punch_message` */

DROP TABLE IF EXISTS `sx_punch_message`;

CREATE TABLE `sx_punch_message` (
  `uuid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) NOT NULL,
  `receive_userId` int(10) NOT NULL COMMENT '接收用户',
  `send_userId` int(10) NOT NULL COMMENT '发送用户',
  `message` varchar(500) DEFAULT NULL COMMENT '消息',
  `picUrl` varchar(200) DEFAULT NULL COMMENT '图片路径',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '发送时间',
  `hadread` tinyint(2) NOT NULL COMMENT '是否已读',
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

/*Data for the table `sx_punch_message` */

LOCK TABLES `sx_punch_message` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_registration` */

DROP TABLE IF EXISTS `sx_registration`;

CREATE TABLE `sx_registration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '报名Id',
  `user_id` int(10) NOT NULL COMMENT '报名用户',
  `active_id` int(10) NOT NULL COMMENT '报名活动',
  `add_time` int(11) NOT NULL COMMENT '报名时间',
  `status` tinyint(2) NOT NULL COMMENT '0、待审核 1、代付款 2、驳回 3、成功 4、已签到',
  `signup_fee` double NOT NULL COMMENT '报名费用',
  `signup_acount` int(10) NOT NULL COMMENT '报名个数',
  `children_fee` double DEFAULT NULL COMMENT '儿童费用',
  `children_acount` int(3) DEFAULT NULL COMMENT '儿童个数',
  `total_acount` int(3) NOT NULL COMMENT '报名总个数',
  `total_fee` double NOT NULL COMMENT '报名总费用',
  `signup_pics` varchar(500) NOT NULL COMMENT '报名活动图片',
  `location` varchar(20000) DEFAULT NULL COMMENT '签到地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COMMENT='活动报名';

/*Data for the table `sx_registration` */

LOCK TABLES `sx_registration` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_rule` */

DROP TABLE IF EXISTS `sx_rule`;

CREATE TABLE `sx_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `module` varchar(50) NOT NULL COMMENT '类别',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

/*Data for the table `sx_rule` */

LOCK TABLES `sx_rule` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_searchhot` */

DROP TABLE IF EXISTS `sx_searchhot`;

CREATE TABLE `sx_searchhot` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `hotname` varchar(100) NOT NULL COMMENT '关键词名称',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 -1 已删除 0 被禁用 1 正常 2 未审核',
  `type` varchar(10) NOT NULL COMMENT '关键词类型 C(''SEARCHHOTTYPE'')中的类型',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  `rank` int(13) NOT NULL DEFAULT '0' COMMENT '强制排序',
  `scorehit` int(13) NOT NULL DEFAULT '0' COMMENT '命中次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='热搜词列表';

/*Data for the table `sx_searchhot` */

LOCK TABLES `sx_searchhot` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_service` */

DROP TABLE IF EXISTS `sx_service`;

CREATE TABLE `sx_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) NOT NULL COMMENT '服务名称',
  `description` varchar(50) NOT NULL COMMENT '服务描述',
  `status` int(2) NOT NULL COMMENT '状态 0启用 1禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `sx_service` */

LOCK TABLES `sx_service` WRITE;

insert  into `sx_service`(`id`,`name`,`description`,`status`) values (1,'城市天气','\"城市+天气\"  如: \"杭州天气\"',0),(2,'百度百科','\"百科+查询内容\"  如: \"百科姚明\"',0),(3,'即时翻译','\"@查询内容(中文或英文)\"',0),(4,'今日老黄历','\"日历\", \"万年历\", \"黄历\"或\"几号\"',0);

UNLOCK TABLES;

/*Table structure for table `sx_site_article` */

DROP TABLE IF EXISTS `sx_site_article`;

CREATE TABLE `sx_site_article` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '对应规则',
  `iscommend` tinyint(1) NOT NULL COMMENT '推荐[c]',
  `ishot` tinyint(1) unsigned NOT NULL COMMENT '头条[h]',
  `pcate` int(10) unsigned NOT NULL COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL COMMENT '2级分类',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `description` varchar(100) NOT NULL COMMENT '简介',
  `content` mediumtext NOT NULL COMMENT '内容详情',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `incontent` tinyint(1) NOT NULL COMMENT '封面图片显示在正文中（1表示显示 0表示不显示）',
  `source` varchar(255) NOT NULL COMMENT '文章来源',
  `author` varchar(50) NOT NULL COMMENT '文章作者',
  `displayorder` int(10) unsigned NOT NULL COMMENT '强制排序',
  `linkurl` varchar(500) NOT NULL COMMENT '对外访问链接（当设置改值以后点击该文章就进入所设置的访问链接）',
  `createtime` int(13) unsigned NOT NULL COMMENT '创建时间',
  `credit` varchar(255) NOT NULL COMMENT '对积分的相关设置用iserializer（）处理以后存储',
  `click` int(10) NOT NULL COMMENT '阅读次数',
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

/*Data for the table `sx_site_article` */

LOCK TABLES `sx_site_article` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_site_cate` */

DROP TABLE IF EXISTS `sx_site_cate`;

CREATE TABLE `sx_site_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) unsigned NOT NULL COMMENT '上级父类id',
  `displayorder` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `struts` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是激活状态 0.否 1.是',
  `icon` varchar(500) NOT NULL COMMENT '类型图标',
  `description` varchar(100) NOT NULL COMMENT '内容描述',
  `linkurl` varchar(500) NOT NULL COMMENT '直接链接',
  `ishomepage` tinyint(1) NOT NULL COMMENT '是否作为首页使用',
  `icontype` tinyint(1) unsigned NOT NULL COMMENT '图标类型1.系统内置 2.自定义上传',
  `iconfile` varchar(255) NOT NULL COMMENT '上传的图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Data for the table `sx_site_cate` */

LOCK TABLES `sx_site_cate` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_user` */

DROP TABLE IF EXISTS `sx_user`;

CREATE TABLE `sx_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理员状态 账号状态 0:禁用,1:正常',
  `email` varchar(50) NOT NULL COMMENT '电子邮箱',
  `mobile` varchar(50) NOT NULL COMMENT '联系电话',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别 0保密 1.男 2.女',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

/*Data for the table `sx_user` */

LOCK TABLES `sx_user` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_adv` */

DROP TABLE IF EXISTS `sx_zxin_shop_adv`;

CREATE TABLE `sx_zxin_shop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_adv` */

LOCK TABLES `sx_zxin_shop_adv` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_category` */

DROP TABLE IF EXISTS `sx_zxin_shop_category`;

CREATE TABLE `sx_zxin_shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `ishome` tinyint(3) DEFAULT '0',
  `advimg` varchar(255) DEFAULT '' COMMENT '分类广告图片',
  `advurl` varchar(500) DEFAULT '' COMMENT '分类广告链接',
  `level` tinyint(3) DEFAULT '0' COMMENT '分类等级',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_isrecommand` (`isrecommand`),
  KEY `idx_ishome` (`ishome`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_category` */

LOCK TABLES `sx_zxin_shop_category` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_coupon` */

DROP TABLE IF EXISTS `sx_zxin_shop_coupon`;

CREATE TABLE `sx_zxin_shop_coupon` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `couponsn` varchar(50) NOT NULL DEFAULT '' COMMENT '优惠券编号',
  `cprice` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '可抵金额',
  `description` varchar(20) NOT NULL DEFAULT '' COMMENT '描述',
  `type` int(5) NOT NULL DEFAULT '1' COMMENT '1线上 2线下',
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '0未用 1已用',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `ucateid` varchar(50) NOT NULL DEFAULT '' COMMENT '可用商品类型',
  `starttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '开始时间',
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `condimoney` int(10) DEFAULT NULL COMMENT '限额',
  `deleted` int(2) NOT NULL DEFAULT '0' COMMENT '0存在 1已删除',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_coupon` */

LOCK TABLES `sx_zxin_shop_coupon` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_dispatch` */

DROP TABLE IF EXISTS `sx_zxin_shop_dispatch`;

CREATE TABLE `sx_zxin_shop_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `dispatchname` varchar(50) DEFAULT '' COMMENT '配送名称',
  `dispatchtype` int(11) DEFAULT '0' COMMENT '配送类型',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `firstprice` decimal(10,2) DEFAULT '0.00' COMMENT '首重价格',
  `secondprice` decimal(10,2) DEFAULT '0.00' COMMENT '续重价格',
  `firstweight` int(11) DEFAULT '0' COMMENT '首重',
  `secondweight` int(11) DEFAULT '0' COMMENT '续重',
  `express` varchar(250) DEFAULT '' COMMENT '物流公司',
  `areas` text COMMENT '配送方式',
  `carriers` text COMMENT '自提',
  `enabled` int(11) DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_dispatch` */

LOCK TABLES `sx_zxin_shop_dispatch` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods`;

CREATE TABLE `sx_zxin_shop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `pcate` int(11) DEFAULT '0' COMMENT '父类',
  `ccate` int(11) DEFAULT '0' COMMENT '子类',
  `type` tinyint(1) DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) DEFAULT '1' COMMENT '1 上架 0下架',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  `title` varchar(100) DEFAULT '' COMMENT '商品名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '图片',
  `thumb_small` varchar(50) DEFAULT NULL COMMENT '缩略图',
  `unit` varchar(5) DEFAULT '' COMMENT '单位',
  `description` varchar(1000) DEFAULT '' COMMENT '简介',
  `content` text COMMENT '描述',
  `goodssn` varchar(50) DEFAULT '' COMMENT '商品编号',
  `productsn` varchar(50) DEFAULT '' COMMENT '商品条码',
  `productprice` decimal(10,2) DEFAULT '0.00' COMMENT '市场价',
  `marketprice` decimal(10,2) DEFAULT '0.00' COMMENT '销售价',
  `costprice` decimal(10,2) DEFAULT '0.00' COMMENT '成本价',
  `originalprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `total` int(10) DEFAULT '0' COMMENT '库存',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(11) DEFAULT '0' COMMENT '销售数量',
  `salesreal` int(11) DEFAULT '0' COMMENT '实际销量',
  `spec` varchar(5000) DEFAULT '' COMMENT '规格',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `weight` decimal(10,2) DEFAULT '0.00' COMMENT '首重',
  `credit` int(11) DEFAULT '0' COMMENT '获取积分',
  `maxbuy` int(11) DEFAULT '0' COMMENT '最大购买量',
  `usermaxbuy` int(11) DEFAULT '0' COMMENT '用户最多购买数量',
  `hasoption` int(11) DEFAULT '0' COMMENT '是否启用商品规格',
  `dispatch` int(11) DEFAULT '0' COMMENT '配送方式',
  `thumb_url` text COMMENT '其他图片',
  `isnew` tinyint(1) DEFAULT '0' COMMENT '是否新品',
  `ishot` tinyint(1) DEFAULT '0' COMMENT '是否热卖',
  `isdiscount` tinyint(1) DEFAULT '0' COMMENT '是否折扣',
  `isrecommand` tinyint(1) DEFAULT '0' COMMENT '是否精品（推荐）',
  `issendfree` tinyint(1) DEFAULT '0' COMMENT '是否包邮 1包邮',
  `istime` tinyint(1) DEFAULT '0' COMMENT '是否限时',
  `iscomment` tinyint(1) DEFAULT '0' COMMENT '评价0 追加1',
  `timestart` int(11) DEFAULT '0' COMMENT '开始时间',
  `timeend` int(11) DEFAULT '0' COMMENT '结束时间',
  `viewcount` int(11) DEFAULT '0' COMMENT '预览量',
  `deleted` tinyint(3) DEFAULT '0' COMMENT '是否删除 0否，1是',
  `hascommission` tinyint(3) DEFAULT '0' COMMENT '分销信息提醒',
  `commission1_rate` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销',
  `commission1_pay` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销价格',
  `commission2_rate` decimal(10,2) DEFAULT '0.00' COMMENT '同上',
  `commission2_pay` decimal(10,2) DEFAULT '0.00' COMMENT '同上',
  `commission3_rate` decimal(10,2) DEFAULT '0.00' COMMENT '同上',
  `commission3_pay` decimal(10,2) DEFAULT '0.00' COMMENT '同上',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '评价从高到低',
  `taobaoid` varchar(255) DEFAULT '',
  `taobaourl` varchar(255) DEFAULT '',
  `updatetime` int(11) DEFAULT '0',
  `share_title` varchar(255) DEFAULT '' COMMENT '分享标题',
  `share_icon` varchar(255) DEFAULT '' COMMENT '分享图标',
  `cash` tinyint(3) DEFAULT '0' COMMENT '货到付款',
  `commission_thumb` varchar(255) DEFAULT '' COMMENT '海报图片',
  `isnodiscount` tinyint(3) DEFAULT '0' COMMENT '1不参与会员折扣',
  `showlevels` text COMMENT '会员等级浏览权限',
  `buylevels` text COMMENT '会员等级购买权限',
  `showgroups` text COMMENT '会员组浏览权限',
  `buygroups` text COMMENT '会员组购买权限',
  `isverify` tinyint(3) DEFAULT '0' COMMENT '线下核销{else}自提',
  `storeids` text COMMENT '门店',
  `noticeopenid` text COMMENT '商家通知',
  `tcate` int(11) DEFAULT '0' COMMENT '第三级分类',
  `noticetype` varchar(20) DEFAULT '0' COMMENT '通知方式  0下单通知  1付款通知  2买家收货通知',
  `needfollow` tinyint(3) DEFAULT '0' COMMENT '购买强制关注',
  `followtip` varchar(255) DEFAULT '' COMMENT '未关注提示',
  `followurl` varchar(255) DEFAULT '' COMMENT '引导关注页面',
  `deduct` decimal(10,2) DEFAULT '0.00' COMMENT '抵扣设置',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_pcate` (`pcate`),
  KEY `idx_ccate` (`ccate`),
  KEY `idx_isnew` (`isnew`),
  KEY `idx_ishot` (`ishot`),
  KEY `idx_isdiscount` (`isdiscount`),
  KEY `idx_isrecommand` (`isrecommand`),
  KEY `idx_iscomment` (`iscomment`),
  KEY `idx_issendfree` (`issendfree`),
  KEY `idx_istime` (`istime`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_tcate` (`tcate`),
  FULLTEXT KEY `idx_buylevels` (`buylevels`),
  FULLTEXT KEY `idx_showgroups` (`showgroups`),
  FULLTEXT KEY `idx_buygroups` (`buygroups`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods` */

LOCK TABLES `sx_zxin_shop_goods` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods_comment` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods_comment`;

CREATE TABLE `sx_zxin_shop_goods_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `content` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods_comment` */

LOCK TABLES `sx_zxin_shop_goods_comment` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods_option` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods_option`;

CREATE TABLE `sx_zxin_shop_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  `skuId` varchar(255) DEFAULT '',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods_option` */

LOCK TABLES `sx_zxin_shop_goods_option` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods_param` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods_param`;

CREATE TABLE `sx_zxin_shop_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods_param` */

LOCK TABLES `sx_zxin_shop_goods_param` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods_spec` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods_spec`;

CREATE TABLE `sx_zxin_shop_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(1000) DEFAULT '',
  `displaytype` tinyint(3) DEFAULT '0',
  `content` text,
  `displayorder` int(11) DEFAULT '0',
  `propId` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods_spec` */

LOCK TABLES `sx_zxin_shop_goods_spec` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_goods_spec_item` */

DROP TABLE IF EXISTS `sx_zxin_shop_goods_spec_item`;

CREATE TABLE `sx_zxin_shop_goods_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `valueId` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_specid` (`specid`),
  KEY `idx_show` (`show`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_goods_spec_item` */

LOCK TABLES `sx_zxin_shop_goods_spec_item` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_member_address` */

DROP TABLE IF EXISTS `sx_zxin_shop_member_address`;

CREATE TABLE `sx_zxin_shop_member_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `province` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `area` varchar(30) DEFAULT '',
  `address` varchar(300) DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `zipcode` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_isdefault` (`isdefault`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=232 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_member_address` */

LOCK TABLES `sx_zxin_shop_member_address` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_member_cart` */

DROP TABLE IF EXISTS `sx_zxin_shop_member_cart`;

CREATE TABLE `sx_zxin_shop_member_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '',
  `goodsid` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `deleted` tinyint(1) DEFAULT '0',
  `optionid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_member_cart` */

LOCK TABLES `sx_zxin_shop_member_cart` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_member_favorite` */

DROP TABLE IF EXISTS `sx_zxin_shop_member_favorite`;

CREATE TABLE `sx_zxin_shop_member_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_member_favorite` */

LOCK TABLES `sx_zxin_shop_member_favorite` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_member_history` */

DROP TABLE IF EXISTS `sx_zxin_shop_member_history`;

CREATE TABLE `sx_zxin_shop_member_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_member_history` */

LOCK TABLES `sx_zxin_shop_member_history` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_member_log` */

DROP TABLE IF EXISTS `sx_zxin_shop_member_log`;

CREATE TABLE `sx_zxin_shop_member_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `type` tinyint(3) DEFAULT NULL COMMENT '0 充值 1 提现',
  `logno` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '0 生成 1 成功 2 失败',
  `money` decimal(10,2) DEFAULT '0.00',
  `rechargetype` varchar(255) DEFAULT '' COMMENT '充值类型',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_type` (`type`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_member_log` */

LOCK TABLES `sx_zxin_shop_member_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_notice` */

DROP TABLE IF EXISTS `sx_zxin_shop_notice`;

CREATE TABLE `sx_zxin_shop_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `detail` text,
  `status` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_notice` */

LOCK TABLES `sx_zxin_shop_notice` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_order` */

DROP TABLE IF EXISTS `sx_zxin_shop_order`;

CREATE TABLE `sx_zxin_shop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区分公众号',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `ordersn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总价',
  `goodsprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品价格',
  `discountprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员折扣价格',
  `sendtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品类型',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '留言',
  `addressid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '地址id',
  `expresscom` varchar(30) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `expresssn` varchar(50) NOT NULL DEFAULT '' COMMENT '物流公司订单号',
  `express` varchar(200) NOT NULL DEFAULT '' COMMENT '物流公司(pinyin)名称',
  `dispatchid` int(10) NOT NULL DEFAULT '0' COMMENT '运费模版id',
  `dispatchprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `paydetail` varchar(255) NOT NULL DEFAULT '' COMMENT '支付详情',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sendtime` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `refundid` int(11) NOT NULL DEFAULT '0' COMMENT '退款申请',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `isverify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否线下核销1是',
  `carrier` text,
  `dispatchtype` tinyint(3) DEFAULT NULL COMMENT '配送方式',
  `canceltime` tinyint(11) DEFAULT '0' COMMENT '取消时间',
  `deductprice` decimal(10,2) DEFAULT '0.00',
  `deductcredit` decimal(10,2) DEFAULT '0.00',
  `deductcredit2` decimal(10,2) DEFAULT '0.00',
  `deductenough` decimal(10,2) DEFAULT '0.00',
  `finishtime` tinyint(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_order` */

LOCK TABLES `sx_zxin_shop_order` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_order_comment` */

DROP TABLE IF EXISTS `sx_zxin_shop_order_comment`;

CREATE TABLE `sx_zxin_shop_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `level` tinyint(3) DEFAULT '0',
  `content` varchar(255) DEFAULT '',
  `images` text,
  `createtime` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `append_content` varchar(255) DEFAULT '',
  `append_images` text,
  `reply_content` varchar(255) DEFAULT '',
  `reply_images` text,
  `append_reply_content` varchar(255) DEFAULT '',
  `append_reply_images` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_orderid` (`orderid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_order_comment` */

LOCK TABLES `sx_zxin_shop_order_comment` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_order_goods` */

DROP TABLE IF EXISTS `sx_zxin_shop_order_goods`;

CREATE TABLE `sx_zxin_shop_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `orderid` int(11) unsigned NOT NULL DEFAULT '0',
  `goodsid` int(11) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(11) unsigned NOT NULL DEFAULT '1',
  `optionid` int(11) DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `optionname` text,
  `commission1` text,
  `applytime1` int(11) DEFAULT '0',
  `checktime1` int(11) DEFAULT '0',
  `paytime1` int(11) DEFAULT '0',
  `invalidtime1` int(11) DEFAULT '0',
  `deletetime1` int(11) DEFAULT '0',
  `status1` tinyint(3) DEFAULT '0',
  `content1` text,
  `commission2` text,
  `applytime2` int(11) DEFAULT '0',
  `checktime2` int(11) DEFAULT '0',
  `paytime2` int(11) DEFAULT '0',
  `invalidtime2` int(11) DEFAULT '0',
  `deletetime2` int(11) DEFAULT '0',
  `status2` tinyint(3) DEFAULT '0',
  `content2` text,
  `commission3` text,
  `applytime3` int(11) DEFAULT '0',
  `checktime3` int(11) DEFAULT '0',
  `paytime3` int(11) DEFAULT '0',
  `invalidtime3` int(11) DEFAULT '0',
  `deletetime3` int(11) DEFAULT '0',
  `status3` tinyint(3) DEFAULT '0',
  `content3` text,
  `realprice` decimal(10,2) DEFAULT '0.00',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_order_goods` */

LOCK TABLES `sx_zxin_shop_order_goods` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_order_refund` */

DROP TABLE IF EXISTS `sx_zxin_shop_order_refund`;

CREATE TABLE `sx_zxin_shop_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `refundno` varchar(255) DEFAULT '',
  `price` varchar(255) DEFAULT '',
  `reason` varchar(255) DEFAULT '',
  `images` text,
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0' COMMENT '0申请 1 通过 2 驳回',
  `reply` text,
  `refundtype` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='退款申请表';

/*Data for the table `sx_zxin_shop_order_refund` */

LOCK TABLES `sx_zxin_shop_order_refund` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_store` */

DROP TABLE IF EXISTS `sx_zxin_shop_store`;

CREATE TABLE `sx_zxin_shop_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '' COMMENT '门店名称',
  `address` varchar(255) DEFAULT '' COMMENT '门店地址',
  `tel` varchar(255) DEFAULT '' COMMENT '电话',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='先下门店 暂时无用';

/*Data for the table `sx_zxin_shop_store` */

LOCK TABLES `sx_zxin_shop_store` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxin_shop_sysset` */

DROP TABLE IF EXISTS `sx_zxin_shop_sysset`;

CREATE TABLE `sx_zxin_shop_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` text,
  `plugins` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxin_shop_sysset` */

LOCK TABLES `sx_zxin_shop_sysset` WRITE;

insert  into `sx_zxin_shop_sysset`(`id`,`uniacid`,`sets`,`plugins`) values (1,0,'a:6:{s:4:\"shop\";a:15:{s:4:\"name\";s:12:\"商城名称\";s:3:\"img\";s:66:\"/Uploads/Picture/images/2016/02/UtjZiq9sGIT1oR8Zs6OTOUtOJ6too7.png\";s:4:\"logo\";s:66:\"/Uploads/Picture/images/2016/02/s5As8jFLFaFFmXjX2zJB5QXYHSxLL5.jpg\";s:7:\"signimg\";s:66:\"/Uploads/Picture/images/2016/02/dTTzGgTQK6ttdd6q6JsGRDD3kGdGGV.jpg\";s:5:\"style\";s:7:\"default\";s:9:\"levelname\";s:12:\"普通会员\";s:8:\"levelurl\";s:21:\"http://www.member.com\";s:8:\"catlevel\";s:1:\"2\";s:7:\"catshow\";i:0;s:9:\"catadvimg\";s:66:\"/Uploads/Picture/images/2016/02/zcScK53CK923QRHqxZ3xkcq9rL3lQ2.jpg\";s:9:\"catadvurl\";s:0:\"\";s:2:\"qq\";s:0:\"\";s:7:\"address\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:5:\"share\";a:5:{s:9:\"followurl\";s:22:\"http://www.guanzhu.com\";s:5:\"title\";s:6:\"分享\";s:4:\"icon\";s:45:\"/Uploads/Picture/2015-11-25/56558f0e1dadc.jpg\";s:4:\"desc\";s:12:\"分享描述\";s:3:\"url\";s:30:\"http://www.fenxianglianjie.com\";}s:6:\"notice\";a:17:{s:3:\"new\";s:1:\"0\";s:7:\"newtype\";s:5:\"0,1,2\";s:6:\"submit\";s:1:\"1\";s:7:\"carrier\";s:1:\"2\";s:6:\"cancel\";s:1:\"3\";s:3:\"pay\";s:1:\"4\";s:4:\"send\";s:1:\"5\";s:6:\"finish\";s:1:\"6\";s:6:\"refund\";s:1:\"7\";s:7:\"refund1\";s:1:\"8\";s:7:\"refund2\";s:1:\"9\";s:7:\"upgrade\";s:2:\"10\";s:11:\"recharge_ok\";s:2:\"11\";s:15:\"recharge_refund\";s:2:\"12\";s:8:\"withdraw\";s:2:\"13\";s:11:\"withdraw_ok\";s:2:\"14\";s:13:\"withdraw_fail\";s:2:\"15\";}s:5:\"tarde\";a:9:{s:7:\"receive\";s:1:\"7\";s:10:\"refunddays\";s:1:\"7\";s:13:\"refundcontent\";s:12:\"退款说明\";s:11:\"receivetime\";s:2:\"60\";s:8:\"withdraw\";s:1:\"1\";s:13:\"withdrawmoney\";s:1:\"0\";s:5:\"money\";s:3:\"100\";s:6:\"credit\";s:2:\"10\";s:12:\"shareaddress\";s:1:\"1\";}s:5:\"trade\";a:9:{s:7:\"receive\";s:1:\"7\";s:10:\"refunddays\";s:1:\"7\";s:13:\"refundcontent\";s:12:\"退款说明\";s:11:\"receivetime\";s:2:\"60\";s:8:\"withdraw\";s:1:\"1\";s:13:\"withdrawmoney\";s:1:\"0\";s:5:\"money\";s:3:\"100\";s:6:\"credit\";s:2:\"10\";s:12:\"shareaddress\";s:1:\"1\";}s:3:\"pay\";a:6:{s:6:\"weixin\";s:1:\"1\";s:11:\"weixin_cert\";s:43:\"../Uploads/data/cert/weixin_cert_file_0.pem\";s:10:\"weixin_key\";s:42:\"../Uploads/data/cert/weixin_key_file_0.pem\";s:11:\"weixin_root\";s:43:\"../Uploads/data/cert/weixin_root_file_0.pem\";s:6:\"alipay\";s:1:\"1\";s:6:\"credit\";s:1:\"0\";}}',NULL);

UNLOCK TABLES;

/*Table structure for table `sx_zxu_cheer` */

DROP TABLE IF EXISTS `sx_zxu_cheer`;

CREATE TABLE `sx_zxu_cheer` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `cheerid` int(13) unsigned NOT NULL COMMENT '加油人id',
  `refuelid` int(13) unsigned NOT NULL COMMENT '被加油人id',
  `createtime` int(13) unsigned NOT NULL COMMENT '创建时间',
  `updatetime` int(13) NOT NULL COMMENT '更新时间',
  `degree` int(13) unsigned NOT NULL COMMENT '我为他的加油次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=711 DEFAULT CHARSET=utf8 COMMENT='相互+U的用户表';

/*Data for the table `sx_zxu_cheer` */

LOCK TABLES `sx_zxu_cheer` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_cheer_log` */

DROP TABLE IF EXISTS `sx_zxu_cheer_log`;

CREATE TABLE `sx_zxu_cheer_log` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `membid` int(13) NOT NULL COMMENT '主动加油人id',
  `refuelid` int(13) NOT NULL COMMENT '被动加油人id',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxu_cheer_log` */

LOCK TABLES `sx_zxu_cheer_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_give_assist` */

DROP TABLE IF EXISTS `sx_zxu_give_assist`;

CREATE TABLE `sx_zxu_give_assist` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账号状态 0:启用,1:禁用',
  `display` int(13) NOT NULL DEFAULT '0' COMMENT '强制排序',
  `description` varchar(500) NOT NULL COMMENT '内容简介',
  `content` text NOT NULL COMMENT '内容详情',
  `thumb` varchar(255) NOT NULL COMMENT '标题图片rul（用于列表页吗的图片显示）',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  `updatetime` int(13) NOT NULL COMMENT '更新时间(即开始时间)',
  `validTime` int(13) NOT NULL DEFAULT '0' COMMENT '结束时间（0表示永不结束）',
  `uid` int(13) NOT NULL COMMENT '创建的管理员id',
  `likecount` int(13) NOT NULL DEFAULT '0' COMMENT '被点赞的次数',
  `likescore` decimal(13,2) NOT NULL COMMENT '点赞赠送的积分数（悬赏金额）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='有赏众帮项目列表';

/*Data for the table `sx_zxu_give_assist` */

LOCK TABLES `sx_zxu_give_assist` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_give_discuss` */

DROP TABLE IF EXISTS `sx_zxu_give_discuss`;

CREATE TABLE `sx_zxu_give_discuss` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `assid` int(13) NOT NULL COMMENT '有赏众帮id',
  `content` varchar(500) NOT NULL COMMENT '评论',
  `memberid` int(13) NOT NULL COMMENT '评论的用户',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='有赏众帮评论表';

/*Data for the table `sx_zxu_give_discuss` */

LOCK TABLES `sx_zxu_give_discuss` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_give_option` */

DROP TABLE IF EXISTS `sx_zxu_give_option`;

CREATE TABLE `sx_zxu_give_option` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL COMMENT '题目',
  `assid` int(13) NOT NULL COMMENT '有赏众帮',
  `key` varchar(50) NOT NULL COMMENT '同组标识',
  `answer` varchar(100) NOT NULL COMMENT '答案',
  `display` int(13) NOT NULL COMMENT '排序',
  `voteCount` int(13) NOT NULL COMMENT '被投票的次数',
  `creattime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='选择题列表';

/*Data for the table `sx_zxu_give_option` */

LOCK TABLES `sx_zxu_give_option` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_give_option_log` */

DROP TABLE IF EXISTS `sx_zxu_give_option_log`;

CREATE TABLE `sx_zxu_give_option_log` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `assistid` int(13) NOT NULL COMMENT '+U项id',
  `option` varchar(500) NOT NULL COMMENT '选项列表中选项的id',
  `membid` int(13) NOT NULL COMMENT '评论者id（即做这个选择题的用户的id）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxu_give_option_log` */

LOCK TABLES `sx_zxu_give_option_log` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_imggif` */

DROP TABLE IF EXISTS `sx_zxu_imggif`;

CREATE TABLE `sx_zxu_imggif` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `imgurl` varchar(255) NOT NULL COMMENT 'gif图片url',
  `membid` int(13) NOT NULL COMMENT '用户id',
  `desc` varchar(200) NOT NULL COMMENT '图片描述',
  `cratetime` int(13) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;

/*Data for the table `sx_zxu_imggif` */

LOCK TABLES `sx_zxu_imggif` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_oneself_refuel` */

DROP TABLE IF EXISTS `sx_zxu_oneself_refuel`;

CREATE TABLE `sx_zxu_oneself_refuel` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `membid` int(13) unsigned NOT NULL COMMENT '用户id',
  `imgurl` varchar(500) NOT NULL COMMENT '拍摄的图片',
  `thumb_url` varchar(500) NOT NULL COMMENT '缩略图路径',
  `details` varchar(5000) NOT NULL COMMENT '内容描述',
  `score` decimal(13,2) NOT NULL COMMENT '投稿发布的积分',
  `createtime` int(13) NOT NULL COMMENT '创建时间',
  `type` tinyint(2) NOT NULL COMMENT '类型 1.自己每日加油 2.自己每天的云笔记 3给宝贝加油',
  `contribute` tinyint(2) NOT NULL DEFAULT '0' COMMENT '投稿状态 0未进行投稿 1.进行投稿',
  `contrtime` int(13) NOT NULL COMMENT '进行投稿的时间',
  `contrstatus` tinyint(2) NOT NULL COMMENT '进行投稿的状态 0.未中标  1待审核 2.已中标',
  `payissue` tinyint(2) NOT NULL COMMENT '是否土豪发布  0未进行土豪发布 1.进行土豪发布',
  `paytime` int(13) DEFAULT NULL COMMENT '进行土豪发布的时间',
  `payscore` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '进行土豪发布的积分',
  `paystatus` tinyint(2) NOT NULL DEFAULT '0' COMMENT '进行土豪发布的状态 0.默认状态 1待审核 2.已中标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=350 DEFAULT CHARSET=utf8 COMMENT='用户每天拍摄的照片';

/*Data for the table `sx_zxu_oneself_refuel` */

LOCK TABLES `sx_zxu_oneself_refuel` WRITE;

UNLOCK TABLES;

/*Table structure for table `sx_zxu_replenish` */

DROP TABLE IF EXISTS `sx_zxu_replenish`;

CREATE TABLE `sx_zxu_replenish` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT,
  `cheerid` int(13) unsigned NOT NULL COMMENT '主表zxu_cheer的id',
  `score` decimal(13,2) NOT NULL COMMENT '所加的积分数',
  `createtime` int(13) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户进行+u的扩充表';

/*Data for the table `sx_zxu_replenish` */

LOCK TABLES `sx_zxu_replenish` WRITE;

UNLOCK TABLES;
