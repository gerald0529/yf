
SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `pay_admin_base_protocol`
-- ----------------------------
DROP TABLE IF EXISTS `pay_admin_base_protocol`;
CREATE TABLE `pay_admin_base_protocol` (
  `protocol_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '协议索引Id',
  `cmd_id` smallint(4) NOT NULL DEFAULT '0' COMMENT '协议Id',
  `ctl` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器类名称',
  `met` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器方法',
  `db` enum('master','slave') NOT NULL DEFAULT 'master' COMMENT '连接数据库类型',
  `typ` enum('e','json','msgpcak','amf') NOT NULL DEFAULT 'json' COMMENT '输出数据默认类型',
  `rights_id` mediumint(20) NOT NULL COMMENT '权限Id',
  `log` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否记录日志',
  `struct` varchar(100) NOT NULL DEFAULT '' COMMENT '生成结构体，独立使用',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '注释',
  `0` varchar(255) NOT NULL DEFAULT 'int16_t:cmd_id' COMMENT '第一个字段',
  `1` varchar(255) NOT NULL DEFAULT '',
  `2` varchar(255) NOT NULL DEFAULT '',
  `3` varchar(255) NOT NULL DEFAULT '',
  `4` varchar(255) NOT NULL DEFAULT '',
  `5` varchar(255) NOT NULL DEFAULT '',
  `6` varchar(255) NOT NULL DEFAULT '',
  `7` varchar(255) NOT NULL DEFAULT '',
  `8` varchar(255) NOT NULL DEFAULT '',
  `9` varchar(255) NOT NULL DEFAULT '',
  `10` varchar(255) NOT NULL DEFAULT '',
  `11` varchar(255) NOT NULL DEFAULT '',
  `12` varchar(255) NOT NULL DEFAULT '',
  `13` varchar(255) NOT NULL DEFAULT '',
  `14` varchar(255) NOT NULL DEFAULT '',
  `15` varchar(255) NOT NULL DEFAULT '',
  `16` varchar(255) NOT NULL DEFAULT '',
  `17` varchar(255) NOT NULL DEFAULT '',
  `18` varchar(255) NOT NULL DEFAULT '',
  `19` varchar(255) NOT NULL DEFAULT '',
  `20` varchar(255) NOT NULL DEFAULT '',
  `21` varchar(255) NOT NULL DEFAULT '',
  `22` varchar(255) NOT NULL DEFAULT '',
  `23` varchar(255) NOT NULL DEFAULT '',
  `24` varchar(255) NOT NULL DEFAULT '',
  `25` varchar(255) NOT NULL DEFAULT '',
  `26` varchar(255) NOT NULL DEFAULT '',
  `27` varchar(255) NOT NULL DEFAULT '',
  `28` varchar(255) NOT NULL DEFAULT '',
  `29` varchar(255) NOT NULL DEFAULT '',
  `30` varchar(255) NOT NULL DEFAULT '',
  `31` varchar(255) NOT NULL DEFAULT '',
  `32` varchar(255) NOT NULL DEFAULT '',
  `33` varchar(255) NOT NULL DEFAULT '',
  `34` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`protocol_id`),
  UNIQUE KEY `cmd_id_key` (`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='基础通信协议表';

-- ----------------------------
--  Table structure for `pay_admin_log_action`
-- ----------------------------
DROP TABLE IF EXISTS `pay_admin_log_action`;
CREATE TABLE `pay_admin_log_action` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `user_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '玩家Id',
  `user_account` varchar(100) NOT NULL DEFAULT '' COMMENT '角色账户',
  `user_name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `action_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '行为id == protocal_id -> rights_id',
  `log_param` text NOT NULL COMMENT '请求的参数',
  `log_ip` varchar(20) NOT NULL DEFAULT '',
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录时间',
  PRIMARY KEY (`log_id`),
  KEY `player_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户行为日志表';

-- ----------------------------
--  Table structure for `pay_admin_rights_group`
-- ----------------------------
DROP TABLE IF EXISTS `pay_admin_rights_group`;
CREATE TABLE `pay_admin_rights_group` (
  `rights_group_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组id',
  `rights_group_name` varchar(50) NOT NULL COMMENT '权限组名称',
  `rights_group_rights_ids` text NOT NULL COMMENT '权限列表',
  `rights_group_add_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rights_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限组表';

-- ----------------------------
--  Records of `pay_admin_rights_group`
-- ----------------------------
BEGIN;
INSERT INTO `pay_admin_rights_group` VALUES ('1', '系统管理员', '[3180,3190,34058,34065,34062,34061,11000,4900,34092,6450,5350,32666,7100,7200,5800,7500,7350,7810,7730,34303,11510,33140,33130,33150,33160,34020,34022,3200]', '2013');
COMMIT;

-- ----------------------------
--  Table structure for `pay_admin_user_base`
-- ----------------------------
DROP TABLE IF EXISTS `pay_admin_user_base`;
CREATE TABLE `pay_admin_user_base` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_account` varchar(50) NOT NULL COMMENT '用户帐号',
  `user_password` char(32) NOT NULL COMMENT '用户密码',
  `user_key` varchar(32) NOT NULL COMMENT '用户key',
  `user_realname` varchar(32) NOT NULL COMMENT '用户真实姓名',
  `user_nickname` varchar(30) NOT NULL COMMENT '用户昵称',
  `user_mobile` varchar(20) NOT NULL COMMENT '用户手机号',
  `user_email` varchar(100) NOT NULL COMMENT '用户密码',
  `rights_group_id` smallint(4) NOT NULL COMMENT '用户权限组id',
  `user_rights_ids` text NOT NULL COMMENT '用户权限',
  `user_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被封禁 0:未被封禁 1:被封禁',
  `user_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员 0:不是',
  `server_id` mediumint(8) NOT NULL COMMENT '服务id-公司关联-关联数据库-key中',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10002 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `pay_admin_user_base`
-- ----------------------------
BEGIN;
INSERT INTO `pay_admin_user_base` VALUES ('10001', 'admin', '', '', '', '', '', '', '1', '', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `pay_admin_web_config`
-- ----------------------------
DROP TABLE IF EXISTS `pay_admin_web_config`;
CREATE TABLE `pay_admin_web_config` (
  `config_key` varchar(50) NOT NULL COMMENT '数组下标',
  `config_value` text NOT NULL COMMENT '数组值',
  `config_type` varchar(50) NOT NULL,
  `config_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `config_comment` text NOT NULL,
  `config_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`config_key`),
  KEY `index` (`config_key`,`config_type`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站配置表';

-- ----------------------------
--  Records of `pay_admin_web_config`
-- ----------------------------
BEGIN;
INSERT INTO `pay_admin_web_config` VALUES ('current_db_version', '37965', 'site', '1', '', 'string'), ('current_version', '1.0.1', 'site', '1', '', 'string'), ('required_mysql_version', '5.0', 'site', '1', '', 'string'), ('required_php_version', '5.3', 'site', '1', '', 'string');
COMMIT;


SET FOREIGN_KEY_CHECKS = 1;
