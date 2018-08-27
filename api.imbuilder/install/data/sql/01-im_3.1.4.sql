-- 用户信息表中添加用户手机号
ALTER TABLE `im_user_base` ADD COLUMN `user_mobile`  varchar(30) NOT NULL COMMENT '手机号' AFTER `user_key`;