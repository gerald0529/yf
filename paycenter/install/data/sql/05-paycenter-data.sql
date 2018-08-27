
	ALTER TABLE `pay_card_info`
MODIFY COLUMN `card_id`  int(10) NOT NULL COMMENT '卡片id' AFTER `card_password`;

ALTER TABLE `pay_card_base`
MODIFY COLUMN `card_id`  int(10) NOT NULL COMMENT '卡的id' FIRST ;

ALTER TABLE `pay_consume_record`
ADD COLUMN `record_payorder`  varchar(50) NOT NULL COMMENT '实际支付单号' AFTER `record_status`;

ALTER TABLE `pay_user_resource`
ADD COLUMN `user_credit_limit` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '信用额度',
ADD COLUMN `user_credit_availability` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '可用额度';


ALTER TABLE `pay_user_info`
ADD COLUMN `user_bt_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '白条审核状态：0未审核1待审核2审核成功3审核失败' AFTER `user_identity_face_logo`;

ALTER TABLE `pay_user_info`
ADD COLUMN `user_btapply_time`  datetime NOT NULL COMMENT '白条申请时间' AFTER `user_bt_status`;

ALTER TABLE `pay_user_info`
ADD COLUMN `user_btverify_time`  datetime NOT NULL COMMENT '白条审核时间' AFTER `user_btapply_time`;

ALTER TABLE `pay_user_info`
ADD COLUMN `user_provinceid`  int(11) NOT NULL AFTER `user_remark`,
ADD COLUMN `user_cityid`  int(11) NOT NULL AFTER `user_provinceid`,
ADD COLUMN `user_areaid`  int(11) NOT NULL AFTER `user_cityid`,
ADD COLUMN `user_address`  varchar(255) NOT NULL COMMENT '用户详细地址' AFTER `user_areaid`;

ALTER TABLE `pay_user_resource`
ADD COLUMN `user_credit_return`  decimal(16,2) NOT NULL DEFAULT 0.00 COMMENT '已还信用额度' AFTER `user_credit_availability`;

ALTER TABLE `pay_consume_record`
ADD COLUMN `credit_remain`  decimal(16,2) NOT NULL DEFAULT 0.00 COMMENT '白条剩余还款金额' AFTER `record_delete`;
 

ALTER TABLE `pay_user_resource`
ADD COLUMN `user_credit_cycle`  mediumint(4) NOT NULL DEFAULT 30 COMMENT '白条还款周期' AFTER `user_credit_return`;

ALTER TABLE `pay_user_resource`
DROP COLUMN `user_credit`;


ALTER TABLE `pay_consume_trade` ADD COLUMN `pay_user_id` INT(11)  NOT NULL DEFAULT '0' COMMENT '付款人id' AFTER `seller_id`;

  