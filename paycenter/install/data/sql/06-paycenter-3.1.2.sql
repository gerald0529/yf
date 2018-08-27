ALTER TABLE  `pay_consume_withdraw` ADD  `remark` VARCHAR( 255 )  NULL COMMENT  '平台审核备注';
INSERT `pay_payment_channel` SET  `payment_channel_id` = '1' , `payment_channel_code` = 'alipay' , `payment_channel_name` = '支付宝-即时到账fastpay' , `payment_channel_image` = 'paycenter/static/default/images/zhifubao.png' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '1' ;
INSERT `pay_payment_channel` SET  `payment_channel_id` = '2' , `payment_channel_code` = 'tenpay' , `payment_channel_name` = '财付通' , `payment_channel_image` = 'paycenter/static/default/images/caifutong.png' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '1' ;
INSERT `pay_payment_channel` SET  `payment_channel_id` = '4' , `payment_channel_code` = 'wx_native' , `payment_channel_name` = '微信支付-扫码' , `payment_channel_image` = 'paycenter/static/default/images/weixinzhifu.png' , `payment_channel_config` = '{"appid":"","key":"","mchid":"","appsecret":""}' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '1' , `payment_channel_enable` = '1' ;
INSERT `pay_payment_channel` SET  `payment_channel_id` = '9' , `payment_channel_code` = 'baitiao' , `payment_channel_name` = '白条支付' , `payment_channel_image` = '' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '1' ;
INSERT `pay_payment_channel` SET  `payment_channel_id` = '11' , `payment_channel_code` = 'bestpay' , `payment_channel_name` = '翼支付' , `payment_channel_image` = 'paycenter/static/default/images/bestpay.png' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '1' ;
INSERT `pay_payment_channel` SET  `payment_channel_id` = '12' , `payment_channel_code` = 'unionpay' , `payment_channel_name` = '银联支付' , `payment_channel_image` = 'paycenter/static/default/images/unionpay.png' , `payment_channel_config` = '' , `payment_channel_status` = '0' , `payment_channel_allow` = 'both' , `payment_channel_wechat` = '0' , `payment_channel_enable` = '1' ;