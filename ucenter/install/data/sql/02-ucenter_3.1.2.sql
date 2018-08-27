
DROP TABLE IF EXISTS `ucenter_base_app_licence`;
CREATE TABLE `ucenter_base_app_licence` (
  `licence_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '服务器id',
  `licence_key` varchar(5000) NOT NULL DEFAULT '' COMMENT '授权码',
  `licence_domain` varchar(255) NOT NULL DEFAULT '' COMMENT '允许的域名'',''分割',
  `licence_price` varchar(255) NOT NULL COMMENT '费用',
  `licence_effective_startdate` date NOT NULL COMMENT '有效期开始与结束',
  `licence_effective_enddate` date NOT NULL COMMENT '有效期开始与结束1',
  `app_id` smallint(6) NOT NULL COMMENT '所属游戏id',
  `company_name` varchar(255) NOT NULL COMMENT '公司名称',
  `company_phone` varchar(255) NOT NULL COMMENT '电话',
  `contacter` varchar(255) NOT NULL COMMENT '联系人',
  `sign_time` datetime NOT NULL COMMENT '签约时间',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员账号',
  `business_agent` varchar(255) NOT NULL COMMENT '业务代表',
  PRIMARY KEY (`licence_id`),
  KEY `game_id` (`app_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='licence表';


DROP TABLE IF EXISTS `ucenter_base_app_licence_domain`;
CREATE TABLE `ucenter_base_app_licence_domain` (
  `licence_domain` varchar(255) NOT NULL DEFAULT '' COMMENT '域名',
  `licence_domain_check_num` int(10) NOT NULL DEFAULT '1' COMMENT '检测次数',
  `licence_domain_check_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '首次检测时间',
  PRIMARY KEY (`licence_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='licence检测记录表';


DROP TABLE IF EXISTS `ucenter_base_app_licence_log`;
CREATE TABLE `ucenter_base_app_licence_log` (
  `licence_log_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT COMMENT '服务器id',
  `licence_key` varchar(5000) NOT NULL DEFAULT '' COMMENT '授权码',
  `licence_log_domain` varchar(5000) NOT NULL DEFAULT '' COMMENT '域名',
  `app_id` smallint(6) NOT NULL DEFAULT '0' COMMENT 'id',
  `licence_log_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '有效期开始与结束1',
  `licence_log_state` tinyint(4) NOT NULL,
  PRIMARY KEY (`licence_log_id`),
  KEY `game_id` (`app_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='licence检测记录表';


INSERT INTO `ucenter_message_template` (`id`, `code`, `name`, `title`, `content_email`, `type`, `is_phone`, `is_email`, `is_mail`, `content_mail`, `content_phone`, `force_phone`, `force_email`, `force_mail`, `mold`) VALUES ('12', 'unbind', '互联解绑', '尊敬的客户，您正在操作远丰商城账号解除绑定，\r\n\r\n如不是您本人操作，请注意账户安全，及时联系[weburl_name]客服。', '尊敬的客户，您正在操作远丰商城账号解除绑定，\r\n\r\n如不是您本人操作，请注意账户安全，及时联系[weburl_name]客服。', '1', '1', '0', '0', '尊敬的客户，您正在操作远丰商城账号解除绑定，\r\n\r\n如不是您本人操作，请注意账户安全，及时联系[weburl_name]客服。', '尊敬的客户，您正在操作远丰商城账号解除绑定，\r\n\r\n如不是您本人操作，请注意账户安全，及时联系[weburl_name]客服。', '0', '0', '0', '0');

