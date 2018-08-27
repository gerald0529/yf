
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yf_app_notify
-- ----------------------------
DROP TABLE IF EXISTS `yf_app_notify`;
CREATE TABLE `yf_app_notify` (
  `app_notify_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `app_notify_voice` tinyint(3) NOT NULL DEFAULT '0' COMMENT '新消息声音',
  `app_notify_vibrate` tinyint(3) NOT NULL DEFAULT '0' COMMENT '新消息振动',
  `equipment` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`app_notify_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
