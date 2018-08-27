CREATE TABLE IF NOT EXISTS `pay_transfer_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` int(11) NOT NULL COMMENT '发起转帐或红包的人',
  `to_user` int(11) NOT NULL COMMENT '接收人',
  `send_time` int(11) NOT NULL COMMENT '发送时间',
  `receive_time` int(11) DEFAULT NULL COMMENT '收到时间',
  `money` decimal(10,2) NOT NULL COMMENT '转了多少钱',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1为已收到，2为过期',
  `txt` text COMMENT '注释',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1红包 2 转帐',
  `transaction_number` char(20) NOT NULL COMMENT '交易单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转帐，红包' AUTO_INCREMENT=1 ;

CREATE TABLE `pay_user_bank_card` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `bank_name` varchar(50) NOT NULL COMMENT '银行名称',
  `bank_card_number` varchar(50) NOT NULL COMMENT '银行卡号',
  `bank_user_name` varchar(30) NOT NULL COMMENT '开户人姓名',
  `bank_mobile_number` varchar(11) NOT NULL COMMENT '银行预留手机号',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户银行卡';

ALTER TABLE `pay_consume_deposit`
ADD COLUMN `deposit_return_trade_no`  varchar(50) NOT NULL DEFAULT '' COMMENT '充值成功以后的流水' AFTER `deposit_trade_status`;

DELETE FROM `pay_payment_channel` WHERE payment_channel_code='tenpay';