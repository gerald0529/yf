

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `pay_card_base`
-- ----------------------------
DROP TABLE IF EXISTS `pay_card_base`;
CREATE TABLE `pay_card_base` (
  `card_id` smallint(6) NOT NULL COMMENT '卡的id',
  `app_id` int(11) NOT NULL DEFAULT '9999' COMMENT 'app id  ： 9999 通用',
  `card_name` varchar(100) NOT NULL COMMENT '卡名称',
  `card_prize` varchar(2000) NOT NULL COMMENT '卡片里面的奖品',
  `card_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '卡片描述',
  `card_start_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '卡的有效开始时间',
  `card_end_time` date NOT NULL DEFAULT '0000-00-00' COMMENT '卡的有效结束时间',
  `card_image` varchar(255) NOT NULL DEFAULT '' COMMENT '卡片图片网址',
  `card_num` int(255) NOT NULL DEFAULT '0' COMMENT '卡片数量',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卡片基础信息表';

-- ----------------------------
--  Table structure for `pay_card_info`
-- ----------------------------
DROP TABLE IF EXISTS `pay_card_info`;
CREATE TABLE `pay_card_info` (
  `card_code` varchar(50) NOT NULL COMMENT '卡片激活码',
  `card_password` varchar(20) NOT NULL DEFAULT '' COMMENT '卡片密码',
  `card_id` smallint(6) NOT NULL COMMENT '卡片id',
  `card_fetch_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '领奖时间',
  `card_media_id` smallint(6) NOT NULL COMMENT '媒体id,参照base_card_media表',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '领卡人的服务器id',
  `user_id` int(10) NOT NULL,
  `user_account` varchar(100) NOT NULL DEFAULT '' COMMENT '领卡人账号',
  `card_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '卡牌生成时间',
  `card_money` decimal(10,2) NOT NULL COMMENT '充值卡余额',
  `card_froze_money` decimal(10,2) NOT NULL COMMENT '卡冻结金额',
  PRIMARY KEY (`card_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卡片信息表';

-- ----------------------------
--  Table structure for `pay_card_media`
-- ----------------------------
DROP TABLE IF EXISTS `pay_card_media`;
CREATE TABLE `pay_card_media` (
  `card_media_id` mediumint(9) NOT NULL COMMENT '卡片的媒体id',
  `card_media_name` varchar(50) NOT NULL COMMENT '卡片的媒体标题',
  PRIMARY KEY (`card_media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卡片渠道表';

-- ----------------------------
--  Table structure for `pay_consume_deposit`
-- ----------------------------
DROP TABLE IF EXISTS `pay_consume_deposit`;
CREATE TABLE `pay_consume_deposit` (
  `deposit_trade_no` varchar(64) NOT NULL DEFAULT '' COMMENT '交易号',
  `deposit_buyer_id` varchar(30) NOT NULL DEFAULT '' COMMENT '用户号',
  `deposit_total_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易金额',
  `deposit_pay_channel` varchar(100) NOT NULL COMMENT '充值的付款方式',
  `deposit_gmt_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '交易创建时间',
  `deposit_gmt_payment` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '交易付款时间',
  `deposit_gmt_close` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deposit_trade_status` varchar(100) NOT NULL DEFAULT '' COMMENT '交易状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='充值表-支付回调callback使用-确认付款';

-- ----------------------------
--  Table structure for `pay_consume_record`
-- ----------------------------
DROP TABLE IF EXISTS `pay_consume_record`;
CREATE TABLE `pay_consume_record` (
  `consume_record_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '交易记录id',
  `order_id` varchar(100) NOT NULL COMMENT '商户订单id',
  `user_id` int(10) unsigned NOT NULL COMMENT '所属用id',
  `user_nickname` varchar(50) NOT NULL COMMENT '昵称',
  `record_money` decimal(10,2) NOT NULL COMMENT '金额',
  `record_date` date NOT NULL COMMENT '年-月-日',
  `record_year` smallint(4) NOT NULL COMMENT '年',
  `record_month` tinyint(2) NOT NULL COMMENT '月',
  `record_day` tinyint(2) NOT NULL COMMENT '日',
  `record_title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `record_desc` varchar(255) NOT NULL COMMENT '描述',
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `trade_type_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易类型',
  `user_type` tinyint(1) NOT NULL COMMENT '1-收款方 2-付款方',
  `record_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '付款状态',
  `record_paytime` datetime NOT NULL COMMENT '支付时间',
  `record_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未删除 1删除',
  PRIMARY KEY (`consume_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='交易明细表-账户收支明细-资金流水表-账户金额变化流水';

-- ----------------------------
--  Table structure for `pay_consume_trade`
-- ----------------------------
DROP TABLE IF EXISTS `pay_consume_trade`;
CREATE TABLE `pay_consume_trade` (
  `consume_trade_id` varchar(100) NOT NULL COMMENT '交易订单id',
  `order_id` varchar(100) NOT NULL COMMENT '商户订单id',
  `buyer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买家id',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '卖家id',
  `order_state_id` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单状态',
  `trade_type_id` tinyint(1) NOT NULL COMMENT '交易类型',
  `payment_channel_id` tinyint(4) NOT NULL COMMENT '支付渠道',
  `app_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单来源',
  `server_id` int(10) unsigned NOT NULL COMMENT '服务器id',
  `trade_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:担保交易  2：直接交易',
  `order_payment_amount` decimal(10,2) NOT NULL COMMENT '总付款额度 = trade_payment_amount + trade_payment_money + trade_payment_recharge_card + trade_payment_points',
  `trade_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额，在线支付金额',
  `trade_payment_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额支付',
  `trade_payment_recharge_card` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值卡余额支付',
  `trade_payment_points` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分支付',
  `trade_discount` decimal(10,2) NOT NULL COMMENT '折扣优惠',
  `trade_commis_amount` decimal(10,2) NOT NULL COMMENT '佣金',
  `trade_refund_amount` decimal(10,2) NOT NULL COMMENT '订单退款',
  `trade_amount` decimal(10,2) NOT NULL COMMENT '总额虚拟的 = trade_order_amount + trade_discount',
  `trade_date` date NOT NULL COMMENT '年-月-日',
  `trade_year` smallint(4) NOT NULL COMMENT '年',
  `trade_month` tinyint(2) NOT NULL COMMENT '月',
  `trade_day` tinyint(2) NOT NULL COMMENT '日',
  `trade_title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `trade_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `trade_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `trade_create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `trade_pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '付款时间',
  `trade_finish_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `trade_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`consume_trade_id`),
  KEY `company_id` (`payment_channel_id`,`server_id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='交易订单表-强调唯一订单-充值则先创建充值订单';

-- ----------------------------
--  Table structure for `pay_consume_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `pay_consume_withdraw`;
CREATE TABLE `pay_consume_withdraw` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pay_uid` int(8) NOT NULL COMMENT '会员支付ID',
  `orderid` varchar(50) DEFAULT NULL COMMENT '交易明细ID',
  `amount` decimal(10,2) NOT NULL COMMENT '总数',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `censor` varchar(50) DEFAULT NULL COMMENT '管理员',
  `check_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `is_succeed` tinyint(2) DEFAULT '0' COMMENT '是否成功',
  `bankflow` varchar(50) DEFAULT NULL COMMENT '银行流水账号',
  `con` text COMMENT '描述',
  `bank` varchar(50) DEFAULT NULL COMMENT '银行',
  `cardno` varchar(32) DEFAULT NULL,
  `cardname` varchar(50) DEFAULT NULL,
  `supportTime` int(6) DEFAULT '0',
  `fee` float(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='提现申请表';

-- ----------------------------
--  Table structure for `pay_message_template`
-- ----------------------------
DROP TABLE IF EXISTS `pay_message_template`;
CREATE TABLE `pay_message_template` (
  `id` int(10) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '主题',
  `content_email` text NOT NULL COMMENT '邮件内容',
  `type` tinyint(1) NOT NULL COMMENT '0商家 1用户',
  `is_phone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `is_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `is_mail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭 1开启',
  `content_mail` text NOT NULL COMMENT '站内信内容',
  `content_phone` text NOT NULL COMMENT '短信内容',
  `force_phone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机短信0不强制1强制',
  `force_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮件0不强制1强制',
  `force_mail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '站内信0不强制1强制',
  `mold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0常用提示 1订单提示 2卡券提示 3售后提示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `pay_message_template`
-- ----------------------------
BEGIN;
INSERT INTO `pay_message_template` VALUES ('2', 'verification', '绑定验证', '请激活您在[weburl_name]账户', '您绑定邮箱在[weburl_name]账户,验证码为[yzm]', '1', '1', '1', '1', '您在[weburl_name]账户上正进行绑定,验证码为[yzm]。', '您正绑定手机在[weburl_name]账户上,验证码为[yzm]。', '1', '0', '0', '0'), ('3', 'Complaints_of_goods', '商品被投诉', '[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。', '您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。', '2', '1', '1', '1', '您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。', '您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。', '0', '0', '0', '3'), ('4', 'Voucher', '优惠券到账', '优惠券到账', '恭喜您获得[name]优惠券，记得在[end]前使用哦~', '1', '1', '1', '1', '恭喜您获得[name]优惠券，记得在[end]前使用哦~', '恭喜您获得[name]优惠券，记得在[end]前使用哦~', '0', '0', '1', '2'), ('5', 'place_your_order', '下单通知', '下单通知', '您的会员在[date]提交了订单[order_id]，请尽快发货。', '2', '1', '0', '1', '您的会员在[date]提交了订单[order_id]，请尽快发货。', '您的会员在[date]提交了订单[order_id]，请尽快发货。', '1', '1', '1', '0'), ('6', 'ordor_complete_shipping', '发货通知', '发货通知', '您的订单[order_id]于[date]时,已发货啦~', '1', '1', '0', '1', '您的订单[order_id]于[date]时,已发货啦~', '您的订单[order_id]于[date]时,已发货啦~', '1', '1', '0', '1'), ('10', 'welcome', '欢迎信息', '感谢您注册[weburl_name]', '感谢您注册[weburl_name]，欢迎您。', '1', '1', '0', '1', '感谢您注册[weburl_name]，欢迎您。', '感谢您注册[weburl_name]，欢迎您。', '1', '1', '0', '0'), ('11', 'Lift verification', '解除验证', '您在[weburl_name]账户进行解除绑定', '您正在[weburl_name]账户上进行解除绑定操作,验证码为[yzm]。', '0', '0', '0', '0', '您在[weburl_name]账户上正进行解除绑定,验证码为[yzm]。', '您正在[weburl_name]账户上进行解除绑定操作,验证码为[yzm]。', '0', '0', '0', '0'), ('12', 'getcode', '获取验证码', '您在[weburl_name]账户进行操作', '您在[weburl_name]上获取的验证码是：[yzm]。请不要把验证码泄露给其他人。', '0', '0', '0', '0', '您在[weburl_name]上获取的验证码是：[yzm]。请不要把验证码泄露给其他人。', '您在[weburl_name]上获取的验证码是：[yzm]。请不要把验证码泄露给其他人。', '0', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `pay_order_state`
-- ----------------------------
DROP TABLE IF EXISTS `pay_order_state`;
CREATE TABLE `pay_order_state` (
  `order_state_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '状态id',
  `order_state_name` varchar(50) NOT NULL COMMENT '订单状态',
  `order_state_text_1` varchar(255) NOT NULL,
  `order_state_text_2` varchar(255) NOT NULL,
  `order_state_text_3` varchar(255) NOT NULL,
  `order_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`order_state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消费状态表';

-- ----------------------------
--  Records of `pay_order_state`
-- ----------------------------
BEGIN;
INSERT INTO `pay_order_state` VALUES ('1', 'ORDER_WAIT_PAY', '待付款', '等待买家付款', '下单', ''), ('2', 'ORDER_PAYED', '待配货', '等待卖家配货', '付款', '//如果不启用配货状态，则将状态改成ORDER_WAIT_PREPARE_GOODS'), ('3', 'ORDER_WAIT_PREPARE_GOODS', '待发货', '等待卖家发货', '配货', '//是否启用？-支付完成~快递出库之间'), ('4', 'ORDER_WAIT_CONFIRM_GOODS', '已发货', '等待买家确认收货', '出库', ''), ('5', 'ORDER_RECEIVED', '已签收', '买家已签收', '已签收', '//买家已签收,货到付款专用'), ('6', 'ORDER_FINISH', '已完成', '交易成功', '交易成功', '//success  fail  mr'), ('7', 'ORDER_CANCEL', '已取消', '交易关闭', '交易关闭', '//付款以后用户退款成功，交易自动关闭');
COMMIT;

-- ----------------------------
--  Table structure for `pay_payment_channel`
-- ----------------------------
DROP TABLE IF EXISTS `pay_payment_channel`;
CREATE TABLE `pay_payment_channel` (
  `payment_channel_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `payment_channel_code` varchar(20) NOT NULL DEFAULT '' COMMENT '代码名称',
  `payment_channel_name` varchar(100) NOT NULL DEFAULT '' COMMENT '支付名称',
  `payment_channel_image` varchar(255) NOT NULL COMMENT '支付方式图片',
  `payment_channel_config` text NOT NULL COMMENT '支付接口配置信息',
  `payment_channel_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '接口状态',
  `payment_channel_allow` enum('pc','wap','both') NOT NULL DEFAULT 'pc' COMMENT '类型',
  `payment_channel_wechat` tinyint(4) NOT NULL DEFAULT '1' COMMENT '微信中是否可以使用',
  `payment_channel_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`payment_channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='支付渠道表';

 

-- ----------------------------
--  Table structure for `pay_record_state`
-- ----------------------------
DROP TABLE IF EXISTS `pay_record_state`;
CREATE TABLE `pay_record_state` (
  `record_state_id` tinyint(4) NOT NULL COMMENT '状态id',
  `record_state_name` varchar(50) NOT NULL COMMENT '交易状态',
  `record_state_text_1` varchar(255) NOT NULL,
  `record_state_text_2` varchar(255) NOT NULL,
  `record_state_text_3` varchar(255) NOT NULL,
  `record_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`record_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `pay_record_state`
-- ----------------------------
BEGIN;
INSERT INTO `pay_record_state` VALUES ('1', '处理中', '', '', '', ''), ('2', '交易完成', '', '', '', ''), ('3', '交易取消', '', '', '', ''), ('4', '交易失败', '', '', '', ''),('5','待发货','','','',''),('6','待收货','','','','');
COMMIT;

-- ----------------------------
--  Table structure for `pay_service_fee`
-- ----------------------------
DROP TABLE IF EXISTS `pay_service_fee`;
CREATE TABLE `pay_service_fee` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `fee_rates` float(12,2) DEFAULT '0.00',
  `fee_min` int(2) DEFAULT '0',
  `fee_max` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='服务费用';

-- ----------------------------
--  Records of `pay_service_fee`
-- ----------------------------
BEGIN;
INSERT INTO `pay_service_fee` VALUES ('1', '2小时内到账', '0.20', '2', '25'), ('2', '次日24点前到账', '0.15', '2', '25'), ('4', '次日48点前到账', '0.05', '2', '25');
COMMIT;

-- ----------------------------
--  Table structure for `pay_trade_mode`
-- ----------------------------
DROP TABLE IF EXISTS `pay_trade_mode`;
CREATE TABLE `pay_trade_mode` (
  `trade_mode_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '交易方式id',
  `trade_mode_name` varchar(50) NOT NULL COMMENT '交易方式名称',
  `trade_mode_text` varchar(20) NOT NULL DEFAULT '' COMMENT '交易方式名称',
  `trade_mode_partner` varchar(20) NOT NULL COMMENT '商户号',
  `trade_mode_key` varchar(50) NOT NULL COMMENT '密钥',
  `trade_mode_sign` varchar(50) NOT NULL COMMENT '校验码',
  `trade_mode_image` varchar(255) NOT NULL COMMENT '支付方式图片',
  `trade_mode_remark` varchar(200) NOT NULL COMMENT '交易方式备注',
  PRIMARY KEY (`trade_mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='交易方式表-担保交易';

-- ----------------------------
--  Records of `pay_trade_mode`
-- ----------------------------
BEGIN;
INSERT INTO `pay_trade_mode` VALUES ('1', 'secured_trade', '担保交易', '', '', '', '', ''), ('2', 'TRANSFER', '转账', '', '', '', '', ''), ('3', 'DEPOSIT', '充值', '', '', '', '', ''), ('4', 'WITHDRAW', '提现', '', '', '', '', '');
COMMIT;

-- ----------------------------
--  Table structure for `pay_trade_type`
-- ----------------------------
DROP TABLE IF EXISTS `pay_trade_type`;
CREATE TABLE `pay_trade_type` (
  `trade_type_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '状态id',
  `trade_type_name` varchar(50) NOT NULL COMMENT '订单状态',
  `trade_type_text` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `trade_type_remark` varchar(200) NOT NULL COMMENT '备注',
  PRIMARY KEY (`trade_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='交易类型表';

-- ----------------------------
--  Records of `pay_trade_type`
-- ----------------------------
BEGIN;
INSERT INTO `pay_trade_type` VALUES ('1', 'SHOPPING', '购物', ''), ('2', 'TRANSFER', '转账', ''), ('3', 'DEPOSIT', '充值', ''), ('4', 'WITHDRAW', '提现', '');
COMMIT;

-- ----------------------------
--  Table structure for `pay_union_order`
-- ----------------------------
DROP TABLE IF EXISTS `pay_union_order`;
CREATE TABLE `pay_union_order` (
  `union_order_id` varchar(20) NOT NULL COMMENT '编号',
  `inorder` varchar(255) NOT NULL COMMENT '合并订单编号',
  `trade_title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称 - 标题',
  `trade_payment_amount` float(10,2) NOT NULL COMMENT '总价格',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `buyer_id` int(12) NOT NULL COMMENT '买家ID',
  `trade_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `order_state_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态 1- 待付款状态',
  `pay_time` datetime NOT NULL COMMENT '支付时间',
  `payment_channel_id` tinyint(4) NOT NULL COMMENT '支付渠道',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单来源',
  `trade_type_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易类型',
  `union_cards_pay_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '购物卡支付金额',
  `union_cards_return_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '购物卡退款金额',
  `union_money_pay_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '预存款支付金额',
  `union_money_return_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '预存款退款金额',
  `union_online_pay_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '在线支付金额',
  PRIMARY KEY (`union_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='合并支付-单一支付也从此通道';

-- ----------------------------
--  Table structure for `pay_user_app`
-- ----------------------------
DROP TABLE IF EXISTS `pay_user_app`;
CREATE TABLE `pay_user_app` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '服务ID',
  `app_name` varchar(40) NOT NULL DEFAULT '' COMMENT '服务名称',
  `app_key` varchar(50) NOT NULL DEFAULT '' COMMENT '服务密钥',
  `app_url` varchar(255) NOT NULL DEFAULT '' COMMENT '服务网址',
  `app_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态  1：启用  0：禁用',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id-平台id，平台结算最后映射到这个用户账户中-platform_id，server_id, platform_user_id',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='应用配置表';

-- ----------------------------
--  Records of `pay_user_app`
-- ----------------------------
BEGIN;
INSERT INTO `pay_user_app` VALUES ('101', 'YuanfengERPfffdafdasf', 'aaaaaabbb', 'http://www.yuanfengerp.com/index.php', '1', '0'), ('102', 'ShopBuilder', 'aaaaaabbb', 'http://shop.bbc-builder.com/index.php', '1', '0'), ('103', 'ImBuilder', 'aaaaaabbb', '', '1', '0'), ('104', 'UCenter', 'aaaaaabbb', '', '1', '0'), ('105', 'PayCenter', 'aaaaaabbb', 'http://paycenter.yuanfeng021.com/index.php', '1', '0');
COMMIT;

-- ----------------------------
--  Table structure for `pay_user_base`
-- ----------------------------
DROP TABLE IF EXISTS `pay_user_base`;
CREATE TABLE `pay_user_base` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_number` varchar(50) NOT NULL DEFAULT '' COMMENT '用户编号',
  `user_account` varchar(50) NOT NULL DEFAULT '' COMMENT '用户帐号',
  `user_passwd` char(32) NOT NULL DEFAULT '' COMMENT '密码：使用用户中心-此处废弃',
  `user_pay_passwd` varchar(32) NOT NULL DEFAULT '' COMMENT '支付确认密码',
  `user_key` char(32) NOT NULL DEFAULT '' COMMENT '用户Key',
  `user_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被封禁，0：未封禁，1：封禁',
  `user_login_times` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '登录次数',
  `user_login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `user_login_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '登录ip',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_account` (`user_account`)
) ENGINE=InnoDB AUTO_INCREMENT=10002 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户基础信息表';

-- ----------------------------
--  Records of `pay_user_base`
-- ----------------------------
BEGIN;
INSERT INTO `pay_user_base` VALUES ('10001', '', 'admin', '', '', '', '0', '1', '0000-00-00 00:00:00', '');
COMMIT;

-- ----------------------------
--  Table structure for `pay_user_info`
-- ----------------------------
DROP TABLE IF EXISTS `pay_user_info`;
CREATE TABLE `pay_user_info` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `user_realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `user_nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_type_id` smallint(4) unsigned DEFAULT '0' COMMENT '用户类别',
  `user_level_id` smallint(4) unsigned DEFAULT '1' COMMENT '用户等级',
  `user_active_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '激活时间',
  `user_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注消息',
  `user_email` varchar(255) NOT NULL COMMENT '用户邮箱',
  `user_mobile` varchar(255) NOT NULL COMMENT '用户手机',
  `user_qq` varchar(255) NOT NULL DEFAULT '',
  `user_avatar` varchar(255) NOT NULL DEFAULT '',
  `user_identity_card` varchar(30) NOT NULL COMMENT '身份证号',
  `user_identity_statu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未审核1待审核2审核成功3审核失败',
  `user_identity_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '证件类型  1身份证 2护照 3军官证',
  `user_identity_font_logo` varchar(255) NOT NULL COMMENT '证件照反面',
  `user_identity_face_logo` varchar(255) NOT NULL COMMENT '证件照正面',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户信息表';

-- ----------------------------
--  Records of `pay_user_info`
-- ----------------------------
BEGIN;
INSERT INTO `pay_user_info` VALUES ('10001', '', '', '0', '1', '2016-09-08 13:54:30', '', '', '', '', '', '', '0', '1', '', '');
COMMIT;

-- ----------------------------
--  Table structure for `pay_user_resource`
-- ----------------------------
DROP TABLE IF EXISTS `pay_user_resource`;
CREATE TABLE `pay_user_resource` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `user_money_pending_settlement` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '待结算余额',
  `user_money` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户资金-预付款充值、转账、结算后的款项等等',
  `user_money_frozen` decimal(16,2) NOT NULL DEFAULT '0.00',
  `user_recharge_card` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '充值卡余额-只能从来购物',
  `user_recharge_card_frozen` decimal(16,2) NOT NULL DEFAULT '0.00',
  `user_points` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '积分',
  `user_points_frozen` decimal(16,2) NOT NULL DEFAULT '0.00',
  `user_credit` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '用户信用',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户资源表';

-- ----------------------------
--  Table structure for `pay_web_config`
-- ----------------------------
DROP TABLE IF EXISTS `pay_web_config`;
CREATE TABLE `pay_web_config` (
  `config_key` varchar(50) NOT NULL COMMENT '数组下标',
  `config_value` text NOT NULL COMMENT '数组值',
  `config_type` varchar(50) NOT NULL,
  `config_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `config_comment` text NOT NULL,
  `config_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`config_key`),
  KEY `index` (`config_key`,`config_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站配置表';

-- ----------------------------
--  Records of `pay_web_config`
-- ----------------------------
BEGIN;
INSERT INTO `pay_web_config` VALUES ('site', 'site_log', '1', '1', 'string', 'string'), ('23123', 'faf', 'msg_tpl', '1', 'fasf', 'string'), ('article_description', '5', 'seo', '1', '', 'string'), ('article_description_content', '5', 'seo', '1', '', 'string'), ('article_keyword', '软沙发', 'seo', '1', '', 'string'), ('article_keyword_content', '7', 'seo', '1', '', 'string'), ('article_title', '{sitename}-文章{name}', 'seo', '1', '', 'string'), ('article_title_content', '7{sitename}', 'seo', '1', '', 'string'), ('authenticate', 'faf', 'msg_tpl', '1', '身份验证通知', 'string'), ('baseurl', 'demo.bbc-builder.com', 'main', '1', '', 'string'), ('bind_email', 'faf', 'msg_tpl', '1', '邮箱验证通知', 'string'), ('body_skin', 'image/default/bg.jpg', 'main', '1', '', 'string'), ('brand_description', '2313', 'seo', '1', '', 'string'), ('brand_description_content', 'trsegt', 'seo', '1', '', 'string'), ('brand_keyword', '23123', 'seo', '1', '', 'string'), ('brand_keyword_content', '123123', 'seo', '1', '', 'string'), ('brand_title', 'j{sitename}23123', 'seo', '1', '', 'string'), ('brand_title_content', 'gfnjgmjn', 'seo', '1', '', 'string'), ('cacheTime', '1000', 'main', '1', '', 'string'), ('captcha_status_goodsqa', '1', 'dumps', '1', '', 'number'), ('captcha_status_login', '1', 'dump', '1', '', 'number'), ('captcha_status_register', '1', 'dump', '1', '', 'number'), ('category_description', '分类', 'seo', '1', '', 'string'), ('category_keyword', '分类', 'seo', '1', '', 'string'), ('category_title', '商品分类{name}{sitename}', 'seo', '1', '', 'string'), ('closecon', '', 'main', '1', '', 'string'), ('closed_reason', '11111', 'site', '1', '', 'string'), ('closetype', '0', 'main', '1', '', 'string'), ('complain_datetime', '2', 'complain', '1', '', 'string'), ('consult_header_text', '<p>11111111111111</p>', 'consult', '1', '', 'string'), ('copyright', 'BBCBuilder版权所有,正版购买地址:  <a href=\"http://www.bbc-builder.com\">http://www.bbc-builder.com</a>  \r\n<br />Powered by BBCbuilder V2.6.1\r\n', 'site', '1', '', 'string'), ('date_format', 'Y-m-d', 'site', '1', '', 'string'), ('description', '网上超市，最经济实惠的网上购物商城，用鼠标逛超市，不用排队，方便实惠送上门，网上购物新生活。', 'seo', '1', '', 'string'), ('domaincity', '0', 'main', '1', '', 'string'), ('domain_length', '3-12', 'domain', '1', '', 'string'), ('domain_modify_frequency', '1', 'domain', '1', '', 'number'), ('drp_is_open', '0', 'main', '1', '', 'string'), ('email', '250314853@qq.com', 'main', '1', '', 'string'), ('email_addr', 'rd02@yuanfeng021.com', 'email', '1', '', 'string'), ('email_host', 'smtp.exmail.qq.com', 'email', '1', '', 'string'), ('email_id', 'rd02', 'email', '1', '', 'string'), ('email_pass', 'huangxinze1', 'email', '1', '', 'string'), ('email_port', '465', 'email', '1', '', 'number'), ('enable_gzip', '0', 'main', '1', '', 'string'), ('enable_tranl', '1', 'main', '1', '', 'string'), ('fafaf', '身份验证通知', 'msg_tpl', '1', '【{$site_name}】您于{$send_time}提交账户安全验证，验证码是：{$verify_code}。', 'string'), ('goods_verify_flag', '1', 'goods', '1', '//商品是否需要审核', 'string'), ('grade_evaluate', '50', 'grade', '1', '订单评论获取成长值', 'number'), ('grade_login', '12', 'grade', '1', '登陆获取成长值', 'number'), ('grade_order', '800', 'grade', '1', '订单评论获取成长值上限', 'number'), ('grade_recharge', '100', 'grade', '1', '订单每多少获取多少成长值', 'number'), ('groupbuy_allow', '1', 'promotion', '1', '是否开启团购', 'number'), ('groupbuy_price', '100', 'groupbuy', '1', '', 'number'), ('groupbuy_review_day', '0', 'groupbuy', '1', '', 'number'), ('guest_comment', '1', 'dumps', '1', '', 'string'), ('hot_commen', '31,42,47,34,35,25,44,26,27,46', 'home', '1', '', 'string'), ('hot_sell', '42,37,28,41,30,31,42,47,34,35', 'home', '1', '', 'string'), ('icp_number', '5.4435234534253', 'site', '1', '', 'string'), ('image_allow_ext', 'gif,jpg,jpeg,bmp,png,swf', 'upload', '1', '图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 \",\" 隔开。', 'string'), ('image_max_filesize', '2000', 'upload', '1', '图片文件大小', 'number'), ('image_storage_type', '', 'upload', '1', '图片存放类型-程序内置较优方式', 'string'), ('index_catid', '1000,1002,1001,1003,1005', 'home', '1', '', 'string'), ('index_liandong1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/plantform/image/20160718/1468825145755207.png', 'index_liandong', '1', '首页联动小图1', 'string'), ('index_liandong2_image', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470221116664496.jpg!236x236.jpg', 'index_liandong', '1', '首页联动小图2', 'string'), ('index_liandong_url1', 'http：shouye.com1', 'index_liandong', '1', '首页联动小图url1', 'string'), ('index_liandong_url2', 'http://localhost/shop/yf_shop/index.php', 'index_liandong', '1', '首页联动小图url2', 'string'), ('index_live_link1', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url1', 'string'), ('index_live_link2', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url2', 'string'), ('index_live_link3', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url3', 'string'), ('index_live_link4', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url4', 'string'), ('index_live_link5', 'http://localhost/shop/yf_shop/index.php11', 'index_slider', '1', '首页轮播url5', 'string'), ('index_newsid', '1', 'home', '1', '', 'string'), ('index_slider1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826459105453.png', 'index_slider', '1', '首页轮播1', 'string'), ('index_slider2_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826471680590.jpg', 'index_slider', '1', '首页轮播2', 'string'), ('index_slider3_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826478677750.jpg', 'index_slider', '1', '首页轮播3', 'string'), ('index_slider4_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826482121676.png', 'index_slider', '1', '首页轮播4', 'string'), ('index_slider5_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826488485484.jpg', 'index_slider', '1', '首页轮播5', 'string'), ('is_modify', '1', 'domain', '1', '', 'number'), ('join_live_link1', 'http://localhost/shop/yf_shop/index.php', 'join_slider', '1', '入驻轮播url1', 'string'), ('join_live_link2', 'http://localhost/shop/yf_shop/index.php', 'join_slider', '1', '入驻轮播url2', 'string'), ('join_slider1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025199534626.png', 'join_slider', '1', '入驻轮播1', 'string'), ('join_slider2_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025254850480.png', 'join_slider', '1', '入驻轮播2', 'string'), ('join_tip', 'fdsafasdaddsadad', 'join_slider', '1', '贴心提示', 'string'), ('jsd', 'JSD-', 'bill_format', '1', '//结算单', 'string'), ('keyword', '网上超市，网上商城，网络购物，进口食品，美容护理，母婴玩具，厨房清洁用品，家用电器，手机数码，电脑软件办公用品，家居生活，服饰内衣，营养保健，钟表珠宝，饰品箱包，汽车生活，图书音像，礼品卡，药品，医疗器械，隐形眼镜等，1号店。', 'seo', '1', '', 'string'), ('keywords', '雷山兄弟扛年货回家，年货下单就到家', 'main', '1', '', 'string'), ('kuaidi100_app_id', 'kuaidi100fadfda', 'kuaidi100', '1', '', 'string'), ('kuaidi100_app_key', 'kuaidi100_statufaf', 'kuaidi100', '1', '', 'string'), ('kuaidi100_status', '1', 'kuaidi100', '1', '', 'string'), ('kuaidiniao_app_key', 'kuaidiniaofafdaf', 'kuaidiniao', '1', '', 'string'), ('kuaidiniao_express', '[\"QFKD\",\"ZTO\",\"DBL\",\"ZENY\"]', 'kuaidiniao', '1', '', 'json'), ('kuaidiniao_e_business_id', 'kuaidiniao_e_business_id', 'kuaidiniao', '1', '', 'string'), ('kuaidiniao_status', '1', 'kuaidiniao', '1', '', 'string'), ('language_id', 'zh_CN', 'site', '1', '', 'string'), ('like', '25,44,26,27,46', 'home', '1', '', 'string'), ('list_catid', '1', 'home', '1', '', 'string'), ('live_link1', 'http://localhost/shop/yf_shop/index.php', 'slider', '1', '轮播轮播', 'string'), ('live_link2', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('live_link3', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('live_link4', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('logistics_channel', 'kuaidi100', 'logistics', '1', '', 'string'), ('logo', '', 'main', '1', '', 'string'), ('mlogo', '', 'main', '1', '', 'string'), ('modify_mobile', 'faf', 'msg_tpl', '1', '手机验证通知', 'string'), ('monetary_unit', '￥', 'site', '1', '', 'string'), ('msg_tpl1', '21212', 'msg_tpl', '1', '212', 'string'), ('new_pro', '48,32,23,25,28', 'home', '1', '', 'string'), ('openstatistics', '1', 'main', '1', '', 'string'), ('opensuburl', '0', 'seo', '1', '', 'string'), ('order_id_prefix_format', 'DD-', 'bill_format', '1', '//自定义订单前缀', 'string'), ('owntel', '021-64966875', 'main', '1', '', 'string'), ('photo_font', '\r\nArial,宋体,微软雅黑', 'photo', '1', '水印字体', 'string'), ('photo_goods_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217234113345.jpg!300x300.jpg', 'photo', '1', '商品默认图片', 'string'), ('photo_shop_head_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217274997950.jpg!180x80.jpg', 'photo', '1', '店铺默认头像', 'string'), ('photo_shop_logo', 'http://yuanfeng.com/tech12/yf_shop/image.php/shop/data/upload/media/173597/47/image/20160714/1468475601861711.jpg', 'photo', '1', '店铺默认标志', 'string'), ('photo_user_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469780228802155.jpg', 'photo', '1', '会员默认头像', 'string'), ('Plugin_Cron', '0', 'plugin', '1', '', 'string'), ('Plugin_Log', '1', 'plugin', '1', '', 'string'), ('Plugin_Perm', '1', 'plugin', '1', '', 'string'), ('Plugin_Xhprof', '0', 'plugin', '1', '', 'string'), ('pointprod_isuse', '1', 'promotion', '1', '积分兑换是否开', 'number'), ('pointshop_isuse', '1', 'promotion', '1', '积分中心是否开启', 'number'), ('points_avatar', '50', 'points', '1', '', 'string'), ('points_checkin', '5', 'points', '1', '', 'string'), ('points_consume', '100', 'points', '1', '', 'string'), ('points_email', '50', 'points', '1', '', 'string'), ('points_evaluate', '21', 'points', '1', '商品评论获取积分', 'string'), ('points_evaluate_good', '50', 'points', '1', '', 'string'), ('points_evaluate_image', '10', 'points', '1', '', 'string'), ('points_login', '15', 'points', '1', '登陆获取积分', 'string'), ('points_mobile', '50', 'points', '1', '', 'string'), ('points_order', '800', 'points', '1', '订单获取积分上限', 'string'), ('points_recharge', '100', 'points', '1', '订单每多少获取多少积分', 'string'), ('points_reg', '50', 'points', '1', '注册获取积分', 'string'), ('point_description', '收到公司的', 'seo', '1', '', 'string'), ('point_description_content', '特温特', 'seo', '1', '', 'string'), ('point_keyword', ' nfbgnjgf', 'seo', '1', '', 'string'), ('point_keyword_content', '热热', 'seo', '1', '', 'string'), ('point_title', 'e{sitename}', 'seo', '1', '', 'string'), ('point_title_content', 'g{sitename}', 'seo', '1', '', 'string'), ('product_description', '商品', 'seo', '1', '', 'string'), ('product_keyword', '商品', 'seo', '1', '', 'string'), ('product_title', '商品{sitename}{name}', 'seo', '1', '', 'string'), ('promotion_allow', '1', 'promotion', '1', '促销活动是否开启', 'number'), ('promotion_discount_price', '12', 'discount', '1', '', 'number'), ('promotion_increase_price', '20', 'increase', '1', '', 'number'), ('promotion_mansong_price', '15', 'mansong', '1', '', 'number'), ('promotion_voucher_buyertimes_limit', '10', 'voucher', '1', '', 'number'), ('promotion_voucher_price', '21', 'voucher', '1', '', 'number'), ('promotion_voucher_storetimes_limit', '2', 'voucher', '1', '', 'number'), ('protection_service_status', '1', 'operation', '1', '', 'string'), ('qanggou', '48', 'home', '1', '', 'string'), ('regname', 'register.php', 'main', '1', '', 'string'), ('remote_image_key', 'abcdgfgsgfsgfsg23132', 'upload', '1', '', 'string'), ('remote_image_status', '1', 'upload', '1', '', 'string'), ('remote_image_url', 'http://127.0.0.1/yf_shop/uploader.php', 'upload', '1', '', 'string'), ('reset_pwd', 'faf', 'msg_tpl', '1', '重置密码通知', 'string'), ('retain_domain', 'www', 'domain', '1', '', 'string'), ('rewrite', '0', 'seo', '1', '', 'string'), ('search_words', '茶杯,衣服,美食,电脑,电视,12,67,76,99', 'search', '1', '搜索词', 'string'), ('send_chain_code', 'faf', 'msg_tpl', '1', '门店提货通知', 'string'), ('send_pickup_code', 'faf', 'msg_tpl', '1', '自提通知', 'string'), ('send_vr_code', 'faf', 'msg_tpl', '1', '虚拟兑换码通知', 'string'), ('service_station_status', '0', 'operation', '1', '', 'string'), ('setting_buyer_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470214463395892.jpg!150x40.jpg', 'setting', '1', '', 'string'), ('setting_email', '552786543@qq.com', 'setting', '1', '', 'string'), ('setting_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217293114679.jpg!240x60.jpg', 'setting', '1', '', 'string'), ('setting_phone', '021-888888,021-112121', 'setting', '1', '', 'string'), ('setting_seller_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160802/1470128931804909.jpg', 'setting', '1', '', 'string'), ('shop_description', '店铺', 'seo', '1', '', 'string'), ('shop_domain', '1', 'domain', '1', '', 'string'), ('shop_is_open', '1', 'main', '1', '', 'string'), ('shop_keyword', '店铺', 'seo', '1', '', 'string'), ('shop_title', '店铺{shopname}{sitename}', 'seo', '1', '', 'string'), ('site_name', '网付宝', 'site', '1', '', 'string'), ('site_status', '1', 'site', '1', '', 'number'), ('slider1_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779648351956.jpg', 'slider', '1', '团购轮播1', 'string'), ('slider2_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779744758951.jpg', 'slider', '1', '团购轮播2', 'string'), ('slider3_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779757819368.jpg', 'slider', '1', '团购轮播3', 'string'), ('slider4_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779795128665.jpg', 'slider', '1', '团购轮播4', 'string'), ('slogo', '', 'main', '1', '', 'string'), ('sms_account', 'yf_shop', 'sms', '1', '', 'string'), ('sms_pass', 'yf_shop', 'sms', '1', '', 'string'), ('sns_description', 'sns', 'seo', '1', '', 'string'), ('sns_keyword', 'sns{name}', 'seo', '1', '', 'string'), ('sns_title', 'sns{sitename}', 'seo', '1', '', 'string'), ('sphinx_search_host', '111123213', 'sphinx', '1', '', 'string'), ('sphinx_search_port', '121212', 'sphinx', '1', '', 'string'), ('sphinx_statu', '1', 'sphinx', '1', '', 'string'), ('statistics_code', '第三方流量统计代码78', 'site', '1', '', 'string'), ('stat_is_open', 'fwefe', 'main', '1', '', 'string'), ('tg_description', '团购', 'seo', '1', '', 'string'), ('tg_description_content', '团购', 'seo', '1', '', 'string'), ('tg_keyword', '团购', 'seo', '1', '', 'string'), ('tg_keyword_content', '团购', 'seo', '1', '', 'string'), ('tg_title', '{sitename}-团购-{name}1', 'seo', '1', '', 'string'), ('tg_title_content', '{sitename}-团购{name}', 'seo', '1', '', 'string'), ('theme_id', 'default', 'site', '1', '', 'string'), ('time_format', 'H:i:s', 'site', '1', '', 'string'), ('time_zone_id', 'Asia/Shanghai', 'site', '1', '', 'number'), ('title', '最好用的支付系统', 'seo', '1', '', 'string'), ('voucher_allow', '1', 'promotion', '1', '代金券功能是否开启', 'number'), ('weburl', 'http://demo.bbc-builder.com', 'main', '1', '', 'string');
COMMIT;

BEGIN;
INSERT INTO `pay_web_config` VALUES ('current_db_version', '37965', 'site', '1', '', 'string'), ('current_version', '1.0.2', 'site', '1', '', 'string'), ('required_mysql_version', '5.0', 'site', '1', '', 'string'), ('required_php_version', '5.3', 'site', '1', '', 'string');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
