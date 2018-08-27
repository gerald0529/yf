
-- ALTER TABLE `yf_user_footprint` ADD COLUMN `footprint_date`  date NOT NULL COMMENT '足记 - 年月日' AFTER `footprint_time`;

-- INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('self_shop_show', '1', 'sub_site_self_shop', '1', '', 'string');

UPDATE `yf_message_template` SET `name`='交易被投诉' WHERE (`id`='3')