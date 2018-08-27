ALTER TABLE `yf_goods_common`
ADD COLUMN `is_del`  tinyint(11) NOT NULL DEFAULT '1' COMMENT '是否删除'  AFTER `common_edit_time`;
ALTER TABLE `yf_goods_base`
ADD COLUMN `is_del`  tinyint(11) NOT NULL DEFAULT '1' COMMENT '是否删除'  AFTER `goods_parent_id`;
