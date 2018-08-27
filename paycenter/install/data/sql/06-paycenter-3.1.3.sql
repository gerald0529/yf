ALTER TABLE `pay_user_info`
ADD COLUMN `user_identity_start_time`  date NOT NULL COMMENT '证件有效期，开始时间' AFTER `user_identity_face_logo`;
ALTER TABLE `pay_user_info`
ADD COLUMN `user_identity_end_time`  date NOT NULL COMMENT '证件有效期，结束时间' AFTER `user_identity_start_time`;

ALTER TABLE `pay_consume_trade`
ADD COLUMN `trade_commis_refund`  decimal(10,2) NOT NULL COMMENT '退还佣金' AFTER `trade_commis_amount`;

DELETE FROM pay_web_config WHERE config_key='site_logo';
INSERT INTO `pay_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES('site_logo','','site','1','','string');

