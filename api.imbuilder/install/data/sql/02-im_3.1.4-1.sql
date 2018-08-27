ALTER TABLE `im_user_base`
ADD `message_version` bigint(20) NOT NULL DEFAULT '0' COMMENT '消息版本号，用于上报消息和检查消息。来源熔联云';

ALTER TABLE `im_chatlog` ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除';