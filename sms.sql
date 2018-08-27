-- 添加百度模板字段
ALTER TABLE `ucenter_message_template` ADD `baidu_tpl_id` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '百度短信模板id';

ALTER TABLE `yf_message_template` ADD `baidu_tpl_id` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '百度短信模板id';

ALTER TABLE `pay_message_template` ADD `baidu_tpl_id` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '百度短信模板id';