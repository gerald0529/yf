-- im群组增加免打扰字段
ALTER TABLE `im_user_group_rel` ADD group_is_disturb TINYINT(1) NOT NULL DEFAULT 0 COMMENT '群组是否免打扰，1是免打扰';
ALTER TABLE `im_user_group_rel` CHANGE group_id group_bind_id VARCHAR(50) NOT NULL DEFAULT 'visitor' COMMENT '群组绑定id 唯一';

-- 添加好友验证信息字段
ALTER TABLE `im_user_push` ADD COLUMN verify_comtent VARCHAR(200) COMMENT '添加好友验证信息';

-- 好友表添加背景图字段
ALTER TABLE `im_user_friend` ADD COLUMN chat_background VARCHAR(255) DEFAULT NULL COMMENT '好友聊天背景图';

-- 修改群组名字段长度
ALTER TABLE `im_user_group` CHANGE group_name group_name VARCHAR(255) NOT NULL DEFAULT '' COMMENT '组名称';

-- 添加用户登录状态 2017.7.7
ALTER TABLE `im_user_login` ADD COLUMN user_status TINYINT(1) NOT NULL DEFAULT 0 COMMENT '用户登陆状态，0为未登录，1为登陆状态，默认为0';

-- 新增群昵称字段
ALTER TABLE `im_user_group` ADD COLUMN group_nickname VARCHAR(50) NOT NULL DEFAULT '' COMMENT '群昵称';