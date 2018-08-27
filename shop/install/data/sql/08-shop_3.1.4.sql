-- 快递物流
DROP TABLE IF EXISTS `yf_express`;
CREATE TABLE `yf_express` (
  `express_id` int(10) NOT NULL AUTO_INCREMENT,
  `express_name` varchar(30) NOT NULL COMMENT '快递公司',
  `express_pinyin` varchar(30) NOT NULL COMMENT '物流拼音-阿里',
  `express_pinyin_kdn` varchar(30) NOT NULL COMMENT '物流拼音-快递鸟',
  `express_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0关闭1开启',
  `express_displayorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用0否1是',
  `express_commonorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用',
  PRIMARY KEY (`express_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快递表';

-- ----------------------------
-- Records of yf_express
-- ----------------------------
INSERT INTO `yf_express` VALUES ('1', '安能', 'ANE','ANE', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('2', '安信达快递', 'ANXINDA','axd', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('3', '百福东方', 'EES','BFDF', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('7', 'COE东方快递', 'COE','COE', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('9', '德邦', 'DEPPON','DBL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('10', 'DHL', 'DHL','DHL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('11', 'D速物流', 'DEXP','DSWL', '0', '0', '1');
INSERT INTO `yf_express` VALUES ('12', '大田物流', 'DTW','DTWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('13', 'EMS', 'EMS','EMS', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('14', '快捷速递', 'FASTEXPRESS','FAST', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('15', 'FedEx联邦快递', 'FEDEXIN','FEDEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('18', '共速达', 'GSD','GSD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('19', '国通快递', 'GTO','GTO', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('22', '天天快递', 'TIKDEX','HHTT', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('23', '恒路物流', 'HENGLU','HLWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('24', '天地华宇', 'HOAU','HOAU', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('26', '百世汇通', 'HTKY','HTKY', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('29', '京东快递', 'JD','JD', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('30', '京广速递', 'KKE','JGSD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('31', '佳吉快运', 'JIAJI','JJKY', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('33', '急先达', 'JOUST','JXD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('34', '晋越快递', 'PEWKEE','JYKD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('35', '加运美', 'TMS','JYM', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('36', '佳怡物流', 'JIAYI','JYWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('37', '龙邦快递', 'LBEX','LB', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('38', '联昊通速递', 'LTS','LHT', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('39', '民航快递', 'CAE','MHKD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('42', '全晨快递', 'QCKD','QCKD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('43', '全峰快递', 'QFKD','QFKD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('47', '顺丰快递', 'SFEXPRESS','SF', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('48', '盛丰物流', 'SFWL','SFWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('49', '盛辉物流', 'SHENFHUI','SHWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('51', '申通快递', 'STO','STO', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('52', '速尔快递', 'SURE','SURE', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('54', '全一快递', 'APEX','UAPEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('55', '优速快递', 'UC56','UC', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('56', '万家物流', 'WANJIA','WJWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('57', '万象物流', 'EWINSHINE','WXWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('58', '新邦物流', 'XBWL','XBWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('59', '信丰快递', 'XFEXPRESS','XFEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('61', '源安达快递', 'YADEX','YADEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('62', '远成物流', 'YCGWL','YCWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('63', '韵达快递', 'YUNDA','YD', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('64', '越丰物流', 'YFEXPRESS','YFEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('65', '原飞航物流', 'YFHEX','YFHEX', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('66', '亚风快递', 'BROADASIA','YFSD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('67', '运通快递', 'YTEXPRESS','YTKD', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('68', '圆通速递', 'YTO','YTO', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('69', '邮政平邮/小包', 'CHINAPOST','YZPY', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('72', '宅急送', 'ZJS','ZJS', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('74', '中铁快运', 'CRE','ZTKY', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('75', '中通速递', 'ZTO','ZTO', '1', '0', '1');
INSERT INTO `yf_express` VALUES ('76', '中铁物流', 'ZTKY','ZTWL', '0', '0', '0');
INSERT INTO `yf_express` VALUES ('77', '中邮物流', 'CNPL','ZYWL', '0', '0', '0');


INSERT INTO `yf_web_config` (`config_key`, `config_value`, `config_type`, `config_enable`, `config_comment`, `config_datatype`) VALUES ('logistics_express', '[]', 'logistics', '1', '', 'json');

ALTER TABLE `yf_order_base` MODIFY COLUMN `voucher_price`  decimal(10,2) NOT NULL COMMENT '代金券面额' AFTER `voucher_id`;

ALTER TABLE `yf_voucher_base` MODIFY COLUMN `voucher_price`  decimal(10,2) NOT NULL COMMENT '代金券面额' AFTER `voucher_end_date`;

ALTER TABLE `yf_voucher_price` MODIFY COLUMN `voucher_price`  decimal(10,2) NOT NULL COMMENT '代金券面值' AFTER `voucher_price_id`;

ALTER TABLE `yf_voucher_template` MODIFY COLUMN `voucher_t_price`  decimal(10,2) NOT NULL COMMENT '代金券模版面额' AFTER `voucher_t_end_date`;

ALTER TABLE `yf_redpacket_base` MODIFY COLUMN `redpacket_price`  decimal(10,2) NOT NULL COMMENT '红包面额' AFTER `redpacket_end_date`;

ALTER TABLE `yf_redpacket_template` MODIFY COLUMN `redpacket_t_price`  decimal(10,2) NOT NULL COMMENT '红包模版面额' AFTER `redpacket_t_end_date`;