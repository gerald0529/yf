-- 支付notify数据
ALTER TABLE `pay_union_order` ADD COLUMN `notify_data` TEXT NOT NULL COMMENT '支付回调参数信息' AFTER `union_online_pay_amount`;
--小程序支付数据
INSERT `pay_payment_channel` SET  `payment_channel_id` = '16' , `payment_channel_code` = 'wxapp' , `payment_channel_name` = '小程序支付' , `payment_channel_image` = '' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'wap' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '0' ;