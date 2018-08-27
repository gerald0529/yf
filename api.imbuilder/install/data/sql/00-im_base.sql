-- Adminer 4.3.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';


DROP TABLE IF EXISTS `im_chatlog`;
CREATE TABLE `im_chatlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(200) NOT NULL COMMENT '发送人',
  `receiver` varchar(200) NOT NULL COMMENT '收信息人',
  `type` varchar(50) NOT NULL COMMENT '类型',
  `content` text NOT NULL COMMENT '内容',
  `msgid` varchar(200) NOT NULL COMMENT '消息类型',
  `created` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='聊天记录';



DROP TABLE IF EXISTS `im_sns_base`;
CREATE TABLE `im_sns_base` (
  `sns_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `user_name` varchar(100) NOT NULL COMMENT '会员名称',
  `sns_title` blob NOT NULL COMMENT '标题',
  `sns_content` text NOT NULL COMMENT '内容',
  `sns_img` text NOT NULL COMMENT '图片',
  `sns_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2 pic 3pro 4shop ',
  `sns_create_time` int(11) NOT NULL COMMENT '添加时间',
  `sns_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除  0-未删除 1-已删除',
  `sns_privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  `sns_comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论数',
  `sns_copy_count` int(11) NOT NULL DEFAULT '0' COMMENT '转发数',
  `sns_like_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `sns_like_user` varchar(255) NOT NULL DEFAULT '' COMMENT '点赞会员',
  `sns_forward` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否转发 0-不转发 1-转发',
  `sns_lable` varchar(255) DEFAULT NULL COMMENT '标签',
  `sns_collection` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子收藏数',
  `sns_like_user_addtime` int(11) NOT NULL COMMENT '点赞用户时间',
  PRIMARY KEY (`sns_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='动态信息表';


DROP TABLE IF EXISTS `im_sns_collection`;
CREATE TABLE `im_sns_collection` (
  `collect_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sns_id` int(11) NOT NULL COMMENT '动态信息ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `collect_time` int(11) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`collect_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收藏表';


DROP TABLE IF EXISTS `im_sns_comment`;
CREATE TABLE `im_sns_comment` (
  `commect_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `user_name` varchar(100) NOT NULL COMMENT '会员名称',
  `sns_id` int(11) NOT NULL COMMENT '原帖ID',
  `commect_content` varchar(255) NOT NULL COMMENT '评论内容',
  `commect_addtime` int(11) NOT NULL COMMENT '添加时间',
  `commect_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0正常 1屏蔽',
  `commect_like` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否点赞',
  `to_commect_id` int(11) NOT NULL COMMENT '被回复的评论id，0为对动态信息进行评论',
  `to_commect_name` varchar(100) NOT NULL COMMENT '回复的评论者',
  PRIMARY KEY (`commect_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论表';


DROP TABLE IF EXISTS `im_sns_forward`;
CREATE TABLE `im_sns_forward` (
  `forward_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sns_id` int(11) NOT NULL COMMENT '被转发帖子ID',
  `forward_sns_id` int(11) NOT NULL COMMENT '转发后的新帖ID',
  `source_sns_id` int(11) NOT NULL COMMENT '原始帖子ID',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`forward_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='转播表';


DROP TABLE IF EXISTS `im_sns_like`;
CREATE TABLE `im_sns_like` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sns_id` int(11) NOT NULL COMMENT '动态信息ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `like_time` int(11) NOT NULL COMMENT '点赞时间',
  PRIMARY KEY (`like_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='点赞表';


DROP TABLE IF EXISTS `im_sns_timeline`;
CREATE TABLE `im_sns_timeline` (
  `timeline_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `sns_id` int(10) NOT NULL COMMENT '密码',
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '活动时间',
  PRIMARY KEY (`timeline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户消息时间轴表';


DROP TABLE IF EXISTS `im_user_app`;
CREATE TABLE `im_user_app` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '服务ID',
  `app_name` varchar(40) NOT NULL DEFAULT '' COMMENT '服务名称',
  `app_key` varchar(50) NOT NULL DEFAULT '' COMMENT '服务密钥',
  `app_url` varchar(255) NOT NULL DEFAULT '' COMMENT '服务网址',
  `app_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态  1：启用  0：禁用',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id-平台id，平台结算最后映射到这个用户账户中-platform_id，server_id, platform_user_id',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='应用配置表';


DROP TABLE IF EXISTS `im_user_base`;
CREATE TABLE `im_user_base` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_account` varchar(50) NOT NULL COMMENT '用户帐号',
  `user_password` char(32) NOT NULL COMMENT '密码：使用用户中心-此处废弃',
  `user_key` varchar(32) NOT NULL COMMENT '用户Key',
  `user_email` varchar(100) NOT NULL COMMENT '用户Email',
  `rights_group_id` smallint(4) NOT NULL COMMENT '用户权限组id',
  `user_rights_ids` text NOT NULL COMMENT '用户权限',
  `user_delete` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否被封禁，0：未封禁，1：封禁',
  `user_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `server_id` mediumint(8) NOT NULL COMMENT '服务id-公司关联-关联数据库-key中',
  PRIMARY KEY (`user_id`,`user_password`),
  UNIQUE KEY `user_account` (`user_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户基础信息表';


DROP TABLE IF EXISTS `im_user_friend`;
CREATE TABLE `im_user_friend` (
  `user_friend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `friend_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `user_friend_is_xin` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否授信 1-未授信 2-已授信',
  `user_friend_xin_limit` int(100) NOT NULL DEFAULT '0' COMMENT '授信额度',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '属于哪个好友分组',
  `friend_describe` varchar(50) NOT NULL DEFAULT '' COMMENT '好友昵称',
  `friend_status` int(1) NOT NULL DEFAULT '0' COMMENT '0不是互为好友 1 互为好友',
  PRIMARY KEY (`user_friend_id`),
  UNIQUE KEY `user_id` (`user_id`,`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户好友关系表';


DROP TABLE IF EXISTS `im_user_friend_group`;
CREATE TABLE `im_user_friend_group` (
  `user_friend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `friend_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '好友id',
  `user_friend_is_xin` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否授信 1-未授信 2-已授信',
  `user_friend_xin_limit` int(100) NOT NULL DEFAULT '0' COMMENT '授信额度',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`user_friend_id`),
  UNIQUE KEY `user_id` (`user_id`,`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户好友关系表';


DROP TABLE IF EXISTS `im_user_goods`;
CREATE TABLE `im_user_goods` (
  `user_goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_common_id` int(11) NOT NULL COMMENT '商品common_id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `goods_name` varchar(255) NOT NULL COMMENT '产品名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '产品价格',
  `goods_pic` varchar(255) NOT NULL COMMENT '产品图片地址',
  `goods_url` varchar(255) NOT NULL COMMENT '点击产品跳转的url',
  `goods_status` tinyint(1) NOT NULL COMMENT '商品状态',
  `goods_verify` tinyint(1) NOT NULL COMMENT '商品审核状态',
  `time` date NOT NULL COMMENT '分享时间',
  `click_number` int(50) NOT NULL DEFAULT '0' COMMENT '产品点击数',
  PRIMARY KEY (`user_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分享表';


DROP TABLE IF EXISTS `im_user_group`;
CREATE TABLE `im_user_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '好友组ID',
  `group_name` varchar(30) NOT NULL DEFAULT '' COMMENT '组名称',
  `group_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '群组类型 0：临时组(上限100人)  1：普通组(上限300人)  2：VIP组 (上限500人)',
  `group_permission` tinyint(4) NOT NULL DEFAULT '0' COMMENT '申请加入模式 0：默认直接加入1：需要身份验证 2:私有群组',
  `group_declared` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(10) NOT NULL COMMENT '管理员',
  `group_bind_id` varchar(32) NOT NULL DEFAULT '',
  `group_describe` varchar(30) NOT NULL COMMENT '群组昵称',
  `group_user` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='好友管理组';


DROP TABLE IF EXISTS `im_user_group_rel`;
CREATE TABLE `im_user_group_rel` (
  `group_rel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL COMMENT '好友组ID',
  `user_id` int(10) NOT NULL COMMENT '成员id',
  `user_label` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_rel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='好友管理组';


DROP TABLE IF EXISTS `im_user_info`;
CREATE TABLE `im_user_info` (
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `user_group` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `user_limit` int(11) unsigned NOT NULL DEFAULT '10000' COMMENT '平台授信额度',
  `limit_remain` int(11) NOT NULL DEFAULT '10000' COMMENT '剩余平台授信额度',
  `user_site_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户站点ID',
  `user_site_domain` varchar(50) DEFAULT NULL COMMENT '用户站点域名',
  `user_question` varchar(100) DEFAULT NULL COMMENT '密码提示问题',
  `user_answer` varchar(100) DEFAULT NULL COMMENT '密码提示答案',
  `user_avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `user_avatar_thumb` varchar(255) DEFAULT NULL COMMENT '头像缩略图',
  `user_gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `user_truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `user_tel` varchar(30) DEFAULT NULL COMMENT '电话',
  `user_birth` varchar(10) DEFAULT NULL COMMENT '生日(YYYY-MM-DD)',
  `user_email` varchar(100) NOT NULL COMMENT '用户邮箱',
  `user_qq` varchar(20) DEFAULT NULL COMMENT 'QQ',
  `user_msn` varchar(255) DEFAULT NULL COMMENT 'MSN',
  `user_province` varchar(20) DEFAULT NULL COMMENT '省份',
  `user_city` varchar(20) DEFAULT NULL COMMENT '城市',
  `user_intro` text COMMENT '个人简介',
  `user_sign` varchar(500) DEFAULT NULL COMMENT '用户签名',
  `user_reg_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_count_login` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `user_lastlogin_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `user_lastlogin_ip` varchar(15) DEFAULT NULL COMMENT '上次登录IP',
  `user_like_games` text COMMENT '喜爱的游戏',
  `user_reg_ip` varchar(15) DEFAULT NULL COMMENT '注册IP',
  `user_money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户资金',
  `user_credit` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_getpsw_code` varchar(32) DEFAULT NULL COMMENT '取回密码认证码',
  `user_verify_code` varchar(32) DEFAULT NULL COMMENT '认证账号认证码',
  `user_phone_code` varchar(32) DEFAULT NULL COMMENT '手机验证码',
  `user_phone_code_stats` varchar(20) DEFAULT NULL COMMENT '获取手机验证码记录(获取次数,上次获取时间)',
  `user_idcard` varchar(30) DEFAULT NULL COMMENT '身份证',
  `user_mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `user_binded` varchar(50) DEFAULT NULL COMMENT '已绑定项目',
  `user_history_password` text COMMENT '历史密码',
  `user_history_ip` text COMMENT '历史 IP',
  `background` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户详细信息表';


DROP TABLE IF EXISTS `im_user_login`;
CREATE TABLE `im_user_login` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_login_times` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '登录次数',
  `user_last_login_time` int(10) unsigned NOT NULL COMMENT '最后登录时间',
  `user_active_time` int(10) unsigned NOT NULL COMMENT '激活时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户登录信息表';


DROP TABLE IF EXISTS `im_user_msg`;
CREATE TABLE `im_user_msg` (
  `msg_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `app_id_sender` varchar(50) NOT NULL COMMENT '发送者id',
  `msg_sender` varchar(100) NOT NULL COMMENT '发送者名称',
  `app_id_receiver` varchar(50) NOT NULL COMMENT '接受者id',
  `msg_receiver` varchar(100) NOT NULL COMMENT '接收者名称',
  `device_type` tinyint(1) NOT NULL,
  `msg_len` int(11) NOT NULL,
  `msg_type` int(11) NOT NULL,
  `msg_content` varchar(500) NOT NULL,
  `msg_file_url` varchar(500) NOT NULL,
  `msg_file_name` varchar(500) NOT NULL,
  `group_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `msg_file_size` int(11) NOT NULL,
  `date_created` varchar(100) NOT NULL COMMENT '日期',
  `msg_domain` varchar(500) NOT NULL COMMENT '说明',
  PRIMARY KEY (`msg_log_id`),
  UNIQUE KEY `app_id_sender` (`msg_sender`,`msg_receiver`,`msg_id`,`date_created`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息记录表';


DROP TABLE IF EXISTS `im_user_msg_sync`;
CREATE TABLE `im_user_msg_sync` (
  `msg_sync_date` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `msg_sync_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否同步：0：未同步； 1:已经同步',
  PRIMARY KEY (`msg_sync_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='同步标记表';


DROP TABLE IF EXISTS `im_user_push`;
CREATE TABLE `im_user_push` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `user_name` varchar(50) DEFAULT NULL,
  `fuid` int(10) DEFAULT NULL COMMENT '添加的好友id',
  `funame` varchar(50) DEFAULT NULL COMMENT '添加的好友的名字',
  `addtime` int(15) DEFAULT NULL COMMENT '时间戳',
  `replay_id` int(1) DEFAULT '0' COMMENT '1代表同意 2代表拒绝',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`user_id`,`fuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='添加好友推送信息表';


DROP TABLE IF EXISTS `im_user_rname`;
CREATE TABLE `im_user_rname` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL COMMENT '用户id',
  `friendid` int(11) DEFAULT NULL COMMENT '好友id',
  `edit_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `content` varchar(250) DEFAULT NULL COMMENT '修改内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户备注';


DROP TABLE IF EXISTS `im_user_visitor`;
CREATE TABLE `im_user_visitor` (
  `visitor_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表主键',
  `visitor_user_account` varchar(50) NOT NULL COMMENT '访问者用户名',
  `master_id` varchar(255) NOT NULL COMMENT '被访问者',
  `visitor_time` date NOT NULL COMMENT '访问时间',
  `visitor_user_id` int(50) NOT NULL DEFAULT '0' COMMENT '访问者id',
  PRIMARY KEY (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `im_web_config`;
CREATE TABLE `im_web_config` (
  `config_key` varchar(50) NOT NULL COMMENT '数组下标',
  `config_value` text NOT NULL COMMENT '数组值',
  `config_type` varchar(50) NOT NULL,
  `config_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `config_comment` text NOT NULL,
  `config_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`config_key`),
  KEY `index` (`config_key`,`config_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站配置表';

INSERT INTO `im_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES
('23123', 'faf',  'msg_tpl',  1,  'fasf', 'string'),
('article_description', '5',  'seo',  1,  '', 'string'),
('article_description_content', '5',  'seo',  1,  '', 'string'),
('article_keyword', '软沙发',  'seo',  1,  '', 'string'),
('article_keyword_content', '7',  'seo',  1,  '', 'string'),
('article_title', '{sitename}-文章{name}',  'seo',  1,  '', 'string'),
('article_title_content', '7{sitename}',  'seo',  1,  '', 'string'),
('authenticate',  'faf',  'msg_tpl',  1,  '身份验证通知', 'string'),
('baseurl', 'demo.bbc-builder.com', 'main', 1,  '', 'string'),
('bind_email',  'faf',  'msg_tpl',  1,  '邮箱验证通知', 'string'),
('body_skin', 'image/default/bg.jpg', 'main', 1,  '', 'string'),
('brand_description', '2313', 'seo',  1,  '', 'string'),
('brand_description_content', 'trsegt', 'seo',  1,  '', 'string'),
('brand_keyword', '23123',  'seo',  1,  '', 'string'),
('brand_keyword_content', '123123', 'seo',  1,  '', 'string'),
('brand_title', 'j{sitename}23123', 'seo',  1,  '', 'string'),
('brand_title_content', 'gfnjgmjn', 'seo',  1,  '', 'string'),
('cacheTime', '1000', 'main', 1,  '', 'string'),
('captcha_status_goodsqa',  '1',  'dumps',  1,  '', 'number'),
('captcha_status_login',  '1',  'dump', 1,  '', 'number'),
('captcha_status_register', '1',  'dump', 1,  '', 'number'),
('category_description',  '分类', 'seo',  1,  '', 'string'),
('category_keyword',  '分类', 'seo',  1,  '', 'string'),
('category_title',  '商品分类{name}{sitename}', 'seo',  1,  '', 'string'),
('closecon',  '', 'main', 1,  '', 'string'),
('closed_reason', '333333', 'site', 1,  '', 'string'),
('closetype', '0',  'main', 1,  '', 'string'),
('complain_datetime', '2',  'complain', 1,  '', 'string'),
('consult_header_text', '<p>11111111111111</p>',  'consult',  1,  '', 'string'),
('copyright', 'BBCBuilder版权所有,正版购买地址:  <a href=\"http://www.bbc-builder.com\">http://www.bbc-builder.com</a>  \r\n<br />Powered by BBCbuilder V2.6.1\r\n       ', 'site', 1,  '', 'string'),
('date_format', 'Y-m-d',  'site', 1,  '', 'string'),
('description', '网上超市，最经济实惠的网上购物商城，用鼠标逛超市，不用排队，方便实惠送上门，网上购物新生活。', 'seo',  1,  '', 'string'),
('domaincity',  '0',  'main', 1,  '', 'string'),
('domain_length', '3-12', 'domain', 1,  '', 'string'),
('domain_modify_frequency', '1',  'domain', 1,  '', 'number'),
('drp_is_open', '0',  'main', 1,  '', 'string'),
('email', '250314853@qq.com', 'main', 1,  '', 'string'),
('email_addr',  'rd02@yuanfeng021.com', 'email',  1,  '', 'string'),
('email_host',  'smtp.exmail.qq.com', 'email',  1,  '', 'string'),
('email_id',  'rd02', 'email',  1,  '', 'string'),
('email_pass',  'huangxinze1',  'email',  1,  '', 'string'),
('email_port',  '465',  'email',  1,  '', 'number'),
('enable_gzip', '0',  'main', 1,  '', 'string'),
('enable_tranl',  '1',  'main', 1,  '', 'string'),
('fafaf', '身份验证通知', 'msg_tpl',  1,  '【{$site_name}】您于{$send_time}提交账户安全验证，验证码是：{$verify_code}。',  'string'),
('goods_verify_flag', '1',  'goods',  1,  '//商品是否需要审核', 'string'),
('grade_evaluate',  '50', 'grade',  1,  '订单评论获取成长值',  'number'),
('grade_login', '12', 'grade',  1,  '登陆获取成长值',  'number'),
('grade_order', '800',  'grade',  1,  '订单评论获取成长值上限',  'number'),
('grade_recharge',  '100',  'grade',  1,  '订单每多少获取多少成长值', 'number'),
('groupbuy_allow',  '1',  'promotion',  1,  '是否开启团购', 'number'),
('groupbuy_price',  '100',  'groupbuy', 1,  '', 'number'),
('groupbuy_review_day', '0',  'groupbuy', 1,  '', 'number'),
('guest_comment', '1',  'dumps',  1,  '', 'string'),
('hot_commen',  '31,42,47,34,35,25,44,26,27,46',  'home', 1,  '', 'string'),
('hot_sell',  '42,37,28,41,30,31,42,47,34,35',  'home', 1,  '', 'string'),
('icp_number',  '5.4435234534253',  'site', 1,  '', 'string'),
('image_allow_ext', 'gif,jpg,jpeg,bmp,png,swf', 'upload', 1,  '图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 \",\" 隔开。', 'string'),
('image_max_filesize',  '2000', 'upload', 1,  '图片文件大小', 'number'),
('image_storage_type',  '', 'upload', 1,  '图片存放类型-程序内置较优方式',  'string'),
('im_appId',  '8aaf0708565f52a9015669109c0a0351', 'im', 1,  '', 'string'),
('im_appToken', '298e6f50cb243133ddd1b836d396029a', 'im', 1,  '', 'string'),
('im_statu',  '0',  'im', 1,  '', 'string'),
('index_catid', '1000,1002,1001,1003,1005', 'home', 1,  '', 'string'),
('index_liandong1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/plantform/image/20160718/1468825145755207.png',  'index_liandong', 1,  '首页联动小图1',  'string'),
('index_liandong2_image', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470221116664496.jpg!236x236.jpg',  'index_liandong', 1,  '首页联动小图2',  'string'),
('index_liandong_url1', 'http：shouye.com1', 'index_liandong', 1,  '首页联动小图url1', 'string'),
('index_liandong_url2', 'http://localhost/shop/yf_shop/index.php',  'index_liandong', 1,  '首页联动小图url2', 'string'),
('index_live_link1',  'http://localhost/shop/yf_shop/index.php',  'index_slider', 1,  '首页轮播url1', 'string'),
('index_live_link2',  'http://localhost/shop/yf_shop/index.php',  'index_slider', 1,  '首页轮播url2', 'string'),
('index_live_link3',  'http://localhost/shop/yf_shop/index.php',  'index_slider', 1,  '首页轮播url3', 'string'),
('index_live_link4',  'http://localhost/shop/yf_shop/index.php',  'index_slider', 1,  '首页轮播url4', 'string'),
('index_live_link5',  'http://localhost/shop/yf_shop/index.php11',  'index_slider', 1,  '首页轮播url5', 'string'),
('index_newsid',  '1',  'home', 1,  '', 'string'),
('index_slider1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826459105453.png',  'index_slider', 1,  '首页轮播1',  'string'),
('index_slider2_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826471680590.jpg',  'index_slider', 1,  '首页轮播2',  'string'),
('index_slider3_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826478677750.jpg',  'index_slider', 1,  '首页轮播3',  'string'),
('index_slider4_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826482121676.png',  'index_slider', 1,  '首页轮播4',  'string'),
('index_slider5_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826488485484.jpg',  'index_slider', 1,  '首页轮播5',  'string'),
('is_modify', '1',  'domain', 1,  '', 'number'),
('join_live_link1', 'http://localhost/shop/yf_shop/index.php',  'join_slider',  1,  '入驻轮播url1', 'string'),
('join_live_link2', 'http://localhost/shop/yf_shop/index.php',  'join_slider',  1,  '入驻轮播url2', 'string'),
('join_slider1_image',  'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025199534626.png',  'join_slider',  1,  '入驻轮播1',  'string'),
('join_slider2_image',  'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025254850480.png',  'join_slider',  1,  '入驻轮播2',  'string'),
('join_tip',  'fdsafasdaddsadad', 'join_slider',  1,  '贴心提示', 'string'),
('jsd', 'JSD-', 'bill_format',  1,  '//结算单',  'string'),
('keyword', '网上超市，网上商城，网络购物，进口食品，美容护理，母婴玩具，厨房清洁用品，家用电器，手机数码，电脑软件办公用品，家居生活，服饰内衣，营养保健，钟表珠宝，饰品箱包，汽车生活，图书音像，礼品卡，药品，医疗器械，隐形眼镜等，1号店。',  'seo',  1,  '', 'string'),
('keywords',  '雷山兄弟扛年货回家，年货下单就到家',  'main', 1,  '', 'string'),
('kuaidi100_app_id',  'kuaidi100fadfda',  'kuaidi100',  1,  '', 'string'),
('kuaidi100_app_key', 'kuaidi100_statufaf', 'kuaidi100',  1,  '', 'string'),
('kuaidi100_status',  '1',  'kuaidi100',  1,  '', 'string'),
('kuaidiniao_app_key',  'kuaidiniaofafdaf', 'kuaidiniao', 1,  '', 'string'),
('kuaidiniao_express',  '[\"QFKD\",\"ZTO\",\"DBL\",\"ZENY\"]',  'kuaidiniao', 1,  '', 'json'),
('kuaidiniao_e_business_id',  'kuaidiniao_e_business_id', 'kuaidiniao', 1,  '', 'string'),
('kuaidiniao_status', '1',  'kuaidiniao', 1,  '', 'string'),
('language_id', 'zh_CN',  'site', 1,  '', 'string'),
('like',  '25,44,26,27,46', 'home', 1,  '', 'string'),
('list_catid',  '1',  'home', 1,  '', 'string'),
('live_link1',  'http://localhost/shop/yf_shop/index.php',  'slider', 1,  '轮播轮播', 'string'),
('live_link2',  'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', 1,  '轮播地址', 'string'),
('live_link3',  'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', 1,  '轮播地址', 'string'),
('live_link4',  'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', 1,  '轮播地址', 'string'),
('logistics_channel', 'kuaidi100',  'logistics',  1,  '', 'string'),
('logo',  '', 'main', 1,  '', 'string'),
('mlogo', '', 'main', 1,  '', 'string'),
('modify_mobile', 'faf',  'msg_tpl',  1,  '手机验证通知', 'string'),
('monetary_unit', '￥',  'site', 1,  '', 'string'),
('msg_tpl1',  '21212',  'msg_tpl',  1,  '212',  'string'),
('new_pro', '48,32,23,25,28', 'home', 1,  '', 'string'),
('openstatistics',  '1',  'main', 1,  '', 'string'),
('opensuburl',  '0',  'seo',  1,  '', 'string'),
('order_id_prefix_format',  'DD-',  'bill_format',  1,  '//自定义订单前缀',  'string'),
('owntel',  '021-64966875', 'main', 1,  '', 'string'),
('photo_font',  '\r\nArial,宋体,微软雅黑',  'photo',  1,  '水印字体', 'string'),
('photo_goods_logo',  'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217234113345.jpg!300x300.jpg',  'photo',  1,  '商品默认图片', 'string'),
('photo_shop_head_logo',  'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217274997950.jpg!180x80.jpg', 'photo',  1,  '店铺默认头像', 'string'),
('photo_shop_logo', 'http://yuanfeng.com/tech12/yf_shop/image.php/shop/data/upload/media/173597/47/image/20160714/1468475601861711.jpg',  'photo',  1,  '店铺默认标志', 'string'),
('photo_user_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469780228802155.jpg',  'photo',  1,  '会员默认头像', 'string'),
('Plugin_Cron', '0',  'plugin', 1,  '', 'string'),
('Plugin_Log',  '1',  'plugin', 1,  '', 'string'),
('Plugin_Perm', '1',  'plugin', 1,  '', 'string'),
('Plugin_Xhprof', '0',  'plugin', 1,  '', 'string'),
('pointprod_isuse', '1',  'promotion',  1,  '积分兑换是否开',  'number'),
('pointshop_isuse', '1',  'promotion',  1,  '积分中心是否开启', 'number'),
('points_avatar', '50', 'points', 1,  '', 'string'),
('points_checkin',  '5',  'points', 1,  '', 'string'),
('points_consume',  '100',  'points', 1,  '', 'string'),
('points_email',  '50', 'points', 1,  '', 'string'),
('points_evaluate', '21', 'points', 1,  '商品评论获取积分', 'string'),
('points_evaluate_good',  '50', 'points', 1,  '', 'string'),
('points_evaluate_image', '10', 'points', 1,  '', 'string'),
('points_login',  '15', 'points', 1,  '登陆获取积分', 'string'),
('points_mobile', '50', 'points', 1,  '', 'string'),
('points_order',  '800',  'points', 1,  '订单获取积分上限', 'string'),
('points_recharge', '100',  'points', 1,  '订单每多少获取多少积分',  'string'),
('points_reg',  '50', 'points', 1,  '注册获取积分', 'string'),
('point_description', '收到公司的',  'seo',  1,  '', 'string'),
('point_description_content', '特温特',  'seo',  1,  '', 'string'),
('point_keyword', ' nfbgnjgf',  'seo',  1,  '', 'string'),
('point_keyword_content', '热热', 'seo',  1,  '', 'string'),
('point_title', 'e{sitename}',  'seo',  1,  '', 'string'),
('point_title_content', 'g{sitename}',  'seo',  1,  '', 'string'),
('product_description', '商品', 'seo',  1,  '', 'string'),
('product_keyword', '商品', 'seo',  1,  '', 'string'),
('product_title', '商品{sitename}{name}', 'seo',  1,  '', 'string'),
('promotion_allow', '1',  'promotion',  1,  '促销活动是否开启', 'number'),
('promotion_discount_price',  '12', 'discount', 1,  '', 'number'),
('promotion_increase_price',  '20', 'increase', 1,  '', 'number'),
('promotion_mansong_price', '15', 'mansong',  1,  '', 'number'),
('promotion_voucher_buyertimes_limit',  '10', 'voucher',  1,  '', 'number'),
('promotion_voucher_price', '21', 'voucher',  1,  '', 'number'),
('promotion_voucher_storetimes_limit',  '2',  'voucher',  1,  '', 'number'),
('protection_service_status', '1',  'operation',  1,  '', 'string'),
('qanggou', '48', 'home', 1,  '', 'string'),
('qq_app_id', '1',  'connect',  1,  '', 'string'),
('qq_app_key',  '1',  'connect',  1,  '', 'string'),
('qq_status', '1',  'connect',  1,  '', 'string'),
('regname', 'register.php', 'main', 1,  '', 'string'),
('reset_pwd', 'faf',  'msg_tpl',  1,  '重置密码通知', 'string'),
('retain_domain', 'www',  'domain', 1,  '', 'string'),
('rewrite', '0',  'seo',  1,  '', 'string'),
('search_words',  '茶杯,衣服,美食,电脑,电视,12,67,76,99', 'search', 1,  '搜索词',  'string'),
('send_chain_code', 'faf',  'msg_tpl',  1,  '门店提货通知', 'string'),
('send_pickup_code',  'faf',  'msg_tpl',  1,  '自提通知', 'string'),
('send_vr_code',  'faf',  'msg_tpl',  1,  '虚拟兑换码通知',  'string'),
('service_station_status',  '0',  'operation',  1,  '', 'string'),
('setting_buyer_logo',  'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470214463395892.jpg!150x40.jpg', 'setting',  1,  '', 'string'),
('setting_email', '552786543@qq.com', 'setting',  1,  '', 'string'),
('setting_logo',  'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217293114679.jpg!240x60.jpg', 'setting',  1,  '', 'string'),
('setting_phone', '021-888888,021-112121',  'setting',  1,  '', 'string'),
('setting_seller_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160802/1470128931804909.jpg',  'setting',  1,  '', 'string'),
('shop_description',  '店铺', 'seo',  1,  '', 'string'),
('shop_domain', '1',  'domain', 1,  '', 'string'),
('shop_is_open',  '1',  'main', 1,  '', 'string'),
('shop_keyword',  '店铺', 'seo',  1,  '', 'string'),
('shop_title',  '店铺{shopname}{sitename}', 'seo',  1,  '', 'string'),
('site_logo', 'http://ucenter.yuanfeng021.com/image.php/ucenter/data/upload/media/plantform/image/20160825/1472092645887800.jpg!240x60.jpg',  'site', 1,  '', 'string'),
('site_name', 'imbuilder',  'site', 1,  '', 'string'),
('site_status', '1',  'site', 1,  '', 'number'),
('slider1_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779648351956.jpg', 'slider', 1,  '团购轮播1',  'string'),
('slider2_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779744758951.jpg', 'slider', 1,  '团购轮播2',  'string'),
('slider3_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779757819368.jpg', 'slider', 1,  '团购轮播3',  'string'),
('slider4_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779795128665.jpg', 'slider', 1,  '团购轮播4',  'string'),
('slogo', '', 'main', 1,  '', 'string'),
('sms_account', 'yf_shop',  'sms',  1,  '', 'string'),
('sms_pass',  'yf_shop',  'sms',  1,  '', 'string'),
('sns_description', 'sns',  'seo',  1,  '', 'string'),
('sns_keyword', 'sns{name}',  'seo',  1,  '', 'string'),
('sns_title', 'sns{sitename}',  'seo',  1,  '', 'string'),
('sphinx_search_host',  '111123213',  'sphinx', 1,  '', 'string'),
('sphinx_search_port',  '121212', 'sphinx', 1,  '', 'string'),
('sphinx_statu',  '1',  'sphinx', 1,  '', 'string'),
('statistics_code', '第三方流量统计代码78',  'site', 1,  '', 'string'),
('stat_is_open',  'fwefe',  'main', 1,  '', 'string'),
('tg_description',  '团购', 'seo',  1,  '', 'string'),
('tg_description_content',  '团购', 'seo',  1,  '', 'string'),
('tg_keyword',  '团购', 'seo',  1,  '', 'string'),
('tg_keyword_content',  '团购', 'seo',  1,  '', 'string'),
('tg_title',  '{sitename}-团购-{name}1',  'seo',  1,  '', 'string'),
('tg_title_content',  '{sitename}-团购{name}',  'seo',  1,  '', 'string'),
('theme_id',  'default',  'site', 1,  '', 'string'),
('time_format', 'H:i:s',  'site', 1,  '', 'string'),
('time_zone_id',  'Asia/Shanghai',  'site', 1,  '', 'number'),
('title', 'BBCbuilder1331{sitename}', 'seo',  1,  '', 'string'),
('user_default_avatar', 'http://ucenter.yuanfeng021.com/image.php/ucenter/data/upload/media/plantform/image/20160825/1472116715774854.jpeg!120x120.jpg',  'site', 1,  '', 'string'),
('voucher_allow', '1',  'promotion',  1,  '代金券功能是否开启',  'number'),
('weburl',  'http://demo.bbc-builder.com',  'main', 1,  '', 'string'),
('weibo_app_id',  '1',  'connect',  1,  '', 'string'),
('weibo_app_key', '1',  'connect',  1,  '', 'string'),
('weibo_status',  '1',  'connect',  1,  '', 'string'),
('weixin_app_id', '1',  'connect',  1,  '', 'string'),
('weixin_app_key',  '1',  'connect',  1,  '', 'string'),
('weixin_status', '1',  'connect',  1,  '', 'string');

-- 2017-04-19 02:31:00