-- 拼团

CREATE TABLE `yf_pintuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增长',
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `user_id` int(11) NOT NULL COMMENT '商家ID',
  `name` varchar(50) NOT NULL COMMENT '拼团活动名称',
  `person_num` int(11) NOT NULL COMMENT '几人成团',
  `start_time` datetime NOT NULL COMMENT '开团时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `created_time` datetime NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可用，1为可用。',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐，0不推荐，1推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团主表';


CREATE TABLE `yf_pintuan_buyer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detail_id` int(11) NOT NULL COMMENT '拼团详情',
  `user_id` int(11) NOT NULL COMMENT '购买者ID',
  `created_time` datetime NOT NULL COMMENT '购买时间',
  `mark_id` int(11) NOT NULL COMMENT '是否是同一个团的，对应yf_pingtuan_mark表',
  `ranking` int(11) NOT NULL COMMENT '在团购，人中排第几。0为单独团',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购买人';


CREATE TABLE `yf_pintuan_combo` (
  `combo_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '套餐编号',
  `user_id` int(10) NOT NULL COMMENT '会员编号',
  `user_nickname` varchar(100) NOT NULL COMMENT '会员名称',
  `shop_id` int(10) NOT NULL COMMENT '店铺编号',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `combo_start_time` datetime NOT NULL COMMENT '开始时间',
  `combo_end_time` datetime NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='拼团套餐表';


CREATE TABLE `yf_pintuan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pintuan_id` int(11) NOT NULL COMMENT '拼团共用ID，如开始时间等',
  `goods_id` int(11) NOT NULL COMMENT '参加拼团的商品ID',
  `price_ori` float(10,2) NOT NULL COMMENT '原价格',
  `price` float(10,2) NOT NULL COMMENT '团购价格',
  `price_one` float(10,2) NOT NULL COMMENT '单独购买价格',
  `recommend_pic` varchar(255) NOT NULL COMMENT '推荐图片地址',
  `cid` int(11) NOT NULL COMMENT '商品类别id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团详情';



CREATE TABLE `yf_pintuan_mark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '团长ID',
  `detail_id` int(11) NOT NULL COMMENT '拼团详情',
  `created_time` datetime NOT NULL COMMENT '创建时间',
  `num` tinyint(4) NOT NULL COMMENT '当前参团人数',
  `status` tinyint(1) NOT NULL COMMENT '0,等待成团。1为已成团，2为成团失败，3为已完成退款',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团，团长标识';



CREATE TABLE `yf_pintuan_temp` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `detail_id` int(11) NOT NULL COMMENT '活动详情id',
  `mark_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '团长id,0表示当前用户为团长',
  `goods_id` bigint(20) NOT NULL COMMENT '商品id',
  `order_id` varchar(100) NOT NULL COMMENT '订单id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0拼团，1单独购买',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('pintuan_allow', '1', 'promotion', '1', '促销活动是否开启', 'number');
INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('pintuan_price', '100', 'pintuan', '1', '拼团', 'number');


INSERT INTO `yf_platform_nav` (`nav_type`, `nav_title`, `nav_url`, `nav_new_open`) VALUES ('3', '拼团活动', 'index.php?ctl=PinTuan&met=index&typ=e', '1'); 

INSERT INTO `yf_order_return_reason` VALUES ('3', '拼团失败退款', '255');


ALTER TABLE `yf_order_base` ADD COLUMN `order_user_discount` DECIMAL(10,2) DEFAULT 0.00 NOT NULL COMMENT '会员折扣' AFTER `directseller_discount`; 


ALTER TABLE yf_chain_base MODIFY chain_name VARCHAR(25) NOT NULL DEFAULT '' COMMENT '门店名称';


ALTER TABLE `yf_goods_cat_nav` ADD COLUMN `goods_cat_nav_adv_url` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '广告图跳转链接' AFTER `goods_cat_nav_adv`;

















