ALTER TABLE `yf_user_message`
ADD COLUMN `message_receive_status`  int(1) NOT NULL DEFAULT 0 COMMENT '是否在接受者处显示 0-显示 1-隐藏' AFTER `user_message_receive`,
ADD COLUMN `message_send_status`  int(1) NOT NULL DEFAULT 0 COMMENT '是否在发送者处显示 0-显示 1-隐藏' AFTER `user_message_send`;
