# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21)
# Database: corethink
# Generation Time: 2015-08-13 23:59:04 +0000
# ************************************************************



# Dump of table ct_addon
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_addon`;

CREATE TABLE `ct_addon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text NOT NULL COMMENT '插件描述',
  `config` text COMMENT '配置',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本号',
  `adminlist` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '插件类型',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

LOCK TABLES `ct_addon` WRITE;
/*!40000 ALTER TABLE `ct_addon` DISABLE KEYS */;

INSERT INTO `ct_addon` (`id`, `name`, `title`, `description`, `config`, `author`, `version`, `adminlist`, `type`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,'ReturnTop','返回顶部','返回顶部','{\"status\":\"1\",\"theme\":\"rocket\",\"customer\":\"\",\"case\":\"\",\"qq\":\"\",\"weibo\":\"\"}','CoreThink','1.0',0,0,1407681961,1408602081,0,1),
	(2,'Email','邮件插件','实现系统发邮件功能','{\"status\":\"1\",\"MAIL_SMTP_TYPE\":\"2\",\"MAIL_SMTP_SECURE\":\"0\",\"MAIL_SMTP_PORT\":\"25\",\"MAIL_SMTP_HOST\":\"smtp.qq.com\",\"MAIL_SMTP_USER\":\"\",\"MAIL_SMTP_PASS\":\"\",\"default\":\"[MAILBODY]\"}','CoreThink','1.0',0,0,1428732454,1428732454,0,1),
	(3,'SyncLogin','第三方账号登陆','第三方账号登陆','{\"type\":[\"Weixin\",\"Qq\",\"Sina\"],\"meta\":\"\",\"WeixinKEY\":\"\",\"WeixinSecret\":\"\",\"QqKEY\":\"\",\"QqSecret\":\"\",\"SinaKEY\":\"\",\"SinaSecret\":\"\",\"RenrenKEY\":\"\",\"RenrenSecret\":\"\"}','CoreThink','1.0',1,0,1428250248,1428250248,0,1),
	(4,'AdFloat','图片漂浮广告','图片漂浮广告','{\"status\":\"0\",\"url\":\"http:\\/\\/www.corethink.cn\",\"image\":\"\",\"width\":\"100\",\"height\":\"100\",\"speed\":\"10\",\"target\":\"1\"}','CoreThink','1.0',0,0,1408602081,1408602081,0,1);

/*!40000 ALTER TABLE `ct_addon` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_addon_hook
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_addon_hook`;

CREATE TABLE `ct_addon_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '钩子ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `addons` varchar(255) NOT NULL COMMENT '钩子挂载的插件 ''，''分割',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子表';

LOCK TABLES `ct_addon_hook` WRITE;
/*!40000 ALTER TABLE `ct_addon_hook` DISABLE KEYS */;

INSERT INTO `ct_addon_hook` (`id`, `name`, `description`, `addons`, `type`, `ctime`, `utime`, `status`)
VALUES
	(1,'PageHeader','页面header钩子，一般用于加载插件CSS文件和代码','SyncLogin',1,1407681961,1407681961,1),
	(2,'PageFooter','页面footer钩子，一般用于加载插件CSS文件和代码','ReturnTop,AdFloat',1,1407681961,1407681961,1),
	(3,'PageSide','页面侧边栏钩子','',1,1407681961,1407681961,1),
	(4,'DocumentListBefore','文档列表页面顶部钩子','',1,1407681961,1407681961,1),
	(5,'DocumentListAfter','文档列表页面底部钩子','',1,1407681961,1407681961,1),
	(6,'DocumentDetailBefore','文档详情页面顶部钩子','',1,1407681961,1407681961,1),
	(7,'DocumentDetailAfter','文档详情页面底部钩子','',1,1407681961,1407681961,1),
	(8,'UploadFile','上传文件钩子','',1,1407681961,1407681961,1),
	(9,'SendMessage','发送消息钩子','',1,1407681961,1407681961,1),
	(10,'SyncLogin','第三方登陆','SyncLogin',1,1407681961,1407681961,1);

/*!40000 ALTER TABLE `ct_addon_hook` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_addon_sync_login
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_addon_sync_login`;

CREATE TABLE `ct_addon_sync_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` varchar(15) NOT NULL DEFAULT '' COMMENT '类别',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT 'OpenID',
  `access_token` varchar(64) NOT NULL DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(64) NOT NULL DEFAULT '' COMMENT 'RefreshToken',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方登陆插件表';



# Dump of table ct_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_category`;

CREATE TABLE `ct_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父分类ID',
  `group` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分组',
  `doc_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分类模型',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `url` varchar(127) NOT NULL COMMENT '链接地址',
  `content` text NOT NULL COMMENT '内容',
  `index_template` varchar(32) NOT NULL DEFAULT '' COMMENT '列表封面模版',
  `detail_template` varchar(32) NOT NULL DEFAULT '' COMMENT '详情页模版',
  `post_auth` tinyint(4) NOT NULL DEFAULT '0' COMMENT '投稿权限',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '缩略图',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目分类表';

LOCK TABLES `ct_category` WRITE;
/*!40000 ALTER TABLE `ct_category` DISABLE KEYS */;

INSERT INTO `ct_category` (`id`, `pid`, `group`, `doc_type`, `title`, `url`, `content`, `index_template`, `detail_template`, `post_auth`, `icon`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,0,1,3,'文章','','','index_default','detail_default',1,'fa fa-send-o',1431926468,1435895071,1,1),
	(9,0,1,1,'会员','User/index','','','',0,'fa fa-users',1435894071,1435895080,9,0),
	(10,0,1,1,'标签','Tag/index','','','',0,'fa fa-tags',1435896603,1435896603,11,0),
	(15,0,3,1,'底部导航','','','','',1,'fa fa-navicon',1435896768,1435896768,1,1),
	(16,15,3,1,'关于','','','','',0,'',1435896839,1435896839,0,1),
	(17,16,3,2,'关于我们','','','','',0,'',1435896882,1435921242,0,1),
	(18,16,3,2,'联系我们','','','','',0,'',1435896882,1435896882,0,1),
	(19,16,3,2,'友情链接','','','','',0,'',1435896882,1435896882,0,1),
	(20,16,3,2,'加入我们','','','','',0,'',1435896882,1435896882,0,1),
	(21,15,3,1,'帮助','','','','',0,'',1435922411,1435922411,0,1),
	(22,21,3,2,'用户协议','','','','',0,'',1435922579,1435922579,0,1),
	(23,21,3,2,'常见问题','','','','',0,'',1435922602,1435922602,0,1),
	(24,21,3,2,'意见反馈','','','','',0,'',1435922628,1435922628,0,1),
	(25,15,3,1,'服务产品','','','','',0,'',1435922794,1435922794,0,1),
	(26,25,3,1,'CoreThink框架','','','','',0,'',1435922823,1435922823,0,1),
	(27,25,3,1,'微＋微信平台','','','','',0,'',1435922866,1435923215,0,1),
	(28,15,3,1,'手册','','','','',0,'',1435922918,1435922918,0,1),
	(29,28,3,1,'CoreThink手册','','','','',0,'',1435922944,1435923226,0,1),
	(30,28,3,1,'ThinkPHP3.2手册','http://document.thinkphp.cn/manual_3_2.html','','','',0,'',1435923030,1435923030,0,1);

/*!40000 ALTER TABLE `ct_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_document
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document`;

CREATE TABLE `ct_document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `doc_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文档类型ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布者ID',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `comment` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赞数',
  `bad` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `mark` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档类型基础表';



# Dump of table ct_document_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document_article`;

CREATE TABLE `ct_document_article` (
  `id` int(11) unsigned NOT NULL COMMENT '文档ID',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '标题',
  `abstract` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `content` text NOT NULL COMMENT '正文内容',
  `tags` varchar(127) NOT NULL COMMENT '标签',
  `cover` int(11) NOT NULL DEFAULT '0' COMMENT '封面图片ID',
  `file` int(11) NOT NULL DEFAULT '0' COMMENT '附件ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章类型扩展表';



# Dump of table ct_document_attribute
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document_attribute`;

CREATE TABLE `ct_document_attribute` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段标题',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `doc_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档属性字段表';

LOCK TABLES `ct_document_attribute` WRITE;
/*!40000 ALTER TABLE `ct_document_attribute` DISABLE KEYS */;

INSERT INTO `ct_document_attribute` (`id`, `name`, `title`, `field`, `type`, `value`, `tip`, `show`, `options`, `doc_type`, `ctime`, `utime`, `status`)
VALUES
	(1,'cid','分类','int(11) unsigned NOT NULL ','select','0','所属分类',1,'',0,1383891233,1384508336,1),
	(2,'uid','用户ID','int(11) unsigned NOT NULL ','num','0','用户ID',0,'',0,1383891233,1384508336,1),
	(3,'view','阅读量','varchar(255) NOT NULL','num','0','标签',0,'',0,1413303715,1413303715,1),
	(4,'comment','评论数','int(11) unsigned NOT NULL ','num','0','评论数',0,'',0,1383891233,1383894927,1),
	(5,'good','赞数','int(11) unsigned NOT NULL ','num','0','赞数',0,'',0,1383891233,1384147827,1),
	(6,'bad','踩数','int(11) unsigned NOT NULL ','num','0','踩数',0,'',0,1407646362,1407646362,1),
	(7,'ctime','创建时间','int(11) unsigned NOT NULL ','time','0','创建时间',1,'',0,1383891233,1383895903,1),
	(8,'utime','更新时间','int(11) unsigned NOT NULL ','time','0','更新时间',0,'',0,1383891233,1384508277,1),
	(9,'sort','排序','int(11) unsigned NOT NULL ','num','0','用于显示的顺序',1,'',0,1383891233,1383895757,1),
	(10,'status','数据状态','tinyint(4) NOT NULL ','radio','1','数据状态',0,'-1:删除\r\n0:禁用\r\n1:正常',0,1383891233,1384508496,1),
	(11,'title','标题','char(127) NOT NULL ','text','','文档标题',1,'',3,1383891233,1383894778,1),
	(12,'abstract','简介','varchar(255) NOT NULL','textarea','','文档简介',1,'',3,1383891233,1384508496,1),
	(13,'content','正文内容','text','kindeditor','','文章正文内容',1,'',3,1383891233,1384508496,1),
	(14,'tags','文章标签','varchar(127) NOT NULL','tags','','标签',1,'',3,1383891233,1384508496,1),
	(15,'cover','封面','int(11) unsigned NOT NULL ','picture','0','文档封面',1,'',3,1383891233,1384508496,1),
	(16,'file','附件','int(11) unsigned NOT NULL ','file','0','附件',1,'',3,1439454552,1439454552,1);

/*!40000 ALTER TABLE `ct_document_attribute` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_document_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_document_type`;

CREATE TABLE `ct_document_type` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `title` char(16) NOT NULL DEFAULT '' COMMENT '模型标题',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '缩略图',
  `main_field` int(11) NOT NULL DEFAULT '0' COMMENT '主要字段',
  `list_field` varchar(127) NOT NULL DEFAULT '' COMMENT '列表显示字段',
  `field_sort` varchar(255) NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '' COMMENT '表单字段分组',
  `system` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '系统类型',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型表';

LOCK TABLES `ct_document_type` WRITE;
/*!40000 ALTER TABLE `ct_document_type` DISABLE KEYS */;

INSERT INTO `ct_document_type` (`id`, `name`, `title`, `icon`, `main_field`, `list_field`, `field_sort`, `field_group`, `system`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,'link','链接','fa fa-link',0,'','','',1,1426580628,1426580628,0,1),
	(2,'page','单页','fa fa-file-text',0,'','','',1,1426580628,1426580628,0,1),
	(3,'article','文章','fa fa-file-word-o',11,'11','{\"1\":[\"1\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\"],\"2\":[\"9\",\"7\"]}','1:基础\n2:扩展',0,1426580628,1426580628,0,1);

/*!40000 ALTER TABLE `ct_document_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_public_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_public_comment`;

CREATE TABLE `ct_public_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论父ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `table` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数据表ID',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分组',
  `data_id` int(11) unsigned NOT NULL COMMENT '数据ID',
  `content` text NOT NULL COMMENT '评论内容',
  `pictures` varchar(15) NOT NULL DEFAULT '' COMMENT '图片列表',
  `rate` tinyint(3) NOT NULL DEFAULT '0' COMMENT '评价/评分',
  `good` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赞数',
  `bad` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `ip` varchar(15) NOT NULL COMMENT '来源IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';



# Dump of table ct_public_digg
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_public_digg`;

CREATE TABLE `ct_public_digg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `table` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '数据表ID',
  `data_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数据ID',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT 'Digg类型',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Digg表';



# Dump of table ct_public_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_public_tag`;

CREATE TABLE `ct_public_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(32) NOT NULL COMMENT '标签',
  `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `group` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '分组',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '图标',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';



# Dump of table ct_public_upload
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_public_upload`;

CREATE TABLE `ct_public_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '上传ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件sha1编码',
  `location` varchar(15) NOT NULL DEFAULT '' COMMENT '文件存储位置',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件上传表';



# Dump of table ct_system_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_system_config`;

CREATE TABLE `ct_system_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '配置标题',
  `name` varchar(32) NOT NULL COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置值',
  `group` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `type` varchar(16) NOT NULL DEFAULT '' COMMENT '配置类型',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '配置额外值',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

LOCK TABLES `ct_system_config` WRITE;
/*!40000 ALTER TABLE `ct_system_config` DISABLE KEYS */;

INSERT INTO `ct_system_config` (`id`, `title`, `name`, `value`, `group`, `type`, `options`, `tip`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,'站点开关','TOGGLE_WEB_SITE','1',1,'select','0:关闭,1:开启','站点关闭后将不能访问',1378898976,1406992386,1,1),
	(2,'网站标题','WEB_SITE_TITLE','CoreThink',1,'text','','网站标题前台显示标题',1378898976,1379235274,2,1),
	(3,'网站口号','WEB_SITE_SLOGAN','轻量级WEB产品开发框架',1,'text','','网站口号、宣传标语、一句话介绍',1434081649,1434081649,2,1),
	(4,'网站LOGO','WEB_SITE_LOGO','',1,'picture','','网站LOGO',1407003397,1407004692,3,1),
	(5,'网站描述','WEB_SITE_DESCRIPTION','CoreThink是一套轻量级WEB产品开发框架，追求简单、高效、卓越。可轻松实现移动互联网时代支持多终端的轻量级WEB产品快速开发。系统功能采用模块化开发，内置丰富的模块，便于用户灵活扩展和二次开发。',1,'textarea','','网站搜索引擎描述',1378898976,1379235841,4,1),
	(6,'网站关键字','WEB_SITE_KEYWORD','南京科斯克网络科技、CoreThink',1,'textarea','','网站搜索引擎关键字',1378898976,1381390100,5,1),
	(7,'版权信息','WEB_SITE_COPYRIGHT','版权所有 © 2014-2015 科斯克网络科技',1,'text','','设置在网站底部显示的版权信息，如“版权所有 © 2014-2015 科斯克网络科技”',1406991855,1406992583,6,1),
	(8,'网站备案号','WEB_SITE_ICP','苏ICP备1502009-2号',1,'text','','设置在网站底部显示的备案号，如“苏ICP备1502009-2号\"',1378900335,1415983236,7,1),
	(9,'站点统计','WEB_SITE_STATISTICS','',1,'textarea','','支持百度、Google、cnzz等所有Javascript的统计代码',1407824190,1407824303,8,1),
	(10,'注册开关','TOGGLE_USER_REGISTER','1',2,'select','0:关闭注册\r\n1:允许注册','是否开放用户注册',1379504487,1379504580,1,1),
	(11,'允许注册方式','ALLOW_REG_TYPE','username,email,mobile',2,'checkbox','username:用户名注册\r\nemail:邮箱注册\r\nmobile:手机注册','',0,0,2,1),
	(12,'注册时间间隔','LIMIT_TIME_BY_IP','0',2,'num','','同一IP注册时间间隔秒数',1379228036,1379228036,3,1),
	(13,'评论开关','TOGGLE_USER_COMMENT','1',2,'select','0:关闭评论,1:允许评论','评论关闭后用户不能进行评论',1418715779,1418716106,4,1),
	(14,'文件上传大小','UPLOAD_FILE_SIZE','10',2,'num','','文件上传大小单位：MB',1428681031,1428681031,5,1),
	(15,'图片上传大小','UPLOAD_IMAGE_SIZE','2',2,'num','','图片上传大小单位：MB',1428681071,1428681071,6,1),
	(16,'敏感字词','SENSITIVE_WORDS','傻逼,垃圾',2,'textarea','','用户注册及内容显示敏感字词',1420385145,1420387079,7,1),
	(17,'后台主题','ADMIN_THEME','default',3,'select','default:默认主题\r\nblue:蓝色理想\r\ngreen:绿色生活','后台界面主题',1436678171,1436690570,1,1),
	(18,'URL模式','URL_MODEL','3',3,'select','0:普通模式\r\n1:PATHINFO模式\r\n2:REWRITE模式\r\n3:兼容模式','',1438423248,1438423248,2,1),
	(19,'是否显示页面Trace','SHOW_PAGE_TRACE','0',3,'select','0:关闭\r\n1:开启','是否显示页面Trace信息',1387165685,1387165685,3,1),
	(20,'开发模式','DEVELOP_MODE','1',3,'select','1:开启\r\n0:关闭','开发模式下会显示菜单管理、配置管理、数据字典等开发者工具',1432393583,1432393583,4,1),
	(21,'静态资源版本标识','STATIC_VERSION','20150803',3,'text','','静态资源版本标识可以防止服务器缓存',1438564784,1438564784,5,1),
	(22,'CDN静态资源列表','CDN_RESOURCE_LIST','',3,'textarea','','配置此项后系统自带的jquery等类库将不会再重复加载',1438564784,1438564784,6,1),
	(23,'系统加密KEY','AUTH_KEY','CoreThink',3,'textarea','','轻易不要修改此项，否则容易造成用户无法登录；如要修改，务必备份原key',1438647773,1438647815,7,1),
	(24,'配置分组','CONFIG_GROUP_LIST','1:基本\r\n2:用户\r\n3:系统\r\n',3,'array','','配置分组',1379228036,1426930700,8,1),
	(25,'分页数量','ADMIN_PAGE_ROWS','10',3,'num','','分页时每页的记录数',1434019462,1434019481,9,1),
	(26,'栏目分组','CATEGORY_GROUP_LIST','1:默认\r\n3:导航\r\n',3,'array','','栏目分类分组',1433602137,1433602165,10,1);

/*!40000 ALTER TABLE `ct_system_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_system_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_system_menu`;

CREATE TABLE `ct_system_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(127) NOT NULL DEFAULT '' COMMENT '链接地址',
  `icon` varchar(64) NOT NULL COMMENT '图标',
  `dev` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发模式可见',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

LOCK TABLES `ct_system_menu` WRITE;
/*!40000 ALTER TABLE `ct_system_menu` DISABLE KEYS */;

INSERT INTO `ct_system_menu` (`id`, `pid`, `title`, `url`, `icon`, `dev`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,0,'首页','Admin/Index/index','fa fa-home',0,1426580628,1438276217,1,1),
	(2,0,'系统','Admin/SystemConfig/group','fa fa-cog',0,1426580628,1438276235,2,1),
	(3,0,'内容','Admin/Category/index','fa fa-tasks',0,1430290092,1438276260,2,1),
	(4,0,'用户','Admin/User/index','fa fa-users',0,1426580628,1438276990,3,1),
	(5,0,'其它','Admin/Other/index','fa fa-cloud',0,1426580628,1426580628,3,0),
	(6,1,'系统操作','','fa fa-folder-open-o',0,1426580628,1426580628,1,1),
	(7,2,'系统功能','','fa fa-folder-open-o',0,1426580628,1438277612,1,1),
	(8,2,'扩展中心','','fa fa-folder-open-o',0,1437185077,1437185164,2,1),
	(9,2,'数据中心','','fa fa-folder-open-o',0,1426580628,1426580628,3,1),
	(10,3,'内容管理','','fa fa-folder-open-o',0,1430290276,1438277591,1,1),
	(11,3,'文件管理','','fa fa-folder-open-o',0,1430290276,1438277393,1,1),
	(12,4,'用户管理','','fa fa-folder-open-o',0,1426580628,1438277454,1,1),
	(13,6,'清空缓存','Admin/Index/rmdirr','',0,1427475588,1427475588,1,1),
	(14,7,'系统设置','Admin/SystemConfig/group','fa fa-gears',0,1426580628,1438276516,1,1),
	(15,14,'修改设置','Admin/SystemConfig/groupSave','',0,1426580628,1426580628,1,1),
	(16,7,'文档模型','Admin/DocumentType/index','fa fa-th-list',1,1426580628,1438277140,2,1),
	(17,16,'添加','Admin/DocumentType/add','',0,1426580628,1426580628,1,1),
	(18,16,'编辑','Admin/DocumentType/edit','',0,1426580628,1426580628,2,1),
	(19,16,'设置状态','Admin/DocumentType/setStatus','',0,1426580628,1426580628,3,1),
	(20,16,'字段管理','Admin/DocumentAttribute/index','',0,1426580628,1430291065,1,1),
	(21,20,'添加','Admin/DocumentAttribute/add','',0,1426580628,1426580628,1,1),
	(22,20,'编辑','Admin/DocumentAttribute/edit','',0,1426580628,1426580628,2,1),
	(23,20,'设置状态','Admin/DocumentAttribute/setStatus','',0,1426580628,1426580628,3,1),
	(24,7,'菜单管理','Admin/SystemMenu/index','fa fa-bars',1,1426580628,1438276552,3,1),
	(25,24,'添加','Admin/SystemMenu/add','',0,1426580628,1426580628,1,1),
	(26,24,'编辑','Admin/SystemMenu/edit','',0,1426580628,1426580628,2,1),
	(27,24,'设置状态','Admin/SystemMenu/setStatus','',0,1426580628,1426580628,3,1),
	(28,7,'配置管理','Admin/SystemConfig/index','fa fa-cogs',1,1426580628,1438276571,4,1),
	(29,28,'添加','Admin/SystemConfig/add','',0,1426580628,1426580628,1,1),
	(30,28,'编辑','Admin/SystemConfig/edit','',0,1426580628,1426580628,2,1),
	(31,28,'设置状态','Admin/SystemConfig/setStatus','',0,1426580628,1426580628,3,1),
	(32,8,'功能模块','Admin/SystemModule/index','fa fa-th-large',0,1437185242,1438276915,1,1),
	(33,32,'安装','Admin/SystemModule/install','',0,1427475588,1427475588,1,1),
	(34,32,'卸载','Admin/SystemModule/uninstall','',0,1427475588,1427475588,2,1),
	(35,32,'更新信息','Admin/SystemModule/updateInfo','',0,1427475588,1427475588,3,1),
	(36,32,'设置状态','Admin/SystemModule/setStatus','',0,1427475588,1427475588,4,1),
	(37,8,'前台主题','Admin/SystemTheme/index','fa fa-adjust',0,1437185290,1438277065,2,1),
	(38,37,'安装','Admin/SystemTheme/install','',0,1427475588,1427475588,1,1),
	(39,37,'卸载','Admin/SystemTheme/uninstall','',0,1427475588,1427475588,2,1),
	(40,37,'更新信息','Admin/SystemTheme/updateInfo','',0,1427475588,1427475588,3,1),
	(41,37,'设置状态','Admin/SystemTheme/setStatus','',0,1427475588,1427475588,4,1),
	(42,37,'切换主题','Admin/SystemTheme/setCurrent','',0,1427475588,1427475588,5,1),
	(43,8,'系统插件','Admin/Addon/index','fa fa-th',0,1427475588,1438277115,3,1),
	(44,43,'安装','Admin/Addon/install','',0,1427475588,1427475588,1,1),
	(45,43,'卸载','Admin/Addon/uninstall','',0,1427475588,1427475588,2,1),
	(46,43,'执行','Admin/Addon/execute','',0,1427475588,1427475588,3,1),
	(47,43,'插件设置','Admin/Addon/config','',0,1427475588,1427475588,4,1),
	(48,43,'插件后台管理','Admin/Addon/adminList','',0,1427475588,1427475588,5,1),
	(49,48,'新增数据','Admin/Addon/adminAdd','',0,1426580628,1426580628,1,1),
	(50,48,'编辑数据','Admin/Addon/adminEdit','',0,1426580628,1426580628,2,1),
	(51,48,'设置状态','Admin/Addon/setStatus','',0,1426580628,1426580628,3,1),
	(52,9,'数据字典','Admin/Datebase/index','fa fa-database',1,1429851071,1438276624,1,1),
	(53,9,'数据备份','Admin/Datebase/export','fa fa-copy',0,1426580628,1426580628,2,1),
	(54,53,'备份','Admin/Datebase/do_export','',0,1426580628,1426580628,1,1),
	(55,53,'优化表','Admin/Datebase/optimize','',0,1426580628,1426580628,2,1),
	(56,53,'修复表','Admin/Datebase/repair','',0,1426580628,1426580628,3,1),
	(57,9,'数据还原','Admin/Datebase/import','fa fa-refresh',1,1426580628,1438276890,3,1),
	(58,57,'还原备份','Admin/Datebase/do_import','',0,1426580628,1426580628,1,1),
	(59,57,'删除备份','Admin/Datebase/del','',0,1426580628,1426580628,2,1),
	(60,10,'栏目分类','Admin/Category/index','fa fa-th-list',0,1426580628,1438277233,1,1),
	(61,60,'添加','Admin/Category/add','',0,1426580628,1426580628,1,1),
	(62,60,'编辑','Admin/Category/edit','',0,1426580628,1426580628,2,1),
	(63,60,'设置状态','Admin/Category/setStatus','',0,1426580628,1426580628,3,1),
	(64,60,'文档列表','Admin/Document/index','',0,1427475588,1427475588,4,1),
	(65,64,'添加','Admin/Document/add','',0,1426580628,1426580628,1,1),
	(66,64,'编辑','Admin/Document/edit','',0,1426580628,1426580628,2,1),
	(67,64,'移动','Admin/Document/move','',0,1426580628,1426580628,3,1),
	(68,64,'设置状态','Admin/Document/setStatus','',0,1426580628,1426580628,4,1),
	(69,10,'标签列表','Admin/PublicTag/index','fa fa-tags',0,1426580628,1438277250,3,1),
	(70,69,'添加','Admin/PublicTag/add','',0,1426580628,1426580628,1,1),
	(71,69,'编辑','Admin/PublicTag/edit','',0,1426580628,1426580628,2,1),
	(72,69,'设置状态','Admin/PublicTag/setStatus','',0,1426580628,1426580628,3,1),
	(73,69,'搜索标签(自动完成)','Admin/PublicTag/searchTags','',0,1426580628,1426580628,4,1),
	(74,10,'万能评论','Admin/PublicComment/index','fa fa-comments',0,1426580628,1438277284,4,1),
	(75,74,'添加','Admin/PublicComment/add','',0,1426580628,1426580628,1,1),
	(76,74,'编辑','Admin/PublicComment/edit','',0,1426580628,1426580628,2,1),
	(77,74,'设置状态','Admin/PublicComment/setStatus','',0,1426580628,1426580628,3,1),
	(78,10,'回收站','Admin/Document/recycle','fa fa-trash',0,1427475588,1438277313,5,1),
	(79,11,'上传管理','Admin/PublicUpload/index','fa fa-upload',0,1427475588,1438277518,1,1),
	(80,79,'上传文件','Admin/PublicUpload/upload','',0,1427475588,1427475588,1,1),
	(81,79,'删除文件','Admin/PublicUpload/delete','',0,1427475588,1427475588,2,1),
	(82,79,'设置状态','Admin/PublicUpload/setStatus','',0,1426580628,1426580628,3,1),
	(83,79,'下载图片','Admin/PublicUpload/downremoteimg','',0,1427475588,1427475588,4,1),
	(84,79,'文件浏览','Admin/PublicUpload/fileManager','',0,1427475588,1427475588,5,1),
	(85,12,'用户列表','Admin/User/index','fa fa-user',0,1426580628,1438277505,1,1),
	(86,85,'添加','Admin/User/add','',0,1426580628,1426580628,1,1),
	(87,85,'编辑','Admin/User/edit','',0,1426580628,1426580628,2,1),
	(88,85,'设置状态','Admin/User/setStatus','',0,1426580628,1426580628,3,1),
	(89,12,'部门管理','Admin/UserGroup/index','fa fa-sitemap',0,1426580628,1438277438,2,1),
	(90,89,'添加','Admin/UserGroup/add','',0,1426580628,1426580628,1,1),
	(91,89,'编辑','Admin/UserGroup/edit','',0,1426580628,1426580628,2,1),
	(92,89,'设置状态','Admin/UserGroup/setStatus','',0,1426580628,1426580628,3,1),
	(93,12,'消息列表','Admin/UserMessage/index','fa fa-envelope-o',0,1440050363,1440050363,3,1),
	(94,93,'添加','Admin/UserMessage/add','',0,1426580628,1426580628,1,1),
	(95,93,'编辑','Admin/UserMessage/edit','',0,1426580628,1426580628,2,1),
	(96,93,'设置状态','Admin/UserMessage/setStatus','',0,1426580628,1426580628,3,1);

/*!40000 ALTER TABLE `ct_system_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_system_module
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_system_module`;

CREATE TABLE `ct_system_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `developer` varchar(32) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本',
  `admin_menu` text NOT NULL COMMENT '菜单节点',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块功能表';



# Dump of table ct_system_theme
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_system_theme`;

CREATE TABLE `ct_system_theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `developer` varchar(32) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本',
  `config` text NOT NULL COMMENT '主题配置',
  `current` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否当前主题',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='前台主题表';

LOCK TABLES `ct_system_theme` WRITE;
/*!40000 ALTER TABLE `ct_system_theme` DISABLE KEYS */;

INSERT INTO `ct_system_theme` (`id`, `name`, `title`, `description`, `developer`, `version`, `config`, `current`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,'default','默认主题','CoreThink默认主题','南京科斯克网络科技有限公司','1.0','',1,1437786501,1437786501,0,1);

/*!40000 ALTER TABLE `ct_system_theme` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_user`;

CREATE TABLE `ct_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `usertype` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名或昵称',
  `email` varchar(32) DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '用户密码',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门/用户组ID',
  `vip` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT ' VIP等级',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户头像',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `sex` enum('-1','0','1') NOT NULL DEFAULT '0' COMMENT '用户性别',
  `age` int(4) NOT NULL DEFAULT '0' COMMENT '年龄',
  `birthday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '生日',
  `summary` varchar(127) NOT NULL DEFAULT '' COMMENT '心情',
  `realname` varchar(15) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `idcard_no` varchar(18) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `extend` varchar(1024) NOT NULL DEFAULT '' COMMENT '用户信息扩展',
  `login` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最近登陆时间',
  `last_login_ip` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最近登陆IP',
  `reg_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_type` varchar(12) NOT NULL DEFAULT '' COMMENT '注册方式',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户会员信息表';

LOCK TABLES `ct_user` WRITE;
/*!40000 ALTER TABLE `ct_user` DISABLE KEYS */;

INSERT INTO `ct_user` (`id`, `usertype`, `username`, `email`, `mobile`, `password`, `group`, `vip`, `avatar`, `score`, `money`, `sex`, `age`, `birthday`, `summary`, `realname`, `idcard_no`, `extend`, `login`, `last_login_time`, `last_login_ip`, `reg_ip`, `reg_type`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,0,'admin',NULL,NULL,'79cc780bd21b161230268824080b8476',1,0,0,0,0.00,'1',0,0,'','','','',1,1438651748,2130706433,2130706433,'admin',1438651748,1438651748,0,1);

/*!40000 ALTER TABLE `ct_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_user_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_user_group`;

CREATE TABLE `ct_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级部门ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '部门名称',
  `icon` varchar(32) NOT NULL COMMENT '图标',
  `menu_auth` text COMMENT '菜单权限',
  `category_auth` text COMMENT '栏目分类权限',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='部门信息表';

LOCK TABLES `ct_user_group` WRITE;
/*!40000 ALTER TABLE `ct_user_group` DISABLE KEYS */;

INSERT INTO `ct_user_group` (`id`, `pid`, `title`, `icon`, `menu_auth`, `category_auth`, `ctime`, `utime`, `sort`, `status`)
VALUES
	(1,0,'管理员','','','',1426881003,1427552428,0,1);

/*!40000 ALTER TABLE `ct_user_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ct_user_message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ct_user_message`;

CREATE TABLE `ct_user_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息父ID',
  `title` varchar(1024) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text COMMENT '消息内容',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0系统消息,1评论消息,2私信消息',
  `to_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '接收用户ID',
  `from_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '私信消息发信用户ID',
  `is_read` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `utime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息表';
