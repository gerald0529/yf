CREATE TABLE `yf_order_invoice` (
  `order_invoice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引id',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `invoice_state` enum('1','2','3') CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '1普通发票2电子发票3增值税发票',
  `invoice_title` varchar(50) DEFAULT '' COMMENT '发票抬头[普通发票]',
  `invoice_content` varchar(10) DEFAULT '' COMMENT '发票内容[普通发票]',
  `invoice_company` varchar(50) DEFAULT '' COMMENT '单位名称',
  `invoice_code` varchar(50) DEFAULT '' COMMENT '纳税人识别号',
  `invoice_reg_addr` varchar(50) DEFAULT '' COMMENT '注册地址',
  `invoice_reg_phone` varchar(30) DEFAULT '' COMMENT '注册电话',
  `invoice_reg_bname` varchar(30) DEFAULT '' COMMENT '开户银行',
  `invoice_reg_baccount` varchar(30) DEFAULT '' COMMENT '银行帐户',
  `invoice_rec_name` varchar(20) DEFAULT '' COMMENT '收票人姓名',
  `invoice_rec_phone` varchar(15) DEFAULT '' COMMENT '收票人手机号',
  `invoice_rec_email` varchar(100) DEFAULT '' COMMENT '收票人邮箱',
  `invoice_rec_province` varchar(30) DEFAULT '' COMMENT '收票人省份',
  `invoice_goto_addr` varchar(50) DEFAULT '' COMMENT '送票地址',
  `invoice_province_id` int(11) DEFAULT NULL,
  `invoice_city_id` int(11) DEFAULT NULL,
  `invoice_area_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`order_invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单发票信息表';

-- 2017-06-12 初始化店铺入驻设置
insert into `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) values('join_type','1','join_setting','1','入驻资格设置 1:仅企业 2:仅个人 3:企业和个人','number');
insert into `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) values('supplier_type','1','supplier_setting','1','入驻资格设置 1:仅企业 2:仅个人 3:企业和个人','number');

-- 推荐商品支持分站显示
ALTER TABLE `yf_goods_recommend` ADD COLUMN `sub_site_id` INT(11) NOT NULL DEFAULT '0' COMMENT '分站id';

-- 2017-06-22 商家中心默认地址修改，供应商入驻改为供应商中心
UPDATE `yf_platform_nav` SET nav_url = 'index.php?ctl=Seller_Shop_Settled&amp;met=index&amp;type=e' WHERE nav_title = '商家中心';
UPDATE `yf_platform_nav` SET nav_title = '供应商中心' WHERE nav_title = '供应商入驻';

-- 2017-6-27 goods_common表添加字段来区分商品是正常添加还是外部导入
ALTER TABLE `yf_goods_common` ADD common_goods_from TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1正常添加，2淘宝导入。默认为1';

-- 2017-06-30 order_base添加红包退款金额
ALTER TABLE `yf_order_base` ADD COLUMN `order_rpt_return`  decimal(10,2) NOT NULL COMMENT '红包退款金额' AFTER `order_rpt_price`;

-- 2017-07-06 运费和售卖地区

ALTER TABLE `yf_goods_common` DROP COLUMN transport_type_name;
ALTER TABLE `yf_goods_common` DROP COLUMN common_freight;
ALTER TABLE `yf_goods_common` ADD transport_area_id INT(10) NOT NULL COMMENT '售卖区域id,和yf_transport_area中的id对应';

CREATE TABLE `yf_transport_area` (
  `id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '售卖区域模板',
  `name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '物流及售卖区域模板名',
  `shop_id` INT(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `area_ids` TEXT NOT NULL COMMENT '地区id',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='售卖区域表';


CREATE TABLE `yf_transport_rule` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `area_name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '区域城市名称',
  `area_ids` TEXT NOT NULL COMMENT '区域城市id',
  `rule_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1按重量  2按件数    3按体积',
  `update_time` DATETIME NOT NULL COMMENT '最后编辑时间',
  `transport_template_id` INT(11) NOT NULL DEFAULT '0' COMMENT '模板id，与transport_template表中的id对应',
  `logistics_type` VARCHAR(50) NOT NULL DEFAULT '1' COMMENT '物流类型，扩展字段',
  `default_num` FLOAT(3,1) NOT NULL DEFAULT '1.0' COMMENT '默认数量',
  `default_price` DECIMAL(6,2) NOT NULL DEFAULT '0.00' COMMENT '默认运费',
  `add_num` FLOAT(3,1) NOT NULL DEFAULT '1.0' COMMENT '增加数量',
  `add_price` DECIMAL(4,2) NOT NULL DEFAULT '0.00' COMMENT '增加运费',
  PRIMARY KEY (`id`),
  KEY `transport_template_id` (`transport_template_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='运费规则表';


CREATE TABLE `yf_transport_template` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '运费模板id',
  `name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '模板名称',
  `shop_id` INT(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `status` TINYINT(2) NOT NULL DEFAULT '0' COMMENT '状态，1开启，0关闭',
  `const_price` DECIMAL(6,2) NOT NULL DEFAULT '0.00' COMMENT '固定运费',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='运费模板表';

-- 2017/07/13 订单商品表增加退款状态
ALTER TABLE `yf_order_goods`
ADD COLUMN `goods_return_status`  tinyint(1) NOT NULL COMMENT '退款状态：0：无退款 1：退款中 2：退款完成' AFTER `order_goods_benefit`;

-- 商家入驻短信模板
insert into `yf_message_template` (`id`, `code`, `name`, `title`, `content_email`, `type`, `is_phone`, `is_email`, `is_mail`, `content_mail`, `content_phone`, `force_phone`, `force_email`, `force_mail`, `mold`) values('33','shop_personal_settled','个人商家入驻信息验证','[weburl_name]提醒：个人商家入驻短信验证','您入驻[weburl_name]的验证码是[yzm]。如果非本人操作，请勿理会。','0','1','1','0','','您入驻[weburl_name]的验证码是[yzm]。如果非本人操作，请勿理会。','0','0','0','0');

-- 退款/退货单中商家处理结果
ALTER TABLE `yf_order_return`
ADD COLUMN `return_shop_handle`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '商家处理结果  1-待处理  2-卖家审核通过 3-卖家审核不通过' AFTER `return_rpt_cash`;

ALTER TABLE `yf_order_goods`
MODIFY COLUMN `goods_return_status`  tinyint(1) NOT NULL COMMENT '退款状态：0：无退款 1：退款中 2：退款完成  3：商家拒绝退款' AFTER `order_goods_benefit`,
MODIFY COLUMN `goods_refund_status`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '退货状态:0是无退货,1是退货中,2是退货完成 3商家拒绝退货' AFTER `goods_return_status`;

ALTER TABLE `yf_order_return`
ADD COLUMN `behalf_deliver`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '分销代发货  0：不代发货 1：代发货(分销订单DD) 2：代发货（供应订单SP）' AFTER `return_goods_return`;

ALTER TABLE `yf_user_footprint`
ADD COLUMN `footprint_date`  date NOT NULL COMMENT '足记 - 年月日' AFTER `footprint_time`;

INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('self_shop_show', '1', 'sub_site_self_shop', '1', '', 'string');

UPDATE `yf_message_template` SET `name`='交易被投诉' WHERE (`id`='3');

ALTER TABLE `yf_shop_cost` ADD COLUMN `activity_type`  tinyint(1) NOT NULL COMMENT '活动类型：1-团购 2-加价购 3-限时折扣 4-满即送 5-代金券' AFTER `cost_desc`;

ALTER TABLE `yf_shop_cost`
ADD COLUMN `activity_month`  int(10) NOT NULL COMMENT '购买活动时间' AFTER `activity_type`;

ALTER TABLE `yf_shop_cost`
ADD COLUMN `activity_price`  float(10,2) NOT NULL COMMENT '活动单价' AFTER `activity_type`;
