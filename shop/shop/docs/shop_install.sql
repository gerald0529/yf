
SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `yf_adv_page_layout`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_page_layout`;
CREATE TABLE `yf_adv_page_layout` (
  `layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layout_name` varchar(50) NOT NULL COMMENT '框架名称',
  `layout_structure` text NOT NULL COMMENT '框架结构|可以逐条存取，考虑到由平台统一设定，直接一个字段存取',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='页面布局版式-模板';

-- ----------------------------
--  Records of `yf_adv_page_layout`
-- ----------------------------
BEGIN;
INSERT INTO `yf_adv_page_layout` VALUES ('1', '模版1', '{\"block1\":{\"name\":\"a_con\",\"style\":{\"height\":\"360\",\"width\":\"210\"},\"child\":{\"block2\":{\"type\":\"category\",\"style\":{\"height\":\"110\",\"width\":\"210\",\"border-bottom\":\"1px solid #CCC\"}},\"block3\":{\"type\":\"ag\",\"style\":{\"height\":\"249\",\"width\":\"210\"}}}},\"block4\":{\"name\":\"b_con\",\"type\":\"ad\",\"style\":{\"height\":\"360\",\"width\":\"326\"}},\"block5\":{\"name\":\"c_con\",\"style\":{\"height\":\"360\",\"width\":\"220\"},\"child\":{\"block6\":{\"type\":\"ag\",\"style\":{\"height\":\"179\",\"width\":\"220\",\"border-bottom\":\"1px solid #CCC\"}},\"block7\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}}}},\"block8\":{\"name\":\"d_con\",\"type\":\"ad\",\"style\":{\"height\":\"360\",\"width\":\"220\"}},\"block9\":{\"name\":\"e_con\",\"style\":{\"height\":\"360\",\"width\":\"220\",\"border-right\":\"none\"},\"child\":{\"block10\":{\"type\":\"ag\",\"style\":{\"height\":\"179\",\"width\":\"220\",\"border-bottom\":\"1px solid #CCC\"}},\"block11\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}}}}}'), ('2', '模版2', '{\"block1\":{\"name\":\"a_con\",\"style\":{\"height\":\"360\",\"width\":\"210\"},\"child\":{\"block2\":{\"type\":\"category\",\"style\":{\"height\":\"110\",\"width\":\"210\",\"border-bottom\":\"1px solid #CCC\"}},\"block3\":{\"type\":\"ag\",\"style\":{\"height\":\"249\",\"width\":\"210\"}}}},\"block4\":{\"name\":\"b_con\",\"type\":\"ad\",\"style\":{\"height\":\"360\",\"width\":\"326\"}},\"block5\":{\"name\":\"c_con\",\"style\":{\"height\":\"360\",\"width\":\"220\"},\"child\":{\"block6\":{\"type\":\"ag\",\"style\":{\"height\":\"179\",\"width\":\"220\",\"border-bottom\":\"1px solid #CCC\"}},\"block7\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}}}},\"block8\":{\"name\":\"d_con\",\"type\":\"ad\",\"style\":{\"height\":\"360\",\"width\":\"220\"}},\"block9\":{\"name\":\"e_con\",\"style\":{\"height\":\"360\",\"width\":\"220\",\"border-right\":\"none\"},\"child\":{\"block10\":{\"type\":\"ag\",\"style\":{\"height\":\"179\",\"width\":\"220\",\"border-bottom\":\"1px solid #CCC\"}},\"block11\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}}}},\"block12\":{\"name\":\"f_con\",\"style\":{\"height\":\"180\",\"width\":\"1200\",\"border-right\":\"none\"},\"child\":{\"block13\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"210\"}},\"block14\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"326\"}},\"block15\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}},\"block16\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\"}},\"block17\":{\"type\":\"ag\",\"style\":{\"height\":\"180\",\"width\":\"220\",\"border-right\":\"none\"}}}}}'), ('3', '模板3', '{\"block18\":{\"name\":\"a_con\",\"type\":\"ad\",\"style\":{\"height\":\"483\",\"width\":\"280\"}},\"block19\":{\"name\":\"b_con\",\"style\":{\"height\":\"483\",\"width\":\"654\",\"border-right\":\"0px\"},\"child\":{\"block20\":{\"type\":\"ag\",\"style\":{\"height\":\"241\",\"width\":\"326\",\"border-bottom\": \"1px solid #e1e1e1\"}},\"block21\":{\"type\":\"ag\",\"style\":{\"height\":\"241\",\"width\":\"326\",\"border-bottom\": \"1px solid #e1e1e1\"}},\"block22\":{\"type\":\"ag\",\"style\":{\"height\":\"241\",\"width\":\"326\"}},\"block23\":{\"type\":\"ag\",\"style\":{\"height\":\"241\",\"width\":\"326\"}}}},\"block24\":{\"name\":\"c_con\",\"style\":{\"height\":\"483\",\"width\":\"264\"},\"child\":{\"block25\":{\"type\":\"ag\",\"style\":{\"height\":\"120\",\"width\":\"264\",\"border-bottom\":\"1px solid #e1e1e1\"}},\"block26\":{\"type\":\"ag\",\"style\":{\"height\":\"120\",\"width\":\"264\",\"border-bottom\":\"1px solid #e1e1e1\"}},\"block27\":{\"type\":\"ag\",\"style\":{\"height\":\"120\",\"width\":\"264\",\"border-bottom\":\"1px solid #e1e1e1\"}},\"block28\":{\"type\":\"ag\",\"style\":{\"height\":\"120\",\"width\":\"264\"}}}}}');
COMMIT;

-- ----------------------------
--  Table structure for `yf_adv_page_settings`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_page_settings`;
CREATE TABLE `yf_adv_page_settings` (
  `page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(20) NOT NULL COMMENT '模块名称',
  `user_id` mediumint(8) NOT NULL COMMENT '所属用户',
  `page_color` varchar(20) NOT NULL COMMENT '颜色',
  `page_type` varchar(10) NOT NULL COMMENT '所在页面',
  `layout_id` int(10) NOT NULL COMMENT '模版',
  `page_update_time` datetime NOT NULL COMMENT '更新时间',
  `page_order` smallint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `page_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `page_html` text NOT NULL COMMENT '模块html代码',
  `page_json` text NOT NULL COMMENT '模块JSON代码',
  `page_cat_id` int(11) NOT NULL DEFAULT '1' COMMENT '所属分类，真正显示页面',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='页面模块表-用户获取最终的广告';

-- ----------------------------
--  Table structure for `yf_adv_page_statistics_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_page_statistics_area`;
CREATE TABLE `yf_adv_page_statistics_area` (
  `page_statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `page_id` int(10) unsigned NOT NULL COMMENT 'id',
  `page_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `page_province` varchar(20) NOT NULL,
  PRIMARY KEY (`page_statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照区域-先按照省为单位';

-- ----------------------------
--  Table structure for `yf_adv_page_statistics_day`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_page_statistics_day`;
CREATE TABLE `yf_adv_page_statistics_day` (
  `page_statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `page_id` int(10) unsigned NOT NULL COMMENT '广告页面id',
  `page_view_num` int(10) NOT NULL DEFAULT '0' COMMENT '统计数',
  `page_data` date NOT NULL DEFAULT '0000-00-00' COMMENT '天',
  PRIMARY KEY (`page_statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `yf_adv_widget_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_base`;
CREATE TABLE `yf_adv_widget_base` (
  `widget_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户id',
  `page_id` int(8) NOT NULL COMMENT '广告页id',
  `layout_id` tinyint(4) NOT NULL DEFAULT '0' COMMENT '模板布局id， 如果没有可以为0，可以理解为组概念',
  `widget_name` varchar(50) NOT NULL COMMENT '广告位名:如果有layout, 则用block1... 程序自动命名。  目前只按照具备layout的功能开发',
  `widget_cat` varchar(50) NOT NULL COMMENT '类别，目前有layout设定决定：广告（自定义数据）|商品分类（商城获取）|商品（商城获取）',
  `widget_width` varchar(10) NOT NULL COMMENT '宽度',
  `widget_height` varchar(10) NOT NULL COMMENT '高度',
  `widget_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型: 图片 幻灯片 滚动 文字  - 如果针对mall等等固定使用地方，可以修改成固定类型',
  `widget_desc` mediumtext NOT NULL COMMENT '描述',
  `widget_price` decimal(10,2) NOT NULL COMMENT '价格',
  `widget_unit` enum('day','week','month') NOT NULL COMMENT '单位',
  `widget_total` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '广告数量',
  `widget_time` datetime NOT NULL COMMENT '创建时间',
  `widget_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view  - 独立建表更好 - cpm可以使用',
  `widget_click_num` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数 - 独立建表更好 - cpc可以使用',
  `widget_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告位表';

-- ----------------------------
--  Table structure for `yf_adv_widget_cat`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_cat`;
CREATE TABLE `yf_adv_widget_cat` (
  `widget_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `widget_cat_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '分类名称',
  `widget_cat_desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`widget_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告位类型表-数据类型，阅读使用，程序不调用';

-- ----------------------------
--  Table structure for `yf_adv_widget_item`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_item`;
CREATE TABLE `yf_adv_widget_item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `widget_id` int(5) unsigned NOT NULL COMMENT '广告位id',
  `item_name` varchar(50) NOT NULL DEFAULT '' COMMENT '广告名',
  `item_url` varchar(200) NOT NULL DEFAULT '' COMMENT '点击访问网址',
  `item_text` mediumtext NOT NULL COMMENT '内容',
  `item_img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `item_bgcolor` varchar(10) NOT NULL DEFAULT '' COMMENT '背景颜色',
  `item_province` varchar(50) NOT NULL DEFAULT '' COMMENT '省',
  `item_city` varchar(50) NOT NULL DEFAULT '' COMMENT '市',
  `item_area` varchar(50) NOT NULL DEFAULT '' COMMENT '区',
  `item_street` varchar(50) NOT NULL DEFAULT '',
  `item_cat_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '类别ID',
  `item_stime` datetime NOT NULL COMMENT '开始时间',
  `item_etime` datetime NOT NULL COMMENT '结束时间',
  `item_sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `item_active` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用',
  `item_time` datetime NOT NULL COMMENT '创建时间',
  `item_click_num` int(10) unsigned NOT NULL COMMENT '点击次数-- 独立建表更好',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告内容表';

-- ----------------------------
--  Table structure for `yf_adv_widget_nav`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_nav`;
CREATE TABLE `yf_adv_widget_nav` (
  `widget_nav_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `widget_nav_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '分类名称',
  `widget_nav_url` varchar(255) NOT NULL COMMENT '头部url',
  `page_id` int(10) NOT NULL DEFAULT '0' COMMENT '模板id',
  PRIMARY KEY (`widget_nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告位楼层头部分类表';

-- ----------------------------
--  Table structure for `yf_adv_widget_statistics_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_statistics_area`;
CREATE TABLE `yf_adv_widget_statistics_area` (
  `statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `widget_id` int(10) unsigned NOT NULL COMMENT 'id',
  `widget_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `widget_click_num` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `widget_province` varchar(20) NOT NULL,
  PRIMARY KEY (`statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照区域-先按照省为单位';

-- ----------------------------
--  Table structure for `yf_adv_widget_statistics_day`
-- ----------------------------
DROP TABLE IF EXISTS `yf_adv_widget_statistics_day`;
CREATE TABLE `yf_adv_widget_statistics_day` (
  `statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `widget_id` int(10) unsigned NOT NULL COMMENT 'id',
  `widget_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `widget_click_num` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `widget_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '统计日期',
  PRIMARY KEY (`statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照天为单位';

-- ----------------------------
--  Table structure for `yf_advertisement`
-- ----------------------------
DROP TABLE IF EXISTS `yf_advertisement`;
CREATE TABLE `yf_advertisement` (
  `adver_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adver_url` varchar(255) NOT NULL COMMENT '链接',
  `adver_img` varchar(255) NOT NULL COMMENT '广告图片',
  `adver_to_display` varchar(255) NOT NULL COMMENT '广告显示在哪个页面',
  `adver_type` varchar(255) NOT NULL COMMENT '广告类型',
  `adver_sort` varchar(255) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`adver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_analysis_platform_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_area`;
CREATE TABLE `yf_analysis_platform_area` (
  `platform_area_id` int(10) NOT NULL AUTO_INCREMENT,
  `area_date` date NOT NULL COMMENT '统计时间',
  `province_id` int(10) NOT NULL COMMENT '省id',
  `city_id` int(10) NOT NULL COMMENT '市id',
  `area` varchar(50) NOT NULL COMMENT '区域名称',
  `order_user_num` int(10) NOT NULL COMMENT '下单会员数',
  `order_cash` decimal(8,2) NOT NULL COMMENT '下单金额',
  `order_num` int(10) NOT NULL COMMENT '下单数量',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台区域统计表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_class`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_class`;
CREATE TABLE `yf_analysis_platform_class` (
  `platform_class_id` int(10) NOT NULL AUTO_INCREMENT,
  `class_date` date NOT NULL COMMENT '统计时间',
  `class_id` int(10) NOT NULL COMMENT '类别id',
  `class_name` varchar(50) NOT NULL COMMENT '类别名称',
  `order_num` int(10) NOT NULL COMMENT '下单量',
  `order_cash` decimal(8,2) NOT NULL COMMENT '下单额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台行业统计表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_general`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_general`;
CREATE TABLE `yf_analysis_platform_general` (
  `platform_general_id` int(10) NOT NULL AUTO_INCREMENT,
  `general_date` date NOT NULL COMMENT '时间',
  `order_cash` decimal(8,2) NOT NULL COMMENT '下单金额',
  `order_goods_num` int(10) NOT NULL COMMENT '下单商品数',
  `order_num` int(10) NOT NULL COMMENT '下单量',
  `order_user_num` int(10) NOT NULL COMMENT '下单会员数',
  `user_new_num` int(10) NOT NULL COMMENT '新增会员数',
  `shop_new_num` int(10) NOT NULL COMMENT '新增店铺数',
  `goods_new_num` int(10) NOT NULL COMMENT '新增商品数',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_general_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台统计概览表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_goods`;
CREATE TABLE `yf_analysis_platform_goods` (
  `platform_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_date` date NOT NULL COMMENT '统计时间',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `goods_price` decimal(8,2) NOT NULL COMMENT '商品价格',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `order_num` int(10) NOT NULL COMMENT '订单数量',
  `order_cash` decimal(8,2) NOT NULL COMMENT '订单金额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台商品分析表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_return`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_return`;
CREATE TABLE `yf_analysis_platform_return` (
  `platform_return_id` int(10) NOT NULL AUTO_INCREMENT,
  `return_date` date NOT NULL COMMENT '统计日期',
  `return_cash` decimal(8,2) NOT NULL COMMENT '统计金额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台售后统计表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_total`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_total`;
CREATE TABLE `yf_analysis_platform_total` (
  `platform_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_num` int(10) NOT NULL COMMENT '店铺总量',
  `user_num` int(10) NOT NULL COMMENT '会员总量',
  `goods_num` int(10) NOT NULL COMMENT '商品总量',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_total_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='总统计表';

-- ----------------------------
--  Table structure for `yf_analysis_platform_user`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_platform_user`;
CREATE TABLE `yf_analysis_platform_user` (
  `platform_user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_date` date NOT NULL COMMENT '统计时间',
  `user_id` int(10) NOT NULL COMMENT '买家id',
  `order_num` int(10) NOT NULL COMMENT '订单数',
  `order_cash` decimal(8,2) NOT NULL COMMENT '订单金额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台买家统计表';

-- ----------------------------
--  Table structure for `yf_analysis_shop_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_shop_area`;
CREATE TABLE `yf_analysis_shop_area` (
  `shop_area_id` int(10) NOT NULL AUTO_INCREMENT,
  `area_date` date NOT NULL COMMENT '统计时间',
  `province_id` int(10) NOT NULL COMMENT '省id',
  `city_id` int(10) NOT NULL COMMENT '市id',
  `area` varchar(50) NOT NULL COMMENT '区域名称',
  `order_user_num` int(10) NOT NULL COMMENT '下单会员数',
  `order_cash` decimal(8,2) NOT NULL COMMENT '下单金额',
  `order_num` int(10) NOT NULL COMMENT '下单数量',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`shop_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺区域统计表';

-- ----------------------------
--  Table structure for `yf_analysis_shop_general`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_shop_general`;
CREATE TABLE `yf_analysis_shop_general` (
  `shop_general_id` int(10) NOT NULL AUTO_INCREMENT,
  `general_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '时间',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `order_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '下单金额',
  `order_goods_num` int(10) NOT NULL COMMENT '下单商品数',
  `order_num` int(10) NOT NULL COMMENT '下单量',
  `order_user_num` int(10) NOT NULL COMMENT '下单会员数',
  `goods_favor_num` int(10) NOT NULL COMMENT '商品收藏量',
  `shop_favor_num` int(10) NOT NULL COMMENT '店铺收藏量',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`shop_general_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺统计概览表';

-- ----------------------------
--  Table structure for `yf_analysis_shop_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_shop_goods`;
CREATE TABLE `yf_analysis_shop_goods` (
  `shop_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `goods_date` date NOT NULL COMMENT '统计时间',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `goods_price` decimal(8,2) NOT NULL COMMENT '商品价格',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `order_num` int(10) NOT NULL COMMENT '订单数量',
  `order_cash` decimal(8,2) NOT NULL COMMENT '订单金额',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`shop_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺商品分析表';

-- ----------------------------
--  Table structure for `yf_analysis_shop_user`
-- ----------------------------
DROP TABLE IF EXISTS `yf_analysis_shop_user`;
CREATE TABLE `yf_analysis_shop_user` (
  `shop_user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_date` date NOT NULL COMMENT '统计时间',
  `user_id` int(10) NOT NULL COMMENT '买家id',
  `order_num` int(10) NOT NULL COMMENT '订单数',
  `order_cash` decimal(8,2) NOT NULL COMMENT '订单金额',
  `shop_id` int(10) NOT NULL,
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`shop_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台买家统计表';

-- ----------------------------
--  Table structure for `yf_announcement`
-- ----------------------------
DROP TABLE IF EXISTS `yf_announcement`;
CREATE TABLE `yf_announcement` (
  `announcement_id` int(11) NOT NULL AUTO_INCREMENT,
  `announcement_title` varchar(100) NOT NULL COMMENT '标题',
  `announcement_content` text NOT NULL COMMENT '内容',
  `announcement_url` varchar(100) DEFAULT NULL COMMENT '跳转链接',
  `announcement_create_time` datetime NOT NULL COMMENT '发布时间',
  `announcement_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 为关闭 1为开启',
  `announcement_displayorder` smallint(6) NOT NULL DEFAULT '255' COMMENT '排序',
  PRIMARY KEY (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='公告表';

-- ----------------------------
--  Table structure for `yf_article_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_article_base`;
CREATE TABLE `yf_article_base` (
  `article_id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `article_desc` mediumtext NOT NULL COMMENT '描述',
  `article_title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `article_url` varchar(100) NOT NULL DEFAULT '' COMMENT '调用网址-url，默认为本页面构造的网址，可填写其它页面',
  `article_group_id` tinyint(3) NOT NULL COMMENT '组',
  `article_template` varchar(50) NOT NULL COMMENT '模板',
  `article_seo_title` varchar(200) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `article_seo_keywords` varchar(200) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `article_seo_description` varchar(200) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `article_reply_flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用问答留言',
  `article_lang` varchar(5) NOT NULL DEFAULT 'cn' COMMENT '语言',
  `article_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型-0文章1公告',
  `article_sort` int(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `article_status` int(1) NOT NULL DEFAULT '2' COMMENT '状态 1:启用  2:关闭',
  `article_add_time` datetime NOT NULL COMMENT '添加世间',
  `article_pic` varchar(255) NOT NULL COMMENT '文章图片',
  `article_islook` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读取0未1读取',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站初始化内容设置';

-- ----------------------------
--  Table structure for `yf_article_group`
-- ----------------------------
DROP TABLE IF EXISTS `yf_article_group`;
CREATE TABLE `yf_article_group` (
  `article_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `article_group_title` varchar(60) NOT NULL DEFAULT '' COMMENT '标题',
  `article_group_lang` varchar(5) NOT NULL DEFAULT 'cn' COMMENT '语言',
  `article_group_sort` smallint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `article_group_logo` varchar(100) NOT NULL DEFAULT '' COMMENT 'logo',
  `article_group_parent_id` int(11) NOT NULL COMMENT '上级分类id',
  PRIMARY KEY (`article_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站初始化内容分组表';

-- ----------------------------
--  Table structure for `yf_article_reply`
-- ----------------------------
DROP TABLE IF EXISTS `yf_article_reply`;
CREATE TABLE `yf_article_reply` (
  `article_reply_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论回复id',
  `article_reply_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复父id',
  `article_id` int(11) unsigned NOT NULL COMMENT '所属文章id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论回复id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '评论回复姓名',
  `user_id_to` int(10) NOT NULL COMMENT '评论回复用户id',
  `user_name_to` varchar(50) NOT NULL COMMENT '评论回复用户名称',
  `article_reply_content` varchar(255) NOT NULL DEFAULT '' COMMENT '评论回复内容',
  `article_reply_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '评论回复时间',
  `article_reply_show_flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '问答是否显示',
  PRIMARY KEY (`article_reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='问答回复表';

-- ----------------------------
--  Table structure for `yf_base_cron`
-- ----------------------------
DROP TABLE IF EXISTS `yf_base_cron`;
CREATE TABLE `yf_base_cron` (
  `cron_id` int(6) NOT NULL AUTO_INCREMENT COMMENT '任务id',
  `cron_name` varchar(50) NOT NULL COMMENT '任务名称',
  `cron_script` varchar(50) NOT NULL COMMENT '任务脚本',
  `cron_lasttransact` int(10) NOT NULL COMMENT '上次执行时间',
  `cron_nexttransact` int(10) NOT NULL COMMENT '下一次执行时间',
  `cron_minute` varchar(10) NOT NULL DEFAULT '*' COMMENT '分',
  `cron_hour` varchar(10) NOT NULL DEFAULT '*' COMMENT '小时',
  `cron_day` varchar(10) NOT NULL DEFAULT '*' COMMENT '日',
  `cron_month` varchar(10) NOT NULL DEFAULT '*' COMMENT '月',
  `cron_week` varchar(10) NOT NULL DEFAULT '*' COMMENT '周',
  `cron_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '其是启用',
  PRIMARY KEY (`cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='计划任务';

-- ----------------------------
--  Table structure for `yf_base_district`
-- ----------------------------
DROP TABLE IF EXISTS `yf_base_district`;
CREATE TABLE `yf_base_district` (
  `district_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `district_name` varchar(50) NOT NULL COMMENT '地区名称',
  `district_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '地区父ID',
  `district_displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `district_region` varchar(3) NOT NULL DEFAULT '' COMMENT '大区名称',
  `district_is_level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '地区深度，从1开始',
  `district_is_leaf` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '无子类',
  PRIMARY KEY (`district_id`),
  KEY `area_parent_id` (`district_parent_id`) COMMENT '(null)'
) ENGINE=InnoDB AUTO_INCREMENT=45056 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='地区表';

-- ----------------------------
--  Records of `yf_base_district`
-- ----------------------------
BEGIN;
INSERT INTO `yf_base_district` VALUES ('1', '北京', '0', '0', '华北', '1', '0'), ('2', '天津', '0', '0', '华北', '1', '0'), ('3', '河北', '0', '0', '华北', '1', '0'), ('4', '山西', '0', '0', '华北', '1', '0'), ('5', '内蒙古', '0', '0', '华北', '1', '0'), ('6', '辽宁', '0', '0', '东北', '1', '0'), ('7', '吉林', '0', '0', '东北', '1', '0'), ('8', '黑龙江', '0', '0', '东北', '1', '0'), ('9', '上海', '0', '0', '华东', '1', '0'), ('10', '江苏', '0', '0', '华东', '1', '0'), ('11', '浙江', '0', '0', '华东', '1', '0'), ('12', '安徽', '0', '0', '华东', '1', '0'), ('13', '福建', '0', '0', '华南', '1', '0'), ('14', '江西', '0', '0', '华东', '1', '0'), ('15', '山东', '0', '0', '华东', '1', '0'), ('16', '河南', '0', '0', '华中', '1', '0'), ('17', '湖北', '0', '0', '华中', '1', '0'), ('18', '湖南', '0', '0', '华中', '1', '0'), ('19', '广东', '0', '0', '华南', '1', '0'), ('20', '广西', '0', '0', '华南', '1', '0'), ('21', '海南', '0', '0', '华南', '1', '0'), ('22', '重庆', '0', '0', '西南', '1', '0'), ('23', '四川', '0', '0', '西南', '1', '0'), ('24', '贵州', '0', '0', '西南', '1', '0'), ('25', '云南', '0', '0', '西南', '1', '0'), ('26', '西藏', '0', '0', '西南', '1', '0'), ('27', '陕西', '0', '0', '西北', '1', '0'), ('28', '甘肃', '0', '0', '西北', '1', '0'), ('29', '青海', '0', '0', '西北', '1', '0'), ('30', '宁夏', '0', '0', '西北', '1', '0'), ('31', '新疆', '0', '0', '西北', '1', '0'), ('32', '台湾', '0', '0', '港澳台', '1', '0'), ('33', '香港', '0', '0', '港澳台', '1', '0'), ('34', '澳门', '0', '0', '港澳台', '1', '0'), ('35', '海外', '0', '0', '海外', '1', '0'), ('36', '北京市', '1', '0', '', '2', '0'), ('37', '东城区', '36', '0', '', '3', '0'), ('38', '西城区', '36', '0', '', '3', '0'), ('39', '上海市', '9', '0', '', '2', '0'), ('40', '天津市', '2', '0', '', '2', '0'), ('41', '朝阳区', '36', '0', '', '3', '0'), ('42', '丰台区', '36', '0', '', '3', '0'), ('43', '石景山区', '36', '0', '', '3', '0'), ('44', '海淀区', '36', '0', '', '3', '0'), ('45', '门头沟区', '36', '0', '', '3', '0'), ('46', '房山区', '36', '0', '', '3', '0'), ('47', '通州区', '36', '0', '', '3', '0'), ('48', '顺义区', '36', '0', '', '3', '0'), ('49', '昌平区', '36', '0', '', '3', '0'), ('50', '大兴区', '36', '0', '', '3', '0'), ('51', '怀柔区', '36', '0', '', '3', '0'), ('52', '平谷区', '36', '0', '', '3', '0'), ('53', '密云县', '36', '0', '', '3', '0'), ('54', '延庆县', '36', '0', '', '3', '0'), ('55', '和平区', '40', '0', '', '3', '0'), ('56', '河东区', '40', '0', '', '3', '0'), ('57', '河西区', '40', '0', '', '3', '0'), ('58', '南开区', '40', '0', '', '3', '0'), ('59', '河北区', '40', '0', '', '3', '0'), ('60', '红桥区', '40', '0', '', '3', '0'), ('61', '塘沽区', '40', '0', '', '3', '0'), ('62', '重庆市', '22', '0', '', '2', '0'), ('64', '东丽区', '40', '0', '', '3', '0'), ('65', '西青区', '40', '0', '', '3', '0'), ('66', '津南区', '40', '0', '', '3', '0'), ('67', '北辰区', '40', '0', '', '3', '0'), ('68', '武清区', '40', '0', '', '3', '0'), ('69', '宝坻区', '40', '0', '', '3', '0'), ('70', '宁河县', '40', '0', '', '3', '0'), ('71', '静海县', '40', '0', '', '3', '0'), ('72', '蓟县', '40', '0', '', '3', '0'), ('73', '石家庄市', '3', '0', '', '2', '0'), ('74', '唐山市', '3', '0', '', '2', '0'), ('75', '秦皇岛市', '3', '0', '', '2', '0'), ('76', '邯郸市', '3', '0', '', '2', '0'), ('77', '邢台市', '3', '0', '', '2', '0'), ('78', '保定市', '3', '0', '', '2', '0'), ('79', '张家口市', '3', '0', '', '2', '0'), ('80', '承德市', '3', '0', '', '2', '0'), ('81', '衡水市', '3', '0', '', '2', '0'), ('82', '廊坊市', '3', '0', '', '2', '0'), ('83', '沧州市', '3', '0', '', '2', '0'), ('84', '太原市', '4', '0', '', '2', '0'), ('85', '大同市', '4', '0', '', '2', '0'), ('86', '阳泉市', '4', '0', '', '2', '0'), ('87', '长治市', '4', '0', '', '2', '0'), ('88', '晋城市', '4', '0', '', '2', '0'), ('89', '朔州市', '4', '0', '', '2', '0'), ('90', '晋中市', '4', '0', '', '2', '0'), ('91', '运城市', '4', '0', '', '2', '0'), ('92', '忻州市', '4', '0', '', '2', '0'), ('93', '临汾市', '4', '0', '', '2', '0'), ('94', '吕梁市', '4', '0', '', '2', '0'), ('95', '呼和浩特市', '5', '0', '', '2', '0'), ('96', '包头市', '5', '0', '', '2', '0'), ('97', '乌海市', '5', '0', '', '2', '0'), ('98', '赤峰市', '5', '0', '', '2', '0'), ('99', '通辽市', '5', '0', '', '2', '0'), ('100', '鄂尔多斯市', '5', '0', '', '2', '0'), ('101', '呼伦贝尔市', '5', '0', '', '2', '0'), ('102', '巴彦淖尔市', '5', '0', '', '2', '0'), ('103', '乌兰察布市', '5', '0', '', '2', '0'), ('104', '兴安盟', '5', '0', '', '2', '0'), ('105', '锡林郭勒盟', '5', '0', '', '2', '0'), ('106', '阿拉善盟', '5', '0', '', '2', '0'), ('107', '沈阳市', '6', '0', '', '2', '0'), ('108', '大连市', '6', '0', '', '2', '0'), ('109', '鞍山市', '6', '0', '', '2', '0'), ('110', '抚顺市', '6', '0', '', '2', '0'), ('111', '本溪市', '6', '0', '', '2', '0'), ('112', '丹东市', '6', '0', '', '2', '0'), ('113', '锦州市', '6', '0', '', '2', '0'), ('114', '营口市', '6', '0', '', '2', '0'), ('115', '阜新市', '6', '0', '', '2', '0'), ('116', '辽阳市', '6', '0', '', '2', '0'), ('117', '盘锦市', '6', '0', '', '2', '0'), ('118', '铁岭市', '6', '0', '', '2', '0'), ('119', '朝阳市', '6', '0', '', '2', '0'), ('120', '葫芦岛市', '6', '0', '', '2', '0'), ('121', '长春市', '7', '0', '', '2', '0'), ('122', '吉林市', '7', '0', '', '2', '0'), ('123', '四平市', '7', '0', '', '2', '0'), ('124', '辽源市', '7', '0', '', '2', '0'), ('125', '通化市', '7', '0', '', '2', '0'), ('126', '白山市', '7', '0', '', '2', '0'), ('127', '松原市', '7', '0', '', '2', '0'), ('128', '白城市', '7', '0', '', '2', '0'), ('129', '延边朝鲜族自治州', '7', '0', '', '2', '0'), ('130', '哈尔滨市', '8', '0', '', '2', '0'), ('131', '齐齐哈尔市', '8', '0', '', '2', '0'), ('132', '鸡西市', '8', '0', '', '2', '0'), ('133', '鹤岗市', '8', '0', '', '2', '0'), ('134', '双鸭山市', '8', '0', '', '2', '0'), ('135', '大庆市', '8', '0', '', '2', '0'), ('136', '伊春市', '8', '0', '', '2', '0'), ('137', '佳木斯市', '8', '0', '', '2', '0'), ('138', '七台河市', '8', '0', '', '2', '0'), ('139', '牡丹江市', '8', '0', '', '2', '0'), ('140', '黑河市', '8', '0', '', '2', '0'), ('141', '绥化市', '8', '0', '', '2', '0'), ('142', '大兴安岭地区', '8', '0', '', '2', '0'), ('143', '黄浦区', '39', '0', '', '3', '0'), ('144', '卢湾区', '39', '0', '', '3', '0'), ('145', '徐汇区', '39', '0', '', '3', '0'), ('146', '长宁区', '39', '0', '', '3', '0'), ('147', '静安区', '39', '0', '', '3', '0'), ('148', '普陀区', '39', '0', '', '3', '0'), ('149', '闸北区', '39', '0', '', '3', '0'), ('150', '虹口区', '39', '0', '', '3', '0'), ('151', '杨浦区', '39', '0', '', '3', '0'), ('152', '闵行区', '39', '0', '', '3', '0'), ('153', '宝山区', '39', '0', '', '3', '0'), ('154', '嘉定区', '39', '0', '', '3', '0'), ('155', '浦东新区', '39', '0', '', '3', '0'), ('156', '金山区', '39', '0', '', '3', '0'), ('157', '松江区', '39', '0', '', '3', '0'), ('158', '青浦区', '39', '0', '', '3', '0'), ('159', '南汇区', '39', '0', '', '3', '0'), ('160', '奉贤区', '39', '0', '', '3', '0'), ('161', '崇明县', '39', '0', '', '3', '0'), ('162', '南京市', '10', '0', '', '2', '0'), ('163', '无锡市', '10', '0', '', '2', '0'), ('164', '徐州市', '10', '0', '', '2', '0'), ('165', '常州市', '10', '0', '', '2', '0'), ('166', '苏州市', '10', '0', '', '2', '0'), ('167', '南通市', '10', '0', '', '2', '0'), ('168', '连云港市', '10', '0', '', '2', '0'), ('169', '淮安市', '10', '0', '', '2', '0'), ('170', '盐城市', '10', '0', '', '2', '0'), ('171', '扬州市', '10', '0', '', '2', '0'), ('172', '镇江市', '10', '0', '', '2', '0'), ('173', '泰州市', '10', '0', '', '2', '0'), ('174', '宿迁市', '10', '0', '', '2', '0'), ('175', '杭州市', '11', '0', '', '2', '0'), ('176', '宁波市', '11', '0', '', '2', '0'), ('177', '温州市', '11', '0', '', '2', '0'), ('178', '嘉兴市', '11', '0', '', '2', '0'), ('179', '湖州市', '11', '0', '', '2', '0'), ('180', '绍兴市', '11', '0', '', '2', '0'), ('181', '舟山市', '11', '0', '', '2', '0'), ('182', '衢州市', '11', '0', '', '2', '0'), ('183', '金华市', '11', '0', '', '2', '0'), ('184', '台州市', '11', '0', '', '2', '0'), ('185', '丽水市', '11', '0', '', '2', '0'), ('186', '合肥市', '12', '0', '', '2', '0'), ('187', '芜湖市', '12', '0', '', '2', '0'), ('188', '蚌埠市', '12', '0', '', '2', '0'), ('189', '淮南市', '12', '0', '', '2', '0'), ('190', '马鞍山市', '12', '0', '', '2', '0'), ('191', '淮北市', '12', '0', '', '2', '0'), ('192', '铜陵市', '12', '0', '', '2', '0'), ('193', '安庆市', '12', '0', '', '2', '0'), ('194', '黄山市', '12', '0', '', '2', '0'), ('195', '滁州市', '12', '0', '', '2', '0'), ('196', '阜阳市', '12', '0', '', '2', '0'), ('197', '宿州市', '12', '0', '', '2', '0'), ('198', '巢湖市', '12', '0', '', '2', '0'), ('199', '六安市', '12', '0', '', '2', '0'), ('200', '亳州市', '12', '0', '', '2', '0'), ('201', '池州市', '12', '0', '', '2', '0'), ('202', '宣城市', '12', '0', '', '2', '0'), ('203', '福州市', '13', '0', '', '2', '0'), ('204', '厦门市', '13', '0', '', '2', '0'), ('205', '莆田市', '13', '0', '', '2', '0'), ('206', '三明市', '13', '0', '', '2', '0'), ('207', '泉州市', '13', '0', '', '2', '0'), ('208', '漳州市', '13', '0', '', '2', '0'), ('209', '南平市', '13', '0', '', '2', '0'), ('210', '龙岩市', '13', '0', '', '2', '0'), ('211', '宁德市', '13', '0', '', '2', '0'), ('212', '南昌市', '14', '0', '', '2', '0'), ('213', '景德镇市', '14', '0', '', '2', '0'), ('214', '萍乡市', '14', '0', '', '2', '0'), ('215', '九江市', '14', '0', '', '2', '0'), ('216', '新余市', '14', '0', '', '2', '0'), ('217', '鹰潭市', '14', '0', '', '2', '0'), ('218', '赣州市', '14', '0', '', '2', '0'), ('219', '吉安市', '14', '0', '', '2', '0'), ('220', '宜春市', '14', '0', '', '2', '0'), ('221', '抚州市', '14', '0', '', '2', '0'), ('222', '上饶市', '14', '0', '', '2', '0'), ('223', '济南市', '15', '0', '', '2', '0'), ('224', '青岛市', '15', '0', '', '2', '0'), ('225', '淄博市', '15', '0', '', '2', '0'), ('226', '枣庄市', '15', '0', '', '2', '0'), ('227', '东营市', '15', '0', '', '2', '0'), ('228', '烟台市', '15', '0', '', '2', '0'), ('229', '潍坊市', '15', '0', '', '2', '0'), ('230', '济宁市', '15', '0', '', '2', '0'), ('231', '泰安市', '15', '0', '', '2', '0'), ('232', '威海市', '15', '0', '', '2', '0'), ('233', '日照市', '15', '0', '', '2', '0'), ('234', '莱芜市', '15', '0', '', '2', '0'), ('235', '临沂市', '15', '0', '', '2', '0'), ('236', '德州市', '15', '0', '', '2', '0'), ('237', '聊城市', '15', '0', '', '2', '0'), ('238', '滨州市', '15', '0', '', '2', '0'), ('239', '菏泽市', '15', '0', '', '2', '0'), ('240', '郑州市', '16', '0', '', '2', '0'), ('241', '开封市', '16', '0', '', '2', '0'), ('242', '洛阳市', '16', '0', '', '2', '0'), ('243', '平顶山市', '16', '0', '', '2', '0'), ('244', '安阳市', '16', '0', '', '2', '0'), ('245', '鹤壁市', '16', '0', '', '2', '0'), ('246', '新乡市', '16', '0', '', '2', '0'), ('247', '焦作市', '16', '0', '', '2', '0'), ('248', '濮阳市', '16', '0', '', '2', '0'), ('249', '许昌市', '16', '0', '', '2', '0'), ('250', '漯河市', '16', '0', '', '2', '0'), ('251', '三门峡市', '16', '0', '', '2', '0'), ('252', '南阳市', '16', '0', '', '2', '0'), ('253', '商丘市', '16', '0', '', '2', '0'), ('254', '信阳市', '16', '0', '', '2', '0'), ('255', '周口市', '16', '0', '', '2', '0'), ('256', '驻马店市', '16', '0', '', '2', '0'), ('257', '济源市', '16', '0', '', '2', '0'), ('258', '武汉市', '17', '0', '', '2', '0'), ('259', '黄石市', '17', '0', '', '2', '0'), ('260', '十堰市', '17', '0', '', '2', '0'), ('261', '宜昌市', '17', '0', '', '2', '0'), ('262', '襄樊市', '17', '0', '', '2', '0'), ('263', '鄂州市', '17', '0', '', '2', '0'), ('264', '荆门市', '17', '0', '', '2', '0'), ('265', '孝感市', '17', '0', '', '2', '0'), ('266', '荆州市', '17', '0', '', '2', '0'), ('267', '黄冈市', '17', '0', '', '2', '0'), ('268', '咸宁市', '17', '0', '', '2', '0'), ('269', '随州市', '17', '0', '', '2', '0'), ('270', '恩施土家族苗族自治州', '17', '0', '', '2', '0'), ('271', '仙桃市', '17', '0', '', '2', '0'), ('272', '潜江市', '17', '0', '', '2', '0'), ('273', '天门市', '17', '0', '', '2', '0'), ('274', '神农架林区', '17', '0', '', '2', '0'), ('275', '长沙市', '18', '0', '', '2', '0'), ('276', '株洲市', '18', '0', '', '2', '0'), ('277', '湘潭市', '18', '0', '', '2', '0'), ('278', '衡阳市', '18', '0', '', '2', '0'), ('279', '邵阳市', '18', '0', '', '2', '0'), ('280', '岳阳市', '18', '0', '', '2', '0'), ('281', '常德市', '18', '0', '', '2', '0'), ('282', '张家界市', '18', '0', '', '2', '0'), ('283', '益阳市', '18', '0', '', '2', '0'), ('284', '郴州市', '18', '0', '', '2', '0'), ('285', '永州市', '18', '0', '', '2', '0'), ('286', '怀化市', '18', '0', '', '2', '0'), ('287', '娄底市', '18', '0', '', '2', '0'), ('288', '湘西土家族苗族自治州', '18', '0', '', '2', '0'), ('289', '广州市', '19', '0', '', '2', '0'), ('290', '韶关市', '19', '0', '', '2', '0'), ('291', '深圳市', '19', '0', '', '2', '0'), ('292', '珠海市', '19', '0', '', '2', '0'), ('293', '汕头市', '19', '0', '', '2', '0'), ('294', '佛山市', '19', '0', '', '2', '0'), ('295', '江门市', '19', '0', '', '2', '0'), ('296', '湛江市', '19', '0', '', '2', '0'), ('297', '茂名市', '19', '0', '', '2', '0'), ('298', '肇庆市', '19', '0', '', '2', '0'), ('299', '惠州市', '19', '0', '', '2', '0'), ('300', '梅州市', '19', '0', '', '2', '0'), ('301', '汕尾市', '19', '0', '', '2', '0'), ('302', '河源市', '19', '0', '', '2', '0'), ('303', '阳江市', '19', '0', '', '2', '0'), ('304', '清远市', '19', '0', '', '2', '0'), ('305', '东莞市', '19', '0', '', '2', '0'), ('306', '中山市', '19', '0', '', '2', '0'), ('307', '潮州市', '19', '0', '', '2', '0'), ('308', '揭阳市', '19', '0', '', '2', '0'), ('309', '云浮市', '19', '0', '', '2', '0'), ('310', '南宁市', '20', '0', '', '2', '0'), ('311', '柳州市', '20', '0', '', '2', '0'), ('312', '桂林市', '20', '0', '', '2', '0'), ('313', '梧州市', '20', '0', '', '2', '0'), ('314', '北海市', '20', '0', '', '2', '0'), ('315', '防城港市', '20', '0', '', '2', '0'), ('316', '钦州市', '20', '0', '', '2', '0'), ('317', '贵港市', '20', '0', '', '2', '0'), ('318', '玉林市', '20', '0', '', '2', '0'), ('319', '百色市', '20', '0', '', '2', '0'), ('320', '贺州市', '20', '0', '', '2', '0'), ('321', '河池市', '20', '0', '', '2', '0'), ('322', '来宾市', '20', '0', '', '2', '0'), ('323', '崇左市', '20', '0', '', '2', '0'), ('324', '海口市', '21', '0', '', '2', '0'), ('325', '三亚市', '21', '0', '', '2', '0'), ('326', '五指山市', '21', '0', '', '2', '0'), ('327', '琼海市', '21', '0', '', '2', '0'), ('328', '儋州市', '21', '0', '', '2', '0'), ('329', '文昌市', '21', '0', '', '2', '0'), ('330', '万宁市', '21', '0', '', '2', '0'), ('331', '东方市', '21', '0', '', '2', '0'), ('332', '定安县', '21', '0', '', '2', '0'), ('333', '屯昌县', '21', '0', '', '2', '0'), ('334', '澄迈县', '21', '0', '', '2', '0'), ('335', '临高县', '21', '0', '', '2', '0'), ('336', '白沙黎族自治县', '21', '0', '', '2', '0'), ('337', '昌江黎族自治县', '21', '0', '', '2', '0'), ('338', '乐东黎族自治县', '21', '0', '', '2', '0'), ('339', '陵水黎族自治县', '21', '0', '', '2', '0'), ('340', '保亭黎族苗族自治县', '21', '0', '', '2', '0'), ('341', '琼中黎族苗族自治县', '21', '0', '', '2', '0'), ('342', '西沙群岛', '21', '0', '', '2', '0'), ('343', '南沙群岛', '21', '0', '', '2', '0'), ('344', '中沙群岛的岛礁及其海域', '21', '0', '', '2', '0'), ('345', '万州区', '62', '0', '', '3', '0'), ('346', '涪陵区', '62', '0', '', '3', '0'), ('347', '渝中区', '62', '0', '', '3', '0'), ('348', '大渡口区', '62', '0', '', '3', '0'), ('349', '江北区', '62', '0', '', '3', '0'), ('350', '沙坪坝区', '62', '0', '', '3', '0'), ('351', '九龙坡区', '62', '0', '', '3', '0'), ('352', '南岸区', '62', '0', '', '3', '0'), ('353', '北碚区', '62', '0', '', '3', '0'), ('354', '双桥区', '62', '0', '', '3', '0'), ('355', '万盛区', '62', '0', '', '3', '0'), ('356', '渝北区', '62', '0', '', '3', '0'), ('357', '巴南区', '62', '0', '', '3', '0'), ('358', '黔江区', '62', '0', '', '3', '0'), ('359', '长寿区', '62', '0', '', '3', '0'), ('360', '綦江县', '62', '0', '', '3', '0'), ('361', '潼南县', '62', '0', '', '3', '0'), ('362', '铜梁县', '62', '0', '', '3', '0'), ('363', '大足县', '62', '0', '', '3', '0'), ('364', '荣昌县', '62', '0', '', '3', '0'), ('365', '璧山县', '62', '0', '', '3', '0'), ('366', '梁平县', '62', '0', '', '3', '0'), ('367', '城口县', '62', '0', '', '3', '0'), ('368', '丰都县', '62', '0', '', '3', '0'), ('369', '垫江县', '62', '0', '', '3', '0'), ('370', '武隆县', '62', '0', '', '3', '0'), ('371', '忠县', '62', '0', '', '3', '0'), ('372', '开县', '62', '0', '', '3', '0'), ('373', '云阳县', '62', '0', '', '3', '0'), ('374', '奉节县', '62', '0', '', '3', '0'), ('375', '巫山县', '62', '0', '', '3', '0'), ('376', '巫溪县', '62', '0', '', '3', '0'), ('377', '石柱土家族自治县', '62', '0', '', '3', '0'), ('378', '秀山土家族苗族自治县', '62', '0', '', '3', '0'), ('379', '酉阳土家族苗族自治县', '62', '0', '', '3', '0'), ('380', '彭水苗族土家族自治县', '62', '0', '', '3', '0'), ('381', '江津市', '62', '0', '', '3', '0'), ('382', '合川市', '62', '0', '', '3', '0'), ('383', '永川市', '62', '0', '', '3', '0'), ('384', '南川市', '62', '0', '', '3', '0'), ('385', '成都市', '23', '0', '', '2', '0'), ('386', '自贡市', '23', '0', '', '2', '0'), ('387', '攀枝花市', '23', '0', '', '2', '0'), ('388', '泸州市', '23', '0', '', '2', '0'), ('389', '德阳市', '23', '0', '', '2', '0'), ('390', '绵阳市', '23', '0', '', '2', '0'), ('391', '广元市', '23', '0', '', '2', '0'), ('392', '遂宁市', '23', '0', '', '2', '0'), ('393', '内江市', '23', '0', '', '2', '0'), ('394', '乐山市', '23', '0', '', '2', '0'), ('395', '南充市', '23', '0', '', '2', '0'), ('396', '眉山市', '23', '0', '', '2', '0'), ('397', '宜宾市', '23', '0', '', '2', '0'), ('398', '广安市', '23', '0', '', '2', '0'), ('399', '达州市', '23', '0', '', '2', '0'), ('400', '雅安市', '23', '0', '', '2', '0'), ('401', '巴中市', '23', '0', '', '2', '0'), ('402', '资阳市', '23', '0', '', '2', '0'), ('403', '阿坝藏族羌族自治州', '23', '0', '', '2', '0'), ('404', '甘孜藏族自治州', '23', '0', '', '2', '0'), ('405', '凉山彝族自治州', '23', '0', '', '2', '0'), ('406', '贵阳市', '24', '0', '', '2', '0'), ('407', '六盘水市', '24', '0', '', '2', '0'), ('408', '遵义市', '24', '0', '', '2', '0'), ('409', '安顺市', '24', '0', '', '2', '0'), ('410', '铜仁地区', '24', '0', '', '2', '0'), ('411', '黔西南布依族苗族自治州', '24', '0', '', '2', '0'), ('412', '毕节地区', '24', '0', '', '2', '0'), ('413', '黔东南苗族侗族自治州', '24', '0', '', '2', '0'), ('414', '黔南布依族苗族自治州', '24', '0', '', '2', '0'), ('415', '昆明市', '25', '0', '', '2', '0'), ('416', '曲靖市', '25', '0', '', '2', '0'), ('417', '玉溪市', '25', '0', '', '2', '0'), ('418', '保山市', '25', '0', '', '2', '0'), ('419', '昭通市', '25', '0', '', '2', '0'), ('420', '丽江市', '25', '0', '', '2', '0'), ('421', '思茅市', '25', '0', '', '2', '0'), ('422', '临沧市', '25', '0', '', '2', '0'), ('423', '楚雄彝族自治州', '25', '0', '', '2', '0'), ('424', '红河哈尼族彝族自治州', '25', '0', '', '2', '0'), ('425', '文山壮族苗族自治州', '25', '0', '', '2', '0'), ('426', '西双版纳傣族自治州', '25', '0', '', '2', '0'), ('427', '大理白族自治州', '25', '0', '', '2', '0'), ('428', '德宏傣族景颇族自治州', '25', '0', '', '2', '0'), ('429', '怒江傈僳族自治州', '25', '0', '', '2', '0'), ('430', '迪庆藏族自治州', '25', '0', '', '2', '0'), ('431', '拉萨市', '26', '0', '', '2', '0'), ('432', '昌都地区', '26', '0', '', '2', '0'), ('433', '山南地区', '26', '0', '', '2', '0'), ('434', '日喀则地区', '26', '0', '', '2', '0'), ('435', '那曲地区', '26', '0', '', '2', '0'), ('436', '阿里地区', '26', '0', '', '2', '0'), ('437', '林芝地区', '26', '0', '', '2', '0'), ('438', '西安市', '27', '0', '', '2', '0'), ('439', '铜川市', '27', '0', '', '2', '0'), ('440', '宝鸡市', '27', '0', '', '2', '0'), ('441', '咸阳市', '27', '0', '', '2', '0'), ('442', '渭南市', '27', '0', '', '2', '0'), ('443', '延安市', '27', '0', '', '2', '0'), ('444', '汉中市', '27', '0', '', '2', '0'), ('445', '榆林市', '27', '0', '', '2', '0'), ('446', '安康市', '27', '0', '', '2', '0'), ('447', '商洛市', '27', '0', '', '2', '0'), ('448', '兰州市', '28', '0', '', '2', '0'), ('449', '嘉峪关市', '28', '0', '', '2', '0'), ('450', '金昌市', '28', '0', '', '2', '0'), ('451', '白银市', '28', '0', '', '2', '0'), ('452', '天水市', '28', '0', '', '2', '0'), ('453', '武威市', '28', '0', '', '2', '0'), ('454', '张掖市', '28', '0', '', '2', '0'), ('455', '平凉市', '28', '0', '', '2', '0'), ('456', '酒泉市', '28', '0', '', '2', '0'), ('457', '庆阳市', '28', '0', '', '2', '0'), ('458', '定西市', '28', '0', '', '2', '0'), ('459', '陇南市', '28', '0', '', '2', '0'), ('460', '临夏回族自治州', '28', '0', '', '2', '0'), ('461', '甘南藏族自治州', '28', '0', '', '2', '0'), ('462', '西宁市', '29', '0', '', '2', '0'), ('463', '海东地区', '29', '0', '', '2', '0'), ('464', '海北藏族自治州', '29', '0', '', '2', '0'), ('465', '黄南藏族自治州', '29', '0', '', '2', '0'), ('466', '海南藏族自治州', '29', '0', '', '2', '0'), ('467', '果洛藏族自治州', '29', '0', '', '2', '0'), ('468', '玉树藏族自治州', '29', '0', '', '2', '0'), ('469', '海西蒙古族藏族自治州', '29', '0', '', '2', '0'), ('470', '银川市', '30', '0', '', '2', '0'), ('471', '石嘴山市', '30', '0', '', '2', '0'), ('472', '吴忠市', '30', '0', '', '2', '0'), ('473', '固原市', '30', '0', '', '2', '0'), ('474', '中卫市', '30', '0', '', '2', '0'), ('475', '乌鲁木齐市', '31', '0', '', '2', '0'), ('476', '克拉玛依市', '31', '0', '', '2', '0'), ('477', '吐鲁番地区', '31', '0', '', '2', '0'), ('478', '哈密地区', '31', '0', '', '2', '0'), ('479', '昌吉回族自治州', '31', '0', '', '2', '0'), ('480', '博尔塔拉蒙古自治州', '31', '0', '', '2', '0'), ('481', '巴音郭楞蒙古自治州', '31', '0', '', '2', '0'), ('482', '阿克苏地区', '31', '0', '', '2', '0'), ('483', '克孜勒苏柯尔克孜自治州', '31', '0', '', '2', '0'), ('484', '喀什地区', '31', '0', '', '2', '0'), ('485', '和田地区', '31', '0', '', '2', '0'), ('486', '伊犁哈萨克自治州', '31', '0', '', '2', '0'), ('487', '塔城地区', '31', '0', '', '2', '0'), ('488', '阿勒泰地区', '31', '0', '', '2', '0'), ('489', '石河子市', '31', '0', '', '2', '0'), ('490', '阿拉尔市', '31', '0', '', '2', '0'), ('491', '图木舒克市', '31', '0', '', '2', '0'), ('492', '五家渠市', '31', '0', '', '2', '0'), ('493', '台北市', '32', '0', '', '2', '0'), ('494', '高雄市', '32', '0', '', '2', '0'), ('495', '基隆市', '32', '0', '', '2', '0'), ('496', '台中市', '32', '0', '', '2', '0'), ('497', '台南市', '32', '0', '', '2', '0'), ('498', '新竹市', '32', '0', '', '2', '0'), ('499', '嘉义市', '32', '0', '', '2', '0'), ('500', '台北县', '32', '0', '', '2', '0'), ('501', '宜兰县', '32', '0', '', '2', '0'), ('502', '桃园县', '32', '0', '', '2', '0'), ('503', '新竹县', '32', '0', '', '2', '0'), ('504', '苗栗县', '32', '0', '', '2', '0'), ('505', '台中县', '32', '0', '', '2', '0'), ('506', '彰化县', '32', '0', '', '2', '0'), ('507', '南投县', '32', '0', '', '2', '0'), ('508', '云林县', '32', '0', '', '2', '0'), ('509', '嘉义县', '32', '0', '', '2', '0'), ('510', '台南县', '32', '0', '', '2', '0'), ('511', '高雄县', '32', '0', '', '2', '0'), ('512', '屏东县', '32', '0', '', '2', '0'), ('513', '澎湖县', '32', '0', '', '2', '0'), ('514', '台东县', '32', '0', '', '2', '0'), ('515', '花莲县', '32', '0', '', '2', '0'), ('516', '中西区', '33', '0', '', '2', '0'), ('517', '东区', '33', '0', '', '2', '0'), ('518', '九龙城区', '33', '0', '', '2', '0'), ('519', '观塘区', '33', '0', '', '2', '0'), ('520', '南区', '33', '0', '', '2', '0'), ('521', '深水埗区', '33', '0', '', '2', '0'), ('522', '黄大仙区', '33', '0', '', '2', '0'), ('523', '湾仔区', '33', '0', '', '2', '0'), ('524', '油尖旺区', '33', '0', '', '2', '0'), ('525', '离岛区', '33', '0', '', '2', '0'), ('526', '葵青区', '33', '0', '', '2', '0'), ('527', '北区', '33', '0', '', '2', '0'), ('528', '西贡区', '33', '0', '', '2', '0'), ('529', '沙田区', '33', '0', '', '2', '0'), ('530', '屯门区', '33', '0', '', '2', '0'), ('531', '大埔区', '33', '0', '', '2', '0'), ('532', '荃湾区', '33', '0', '', '2', '0'), ('533', '元朗区', '33', '0', '', '2', '0'), ('534', '澳门特别行政区', '34', '0', '', '2', '0'), ('535', '美国', '45055', '0', '', '3', '0'), ('536', '加拿大', '45055', '0', '', '3', '0'), ('537', '澳大利亚', '45055', '0', '', '3', '0'), ('538', '新西兰', '45055', '0', '', '3', '0'), ('539', '英国', '45055', '0', '', '3', '0'), ('540', '法国', '45055', '0', '', '3', '0'), ('541', '德国', '45055', '0', '', '3', '0'), ('542', '捷克', '45055', '0', '', '3', '0'), ('543', '荷兰', '45055', '0', '', '3', '0'), ('544', '瑞士', '45055', '0', '', '3', '0'), ('545', '希腊', '45055', '0', '', '3', '0'), ('546', '挪威', '45055', '0', '', '3', '0'), ('547', '瑞典', '45055', '0', '', '3', '0'), ('548', '丹麦', '45055', '0', '', '3', '0'), ('549', '芬兰', '45055', '0', '', '3', '0'), ('550', '爱尔兰', '45055', '0', '', '3', '0'), ('551', '奥地利', '45055', '0', '', '3', '0'), ('552', '意大利', '45055', '0', '', '3', '0'), ('553', '乌克兰', '45055', '0', '', '3', '0'), ('554', '俄罗斯', '45055', '0', '', '3', '0'), ('555', '西班牙', '45055', '0', '', '3', '0'), ('556', '韩国', '45055', '0', '', '3', '0'), ('557', '新加坡', '45055', '0', '', '3', '0'), ('558', '马来西亚', '45055', '0', '', '3', '0'), ('559', '印度', '45055', '0', '', '3', '0'), ('560', '泰国', '45055', '0', '', '3', '0'), ('561', '日本', '45055', '0', '', '3', '0'), ('562', '巴西', '45055', '0', '', '3', '0'), ('563', '阿根廷', '45055', '0', '', '3', '0'), ('564', '南非', '45055', '0', '', '3', '0'), ('565', '埃及', '45055', '0', '', '3', '0'), ('566', '其他', '36', '0', '', '3', '0'), ('1126', '井陉县', '73', '0', '', '3', '0'), ('1127', '井陉矿区', '73', '0', '', '3', '0'), ('1128', '元氏县', '73', '0', '', '3', '0'), ('1129', '平山县', '73', '0', '', '3', '0'), ('1130', '新乐市', '73', '0', '', '3', '0'), ('1131', '新华区', '73', '0', '', '3', '0'), ('1132', '无极县', '73', '0', '', '3', '0'), ('1133', '晋州市', '73', '0', '', '3', '0'), ('1134', '栾城县', '73', '0', '', '3', '0'), ('1135', '桥东区', '73', '0', '', '3', '0'), ('1136', '桥西区', '73', '0', '', '3', '0'), ('1137', '正定县', '73', '0', '', '3', '0'), ('1138', '深泽县', '73', '0', '', '3', '0'), ('1139', '灵寿县', '73', '0', '', '3', '0'), ('1140', '藁城市', '73', '0', '', '3', '0'), ('1141', '行唐县', '73', '0', '', '3', '0'), ('1142', '裕华区', '73', '0', '', '3', '0'), ('1143', '赞皇县', '73', '0', '', '3', '0'), ('1144', '赵县', '73', '0', '', '3', '0'), ('1145', '辛集市', '73', '0', '', '3', '0'), ('1146', '长安区', '73', '0', '', '3', '0'), ('1147', '高邑县', '73', '0', '', '3', '0'), ('1148', '鹿泉市', '73', '0', '', '3', '0'), ('1149', '丰南区', '74', '0', '', '3', '0'), ('1150', '丰润区', '74', '0', '', '3', '0'), ('1151', '乐亭县', '74', '0', '', '3', '0'), ('1152', '古冶区', '74', '0', '', '3', '0'), ('1153', '唐海县', '74', '0', '', '3', '0'), ('1154', '开平区', '74', '0', '', '3', '0'), ('1155', '滦南县', '74', '0', '', '3', '0'), ('1156', '滦县', '74', '0', '', '3', '0'), ('1157', '玉田县', '74', '0', '', '3', '0'), ('1158', '路北区', '74', '0', '', '3', '0'), ('1159', '路南区', '74', '0', '', '3', '0'), ('1160', '迁安市', '74', '0', '', '3', '0'), ('1161', '迁西县', '74', '0', '', '3', '0'), ('1162', '遵化市', '74', '0', '', '3', '0'), ('1163', '北戴河区', '75', '0', '', '3', '0'), ('1164', '卢龙县', '75', '0', '', '3', '0'), ('1165', '山海关区', '75', '0', '', '3', '0'), ('1166', '抚宁县', '75', '0', '', '3', '0'), ('1167', '昌黎县', '75', '0', '', '3', '0'), ('1168', '海港区', '75', '0', '', '3', '0'), ('1169', '青龙满族自治县', '75', '0', '', '3', '0'), ('1170', '丛台区', '76', '0', '', '3', '0'), ('1171', '临漳县', '76', '0', '', '3', '0'), ('1172', '复兴区', '76', '0', '', '3', '0'), ('1173', '大名县', '76', '0', '', '3', '0'), ('1174', '峰峰矿区', '76', '0', '', '3', '0'), ('1175', '广平县', '76', '0', '', '3', '0'), ('1176', '成安县', '76', '0', '', '3', '0'), ('1177', '曲周县', '76', '0', '', '3', '0'), ('1178', '武安市', '76', '0', '', '3', '0'), ('1179', '永年县', '76', '0', '', '3', '0'), ('1180', '涉县', '76', '0', '', '3', '0'), ('1181', '磁县', '76', '0', '', '3', '0'), ('1182', '肥乡县', '76', '0', '', '3', '0'), ('1183', '邯山区', '76', '0', '', '3', '0'), ('1184', '邯郸县', '76', '0', '', '3', '0'), ('1185', '邱县', '76', '0', '', '3', '0'), ('1186', '馆陶县', '76', '0', '', '3', '0'), ('1187', '魏县', '76', '0', '', '3', '0'), ('1188', '鸡泽县', '76', '0', '', '3', '0'), ('1189', '临城县', '77', '0', '', '3', '0'), ('1190', '临西县', '77', '0', '', '3', '0'), ('1191', '任县', '77', '0', '', '3', '0'), ('1192', '内丘县', '77', '0', '', '3', '0'), ('1193', '南和县', '77', '0', '', '3', '0'), ('1194', '南宫市', '77', '0', '', '3', '0'), ('1195', '威县', '77', '0', '', '3', '0'), ('1196', '宁晋县', '77', '0', '', '3', '0'), ('1197', '巨鹿县', '77', '0', '', '3', '0'), ('1198', '平乡县', '77', '0', '', '3', '0'), ('1199', '广宗县', '77', '0', '', '3', '0'), ('1200', '新河县', '77', '0', '', '3', '0'), ('1201', '柏乡县', '77', '0', '', '3', '0'), ('1202', '桥东区', '77', '0', '', '3', '0'), ('1203', '桥西区', '77', '0', '', '3', '0'), ('1204', '沙河市', '77', '0', '', '3', '0'), ('1205', '清河县', '77', '0', '', '3', '0'), ('1206', '邢台县', '77', '0', '', '3', '0'), ('1207', '隆尧县', '77', '0', '', '3', '0'), ('1208', '北市区', '78', '0', '', '3', '0'), ('1209', '南市区', '78', '0', '', '3', '0'), ('1210', '博野县', '78', '0', '', '3', '0'), ('1211', '唐县', '78', '0', '', '3', '0'), ('1212', '安国市', '78', '0', '', '3', '0'), ('1213', '安新县', '78', '0', '', '3', '0'), ('1214', '定兴县', '78', '0', '', '3', '0'), ('1215', '定州市', '78', '0', '', '3', '0'), ('1216', '容城县', '78', '0', '', '3', '0'), ('1217', '徐水县', '78', '0', '', '3', '0'), ('1218', '新市区', '78', '0', '', '3', '0'), ('1219', '易县', '78', '0', '', '3', '0'), ('1220', '曲阳县', '78', '0', '', '3', '0'), ('1221', '望都县', '78', '0', '', '3', '0'), ('1222', '涞水县', '78', '0', '', '3', '0'), ('1223', '涞源县', '78', '0', '', '3', '0'), ('1224', '涿州市', '78', '0', '', '3', '0'), ('1225', '清苑县', '78', '0', '', '3', '0'), ('1226', '满城县', '78', '0', '', '3', '0'), ('1227', '蠡县', '78', '0', '', '3', '0'), ('1228', '阜平县', '78', '0', '', '3', '0'), ('1229', '雄县', '78', '0', '', '3', '0'), ('1230', '顺平县', '78', '0', '', '3', '0'), ('1231', '高碑店市', '78', '0', '', '3', '0'), ('1232', '高阳县', '78', '0', '', '3', '0'), ('1233', '万全县', '79', '0', '', '3', '0'), ('1234', '下花园区', '79', '0', '', '3', '0'), ('1235', '宣化区', '79', '0', '', '3', '0'), ('1236', '宣化县', '79', '0', '', '3', '0'), ('1237', '尚义县', '79', '0', '', '3', '0'), ('1238', '崇礼县', '79', '0', '', '3', '0'), ('1239', '康保县', '79', '0', '', '3', '0'), ('1240', '张北县', '79', '0', '', '3', '0'), ('1241', '怀安县', '79', '0', '', '3', '0'), ('1242', '怀来县', '79', '0', '', '3', '0'), ('1243', '桥东区', '79', '0', '', '3', '0'), ('1244', '桥西区', '79', '0', '', '3', '0'), ('1245', '沽源县', '79', '0', '', '3', '0'), ('1246', '涿鹿县', '79', '0', '', '3', '0'), ('1247', '蔚县', '79', '0', '', '3', '0'), ('1248', '赤城县', '79', '0', '', '3', '0'), ('1249', '阳原县', '79', '0', '', '3', '0'), ('1250', '丰宁满族自治县', '80', '0', '', '3', '0'), ('1251', '兴隆县', '80', '0', '', '3', '0'), ('1252', '双桥区', '80', '0', '', '3', '0'), ('1253', '双滦区', '80', '0', '', '3', '0'), ('1254', '围场满族蒙古族自治县', '80', '0', '', '3', '0'), ('1255', '宽城满族自治县', '80', '0', '', '3', '0'), ('1256', '平泉县', '80', '0', '', '3', '0'), ('1257', '承德县', '80', '0', '', '3', '0'), ('1258', '滦平县', '80', '0', '', '3', '0'), ('1259', '隆化县', '80', '0', '', '3', '0'), ('1260', '鹰手营子矿区', '80', '0', '', '3', '0'), ('1261', '冀州市', '81', '0', '', '3', '0'), ('1262', '安平县', '81', '0', '', '3', '0'), ('1263', '故城县', '81', '0', '', '3', '0'), ('1264', '景县', '81', '0', '', '3', '0'), ('1265', '枣强县', '81', '0', '', '3', '0'), ('1266', '桃城区', '81', '0', '', '3', '0'), ('1267', '武强县', '81', '0', '', '3', '0'), ('1268', '武邑县', '81', '0', '', '3', '0'), ('1269', '深州市', '81', '0', '', '3', '0'), ('1270', '阜城县', '81', '0', '', '3', '0'), ('1271', '饶阳县', '81', '0', '', '3', '0'), ('1272', '三河市', '82', '0', '', '3', '0'), ('1273', '固安县', '82', '0', '', '3', '0'), ('1274', '大厂回族自治县', '82', '0', '', '3', '0'), ('1275', '大城县', '82', '0', '', '3', '0'), ('1276', '安次区', '82', '0', '', '3', '0'), ('1277', '广阳区', '82', '0', '', '3', '0'), ('1278', '文安县', '82', '0', '', '3', '0'), ('1279', '永清县', '82', '0', '', '3', '0'), ('1280', '霸州市', '82', '0', '', '3', '0'), ('1281', '香河县', '82', '0', '', '3', '0'), ('1282', '东光县', '83', '0', '', '3', '0'), ('1283', '任丘市', '83', '0', '', '3', '0'), ('1284', '南皮县', '83', '0', '', '3', '0'), ('1285', '吴桥县', '83', '0', '', '3', '0'), ('1286', '孟村回族自治县', '83', '0', '', '3', '0'), ('1287', '新华区', '83', '0', '', '3', '0'), ('1288', '沧县', '83', '0', '', '3', '0'), ('1289', '河间市', '83', '0', '', '3', '0'), ('1290', '泊头市', '83', '0', '', '3', '0'), ('1291', '海兴县', '83', '0', '', '3', '0'), ('1292', '献县', '83', '0', '', '3', '0'), ('1293', '盐山县', '83', '0', '', '3', '0'), ('1294', '肃宁县', '83', '0', '', '3', '0'), ('1295', '运河区', '83', '0', '', '3', '0'), ('1296', '青县', '83', '0', '', '3', '0'), ('1297', '黄骅市', '83', '0', '', '3', '0'), ('1298', '万柏林区', '84', '0', '', '3', '0'), ('1299', '古交市', '84', '0', '', '3', '0'), ('1300', '娄烦县', '84', '0', '', '3', '0'), ('1301', '小店区', '84', '0', '', '3', '0'), ('1302', '尖草坪区', '84', '0', '', '3', '0'), ('1303', '晋源区', '84', '0', '', '3', '0'), ('1304', '杏花岭区', '84', '0', '', '3', '0'), ('1305', '清徐县', '84', '0', '', '3', '0'), ('1306', '迎泽区', '84', '0', '', '3', '0'), ('1307', '阳曲县', '84', '0', '', '3', '0'), ('1308', '南郊区', '85', '0', '', '3', '0'), ('1309', '城区', '85', '0', '', '3', '0'), ('1310', '大同县', '85', '0', '', '3', '0'), ('1311', '天镇县', '85', '0', '', '3', '0'), ('1312', '左云县', '85', '0', '', '3', '0'), ('1313', '广灵县', '85', '0', '', '3', '0'), ('1314', '新荣区', '85', '0', '', '3', '0'), ('1315', '浑源县', '85', '0', '', '3', '0'), ('1316', '灵丘县', '85', '0', '', '3', '0'), ('1317', '矿区', '85', '0', '', '3', '0'), ('1318', '阳高县', '85', '0', '', '3', '0'), ('1319', '城区', '86', '0', '', '3', '0'), ('1320', '平定县', '86', '0', '', '3', '0'), ('1321', '盂县', '86', '0', '', '3', '0'), ('1322', '矿区', '86', '0', '', '3', '0'), ('1323', '郊区', '86', '0', '', '3', '0'), ('1324', '城区', '87', '0', '', '3', '0'), ('1325', '壶关县', '87', '0', '', '3', '0'), ('1326', '屯留县', '87', '0', '', '3', '0'), ('1327', '平顺县', '87', '0', '', '3', '0'), ('1328', '武乡县', '87', '0', '', '3', '0'), ('1329', '沁县', '87', '0', '', '3', '0'), ('1330', '沁源县', '87', '0', '', '3', '0'), ('1331', '潞城市', '87', '0', '', '3', '0'), ('1332', '襄垣县', '87', '0', '', '3', '0'), ('1333', '郊区', '87', '0', '', '3', '0'), ('1334', '长子县', '87', '0', '', '3', '0'), ('1335', '长治县', '87', '0', '', '3', '0'), ('1336', '黎城县', '87', '0', '', '3', '0'), ('1337', '城区', '88', '0', '', '3', '0'), ('1338', '沁水县', '88', '0', '', '3', '0'), ('1339', '泽州县', '88', '0', '', '3', '0'), ('1340', '阳城县', '88', '0', '', '3', '0'), ('1341', '陵川县', '88', '0', '', '3', '0'), ('1342', '高平市', '88', '0', '', '3', '0'), ('1343', '右玉县', '89', '0', '', '3', '0'), ('1344', '山阴县', '89', '0', '', '3', '0'), ('1345', '平鲁区', '89', '0', '', '3', '0'), ('1346', '应县', '89', '0', '', '3', '0'), ('1347', '怀仁县', '89', '0', '', '3', '0'), ('1348', '朔城区', '89', '0', '', '3', '0'), ('1349', '介休市', '90', '0', '', '3', '0'), ('1350', '和顺县', '90', '0', '', '3', '0'), ('1351', '太谷县', '90', '0', '', '3', '0'), ('1352', '寿阳县', '90', '0', '', '3', '0'), ('1353', '左权县', '90', '0', '', '3', '0'), ('1354', '平遥县', '90', '0', '', '3', '0'), ('1355', '昔阳县', '90', '0', '', '3', '0'), ('1356', '榆次区', '90', '0', '', '3', '0'), ('1357', '榆社县', '90', '0', '', '3', '0'), ('1358', '灵石县', '90', '0', '', '3', '0'), ('1359', '祁县', '90', '0', '', '3', '0'), ('1360', '万荣县', '91', '0', '', '3', '0'), ('1361', '临猗县', '91', '0', '', '3', '0'), ('1362', '垣曲县', '91', '0', '', '3', '0'), ('1363', '夏县', '91', '0', '', '3', '0'), ('1364', '平陆县', '91', '0', '', '3', '0'), ('1365', '新绛县', '91', '0', '', '3', '0'), ('1366', '永济市', '91', '0', '', '3', '0'), ('1367', '河津市', '91', '0', '', '3', '0'), ('1368', '盐湖区', '91', '0', '', '3', '0'), ('1369', '稷山县', '91', '0', '', '3', '0'), ('1370', '绛县', '91', '0', '', '3', '0'), ('1371', '芮城县', '91', '0', '', '3', '0'), ('1372', '闻喜县', '91', '0', '', '3', '0'), ('1373', '五台县', '92', '0', '', '3', '0'), ('1374', '五寨县', '92', '0', '', '3', '0'), ('1375', '代县', '92', '0', '', '3', '0'), ('1376', '保德县', '92', '0', '', '3', '0'), ('1377', '偏关县', '92', '0', '', '3', '0'), ('1378', '原平市', '92', '0', '', '3', '0'), ('1379', '宁武县', '92', '0', '', '3', '0'), ('1380', '定襄县', '92', '0', '', '3', '0'), ('1381', '岢岚县', '92', '0', '', '3', '0'), ('1382', '忻府区', '92', '0', '', '3', '0'), ('1383', '河曲县', '92', '0', '', '3', '0'), ('1384', '神池县', '92', '0', '', '3', '0'), ('1385', '繁峙县', '92', '0', '', '3', '0'), ('1386', '静乐县', '92', '0', '', '3', '0'), ('1387', '乡宁县', '93', '0', '', '3', '0'), ('1388', '侯马市', '93', '0', '', '3', '0'), ('1389', '古县', '93', '0', '', '3', '0'), ('1390', '吉县', '93', '0', '', '3', '0'), ('1391', '大宁县', '93', '0', '', '3', '0'), ('1392', '安泽县', '93', '0', '', '3', '0'), ('1393', '尧都区', '93', '0', '', '3', '0'), ('1394', '曲沃县', '93', '0', '', '3', '0'), ('1395', '永和县', '93', '0', '', '3', '0'), ('1396', '汾西县', '93', '0', '', '3', '0'), ('1397', '洪洞县', '93', '0', '', '3', '0'), ('1398', '浮山县', '93', '0', '', '3', '0'), ('1399', '翼城县', '93', '0', '', '3', '0'), ('1400', '蒲县', '93', '0', '', '3', '0'), ('1401', '襄汾县', '93', '0', '', '3', '0'), ('1402', '隰县', '93', '0', '', '3', '0'), ('1403', '霍州市', '93', '0', '', '3', '0'), ('1404', '中阳县', '94', '0', '', '3', '0'), ('1405', '临县', '94', '0', '', '3', '0'), ('1406', '交口县', '94', '0', '', '3', '0'), ('1407', '交城县', '94', '0', '', '3', '0'), ('1408', '兴县', '94', '0', '', '3', '0'), ('1409', '孝义市', '94', '0', '', '3', '0'), ('1410', '岚县', '94', '0', '', '3', '0'), ('1411', '文水县', '94', '0', '', '3', '0'), ('1412', '方山县', '94', '0', '', '3', '0'), ('1413', '柳林县', '94', '0', '', '3', '0'), ('1414', '汾阳市', '94', '0', '', '3', '0'), ('1415', '石楼县', '94', '0', '', '3', '0'), ('1416', '离石区', '94', '0', '', '3', '0'), ('1417', '和林格尔县', '95', '0', '', '3', '0'), ('1418', '回民区', '95', '0', '', '3', '0'), ('1419', '土默特左旗', '95', '0', '', '3', '0'), ('1420', '托克托县', '95', '0', '', '3', '0'), ('1421', '新城区', '95', '0', '', '3', '0'), ('1422', '武川县', '95', '0', '', '3', '0'), ('1423', '清水河县', '95', '0', '', '3', '0'), ('1424', '玉泉区', '95', '0', '', '3', '0'), ('1425', '赛罕区', '95', '0', '', '3', '0'), ('1426', '东河区', '96', '0', '', '3', '0'), ('1427', '九原区', '96', '0', '', '3', '0'), ('1428', '固阳县', '96', '0', '', '3', '0'), ('1429', '土默特右旗', '96', '0', '', '3', '0'), ('1430', '昆都仑区', '96', '0', '', '3', '0'), ('1431', '白云矿区', '96', '0', '', '3', '0'), ('1432', '石拐区', '96', '0', '', '3', '0'), ('1433', '达尔罕茂明安联合旗', '96', '0', '', '3', '0'), ('1434', '青山区', '96', '0', '', '3', '0'), ('1435', '乌达区', '97', '0', '', '3', '0'), ('1436', '海勃湾区', '97', '0', '', '3', '0'), ('1437', '海南区', '97', '0', '', '3', '0'), ('1438', '元宝山区', '98', '0', '', '3', '0'), ('1439', '克什克腾旗', '98', '0', '', '3', '0'), ('1440', '喀喇沁旗', '98', '0', '', '3', '0'), ('1441', '宁城县', '98', '0', '', '3', '0'), ('1442', '巴林右旗', '98', '0', '', '3', '0'), ('1443', '巴林左旗', '98', '0', '', '3', '0'), ('1444', '敖汉旗', '98', '0', '', '3', '0'), ('1445', '松山区', '98', '0', '', '3', '0'), ('1446', '林西县', '98', '0', '', '3', '0'), ('1447', '红山区', '98', '0', '', '3', '0'), ('1448', '翁牛特旗', '98', '0', '', '3', '0'), ('1449', '阿鲁科尔沁旗', '98', '0', '', '3', '0'), ('1450', '奈曼旗', '99', '0', '', '3', '0'), ('1451', '库伦旗', '99', '0', '', '3', '0'), ('1452', '开鲁县', '99', '0', '', '3', '0'), ('1453', '扎鲁特旗', '99', '0', '', '3', '0'), ('1454', '科尔沁区', '99', '0', '', '3', '0'), ('1455', '科尔沁左翼中旗', '99', '0', '', '3', '0'), ('1456', '科尔沁左翼后旗', '99', '0', '', '3', '0'), ('1457', '霍林郭勒市', '99', '0', '', '3', '0'), ('1458', '东胜区', '100', '0', '', '3', '0'), ('1459', '乌审旗', '100', '0', '', '3', '0'), ('1460', '伊金霍洛旗', '100', '0', '', '3', '0'), ('1461', '准格尔旗', '100', '0', '', '3', '0'), ('1462', '杭锦旗', '100', '0', '', '3', '0'), ('1463', '达拉特旗', '100', '0', '', '3', '0'), ('1464', '鄂东胜区', '100', '0', '', '3', '0'), ('1465', '鄂托克前旗', '100', '0', '', '3', '0'), ('1466', '鄂托克旗', '100', '0', '', '3', '0'), ('1467', '扎兰屯市', '101', '0', '', '3', '0'), ('1468', '新巴尔虎右旗', '101', '0', '', '3', '0'), ('1469', '新巴尔虎左旗', '101', '0', '', '3', '0'), ('1470', '根河市', '101', '0', '', '3', '0'), ('1471', '海拉尔区', '101', '0', '', '3', '0'), ('1472', '满洲里市', '101', '0', '', '3', '0'), ('1473', '牙克石市', '101', '0', '', '3', '0'), ('1474', '莫力达瓦达斡尔族自治旗', '101', '0', '', '3', '0'), ('1475', '鄂伦春自治旗', '101', '0', '', '3', '0'), ('1476', '鄂温克族自治旗', '101', '0', '', '3', '0'), ('1477', '阿荣旗', '101', '0', '', '3', '0'), ('1478', '陈巴尔虎旗', '101', '0', '', '3', '0'), ('1479', '额尔古纳市', '101', '0', '', '3', '0'), ('1480', '临河区', '102', '0', '', '3', '0'), ('1481', '乌拉特中旗', '102', '0', '', '3', '0'), ('1482', '乌拉特前旗', '102', '0', '', '3', '0'), ('1483', '乌拉特后旗', '102', '0', '', '3', '0'), ('1484', '五原县', '102', '0', '', '3', '0'), ('1485', '杭锦后旗', '102', '0', '', '3', '0'), ('1486', '磴口县', '102', '0', '', '3', '0'), ('1487', '丰镇市', '103', '0', '', '3', '0'), ('1488', '兴和县', '103', '0', '', '3', '0'), ('1489', '凉城县', '103', '0', '', '3', '0'), ('1490', '化德县', '103', '0', '', '3', '0'), ('1491', '卓资县', '103', '0', '', '3', '0'), ('1492', '商都县', '103', '0', '', '3', '0'), ('1493', '四子王旗', '103', '0', '', '3', '0'), ('1494', '察哈尔右翼中旗', '103', '0', '', '3', '0'), ('1495', '察哈尔右翼前旗', '103', '0', '', '3', '0'), ('1496', '察哈尔右翼后旗', '103', '0', '', '3', '0'), ('1497', '集宁区', '103', '0', '', '3', '0'), ('1498', '乌兰浩特市', '104', '0', '', '3', '0'), ('1499', '扎赉特旗', '104', '0', '', '3', '0'), ('1500', '科尔沁右翼中旗', '104', '0', '', '3', '0'), ('1501', '科尔沁右翼前旗', '104', '0', '', '3', '0'), ('1502', '突泉县', '104', '0', '', '3', '0'), ('1503', '阿尔山市', '104', '0', '', '3', '0'), ('1504', '东乌珠穆沁旗', '105', '0', '', '3', '0'), ('1505', '二连浩特市', '105', '0', '', '3', '0'), ('1506', '多伦县', '105', '0', '', '3', '0'), ('1507', '太仆寺旗', '105', '0', '', '3', '0'), ('1508', '正蓝旗', '105', '0', '', '3', '0'), ('1509', '正镶白旗', '105', '0', '', '3', '0'), ('1510', '苏尼特右旗', '105', '0', '', '3', '0'), ('1511', '苏尼特左旗', '105', '0', '', '3', '0'), ('1512', '西乌珠穆沁旗', '105', '0', '', '3', '0'), ('1513', '锡林浩特市', '105', '0', '', '3', '0'), ('1514', '镶黄旗', '105', '0', '', '3', '0'), ('1515', '阿巴嘎旗', '105', '0', '', '3', '0'), ('1516', '阿拉善右旗', '106', '0', '', '3', '0'), ('1517', '阿拉善左旗', '106', '0', '', '3', '0'), ('1518', '额济纳旗', '106', '0', '', '3', '0'), ('1519', '东陵区', '107', '0', '', '3', '0'), ('1520', '于洪区', '107', '0', '', '3', '0'), ('1521', '和平区', '107', '0', '', '3', '0'), ('1522', '大东区', '107', '0', '', '3', '0'), ('1523', '康平县', '107', '0', '', '3', '0'), ('1524', '新民市', '107', '0', '', '3', '0'), ('1525', '沈北新区', '107', '0', '', '3', '0'), ('1526', '沈河区', '107', '0', '', '3', '0'), ('1527', '法库县', '107', '0', '', '3', '0'), ('1528', '皇姑区', '107', '0', '', '3', '0'), ('1529', '苏家屯区', '107', '0', '', '3', '0'), ('1530', '辽中县', '107', '0', '', '3', '0'), ('1531', '铁西区', '107', '0', '', '3', '0'), ('1532', '中山区', '108', '0', '', '3', '0'), ('1533', '庄河市', '108', '0', '', '3', '0'), ('1534', '旅顺口区', '108', '0', '', '3', '0'), ('1535', '普兰店市', '108', '0', '', '3', '0'), ('1536', '沙河口区', '108', '0', '', '3', '0'), ('1537', '瓦房店市', '108', '0', '', '3', '0'), ('1538', '甘井子区', '108', '0', '', '3', '0'), ('1539', '西岗区', '108', '0', '', '3', '0'), ('1540', '金州区', '108', '0', '', '3', '0'), ('1541', '长海县', '108', '0', '', '3', '0'), ('1542', '千山区', '109', '0', '', '3', '0'), ('1543', '台安县', '109', '0', '', '3', '0'), ('1544', '岫岩满族自治县', '109', '0', '', '3', '0'), ('1545', '海城市', '109', '0', '', '3', '0'), ('1546', '立山区', '109', '0', '', '3', '0'), ('1547', '铁东区', '109', '0', '', '3', '0'), ('1548', '铁西区', '109', '0', '', '3', '0'), ('1549', '东洲区', '110', '0', '', '3', '0'), ('1550', '抚顺县', '110', '0', '', '3', '0'), ('1551', '新宾满族自治县', '110', '0', '', '3', '0'), ('1552', '新抚区', '110', '0', '', '3', '0'), ('1553', '望花区', '110', '0', '', '3', '0'), ('1554', '清原满族自治县', '110', '0', '', '3', '0'), ('1555', '顺城区', '110', '0', '', '3', '0'), ('1556', '南芬区', '111', '0', '', '3', '0'), ('1557', '平山区', '111', '0', '', '3', '0'), ('1558', '明山区', '111', '0', '', '3', '0'), ('1559', '本溪满族自治县', '111', '0', '', '3', '0'), ('1560', '桓仁满族自治县', '111', '0', '', '3', '0'), ('1561', '溪湖区', '111', '0', '', '3', '0'), ('1562', '东港市', '112', '0', '', '3', '0'), ('1563', '元宝区', '112', '0', '', '3', '0'), ('1564', '凤城市', '112', '0', '', '3', '0'), ('1565', '宽甸满族自治县', '112', '0', '', '3', '0'), ('1566', '振兴区', '112', '0', '', '3', '0'), ('1567', '振安区', '112', '0', '', '3', '0'), ('1568', '义县', '113', '0', '', '3', '0'), ('1569', '凌河区', '113', '0', '', '3', '0'), ('1570', '凌海市', '113', '0', '', '3', '0'), ('1571', '北镇市', '113', '0', '', '3', '0'), ('1572', '古塔区', '113', '0', '', '3', '0'), ('1573', '太和区', '113', '0', '', '3', '0'), ('1574', '黑山县', '113', '0', '', '3', '0'), ('1575', '大石桥市', '114', '0', '', '3', '0'), ('1576', '盖州市', '114', '0', '', '3', '0'), ('1577', '站前区', '114', '0', '', '3', '0'), ('1578', '老边区', '114', '0', '', '3', '0'), ('1579', '西市区', '114', '0', '', '3', '0'), ('1580', '鲅鱼圈区', '114', '0', '', '3', '0'), ('1581', '太平区', '115', '0', '', '3', '0'), ('1582', '彰武县', '115', '0', '', '3', '0'), ('1583', '新邱区', '115', '0', '', '3', '0'), ('1584', '海州区', '115', '0', '', '3', '0'), ('1585', '清河门区', '115', '0', '', '3', '0'), ('1586', '细河区', '115', '0', '', '3', '0'), ('1587', '蒙古族自治县', '115', '0', '', '3', '0'), ('1588', '太子河区', '116', '0', '', '3', '0'), ('1589', '宏伟区', '116', '0', '', '3', '0'), ('1590', '弓长岭区', '116', '0', '', '3', '0'), ('1591', '文圣区', '116', '0', '', '3', '0'), ('1592', '灯塔市', '116', '0', '', '3', '0'), ('1593', '白塔区', '116', '0', '', '3', '0'), ('1594', '辽阳县', '116', '0', '', '3', '0'), ('1595', '兴隆台区', '117', '0', '', '3', '0'), ('1596', '双台子区', '117', '0', '', '3', '0'), ('1597', '大洼县', '117', '0', '', '3', '0'), ('1598', '盘山县', '117', '0', '', '3', '0'), ('1599', '开原市', '118', '0', '', '3', '0'), ('1600', '昌图县', '118', '0', '', '3', '0'), ('1601', '清河区', '118', '0', '', '3', '0'), ('1602', '西丰县', '118', '0', '', '3', '0'), ('1603', '调兵山市', '118', '0', '', '3', '0'), ('1604', '铁岭县', '118', '0', '', '3', '0'), ('1605', '银州区', '118', '0', '', '3', '0'), ('1606', '凌源市', '119', '0', '', '3', '0'), ('1607', '北票市', '119', '0', '', '3', '0'), ('1608', '双塔区', '119', '0', '', '3', '0'), ('1609', '喀喇沁左翼蒙古族自治县', '119', '0', '', '3', '0'), ('1610', '建平县', '119', '0', '', '3', '0'), ('1611', '朝阳县', '119', '0', '', '3', '0'), ('1612', '龙城区', '119', '0', '', '3', '0'), ('1613', '兴城市', '120', '0', '', '3', '0'), ('1614', '南票区', '120', '0', '', '3', '0'), ('1615', '建昌县', '120', '0', '', '3', '0'), ('1616', '绥中县', '120', '0', '', '3', '0'), ('1617', '连山区', '120', '0', '', '3', '0'), ('1618', '龙港区', '120', '0', '', '3', '0'), ('1619', '九台市', '121', '0', '', '3', '0'), ('1620', '二道区', '121', '0', '', '3', '0'), ('1621', '农安县', '121', '0', '', '3', '0'), ('1622', '南关区', '121', '0', '', '3', '0'), ('1623', '双阳区', '121', '0', '', '3', '0'), ('1624', '宽城区', '121', '0', '', '3', '0'), ('1625', '德惠市', '121', '0', '', '3', '0'), ('1626', '朝阳区', '121', '0', '', '3', '0'), ('1627', '榆树市', '121', '0', '', '3', '0'), ('1628', '绿园区', '121', '0', '', '3', '0'), ('1629', '丰满区', '122', '0', '', '3', '0'), ('1630', '昌邑区', '122', '0', '', '3', '0'), ('1631', '桦甸市', '122', '0', '', '3', '0'), ('1632', '永吉县', '122', '0', '', '3', '0'), ('1633', '磐石市', '122', '0', '', '3', '0'), ('1634', '舒兰市', '122', '0', '', '3', '0'), ('1635', '船营区', '122', '0', '', '3', '0'), ('1636', '蛟河市', '122', '0', '', '3', '0'), ('1637', '龙潭区', '122', '0', '', '3', '0'), ('1638', '伊通满族自治县', '123', '0', '', '3', '0'), ('1639', '公主岭市', '123', '0', '', '3', '0'), ('1640', '双辽市', '123', '0', '', '3', '0'), ('1641', '梨树县', '123', '0', '', '3', '0'), ('1642', '铁东区', '123', '0', '', '3', '0'), ('1643', '铁西区', '123', '0', '', '3', '0'), ('1644', '东丰县', '124', '0', '', '3', '0'), ('1645', '东辽县', '124', '0', '', '3', '0'), ('1646', '西安区', '124', '0', '', '3', '0'), ('1647', '龙山区', '124', '0', '', '3', '0'), ('1648', '东昌区', '125', '0', '', '3', '0'), ('1649', '二道江区', '125', '0', '', '3', '0'), ('1650', '柳河县', '125', '0', '', '3', '0'), ('1651', '梅河口市', '125', '0', '', '3', '0'), ('1652', '辉南县', '125', '0', '', '3', '0'), ('1653', '通化县', '125', '0', '', '3', '0'), ('1654', '集安市', '125', '0', '', '3', '0'), ('1655', '临江市', '126', '0', '', '3', '0'), ('1656', '八道江区', '126', '0', '', '3', '0'), ('1657', '抚松县', '126', '0', '', '3', '0'), ('1658', '江源区', '126', '0', '', '3', '0'), ('1659', '长白朝鲜族自治县', '126', '0', '', '3', '0'), ('1660', '靖宇县', '126', '0', '', '3', '0'), ('1661', '干安县', '127', '0', '', '3', '0'), ('1662', '前郭尔罗斯蒙古族自治县', '127', '0', '', '3', '0'), ('1663', '宁江区', '127', '0', '', '3', '0'), ('1664', '扶余县', '127', '0', '', '3', '0'), ('1665', '长岭县', '127', '0', '', '3', '0'), ('1666', '大安市', '128', '0', '', '3', '0'), ('1667', '洮北区', '128', '0', '', '3', '0'), ('1668', '洮南市', '128', '0', '', '3', '0'), ('1669', '通榆县', '128', '0', '', '3', '0'), ('1670', '镇赉县', '128', '0', '', '3', '0'), ('1671', '和龙市', '129', '0', '', '3', '0'), ('1672', '图们市', '129', '0', '', '3', '0'), ('1673', '安图县', '129', '0', '', '3', '0'), ('1674', '延吉市', '129', '0', '', '3', '0'), ('1675', '敦化市', '129', '0', '', '3', '0'), ('1676', '汪清县', '129', '0', '', '3', '0'), ('1677', '珲春市', '129', '0', '', '3', '0'), ('1678', '龙井市', '129', '0', '', '3', '0'), ('1679', '五常市', '130', '0', '', '3', '0'), ('1680', '依兰县', '130', '0', '', '3', '0'), ('1681', '南岗区', '130', '0', '', '3', '0'), ('1682', '双城市', '130', '0', '', '3', '0'), ('1683', '呼兰区', '130', '0', '', '3', '0'), ('1684', '哈尔滨市道里区', '130', '0', '', '3', '0'), ('1685', '宾县', '130', '0', '', '3', '0'), ('1686', '尚志市', '130', '0', '', '3', '0'), ('1687', '巴彦县', '130', '0', '', '3', '0'), ('1688', '平房区', '130', '0', '', '3', '0'), ('1689', '延寿县', '130', '0', '', '3', '0'), ('1690', '方正县', '130', '0', '', '3', '0'), ('1691', '木兰县', '130', '0', '', '3', '0'), ('1692', '松北区', '130', '0', '', '3', '0'), ('1693', '通河县', '130', '0', '', '3', '0'), ('1694', '道外区', '130', '0', '', '3', '0'), ('1695', '阿城区', '130', '0', '', '3', '0'), ('1696', '香坊区', '130', '0', '', '3', '0'), ('1697', '依安县', '131', '0', '', '3', '0'), ('1698', '克东县', '131', '0', '', '3', '0'), ('1699', '克山县', '131', '0', '', '3', '0'), ('1700', '富拉尔基区', '131', '0', '', '3', '0'), ('1701', '富裕县', '131', '0', '', '3', '0'), ('1702', '建华区', '131', '0', '', '3', '0'), ('1703', '拜泉县', '131', '0', '', '3', '0'), ('1704', '昂昂溪区', '131', '0', '', '3', '0'), ('1705', '梅里斯达斡尔族区', '131', '0', '', '3', '0'), ('1706', '泰来县', '131', '0', '', '3', '0'), ('1707', '甘南县', '131', '0', '', '3', '0'), ('1708', '碾子山区', '131', '0', '', '3', '0'), ('1709', '讷河市', '131', '0', '', '3', '0'), ('1710', '铁锋区', '131', '0', '', '3', '0'), ('1711', '龙江县', '131', '0', '', '3', '0'), ('1712', '龙沙区', '131', '0', '', '3', '0'), ('1713', '城子河区', '132', '0', '', '3', '0'), ('1714', '密山市', '132', '0', '', '3', '0'), ('1715', '恒山区', '132', '0', '', '3', '0'), ('1716', '梨树区', '132', '0', '', '3', '0'), ('1717', '滴道区', '132', '0', '', '3', '0'), ('1718', '虎林市', '132', '0', '', '3', '0'), ('1719', '鸡东县', '132', '0', '', '3', '0'), ('1720', '鸡冠区', '132', '0', '', '3', '0'), ('1721', '麻山区', '132', '0', '', '3', '0'), ('1722', '东山区', '133', '0', '', '3', '0'), ('1723', '兴安区', '133', '0', '', '3', '0'), ('1724', '兴山区', '133', '0', '', '3', '0'), ('1725', '南山区', '133', '0', '', '3', '0'), ('1726', '向阳区', '133', '0', '', '3', '0'), ('1727', '工农区', '133', '0', '', '3', '0'), ('1728', '绥滨县', '133', '0', '', '3', '0'), ('1729', '萝北县', '133', '0', '', '3', '0'), ('1730', '友谊县', '134', '0', '', '3', '0'), ('1731', '四方台区', '134', '0', '', '3', '0'), ('1732', '宝山区', '134', '0', '', '3', '0'), ('1733', '宝清县', '134', '0', '', '3', '0'), ('1734', '尖山区', '134', '0', '', '3', '0'), ('1735', '岭东区', '134', '0', '', '3', '0'), ('1736', '集贤县', '134', '0', '', '3', '0'), ('1737', '饶河县', '134', '0', '', '3', '0'), ('1738', '大同区', '135', '0', '', '3', '0'), ('1739', '杜尔伯特蒙古族自治县', '135', '0', '', '3', '0'), ('1740', '林甸县', '135', '0', '', '3', '0'), ('1741', '红岗区', '135', '0', '', '3', '0'), ('1742', '肇州县', '135', '0', '', '3', '0'), ('1743', '肇源县', '135', '0', '', '3', '0'), ('1744', '胡路区', '135', '0', '', '3', '0'), ('1745', '萨尔图区', '135', '0', '', '3', '0'), ('1746', '龙凤区', '135', '0', '', '3', '0'), ('1747', '上甘岭区', '136', '0', '', '3', '0'), ('1748', '乌伊岭区', '136', '0', '', '3', '0'), ('1749', '乌马河区', '136', '0', '', '3', '0'), ('1750', '五营区', '136', '0', '', '3', '0'), ('1751', '伊春区', '136', '0', '', '3', '0'), ('1752', '南岔区', '136', '0', '', '3', '0'), ('1753', '友好区', '136', '0', '', '3', '0'), ('1754', '嘉荫县', '136', '0', '', '3', '0'), ('1755', '带岭区', '136', '0', '', '3', '0'), ('1756', '新青区', '136', '0', '', '3', '0'), ('1757', '汤旺河区', '136', '0', '', '3', '0'), ('1758', '红星区', '136', '0', '', '3', '0'), ('1759', '美溪区', '136', '0', '', '3', '0'), ('1760', '翠峦区', '136', '0', '', '3', '0'), ('1761', '西林区', '136', '0', '', '3', '0'), ('1762', '金山屯区', '136', '0', '', '3', '0'), ('1763', '铁力市', '136', '0', '', '3', '0'), ('1764', '东风区', '137', '0', '', '3', '0'), ('1765', '前进区', '137', '0', '', '3', '0'), ('1766', '同江市', '137', '0', '', '3', '0'), ('1767', '向阳区', '137', '0', '', '3', '0'), ('1768', '富锦市', '137', '0', '', '3', '0'), ('1769', '抚远县', '137', '0', '', '3', '0'), ('1770', '桦南县', '137', '0', '', '3', '0'), ('1771', '桦川县', '137', '0', '', '3', '0'), ('1772', '汤原县', '137', '0', '', '3', '0'), ('1773', '郊区', '137', '0', '', '3', '0'), ('1774', '勃利县', '138', '0', '', '3', '0'), ('1775', '新兴区', '138', '0', '', '3', '0'), ('1776', '桃山区', '138', '0', '', '3', '0'), ('1777', '茄子河区', '138', '0', '', '3', '0'), ('1778', '东宁县', '139', '0', '', '3', '0'), ('1779', '东安区', '139', '0', '', '3', '0'), ('1780', '宁安市', '139', '0', '', '3', '0'), ('1781', '林口县', '139', '0', '', '3', '0'), ('1782', '海林市', '139', '0', '', '3', '0'), ('1783', '爱民区', '139', '0', '', '3', '0'), ('1784', '穆棱市', '139', '0', '', '3', '0'), ('1785', '绥芬河市', '139', '0', '', '3', '0'), ('1786', '西安区', '139', '0', '', '3', '0'), ('1787', '阳明区', '139', '0', '', '3', '0'), ('1788', '五大连池市', '140', '0', '', '3', '0'), ('1789', '北安市', '140', '0', '', '3', '0'), ('1790', '嫩江县', '140', '0', '', '3', '0'), ('1791', '孙吴县', '140', '0', '', '3', '0'), ('1792', '爱辉区', '140', '0', '', '3', '0'), ('1793', '车逊克县', '140', '0', '', '3', '0'), ('1794', '逊克县', '140', '0', '', '3', '0'), ('1795', '兰西县', '141', '0', '', '3', '0'), ('1796', '安达市', '141', '0', '', '3', '0'), ('1797', '庆安县', '141', '0', '', '3', '0'), ('1798', '明水县', '141', '0', '', '3', '0'), ('1799', '望奎县', '141', '0', '', '3', '0'), ('1800', '海伦市', '141', '0', '', '3', '0'), ('1801', '绥化市北林区', '141', '0', '', '3', '0'), ('1802', '绥棱县', '141', '0', '', '3', '0'), ('1803', '肇东市', '141', '0', '', '3', '0'), ('1804', '青冈县', '141', '0', '', '3', '0'), ('1805', '呼玛县', '142', '0', '', '3', '0'), ('1806', '塔河县', '142', '0', '', '3', '0'), ('1807', '大兴安岭地区加格达奇区', '142', '0', '', '3', '0'), ('1808', '大兴安岭地区呼中区', '142', '0', '', '3', '0'), ('1809', '大兴安岭地区新林区', '142', '0', '', '3', '0'), ('1810', '大兴安岭地区松岭区', '142', '0', '', '3', '0'), ('1811', '漠河县', '142', '0', '', '3', '0'), ('2027', '下关区', '162', '0', '', '3', '0'), ('2028', '六合区', '162', '0', '', '3', '0'), ('2029', '建邺区', '162', '0', '', '3', '0'), ('2030', '栖霞区', '162', '0', '', '3', '0'), ('2031', '江宁区', '162', '0', '', '3', '0'), ('2032', '浦口区', '162', '0', '', '3', '0'), ('2033', '溧水县', '162', '0', '', '3', '0'), ('2034', '玄武区', '162', '0', '', '3', '0'), ('2035', '白下区', '162', '0', '', '3', '0'), ('2036', '秦淮区', '162', '0', '', '3', '0'), ('2037', '雨花台区', '162', '0', '', '3', '0'), ('2038', '高淳县', '162', '0', '', '3', '0'), ('2039', '鼓楼区', '162', '0', '', '3', '0'), ('2040', '北塘区', '163', '0', '', '3', '0'), ('2041', '南长区', '163', '0', '', '3', '0'), ('2042', '宜兴市', '163', '0', '', '3', '0'), ('2043', '崇安区', '163', '0', '', '3', '0'), ('2044', '惠山区', '163', '0', '', '3', '0'), ('2045', '江阴市', '163', '0', '', '3', '0'), ('2046', '滨湖区', '163', '0', '', '3', '0'), ('2047', '锡山区', '163', '0', '', '3', '0'), ('2048', '丰县', '164', '0', '', '3', '0'), ('2049', '九里区', '164', '0', '', '3', '0'), ('2050', '云龙区', '164', '0', '', '3', '0'), ('2051', '新沂市', '164', '0', '', '3', '0'), ('2052', '沛县', '164', '0', '', '3', '0'), ('2053', '泉山区', '164', '0', '', '3', '0'), ('2054', '睢宁县', '164', '0', '', '3', '0'), ('2055', '贾汪区', '164', '0', '', '3', '0'), ('2056', '邳州市', '164', '0', '', '3', '0'), ('2057', '铜山县', '164', '0', '', '3', '0'), ('2058', '鼓楼区', '164', '0', '', '3', '0'), ('2059', '天宁区', '165', '0', '', '3', '0'), ('2060', '戚墅堰区', '165', '0', '', '3', '0'), ('2061', '新北区', '165', '0', '', '3', '0'), ('2062', '武进区', '165', '0', '', '3', '0'), ('2063', '溧阳市', '165', '0', '', '3', '0'), ('2064', '金坛市', '165', '0', '', '3', '0'), ('2065', '钟楼区', '165', '0', '', '3', '0'), ('2066', '吴中区', '166', '0', '', '3', '0'), ('2067', '吴江市', '166', '0', '', '3', '0'), ('2068', '太仓市', '166', '0', '', '3', '0'), ('2069', '常熟市', '166', '0', '', '3', '0'), ('2070', '平江区', '166', '0', '', '3', '0'), ('2071', '张家港市', '166', '0', '', '3', '0'), ('2072', '昆山市', '166', '0', '', '3', '0'), ('2073', '沧浪区', '166', '0', '', '3', '0'), ('2074', '相城区', '166', '0', '', '3', '0'), ('2075', '苏州工业园区', '166', '0', '', '3', '0'), ('2076', '虎丘区', '166', '0', '', '3', '0'), ('2077', '金阊区', '166', '0', '', '3', '0'), ('2078', '启东市', '167', '0', '', '3', '0'), ('2079', '如东县', '167', '0', '', '3', '0'), ('2080', '如皋市', '167', '0', '', '3', '0'), ('2081', '崇川区', '167', '0', '', '3', '0'), ('2082', '海安县', '167', '0', '', '3', '0'), ('2083', '海门市', '167', '0', '', '3', '0'), ('2084', '港闸区', '167', '0', '', '3', '0'), ('2085', '通州市', '167', '0', '', '3', '0'), ('2086', '东海县', '168', '0', '', '3', '0'), ('2087', '新浦区', '168', '0', '', '3', '0'), ('2088', '海州区', '168', '0', '', '3', '0'), ('2089', '灌云县', '168', '0', '', '3', '0'), ('2090', '灌南县', '168', '0', '', '3', '0'), ('2091', '赣榆县', '168', '0', '', '3', '0'), ('2092', '连云区', '168', '0', '', '3', '0'), ('2093', '楚州区', '169', '0', '', '3', '0'), ('2094', '洪泽县', '169', '0', '', '3', '0'), ('2095', '涟水县', '169', '0', '', '3', '0'), ('2096', '淮阴区', '169', '0', '', '3', '0'), ('2097', '清河区', '169', '0', '', '3', '0'), ('2098', '清浦区', '169', '0', '', '3', '0'), ('2099', '盱眙县', '169', '0', '', '3', '0'), ('2100', '金湖县', '169', '0', '', '3', '0'), ('2101', '东台市', '170', '0', '', '3', '0'), ('2102', '亭湖区', '170', '0', '', '3', '0'), ('2103', '响水县', '170', '0', '', '3', '0'), ('2104', '大丰市', '170', '0', '', '3', '0'), ('2105', '射阳县', '170', '0', '', '3', '0'), ('2106', '建湖县', '170', '0', '', '3', '0'), ('2107', '滨海县', '170', '0', '', '3', '0'), ('2108', '盐都区', '170', '0', '', '3', '0'), ('2109', '阜宁县', '170', '0', '', '3', '0'), ('2110', '仪征市', '171', '0', '', '3', '0'), ('2111', '宝应县', '171', '0', '', '3', '0'), ('2112', '广陵区', '171', '0', '', '3', '0'), ('2113', '江都市', '171', '0', '', '3', '0'), ('2114', '维扬区', '171', '0', '', '3', '0'), ('2115', '邗江区', '171', '0', '', '3', '0'), ('2116', '高邮市', '171', '0', '', '3', '0'), ('2117', '丹徒区', '172', '0', '', '3', '0'), ('2118', '丹阳市', '172', '0', '', '3', '0'), ('2119', '京口区', '172', '0', '', '3', '0'), ('2120', '句容市', '172', '0', '', '3', '0'), ('2121', '扬中市', '172', '0', '', '3', '0'), ('2122', '润州区', '172', '0', '', '3', '0'), ('2123', '兴化市', '173', '0', '', '3', '0'), ('2124', '姜堰市', '173', '0', '', '3', '0'), ('2125', '泰兴市', '173', '0', '', '3', '0'), ('2126', '海陵区', '173', '0', '', '3', '0'), ('2127', '靖江市', '173', '0', '', '3', '0'), ('2128', '高港区', '173', '0', '', '3', '0'), ('2129', '宿城区', '174', '0', '', '3', '0'), ('2130', '宿豫区', '174', '0', '', '3', '0'), ('2131', '沭阳县', '174', '0', '', '3', '0'), ('2132', '泗洪县', '174', '0', '', '3', '0'), ('2133', '泗阳县', '174', '0', '', '3', '0'), ('2134', '上城区', '175', '0', '', '3', '0'), ('2135', '下城区', '175', '0', '', '3', '0'), ('2136', '临安市', '175', '0', '', '3', '0'), ('2137', '余杭区', '175', '0', '', '3', '0'), ('2138', '富阳市', '175', '0', '', '3', '0'), ('2139', '建德市', '175', '0', '', '3', '0'), ('2140', '拱墅区', '175', '0', '', '3', '0'), ('2141', '桐庐县', '175', '0', '', '3', '0'), ('2142', '江干区', '175', '0', '', '3', '0'), ('2143', '淳安县', '175', '0', '', '3', '0'), ('2144', '滨江区', '175', '0', '', '3', '0'), ('2145', '萧山区', '175', '0', '', '3', '0'), ('2146', '西湖区', '175', '0', '', '3', '0'), ('2147', '余姚市', '176', '0', '', '3', '0'), ('2148', '北仑区', '176', '0', '', '3', '0'), ('2149', '奉化市', '176', '0', '', '3', '0'), ('2150', '宁海县', '176', '0', '', '3', '0'), ('2151', '慈溪市', '176', '0', '', '3', '0'), ('2152', '江东区', '176', '0', '', '3', '0'), ('2153', '江北区', '176', '0', '', '3', '0'), ('2154', '海曙区', '176', '0', '', '3', '0'), ('2155', '象山县', '176', '0', '', '3', '0'), ('2156', '鄞州区', '176', '0', '', '3', '0'), ('2157', '镇海区', '176', '0', '', '3', '0'), ('2158', '乐清市', '177', '0', '', '3', '0'), ('2159', '平阳县', '177', '0', '', '3', '0'), ('2160', '文成县', '177', '0', '', '3', '0'), ('2161', '永嘉县', '177', '0', '', '3', '0'), ('2162', '泰顺县', '177', '0', '', '3', '0'), ('2163', '洞头县', '177', '0', '', '3', '0'), ('2164', '瑞安市', '177', '0', '', '3', '0'), ('2165', '瓯海区', '177', '0', '', '3', '0'), ('2166', '苍南县', '177', '0', '', '3', '0'), ('2167', '鹿城区', '177', '0', '', '3', '0'), ('2168', '龙湾区', '177', '0', '', '3', '0'), ('2169', '南湖区', '178', '0', '', '3', '0'), ('2170', '嘉善县', '178', '0', '', '3', '0'), ('2171', '平湖市', '178', '0', '', '3', '0'), ('2172', '桐乡市', '178', '0', '', '3', '0'), ('2173', '海宁市', '178', '0', '', '3', '0'), ('2174', '海盐县', '178', '0', '', '3', '0'), ('2175', '秀洲区', '178', '0', '', '3', '0'), ('2176', '南浔区', '179', '0', '', '3', '0'), ('2177', '吴兴区', '179', '0', '', '3', '0'), ('2178', '安吉县', '179', '0', '', '3', '0'), ('2179', '德清县', '179', '0', '', '3', '0'), ('2180', '长兴县', '179', '0', '', '3', '0'), ('2181', '上虞市', '180', '0', '', '3', '0'), ('2182', '嵊州市', '180', '0', '', '3', '0'), ('2183', '新昌县', '180', '0', '', '3', '0'), ('2184', '绍兴县', '180', '0', '', '3', '0'), ('2185', '诸暨市', '180', '0', '', '3', '0'), ('2186', '越城区', '180', '0', '', '3', '0'), ('2187', '定海区', '181', '0', '', '3', '0'), ('2188', '岱山县', '181', '0', '', '3', '0'), ('2189', '嵊泗县', '181', '0', '', '3', '0'), ('2190', '普陀区', '181', '0', '', '3', '0'), ('2191', '常山县', '182', '0', '', '3', '0'), ('2192', '开化县', '182', '0', '', '3', '0'), ('2193', '柯城区', '182', '0', '', '3', '0'), ('2194', '江山市', '182', '0', '', '3', '0'), ('2195', '衢江区', '182', '0', '', '3', '0'), ('2196', '龙游县', '182', '0', '', '3', '0'), ('2197', '东阳市', '183', '0', '', '3', '0'), ('2198', '义乌市', '183', '0', '', '3', '0'), ('2199', '兰溪市', '183', '0', '', '3', '0'), ('2200', '婺城区', '183', '0', '', '3', '0'), ('2201', '武义县', '183', '0', '', '3', '0'), ('2202', '永康市', '183', '0', '', '3', '0'), ('2203', '浦江县', '183', '0', '', '3', '0'), ('2204', '磐安县', '183', '0', '', '3', '0'), ('2205', '金东区', '183', '0', '', '3', '0'), ('2206', '三门县', '184', '0', '', '3', '0'), ('2207', '临海市', '184', '0', '', '3', '0'), ('2208', '仙居县', '184', '0', '', '3', '0'), ('2209', '天台县', '184', '0', '', '3', '0'), ('2210', '椒江区', '184', '0', '', '3', '0'), ('2211', '温岭市', '184', '0', '', '3', '0'), ('2212', '玉环县', '184', '0', '', '3', '0'), ('2213', '路桥区', '184', '0', '', '3', '0'), ('2214', '黄岩区', '184', '0', '', '3', '0'), ('2215', '云和县', '185', '0', '', '3', '0'), ('2216', '庆元县', '185', '0', '', '3', '0'), ('2217', '景宁畲族自治县', '185', '0', '', '3', '0'), ('2218', '松阳县', '185', '0', '', '3', '0'), ('2219', '缙云县', '185', '0', '', '3', '0'), ('2220', '莲都区', '185', '0', '', '3', '0'), ('2221', '遂昌县', '185', '0', '', '3', '0'), ('2222', '青田县', '185', '0', '', '3', '0'), ('2223', '龙泉市', '185', '0', '', '3', '0'), ('2224', '包河区', '186', '0', '', '3', '0'), ('2225', '庐阳区', '186', '0', '', '3', '0'), ('2226', '瑶海区', '186', '0', '', '3', '0'), ('2227', '肥东县', '186', '0', '', '3', '0'), ('2228', '肥西县', '186', '0', '', '3', '0'), ('2229', '蜀山区', '186', '0', '', '3', '0'), ('2230', '长丰县', '186', '0', '', '3', '0'), ('2231', '三山区', '187', '0', '', '3', '0'), ('2232', '南陵县', '187', '0', '', '3', '0'), ('2233', '弋江区', '187', '0', '', '3', '0'), ('2234', '繁昌县', '187', '0', '', '3', '0'), ('2235', '芜湖县', '187', '0', '', '3', '0'), ('2236', '镜湖区', '187', '0', '', '3', '0'), ('2237', '鸠江区', '187', '0', '', '3', '0'), ('2238', '五河县', '188', '0', '', '3', '0'), ('2239', '固镇县', '188', '0', '', '3', '0'), ('2240', '怀远县', '188', '0', '', '3', '0'), ('2241', '淮上区', '188', '0', '', '3', '0'), ('2242', '禹会区', '188', '0', '', '3', '0'), ('2243', '蚌山区', '188', '0', '', '3', '0'), ('2244', '龙子湖区', '188', '0', '', '3', '0'), ('2245', '八公山区', '189', '0', '', '3', '0'), ('2246', '凤台县', '189', '0', '', '3', '0'), ('2247', '大通区', '189', '0', '', '3', '0'), ('2248', '潘集区', '189', '0', '', '3', '0'), ('2249', '田家庵区', '189', '0', '', '3', '0'), ('2250', '谢家集区', '189', '0', '', '3', '0'), ('2251', '当涂县', '190', '0', '', '3', '0'), ('2252', '花山区', '190', '0', '', '3', '0'), ('2253', '金家庄区', '190', '0', '', '3', '0'), ('2254', '雨山区', '190', '0', '', '3', '0'), ('2255', '杜集区', '191', '0', '', '3', '0'), ('2256', '濉溪县', '191', '0', '', '3', '0'), ('2257', '烈山区', '191', '0', '', '3', '0'), ('2258', '相山区', '191', '0', '', '3', '0'), ('2259', '狮子山区', '192', '0', '', '3', '0'), ('2260', '郊区', '192', '0', '', '3', '0'), ('2261', '铜官山区', '192', '0', '', '3', '0'), ('2262', '铜陵县', '192', '0', '', '3', '0'), ('2263', '大观区', '193', '0', '', '3', '0'), ('2264', '太湖县', '193', '0', '', '3', '0'), ('2265', '宜秀区', '193', '0', '', '3', '0'), ('2266', '宿松县', '193', '0', '', '3', '0'), ('2267', '岳西县', '193', '0', '', '3', '0'), ('2268', '怀宁县', '193', '0', '', '3', '0'), ('2269', '望江县', '193', '0', '', '3', '0'), ('2270', '枞阳县', '193', '0', '', '3', '0'), ('2271', '桐城市', '193', '0', '', '3', '0'), ('2272', '潜山县', '193', '0', '', '3', '0'), ('2273', '迎江区', '193', '0', '', '3', '0'), ('2274', '休宁县', '194', '0', '', '3', '0'), ('2275', '屯溪区', '194', '0', '', '3', '0'), ('2276', '徽州区', '194', '0', '', '3', '0'), ('2277', '歙县', '194', '0', '', '3', '0'), ('2278', '祁门县', '194', '0', '', '3', '0'), ('2279', '黄山区', '194', '0', '', '3', '0'), ('2280', '黟县', '194', '0', '', '3', '0'), ('2281', '全椒县', '195', '0', '', '3', '0'), ('2282', '凤阳县', '195', '0', '', '3', '0'), ('2283', '南谯区', '195', '0', '', '3', '0'), ('2284', '天长市', '195', '0', '', '3', '0'), ('2285', '定远县', '195', '0', '', '3', '0'), ('2286', '明光市', '195', '0', '', '3', '0'), ('2287', '来安县', '195', '0', '', '3', '0'), ('2288', '琅玡区', '195', '0', '', '3', '0'), ('2289', '临泉县', '196', '0', '', '3', '0'), ('2290', '太和县', '196', '0', '', '3', '0'), ('2291', '界首市', '196', '0', '', '3', '0'), ('2292', '阜南县', '196', '0', '', '3', '0'), ('2293', '颍东区', '196', '0', '', '3', '0'), ('2294', '颍州区', '196', '0', '', '3', '0'), ('2295', '颍泉区', '196', '0', '', '3', '0'), ('2296', '颖上县', '196', '0', '', '3', '0'), ('2297', '埇桥区', '197', '0', '', '3', '0'), ('2298', '泗县辖', '197', '0', '', '3', '0'), ('2299', '灵璧县', '197', '0', '', '3', '0'), ('2300', '砀山县', '197', '0', '', '3', '0'), ('2301', '萧县', '197', '0', '', '3', '0'), ('2302', '含山县', '198', '0', '', '3', '0'), ('2303', '和县', '198', '0', '', '3', '0'), ('2304', '居巢区', '198', '0', '', '3', '0'), ('2305', '庐江县', '198', '0', '', '3', '0'), ('2306', '无为县', '198', '0', '', '3', '0'), ('2307', '寿县', '199', '0', '', '3', '0'), ('2308', '舒城县', '199', '0', '', '3', '0'), ('2309', '裕安区', '199', '0', '', '3', '0'), ('2310', '金安区', '199', '0', '', '3', '0'), ('2311', '金寨县', '199', '0', '', '3', '0'), ('2312', '霍山县', '199', '0', '', '3', '0'), ('2313', '霍邱县', '199', '0', '', '3', '0'), ('2314', '利辛县', '200', '0', '', '3', '0'), ('2315', '涡阳县', '200', '0', '', '3', '0'), ('2316', '蒙城县', '200', '0', '', '3', '0'), ('2317', '谯城区', '200', '0', '', '3', '0'), ('2318', '东至县', '201', '0', '', '3', '0'), ('2319', '石台县', '201', '0', '', '3', '0'), ('2320', '贵池区', '201', '0', '', '3', '0'), ('2321', '青阳县', '201', '0', '', '3', '0'), ('2322', '宁国市', '202', '0', '', '3', '0'), ('2323', '宣州区', '202', '0', '', '3', '0');
INSERT INTO `yf_base_district` VALUES ('2324', '广德县', '202', '0', '', '3', '0'), ('2325', '旌德县', '202', '0', '', '3', '0'), ('2326', '泾县', '202', '0', '', '3', '0'), ('2327', '绩溪县', '202', '0', '', '3', '0'), ('2328', '郎溪县', '202', '0', '', '3', '0'), ('2329', '仓山区', '203', '0', '', '3', '0'), ('2330', '台江区', '203', '0', '', '3', '0'), ('2331', '平潭县', '203', '0', '', '3', '0'), ('2332', '晋安区', '203', '0', '', '3', '0'), ('2333', '永泰县', '203', '0', '', '3', '0'), ('2334', '福清市', '203', '0', '', '3', '0'), ('2335', '罗源县', '203', '0', '', '3', '0'), ('2336', '连江县', '203', '0', '', '3', '0'), ('2337', '长乐市', '203', '0', '', '3', '0'), ('2338', '闽侯县', '203', '0', '', '3', '0'), ('2339', '闽清县', '203', '0', '', '3', '0'), ('2340', '马尾区', '203', '0', '', '3', '0'), ('2341', '鼓楼区', '203', '0', '', '3', '0'), ('2342', '同安区', '204', '0', '', '3', '0'), ('2343', '思明区', '204', '0', '', '3', '0'), ('2344', '海沧区', '204', '0', '', '3', '0'), ('2345', '湖里区', '204', '0', '', '3', '0'), ('2346', '翔安区', '204', '0', '', '3', '0'), ('2347', '集美区', '204', '0', '', '3', '0'), ('2348', '仙游县', '205', '0', '', '3', '0'), ('2349', '城厢区', '205', '0', '', '3', '0'), ('2350', '涵江区', '205', '0', '', '3', '0'), ('2351', '秀屿区', '205', '0', '', '3', '0'), ('2352', '荔城区', '205', '0', '', '3', '0'), ('2353', '三元区', '206', '0', '', '3', '0'), ('2354', '大田县', '206', '0', '', '3', '0'), ('2355', '宁化县', '206', '0', '', '3', '0'), ('2356', '将乐县', '206', '0', '', '3', '0'), ('2357', '尤溪县', '206', '0', '', '3', '0'), ('2358', '建宁县', '206', '0', '', '3', '0'), ('2359', '明溪县', '206', '0', '', '3', '0'), ('2360', '梅列区', '206', '0', '', '3', '0'), ('2361', '永安市', '206', '0', '', '3', '0'), ('2362', '沙县', '206', '0', '', '3', '0'), ('2363', '泰宁县', '206', '0', '', '3', '0'), ('2364', '清流县', '206', '0', '', '3', '0'), ('2365', '丰泽区', '207', '0', '', '3', '0'), ('2366', '南安市', '207', '0', '', '3', '0'), ('2367', '安溪县', '207', '0', '', '3', '0'), ('2368', '德化县', '207', '0', '', '3', '0'), ('2369', '惠安县', '207', '0', '', '3', '0'), ('2370', '晋江市', '207', '0', '', '3', '0'), ('2371', '永春县', '207', '0', '', '3', '0'), ('2372', '泉港区', '207', '0', '', '3', '0'), ('2373', '洛江区', '207', '0', '', '3', '0'), ('2374', '石狮市', '207', '0', '', '3', '0'), ('2375', '金门县', '207', '0', '', '3', '0'), ('2376', '鲤城区', '207', '0', '', '3', '0'), ('2377', '东山县', '208', '0', '', '3', '0'), ('2378', '云霄县', '208', '0', '', '3', '0'), ('2379', '华安县', '208', '0', '', '3', '0'), ('2380', '南靖县', '208', '0', '', '3', '0'), ('2381', '平和县', '208', '0', '', '3', '0'), ('2382', '漳浦县', '208', '0', '', '3', '0'), ('2383', '芗城区', '208', '0', '', '3', '0'), ('2384', '诏安县', '208', '0', '', '3', '0'), ('2385', '长泰县', '208', '0', '', '3', '0'), ('2386', '龙文区', '208', '0', '', '3', '0'), ('2387', '龙海市', '208', '0', '', '3', '0'), ('2388', '光泽县', '209', '0', '', '3', '0'), ('2389', '延平区', '209', '0', '', '3', '0'), ('2390', '建瓯市', '209', '0', '', '3', '0'), ('2391', '建阳市', '209', '0', '', '3', '0'), ('2392', '政和县', '209', '0', '', '3', '0'), ('2393', '松溪县', '209', '0', '', '3', '0'), ('2394', '武夷山市', '209', '0', '', '3', '0'), ('2395', '浦城县', '209', '0', '', '3', '0'), ('2396', '邵武市', '209', '0', '', '3', '0'), ('2397', '顺昌县', '209', '0', '', '3', '0'), ('2398', '上杭县', '210', '0', '', '3', '0'), ('2399', '新罗区', '210', '0', '', '3', '0'), ('2400', '武平县', '210', '0', '', '3', '0'), ('2401', '永定县', '210', '0', '', '3', '0'), ('2402', '漳平市', '210', '0', '', '3', '0'), ('2403', '连城县', '210', '0', '', '3', '0'), ('2404', '长汀县', '210', '0', '', '3', '0'), ('2405', '古田县', '211', '0', '', '3', '0'), ('2406', '周宁县', '211', '0', '', '3', '0'), ('2407', '寿宁县', '211', '0', '', '3', '0'), ('2408', '屏南县', '211', '0', '', '3', '0'), ('2409', '柘荣县', '211', '0', '', '3', '0'), ('2410', '福安市', '211', '0', '', '3', '0'), ('2411', '福鼎市', '211', '0', '', '3', '0'), ('2412', '蕉城区', '211', '0', '', '3', '0'), ('2413', '霞浦县', '211', '0', '', '3', '0'), ('2414', '东湖区', '212', '0', '', '3', '0'), ('2415', '南昌县', '212', '0', '', '3', '0'), ('2416', '安义县', '212', '0', '', '3', '0'), ('2417', '新建县', '212', '0', '', '3', '0'), ('2418', '湾里区', '212', '0', '', '3', '0'), ('2419', '西湖区', '212', '0', '', '3', '0'), ('2420', '进贤县', '212', '0', '', '3', '0'), ('2421', '青云谱区', '212', '0', '', '3', '0'), ('2422', '青山湖区', '212', '0', '', '3', '0'), ('2423', '乐平市', '213', '0', '', '3', '0'), ('2424', '昌江区', '213', '0', '', '3', '0'), ('2425', '浮梁县', '213', '0', '', '3', '0'), ('2426', '珠山区', '213', '0', '', '3', '0'), ('2427', '上栗县', '214', '0', '', '3', '0'), ('2428', '安源区', '214', '0', '', '3', '0'), ('2429', '湘东区', '214', '0', '', '3', '0'), ('2430', '芦溪县', '214', '0', '', '3', '0'), ('2431', '莲花县', '214', '0', '', '3', '0'), ('2432', '九江县', '215', '0', '', '3', '0'), ('2433', '修水县', '215', '0', '', '3', '0'), ('2434', '庐山区', '215', '0', '', '3', '0'), ('2435', '彭泽县', '215', '0', '', '3', '0'), ('2436', '德安县', '215', '0', '', '3', '0'), ('2437', '星子县', '215', '0', '', '3', '0'), ('2438', '武宁县', '215', '0', '', '3', '0'), ('2439', '永修县', '215', '0', '', '3', '0'), ('2440', '浔阳区', '215', '0', '', '3', '0'), ('2441', '湖口县', '215', '0', '', '3', '0'), ('2442', '瑞昌市', '215', '0', '', '3', '0'), ('2443', '都昌县', '215', '0', '', '3', '0'), ('2444', '分宜县', '216', '0', '', '3', '0'), ('2445', '渝水区', '216', '0', '', '3', '0'), ('2446', '余江县', '217', '0', '', '3', '0'), ('2447', '月湖区', '217', '0', '', '3', '0'), ('2448', '贵溪市', '217', '0', '', '3', '0'), ('2449', '上犹县', '218', '0', '', '3', '0'), ('2450', '于都县', '218', '0', '', '3', '0'), ('2451', '会昌县', '218', '0', '', '3', '0'), ('2452', '信丰县', '218', '0', '', '3', '0'), ('2453', '全南县', '218', '0', '', '3', '0'), ('2454', '兴国县', '218', '0', '', '3', '0'), ('2455', '南康市', '218', '0', '', '3', '0'), ('2456', '大余县', '218', '0', '', '3', '0'), ('2457', '宁都县', '218', '0', '', '3', '0'), ('2458', '安远县', '218', '0', '', '3', '0'), ('2459', '定南县', '218', '0', '', '3', '0'), ('2460', '寻乌县', '218', '0', '', '3', '0'), ('2461', '崇义县', '218', '0', '', '3', '0'), ('2462', '瑞金市', '218', '0', '', '3', '0'), ('2463', '石城县', '218', '0', '', '3', '0'), ('2464', '章贡区', '218', '0', '', '3', '0'), ('2465', '赣县', '218', '0', '', '3', '0'), ('2466', '龙南县', '218', '0', '', '3', '0'), ('2467', '万安县', '219', '0', '', '3', '0'), ('2468', '井冈山市', '219', '0', '', '3', '0'), ('2469', '吉安县', '219', '0', '', '3', '0'), ('2470', '吉州区', '219', '0', '', '3', '0'), ('2471', '吉水县', '219', '0', '', '3', '0'), ('2472', '安福县', '219', '0', '', '3', '0'), ('2473', '峡江县', '219', '0', '', '3', '0'), ('2474', '新干县', '219', '0', '', '3', '0'), ('2475', '永丰县', '219', '0', '', '3', '0'), ('2476', '永新县', '219', '0', '', '3', '0'), ('2477', '泰和县', '219', '0', '', '3', '0'), ('2478', '遂川县', '219', '0', '', '3', '0'), ('2479', '青原区', '219', '0', '', '3', '0'), ('2480', '万载县', '220', '0', '', '3', '0'), ('2481', '上高县', '220', '0', '', '3', '0'), ('2482', '丰城市', '220', '0', '', '3', '0'), ('2483', '奉新县', '220', '0', '', '3', '0'), ('2484', '宜丰县', '220', '0', '', '3', '0'), ('2485', '樟树市', '220', '0', '', '3', '0'), ('2486', '袁州区', '220', '0', '', '3', '0'), ('2487', '铜鼓县', '220', '0', '', '3', '0'), ('2488', '靖安县', '220', '0', '', '3', '0'), ('2489', '高安市', '220', '0', '', '3', '0'), ('2490', '东乡县', '221', '0', '', '3', '0'), ('2491', '临川区', '221', '0', '', '3', '0'), ('2492', '乐安县', '221', '0', '', '3', '0'), ('2493', '南丰县', '221', '0', '', '3', '0'), ('2494', '南城县', '221', '0', '', '3', '0'), ('2495', '宜黄县', '221', '0', '', '3', '0'), ('2496', '崇仁县', '221', '0', '', '3', '0'), ('2497', '广昌县', '221', '0', '', '3', '0'), ('2498', '资溪县', '221', '0', '', '3', '0'), ('2499', '金溪县', '221', '0', '', '3', '0'), ('2500', '黎川县', '221', '0', '', '3', '0'), ('2501', '万年县', '222', '0', '', '3', '0'), ('2502', '上饶县', '222', '0', '', '3', '0'), ('2503', '余干县', '222', '0', '', '3', '0'), ('2504', '信州区', '222', '0', '', '3', '0'), ('2505', '婺源县', '222', '0', '', '3', '0'), ('2506', '广丰县', '222', '0', '', '3', '0'), ('2507', '弋阳县', '222', '0', '', '3', '0'), ('2508', '德兴市', '222', '0', '', '3', '0'), ('2509', '横峰县', '222', '0', '', '3', '0'), ('2510', '玉山县', '222', '0', '', '3', '0'), ('2511', '鄱阳县', '222', '0', '', '3', '0'), ('2512', '铅山县', '222', '0', '', '3', '0'), ('2513', '历下区', '223', '0', '', '3', '0'), ('2514', '历城区', '223', '0', '', '3', '0'), ('2515', '商河县', '223', '0', '', '3', '0'), ('2516', '天桥区', '223', '0', '', '3', '0'), ('2517', '市中区', '223', '0', '', '3', '0'), ('2518', '平阴县', '223', '0', '', '3', '0'), ('2519', '槐荫区', '223', '0', '', '3', '0'), ('2520', '济阳县', '223', '0', '', '3', '0'), ('2521', '章丘市', '223', '0', '', '3', '0'), ('2522', '长清区', '223', '0', '', '3', '0'), ('2523', '即墨市', '224', '0', '', '3', '0'), ('2524', '四方区', '224', '0', '', '3', '0'), ('2525', '城阳区', '224', '0', '', '3', '0'), ('2526', '崂山区', '224', '0', '', '3', '0'), ('2527', '市北区', '224', '0', '', '3', '0'), ('2528', '市南区', '224', '0', '', '3', '0'), ('2529', '平度市', '224', '0', '', '3', '0'), ('2530', '李沧区', '224', '0', '', '3', '0'), ('2531', '胶南市', '224', '0', '', '3', '0'), ('2532', '胶州市', '224', '0', '', '3', '0'), ('2533', '莱西市', '224', '0', '', '3', '0'), ('2534', '黄岛区', '224', '0', '', '3', '0'), ('2535', '临淄区', '225', '0', '', '3', '0'), ('2536', '博山区', '225', '0', '', '3', '0'), ('2537', '周村区', '225', '0', '', '3', '0'), ('2538', '张店区', '225', '0', '', '3', '0'), ('2539', '桓台县', '225', '0', '', '3', '0'), ('2540', '沂源县', '225', '0', '', '3', '0'), ('2541', '淄川区', '225', '0', '', '3', '0'), ('2542', '高青县', '225', '0', '', '3', '0'), ('2543', '台儿庄区', '226', '0', '', '3', '0'), ('2544', '山亭区', '226', '0', '', '3', '0'), ('2545', '峄城区', '226', '0', '', '3', '0'), ('2546', '市中区', '226', '0', '', '3', '0'), ('2547', '滕州市', '226', '0', '', '3', '0'), ('2548', '薛城区', '226', '0', '', '3', '0'), ('2549', '东营区', '227', '0', '', '3', '0'), ('2550', '利津县', '227', '0', '', '3', '0'), ('2551', '垦利县', '227', '0', '', '3', '0'), ('2552', '广饶县', '227', '0', '', '3', '0'), ('2553', '河口区', '227', '0', '', '3', '0'), ('2554', '招远市', '228', '0', '', '3', '0'), ('2555', '栖霞市', '228', '0', '', '3', '0'), ('2556', '海阳市', '228', '0', '', '3', '0'), ('2557', '牟平区', '228', '0', '', '3', '0'), ('2558', '福山区', '228', '0', '', '3', '0'), ('2559', '芝罘区', '228', '0', '', '3', '0'), ('2560', '莱山区', '228', '0', '', '3', '0'), ('2561', '莱州市', '228', '0', '', '3', '0'), ('2562', '莱阳市', '228', '0', '', '3', '0'), ('2563', '蓬莱市', '228', '0', '', '3', '0'), ('2564', '长岛县', '228', '0', '', '3', '0'), ('2565', '龙口市', '228', '0', '', '3', '0'), ('2566', '临朐县', '229', '0', '', '3', '0'), ('2567', '坊子区', '229', '0', '', '3', '0'), ('2568', '奎文区', '229', '0', '', '3', '0'), ('2569', '安丘市', '229', '0', '', '3', '0'), ('2570', '寒亭区', '229', '0', '', '3', '0'), ('2571', '寿光市', '229', '0', '', '3', '0'), ('2572', '昌乐县', '229', '0', '', '3', '0'), ('2573', '昌邑市', '229', '0', '', '3', '0'), ('2574', '潍城区', '229', '0', '', '3', '0'), ('2575', '诸城市', '229', '0', '', '3', '0'), ('2576', '青州市', '229', '0', '', '3', '0'), ('2577', '高密市', '229', '0', '', '3', '0'), ('2578', '任城区', '230', '0', '', '3', '0'), ('2579', '兖州市', '230', '0', '', '3', '0'), ('2580', '嘉祥县', '230', '0', '', '3', '0'), ('2581', '市中区', '230', '0', '', '3', '0'), ('2582', '微山县', '230', '0', '', '3', '0'), ('2583', '曲阜市', '230', '0', '', '3', '0'), ('2584', '梁山县', '230', '0', '', '3', '0'), ('2585', '汶上县', '230', '0', '', '3', '0'), ('2586', '泗水县', '230', '0', '', '3', '0'), ('2587', '邹城市', '230', '0', '', '3', '0'), ('2588', '金乡县', '230', '0', '', '3', '0'), ('2589', '鱼台县', '230', '0', '', '3', '0'), ('2590', '东平县', '231', '0', '', '3', '0'), ('2591', '宁阳县', '231', '0', '', '3', '0'), ('2592', '岱岳区', '231', '0', '', '3', '0'), ('2593', '新泰市', '231', '0', '', '3', '0'), ('2594', '泰山区', '231', '0', '', '3', '0'), ('2595', '肥城市', '231', '0', '', '3', '0'), ('2596', '乳山市', '232', '0', '', '3', '0'), ('2597', '文登市', '232', '0', '', '3', '0'), ('2598', '环翠区', '232', '0', '', '3', '0'), ('2599', '荣成市', '232', '0', '', '3', '0'), ('2600', '东港区', '233', '0', '', '3', '0'), ('2601', '五莲县', '233', '0', '', '3', '0'), ('2602', '岚山区', '233', '0', '', '3', '0'), ('2603', '莒县', '233', '0', '', '3', '0'), ('2604', '莱城区', '234', '0', '', '3', '0'), ('2605', '钢城区', '234', '0', '', '3', '0'), ('2606', '临沭县', '235', '0', '', '3', '0'), ('2607', '兰山区', '235', '0', '', '3', '0'), ('2608', '平邑县', '235', '0', '', '3', '0'), ('2609', '沂南县', '235', '0', '', '3', '0'), ('2610', '沂水县', '235', '0', '', '3', '0'), ('2611', '河东区', '235', '0', '', '3', '0'), ('2612', '罗庄区', '235', '0', '', '3', '0'), ('2613', '苍山县', '235', '0', '', '3', '0'), ('2614', '莒南县', '235', '0', '', '3', '0'), ('2615', '蒙阴县', '235', '0', '', '3', '0'), ('2616', '费县', '235', '0', '', '3', '0'), ('2617', '郯城县', '235', '0', '', '3', '0'), ('2618', '临邑县', '236', '0', '', '3', '0'), ('2619', '乐陵市', '236', '0', '', '3', '0'), ('2620', '夏津县', '236', '0', '', '3', '0'), ('2621', '宁津县', '236', '0', '', '3', '0'), ('2622', '平原县', '236', '0', '', '3', '0'), ('2623', '庆云县', '236', '0', '', '3', '0'), ('2624', '德城区', '236', '0', '', '3', '0'), ('2625', '武城县', '236', '0', '', '3', '0'), ('2626', '禹城市', '236', '0', '', '3', '0'), ('2627', '陵县', '236', '0', '', '3', '0'), ('2628', '齐河县', '236', '0', '', '3', '0'), ('2629', '东昌府区', '237', '0', '', '3', '0'), ('2630', '东阿县', '237', '0', '', '3', '0'), ('2631', '临清市', '237', '0', '', '3', '0'), ('2632', '冠县', '237', '0', '', '3', '0'), ('2633', '茌平县', '237', '0', '', '3', '0'), ('2634', '莘县', '237', '0', '', '3', '0'), ('2635', '阳谷县', '237', '0', '', '3', '0'), ('2636', '高唐县', '237', '0', '', '3', '0'), ('2637', '博兴县', '238', '0', '', '3', '0'), ('2638', '惠民县', '238', '0', '', '3', '0'), ('2639', '无棣县', '238', '0', '', '3', '0'), ('2640', '沾化县', '238', '0', '', '3', '0'), ('2641', '滨城区', '238', '0', '', '3', '0'), ('2642', '邹平县', '238', '0', '', '3', '0'), ('2643', '阳信县', '238', '0', '', '3', '0'), ('2644', '东明县', '239', '0', '', '3', '0'), ('2645', '单县', '239', '0', '', '3', '0'), ('2646', '定陶县', '239', '0', '', '3', '0'), ('2647', '巨野县', '239', '0', '', '3', '0'), ('2648', '成武县', '239', '0', '', '3', '0'), ('2649', '曹县', '239', '0', '', '3', '0'), ('2650', '牡丹区', '239', '0', '', '3', '0'), ('2651', '郓城县', '239', '0', '', '3', '0'), ('2652', '鄄城县', '239', '0', '', '3', '0'), ('2653', '上街区', '240', '0', '', '3', '0'), ('2654', '中原区', '240', '0', '', '3', '0'), ('2655', '中牟县', '240', '0', '', '3', '0'), ('2656', '二七区', '240', '0', '', '3', '0'), ('2657', '巩义市', '240', '0', '', '3', '0'), ('2658', '惠济区', '240', '0', '', '3', '0'), ('2659', '新密市', '240', '0', '', '3', '0'), ('2660', '新郑市', '240', '0', '', '3', '0'), ('2661', '登封市', '240', '0', '', '3', '0'), ('2662', '管城回族区', '240', '0', '', '3', '0'), ('2663', '荥阳市', '240', '0', '', '3', '0'), ('2664', '金水区', '240', '0', '', '3', '0'), ('2665', '兰考县', '241', '0', '', '3', '0'), ('2666', '尉氏县', '241', '0', '', '3', '0'), ('2667', '开封县', '241', '0', '', '3', '0'), ('2668', '杞县', '241', '0', '', '3', '0'), ('2669', '禹王台区', '241', '0', '', '3', '0'), ('2670', '通许县', '241', '0', '', '3', '0'), ('2671', '金明区', '241', '0', '', '3', '0'), ('2672', '顺河回族区', '241', '0', '', '3', '0'), ('2673', '鼓楼区', '241', '0', '', '3', '0'), ('2674', '龙亭区', '241', '0', '', '3', '0'), ('2675', '伊川县', '242', '0', '', '3', '0'), ('2676', '偃师市', '242', '0', '', '3', '0'), ('2677', '吉利区', '242', '0', '', '3', '0'), ('2678', '孟津县', '242', '0', '', '3', '0'), ('2679', '宜阳县', '242', '0', '', '3', '0'), ('2680', '嵩县', '242', '0', '', '3', '0'), ('2681', '新安县', '242', '0', '', '3', '0'), ('2682', '栾川县', '242', '0', '', '3', '0'), ('2683', '汝阳县', '242', '0', '', '3', '0'), ('2684', '洛宁县', '242', '0', '', '3', '0'), ('2685', '洛龙区', '242', '0', '', '3', '0'), ('2686', '涧西区', '242', '0', '', '3', '0'), ('2687', '瀍河回族区', '242', '0', '', '3', '0'), ('2688', '老城区', '242', '0', '', '3', '0'), ('2689', '西工区', '242', '0', '', '3', '0'), ('2690', '卫东区', '243', '0', '', '3', '0'), ('2691', '叶县', '243', '0', '', '3', '0'), ('2692', '宝丰县', '243', '0', '', '3', '0'), ('2693', '新华区', '243', '0', '', '3', '0'), ('2694', '汝州市', '243', '0', '', '3', '0'), ('2695', '湛河区', '243', '0', '', '3', '0'), ('2696', '石龙区', '243', '0', '', '3', '0'), ('2697', '舞钢市', '243', '0', '', '3', '0'), ('2698', '郏县', '243', '0', '', '3', '0'), ('2699', '鲁山县', '243', '0', '', '3', '0'), ('2700', '内黄县', '244', '0', '', '3', '0'), ('2701', '北关区', '244', '0', '', '3', '0'), ('2702', '安阳县', '244', '0', '', '3', '0'), ('2703', '文峰区', '244', '0', '', '3', '0'), ('2704', '林州市', '244', '0', '', '3', '0'), ('2705', '殷都区', '244', '0', '', '3', '0'), ('2706', '汤阴县', '244', '0', '', '3', '0'), ('2707', '滑县', '244', '0', '', '3', '0'), ('2708', '龙安区', '244', '0', '', '3', '0'), ('2709', '山城区', '245', '0', '', '3', '0'), ('2710', '浚县', '245', '0', '', '3', '0'), ('2711', '淇县', '245', '0', '', '3', '0'), ('2712', '淇滨区', '245', '0', '', '3', '0'), ('2713', '鹤山区', '245', '0', '', '3', '0'), ('2714', '凤泉区', '246', '0', '', '3', '0'), ('2715', '卫滨区', '246', '0', '', '3', '0'), ('2716', '卫辉市', '246', '0', '', '3', '0'), ('2717', '原阳县', '246', '0', '', '3', '0'), ('2718', '封丘县', '246', '0', '', '3', '0'), ('2719', '延津县', '246', '0', '', '3', '0'), ('2720', '新乡县', '246', '0', '', '3', '0'), ('2721', '牧野区', '246', '0', '', '3', '0'), ('2722', '红旗区', '246', '0', '', '3', '0'), ('2723', '获嘉县', '246', '0', '', '3', '0'), ('2724', '辉县市', '246', '0', '', '3', '0'), ('2725', '长垣县', '246', '0', '', '3', '0'), ('2726', '中站区', '247', '0', '', '3', '0'), ('2727', '修武县', '247', '0', '', '3', '0'), ('2728', '博爱县', '247', '0', '', '3', '0'), ('2729', '孟州市', '247', '0', '', '3', '0'), ('2730', '山阳区', '247', '0', '', '3', '0'), ('2731', '武陟县', '247', '0', '', '3', '0'), ('2732', '沁阳市', '247', '0', '', '3', '0'), ('2733', '温县', '247', '0', '', '3', '0'), ('2734', '解放区', '247', '0', '', '3', '0'), ('2735', '马村区', '247', '0', '', '3', '0'), ('2736', '华龙区', '248', '0', '', '3', '0'), ('2737', '南乐县', '248', '0', '', '3', '0'), ('2738', '台前县', '248', '0', '', '3', '0'), ('2739', '清丰县', '248', '0', '', '3', '0'), ('2740', '濮阳县', '248', '0', '', '3', '0'), ('2741', '范县', '248', '0', '', '3', '0'), ('2742', '禹州市', '249', '0', '', '3', '0'), ('2743', '襄城县', '249', '0', '', '3', '0'), ('2744', '许昌县', '249', '0', '', '3', '0'), ('2745', '鄢陵县', '249', '0', '', '3', '0'), ('2746', '长葛市', '249', '0', '', '3', '0'), ('2747', '魏都区', '249', '0', '', '3', '0'), ('2748', '临颍县', '250', '0', '', '3', '0'), ('2749', '召陵区', '250', '0', '', '3', '0'), ('2750', '源汇区', '250', '0', '', '3', '0'), ('2751', '舞阳县', '250', '0', '', '3', '0'), ('2752', '郾城区', '250', '0', '', '3', '0'), ('2753', '义马市', '251', '0', '', '3', '0'), ('2754', '卢氏县', '251', '0', '', '3', '0'), ('2755', '渑池县', '251', '0', '', '3', '0'), ('2756', '湖滨区', '251', '0', '', '3', '0'), ('2757', '灵宝市', '251', '0', '', '3', '0'), ('2758', '陕县', '251', '0', '', '3', '0'), ('2759', '内乡县', '252', '0', '', '3', '0'), ('2760', '南召县', '252', '0', '', '3', '0'), ('2761', '卧龙区', '252', '0', '', '3', '0'), ('2762', '唐河县', '252', '0', '', '3', '0'), ('2763', '宛城区', '252', '0', '', '3', '0'), ('2764', '新野县', '252', '0', '', '3', '0'), ('2765', '方城县', '252', '0', '', '3', '0'), ('2766', '桐柏县', '252', '0', '', '3', '0'), ('2767', '淅川县', '252', '0', '', '3', '0'), ('2768', '社旗县', '252', '0', '', '3', '0'), ('2769', '西峡县', '252', '0', '', '3', '0'), ('2770', '邓州市', '252', '0', '', '3', '0'), ('2771', '镇平县', '252', '0', '', '3', '0'), ('2772', '夏邑县', '253', '0', '', '3', '0'), ('2773', '宁陵县', '253', '0', '', '3', '0'), ('2774', '柘城县', '253', '0', '', '3', '0'), ('2775', '民权县', '253', '0', '', '3', '0'), ('2776', '永城市', '253', '0', '', '3', '0'), ('2777', '睢县', '253', '0', '', '3', '0'), ('2778', '睢阳区', '253', '0', '', '3', '0'), ('2779', '粱园区', '253', '0', '', '3', '0'), ('2780', '虞城县', '253', '0', '', '3', '0'), ('2781', '光山县', '254', '0', '', '3', '0'), ('2782', '商城县', '254', '0', '', '3', '0'), ('2783', '固始县', '254', '0', '', '3', '0'), ('2784', '平桥区', '254', '0', '', '3', '0'), ('2785', '息县', '254', '0', '', '3', '0'), ('2786', '新县', '254', '0', '', '3', '0'), ('2787', '浉河区', '254', '0', '', '3', '0'), ('2788', '淮滨县', '254', '0', '', '3', '0'), ('2789', '潢川县', '254', '0', '', '3', '0'), ('2790', '罗山县', '254', '0', '', '3', '0'), ('2791', '商水县', '255', '0', '', '3', '0'), ('2792', '太康县', '255', '0', '', '3', '0'), ('2793', '川汇区', '255', '0', '', '3', '0'), ('2794', '扶沟县', '255', '0', '', '3', '0'), ('2795', '沈丘县', '255', '0', '', '3', '0'), ('2796', '淮阳县', '255', '0', '', '3', '0'), ('2797', '西华县', '255', '0', '', '3', '0'), ('2798', '郸城县', '255', '0', '', '3', '0'), ('2799', '项城市', '255', '0', '', '3', '0'), ('2800', '鹿邑县', '255', '0', '', '3', '0'), ('2801', '上蔡县', '256', '0', '', '3', '0'), ('2802', '平舆县', '256', '0', '', '3', '0'), ('2803', '新蔡县', '256', '0', '', '3', '0'), ('2804', '正阳县', '256', '0', '', '3', '0'), ('2805', '汝南县', '256', '0', '', '3', '0'), ('2806', '泌阳县', '256', '0', '', '3', '0'), ('2807', '确山县', '256', '0', '', '3', '0'), ('2808', '西平县', '256', '0', '', '3', '0'), ('2809', '遂平县', '256', '0', '', '3', '0'), ('2810', '驿城区', '256', '0', '', '3', '0'), ('2811', '济源市', '257', '0', '', '3', '0'), ('2812', '东西湖区', '258', '0', '', '3', '0'), ('2813', '新洲区', '258', '0', '', '3', '0'), ('2814', '武昌区', '258', '0', '', '3', '0'), ('2815', '汉南区', '258', '0', '', '3', '0'), ('2816', '汉阳区', '258', '0', '', '3', '0'), ('2817', '江夏区', '258', '0', '', '3', '0'), ('2818', '江岸区', '258', '0', '', '3', '0'), ('2819', '江汉区', '258', '0', '', '3', '0'), ('2820', '洪山区', '258', '0', '', '3', '0'), ('2821', '硚口区', '258', '0', '', '3', '0'), ('2822', '蔡甸区', '258', '0', '', '3', '0'), ('2823', '青山区', '258', '0', '', '3', '0'), ('2824', '黄陂区', '258', '0', '', '3', '0'), ('2825', '下陆区', '259', '0', '', '3', '0'), ('2826', '大冶市', '259', '0', '', '3', '0'), ('2827', '西塞山区', '259', '0', '', '3', '0'), ('2828', '铁山区', '259', '0', '', '3', '0'), ('2829', '阳新县', '259', '0', '', '3', '0'), ('2830', '黄石港区', '259', '0', '', '3', '0'), ('2831', '丹江口市', '260', '0', '', '3', '0'), ('2832', '张湾区', '260', '0', '', '3', '0'), ('2833', '房县', '260', '0', '', '3', '0'), ('2834', '竹山县', '260', '0', '', '3', '0'), ('2835', '竹溪县', '260', '0', '', '3', '0'), ('2836', '茅箭区', '260', '0', '', '3', '0'), ('2837', '郧县', '260', '0', '', '3', '0'), ('2838', '郧西县', '260', '0', '', '3', '0'), ('2839', '五峰土家族自治县', '261', '0', '', '3', '0'), ('2840', '伍家岗区', '261', '0', '', '3', '0'), ('2841', '兴山县', '261', '0', '', '3', '0'), ('2842', '夷陵区', '261', '0', '', '3', '0'), ('2843', '宜都市', '261', '0', '', '3', '0'), ('2844', '当阳市', '261', '0', '', '3', '0'), ('2845', '枝江市', '261', '0', '', '3', '0'), ('2846', '点军区', '261', '0', '', '3', '0'), ('2847', '秭归县', '261', '0', '', '3', '0'), ('2848', '虢亭区', '261', '0', '', '3', '0'), ('2849', '西陵区', '261', '0', '', '3', '0'), ('2850', '远安县', '261', '0', '', '3', '0'), ('2851', '长阳土家族自治县', '261', '0', '', '3', '0'), ('2852', '保康县', '262', '0', '', '3', '0'), ('2853', '南漳县', '262', '0', '', '3', '0'), ('2854', '宜城市', '262', '0', '', '3', '0'), ('2855', '枣阳市', '262', '0', '', '3', '0'), ('2856', '樊城区', '262', '0', '', '3', '0'), ('2857', '老河口市', '262', '0', '', '3', '0'), ('2858', '襄城区', '262', '0', '', '3', '0'), ('2859', '襄阳区', '262', '0', '', '3', '0'), ('2860', '谷城县', '262', '0', '', '3', '0'), ('2861', '华容区', '263', '0', '', '3', '0'), ('2862', '粱子湖', '263', '0', '', '3', '0'), ('2863', '鄂城区', '263', '0', '', '3', '0'), ('2864', '东宝区', '264', '0', '', '3', '0'), ('2865', '京山县', '264', '0', '', '3', '0'), ('2866', '掇刀区', '264', '0', '', '3', '0'), ('2867', '沙洋县', '264', '0', '', '3', '0'), ('2868', '钟祥市', '264', '0', '', '3', '0'), ('2869', '云梦县', '265', '0', '', '3', '0'), ('2870', '大悟县', '265', '0', '', '3', '0'), ('2871', '孝南区', '265', '0', '', '3', '0'), ('2872', '孝昌县', '265', '0', '', '3', '0'), ('2873', '安陆市', '265', '0', '', '3', '0'), ('2874', '应城市', '265', '0', '', '3', '0'), ('2875', '汉川市', '265', '0', '', '3', '0'), ('2876', '公安县', '266', '0', '', '3', '0'), ('2877', '松滋市', '266', '0', '', '3', '0'), ('2878', '江陵县', '266', '0', '', '3', '0'), ('2879', '沙市区', '266', '0', '', '3', '0'), ('2880', '洪湖市', '266', '0', '', '3', '0'), ('2881', '监利县', '266', '0', '', '3', '0'), ('2882', '石首市', '266', '0', '', '3', '0'), ('2883', '荆州区', '266', '0', '', '3', '0'), ('2884', '团风县', '267', '0', '', '3', '0'), ('2885', '武穴市', '267', '0', '', '3', '0'), ('2886', '浠水县', '267', '0', '', '3', '0'), ('2887', '红安县', '267', '0', '', '3', '0'), ('2888', '罗田县', '267', '0', '', '3', '0'), ('2889', '英山县', '267', '0', '', '3', '0'), ('2890', '蕲春县', '267', '0', '', '3', '0'), ('2891', '麻城市', '267', '0', '', '3', '0'), ('2892', '黄州区', '267', '0', '', '3', '0'), ('2893', '黄梅县', '267', '0', '', '3', '0'), ('2894', '咸安区', '268', '0', '', '3', '0'), ('2895', '嘉鱼县', '268', '0', '', '3', '0'), ('2896', '崇阳县', '268', '0', '', '3', '0'), ('2897', '赤壁市', '268', '0', '', '3', '0'), ('2898', '通城县', '268', '0', '', '3', '0'), ('2899', '通山县', '268', '0', '', '3', '0'), ('2900', '广水市', '269', '0', '', '3', '0'), ('2901', '曾都区', '269', '0', '', '3', '0'), ('2902', '利川市', '270', '0', '', '3', '0'), ('2903', '咸丰县', '270', '0', '', '3', '0'), ('2904', '宣恩县', '270', '0', '', '3', '0'), ('2905', '巴东县', '270', '0', '', '3', '0'), ('2906', '建始县', '270', '0', '', '3', '0'), ('2907', '恩施市', '270', '0', '', '3', '0'), ('2908', '来凤县', '270', '0', '', '3', '0'), ('2909', '鹤峰县', '270', '0', '', '3', '0'), ('2910', '仙桃市', '271', '0', '', '3', '0'), ('2911', '潜江市', '272', '0', '', '3', '0'), ('2912', '天门市', '273', '0', '', '3', '0'), ('2913', '神农架林区', '274', '0', '', '3', '0'), ('2914', '天心区', '275', '0', '', '3', '0'), ('2915', '宁乡县', '275', '0', '', '3', '0'), ('2916', '岳麓区', '275', '0', '', '3', '0'), ('2917', '开福区', '275', '0', '', '3', '0'), ('2918', '望城县', '275', '0', '', '3', '0'), ('2919', '浏阳市', '275', '0', '', '3', '0'), ('2920', '芙蓉区', '275', '0', '', '3', '0'), ('2921', '长沙县', '275', '0', '', '3', '0'), ('2922', '雨花区', '275', '0', '', '3', '0'), ('2923', '天元区', '276', '0', '', '3', '0'), ('2924', '攸县', '276', '0', '', '3', '0'), ('2925', '株洲县', '276', '0', '', '3', '0'), ('2926', '炎陵县', '276', '0', '', '3', '0'), ('2927', '石峰区', '276', '0', '', '3', '0'), ('2928', '芦淞区', '276', '0', '', '3', '0'), ('2929', '茶陵县', '276', '0', '', '3', '0'), ('2930', '荷塘区', '276', '0', '', '3', '0'), ('2931', '醴陵市', '276', '0', '', '3', '0'), ('2932', '岳塘区', '277', '0', '', '3', '0'), ('2933', '湘乡市', '277', '0', '', '3', '0'), ('2934', '湘潭县', '277', '0', '', '3', '0'), ('2935', '雨湖区', '277', '0', '', '3', '0'), ('2936', '韶山市', '277', '0', '', '3', '0'), ('2937', '南岳区', '278', '0', '', '3', '0'), ('2938', '常宁市', '278', '0', '', '3', '0'), ('2939', '珠晖区', '278', '0', '', '3', '0'), ('2940', '石鼓区', '278', '0', '', '3', '0'), ('2941', '祁东县', '278', '0', '', '3', '0'), ('2942', '耒阳市', '278', '0', '', '3', '0'), ('2943', '蒸湘区', '278', '0', '', '3', '0'), ('2944', '衡东县', '278', '0', '', '3', '0'), ('2945', '衡南县', '278', '0', '', '3', '0'), ('2946', '衡山县', '278', '0', '', '3', '0'), ('2947', '衡阳县', '278', '0', '', '3', '0'), ('2948', '雁峰区', '278', '0', '', '3', '0'), ('2949', '北塔区', '279', '0', '', '3', '0'), ('2950', '双清区', '279', '0', '', '3', '0'), ('2951', '城步苗族自治县', '279', '0', '', '3', '0'), ('2952', '大祥区', '279', '0', '', '3', '0'), ('2953', '新宁县', '279', '0', '', '3', '0'), ('2954', '新邵县', '279', '0', '', '3', '0'), ('2955', '武冈市', '279', '0', '', '3', '0'), ('2956', '洞口县', '279', '0', '', '3', '0'), ('2957', '绥宁县', '279', '0', '', '3', '0'), ('2958', '邵东县', '279', '0', '', '3', '0'), ('2959', '邵阳县', '279', '0', '', '3', '0'), ('2960', '隆回县', '279', '0', '', '3', '0'), ('2961', '临湘市', '280', '0', '', '3', '0'), ('2962', '云溪区', '280', '0', '', '3', '0'), ('2963', '华容县', '280', '0', '', '3', '0'), ('2964', '君山区', '280', '0', '', '3', '0'), ('2965', '岳阳县', '280', '0', '', '3', '0'), ('2966', '岳阳楼区', '280', '0', '', '3', '0'), ('2967', '平江县', '280', '0', '', '3', '0'), ('2968', '汨罗市', '280', '0', '', '3', '0'), ('2969', '湘阴县', '280', '0', '', '3', '0'), ('2970', '临澧县', '281', '0', '', '3', '0'), ('2971', '安乡县', '281', '0', '', '3', '0'), ('2972', '桃源县', '281', '0', '', '3', '0'), ('2973', '武陵区', '281', '0', '', '3', '0'), ('2974', '汉寿县', '281', '0', '', '3', '0'), ('2975', '津市市', '281', '0', '', '3', '0'), ('2976', '澧县', '281', '0', '', '3', '0'), ('2977', '石门县', '281', '0', '', '3', '0'), ('2978', '鼎城区', '281', '0', '', '3', '0'), ('2979', '慈利县', '282', '0', '', '3', '0'), ('2980', '桑植县', '282', '0', '', '3', '0'), ('2981', '武陵源区', '282', '0', '', '3', '0'), ('2982', '永定区', '282', '0', '', '3', '0'), ('2983', '南县', '283', '0', '', '3', '0'), ('2984', '安化县', '283', '0', '', '3', '0'), ('2985', '桃江县', '283', '0', '', '3', '0'), ('2986', '沅江市', '283', '0', '', '3', '0'), ('2987', '资阳区', '283', '0', '', '3', '0'), ('2988', '赫山区', '283', '0', '', '3', '0'), ('2989', '临武县', '284', '0', '', '3', '0'), ('2990', '北湖区', '284', '0', '', '3', '0'), ('2991', '嘉禾县', '284', '0', '', '3', '0'), ('2992', '安仁县', '284', '0', '', '3', '0'), ('2993', '宜章县', '284', '0', '', '3', '0'), ('2994', '桂东县', '284', '0', '', '3', '0'), ('2995', '桂阳县', '284', '0', '', '3', '0'), ('2996', '永兴县', '284', '0', '', '3', '0'), ('2997', '汝城县', '284', '0', '', '3', '0'), ('2998', '苏仙区', '284', '0', '', '3', '0'), ('2999', '资兴市', '284', '0', '', '3', '0'), ('3000', '东安县', '285', '0', '', '3', '0'), ('3001', '冷水滩区', '285', '0', '', '3', '0'), ('3002', '双牌县', '285', '0', '', '3', '0'), ('3003', '宁远县', '285', '0', '', '3', '0'), ('3004', '新田县', '285', '0', '', '3', '0'), ('3005', '江华瑶族自治县', '285', '0', '', '3', '0'), ('3006', '江永县', '285', '0', '', '3', '0'), ('3007', '祁阳县', '285', '0', '', '3', '0'), ('3008', '蓝山县', '285', '0', '', '3', '0'), ('3009', '道县', '285', '0', '', '3', '0'), ('3010', '零陵区', '285', '0', '', '3', '0'), ('3011', '中方县', '286', '0', '', '3', '0'), ('3012', '会同县', '286', '0', '', '3', '0'), ('3013', '新晃侗族自治县', '286', '0', '', '3', '0'), ('3014', '沅陵县', '286', '0', '', '3', '0'), ('3015', '洪江市/洪江区', '286', '0', '', '3', '0'), ('3016', '溆浦县', '286', '0', '', '3', '0'), ('3017', '芷江侗族自治县', '286', '0', '', '3', '0'), ('3018', '辰溪县', '286', '0', '', '3', '0'), ('3019', '通道侗族自治县', '286', '0', '', '3', '0'), ('3020', '靖州苗族侗族自治县', '286', '0', '', '3', '0'), ('3021', '鹤城区', '286', '0', '', '3', '0'), ('3022', '麻阳苗族自治县', '286', '0', '', '3', '0'), ('3023', '冷水江市', '287', '0', '', '3', '0'), ('3024', '双峰县', '287', '0', '', '3', '0'), ('3025', '娄星区', '287', '0', '', '3', '0'), ('3026', '新化县', '287', '0', '', '3', '0'), ('3027', '涟源市', '287', '0', '', '3', '0'), ('3028', '保靖县', '288', '0', '', '3', '0'), ('3029', '凤凰县', '288', '0', '', '3', '0'), ('3030', '古丈县', '288', '0', '', '3', '0'), ('3031', '吉首市', '288', '0', '', '3', '0'), ('3032', '永顺县', '288', '0', '', '3', '0'), ('3033', '泸溪县', '288', '0', '', '3', '0'), ('3034', '花垣县', '288', '0', '', '3', '0'), ('3035', '龙山县', '288', '0', '', '3', '0'), ('3036', '萝岗区', '289', '0', '', '3', '0'), ('3037', '南沙区', '289', '0', '', '3', '0'), ('3038', '从化市', '289', '0', '', '3', '0'), ('3039', '增城市', '289', '0', '', '3', '0'), ('3040', '天河区', '289', '0', '', '3', '0'), ('3041', '海珠区', '289', '0', '', '3', '0'), ('3042', '番禺区', '289', '0', '', '3', '0'), ('3043', '白云区', '289', '0', '', '3', '0'), ('3044', '花都区', '289', '0', '', '3', '0'), ('3045', '荔湾区', '289', '0', '', '3', '0'), ('3046', '越秀区', '289', '0', '', '3', '0'), ('3047', '黄埔区', '289', '0', '', '3', '0'), ('3048', '乐昌市', '290', '0', '', '3', '0'), ('3049', '乳源瑶族自治县', '290', '0', '', '3', '0'), ('3050', '仁化县', '290', '0', '', '3', '0'), ('3051', '南雄市', '290', '0', '', '3', '0'), ('3052', '始兴县', '290', '0', '', '3', '0'), ('3053', '新丰县', '290', '0', '', '3', '0'), ('3054', '曲江区', '290', '0', '', '3', '0'), ('3055', '武江区', '290', '0', '', '3', '0'), ('3056', '浈江区', '290', '0', '', '3', '0'), ('3057', '翁源县', '290', '0', '', '3', '0'), ('3058', '南山区', '291', '0', '', '3', '0'), ('3059', '宝安区', '291', '0', '', '3', '0'), ('3060', '盐田区', '291', '0', '', '3', '0'), ('3061', '福田区', '291', '0', '', '3', '0'), ('3062', '罗湖区', '291', '0', '', '3', '0'), ('3063', '龙岗区', '291', '0', '', '3', '0'), ('3064', '斗门区', '292', '0', '', '3', '0'), ('3065', '金湾区', '292', '0', '', '3', '0'), ('3066', '香洲区', '292', '0', '', '3', '0'), ('3067', '南澳县', '293', '0', '', '3', '0'), ('3068', '潮南区', '293', '0', '', '3', '0'), ('3069', '潮阳区', '293', '0', '', '3', '0'), ('3070', '澄海区', '293', '0', '', '3', '0'), ('3071', '濠江区', '293', '0', '', '3', '0'), ('3072', '金平区', '293', '0', '', '3', '0'), ('3073', '龙湖区', '293', '0', '', '3', '0'), ('3074', '三水区', '294', '0', '', '3', '0'), ('3075', '南海区', '294', '0', '', '3', '0'), ('3076', '禅城区', '294', '0', '', '3', '0'), ('3077', '顺德区', '294', '0', '', '3', '0'), ('3078', '高明区', '294', '0', '', '3', '0'), ('3079', '台山市', '295', '0', '', '3', '0'), ('3080', '开平市', '295', '0', '', '3', '0'), ('3081', '恩平市', '295', '0', '', '3', '0'), ('3082', '新会区', '295', '0', '', '3', '0'), ('3083', '江海区', '295', '0', '', '3', '0'), ('3084', '蓬江区', '295', '0', '', '3', '0'), ('3085', '鹤山市', '295', '0', '', '3', '0'), ('3086', '吴川市', '296', '0', '', '3', '0'), ('3087', '坡头区', '296', '0', '', '3', '0'), ('3088', '廉江市', '296', '0', '', '3', '0'), ('3089', '徐闻县', '296', '0', '', '3', '0'), ('3090', '赤坎区', '296', '0', '', '3', '0'), ('3091', '遂溪县', '296', '0', '', '3', '0'), ('3092', '雷州市', '296', '0', '', '3', '0'), ('3093', '霞山区', '296', '0', '', '3', '0'), ('3094', '麻章区', '296', '0', '', '3', '0'), ('3095', '信宜市', '297', '0', '', '3', '0'), ('3096', '化州市', '297', '0', '', '3', '0'), ('3097', '电白县', '297', '0', '', '3', '0'), ('3098', '茂南区', '297', '0', '', '3', '0'), ('3099', '茂港区', '297', '0', '', '3', '0'), ('3100', '高州市', '297', '0', '', '3', '0'), ('3101', '四会市', '298', '0', '', '3', '0'), ('3102', '封开县', '298', '0', '', '3', '0'), ('3103', '广宁县', '298', '0', '', '3', '0'), ('3104', '德庆县', '298', '0', '', '3', '0'), ('3105', '怀集县', '298', '0', '', '3', '0'), ('3106', '端州区', '298', '0', '', '3', '0'), ('3107', '高要市', '298', '0', '', '3', '0'), ('3108', '鼎湖区', '298', '0', '', '3', '0'), ('3109', '博罗县', '299', '0', '', '3', '0'), ('3110', '惠东县', '299', '0', '', '3', '0'), ('3111', '惠城区', '299', '0', '', '3', '0'), ('3112', '惠阳区', '299', '0', '', '3', '0'), ('3113', '龙门县', '299', '0', '', '3', '0'), ('3114', '丰顺县', '300', '0', '', '3', '0'), ('3115', '五华县', '300', '0', '', '3', '0'), ('3116', '兴宁市', '300', '0', '', '3', '0'), ('3117', '大埔县', '300', '0', '', '3', '0'), ('3118', '平远县', '300', '0', '', '3', '0'), ('3119', '梅县', '300', '0', '', '3', '0'), ('3120', '梅江区', '300', '0', '', '3', '0'), ('3121', '蕉岭县', '300', '0', '', '3', '0'), ('3122', '城区', '301', '0', '', '3', '0'), ('3123', '海丰县', '301', '0', '', '3', '0'), ('3124', '陆丰市', '301', '0', '', '3', '0'), ('3125', '陆河县', '301', '0', '', '3', '0'), ('3126', '东源县', '302', '0', '', '3', '0'), ('3127', '和平县', '302', '0', '', '3', '0'), ('3128', '源城区', '302', '0', '', '3', '0'), ('3129', '紫金县', '302', '0', '', '3', '0'), ('3130', '连平县', '302', '0', '', '3', '0'), ('3131', '龙川县', '302', '0', '', '3', '0'), ('3132', '江城区', '303', '0', '', '3', '0'), ('3133', '阳东县', '303', '0', '', '3', '0'), ('3134', '阳春市', '303', '0', '', '3', '0'), ('3135', '阳西县', '303', '0', '', '3', '0'), ('3136', '佛冈县', '304', '0', '', '3', '0'), ('3137', '清城区', '304', '0', '', '3', '0'), ('3138', '清新县', '304', '0', '', '3', '0'), ('3139', '英德市', '304', '0', '', '3', '0'), ('3140', '连南瑶族自治县', '304', '0', '', '3', '0'), ('3141', '连山壮族瑶族自治县', '304', '0', '', '3', '0'), ('3142', '连州市', '304', '0', '', '3', '0'), ('3143', '阳山县', '304', '0', '', '3', '0'), ('3144', '东莞市', '305', '0', '', '3', '0'), ('3145', '中山市', '306', '0', '', '3', '0'), ('3146', '湘桥区', '307', '0', '', '3', '0'), ('3147', '潮安县', '307', '0', '', '3', '0'), ('3148', '饶平县', '307', '0', '', '3', '0'), ('3149', '惠来县', '308', '0', '', '3', '0'), ('3150', '揭东县', '308', '0', '', '3', '0'), ('3151', '揭西县', '308', '0', '', '3', '0'), ('3152', '普宁市', '308', '0', '', '3', '0'), ('3153', '榕城区', '308', '0', '', '3', '0'), ('3154', '云城区', '309', '0', '', '3', '0'), ('3155', '云安县', '309', '0', '', '3', '0'), ('3156', '新兴县', '309', '0', '', '3', '0'), ('3157', '罗定市', '309', '0', '', '3', '0'), ('3158', '郁南县', '309', '0', '', '3', '0'), ('3159', '上林县', '310', '0', '', '3', '0'), ('3160', '兴宁区', '310', '0', '', '3', '0'), ('3161', '宾阳县', '310', '0', '', '3', '0'), ('3162', '横县', '310', '0', '', '3', '0'), ('3163', '武鸣县', '310', '0', '', '3', '0'), ('3164', '江南区', '310', '0', '', '3', '0'), ('3165', '良庆区', '310', '0', '', '3', '0'), ('3166', '西乡塘区', '310', '0', '', '3', '0'), ('3167', '邕宁区', '310', '0', '', '3', '0'), ('3168', '隆安县', '310', '0', '', '3', '0'), ('3169', '青秀区', '310', '0', '', '3', '0'), ('3170', '马山县', '310', '0', '', '3', '0'), ('3171', '三江侗族自治县', '311', '0', '', '3', '0'), ('3172', '城中区', '311', '0', '', '3', '0'), ('3173', '柳北区', '311', '0', '', '3', '0'), ('3174', '柳南区', '311', '0', '', '3', '0'), ('3175', '柳城县', '311', '0', '', '3', '0'), ('3176', '柳江县', '311', '0', '', '3', '0'), ('3177', '融安县', '311', '0', '', '3', '0'), ('3178', '融水苗族自治县', '311', '0', '', '3', '0'), ('3179', '鱼峰区', '311', '0', '', '3', '0'), ('3180', '鹿寨县', '311', '0', '', '3', '0'), ('3181', '七星区', '312', '0', '', '3', '0'), ('3182', '临桂县', '312', '0', '', '3', '0'), ('3183', '全州县', '312', '0', '', '3', '0'), ('3184', '兴安县', '312', '0', '', '3', '0'), ('3185', '叠彩区', '312', '0', '', '3', '0'), ('3186', '平乐县', '312', '0', '', '3', '0'), ('3187', '恭城瑶族自治县', '312', '0', '', '3', '0'), ('3188', '永福县', '312', '0', '', '3', '0'), ('3189', '灌阳县', '312', '0', '', '3', '0'), ('3190', '灵川县', '312', '0', '', '3', '0'), ('3191', '秀峰区', '312', '0', '', '3', '0'), ('3192', '荔浦县', '312', '0', '', '3', '0'), ('3193', '象山区', '312', '0', '', '3', '0'), ('3194', '资源县', '312', '0', '', '3', '0'), ('3195', '阳朔县', '312', '0', '', '3', '0'), ('3196', '雁山区', '312', '0', '', '3', '0'), ('3197', '龙胜各族自治县', '312', '0', '', '3', '0'), ('3198', '万秀区', '313', '0', '', '3', '0'), ('3199', '岑溪市', '313', '0', '', '3', '0'), ('3200', '苍梧县', '313', '0', '', '3', '0'), ('3201', '蒙山县', '313', '0', '', '3', '0'), ('3202', '藤县', '313', '0', '', '3', '0'), ('3203', '蝶山区', '313', '0', '', '3', '0'), ('3204', '长洲区', '313', '0', '', '3', '0'), ('3205', '合浦县', '314', '0', '', '3', '0'), ('3206', '海城区', '314', '0', '', '3', '0'), ('3207', '铁山港区', '314', '0', '', '3', '0'), ('3208', '银海区', '314', '0', '', '3', '0'), ('3209', '上思县', '315', '0', '', '3', '0'), ('3210', '东兴市', '315', '0', '', '3', '0'), ('3211', '港口区', '315', '0', '', '3', '0'), ('3212', '防城区', '315', '0', '', '3', '0'), ('3213', '浦北县', '316', '0', '', '3', '0'), ('3214', '灵山县', '316', '0', '', '3', '0'), ('3215', '钦北区', '316', '0', '', '3', '0'), ('3216', '钦南区', '316', '0', '', '3', '0'), ('3217', '平南县', '317', '0', '', '3', '0'), ('3218', '桂平市', '317', '0', '', '3', '0'), ('3219', '港北区', '317', '0', '', '3', '0'), ('3220', '港南区', '317', '0', '', '3', '0'), ('3221', '覃塘区', '317', '0', '', '3', '0'), ('3222', '兴业县', '318', '0', '', '3', '0'), ('3223', '北流市', '318', '0', '', '3', '0'), ('3224', '博白县', '318', '0', '', '3', '0'), ('3225', '容县', '318', '0', '', '3', '0'), ('3226', '玉州区', '318', '0', '', '3', '0'), ('3227', '陆川县', '318', '0', '', '3', '0'), ('3228', '乐业县', '319', '0', '', '3', '0'), ('3229', '凌云县', '319', '0', '', '3', '0'), ('3230', '右江区', '319', '0', '', '3', '0'), ('3231', '平果县', '319', '0', '', '3', '0'), ('3232', '德保县', '319', '0', '', '3', '0'), ('3233', '田东县', '319', '0', '', '3', '0'), ('3234', '田林县', '319', '0', '', '3', '0'), ('3235', '田阳县', '319', '0', '', '3', '0'), ('3236', '西林县', '319', '0', '', '3', '0'), ('3237', '那坡县', '319', '0', '', '3', '0'), ('3238', '隆林各族自治县', '319', '0', '', '3', '0'), ('3239', '靖西县', '319', '0', '', '3', '0'), ('3240', '八步区', '320', '0', '', '3', '0'), ('3241', '富川瑶族自治县', '320', '0', '', '3', '0'), ('3242', '昭平县', '320', '0', '', '3', '0'), ('3243', '钟山县', '320', '0', '', '3', '0'), ('3244', '东兰县', '321', '0', '', '3', '0'), ('3245', '凤山县', '321', '0', '', '3', '0'), ('3246', '南丹县', '321', '0', '', '3', '0'), ('3247', '大化瑶族自治县', '321', '0', '', '3', '0'), ('3248', '天峨县', '321', '0', '', '3', '0'), ('3249', '宜州市', '321', '0', '', '3', '0'), ('3250', '巴马瑶族自治县', '321', '0', '', '3', '0'), ('3251', '环江毛南族自治县', '321', '0', '', '3', '0'), ('3252', '罗城仫佬族自治县', '321', '0', '', '3', '0'), ('3253', '都安瑶族自治县', '321', '0', '', '3', '0'), ('3254', '金城江区', '321', '0', '', '3', '0'), ('3255', '兴宾区', '322', '0', '', '3', '0'), ('3256', '合山市', '322', '0', '', '3', '0'), ('3257', '忻城县', '322', '0', '', '3', '0'), ('3258', '武宣县', '322', '0', '', '3', '0'), ('3259', '象州县', '322', '0', '', '3', '0'), ('3260', '金秀瑶族自治县', '322', '0', '', '3', '0'), ('3261', '凭祥市', '323', '0', '', '3', '0'), ('3262', '大新县', '323', '0', '', '3', '0'), ('3263', '天等县', '323', '0', '', '3', '0'), ('3264', '宁明县', '323', '0', '', '3', '0'), ('3265', '扶绥县', '323', '0', '', '3', '0'), ('3266', '江州区', '323', '0', '', '3', '0'), ('3267', '龙州县', '323', '0', '', '3', '0'), ('3268', '琼山区', '324', '0', '', '3', '0'), ('3269', '秀英区', '324', '0', '', '3', '0'), ('3270', '美兰区', '324', '0', '', '3', '0'), ('3271', '龙华区', '324', '0', '', '3', '0'), ('3272', '三亚市', '325', '0', '', '3', '0'), ('3273', '五指山市', '326', '0', '', '3', '0'), ('3274', '琼海市', '327', '0', '', '3', '0'), ('3275', '儋州市', '328', '0', '', '3', '0'), ('3276', '文昌市', '329', '0', '', '3', '0'), ('3277', '万宁市', '330', '0', '', '3', '0'), ('3278', '东方市', '331', '0', '', '3', '0'), ('3279', '定安县', '332', '0', '', '3', '0'), ('3280', '屯昌县', '333', '0', '', '3', '0'), ('3281', '澄迈县', '334', '0', '', '3', '0'), ('3282', '临高县', '335', '0', '', '3', '0'), ('3283', '白沙黎族自治县', '336', '0', '', '3', '0'), ('3284', '昌江黎族自治县', '337', '0', '', '3', '0'), ('3285', '乐东黎族自治县', '338', '0', '', '3', '0'), ('3286', '陵水黎族自治县', '339', '0', '', '3', '0'), ('3287', '保亭黎族苗族自治县', '340', '0', '', '3', '0'), ('3288', '琼中黎族苗族自治县', '341', '0', '', '3', '0'), ('4209', '双流县', '385', '0', '', '3', '0'), ('4210', '大邑县', '385', '0', '', '3', '0'), ('4211', '崇州市', '385', '0', '', '3', '0'), ('4212', '彭州市', '385', '0', '', '3', '0'), ('4213', '成华区', '385', '0', '', '3', '0'), ('4214', '新津县', '385', '0', '', '3', '0'), ('4215', '新都区', '385', '0', '', '3', '0'), ('4216', '武侯区', '385', '0', '', '3', '0'), ('4217', '温江区', '385', '0', '', '3', '0'), ('4218', '蒲江县', '385', '0', '', '3', '0'), ('4219', '邛崃市', '385', '0', '', '3', '0'), ('4220', '郫县', '385', '0', '', '3', '0'), ('4221', '都江堰市', '385', '0', '', '3', '0'), ('4222', '金堂县', '385', '0', '', '3', '0'), ('4223', '金牛区', '385', '0', '', '3', '0'), ('4224', '锦江区', '385', '0', '', '3', '0'), ('4225', '青白江区', '385', '0', '', '3', '0'), ('4226', '青羊区', '385', '0', '', '3', '0'), ('4227', '龙泉驿区', '385', '0', '', '3', '0'), ('4228', '大安区', '386', '0', '', '3', '0'), ('4229', '富顺县', '386', '0', '', '3', '0'), ('4230', '沿滩区', '386', '0', '', '3', '0'), ('4231', '自流井区', '386', '0', '', '3', '0'), ('4232', '荣县', '386', '0', '', '3', '0'), ('4233', '贡井区', '386', '0', '', '3', '0'), ('4234', '东区', '387', '0', '', '3', '0'), ('4235', '仁和区', '387', '0', '', '3', '0'), ('4236', '盐边县', '387', '0', '', '3', '0'), ('4237', '米易县', '387', '0', '', '3', '0'), ('4238', '西区', '387', '0', '', '3', '0'), ('4239', '叙永县', '388', '0', '', '3', '0'), ('4240', '古蔺县', '388', '0', '', '3', '0'), ('4241', '合江县', '388', '0', '', '3', '0'), ('4242', '江阳区', '388', '0', '', '3', '0'), ('4243', '泸县', '388', '0', '', '3', '0'), ('4244', '纳溪区', '388', '0', '', '3', '0'), ('4245', '龙马潭区', '388', '0', '', '3', '0'), ('4246', '中江县', '389', '0', '', '3', '0'), ('4247', '什邡市', '389', '0', '', '3', '0'), ('4248', '广汉市', '389', '0', '', '3', '0'), ('4249', '旌阳区', '389', '0', '', '3', '0'), ('4250', '绵竹市', '389', '0', '', '3', '0'), ('4251', '罗江县', '389', '0', '', '3', '0'), ('4252', '三台县', '390', '0', '', '3', '0'), ('4253', '北川羌族自治县', '390', '0', '', '3', '0'), ('4254', '安县', '390', '0', '', '3', '0'), ('4255', '平武县', '390', '0', '', '3', '0'), ('4256', '梓潼县', '390', '0', '', '3', '0'), ('4257', '江油市', '390', '0', '', '3', '0'), ('4258', '涪城区', '390', '0', '', '3', '0'), ('4259', '游仙区', '390', '0', '', '3', '0'), ('4260', '盐亭县', '390', '0', '', '3', '0'), ('4261', '元坝区', '391', '0', '', '3', '0'), ('4262', '利州区', '391', '0', '', '3', '0'), ('4263', '剑阁县', '391', '0', '', '3', '0'), ('4264', '旺苍县', '391', '0', '', '3', '0'), ('4265', '朝天区', '391', '0', '', '3', '0'), ('4266', '苍溪县', '391', '0', '', '3', '0'), ('4267', '青川县', '391', '0', '', '3', '0'), ('4268', '大英县', '392', '0', '', '3', '0'), ('4269', '安居区', '392', '0', '', '3', '0'), ('4270', '射洪县', '392', '0', '', '3', '0'), ('4271', '船山区', '392', '0', '', '3', '0'), ('4272', '蓬溪县', '392', '0', '', '3', '0'), ('4273', '东兴区', '393', '0', '', '3', '0'), ('4274', '威远县', '393', '0', '', '3', '0'), ('4275', '市中区', '393', '0', '', '3', '0'), ('4276', '资中县', '393', '0', '', '3', '0'), ('4277', '隆昌县', '393', '0', '', '3', '0'), ('4278', '五通桥区', '394', '0', '', '3', '0'), ('4279', '井研县', '394', '0', '', '3', '0'), ('4280', '夹江县', '394', '0', '', '3', '0'), ('4281', '峨眉山市', '394', '0', '', '3', '0'), ('4282', '峨边彝族自治县', '394', '0', '', '3', '0'), ('4283', '市中区', '394', '0', '', '3', '0'), ('4284', '沐川县', '394', '0', '', '3', '0'), ('4285', '沙湾区', '394', '0', '', '3', '0'), ('4286', '犍为县', '394', '0', '', '3', '0'), ('4287', '金口河区', '394', '0', '', '3', '0'), ('4288', '马边彝族自治县', '394', '0', '', '3', '0'), ('4289', '仪陇县', '395', '0', '', '3', '0'), ('4290', '南充市嘉陵区', '395', '0', '', '3', '0'), ('4291', '南部县', '395', '0', '', '3', '0'), ('4292', '嘉陵区', '395', '0', '', '3', '0'), ('4293', '营山县', '395', '0', '', '3', '0'), ('4294', '蓬安县', '395', '0', '', '3', '0'), ('4295', '西充县', '395', '0', '', '3', '0'), ('4296', '阆中市', '395', '0', '', '3', '0'), ('4297', '顺庆区', '395', '0', '', '3', '0'), ('4298', '高坪区', '395', '0', '', '3', '0'), ('4299', '东坡区', '396', '0', '', '3', '0'), ('4300', '丹棱县', '396', '0', '', '3', '0'), ('4301', '仁寿县', '396', '0', '', '3', '0'), ('4302', '彭山县', '396', '0', '', '3', '0'), ('4303', '洪雅县', '396', '0', '', '3', '0'), ('4304', '青神县', '396', '0', '', '3', '0'), ('4305', '兴文县', '397', '0', '', '3', '0'), ('4306', '南溪县', '397', '0', '', '3', '0'), ('4307', '宜宾县', '397', '0', '', '3', '0'), ('4308', '屏山县', '397', '0', '', '3', '0'), ('4309', '江安县', '397', '0', '', '3', '0'), ('4310', '珙县', '397', '0', '', '3', '0'), ('4311', '筠连县', '397', '0', '', '3', '0'), ('4312', '翠屏区', '397', '0', '', '3', '0'), ('4313', '长宁县', '397', '0', '', '3', '0'), ('4314', '高县', '397', '0', '', '3', '0'), ('4315', '华蓥市', '398', '0', '', '3', '0'), ('4316', '岳池县', '398', '0', '', '3', '0'), ('4317', '广安区', '398', '0', '', '3', '0'), ('4318', '武胜县', '398', '0', '', '3', '0'), ('4319', '邻水县', '398', '0', '', '3', '0'), ('4320', '万源市', '399', '0', '', '3', '0'), ('4321', '大竹县', '399', '0', '', '3', '0'), ('4322', '宣汉县', '399', '0', '', '3', '0'), ('4323', '开江县', '399', '0', '', '3', '0'), ('4324', '渠县', '399', '0', '', '3', '0'), ('4325', '达县', '399', '0', '', '3', '0'), ('4326', '通川区', '399', '0', '', '3', '0'), ('4327', '名山县', '400', '0', '', '3', '0'), ('4328', '天全县', '400', '0', '', '3', '0'), ('4329', '宝兴县', '400', '0', '', '3', '0'), ('4330', '汉源县', '400', '0', '', '3', '0'), ('4331', '石棉县', '400', '0', '', '3', '0'), ('4332', '芦山县', '400', '0', '', '3', '0'), ('4333', '荥经县', '400', '0', '', '3', '0'), ('4334', '雨城区', '400', '0', '', '3', '0'), ('4335', '南江县', '401', '0', '', '3', '0'), ('4336', '巴州区', '401', '0', '', '3', '0'), ('4337', '平昌县', '401', '0', '', '3', '0'), ('4338', '通江县', '401', '0', '', '3', '0'), ('4339', '乐至县', '402', '0', '', '3', '0'), ('4340', '安岳县', '402', '0', '', '3', '0'), ('4341', '简阳市', '402', '0', '', '3', '0'), ('4342', '雁江区', '402', '0', '', '3', '0'), ('4343', '九寨沟县', '403', '0', '', '3', '0'), ('4344', '壤塘县', '403', '0', '', '3', '0'), ('4345', '小金县', '403', '0', '', '3', '0'), ('4346', '松潘县', '403', '0', '', '3', '0'), ('4347', '汶川县', '403', '0', '', '3', '0'), ('4348', '理县', '403', '0', '', '3', '0'), ('4349', '红原县', '403', '0', '', '3', '0'), ('4350', '若尔盖县', '403', '0', '', '3', '0'), ('4351', '茂县', '403', '0', '', '3', '0'), ('4352', '金川县', '403', '0', '', '3', '0'), ('4353', '阿坝县', '403', '0', '', '3', '0'), ('4354', '马尔康县', '403', '0', '', '3', '0'), ('4355', '黑水县', '403', '0', '', '3', '0'), ('4356', '丹巴县', '404', '0', '', '3', '0'), ('4357', '乡城县', '404', '0', '', '3', '0'), ('4358', '巴塘县', '404', '0', '', '3', '0'), ('4359', '康定县', '404', '0', '', '3', '0'), ('4360', '得荣县', '404', '0', '', '3', '0'), ('4361', '德格县', '404', '0', '', '3', '0'), ('4362', '新龙县', '404', '0', '', '3', '0'), ('4363', '泸定县', '404', '0', '', '3', '0'), ('4364', '炉霍县', '404', '0', '', '3', '0'), ('4365', '理塘县', '404', '0', '', '3', '0'), ('4366', '甘孜县', '404', '0', '', '3', '0'), ('4367', '白玉县', '404', '0', '', '3', '0'), ('4368', '石渠县', '404', '0', '', '3', '0'), ('4369', '稻城县', '404', '0', '', '3', '0'), ('4370', '色达县', '404', '0', '', '3', '0'), ('4371', '道孚县', '404', '0', '', '3', '0'), ('4372', '雅江县', '404', '0', '', '3', '0'), ('4373', '会东县', '405', '0', '', '3', '0'), ('4374', '会理县', '405', '0', '', '3', '0'), ('4375', '冕宁县', '405', '0', '', '3', '0'), ('4376', '喜德县', '405', '0', '', '3', '0'), ('4377', '宁南县', '405', '0', '', '3', '0'), ('4378', '布拖县', '405', '0', '', '3', '0'), ('4379', '德昌县', '405', '0', '', '3', '0'), ('4380', '昭觉县', '405', '0', '', '3', '0'), ('4381', '普格县', '405', '0', '', '3', '0'), ('4382', '木里藏族自治县', '405', '0', '', '3', '0'), ('4383', '甘洛县', '405', '0', '', '3', '0'), ('4384', '盐源县', '405', '0', '', '3', '0'), ('4385', '美姑县', '405', '0', '', '3', '0'), ('4386', '西昌', '405', '0', '', '3', '0'), ('4387', '越西县', '405', '0', '', '3', '0'), ('4388', '金阳县', '405', '0', '', '3', '0'), ('4389', '雷波县', '405', '0', '', '3', '0'), ('4390', '乌当区', '406', '0', '', '3', '0'), ('4391', '云岩区', '406', '0', '', '3', '0'), ('4392', '修文县', '406', '0', '', '3', '0'), ('4393', '南明区', '406', '0', '', '3', '0'), ('4394', '小河区', '406', '0', '', '3', '0'), ('4395', '开阳县', '406', '0', '', '3', '0'), ('4396', '息烽县', '406', '0', '', '3', '0'), ('4397', '清镇市', '406', '0', '', '3', '0'), ('4398', '白云区', '406', '0', '', '3', '0'), ('4399', '花溪区', '406', '0', '', '3', '0'), ('4400', '六枝特区', '407', '0', '', '3', '0'), ('4401', '水城县', '407', '0', '', '3', '0'), ('4402', '盘县', '407', '0', '', '3', '0'), ('4403', '钟山区', '407', '0', '', '3', '0'), ('4404', '习水县', '408', '0', '', '3', '0'), ('4405', '仁怀市', '408', '0', '', '3', '0'), ('4406', '余庆县', '408', '0', '', '3', '0'), ('4407', '凤冈县', '408', '0', '', '3', '0'), ('4408', '务川仡佬族苗族自治县', '408', '0', '', '3', '0'), ('4409', '桐梓县', '408', '0', '', '3', '0'), ('4410', '正安县', '408', '0', '', '3', '0'), ('4411', '汇川区', '408', '0', '', '3', '0'), ('4412', '湄潭县', '408', '0', '', '3', '0'), ('4413', '红花岗区', '408', '0', '', '3', '0'), ('4414', '绥阳县', '408', '0', '', '3', '0'), ('4415', '赤水市', '408', '0', '', '3', '0'), ('4416', '道真仡佬族苗族自治县', '408', '0', '', '3', '0'), ('4417', '遵义县', '408', '0', '', '3', '0'), ('4418', '关岭布依族苗族自治县', '409', '0', '', '3', '0'), ('4419', '平坝县', '409', '0', '', '3', '0'), ('4420', '普定县', '409', '0', '', '3', '0'), ('4421', '紫云苗族布依族自治县', '409', '0', '', '3', '0'), ('4422', '西秀区', '409', '0', '', '3', '0'), ('4423', '镇宁布依族苗族自治县', '409', '0', '', '3', '0'), ('4424', '万山特区', '410', '0', '', '3', '0'), ('4425', '印江土家族苗族自治县', '410', '0', '', '3', '0'), ('4426', '德江县', '410', '0', '', '3', '0'), ('4427', '思南县', '410', '0', '', '3', '0'), ('4428', '松桃苗族自治县', '410', '0', '', '3', '0'), ('4429', '江口县', '410', '0', '', '3', '0'), ('4430', '沿河土家族自治县', '410', '0', '', '3', '0'), ('4431', '玉屏侗族自治县', '410', '0', '', '3', '0'), ('4432', '石阡县', '410', '0', '', '3', '0'), ('4433', '铜仁市', '410', '0', '', '3', '0'), ('4434', '兴义市', '411', '0', '', '3', '0'), ('4435', '兴仁县', '411', '0', '', '3', '0'), ('4436', '册亨县', '411', '0', '', '3', '0'), ('4437', '安龙县', '411', '0', '', '3', '0'), ('4438', '普安县', '411', '0', '', '3', '0'), ('4439', '晴隆县', '411', '0', '', '3', '0'), ('4440', '望谟县', '411', '0', '', '3', '0'), ('4441', '贞丰县', '411', '0', '', '3', '0'), ('4442', '大方县', '412', '0', '', '3', '0'), ('4443', '威宁彝族回族苗族自治县', '412', '0', '', '3', '0'), ('4444', '毕节市', '412', '0', '', '3', '0'), ('4445', '纳雍县', '412', '0', '', '3', '0'), ('4446', '织金县', '412', '0', '', '3', '0'), ('4447', '赫章县', '412', '0', '', '3', '0'), ('4448', '金沙县', '412', '0', '', '3', '0'), ('4449', '黔西县', '412', '0', '', '3', '0'), ('4450', '三穗县', '413', '0', '', '3', '0'), ('4451', '丹寨县', '413', '0', '', '3', '0'), ('4452', '从江县', '413', '0', '', '3', '0'), ('4453', '凯里市', '413', '0', '', '3', '0'), ('4454', '剑河县', '413', '0', '', '3', '0'), ('4455', '台江县', '413', '0', '', '3', '0'), ('4456', '天柱县', '413', '0', '', '3', '0'), ('4457', '岑巩县', '413', '0', '', '3', '0'), ('4458', '施秉县', '413', '0', '', '3', '0'), ('4459', '榕江县', '413', '0', '', '3', '0'), ('4460', '锦屏县', '413', '0', '', '3', '0'), ('4461', '镇远县', '413', '0', '', '3', '0'), ('4462', '雷山县', '413', '0', '', '3', '0'), ('4463', '麻江县', '413', '0', '', '3', '0'), ('4464', '黄平县', '413', '0', '', '3', '0'), ('4465', '黎平县', '413', '0', '', '3', '0'), ('4466', '三都水族自治县', '414', '0', '', '3', '0'), ('4467', '平塘县', '414', '0', '', '3', '0'), ('4468', '惠水县', '414', '0', '', '3', '0'), ('4469', '独山县', '414', '0', '', '3', '0'), ('4470', '瓮安县', '414', '0', '', '3', '0'), ('4471', '福泉市', '414', '0', '', '3', '0'), ('4472', '罗甸县', '414', '0', '', '3', '0'), ('4473', '荔波县', '414', '0', '', '3', '0'), ('4474', '贵定县', '414', '0', '', '3', '0'), ('4475', '都匀市', '414', '0', '', '3', '0'), ('4476', '长顺县', '414', '0', '', '3', '0'), ('4477', '龙里县', '414', '0', '', '3', '0'), ('4478', '东川区', '415', '0', '', '3', '0'), ('4479', '五华区', '415', '0', '', '3', '0'), ('4480', '呈贡县', '415', '0', '', '3', '0'), ('4481', '安宁市', '415', '0', '', '3', '0'), ('4482', '官渡区', '415', '0', '', '3', '0'), ('4483', '宜良县', '415', '0', '', '3', '0'), ('4484', '富民县', '415', '0', '', '3', '0'), ('4485', '寻甸回族彝族自治县', '415', '0', '', '3', '0'), ('4486', '嵩明县', '415', '0', '', '3', '0'), ('4487', '晋宁县', '415', '0', '', '3', '0'), ('4488', '盘龙区', '415', '0', '', '3', '0'), ('4489', '石林彝族自治县', '415', '0', '', '3', '0'), ('4490', '禄劝彝族苗族自治县', '415', '0', '', '3', '0'), ('4491', '西山区', '415', '0', '', '3', '0'), ('4492', '会泽县', '416', '0', '', '3', '0'), ('4493', '宣威市', '416', '0', '', '3', '0'), ('4494', '富源县', '416', '0', '', '3', '0'), ('4495', '师宗县', '416', '0', '', '3', '0'), ('4496', '沾益县', '416', '0', '', '3', '0'), ('4497', '罗平县', '416', '0', '', '3', '0'), ('4498', '陆良县', '416', '0', '', '3', '0'), ('4499', '马龙县', '416', '0', '', '3', '0'), ('4500', '麒麟区', '416', '0', '', '3', '0'), ('4501', '元江哈尼族彝族傣族自治县', '417', '0', '', '3', '0'), ('4502', '华宁县', '417', '0', '', '3', '0'), ('4503', '峨山彝族自治县', '417', '0', '', '3', '0'), ('4504', '新平彝族傣族自治县', '417', '0', '', '3', '0'), ('4505', '易门县', '417', '0', '', '3', '0'), ('4506', '江川县', '417', '0', '', '3', '0'), ('4507', '澄江县', '417', '0', '', '3', '0'), ('4508', '红塔区', '417', '0', '', '3', '0'), ('4509', '通海县', '417', '0', '', '3', '0'), ('4510', '施甸县', '418', '0', '', '3', '0'), ('4511', '昌宁县', '418', '0', '', '3', '0'), ('4512', '腾冲县', '418', '0', '', '3', '0'), ('4513', '隆阳区', '418', '0', '', '3', '0'), ('4514', '龙陵县', '418', '0', '', '3', '0'), ('4515', '大关县', '419', '0', '', '3', '0'), ('4516', '威信县', '419', '0', '', '3', '0'), ('4517', '巧家县', '419', '0', '', '3', '0'), ('4518', '彝良县', '419', '0', '', '3', '0'), ('4519', '昭阳区', '419', '0', '', '3', '0'), ('4520', '水富县', '419', '0', '', '3', '0'), ('4521', '永善县', '419', '0', '', '3', '0'), ('4522', '盐津县', '419', '0', '', '3', '0'), ('4523', '绥江县', '419', '0', '', '3', '0'), ('4524', '镇雄县', '419', '0', '', '3', '0'), ('4525', '鲁甸县', '419', '0', '', '3', '0'), ('4526', '华坪县', '420', '0', '', '3', '0'), ('4527', '古城区', '420', '0', '', '3', '0'), ('4528', '宁蒗彝族自治县', '420', '0', '', '3', '0'), ('4529', '永胜县', '420', '0', '', '3', '0'), ('4530', '玉龙纳西族自治县', '420', '0', '', '3', '0'), ('4531', '临翔区', '422', '0', '', '3', '0'), ('4532', '云县', '422', '0', '', '3', '0'), ('4533', '凤庆县', '422', '0', '', '3', '0'), ('4534', '双江拉祜族佤族布朗族傣族自治县', '422', '0', '', '3', '0'), ('4535', '永德县', '422', '0', '', '3', '0'), ('4536', '沧源佤族自治县', '422', '0', '', '3', '0'), ('4537', '耿马傣族佤族自治县', '422', '0', '', '3', '0'), ('4538', '镇康县', '422', '0', '', '3', '0'), ('4539', '元谋县', '423', '0', '', '3', '0'), ('4540', '南华县', '423', '0', '', '3', '0'), ('4541', '双柏县', '423', '0', '', '3', '0'), ('4542', '大姚县', '423', '0', '', '3', '0'), ('4543', '姚安县', '423', '0', '', '3', '0'), ('4544', '楚雄市', '423', '0', '', '3', '0'), ('4545', '武定县', '423', '0', '', '3', '0'), ('4546', '永仁县', '423', '0', '', '3', '0'), ('4547', '牟定县', '423', '0', '', '3', '0'), ('4548', '禄丰县', '423', '0', '', '3', '0'), ('4549', '个旧市', '424', '0', '', '3', '0'), ('4550', '元阳县', '424', '0', '', '3', '0'), ('4551', '屏边苗族自治县', '424', '0', '', '3', '0'), ('4552', '建水县', '424', '0', '', '3', '0'), ('4553', '开远市', '424', '0', '', '3', '0'), ('4554', '弥勒县', '424', '0', '', '3', '0'), ('4555', '河口瑶族自治县', '424', '0', '', '3', '0'), ('4556', '泸西县', '424', '0', '', '3', '0'), ('4557', '石屏县', '424', '0', '', '3', '0'), ('4558', '红河县', '424', '0', '', '3', '0'), ('4559', '绿春县', '424', '0', '', '3', '0'), ('4560', '蒙自县', '424', '0', '', '3', '0'), ('4561', '金平苗族瑶族傣族自治县', '424', '0', '', '3', '0'), ('4562', '丘北县', '425', '0', '', '3', '0'), ('4563', '富宁县', '425', '0', '', '3', '0'), ('4564', '广南县', '425', '0', '', '3', '0'), ('4565', '文山县', '425', '0', '', '3', '0'), ('4566', '砚山县', '425', '0', '', '3', '0'), ('4567', '西畴县', '425', '0', '', '3', '0'), ('4568', '马关县', '425', '0', '', '3', '0'), ('4569', '麻栗坡县', '425', '0', '', '3', '0'), ('4570', '勐海县', '426', '0', '', '3', '0'), ('4571', '勐腊县', '426', '0', '', '3', '0'), ('4572', '景洪市', '426', '0', '', '3', '0'), ('4573', '云龙县', '427', '0', '', '3', '0'), ('4574', '剑川县', '427', '0', '', '3', '0'), ('4575', '南涧彝族自治县', '427', '0', '', '3', '0'), ('4576', '大理市', '427', '0', '', '3', '0'), ('4577', '宾川县', '427', '0', '', '3', '0'), ('4578', '巍山彝族回族自治县', '427', '0', '', '3', '0'), ('4579', '弥渡县', '427', '0', '', '3', '0'), ('4580', '永平县', '427', '0', '', '3', '0'), ('4581', '洱源县', '427', '0', '', '3', '0'), ('4582', '漾濞彝族自治县', '427', '0', '', '3', '0'), ('4583', '祥云县', '427', '0', '', '3', '0'), ('4584', '鹤庆县', '427', '0', '', '3', '0'), ('4585', '梁河县', '428', '0', '', '3', '0'), ('4586', '潞西市', '428', '0', '', '3', '0'), ('4587', '瑞丽市', '428', '0', '', '3', '0'), ('4588', '盈江县', '428', '0', '', '3', '0'), ('4589', '陇川县', '428', '0', '', '3', '0'), ('4590', '德钦县', '430', '0', '', '3', '0'), ('4591', '维西傈僳族自治县', '430', '0', '', '3', '0'), ('4592', '香格里拉县', '430', '0', '', '3', '0'), ('4593', '城关区', '431', '0', '', '3', '0'), ('4594', '堆龙德庆县', '431', '0', '', '3', '0'), ('4595', '墨竹工卡县', '431', '0', '', '3', '0'), ('4596', '尼木县', '431', '0', '', '3', '0'), ('4597', '当雄县', '431', '0', '', '3', '0'), ('4598', '曲水县', '431', '0', '', '3', '0'), ('4599', '林周县', '431', '0', '', '3', '0'), ('4600', '达孜县', '431', '0', '', '3', '0'), ('4601', '丁青县', '432', '0', '', '3', '0'), ('4602', '八宿县', '432', '0', '', '3', '0'), ('4603', '察雅县', '432', '0', '', '3', '0'), ('4604', '左贡县', '432', '0', '', '3', '0'), ('4605', '昌都县', '432', '0', '', '3', '0'), ('4606', '江达县', '432', '0', '', '3', '0'), ('4607', '洛隆县', '432', '0', '', '3', '0'), ('4608', '类乌齐县', '432', '0', '', '3', '0'), ('4609', '芒康县', '432', '0', '', '3', '0'), ('4610', '贡觉县', '432', '0', '', '3', '0'), ('4611', '边坝县', '432', '0', '', '3', '0'), ('4612', '乃东县', '433', '0', '', '3', '0'), ('4613', '加查县', '433', '0', '', '3', '0'), ('4614', '扎囊县', '433', '0', '', '3', '0'), ('4615', '措美县', '433', '0', '', '3', '0'), ('4616', '曲松县', '433', '0', '', '3', '0'), ('4617', '桑日县', '433', '0', '', '3', '0'), ('4618', '洛扎县', '433', '0', '', '3', '0'), ('4619', '浪卡子县', '433', '0', '', '3', '0'), ('4620', '琼结县', '433', '0', '', '3', '0'), ('4621', '贡嘎县', '433', '0', '', '3', '0'), ('4622', '错那县', '433', '0', '', '3', '0'), ('4623', '隆子县', '433', '0', '', '3', '0'), ('4624', '亚东县', '434', '0', '', '3', '0'), ('4625', '仁布县', '434', '0', '', '3', '0'), ('4626', '仲巴县', '434', '0', '', '3', '0'), ('4627', '南木林县', '434', '0', '', '3', '0'), ('4628', '吉隆县', '434', '0', '', '3', '0'), ('4629', '定日县', '434', '0', '', '3', '0'), ('4630', '定结县', '434', '0', '', '3', '0'), ('4631', '岗巴县', '434', '0', '', '3', '0'), ('4632', '康马县', '434', '0', '', '3', '0'), ('4633', '拉孜县', '434', '0', '', '3', '0'), ('4634', '日喀则市', '434', '0', '', '3', '0'), ('4635', '昂仁县', '434', '0', '', '3', '0'), ('4636', '江孜县', '434', '0', '', '3', '0'), ('4637', '白朗县', '434', '0', '', '3', '0'), ('4638', '聂拉木县', '434', '0', '', '3', '0'), ('4639', '萨嘎县', '434', '0', '', '3', '0'), ('4640', '萨迦县', '434', '0', '', '3', '0'), ('4641', '谢通门县', '434', '0', '', '3', '0'), ('4642', '嘉黎县', '435', '0', '', '3', '0'), ('4643', '安多县', '435', '0', '', '3', '0'), ('4644', '尼玛县', '435', '0', '', '3', '0'), ('4645', '巴青县', '435', '0', '', '3', '0'), ('4646', '比如县', '435', '0', '', '3', '0'), ('4647', '班戈县', '435', '0', '', '3', '0'), ('4648', '申扎县', '435', '0', '', '3', '0'), ('4649', '索县', '435', '0', '', '3', '0'), ('4650', '聂荣县', '435', '0', '', '3', '0'), ('4651', '那曲县', '435', '0', '', '3', '0'), ('4652', '噶尔县', '436', '0', '', '3', '0'), ('4653', '措勤县', '436', '0', '', '3', '0'), ('4654', '改则县', '436', '0', '', '3', '0'), ('4655', '日土县', '436', '0', '', '3', '0'), ('4656', '普兰县', '436', '0', '', '3', '0'), ('4657', '札达县', '436', '0', '', '3', '0'), ('4658', '革吉县', '436', '0', '', '3', '0'), ('4659', '墨脱县', '437', '0', '', '3', '0'), ('4660', '察隅县', '437', '0', '', '3', '0'), ('4661', '工布江达县', '437', '0', '', '3', '0'), ('4662', '朗县', '437', '0', '', '3', '0'), ('4663', '林芝县', '437', '0', '', '3', '0'), ('4664', '波密县', '437', '0', '', '3', '0'), ('4665', '米林县', '437', '0', '', '3', '0'), ('4666', '临潼区', '438', '0', '', '3', '0'), ('4667', '周至县', '438', '0', '', '3', '0'), ('4668', '户县', '438', '0', '', '3', '0'), ('4669', '新城区', '438', '0', '', '3', '0'), ('4670', '未央区', '438', '0', '', '3', '0'), ('4671', '灞桥区', '438', '0', '', '3', '0'), ('4672', '碑林区', '438', '0', '', '3', '0'), ('4673', '莲湖区', '438', '0', '', '3', '0'), ('4674', '蓝田县', '438', '0', '', '3', '0'), ('4675', '长安区', '438', '0', '', '3', '0'), ('4676', '阎良区', '438', '0', '', '3', '0'), ('4677', '雁塔区', '438', '0', '', '3', '0'), ('4678', '高陵县', '438', '0', '', '3', '0'), ('4679', '印台区', '439', '0', '', '3', '0'), ('4680', '宜君县', '439', '0', '', '3', '0'), ('4681', '王益区', '439', '0', '', '3', '0'), ('4682', '耀州区', '439', '0', '', '3', '0'), ('4683', '凤县', '440', '0', '', '3', '0'), ('4684', '凤翔县', '440', '0', '', '3', '0'), ('4685', '千阳县', '440', '0', '', '3', '0'), ('4686', '太白县', '440', '0', '', '3', '0'), ('4687', '岐山县', '440', '0', '', '3', '0'), ('4688', '扶风县', '440', '0', '', '3', '0'), ('4689', '渭滨区', '440', '0', '', '3', '0'), ('4690', '眉县', '440', '0', '', '3', '0'), ('4691', '金台区', '440', '0', '', '3', '0'), ('4692', '陇县', '440', '0', '', '3', '0'), ('4693', '陈仓区', '440', '0', '', '3', '0'), ('4694', '麟游县', '440', '0', '', '3', '0'), ('4695', '三原县', '441', '0', '', '3', '0'), ('4696', '干县', '441', '0', '', '3', '0'), ('4697', '兴平市', '441', '0', '', '3', '0'), ('4698', '彬县', '441', '0', '', '3', '0'), ('4699', '旬邑县', '441', '0', '', '3', '0'), ('4700', '杨陵区', '441', '0', '', '3', '0'), ('4701', '武功县', '441', '0', '', '3', '0'), ('4702', '永寿县', '441', '0', '', '3', '0'), ('4703', '泾阳县', '441', '0', '', '3', '0'), ('4704', '淳化县', '441', '0', '', '3', '0'), ('4705', '渭城区', '441', '0', '', '3', '0'), ('4706', '礼泉县', '441', '0', '', '3', '0'), ('4707', '秦都区', '441', '0', '', '3', '0'), ('4708', '长武县', '441', '0', '', '3', '0'), ('4709', '临渭区', '442', '0', '', '3', '0'), ('4710', '华县', '442', '0', '', '3', '0'), ('4711', '华阴市', '442', '0', '', '3', '0'), ('4712', '合阳县', '442', '0', '', '3', '0'), ('4713', '大荔县', '442', '0', '', '3', '0'), ('4714', '富平县', '442', '0', '', '3', '0'), ('4715', '潼关县', '442', '0', '', '3', '0'), ('4716', '澄城县', '442', '0', '', '3', '0'), ('4717', '白水县', '442', '0', '', '3', '0'), ('4718', '蒲城县', '442', '0', '', '3', '0'), ('4719', '韩城市', '442', '0', '', '3', '0'), ('4720', '吴起县', '443', '0', '', '3', '0'), ('4721', '子长县', '443', '0', '', '3', '0'), ('4722', '安塞县', '443', '0', '', '3', '0'), ('4723', '宜川县', '443', '0', '', '3', '0'), ('4724', '宝塔区', '443', '0', '', '3', '0'), ('4725', '富县', '443', '0', '', '3', '0'), ('4726', '延川县', '443', '0', '', '3', '0'), ('4727', '延长县', '443', '0', '', '3', '0'), ('4728', '志丹县', '443', '0', '', '3', '0'), ('4729', '洛川县', '443', '0', '', '3', '0'), ('4730', '甘泉县', '443', '0', '', '3', '0'), ('4731', '黄陵县', '443', '0', '', '3', '0'), ('4732', '黄龙县', '443', '0', '', '3', '0'), ('4733', '佛坪县', '444', '0', '', '3', '0'), ('4734', '勉县', '444', '0', '', '3', '0'), ('4735', '南郑县', '444', '0', '', '3', '0'), ('4736', '城固县', '444', '0', '', '3', '0'), ('4737', '宁强县', '444', '0', '', '3', '0'), ('4738', '汉台区', '444', '0', '', '3', '0'), ('4739', '洋县', '444', '0', '', '3', '0'), ('4740', '留坝县', '444', '0', '', '3', '0'), ('4741', '略阳县', '444', '0', '', '3', '0'), ('4742', '西乡县', '444', '0', '', '3', '0'), ('4743', '镇巴县', '444', '0', '', '3', '0'), ('4744', '佳县', '445', '0', '', '3', '0'), ('4745', '吴堡县', '445', '0', '', '3', '0'), ('4746', '子洲县', '445', '0', '', '3', '0'), ('4747', '定边县', '445', '0', '', '3', '0'), ('4748', '府谷县', '445', '0', '', '3', '0'), ('4749', '榆林市榆阳区', '445', '0', '', '3', '0'), ('4750', '横山县', '445', '0', '', '3', '0'), ('4751', '清涧县', '445', '0', '', '3', '0'), ('4752', '神木县', '445', '0', '', '3', '0'), ('4753', '米脂县', '445', '0', '', '3', '0'), ('4754', '绥德县', '445', '0', '', '3', '0'), ('4755', '靖边县', '445', '0', '', '3', '0'), ('4756', '宁陕县', '446', '0', '', '3', '0'), ('4757', '岚皋县', '446', '0', '', '3', '0'), ('4758', '平利县', '446', '0', '', '3', '0'), ('4759', '旬阳县', '446', '0', '', '3', '0'), ('4760', '汉滨区', '446', '0', '', '3', '0');
INSERT INTO `yf_base_district` VALUES ('4761', '汉阴县', '446', '0', '', '3', '0'), ('4762', '白河县', '446', '0', '', '3', '0'), ('4763', '石泉县', '446', '0', '', '3', '0'), ('4764', '紫阳县', '446', '0', '', '3', '0'), ('4765', '镇坪县', '446', '0', '', '3', '0'), ('4766', '丹凤县', '447', '0', '', '3', '0'), ('4767', '商南县', '447', '0', '', '3', '0'), ('4768', '商州区', '447', '0', '', '3', '0'), ('4769', '山阳县', '447', '0', '', '3', '0'), ('4770', '柞水县', '447', '0', '', '3', '0'), ('4771', '洛南县', '447', '0', '', '3', '0'), ('4772', '镇安县', '447', '0', '', '3', '0'), ('4773', '七里河区', '448', '0', '', '3', '0'), ('4774', '城关区', '448', '0', '', '3', '0'), ('4775', '安宁区', '448', '0', '', '3', '0'), ('4776', '榆中县', '448', '0', '', '3', '0'), ('4777', '永登县', '448', '0', '', '3', '0'), ('4778', '皋兰县', '448', '0', '', '3', '0'), ('4779', '红古区', '448', '0', '', '3', '0'), ('4780', '西固区', '448', '0', '', '3', '0'), ('4781', '嘉峪关市', '449', '0', '', '3', '0'), ('4782', '永昌县', '450', '0', '', '3', '0'), ('4783', '金川区', '450', '0', '', '3', '0'), ('4784', '会宁县', '451', '0', '', '3', '0'), ('4785', '平川区', '451', '0', '', '3', '0'), ('4786', '景泰县', '451', '0', '', '3', '0'), ('4787', '白银区', '451', '0', '', '3', '0'), ('4788', '靖远县', '451', '0', '', '3', '0'), ('4789', '张家川回族自治县', '452', '0', '', '3', '0'), ('4790', '武山县', '452', '0', '', '3', '0'), ('4791', '清水县', '452', '0', '', '3', '0'), ('4792', '甘谷县', '452', '0', '', '3', '0'), ('4793', '秦安县', '452', '0', '', '3', '0'), ('4794', '秦州区', '452', '0', '', '3', '0'), ('4795', '麦积区', '452', '0', '', '3', '0'), ('4796', '凉州区', '453', '0', '', '3', '0'), ('4797', '古浪县', '453', '0', '', '3', '0'), ('4798', '天祝藏族自治县', '453', '0', '', '3', '0'), ('4799', '民勤县', '453', '0', '', '3', '0'), ('4800', '临泽县', '454', '0', '', '3', '0'), ('4801', '山丹县', '454', '0', '', '3', '0'), ('4802', '民乐县', '454', '0', '', '3', '0'), ('4803', '甘州区', '454', '0', '', '3', '0'), ('4804', '肃南裕固族自治县', '454', '0', '', '3', '0'), ('4805', '高台县', '454', '0', '', '3', '0'), ('4806', '华亭县', '455', '0', '', '3', '0'), ('4807', '崆峒区', '455', '0', '', '3', '0'), ('4808', '崇信县', '455', '0', '', '3', '0'), ('4809', '庄浪县', '455', '0', '', '3', '0'), ('4810', '泾川县', '455', '0', '', '3', '0'), ('4811', '灵台县', '455', '0', '', '3', '0'), ('4812', '静宁县', '455', '0', '', '3', '0'), ('4813', '敦煌市', '456', '0', '', '3', '0'), ('4814', '玉门市', '456', '0', '', '3', '0'), ('4815', '瓜州县（原安西县）', '456', '0', '', '3', '0'), ('4816', '肃北蒙古族自治县', '456', '0', '', '3', '0'), ('4817', '肃州区', '456', '0', '', '3', '0'), ('4818', '金塔县', '456', '0', '', '3', '0'), ('4819', '阿克塞哈萨克族自治县', '456', '0', '', '3', '0'), ('4820', '华池县', '457', '0', '', '3', '0'), ('4821', '合水县', '457', '0', '', '3', '0'), ('4822', '宁县', '457', '0', '', '3', '0'), ('4823', '庆城县', '457', '0', '', '3', '0'), ('4824', '正宁县', '457', '0', '', '3', '0'), ('4825', '环县', '457', '0', '', '3', '0'), ('4826', '西峰区', '457', '0', '', '3', '0'), ('4827', '镇原县', '457', '0', '', '3', '0'), ('4828', '临洮县', '458', '0', '', '3', '0'), ('4829', '安定区', '458', '0', '', '3', '0'), ('4830', '岷县', '458', '0', '', '3', '0'), ('4831', '渭源县', '458', '0', '', '3', '0'), ('4832', '漳县', '458', '0', '', '3', '0'), ('4833', '通渭县', '458', '0', '', '3', '0'), ('4834', '陇西县', '458', '0', '', '3', '0'), ('4835', '两当县', '459', '0', '', '3', '0'), ('4836', '宕昌县', '459', '0', '', '3', '0'), ('4837', '康县', '459', '0', '', '3', '0'), ('4838', '徽县', '459', '0', '', '3', '0'), ('4839', '成县', '459', '0', '', '3', '0'), ('4840', '文县', '459', '0', '', '3', '0'), ('4841', '武都区', '459', '0', '', '3', '0'), ('4842', '礼县', '459', '0', '', '3', '0'), ('4843', '西和县', '459', '0', '', '3', '0'), ('4844', '东乡族自治县', '460', '0', '', '3', '0'), ('4845', '临夏县', '460', '0', '', '3', '0'), ('4846', '临夏市', '460', '0', '', '3', '0'), ('4847', '和政县', '460', '0', '', '3', '0'), ('4848', '广河县', '460', '0', '', '3', '0'), ('4849', '康乐县', '460', '0', '', '3', '0'), ('4850', '永靖县', '460', '0', '', '3', '0'), ('4851', '积石山保安族东乡族撒拉族自治县', '460', '0', '', '3', '0'), ('4852', '临潭县', '461', '0', '', '3', '0'), ('4853', '卓尼县', '461', '0', '', '3', '0'), ('4854', '合作市', '461', '0', '', '3', '0'), ('4855', '夏河县', '461', '0', '', '3', '0'), ('4856', '玛曲县', '461', '0', '', '3', '0'), ('4857', '碌曲县', '461', '0', '', '3', '0'), ('4858', '舟曲县', '461', '0', '', '3', '0'), ('4859', '迭部县', '461', '0', '', '3', '0'), ('4860', '城东区', '462', '0', '', '3', '0'), ('4861', '城中区', '462', '0', '', '3', '0'), ('4862', '城北区', '462', '0', '', '3', '0'), ('4863', '城西区', '462', '0', '', '3', '0'), ('4864', '大通回族土族自治县', '462', '0', '', '3', '0'), ('4865', '湟中县', '462', '0', '', '3', '0'), ('4866', '湟源县', '462', '0', '', '3', '0'), ('4867', '乐都县', '463', '0', '', '3', '0'), ('4868', '互助土族自治县', '463', '0', '', '3', '0'), ('4869', '化隆回族自治县', '463', '0', '', '3', '0'), ('4870', '平安县', '463', '0', '', '3', '0'), ('4871', '循化撒拉族自治县', '463', '0', '', '3', '0'), ('4872', '民和回族土族自治县', '463', '0', '', '3', '0'), ('4873', '刚察县', '464', '0', '', '3', '0'), ('4874', '海晏县', '464', '0', '', '3', '0'), ('4875', '祁连县', '464', '0', '', '3', '0'), ('4876', '门源回族自治县', '464', '0', '', '3', '0'), ('4877', '同仁县', '465', '0', '', '3', '0'), ('4878', '尖扎县', '465', '0', '', '3', '0'), ('4879', '河南蒙古族自治县', '465', '0', '', '3', '0'), ('4880', '泽库县', '465', '0', '', '3', '0'), ('4881', '共和县', '466', '0', '', '3', '0'), ('4882', '兴海县', '466', '0', '', '3', '0'), ('4883', '同德县', '466', '0', '', '3', '0'), ('4884', '贵南县', '466', '0', '', '3', '0'), ('4885', '贵德县', '466', '0', '', '3', '0'), ('4886', '久治县', '467', '0', '', '3', '0'), ('4887', '玛多县', '467', '0', '', '3', '0'), ('4888', '玛沁县', '467', '0', '', '3', '0'), ('4889', '班玛县', '467', '0', '', '3', '0'), ('4890', '甘德县', '467', '0', '', '3', '0'), ('4891', '达日县', '467', '0', '', '3', '0'), ('4892', '囊谦县', '468', '0', '', '3', '0'), ('4893', '曲麻莱县', '468', '0', '', '3', '0'), ('4894', '杂多县', '468', '0', '', '3', '0'), ('4895', '治多县', '468', '0', '', '3', '0'), ('4896', '玉树县', '468', '0', '', '3', '0'), ('4897', '称多县', '468', '0', '', '3', '0'), ('4898', '乌兰县', '469', '0', '', '3', '0'), ('4899', '冷湖行委', '469', '0', '', '3', '0'), ('4900', '大柴旦行委', '469', '0', '', '3', '0'), ('4901', '天峻县', '469', '0', '', '3', '0'), ('4902', '德令哈市', '469', '0', '', '3', '0'), ('4903', '格尔木市', '469', '0', '', '3', '0'), ('4904', '茫崖行委', '469', '0', '', '3', '0'), ('4905', '都兰县', '469', '0', '', '3', '0'), ('4906', '兴庆区', '470', '0', '', '3', '0'), ('4907', '永宁县', '470', '0', '', '3', '0'), ('4908', '灵武市', '470', '0', '', '3', '0'), ('4909', '西夏区', '470', '0', '', '3', '0'), ('4910', '贺兰县', '470', '0', '', '3', '0'), ('4911', '金凤区', '470', '0', '', '3', '0'), ('4912', '大武口区', '471', '0', '', '3', '0'), ('4913', '平罗县', '471', '0', '', '3', '0'), ('4914', '惠农区', '471', '0', '', '3', '0'), ('4915', '利通区', '472', '0', '', '3', '0'), ('4916', '同心县', '472', '0', '', '3', '0'), ('4917', '盐池县', '472', '0', '', '3', '0'), ('4918', '青铜峡市', '472', '0', '', '3', '0'), ('4919', '原州区', '473', '0', '', '3', '0'), ('4920', '彭阳县', '473', '0', '', '3', '0'), ('4921', '泾源县', '473', '0', '', '3', '0'), ('4922', '西吉县', '473', '0', '', '3', '0'), ('4923', '隆德县', '473', '0', '', '3', '0'), ('4924', '中宁县', '474', '0', '', '3', '0'), ('4925', '沙坡头区', '474', '0', '', '3', '0'), ('4926', '海原县', '474', '0', '', '3', '0'), ('4927', '东山区', '475', '0', '', '3', '0'), ('4928', '乌鲁木齐县', '475', '0', '', '3', '0'), ('4929', '天山区', '475', '0', '', '3', '0'), ('4930', '头屯河区', '475', '0', '', '3', '0'), ('4931', '新市区', '475', '0', '', '3', '0'), ('4932', '水磨沟区', '475', '0', '', '3', '0'), ('4933', '沙依巴克区', '475', '0', '', '3', '0'), ('4934', '达坂城区', '475', '0', '', '3', '0'), ('4935', '乌尔禾区', '476', '0', '', '3', '0'), ('4936', '克拉玛依区', '476', '0', '', '3', '0'), ('4937', '独山子区', '476', '0', '', '3', '0'), ('4938', '白碱滩区', '476', '0', '', '3', '0'), ('4939', '吐鲁番市', '477', '0', '', '3', '0'), ('4940', '托克逊县', '477', '0', '', '3', '0'), ('4941', '鄯善县', '477', '0', '', '3', '0'), ('4942', '伊吾县', '478', '0', '', '3', '0'), ('4943', '哈密市', '478', '0', '', '3', '0'), ('4944', '巴里坤哈萨克自治县', '478', '0', '', '3', '0'), ('4945', '吉木萨尔县', '479', '0', '', '3', '0'), ('4946', '呼图壁县', '479', '0', '', '3', '0'), ('4947', '奇台县', '479', '0', '', '3', '0'), ('4948', '昌吉市', '479', '0', '', '3', '0'), ('4949', '木垒哈萨克自治县', '479', '0', '', '3', '0'), ('4950', '玛纳斯县', '479', '0', '', '3', '0'), ('4951', '米泉市', '479', '0', '', '3', '0'), ('4952', '阜康市', '479', '0', '', '3', '0'), ('4953', '博乐市', '480', '0', '', '3', '0'), ('4954', '温泉县', '480', '0', '', '3', '0'), ('4955', '精河县', '480', '0', '', '3', '0'), ('4956', '博湖县', '481', '0', '', '3', '0'), ('4957', '和硕县', '481', '0', '', '3', '0'), ('4958', '和静县', '481', '0', '', '3', '0'), ('4959', '尉犁县', '481', '0', '', '3', '0'), ('4960', '库尔勒市', '481', '0', '', '3', '0'), ('4961', '焉耆回族自治县', '481', '0', '', '3', '0'), ('4962', '若羌县', '481', '0', '', '3', '0'), ('4963', '轮台县', '481', '0', '', '3', '0'), ('4964', '乌什县', '482', '0', '', '3', '0'), ('4965', '库车县', '482', '0', '', '3', '0'), ('4966', '拜城县', '482', '0', '', '3', '0'), ('4967', '新和县', '482', '0', '', '3', '0'), ('4968', '柯坪县', '482', '0', '', '3', '0'), ('4969', '沙雅县', '482', '0', '', '3', '0'), ('4970', '温宿县', '482', '0', '', '3', '0'), ('4971', '阿克苏市', '482', '0', '', '3', '0'), ('4972', '阿瓦提县', '482', '0', '', '3', '0'), ('4973', '乌恰县', '483', '0', '', '3', '0'), ('4974', '阿克陶县', '483', '0', '', '3', '0'), ('4975', '阿合奇县', '483', '0', '', '3', '0'), ('4976', '阿图什市', '483', '0', '', '3', '0'), ('4977', '伽师县', '484', '0', '', '3', '0'), ('4978', '叶城县', '484', '0', '', '3', '0'), ('4979', '喀什市', '484', '0', '', '3', '0'), ('4980', '塔什库尔干塔吉克自治县', '484', '0', '', '3', '0'), ('4981', '岳普湖县', '484', '0', '', '3', '0'), ('4982', '巴楚县', '484', '0', '', '3', '0'), ('4983', '泽普县', '484', '0', '', '3', '0'), ('4984', '疏勒县', '484', '0', '', '3', '0'), ('4985', '疏附县', '484', '0', '', '3', '0'), ('4986', '英吉沙县', '484', '0', '', '3', '0'), ('4987', '莎车县', '484', '0', '', '3', '0'), ('4988', '麦盖提县', '484', '0', '', '3', '0'), ('4989', '于田县', '485', '0', '', '3', '0'), ('4990', '和田县', '485', '0', '', '3', '0'), ('4991', '和田市', '485', '0', '', '3', '0'), ('4992', '墨玉县', '485', '0', '', '3', '0'), ('4993', '民丰县', '485', '0', '', '3', '0'), ('4994', '洛浦县', '485', '0', '', '3', '0'), ('4995', '皮山县', '485', '0', '', '3', '0'), ('4996', '策勒县', '485', '0', '', '3', '0'), ('4997', '伊宁县', '486', '0', '', '3', '0'), ('4998', '伊宁市', '486', '0', '', '3', '0'), ('4999', '奎屯市', '486', '0', '', '3', '0'), ('5000', '察布查尔锡伯自治县', '486', '0', '', '3', '0'), ('5001', '尼勒克县', '486', '0', '', '3', '0'), ('5002', '巩留县', '486', '0', '', '3', '0'), ('5003', '新源县', '486', '0', '', '3', '0'), ('5004', '昭苏县', '486', '0', '', '3', '0'), ('5005', '特克斯县', '486', '0', '', '3', '0'), ('5006', '霍城县', '486', '0', '', '3', '0'), ('5007', '乌苏市', '487', '0', '', '3', '0'), ('5008', '和布克赛尔蒙古自治县', '487', '0', '', '3', '0'), ('5009', '塔城市', '487', '0', '', '3', '0'), ('5010', '托里县', '487', '0', '', '3', '0'), ('5011', '沙湾县', '487', '0', '', '3', '0'), ('5012', '裕民县', '487', '0', '', '3', '0'), ('5013', '额敏县', '487', '0', '', '3', '0'), ('5014', '吉木乃县', '488', '0', '', '3', '0'), ('5015', '哈巴河县', '488', '0', '', '3', '0'), ('5016', '富蕴县', '488', '0', '', '3', '0'), ('5017', '布尔津县', '488', '0', '', '3', '0'), ('5018', '福海县', '488', '0', '', '3', '0'), ('5019', '阿勒泰市', '488', '0', '', '3', '0'), ('5020', '青河县', '488', '0', '', '3', '0'), ('5021', '石河子市', '489', '0', '', '3', '0'), ('5022', '阿拉尔市', '490', '0', '', '3', '0'), ('5023', '图木舒克市', '491', '0', '', '3', '0'), ('5024', '五家渠市', '492', '0', '', '3', '0'), ('45055', '海外', '35', '0', '', '2', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yf_base_filter_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `yf_base_filter_keyword`;
CREATE TABLE `yf_base_filter_keyword` (
  `keyword_find` varchar(50) NOT NULL,
  `keyword_replace` varchar(50) NOT NULL,
  `keyword_statu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:禁止 2：替换',
  `keyword_time` datetime NOT NULL,
  PRIMARY KEY (`keyword_find`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='敏感词过滤表';

-- ----------------------------
--  Table structure for `yf_base_menu`
-- ----------------------------
DROP TABLE IF EXISTS `yf_base_menu`;
CREATE TABLE `yf_base_menu` (
  `menu_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `menu_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '菜单id',
  `menu_rel` varchar(20) NOT NULL DEFAULT 'pageTab',
  `menu_name` varchar(20) NOT NULL COMMENT '菜单名称',
  `menu_label` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单text',
  `menu_icon` varchar(20) NOT NULL DEFAULT '' COMMENT '图标class',
  `menu_url_ctl` varchar(20) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `menu_url_met` varchar(20) NOT NULL DEFAULT '' COMMENT '控制器方法',
  `menu_url_parem` varchar(50) NOT NULL DEFAULT '' COMMENT 'url参数',
  `menu_url` varchar(100) NOT NULL DEFAULT '' COMMENT '类型 1 页面 2 url',
  `menu_order` tinyint(4) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `menu_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后更新时间',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='菜单表-10个递增';

-- ----------------------------
--  Table structure for `yf_card_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_card_base`;
CREATE TABLE `yf_card_base` (
  `card_number` varchar(50) NOT NULL COMMENT '充值卡序列号',
  `card_batch` varchar(50) NOT NULL COMMENT '充值卡批次标识',
  `card_cash` decimal(10,2) NOT NULL COMMENT '充值卡面额',
  `admin_id` int(10) NOT NULL COMMENT '创建充值卡的管理员id',
  `admin_name` varchar(50) NOT NULL COMMENT '管理员名称',
  `user_id` int(10) NOT NULL COMMENT '领取充值卡的用户id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名称',
  `card_publish_time` datetime NOT NULL COMMENT '充值卡创建时间',
  `card_get_time` datetime NOT NULL COMMENT '充值卡领取时间',
  PRIMARY KEY (`card_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值卡表';

-- ----------------------------
--  Table structure for `yf_cart`
-- ----------------------------
DROP TABLE IF EXISTS `yf_cart`;
CREATE TABLE `yf_cart` (
  `cart_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '买家id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `goods_num` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `cart_status` tinyint(1) NOT NULL COMMENT '状态有什么用？',
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='购物车表';

-- ----------------------------
--  Table structure for `yf_complain_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_complain_base`;
CREATE TABLE `yf_complain_base` (
  `complain_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投诉id',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `user_id_accuser` int(10) NOT NULL COMMENT '原告id',
  `user_account_accuser` varchar(50) NOT NULL COMMENT '原告名称',
  `user_id_accused` int(10) NOT NULL COMMENT '被告id',
  `user_account_accused` varchar(50) NOT NULL COMMENT '被告名称',
  `complain_subject_content` varchar(50) NOT NULL COMMENT '投诉主题',
  `complain_subject_id` int(11) NOT NULL COMMENT '投诉主题id',
  `complain_content` varchar(255) NOT NULL COMMENT '投诉内容',
  `complain_pic` text NOT NULL COMMENT '投诉图片,逗号分隔',
  `complain_datetime` datetime NOT NULL COMMENT '投诉时间',
  `complain_handle_datetime` datetime NOT NULL COMMENT '投诉处理时间',
  `complain_handle_user_id` int(10) NOT NULL COMMENT '投诉处理人id',
  `appeal_message` varchar(255) NOT NULL COMMENT '申诉内容',
  `appeal_datetime` datetime NOT NULL COMMENT '申诉时间',
  `appeal_pic` text NOT NULL COMMENT '申诉图片,逗号分隔',
  `final_handle_message` varchar(255) NOT NULL COMMENT '最终处理意见',
  `final_handle_datetime` datetime NOT NULL COMMENT '最终处理时间',
  `user_id_final_handle` int(10) NOT NULL COMMENT '最终处理人id',
  `complain_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '投诉状态(1-新投诉/2-投诉通过转给被投诉人(待申诉)/3-被投诉人已申诉(对话中)/4-提交仲裁(待仲裁)/5-已关闭)',
  `complain_active` tinyint(4) NOT NULL DEFAULT '0' COMMENT '投诉是否通过平台审批(0未通过/1通过)',
  PRIMARY KEY (`complain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='投诉表';

-- ----------------------------
--  Table structure for `yf_complain_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_complain_goods`;
CREATE TABLE `yf_complain_goods` (
  `complain_goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投诉商品序列id',
  `complain_id` int(11) NOT NULL COMMENT '投诉id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `goods_num` int(11) NOT NULL COMMENT '商品数量',
  `goods_image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `complain_message` varchar(100) NOT NULL COMMENT '被投诉商品的问题描述',
  `order_goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品ID',
  `order_goods_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '订单商品类型:1默认2团购商品3限时折扣商品4组合套装(待定)',
  `order_id` varchar(50) NOT NULL,
  PRIMARY KEY (`complain_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='投诉商品表';

-- ----------------------------
--  Table structure for `yf_complain_subject`
-- ----------------------------
DROP TABLE IF EXISTS `yf_complain_subject`;
CREATE TABLE `yf_complain_subject` (
  `complain_subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投诉主题id',
  `complain_subject_content` varchar(50) NOT NULL COMMENT '投诉主题',
  `complain_subject_desc` varchar(100) NOT NULL COMMENT '投诉主题描述',
  `complain_subject_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '投诉主题状态(1-有效/0-失效)',
  PRIMARY KEY (`complain_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='投诉主题表';

-- ----------------------------
--  Table structure for `yf_complain_talk`
-- ----------------------------
DROP TABLE IF EXISTS `yf_complain_talk`;
CREATE TABLE `yf_complain_talk` (
  `talk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投诉对话id',
  `complain_id` int(11) NOT NULL COMMENT '投诉id',
  `user_id` int(11) NOT NULL COMMENT '发言人id',
  `user_name` varchar(50) NOT NULL COMMENT '发言人名称',
  `talk_member_type` varchar(10) NOT NULL COMMENT '发言人类型(1-投诉人/2-被投诉人/3-平台)',
  `talk_content` varchar(255) NOT NULL COMMENT '发言内容',
  `talk_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '发言状态(1-显示/0-不显示)',
  `talk_admin` int(11) NOT NULL DEFAULT '0' COMMENT '对话管理员，屏蔽对话人的id',
  `talk_datetime` datetime NOT NULL COMMENT '对话发表时间',
  PRIMARY KEY (`talk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='投诉对话表';

-- ----------------------------
--  Table structure for `yf_consult_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_consult_base`;
CREATE TABLE `yf_consult_base` (
  `consult_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '咨询id',
  `consult_type_id` int(10) NOT NULL COMMENT '咨询类别id',
  `consult_type_name` varchar(50) NOT NULL COMMENT '咨询类别名',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名称',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `user_account` varchar(50) NOT NULL COMMENT '用户名称',
  `consult_question` varchar(255) NOT NULL COMMENT '咨询内容',
  `question_time` datetime NOT NULL COMMENT '提问时间',
  `answer_time` datetime NOT NULL COMMENT '回答时间',
  `consult_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-未回复 2-已回复',
  `consult_answer` varchar(255) NOT NULL COMMENT '回答',
  `consult_answer_time` datetime NOT NULL COMMENT '回复时间',
  `answer_user_id` int(10) unsigned NOT NULL,
  `answer_user_name` varchar(20) NOT NULL,
  `no_show_user` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名，1-匿名',
  PRIMARY KEY (`consult_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品咨询';

-- ----------------------------
--  Table structure for `yf_consult_reply`
-- ----------------------------
DROP TABLE IF EXISTS `yf_consult_reply`;
CREATE TABLE `yf_consult_reply` (
  `consult_reply_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '咨询id',
  `consult_id` int(10) NOT NULL COMMENT '咨询类别id',
  `consult_answer` varchar(255) NOT NULL COMMENT '咨询回答',
  `answer_time` datetime NOT NULL COMMENT '回答时间',
  `answer_user_id` int(10) NOT NULL,
  `answer_user_account` varchar(50) NOT NULL,
  `answer_user_identify` tinyint(1) NOT NULL DEFAULT '1' COMMENT '回复者身份 1-卖家 2-买家',
  PRIMARY KEY (`consult_reply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品咨询';

-- ----------------------------
--  Table structure for `yf_consult_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_consult_type`;
CREATE TABLE `yf_consult_type` (
  `consult_type_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题分类id',
  `consult_type_name` varchar(50) NOT NULL COMMENT '分类名称',
  `consult_type_sort` int(3) NOT NULL DEFAULT '255' COMMENT '咨询类型排序',
  PRIMARY KEY (`consult_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='咨询问题分类表';

-- ----------------------------
--  Records of `yf_consult_type`
-- ----------------------------
BEGIN;
INSERT INTO `yf_consult_type` VALUES ('1', '有错别字', '0'), ('2', '链接失效', '0'), ('3', '发货太慢', '0'), ('4', '文字太小', '0'), ('5', '支付失败', '0'), ('6', '无法下单', '0'), ('7', '搜不到商品', '0'), ('8', '页面打开慢', '0'), ('9', '免邮门槛高', '0'), ('11', '管制刀具、弓弩类、其他武器等', '0'), ('12', '赌博用具类', '0'), ('13', '枪支弹药', '0'), ('14', '毒品及吸毒工具', '0'), ('15', '色差大，质量差！', '255'), ('16', '其他问题', '255'), ('17', '78999', '255');
COMMIT;

-- ----------------------------
--  Table structure for `yf_delivery_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_delivery_base`;
CREATE TABLE `yf_delivery_base` (
  `delivery_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '服务站id',
  `user_account` varchar(50) NOT NULL COMMENT '服务站用户名',
  `delivery_real_name` varchar(50) NOT NULL COMMENT '真实姓名',
  `delivery_mobile` varchar(11) NOT NULL COMMENT '手机号',
  `delivery_tel` varchar(15) NOT NULL COMMENT '座机号',
  `delivery_name` varchar(50) NOT NULL COMMENT '自提站名称',
  `delivery_province_id` int(10) NOT NULL COMMENT '省id',
  `delivery_city_id` int(10) NOT NULL COMMENT '市id',
  `delivery_area_id` int(10) NOT NULL COMMENT '区域id',
  `delivery_area` varchar(255) NOT NULL COMMENT '区域',
  `delivery_address` varchar(255) NOT NULL COMMENT '地址',
  `delivery_identifycard` varchar(20) NOT NULL COMMENT '身份证号',
  `delivery_identifycard_pic` varchar(255) NOT NULL COMMENT '身份证图片',
  `delivery_apply_date` datetime NOT NULL COMMENT '申请时间',
  `delivery_password` varchar(32) NOT NULL COMMENT '密码',
  `delivery_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-开启，2-关闭',
  `delivery_check_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态 1-审核中 2-已通过 3-不通过',
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_discount_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_discount_base`;
CREATE TABLE `yf_discount_base` (
  `discount_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时编号',
  `discount_name` varchar(50) NOT NULL COMMENT '活动名称',
  `discount_title` varchar(10) NOT NULL COMMENT '活动标题',
  `discount_explain` varchar(50) NOT NULL COMMENT '活动说明',
  `combo_id` int(10) unsigned NOT NULL COMMENT '套餐编号',
  `discount_start_time` datetime NOT NULL COMMENT '活动开始时间',
  `discount_end_time` datetime NOT NULL COMMENT '活动结束时间',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `user_nick_name` varchar(50) NOT NULL COMMENT '用户名',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `discount_lower_limit` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '购买下限，1为不限制',
  `discount_state` int(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态， 1-正常/2-结束/3-管理员关闭',
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣活动表';

-- ----------------------------
--  Table structure for `yf_discount_combo`
-- ----------------------------
DROP TABLE IF EXISTS `yf_discount_combo`;
CREATE TABLE `yf_discount_combo` (
  `combo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时折扣套餐编号',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `combo_start_time` datetime NOT NULL COMMENT '套餐开始时间',
  `combo_end_time` datetime NOT NULL COMMENT '套餐结束时间',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣套餐表';

-- ----------------------------
--  Table structure for `yf_discount_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_discount_goods`;
CREATE TABLE `yf_discount_goods` (
  `discount_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时折扣商品表',
  `discount_id` int(10) unsigned NOT NULL COMMENT '限时活动编号',
  `discount_name` varchar(50) NOT NULL COMMENT '活动名称',
  `discount_title` varchar(10) NOT NULL COMMENT '活动标题',
  `discount_explain` varchar(50) NOT NULL COMMENT '活动说明',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品编号',
  `common_id` int(10) NOT NULL,
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品原价',
  `discount_price` decimal(10,2) NOT NULL COMMENT '限时折扣价格',
  `goods_image` varchar(100) NOT NULL COMMENT '商品图片',
  `goods_start_time` datetime NOT NULL COMMENT '开始时间',
  `goods_end_time` datetime NOT NULL COMMENT '结束时间',
  `goods_lower_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买下限，0为不限制',
  `discount_goods_state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态， 1-正常/2-结束/3-管理员关闭',
  `discount_goods_recommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐标志 0-未推荐 1-已推荐',
  PRIMARY KEY (`discount_goods_id`),
  KEY `discount_id` (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣商品表';

-- ----------------------------
--  Table structure for `yf_express`
-- ----------------------------
DROP TABLE IF EXISTS `yf_express`;
CREATE TABLE `yf_express` (
  `express_id` int(10) NOT NULL AUTO_INCREMENT,
  `express_name` varchar(30) NOT NULL COMMENT '快递公司',
  `express_pinyin` varchar(30) NOT NULL COMMENT '物流',
  `express_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0关闭1开启',
  `express_displayorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自提0否1是',
  `express_commonorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用',
  PRIMARY KEY (`express_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快递表';

-- ----------------------------
--  Records of `yf_express`
-- ----------------------------
BEGIN;
INSERT INTO `yf_express` VALUES ('1', '安能物流', 'ANE', '1', '0', '1'), ('2', '安信达快递', 'AXD', '0', '0', '1'), ('3', '百福东方', 'BFDF', '0', '0', '0'), ('4', '北青小红帽', 'BQXHM', '0', '0', '0'), ('5', 'CCES快递', 'CCES', '1', '0', '0'), ('6', '城市100                      ', 'CITY100', '0', '0', '1'), ('7', 'COE东方快递', 'COE', '0', '0', '0'), ('8', '长沙创一', 'CSCY', '0', '0', '0'), ('9', '德邦', 'DBL', '0', '0', '0'), ('10', 'DHL', 'DHL', '0', '0', '0'), ('11', 'D速物流', 'DSWL', '0', '0', '1'), ('12', '大田物流', 'DTWL', '0', '0', '0'), ('13', 'EMS', 'EMS', '0', '0', '0'), ('14', '快捷速递', 'FAST', '0', '0', '0'), ('15', 'FedEx联邦快递', 'FEDEX', '0', '0', '0'), ('16', '飞康达', 'FKD', '1', '0', '1'), ('17', '广东邮政', 'GDEMS', '1', '1', '1'), ('18', '共速达', 'GSD', '0', '0', '0'), ('19', '国通快递', 'GTO', '0', '0', '0'), ('20', '高铁速递', 'GTSD', '0', '0', '0'), ('21', '汇丰物流', 'HFWL', '0', '0', '0'), ('22', '天天快递', 'HHTT', '0', '0', '0'), ('23', '恒路物流', 'HLWL', '0', '0', '0'), ('24', '天地华宇', 'HOAU', '0', '0', '0'), ('25', '华强物流', 'hq568', '0', '0', '0'), ('26', '百世汇通', 'HTKY', '0', '0', '0'), ('27', '华夏龙物流', 'HXLWL', '0', '0', '0'), ('28', '好来运快递', 'HYLSD', '0', '0', '0'), ('29', '京东快递', 'JD', '0', '0', '0'), ('30', '京广速递', 'JGSD', '0', '0', '0'), ('31', '佳吉快运', 'JJKY', '0', '0', '0'), ('32', '捷特快递', 'JTKD', '0', '0', '0'), ('33', '急先达', 'JXD', '0', '0', '0'), ('34', '晋越快递', 'JYKD', '0', '0', '0'), ('35', '加运美', 'JYM', '0', '0', '0'), ('36', '佳怡物流', 'JYWL', '0', '0', '0'), ('37', '龙邦快递', 'LB', '0', '0', '0'), ('38', '联昊通速递', 'LHT', '0', '0', '0'), ('39', '民航快递', 'MHKD', '0', '0', '0'), ('40', '明亮物流', 'MLWL', '0', '0', '0'), ('41', '能达速递', 'NEDA', '0', '0', '0'), ('42', '全晨快递', 'QCKD', '0', '0', '0'), ('43', '全峰快递', 'QFKD', '0', '0', '0'), ('44', '全日通快递', 'QRT', '0', '0', '0'), ('45', '圣安物流', 'SAWL', '0', '0', '0'), ('46', '上大物流', 'SDWL', '0', '0', '0'), ('47', '顺丰快递', 'SF', '0', '0', '0'), ('48', '盛丰物流', 'SFWL', '0', '0', '0'), ('49', '盛辉物流', 'SHWL', '0', '0', '0'), ('50', '速通物流', 'ST', '0', '0', '0'), ('51', '申通快递', 'STO', '0', '0', '0'), ('52', '速尔快递', 'SURE', '0', '0', '0'), ('53', '唐山申通', 'TSSTO', '0', '0', '0'), ('54', '全一快递', 'UAPEX', '0', '0', '0'), ('55', '优速快递', 'UC', '0', '0', '0'), ('56', '万家物流', 'WJWL', '0', '0', '0'), ('57', '万象物流', 'WXWL', '0', '0', '0'), ('58', '新邦物流', 'XBWL', '0', '0', '0'), ('59', '信丰快递', 'XFEX', '0', '0', '0'), ('60', '希优特', 'XYT', '0', '0', '0'), ('61', '源安达快递', 'YADEX', '0', '0', '0'), ('62', '远成物流', 'YCWL', '0', '0', '0'), ('63', '韵达快递', 'YD', '0', '0', '0'), ('64', '越丰物流', 'YFEX', '0', '0', '0'), ('65', '原飞航物流', 'YFHEX', '0', '0', '0'), ('66', '亚风快递', 'YFSD', '0', '0', '0'), ('67', '运通快递', 'YTKD', '0', '0', '0'), ('68', '圆通速递', 'YTO', '0', '0', '0'), ('69', '邮政平邮/小包', 'YZPY', '0', '0', '0'), ('70', '增益快递', 'ZENY', '0', '0', '0'), ('71', '汇强快递', 'ZHQKD', '0', '0', '0'), ('72', '宅急送', 'ZJS', '0', '0', '0'), ('73', '众通快递', 'ZTE', '0', '0', '0'), ('74', '中铁快运', 'ZTKY', '0', '0', '0'), ('75', '中通速递', 'ZTO', '0', '0', '0'), ('76', '中铁物流', 'ZTWL', '0', '0', '0'), ('77', '中邮物流', 'ZYWL', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yf_feed_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_feed_base`;
CREATE TABLE `yf_feed_base` (
  `feed_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `feed_group_id` tinyint(2) NOT NULL DEFAULT '0' COMMENT '问题组',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `feed_desc` varchar(100) NOT NULL DEFAULT '' COMMENT '问题描述',
  `feed_url` varchar(30) NOT NULL DEFAULT '' COMMENT '页面链接（选填）',
  `feed_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '反馈状态 1 : 已经确认',
  `feed_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='反馈表';

-- ----------------------------
--  Table structure for `yf_feed_group`
-- ----------------------------
DROP TABLE IF EXISTS `yf_feed_group`;
CREATE TABLE `yf_feed_group` (
  `feed_group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '反馈组id',
  `feed_group_name` varchar(30) NOT NULL COMMENT '反馈组名称',
  PRIMARY KEY (`feed_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='反馈组表';

-- ----------------------------
--  Table structure for `yf_goods_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_base`;
CREATE TABLE `yf_goods_base` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `common_id` int(10) unsigned NOT NULL COMMENT '商品公共表id',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名称（+规格名称）',
  `goods_promotion_tips` varchar(200) NOT NULL COMMENT '促销提示',
  `cat_id` int(10) unsigned NOT NULL COMMENT '商品分类id',
  `brand_id` int(10) unsigned NOT NULL COMMENT '品牌id',
  `goods_spec` varchar(255) NOT NULL DEFAULT '' COMMENT '商品规格-JSON存储',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `goods_market_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `goods_stock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `goods_alarm` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存预警值',
  `goods_code` varchar(50) NOT NULL COMMENT '商家编号货号',
  `goods_barcode` varchar(50) DEFAULT '' COMMENT '商品二维码',
  `goods_is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品推荐 1是，0否 默认0',
  `goods_click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品点击数量',
  `goods_salenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售数量',
  `goods_collect` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `goods_image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主图',
  `color_id` int(10) NOT NULL DEFAULT '0',
  `goods_evaluation_good_star` tinyint(3) unsigned NOT NULL DEFAULT '5' COMMENT '好评星级',
  `goods_evaluation_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价数',
  `goods_max_sale` int(10) NOT NULL DEFAULT '0' COMMENT '单人最大购买数量',
  `goods_is_shelves` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-上架 2-下架',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品表';

-- ----------------------------
--  Table structure for `yf_goods_brand`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_brand`;
CREATE TABLE `yf_goods_brand` (
  `brand_id` int(10) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(50) NOT NULL,
  `brand_name_cn` varchar(255) NOT NULL DEFAULT '' COMMENT '拼音',
  `cat_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `brand_initial` varchar(1) NOT NULL COMMENT '首字母',
  `brand_show_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '展示方式',
  `brand_pic` varchar(255) NOT NULL,
  `brand_displayorder` smallint(3) NOT NULL DEFAULT '0',
  `brand_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `brand_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '上传店铺的id',
  `brand_collect` int(10) NOT NULL COMMENT '收藏数量',
  PRIMARY KEY (`brand_id`),
  KEY `brand_name` (`brand_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品品牌表';

-- ----------------------------
--  Table structure for `yf_goods_cat`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_cat`;
CREATE TABLE `yf_goods_cat` (
  `cat_id` int(9) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) NOT NULL COMMENT ' 分类名称',
  `cat_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `cat_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
  `type_id` int(10) NOT NULL DEFAULT '0' COMMENT '类型id',
  `cat_commission` float NOT NULL DEFAULT '0' COMMENT '分佣比例',
  `cat_is_wholesale` tinyint(1) NOT NULL DEFAULT '0',
  `cat_is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许虚拟',
  `cat_templates` varchar(100) NOT NULL DEFAULT '0',
  `cat_displayorder` smallint(3) NOT NULL DEFAULT '255' COMMENT '排序',
  `cat_level` tinyint(1) NOT NULL COMMENT '分类级别',
  `cat_show_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:SPU  2:颜色',
  PRIMARY KEY (`cat_id`),
  KEY `cat_parent_id` (`cat_parent_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品分类表';

-- ----------------------------
--  Table structure for `yf_goods_cat_nav`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_cat_nav`;
CREATE TABLE `yf_goods_cat_nav` (
  `goods_cat_nav_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_cat_nav_name` varchar(50) NOT NULL COMMENT '分类别名',
  `goods_cat_nav_brand` varchar(200) NOT NULL COMMENT '推荐品牌',
  `goods_cat_nav_recommend` text NOT NULL COMMENT '推荐分类',
  `goods_cat_nav_pic` varchar(255) NOT NULL COMMENT '分类图片',
  `goods_cat_nav_adv` varchar(255) NOT NULL COMMENT '广告图',
  `goods_cat_id` int(10) NOT NULL COMMENT '商品分类id',
  `goods_cat_nav_recommend_display` text NOT NULL COMMENT '显示用推荐分类',
  PRIMARY KEY (`goods_cat_nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类导航';

-- ----------------------------
--  Table structure for `yf_goods_common`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_common`;
CREATE TABLE `yf_goods_common` (
  `common_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `common_name` varchar(50) NOT NULL COMMENT '商品名称',
  `common_promotion_tips` varchar(50) NOT NULL COMMENT '商品广告词',
  `cat_id` int(10) unsigned NOT NULL COMMENT '商品分类',
  `cat_name` varchar(200) NOT NULL COMMENT '商品分类',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `shop_cat_id` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺分类id 首尾用,隔开',
  `shop_goods_cat_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '店铺商品分类id  -- json',
  `goods_id` text NOT NULL COMMENT 'goods_id -- json [goods_id: xx, color_id: xx]',
  `shop_self_support` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否自营',
  `shop_status` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '店铺状态-3：开店成功 2:待审核付款 1:待审核资料  0:关闭',
  `common_property` text NOT NULL COMMENT '属性',
  `common_spec_name` varchar(255) NOT NULL COMMENT '规格名称',
  `common_spec_value` text NOT NULL COMMENT '规格值',
  `brand_id` int(10) unsigned NOT NULL COMMENT '品牌id',
  `brand_name` varchar(100) NOT NULL COMMENT '品牌名称',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型id',
  `common_image` varchar(255) NOT NULL COMMENT '商品主图',
  `common_packing_list` text NOT NULL,
  `common_service` text NOT NULL,
  `common_state` tinyint(3) unsigned NOT NULL COMMENT '商品状态 0下架，1正常，10违规（禁售）',
  `common_state_remark` varchar(255) NOT NULL COMMENT '违规原因',
  `common_verify` tinyint(3) unsigned NOT NULL COMMENT '商品审核 1通过，0未通过，10审核中',
  `common_verify_remark` varchar(255) NOT NULL COMMENT '审核失败原因',
  `common_add_time` datetime NOT NULL COMMENT '商品添加时间',
  `common_sell_time` datetime NOT NULL COMMENT '上架时间',
  `common_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `common_market_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `common_cost_price` decimal(10,2) NOT NULL COMMENT '成本价',
  `common_stock` int(10) unsigned NOT NULL COMMENT '商品库存',
  `common_limit` smallint(3) NOT NULL DEFAULT '0' COMMENT '每人限购 0 代表不限购',
  `common_alarm` int(10) unsigned NOT NULL DEFAULT '0',
  `common_code` varchar(50) NOT NULL COMMENT '商家编号',
  `common_platform_code` varchar(100) NOT NULL DEFAULT '0' COMMENT '平台货号',
  `common_cubage` decimal(10,2) NOT NULL COMMENT '商品重量',
  `common_collect` int(10) NOT NULL DEFAULT '0' COMMENT '商品收藏量',
  `common_evaluate` int(10) NOT NULL DEFAULT '0' COMMENT '商品评论数',
  `common_salenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品销量',
  `common_invoices` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开具增值税发票 1是，0否',
  `common_is_return` tinyint(1) NOT NULL DEFAULT '1' COMMENT '7天无理由退货 1=不支持  2=支持',
  `common_formatid_top` int(10) unsigned NOT NULL COMMENT '顶部关联板式',
  `common_formatid_bottom` int(10) unsigned NOT NULL COMMENT '底部关联板式',
  `common_is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品推荐 1:推荐 2:推荐',
  `common_is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟商品',
  `common_virtual_date` datetime NOT NULL COMMENT '虚拟商品有效期',
  `common_virtual_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支持过期退款',
  `transport_type_id` int(10) NOT NULL DEFAULT '0' COMMENT '0--> 固定运费   非零：transport_type_id  运费类型',
  `transport_type_name` varchar(30) NOT NULL COMMENT '运费模板名称',
  `common_freight` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `common_location` text NOT NULL COMMENT '商品所在地 json',
  `common_is_tuan` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否参加团购活动',
  `common_is_xian` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否参加限时折扣活动',
  `common_is_jia` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否参加加价购活动',
  `common_shop_contract_1` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障-由店铺映射到商品，用来检索使用',
  `common_shop_contract_2` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障-由店铺映射到商品，用来检索使用',
  `common_shop_contract_3` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障-由店铺映射到商品，用来检索使用',
  `common_shop_contract_4` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障-由店铺映射到商品，用来检索使用',
  `common_shop_contract_5` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障-由店铺映射到商品，用来检索使用',
  `common_shop_contract_6` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`common_id`),
  KEY `cat_id` (`cat_id`),
  KEY `shop_id` (`shop_id`),
  KEY `type_id` (`type_id`),
  KEY `common_verify` (`common_verify`),
  KEY `common_state` (`common_state`),
  KEY `common_name` (`common_name`),
  KEY `shop_name` (`shop_name`),
  KEY `brand_name` (`brand_name`),
  KEY `brand_id` (`brand_id`),
  KEY `shop_status` (`shop_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品公共内容表-未来可分表';

-- ----------------------------
--  Table structure for `yf_goods_common_detail`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_common_detail`;
CREATE TABLE `yf_goods_common_detail` (
  `common_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `common_body` text NOT NULL COMMENT '商品内容',
  PRIMARY KEY (`common_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品公共内容详情表';

-- ----------------------------
--  Table structure for `yf_goods_evaluation`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_evaluation`;
CREATE TABLE `yf_goods_evaluation` (
  `evaluation_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '会员ID',
  `member_name` varchar(50) NOT NULL COMMENT '会员名',
  `order_id` varchar(50) NOT NULL COMMENT '订单ID',
  `shop_id` int(10) NOT NULL COMMENT '商家ID',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `common_id` int(10) NOT NULL,
  `goods_id` int(10) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `goods_image` varchar(255) NOT NULL COMMENT '商品图片',
  `scores` tinyint(1) NOT NULL COMMENT '1-5分',
  `result` enum('good','neutral','bad') NOT NULL COMMENT '结果',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `image` text NOT NULL COMMENT '晒单图片',
  `isanonymous` tinyint(1) NOT NULL COMMENT '是否匿名评价',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL COMMENT '状态 0禁止显示 1显示 2置顶',
  `explain_content` varchar(255) NOT NULL COMMENT '解释内容',
  `update_time` datetime NOT NULL,
  `evaluation_from` enum('1','2') NOT NULL DEFAULT '1' COMMENT '手机端',
  PRIMARY KEY (`evaluation_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品评论表';

-- ----------------------------
--  Table structure for `yf_goods_format`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_format`;
CREATE TABLE `yf_goods_format` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  `content` text NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='关联板式表';

-- ----------------------------
--  Table structure for `yf_goods_images`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_images`;
CREATE TABLE `yf_goods_images` (
  `images_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品图片id',
  `common_id` int(10) unsigned NOT NULL COMMENT '商品公共内容id',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `images_color_id` int(10) unsigned NOT NULL COMMENT '颜色规格值id',
  `images_image` varchar(255) NOT NULL COMMENT '商品图片',
  `images_displayorder` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `images_is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '默认主题，1是，0否',
  PRIMARY KEY (`images_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品图片';

-- ----------------------------
--  Table structure for `yf_goods_property`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_property`;
CREATE TABLE `yf_goods_property` (
  `property_id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `property_name` varchar(100) NOT NULL COMMENT '属性名称',
  `type_id` int(10) NOT NULL COMMENT '所属类型id',
  `property_item` text NOT NULL COMMENT '属性值列',
  `property_is_search` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被搜索。0为不搜索、1为搜索',
  `property_format` enum('text','select','checkbox') NOT NULL COMMENT '显示类型',
  `property_displayorder` smallint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`property_id`),
  KEY `catid` (`property_format`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品属性值表';

-- ----------------------------
--  Table structure for `yf_goods_property_index`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_property_index`;
CREATE TABLE `yf_goods_property_index` (
  `goods_property_index_id` int(11) NOT NULL AUTO_INCREMENT,
  `common_id` int(10) unsigned NOT NULL COMMENT '商品公共表id',
  `property_id` int(10) unsigned NOT NULL COMMENT '属性id',
  `property_value_id` int(10) unsigned NOT NULL COMMENT '属性值id',
  PRIMARY KEY (`goods_property_index_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品与属性对应表';

-- ----------------------------
--  Table structure for `yf_goods_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_property_value`;
CREATE TABLE `yf_goods_property_value` (
  `property_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `property_value_name` varchar(100) NOT NULL COMMENT '属性值名称',
  `property_id` int(10) unsigned NOT NULL COMMENT '所属属性id',
  `property_value_displayorder` smallint(3) unsigned NOT NULL DEFAULT '1' COMMENT '属性值排序',
  PRIMARY KEY (`property_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品属性值表';

-- ----------------------------
--  Table structure for `yf_goods_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_recommend`;
CREATE TABLE `yf_goods_recommend` (
  `goods_recommend_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品推荐id',
  `goods_cat_id` int(10) NOT NULL COMMENT '商品分类id',
  `common_id` varchar(50) NOT NULL COMMENT '推荐商品id，最多四个',
  `recommend_num` int(5) NOT NULL COMMENT '推荐数量',
  PRIMARY KEY (`goods_recommend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品推荐表';

-- ----------------------------
--  Table structure for `yf_goods_service`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_service`;
CREATE TABLE `yf_goods_service` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `yf_goods_spec`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_spec`;
CREATE TABLE `yf_goods_spec` (
  `spec_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `spec_name` varchar(100) NOT NULL COMMENT '规格名称',
  `cat_id` int(10) unsigned NOT NULL COMMENT '快捷定位',
  `spec_displayorder` smallint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `spec_readonly` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '不可删除',
  PRIMARY KEY (`spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品规格表';

-- ----------------------------
--  Table structure for `yf_goods_spec_value`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_spec_value`;
CREATE TABLE `yf_goods_spec_value` (
  `spec_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `spec_value_name` varchar(100) NOT NULL COMMENT '规格值名称',
  `spec_id` int(10) unsigned NOT NULL COMMENT '所属规格id',
  `type_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  `shop_id` int(10) NOT NULL,
  `spec_value_displayorder` smallint(3) NOT NULL DEFAULT '1' COMMENT '排序',
  PRIMARY KEY (`spec_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品规格值表';

-- ----------------------------
--  Table structure for `yf_goods_state`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_state`;
CREATE TABLE `yf_goods_state` (
  `goods_state_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '产品状态id',
  `goods_state_name` varchar(50) NOT NULL DEFAULT '' COMMENT '产品状态状态',
  `goods_state_text_1` varchar(255) NOT NULL DEFAULT '' COMMENT '产品状态',
  `goods_state_text_2` varchar(255) NOT NULL DEFAULT '' COMMENT '产品状态',
  `goods_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`goods_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='产品状态表';

-- ----------------------------
--  Table structure for `yf_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_type`;
CREATE TABLE `yf_goods_type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type_name` varchar(100) NOT NULL COMMENT '类型名称',
  `type_displayorder` tinyint(1) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `cat_id` int(10) NOT NULL DEFAULT '-1' COMMENT '仅仅定位，无用',
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `type_draft` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '草稿：只允许存在一条记录',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品类型表-要取消各种快捷定位';

-- ----------------------------
--  Table structure for `yf_goods_type_brand`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_type_brand`;
CREATE TABLE `yf_goods_type_brand` (
  `type_brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL COMMENT '类型id',
  `brand_id` int(10) unsigned NOT NULL COMMENT '规格id',
  PRIMARY KEY (`type_brand_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品类型与品牌对应表';

-- ----------------------------
--  Table structure for `yf_goods_type_spec`
-- ----------------------------
DROP TABLE IF EXISTS `yf_goods_type_spec`;
CREATE TABLE `yf_goods_type_spec` (
  `type_spec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL COMMENT '类型id',
  `spec_id` int(10) unsigned NOT NULL COMMENT '规格id',
  PRIMARY KEY (`type_spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品类型与规格对应表';

-- ----------------------------
--  Table structure for `yf_grade_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_grade_log`;
CREATE TABLE `yf_grade_log` (
  `grade_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `points_log_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1获取2消费',
  `class_id` tinyint(1) NOT NULL COMMENT '1''会员登录'',2''购买商品'',3''评价''',
  `user_id` int(10) NOT NULL COMMENT '会员编号',
  `user_name` varchar(50) NOT NULL COMMENT '会员名称',
  `admin_name` varchar(100) NOT NULL COMMENT '管理员名称',
  `grade_log_grade` int(10) NOT NULL DEFAULT '0' COMMENT '获得经验',
  `freeze_grade` int(10) NOT NULL DEFAULT '0' COMMENT '冻结经验',
  `grade_log_time` datetime NOT NULL COMMENT '创建时间',
  `grade_log_desc` varchar(100) NOT NULL COMMENT '描述',
  `grade_log_flag` varchar(20) NOT NULL COMMENT '标记',
  PRIMARY KEY (`grade_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员经验日志表';

-- ----------------------------
--  Table structure for `yf_groupbuy_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_groupbuy_area`;
CREATE TABLE `yf_groupbuy_area` (
  `groupbuy_area_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区编号',
  `groupbuy_area_name` varchar(50) NOT NULL COMMENT '地区名称',
  `groupbuy_area_parent_id` int(10) unsigned NOT NULL COMMENT '父地区编号',
  `groupbuy_area_sort` tinyint(1) unsigned NOT NULL COMMENT '排序',
  `groupbuy_area_deep` tinyint(1) unsigned NOT NULL COMMENT '深度',
  PRIMARY KEY (`groupbuy_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购地区表';

-- ----------------------------
--  Table structure for `yf_groupbuy_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_groupbuy_base`;
CREATE TABLE `yf_groupbuy_base` (
  `groupbuy_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购ID',
  `groupbuy_name` varchar(255) NOT NULL COMMENT '活动名称',
  `groupbuy_starttime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `groupbuy_endtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `common_id` int(10) unsigned NOT NULL COMMENT '商品公共表ID',
  `goods_name` varchar(200) NOT NULL COMMENT '商品名称',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品原价',
  `groupbuy_price` decimal(10,2) NOT NULL COMMENT '团购价格',
  `groupbuy_rebate` decimal(10,2) NOT NULL COMMENT '折扣',
  `groupbuy_virtual_quantity` int(10) unsigned NOT NULL COMMENT '虚拟购买数量',
  `groupbuy_upper_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买上限',
  `groupbuy_buyer_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已购买人数',
  `groupbuy_buy_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `groupbuy_intro` text NOT NULL COMMENT '本团介绍',
  `groupbuy_state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团购状态 1.审核中 2.正常 3.结束 4.审核失败 5.管理员关闭',
  `groupbuy_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐 0.未推荐 1.已推荐',
  `groupbuy_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '团购类型：1-线上团（实物）；2-虚拟团',
  `groupbuy_views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `groupbuy_cat_id` int(10) unsigned NOT NULL COMMENT '团购类别编号',
  `groupbuy_scat_id` int(10) NOT NULL,
  `groupbuy_city_id` int(10) NOT NULL,
  `groupbuy_area_id` int(10) unsigned NOT NULL COMMENT '团购地区编号',
  `groupbuy_image` varchar(200) NOT NULL COMMENT '团购图片',
  `groupbuy_image_rec` varchar(200) NOT NULL COMMENT '团购推荐位图片',
  `groupbuy_remark` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`groupbuy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团购商品表';

-- ----------------------------
--  Table structure for `yf_groupbuy_cat`
-- ----------------------------
DROP TABLE IF EXISTS `yf_groupbuy_cat`;
CREATE TABLE `yf_groupbuy_cat` (
  `groupbuy_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '类别编号',
  `groupbuy_cat_name` varchar(20) NOT NULL COMMENT '类别名称',
  `groupbuy_cat_parent_id` int(10) unsigned NOT NULL COMMENT '父类别编号',
  `groupbuy_cat_sort` tinyint(1) unsigned NOT NULL COMMENT '排序',
  `groupbuy_cat_deep` tinyint(1) unsigned NOT NULL COMMENT '深度',
  `groupbuy_cat_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '团购类型 1-实物，2-虚拟商品',
  PRIMARY KEY (`groupbuy_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购类别表';

-- ----------------------------
--  Table structure for `yf_groupbuy_combo`
-- ----------------------------
DROP TABLE IF EXISTS `yf_groupbuy_combo`;
CREATE TABLE `yf_groupbuy_combo` (
  `combo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购套餐编号',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `combo_starttime` datetime NOT NULL COMMENT '套餐开始时间',
  `combo_endtime` datetime NOT NULL COMMENT '套餐结束时间',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团购套餐表';

-- ----------------------------
--  Table structure for `yf_groupbuy_price_range`
-- ----------------------------
DROP TABLE IF EXISTS `yf_groupbuy_price_range`;
CREATE TABLE `yf_groupbuy_price_range` (
  `range_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '价格区间编号',
  `range_name` varchar(20) NOT NULL COMMENT '区间名称',
  `range_start` int(10) unsigned NOT NULL COMMENT '区间下限',
  `range_end` int(10) unsigned NOT NULL COMMENT '区间上限',
  PRIMARY KEY (`range_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团购价格区间表';

-- ----------------------------
--  Table structure for `yf_increase_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_increase_base`;
CREATE TABLE `yf_increase_base` (
  `increase_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购活动编号',
  `increase_name` varchar(50) NOT NULL COMMENT '活动名称',
  `combo_id` int(10) unsigned NOT NULL COMMENT '套餐编号',
  `increase_start_time` datetime NOT NULL COMMENT '活动开始时间',
  `increase_end_time` datetime NOT NULL COMMENT '活动结束时间',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  `increase_state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '活动状态(1-正常/2-已结束/3-管理员关闭)',
  PRIMARY KEY (`increase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加价购活动表';

-- ----------------------------
--  Table structure for `yf_increase_combo`
-- ----------------------------
DROP TABLE IF EXISTS `yf_increase_combo`;
CREATE TABLE `yf_increase_combo` (
  `combo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购套餐编号',
  `combo_start_time` datetime NOT NULL COMMENT '开始时间',
  `combo_end_time` datetime NOT NULL COMMENT '结束时间',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加价购套餐表';

-- ----------------------------
--  Table structure for `yf_increase_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_increase_goods`;
CREATE TABLE `yf_increase_goods` (
  `increase_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购商品表id',
  `increase_id` int(10) unsigned NOT NULL COMMENT '限时活动编号',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品编号',
  `common_id` int(10) NOT NULL,
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `goods_start_time` datetime NOT NULL COMMENT '开始时间',
  `goods_end_time` datetime NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`increase_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加价购商品表';

-- ----------------------------
--  Table structure for `yf_increase_redemp_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_increase_redemp_goods`;
CREATE TABLE `yf_increase_redemp_goods` (
  `redemp_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购换购商品表',
  `rule_id` int(10) unsigned NOT NULL COMMENT '加价购规则编号',
  `increase_id` int(10) unsigned NOT NULL COMMENT '加价购活动编号',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `redemp_price` decimal(10,2) NOT NULL COMMENT '换购价',
  PRIMARY KEY (`redemp_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加价购换购商品表';

-- ----------------------------
--  Table structure for `yf_increase_rule`
-- ----------------------------
DROP TABLE IF EXISTS `yf_increase_rule`;
CREATE TABLE `yf_increase_rule` (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购规则编号',
  `increase_id` int(10) unsigned NOT NULL COMMENT '活动编号',
  `rule_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '规则级别价格',
  `rule_goods_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限定换购数量，0为不限定数量',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加价购规则表';

-- ----------------------------
--  Table structure for `yf_invoice`
-- ----------------------------
DROP TABLE IF EXISTS `yf_invoice`;
CREATE TABLE `yf_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引id',
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
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='买家发票信息表';

-- ----------------------------
--  Table structure for `yf_log_action`
-- ----------------------------
DROP TABLE IF EXISTS `yf_log_action`;
CREATE TABLE `yf_log_action` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `user_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '玩家Id',
  `user_account` varchar(100) NOT NULL DEFAULT '' COMMENT '角色账户',
  `user_name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `action_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '行为id == protocal_id -> rights_id',
  `action_type_id` mediumint(9) NOT NULL COMMENT '操作类型id，right_parent_id',
  `log_param` text NOT NULL COMMENT '请求的参数',
  `log_ip` varchar(20) NOT NULL DEFAULT '',
  `log_date` date NOT NULL COMMENT '日志日期',
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录时间',
  PRIMARY KEY (`log_id`),
  KEY `player_id` (`user_id`) COMMENT '(null)',
  KEY `log_date` (`log_date`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户行为日志表';

-- ----------------------------
--  Table structure for `yf_mansong_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_mansong_base`;
CREATE TABLE `yf_mansong_base` (
  `mansong_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '满送活动编号',
  `mansong_name` varchar(50) NOT NULL COMMENT '活动名称',
  `combo_id` int(10) unsigned NOT NULL COMMENT '套餐编号',
  `mansong_start_time` datetime NOT NULL COMMENT '活动开始时间',
  `mansong_end_time` datetime NOT NULL COMMENT '活动结束时间',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `mansong_state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '活动状态(1-正常/2-已结束/3-管理员关闭，取消)',
  `mansong_remark` varchar(200) NOT NULL COMMENT '备注',
  PRIMARY KEY (`mansong_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='满就送活动表';

-- ----------------------------
--  Table structure for `yf_mansong_combo`
-- ----------------------------
DROP TABLE IF EXISTS `yf_mansong_combo`;
CREATE TABLE `yf_mansong_combo` (
  `combo_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '满就送套餐编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户编号',
  `shop_id` int(11) unsigned NOT NULL COMMENT '店铺编号',
  `user_nickname` varchar(50) NOT NULL COMMENT '用户名',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `combo_start_time` datetime NOT NULL COMMENT '开始时间',
  `combo_end_time` datetime NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='满就送套餐表';

-- ----------------------------
--  Table structure for `yf_mansong_rule`
-- ----------------------------
DROP TABLE IF EXISTS `yf_mansong_rule`;
CREATE TABLE `yf_mansong_rule` (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则编号',
  `mansong_id` int(10) unsigned NOT NULL COMMENT '活动编号',
  `rule_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '级别价格',
  `rule_discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '减现金优惠金额',
  `goods_name` varchar(50) NOT NULL COMMENT '礼品名称',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品编号',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='满就送活动规则表';

-- ----------------------------
--  Table structure for `yf_mb_cat_image`
-- ----------------------------
DROP TABLE IF EXISTS `yf_mb_cat_image`;
CREATE TABLE `yf_mb_cat_image` (
  `mb_cat_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cat_id` int(10) unsigned NOT NULL COMMENT 'cat_id',
  `mb_cat_image` varchar(255) NOT NULL COMMENT '分类图片',
  `cat_adv_image` varchar(255) NOT NULL COMMENT '广告图片',
  `cat_adv_url` varchar(255) NOT NULL COMMENT '广告地址',
  PRIMARY KEY (`mb_cat_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分类图片';

-- ----------------------------
--  Table structure for `yf_mb_tpl_layout`
-- ----------------------------
DROP TABLE IF EXISTS `yf_mb_tpl_layout`;
CREATE TABLE `yf_mb_tpl_layout` (
  `mb_tpl_layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mb_tpl_layout_title` varchar(50) NOT NULL COMMENT '标题',
  `mb_tpl_layout_type` varchar(50) NOT NULL COMMENT '类型',
  `mb_tpl_layout_data` text NOT NULL COMMENT '根据不同的类型，所存储的数据也不同，仔细！（json）',
  `mb_tpl_layout_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用启用 0:未启用 1:启用',
  `mb_tpl_layout_order` tinyint(2) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`mb_tpl_layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='手机端模板';

-- ----------------------------
--  Table structure for `yf_member_agreement`
-- ----------------------------
DROP TABLE IF EXISTS `yf_member_agreement`;
CREATE TABLE `yf_member_agreement` (
  `member_agreement_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '会员协议id',
  `member_agreement_title` varchar(30) NOT NULL COMMENT '会员协议标题',
  `member_agreement_content` varchar(255) NOT NULL COMMENT '会员协议内容',
  `member_agreement_time` datetime NOT NULL COMMENT '会员协议添加时间',
  `member_agreement_pic` varchar(100) NOT NULL COMMENT '会员协议图片',
  PRIMARY KEY (`member_agreement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员协议表';

-- ----------------------------
--  Table structure for `yf_member_consume_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_member_consume_log`;
CREATE TABLE `yf_member_consume_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) NOT NULL,
  `desc` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `yf_message`
-- ----------------------------
DROP TABLE IF EXISTS `yf_message`;
CREATE TABLE `yf_message` (
  `message_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `message_user_id` int(10) NOT NULL COMMENT '消息接收者id',
  `message_user_name` varchar(50) NOT NULL COMMENT '消息接收者',
  `message_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '消息类型买家1订单信息2成长记录3账户信息4其他',
  `message_title` varchar(100) NOT NULL COMMENT '消息标题',
  `message_content` text NOT NULL COMMENT '消息内容',
  `message_islook` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读取0未1读取',
  `message_create_time` datetime NOT NULL COMMENT '消息创建时间',
  `message_mold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0买家1商家',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统消息表';

-- ----------------------------
--  Table structure for `yf_message_setting`
-- ----------------------------
DROP TABLE IF EXISTS `yf_message_setting`;
CREATE TABLE `yf_message_setting` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `message_template_all` varchar(255) NOT NULL COMMENT '选择开启的所有模板id',
  `setting_time` datetime NOT NULL COMMENT '设置时间',
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息设置表';

-- ----------------------------
--  Table structure for `yf_message_template`
-- ----------------------------
DROP TABLE IF EXISTS `yf_message_template`;
CREATE TABLE `yf_message_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '主题',
  `content_email` text NOT NULL COMMENT '邮件内容',
  `type` tinyint(1) NOT NULL COMMENT '0商家1用户',
  `is_phone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭1开启',
  `is_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭1开启',
  `is_mail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭1开启',
  `content_mail` text NOT NULL COMMENT '站内信内容',
  `content_phone` text NOT NULL COMMENT '短信内容',
  `force_phone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机短信0不强制1强制',
  `force_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮件0不强制1强制',
  `force_mail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '站内信0不强制1强制',
  `mold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0常用提示1订单提示2卡券提示3售后提示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='邮件模板';

-- ----------------------------
--  Records of `yf_message_template`
-- ----------------------------
BEGIN;
INSERT INTO `yf_message_template` VALUES ('1', 'Commodity advisory reply reminder', '商品咨询回复提醒', '[weburl_name]提醒：商品咨询回复提醒', '<p>[weburl_name]提醒：<br /><br />您关于商品[goods_name]的咨询，商家已经回复。去会员中心查看回复。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您关于商品[goods_name]的咨询，商家已经回复。去会员中心查看回复。', '您关于商品[goods_name]的咨询，商家已经回复。去会员中心查看回复。', '1', '0', '1', '3'), ('2', 'verification', '绑定验证', '[weburl_name]提醒：请激活您在[weburl_name]账户', '您绑定手机在[weburl_name]账户,验证码为[yzm]', '3', '1', '1', '1', '您正绑定手机在[weburl_name]账户上,验证码为[yzm]。', '您正绑定手机在[weburl_name]账户上,验证码为[yzm]。', '1', '0', '1', '0'), ('3', 'Complaints_of_goods', '交易被投诉', '[weburl_name]提醒：您售出的商品被投诉，等待商家申诉', '<p>[weburl_name]提醒：<br /><br />您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '1', '1', '1', '您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。', '您好，[weburl_name]提醒：您售出的商品被投诉，等待商家申诉。投诉单编号：[order_id]，请尽快处理。', '1', '1', '1', '3'), ('4', 'Voucher', '优惠券到账', '[weburl_name]提醒：优惠券到账', '<p>[weburl_name]提醒：<br /><br />恭喜您获得[name]优惠券，记得在[end]前使用哦~<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '恭喜您获得[name]优惠券，记得在[end]前使用哦~', '恭喜您获得[name]优惠券，记得在[end]前使用哦~', '0', '0', '0', '2'), ('5', 'place_your_order', '下单通知', '[weburl_name]提醒：下单通知', '<p>[weburl_name]提醒：<br /><br />您的会员在[date]提交了订单[order_id]，请尽快发货。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '1', '1', '1', '您的会员在[date]提交了订单[order_id]，请尽快发货。', '您的会员在[date]提交了订单[order_id]，请尽快发货。', '1', '1', '1', '0'), ('6', 'ordor_complete_shipping', '发货通知', '[weburl_name]提醒：发货通知', '<p>[weburl_name]提醒：<br /><br />您的订单[order_id]于[date]时,已发货啦~<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '1', '1', '1', '您的订单[order_id]于[date]时,已发货啦~', '您的订单[order_id]于[date]时,已发货啦~', '1', '1', '1', '1'), ('7', 'Payment reminder', '付款成功提醒', '[weburl_name]提醒：付款成功提醒', '<p>[weburl_name]提醒：<br /><br />关于订单：[order_id]的款项已经收到，请留意出库通知。可去会员中心查看订单详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '关于订单：[order_id]的款项已经收到，请留意出库通知。可去会员中心查看订单详情。', '关于订单：[order_id]的款项已经收到，请留意出库通知。可去会员中心查看订单详情。', '1', '1', '1', '1'), ('8', 'Refund return reminder', '退款退货提醒', '[weburl_name]提醒：退款退货提醒', '<p>[weburl_name]提醒：<br /><br />您的退款退货单有了变化，可去会员中心查看订单详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您的退款退货单有了变化，可去会员中心查看订单详情。', '您的退款退货单有了变化，可去会员中心查看订单详情。', '1', '1', '1', '3'), ('9', 'The use of vouchers to remind', '代金券使用提醒', '[weburl_name]提醒：代金券使用提醒', '<p>[weburl_name]提醒：<br /><br />您有代金券已经使用，可去会员中心查看订单详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您有代金券已经使用，可去会员中心查看订单详情。', '您有代金券已经使用，可去会员中心查看订单详情。', '1', '1', '1', '2'), ('10', 'welcome', '欢迎信息', '感谢您注册[weburl_name]', '<p>[weburl_name]提醒：<br /><br />感谢您注册[weburl_name]，欢迎您。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '1', '1', '1', '感谢您注册[weburl_name]，欢迎您。', '感谢您注册[weburl_name]，欢迎您。', '1', '1', '1', '0'), ('11', 'goods are not in stock', '您的商品库存不足', '[weburl_name]提醒：您的商品库存不足，请及时补货', '<p>[weburl_name]提醒：<br /><br />您的商品库存不足，请及时补货。SPU：[common_id]，SKU：[goods_id]。<br /><br /><br /><br /><br /><br />                                                                                                       [user_name]<br /><br />                                                                                                         [date]</p>', '2', '0', '0', '0', '您的商品库存不足，请及时补货。SPU：[common_id]，SKU：[goods_id],可去店铺查看详情。', '您的商品库存不足，请及时补货。SPU：[common_id]，SKU：[goods_id],可去店铺查看详情。', '1', '1', '1', '0'), ('12', 'Commodity audit failed to remind', '商品审核失败提醒', '[weburl_name]提醒：您的商品没有通过管理员审核', '<p>[weburl_name]提醒：<br /><br />您的商品没有通过管理员审核，原因：[des]。SPU：[common_id],可去店铺查看详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的商品没有通过管理员审核，原因：[des]。SPU：[common_id],可去店铺查看详情。', '您的商品没有通过管理员审核，原因：[des]。SPU：[common_id],可去店铺查看详情。', '1', '1', '1', '4'), ('13', 'Commodity violation is under the shelf', ' 	\r\n商品违规被下架', '[weburl_name]提醒：商品违规被下架', '<p>[weburl_name]提醒：<br /><br />您的商品被违规下架，原因：[des]。SPU：[common_id],可去店铺查看详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的商品被违规下架，原因：[des]。SPU：[common_id],可去店铺查看详情。', '您的商品被违规下架，原因：[des]。SPU：[common_id],可去店铺查看详情。', '1', '1', '1', '0'), ('14', 'Refund reminder', '退款提醒', '[weburl_name]提醒：退款提醒', '<p>[weburl_name]提醒：<br /><br />您有一个退款单需要处理，退款编号：[order_id],快去查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您有一个退款单需要处理，退款编号：[order_id],快去查看。', '您有一个退款单需要处理，退款编号：[order_id],快去查看。', '1', '1', '1', '0'), ('15', 'Refund automatic processing reminder', '退款自动处理提醒', '[weburl_name]提醒：退款自动处理提醒', '<p>[weburl_name]提醒：<br /><br />您的退款单超期未处理，已自动同意买家退款申请。退款单编号：[order_id],可在店铺查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的退款单超期未处理，已自动同意买家退款申请。退款单编号：[order_id],可在店铺查看。', '您的退款单超期未处理，已自动同意买家退款申请。退款单编号：[order_id],可在店铺查看。', '1', '1', '1', '0'), ('16', 'Return reminder', '退货提醒', '[weburl_name]提醒：退货提醒', '<p>[weburl_name]提醒：<br /><br />您有一个退货单需要处理。退货编号：[order_id],快去查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您有一个退货单需要处理。退货编号：[order_id],快去查看。', '您有一个退款单需要处理，退款编号：[order_id],快去查看。', '1', '1', '1', '0'), ('17', 'Return automatic processing reminder', '退货自动处理提醒', '[weburl_name]提醒：退货自动处理提醒', '<p>[weburl_name]提醒：<br /><br />您的退货单超期未处理，已自动同意买家退款申请。退货单编号：[order_id],可在店铺查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的退货单超期未处理，已自动同意买家退款申请。退货单编号：[order_id],可在店铺查看。', '您的退货单超期未处理，已自动同意买家退款申请。退货单编号：[order_id],可在店铺查看。', '1', '1', '1', '0'), ('18', 'Automatic handling reminder', '退货未收货自动处理提醒', '[weburl_name]提醒：退货未收货自动处理提醒', '<p>[weburl_name]提醒：<br /><br />您的退货单不处理收货超期未处理，已自动按弃货处理。退货单编号：[order_id],可在店铺查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的退货单不处理收货超期未处理，已自动按弃货处理。退货单编号：[order_id],可在店铺查看。', '您的退货单不处理收货超期未处理，已自动按弃货处理。退货单编号：[order_id],可在店铺查看。', '1', '1', '1', '0'), ('19', 'Settlement sheet for confirmation', '结算单等待确认提醒', '[weburl_name]提醒：结算单等待确认提醒', '<p>[weburl_name]提醒：<br /><br />您有新的结算单等待确认，开始时间：[start_time]，结束时间：[end]，结算单号：[order_id],可在店铺查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您有新的结算单等待确认，开始时间：[start_time]，结束时间：[end]，结算单号：[order_id],可在店铺查看。', '您有新的结算单等待确认，开始时间：[start_time]，结束时间：[end]，结算单号：[order_id],可在店铺查看。', '1', '1', '1', '0'), ('20', 'Settlement bill has been paid to remind', '结算单已经付款提醒', '[weburl_name]提醒：结算单已经付款提醒', '<p>[weburl_name]提醒：<br /><br />您的结算单平台已付款，请注意查收，结算单编号：[order_id],可在店铺查看。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '您的结算单平台已付款，请注意查收，结算单编号：[order_id],可在店铺查看。', '您的结算单平台已付款，请注意查收，结算单编号：[order_id],可在店铺查看。', '1', '1', '1', '0'), ('21', 'Store expiration reminder', '店铺到期提醒', '[weburl_name]提醒：店铺到期提醒', '<p>[weburl_name]提醒：<br /><br />你的店铺即将到期，请及时续期。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '2', '0', '0', '0', '你的店铺即将到期，请及时续期。', '你的店铺即将到期，请及时续期。', '1', '1', '1', '0'), ('23', 'Imminent expiration reminder', ' 	\r\n代金券即将到期提醒', '[weburl_name]提醒：代金券即将到期提醒', '<p>[weburl_name]提醒：<br /><br />您有一个代金券即将在[end_time]过期，请记得使用，可去会员中心查看订单详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您有一个代金券即将在[end_time]过期，请记得使用，可去会员中心查看订单详情。', '您有一个代金券即将在[end_time]过期，请记得使用，可去会员中心查看订单详情。', '1', '1', '1', '2'), ('24', 'Redemption code is about to expire reminder', '兑换码即将到期提醒', '[weburl_name]提醒：兑换码即将到期提醒', '<p>[weburl_name]提醒：<br /><br />您有一组兑换码即将在[end_time]过期，请记得使用。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您有一组兑换码即将在[end_time]过期，请记得使用。', '您有一组兑换码即将在[end_time]过期，请记得使用。', '1', '1', '1', '1'), ('25', 'Balance change alert', '余额变动提醒', '[weburl_name]提醒：余额变动提醒', '<p>[weburl_name]提醒：<br /><br />你的账户于[date]账户资金有变化，描述：[des]，可用金额变化 ：[av_amount]，冻结金额变化：[freeze_amount]。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '你的账户于[date]账户资金有变化，描述：[des]，可用金额变化 ：[av_amount]，冻结金额变化：[freeze_amount]。', '你的账户于[date]账户资金有变化，描述：[des]，可用金额变化 ：[av_amount]，冻结金额变化：[freeze_amount]。可去支付中心查看余额。', '1', '1', '1', '2'), ('26', 'Platform customer service reply reminder', '平台客服回复提醒', '[weburl_name]提醒：平台客服回复提醒', '<p>[weburl_name]提醒：<br /><br />您的平台客服咨询已经回复，可去会员中心查看详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您的平台客服咨询已经回复，可去会员中心查看详情。', '您的平台客服咨询已经回复，可去会员中心查看详情。', '1', '1', '1', '3'), ('27', 'Prepaid card balance change reminder', '充值卡余额变动提醒', '[weburl_name]提醒：充值卡余额变动提醒', '<p>[weburl_name]提醒：<br /><br />你的账户于[date]充值卡余额有变化，描述：[des]，可用充值卡余额变化 ：[av_amoun[元，冻结充值卡余额变化：[freeze_amount]。可去支付中心查看充值卡余额。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '你的账户于[date]充值卡余额有变化，描述：[des]，可用充值卡余额变化 ：[av_amoun[元，冻结充值卡余额变化：[freeze_amount]。可去支付中心查看充值卡余额。', '你的账户于[date]充值卡余额有变化，描述：[des]，可用充值卡余额变化 ：[av_amoun[元，冻结充值卡余额变化：[freeze_amount]。可去支付中心查看充值卡余额。', '1', '1', '1', '2'), ('28', 'Scheduled order tail payment reminder', '预定订单尾款支付提醒', '[weburl_name]提醒：预定订单尾款支付提醒', '<p>[weburl_name]提醒：<br /><br />您的订单已经进入支付尾款时间。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您的订单已经进入支付尾款时间。', '您的订单已经进入支付尾款时间。', '1', '1', '1', '1'), ('29', 'Red Alert', ' 	\r\n红包使用提醒', '[weburl_name]提醒：红包使用提醒', '<p>[weburl_name]提醒：<br /><br />您有红包已经使用，编号：[order_id]，可去会员中心查看订单详情。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '1', '0', '0', '0', '您有红包已经使用，编号：[order_id]，可去会员中心查看订单详情。', '您有红包已经使用，编号：[order_id]，可去会员中心查看订单详情。', '1', '1', '1', '0'), ('30', 'Lift verification', '解除验证', '您在[weburl_name]账户进行解除绑定', '<p>[weburl_name]提醒：<br /><br />您正在[weburl_name]账户上进行解除绑定操作,验证码为[yzm]。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p>', '0', '0', '0', '0', '您在[weburl_name]账户上正进行解除绑定,验证码为[yzm]。', '您正在[weburl_name]账户上进行解除绑定操作,验证码为[yzm]。', '0', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yf_number_seq`
-- ----------------------------
DROP TABLE IF EXISTS `yf_number_seq`;
CREATE TABLE `yf_number_seq` (
  `prefix` varchar(20) NOT NULL DEFAULT '' COMMENT '前缀',
  `number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '增长值',
  PRIMARY KEY (`prefix`),
  UNIQUE KEY `prefix` (`prefix`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='编号管理表';

-- ----------------------------
--  Table structure for `yf_order_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_base`;
CREATE TABLE `yf_order_base` (
  `order_id` varchar(50) NOT NULL COMMENT '订单号',
  `shop_id` int(10) NOT NULL COMMENT '卖家店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '卖家店铺名称',
  `buyer_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '买家id',
  `buyer_user_name` varchar(50) NOT NULL COMMENT '买家姓名',
  `seller_user_id` int(10) unsigned NOT NULL COMMENT '卖家id',
  `seller_user_name` varchar(50) NOT NULL,
  `order_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '订单日期',
  `order_create_time` datetime NOT NULL COMMENT '订单生成时间',
  `order_receiver_name` varchar(50) NOT NULL COMMENT '收货人的姓名',
  `order_receiver_address` varchar(255) NOT NULL COMMENT '收货人的详细地址',
  `order_receiver_contact` varchar(50) NOT NULL COMMENT '收货人的联系方式',
  `order_receiver_date` datetime NOT NULL COMMENT '收货时间（最晚收货时间）',
  `payment_id` varchar(50) NOT NULL COMMENT '支付方式id',
  `payment_name` varchar(50) NOT NULL COMMENT '支付方式名称',
  `payment_time` datetime NOT NULL COMMENT '支付(付款)时间',
  `payment_number` varchar(20) NOT NULL COMMENT '支付单号',
  `payment_other_number` varchar(20) NOT NULL COMMENT '第三方支付平台交易号 - 最终支付的支付单号',
  `order_seller_name` varchar(50) NOT NULL COMMENT '发货人的姓名',
  `order_seller_address` varchar(255) NOT NULL COMMENT '发货人的地址',
  `order_seller_contact` varchar(50) NOT NULL COMMENT '发货人的联系方式',
  `order_shipping_time` datetime NOT NULL COMMENT '配送时间',
  `order_shipping_express_id` smallint(3) NOT NULL DEFAULT '0' COMMENT '配送公司ID',
  `order_shipping_code` varchar(50) NOT NULL COMMENT '物流单号',
  `order_shipping_message` varchar(255) NOT NULL COMMENT '卖家备注',
  `order_finished_time` datetime NOT NULL COMMENT '订单完成时间',
  `order_invoice` varchar(100) NOT NULL COMMENT '发票信息',
  `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格',
  `order_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  `order_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣价格',
  `order_point_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '买家使用积分',
  `order_shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费价格',
  `order_buyer_evaluation_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '买家评价状态 0-未评价 1-已评价',
  `order_buyer_evaluation_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '评价时间',
  `order_seller_evaluation_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卖家评价状态 0为评价，1已评价',
  `order_seller_evaluation_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '评价时间',
  `order_message` varchar(255) NOT NULL DEFAULT '' COMMENT '订单留言',
  `order_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `order_points_add` int(10) NOT NULL DEFAULT '0' COMMENT '订单赠送积分',
  `voucher_id` int(10) NOT NULL COMMENT '代金券id',
  `voucher_price` int(10) NOT NULL COMMENT '代金券面额',
  `voucher_code` varchar(32) NOT NULL COMMENT '代金券编码',
  `order_refund_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退款状态:0是无退款,1是退款中,2是退款完成',
  `order_return_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '退货状态:0是无退货,1是退货中,2是退货完成',
  `order_refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `order_return_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `order_from` enum('1','2') NOT NULL DEFAULT '1' COMMENT '手机端',
  `order_commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易佣金',
  `order_commission_return_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '交易佣金退款',
  `order_is_virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟订单',
  `order_virtual_code` varchar(100) NOT NULL DEFAULT '' COMMENT '虚拟商品兑换码',
  `order_virtual_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟商品是否使用 0-未使用 1-已使用',
  `order_shop_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卖家删除',
  `order_buyer_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '买家删除',
  `order_cancel_identity` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单取消者身份   1-买家 2-卖家 3-系统',
  `order_cancel_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单取消原因',
  `order_cancel_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '订单取消时间',
  `order_shop_benefit` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺优惠',
  PRIMARY KEY (`order_id`),
  KEY `shop_id` (`shop_id`),
  KEY `buyer_user_id` (`buyer_user_id`),
  KEY `seller_user_id` (`seller_user_id`),
  KEY `order_status` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单详细信息';

-- ----------------------------
--  Table structure for `yf_order_base1`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_base1`;
CREATE TABLE `yf_order_base1` (
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `order_number` varchar(50) NOT NULL COMMENT '订单单号',
  `order_status` tinyint(1) NOT NULL COMMENT '订单状态',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格',
  `order_freight` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `order_create_time` datetime NOT NULL COMMENT '创建日期',
  `buyer_id` int(10) NOT NULL COMMENT '买家ID',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单表';

-- ----------------------------
--  Table structure for `yf_order_cancel_reason`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_cancel_reason`;
CREATE TABLE `yf_order_cancel_reason` (
  `cancel_reason_id` int(20) NOT NULL AUTO_INCREMENT,
  `cancel_reason_content` varchar(100) DEFAULT '' COMMENT '取消订单的原因',
  `cancel_identity` tinyint(1) DEFAULT '0' COMMENT '取消订单者的身份 1-买家 2-卖家',
  PRIMARY KEY (`cancel_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单取消原因表';

-- ----------------------------
--  Records of `yf_order_cancel_reason`
-- ----------------------------
BEGIN;
INSERT INTO `yf_order_cancel_reason` VALUES ('1', '改买其他商品', '1'), ('2', '改用其他配送方式', '1'), ('3', '从其他商店购买', '1'), ('4', '无法备齐货物', '2'), ('5', '不是有效的订单', '2'), ('6', '买家主动要求', '2');
COMMIT;

-- ----------------------------
--  Table structure for `yf_order_delivery`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_delivery`;
CREATE TABLE `yf_order_delivery` (
  `order_delivery_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) DEFAULT NULL,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `money` decimal(20,2) NOT NULL DEFAULT '0.00',
  `shipping_id` varchar(50) DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_no` varchar(50) DEFAULT NULL,
  `ship_name` varchar(50) DEFAULT NULL,
  `ship_addr` varchar(100) DEFAULT NULL,
  `ship_zip` varchar(20) DEFAULT NULL,
  `ship_tel` varchar(30) DEFAULT NULL,
  `ship_mobile` varchar(50) DEFAULT NULL,
  `start_time` int(10) unsigned DEFAULT NULL,
  `end_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`order_delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='送货地址';

-- ----------------------------
--  Table structure for `yf_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_goods`;
CREATE TABLE `yf_order_goods` (
  `order_goods_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `common_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品common_id',
  `buyer_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '买家id',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_class_id` int(10) NOT NULL COMMENT '商品对应的类目ID',
  `spec_id` int(10) NOT NULL COMMENT '规格id',
  `order_spec_info` varchar(50) NOT NULL COMMENT '规格描述',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `order_goods_num` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '商品数量',
  `goods_image` varchar(255) NOT NULL COMMENT '商品图片',
  `order_goods_returnnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品金额',
  `order_goods_payment_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `order_goods_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `order_goods_adjust_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手工调整金额',
  `order_goods_point_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分费用',
  `order_goods_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品佣金',
  `shop_id` mediumint(10) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `order_goods_status` tinyint(1) NOT NULL COMMENT '订单状态',
  `order_goods_evaluation_status` tinyint(1) NOT NULL COMMENT '评价状态 0为评价，1已评价',
  `order_goods_benefit` varchar(255) NOT NULL DEFAULT '' COMMENT '订单商品优惠',
  `goods_refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退货状态:0是无退货,1是退货中,2是退货完成',
  `order_goods_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '时间',
  PRIMARY KEY (`order_goods_id`),
  KEY `order_id` (`order_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单商品表';

-- ----------------------------
--  Table structure for `yf_order_goods_snapshot`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_goods_snapshot`;
CREATE TABLE `yf_order_goods_snapshot` (
  `order_goods_snapshot_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_id` varchar(50) NOT NULL COMMENT '订单ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `shop_id` int(10) DEFAULT NULL COMMENT '店铺ID',
  `common_id` int(10) NOT NULL COMMENT '商品common_id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `goods_name` varchar(100) DEFAULT NULL COMMENT '商品名称',
  `goods_image` varchar(255) DEFAULT '0' COMMENT '分类ID',
  `goods_price` float(10,2) DEFAULT '0.00' COMMENT '价格',
  `freight` float(10,2) DEFAULT '0.00' COMMENT '运费',
  `snapshot_create_time` datetime DEFAULT NULL,
  `snapshot_uptime` datetime DEFAULT NULL COMMENT '更新时间',
  `snapshot_detail` text,
  PRIMARY KEY (`order_goods_snapshot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='快照表';

-- ----------------------------
--  Table structure for `yf_order_goods_virtual_code`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_goods_virtual_code`;
CREATE TABLE `yf_order_goods_virtual_code` (
  `virtual_code_id` varchar(50) NOT NULL COMMENT '虚拟码',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `order_goods_id` int(10) NOT NULL COMMENT '订单商品id',
  `virtual_code_status` int(10) NOT NULL DEFAULT '0' COMMENT '虚拟码状态 0:未使用 1:已使用 2:冻结',
  `virtual_code_usetime` datetime NOT NULL COMMENT '虚拟兑换码使用时间',
  PRIMARY KEY (`virtual_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='虚拟兑换码';

-- ----------------------------
--  Table structure for `yf_order_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_log`;
CREATE TABLE `yf_order_log` (
  `order_log_id` int(20) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) DEFAULT NULL,
  `admin_id` smallint(5) DEFAULT NULL,
  `admin_name` varchar(30) DEFAULT NULL,
  `order_log_text` longtext,
  `order_log_time` int(10) unsigned DEFAULT NULL,
  `order_log_behavior` varchar(20) DEFAULT '',
  `order_log_result` enum('success','failure') DEFAULT 'success',
  PRIMARY KEY (`order_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `yf_order_payment`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_payment`;
CREATE TABLE `yf_order_payment` (
  `order_payment_id` int(20) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) DEFAULT NULL,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `order_payment_money` decimal(20,2) NOT NULL DEFAULT '0.00',
  `payment_type` enum('online','offline') DEFAULT 'online',
  `payment_id` smallint(4) DEFAULT '0',
  `payment_name` varchar(100) DEFAULT NULL,
  `order_payment_ip` varchar(20) DEFAULT NULL,
  `order_payment_start_time` int(10) unsigned DEFAULT NULL,
  `order_payment_end_time` int(10) unsigned DEFAULT NULL,
  `order_payment_status` tinyint(1) DEFAULT '1',
  `order_payment_trade_no` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`order_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `yf_order_return`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_return`;
CREATE TABLE `yf_order_return` (
  `order_return_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退货记录ID',
  `order_number` varchar(50) NOT NULL COMMENT '订单编号',
  `order_amount` decimal(8,2) NOT NULL COMMENT '订单总额',
  `order_goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '退货商品编号，0为退款',
  `order_goods_name` varchar(255) NOT NULL COMMENT '退款商品名称',
  `order_goods_price` decimal(8,2) NOT NULL COMMENT '商品单价',
  `order_goods_num` int(10) NOT NULL COMMENT '退货数量',
  `order_goods_pic` varchar(255) NOT NULL,
  `return_code` varchar(50) NOT NULL COMMENT '退货编号',
  `return_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-退款申请 2-退货申请 3-虚拟退款',
  `seller_user_id` int(10) unsigned NOT NULL COMMENT '卖家ID',
  `seller_user_account` varchar(50) NOT NULL COMMENT '店铺名称',
  `buyer_user_id` int(10) unsigned NOT NULL COMMENT '买家ID',
  `buyer_user_account` varchar(50) NOT NULL COMMENT '买家会员名',
  `return_add_time` datetime NOT NULL COMMENT '添加时间',
  `return_reason_id` int(10) NOT NULL COMMENT '退款理由id',
  `return_reason` varchar(255) NOT NULL COMMENT '退款理由',
  `return_message` varchar(300) NOT NULL COMMENT '退货备注',
  `return_real_name` varchar(30) NOT NULL COMMENT '收货人',
  `return_addr_id` int(10) NOT NULL COMMENT '收货地址id',
  `return_addr_name` varchar(30) NOT NULL COMMENT '收货地址',
  `return_addr` varchar(150) NOT NULL COMMENT '收货地址详情',
  `return_post_code` int(6) NOT NULL COMMENT '邮编',
  `return_tel` varchar(20) NOT NULL COMMENT '联系电话',
  `return_mobile` varchar(20) NOT NULL COMMENT '联系手机',
  `return_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-新发起等待卖家审核 2-卖家审核通过 3-卖家审核不通过 4-卖家收到货物 5-平台审核通过',
  `return_cash` decimal(8,2) NOT NULL COMMENT '退款金额',
  `return_shop_time` datetime NOT NULL COMMENT '商家处理时间',
  `return_shop_message` varchar(300) NOT NULL COMMENT '商家备注',
  `return_finish_time` datetime NOT NULL COMMENT '退款完成时间',
  `return_commision_fee` decimal(8,2) NOT NULL COMMENT '退还佣金',
  `return_platform_message` varchar(255) NOT NULL COMMENT '平台留言',
  `return_goods_return` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要退货 0-不需要，1-需要',
  PRIMARY KEY (`order_return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='退货表';

-- ----------------------------
--  Table structure for `yf_order_return_reason`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_return_reason`;
CREATE TABLE `yf_order_return_reason` (
  `order_return_reason_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_return_reason_content` varchar(255) NOT NULL COMMENT '投诉理由内容',
  `order_return_reason_sort` int(3) NOT NULL DEFAULT '225' COMMENT '投诉理由排序',
  PRIMARY KEY (`order_return_reason_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yf_order_return_reason`
-- ----------------------------
BEGIN;
INSERT INTO `yf_order_return_reason` VALUES ('1', '运费不合理', '255'), ('2', '不包邮', '255');
COMMIT;

-- ----------------------------
--  Table structure for `yf_order_settlement`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_settlement`;
CREATE TABLE `yf_order_settlement` (
  `os_id` varchar(11) NOT NULL COMMENT '结算单编号(年月店铺ID)',
  `os_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始日期',
  `os_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束日期',
  `os_order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `os_shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `os_order_return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退单金额',
  `os_commis_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `os_commis_return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还佣金',
  `os_shop_cost_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺促销活动费用',
  `os_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应结金额',
  `os_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生成结算单日期',
  `os_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '结算单年月份',
  `os_state` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1默认(已出账)2店家已确认3平台已审核4结算完成',
  `os_pay_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '付款日期',
  `os_pay_content` varchar(200) NOT NULL DEFAULT '' COMMENT '支付备注',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `shop_name` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺名',
  `os_order_type` tinyint(1) NOT NULL COMMENT '结算订单类型 1-虚拟订单 2-实物订单',
  PRIMARY KEY (`os_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单结算表';

-- ----------------------------
--  Table structure for `yf_order_settlement_stat`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_settlement_stat`;
CREATE TABLE `yf_order_settlement_stat` (
  `date` mediumint(9) unsigned NOT NULL,
  `settlement_year` smallint(6) NOT NULL COMMENT '年',
  `start_time` int(11) NOT NULL COMMENT '开始日期',
  `end_time` int(11) NOT NULL COMMENT '结束日期',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退单金额',
  `commission_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `commission_return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还佣金',
  `shop_cost_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺促销活动费用',
  `result_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本期应结',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='月销量统计表';

-- ----------------------------
--  Table structure for `yf_order_state`
-- ----------------------------
DROP TABLE IF EXISTS `yf_order_state`;
CREATE TABLE `yf_order_state` (
  `order_state_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '状态id',
  `order_state_name` varchar(50) NOT NULL COMMENT '订单状态',
  `order_state_text_1` varchar(255) NOT NULL,
  `order_state_text_2` varchar(255) NOT NULL,
  `order_state_text_3` varchar(255) NOT NULL,
  `order_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`order_state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单状态表';

-- ----------------------------
--  Records of `yf_order_state`
-- ----------------------------
BEGIN;
INSERT INTO `yf_order_state` VALUES ('1', 'ORDER_WAIT_PAY', '待付款', '等待买家付款', '下单', ''), ('2', 'ORDER_PAYED', '待配货', '等待卖家配货', '付款', '//如果不启用配货状态，则将状态改成ORDER_WAIT_PREPARE_GOODS'), ('3', 'ORDER_WAIT_PREPARE_GOODS', '待发货', '等待卖家发货', '配货', '//是否启用？-支付完成~快递出库之间'), ('4', 'ORDER_WAIT_CONFIRM_GOODS', '已发货', '等待买家确认收货', '出库', ''), ('5', 'ORDER_RECEIVED', '已签收', '买家已签收', '已签收', '//买家已签收,货到付款专用'), ('6', 'ORDER_FINISH', '已完成', '交易成功', '交易成功', '//success  fail  mr'), ('7', 'ORDER_CANCEL', '已取消', '交易关闭', '交易关闭', '//付款以后用户退款成功，交易自动关闭'), ('8', 'ORDER_REFUND', '退款中', '买家发起退款', '退款中', ''), ('9', 'ORDER_REFUND_FINISH', '退款完成', '退款平台审核通过', '退款完成', '');
COMMIT;

-- ----------------------------
--  Table structure for `yf_payment_channel`
-- ----------------------------
DROP TABLE IF EXISTS `yf_payment_channel`;
CREATE TABLE `yf_payment_channel` (
  `payment_channel_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `payment_channel_code` varchar(20) NOT NULL DEFAULT '' COMMENT '代码名称',
  `payment_channel_name` varchar(100) NOT NULL DEFAULT '' COMMENT '支付名称',
  `payment_channel_config` text NOT NULL COMMENT '支付接口配置信息',
  `payment_channel_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '接口状态',
  `payment_channel_allow` enum('pc','wap','both') NOT NULL DEFAULT 'pc' COMMENT '类型',
  `payment_channel_wechat` tinyint(4) NOT NULL DEFAULT '1' COMMENT '微信中是否可以使用',
  `payment_channel_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`payment_channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='支付渠道表';

-- ----------------------------
--  Table structure for `yf_platform_custom_service`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_custom_service`;
CREATE TABLE `yf_platform_custom_service` (
  `custom_service_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '平台咨询ID',
  `custom_service_type_id` int(10) unsigned NOT NULL COMMENT '平台咨询类型ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户Id',
  `user_account` varchar(50) NOT NULL COMMENT '用户账号',
  `custom_service_question` varchar(255) NOT NULL COMMENT '咨询内容',
  `custom_service_question_time` datetime NOT NULL,
  `user_id_admin` int(10) unsigned NOT NULL COMMENT '平台客服id-管理员id',
  `custom_service_answer` varchar(255) NOT NULL COMMENT '咨询回复',
  `custom_service_answer_time` datetime NOT NULL,
  `custom_service_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否回复  1   2:已经回复',
  PRIMARY KEY (`custom_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台客服-平台咨询表';

-- ----------------------------
--  Table structure for `yf_platform_custom_service_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_custom_service_type`;
CREATE TABLE `yf_platform_custom_service_type` (
  `custom_service_type_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '平台咨询类型ID',
  `custom_service_type_sort` int(3) NOT NULL DEFAULT '255' COMMENT '平台咨询类型排序',
  `custom_service_type_name` varchar(50) NOT NULL COMMENT '平台咨询类型名',
  `custom_service_type_desc` varchar(255) NOT NULL COMMENT '平台咨询类型备注',
  PRIMARY KEY (`custom_service_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='平台咨询类别表';

-- ----------------------------
--  Table structure for `yf_platform_nav`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_nav`;
CREATE TABLE `yf_platform_nav` (
  `nav_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `nav_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类别，0自定义导航，1商品分类，2文章导航，3活动导航，默认为0',
  `nav_item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别ID，对应着nav_type中的内容，默认为0',
  `nav_title` varchar(100) NOT NULL COMMENT '导航标题',
  `nav_url` varchar(255) NOT NULL COMMENT '导航链接',
  `nav_location` tinyint(1) NOT NULL DEFAULT '0' COMMENT '导航位置，0头部，1中部，2底部，默认为0',
  `nav_new_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否以新窗口打开，0为否，1为是，默认为0',
  `nav_displayorder` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `nav_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `nav_readonly` tinyint(4) NOT NULL DEFAULT '0' COMMENT '不可修改-团购、积分等等',
  PRIMARY KEY (`nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='页面导航表';

-- ----------------------------
--  Table structure for `yf_platform_report`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_report`;
CREATE TABLE `yf_platform_report` (
  `report_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) NOT NULL COMMENT '会员id',
  `user_account` varchar(50) NOT NULL COMMENT '会员名',
  `goods_id` int(10) NOT NULL COMMENT '被举报的商品id',
  `goods_name` varchar(100) NOT NULL COMMENT '被举报的商品名称',
  `goods_image` varchar(255) NOT NULL,
  `subject_id` int(10) NOT NULL COMMENT '举报主题id',
  `subject_name` varchar(50) NOT NULL COMMENT '举报主题',
  `report_content` varchar(100) NOT NULL COMMENT '举报信息',
  `report_image` varchar(255) NOT NULL COMMENT '图片',
  `report_time` datetime NOT NULL COMMENT '举报时间',
  `shop_id` int(10) NOT NULL COMMENT '被举报商品的店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '被举报商品的店铺',
  `report_state` tinyint(1) NOT NULL COMMENT '举报状态(1未处理/2已处理)',
  `report_result` tinyint(1) NOT NULL COMMENT '举报处理结果(1无效举报/2恶意举报/3有效举报)',
  `report_message` varchar(100) NOT NULL COMMENT '举报处理信息',
  `report_handle_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '举报处理时间',
  `report_handle_admin` varchar(50) NOT NULL DEFAULT '0' COMMENT '管理员',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='举报表';

-- ----------------------------
--  Table structure for `yf_platform_report_subject`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_report_subject`;
CREATE TABLE `yf_platform_report_subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报主题id',
  `subject_name` varchar(100) NOT NULL COMMENT '举报主题内容',
  `type_id` int(11) NOT NULL COMMENT '举报类型id',
  `type_name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='举报主题表';

-- ----------------------------
--  Table structure for `yf_platform_report_subject_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_platform_report_subject_type`;
CREATE TABLE `yf_platform_report_subject_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报类型id',
  `name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  `desc` varchar(100) NOT NULL COMMENT '举报类型描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='举报类型表';

-- ----------------------------
--  Table structure for `yf_points_cart`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_cart`;
CREATE TABLE `yf_points_cart` (
  `points_cart_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `points_user_id` int(10) NOT NULL COMMENT '会员编号',
  `points_goods_id` int(10) NOT NULL COMMENT '积分礼品序号',
  `points_goods_name` varchar(10) NOT NULL COMMENT '积分礼品名称',
  `points_goods_points` int(10) NOT NULL COMMENT '积分礼品兑换积分',
  `points_goods_choosenum` int(10) NOT NULL COMMENT '选择积分礼品数量',
  `points_goods_image` varchar(255) NOT NULL COMMENT '积分礼品图片',
  PRIMARY KEY (`points_cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分礼品兑换购物车';

-- ----------------------------
--  Table structure for `yf_points_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_goods`;
CREATE TABLE `yf_points_goods` (
  `points_goods_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '积分礼品索引id',
  `points_goods_name` varchar(100) NOT NULL COMMENT '积分礼品名称',
  `points_goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分礼品原价',
  `points_goods_points` int(10) NOT NULL COMMENT '积分礼品兑换所需积分',
  `points_goods_image` varchar(255) NOT NULL COMMENT '积分礼品默认封面图片',
  `points_goods_tag` varchar(100) NOT NULL COMMENT '积分礼品标签',
  `points_goods_serial` varchar(50) NOT NULL COMMENT '积分礼品货号',
  `points_goods_storage` int(10) NOT NULL DEFAULT '0' COMMENT '积分礼品库存数',
  `points_goods_shelves` tinyint(1) NOT NULL COMMENT '积分礼品上架 0表示下架 1表示上架',
  `points_goods_recommend` tinyint(1) NOT NULL COMMENT '积分礼品是否推荐,1-是、0-否',
  `points_goods_add_time` datetime NOT NULL COMMENT '积分礼品添加时间',
  `points_goods_keywords` varchar(100) NOT NULL COMMENT '积分礼品关键字',
  `points_goods_description` varchar(200) NOT NULL COMMENT '积分礼品描述',
  `points_goods_body` text NOT NULL COMMENT '积分礼品详细内容',
  `points_goods_salenum` int(10) NOT NULL DEFAULT '0' COMMENT '积分礼品售出数量',
  `points_goods_view` int(10) NOT NULL DEFAULT '0' COMMENT '积分商品浏览次数',
  `points_goods_limitgrade` int(10) NOT NULL DEFAULT '0' COMMENT '换购针对会员等级限制，默认为0,所有等级都可换购',
  `points_goods_islimit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限制每会员兑换数量，0不限制，1限制，默认0',
  `points_goods_limitnum` int(10) NOT NULL COMMENT '每会员限制兑换数量',
  `points_goods_islimittime` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限制兑换时间 0为不限制 1为限制',
  `points_goods_starttime` datetime NOT NULL COMMENT '兑换开始时间',
  `points_goods_endtime` datetime NOT NULL COMMENT '兑换结束时间',
  `points_goods_sort` int(10) NOT NULL DEFAULT '0' COMMENT '礼品排序',
  PRIMARY KEY (`points_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='积分礼品表';

-- ----------------------------
--  Table structure for `yf_points_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_log`;
CREATE TABLE `yf_points_log` (
  `points_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `points_log_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1获取积分2积分消费',
  `class_id` tinyint(1) NOT NULL COMMENT '积分类型1.会员注册,2.会员登录3.评价4.购买商品5.6.管理员操作7.积分换购商品8.积分兑换代金券',
  `user_id` int(10) NOT NULL COMMENT '会员编号',
  `user_name` varchar(50) NOT NULL COMMENT '会员名称',
  `admin_name` varchar(100) NOT NULL COMMENT '管理员名称',
  `points_log_points` int(10) NOT NULL DEFAULT '0' COMMENT '可用积分',
  `freeze_points` int(10) NOT NULL DEFAULT '0' COMMENT '冻结积分',
  `points_log_time` datetime NOT NULL COMMENT '创建时间',
  `points_log_desc` varchar(100) NOT NULL COMMENT '描述',
  `points_log_flag` varchar(20) NOT NULL COMMENT '标记',
  PRIMARY KEY (`points_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员积分日志表';

-- ----------------------------
--  Table structure for `yf_points_order`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_order`;
CREATE TABLE `yf_points_order` (
  `points_order_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '兑换订单编号',
  `points_order_rid` varchar(50) NOT NULL COMMENT '兑换订单号',
  `points_buyerid` int(10) NOT NULL COMMENT '兑换会员id',
  `points_buyername` varchar(50) NOT NULL COMMENT '兑换会员姓名',
  `points_buyeremail` varchar(100) NOT NULL COMMENT '兑换会员email',
  `points_addtime` datetime NOT NULL COMMENT '兑换订单生成时间',
  `points_paymenttime` datetime NOT NULL COMMENT '支付(付款)时间',
  `points_shippingtime` datetime NOT NULL COMMENT '配送时间',
  `points_shippingcode` varchar(50) NOT NULL COMMENT '物流单号',
  `points_logistics` varchar(50) NOT NULL COMMENT '物流公司名称',
  `points_finnshedtime` datetime NOT NULL COMMENT '订单完成时间',
  `points_allpoints` int(10) NOT NULL DEFAULT '0' COMMENT '兑换总积分',
  `points_orderamount` decimal(10,2) NOT NULL COMMENT '兑换订单总金额',
  `points_shippingcharge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '运费承担方式 0表示平台 1表示买家',
  `points_shippingfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费金额',
  `points_ordermessage` varchar(300) NOT NULL DEFAULT '无' COMMENT '订单留言',
  `points_orderstate` int(4) NOT NULL DEFAULT '1' COMMENT '订单状态：1(已下单，等待发货);2(已发货，等待收货);3(确认收货)4(取消):',
  PRIMARY KEY (`points_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分兑换订单表';

-- ----------------------------
--  Table structure for `yf_points_orderaddress`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_orderaddress`;
CREATE TABLE `yf_points_orderaddress` (
  `points_oaid` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `points_orderid` varchar(20) NOT NULL COMMENT '订单id',
  `points_truename` varchar(10) NOT NULL COMMENT '收货人姓名',
  `points_areaid` int(10) NOT NULL COMMENT '地区id',
  `points_areainfo` varchar(100) NOT NULL COMMENT '地区内容',
  `points_address` varchar(200) NOT NULL COMMENT '详细地址',
  `points_zipcode` varchar(20) NOT NULL COMMENT '邮政编码',
  `points_telphone` varchar(20) NOT NULL COMMENT '电话号码',
  `points_mobphone` varchar(20) NOT NULL COMMENT '手机号码',
  PRIMARY KEY (`points_oaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='兑换订单地址表';

-- ----------------------------
--  Table structure for `yf_points_ordergoods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_points_ordergoods`;
CREATE TABLE `yf_points_ordergoods` (
  `points_recid` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单礼品表索引',
  `points_orderid` varchar(50) NOT NULL COMMENT '订单id',
  `points_goodsid` int(10) NOT NULL COMMENT '礼品id',
  `points_goodsname` varchar(100) NOT NULL COMMENT '礼品名称',
  `points_goodspoints` int(10) NOT NULL COMMENT '礼品兑换积分',
  `points_goodsnum` int(10) NOT NULL COMMENT '礼品数量',
  `points_goodsimage` varchar(255) NOT NULL COMMENT '礼品图片',
  PRIMARY KEY (`points_recid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='兑换订单商品表';

-- ----------------------------
--  Table structure for `yf_rec_position`
-- ----------------------------
DROP TABLE IF EXISTS `yf_rec_position`;
CREATE TABLE `yf_rec_position` (
  `position_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `position_title` varchar(30) NOT NULL COMMENT '推荐位标题',
  `position_type` tinyint(1) NOT NULL COMMENT '推荐位类型 0-图片 1-文字',
  `position_pic` varchar(255) NOT NULL COMMENT '推荐位图片',
  `position_content` varchar(255) NOT NULL COMMENT '文字展示',
  `position_alert_type` tinyint(1) NOT NULL COMMENT '弹出方式 0 本窗口 1 新窗口',
  `position_url` varchar(255) NOT NULL COMMENT '跳转网址',
  `position_code` varchar(255) NOT NULL COMMENT '调用代码',
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_report_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_report_base`;
CREATE TABLE `yf_report_base` (
  `report_id` int(10) NOT NULL AUTO_INCREMENT,
  `report_type_id` int(10) NOT NULL,
  `report_type_name` varchar(50) NOT NULL,
  `report_subject_id` int(10) NOT NULL,
  `report_subject_name` varchar(50) NOT NULL,
  `report_message` varchar(255) NOT NULL,
  `report_pic` text NOT NULL COMMENT '举报证据，逗号分隔',
  `report_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-未处理 2-已处理',
  `user_id` int(10) NOT NULL,
  `user_account` varchar(50) NOT NULL,
  `shop_id` int(10) NOT NULL,
  `shop_name` varchar(50) NOT NULL,
  `goods_id` int(10) NOT NULL,
  `goods_name` varchar(255) NOT NULL,
  `goods_pic` varchar(255) NOT NULL,
  `report_date` datetime NOT NULL,
  `report_handle_message` varchar(255) NOT NULL,
  `report_handle_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-未处理 1-有效 2-无效',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_report_subject`
-- ----------------------------
DROP TABLE IF EXISTS `yf_report_subject`;
CREATE TABLE `yf_report_subject` (
  `report_subject_id` int(10) NOT NULL AUTO_INCREMENT,
  `report_subject_name` varchar(50) NOT NULL,
  `report_type_id` int(10) NOT NULL,
  `report_type_name` varchar(50) NOT NULL,
  PRIMARY KEY (`report_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_report_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_report_type`;
CREATE TABLE `yf_report_type` (
  `report_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `report_type_name` varchar(50) NOT NULL,
  `report_type_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`report_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yf_report_type`
-- ----------------------------
BEGIN;
INSERT INTO `yf_report_type` VALUES ('1', '举报测试', '测试测试123'), ('2', '测试删除', '测测看'), ('4', 'ceshi', '阿瑟大时代');
COMMIT;

-- ----------------------------
--  Table structure for `yf_search_word`
-- ----------------------------
DROP TABLE IF EXISTS `yf_search_word`;
CREATE TABLE `yf_search_word` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_keyword` varchar(80) DEFAULT NULL,
  `search_char_index` varchar(80) DEFAULT NULL,
  `search_nums` int(11) DEFAULT '0',
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='搜索热门词';

-- ----------------------------
--  Table structure for `yf_seller_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_seller_base`;
CREATE TABLE `yf_seller_base` (
  `seller_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '卖家id',
  `seller_name` varchar(50) NOT NULL COMMENT '卖家用户名',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `rights_group_id` int(10) unsigned NOT NULL COMMENT '卖家组ID',
  `seller_is_admin` tinyint(3) unsigned NOT NULL COMMENT '是否管理员(0-不是 1-是)',
  `seller_login_time` datetime NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卖家用户表';

-- ----------------------------
--  Table structure for `yf_seller_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_seller_log`;
CREATE TABLE `yf_seller_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(50) NOT NULL COMMENT '日志内容',
  `create_time` int(10) unsigned NOT NULL COMMENT '日志时间',
  `seller_id` int(10) unsigned NOT NULL COMMENT '卖家id',
  `seller_name` varchar(50) NOT NULL COMMENT '卖家帐号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `seller_ip` varchar(50) NOT NULL COMMENT '卖家ip',
  `url` varchar(50) NOT NULL COMMENT '日志url',
  `status` tinyint(1) unsigned NOT NULL COMMENT '日志状态(0-失败 1-成功)',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卖家日志表';

-- ----------------------------
--  Table structure for `yf_seller_rights_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_seller_rights_base`;
CREATE TABLE `yf_seller_rights_base` (
  `rights_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限Id',
  `rights_name` varchar(20) NOT NULL DEFAULT '' COMMENT '权限名称',
  `rights_parent_id` smallint(4) unsigned NOT NULL COMMENT '权限父Id',
  `rights_remark` varchar(255) NOT NULL COMMENT '备注',
  `rights_order` smallint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`rights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限表 ';

-- ----------------------------
--  Table structure for `yf_seller_rights_group`
-- ----------------------------
DROP TABLE IF EXISTS `yf_seller_rights_group`;
CREATE TABLE `yf_seller_rights_group` (
  `rights_group_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组id',
  `rights_group_name` varchar(50) NOT NULL COMMENT '权限组名称',
  `rights_group_rights_ids` text NOT NULL COMMENT '权限列表',
  `rights_group_add_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rights_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限组表';

-- ----------------------------
--  Table structure for `yf_service`
-- ----------------------------
DROP TABLE IF EXISTS `yf_service`;
CREATE TABLE `yf_service` (
  `service_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '消费者保障id',
  `service_name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `service_desc` text NOT NULL COMMENT '消费者保障描述',
  `service_deposit` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '保证金',
  `service_icon` varchar(200) NOT NULL DEFAULT '' COMMENT '项目图标',
  `service_url` varchar(200) NOT NULL DEFAULT '' COMMENT '说明文章链接地址',
  `service_displayorder` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `service_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消费者保障服务表';

-- ----------------------------
--  Table structure for `yf_shared_bindings`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shared_bindings`;
CREATE TABLE `yf_shared_bindings` (
  `shared_bindings_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分享绑定id',
  `shared_bindings_name` varchar(50) NOT NULL COMMENT '分享绑定的名字',
  `shared_bindings_ulr` varchar(50) NOT NULL COMMENT '绑定的url',
  `shared_bindings_statu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启状态0否1开启',
  `shared_bindings_appid` varchar(50) NOT NULL COMMENT '应用标识',
  `shared_bindings_key` varchar(100) NOT NULL COMMENT '应用密钥',
  `shared_bindings_appcode` text NOT NULL COMMENT '域名验证信息',
  PRIMARY KEY (`shared_bindings_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `yf_shop_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_base`;
CREATE TABLE `yf_shop_base` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名称',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `shop_grade_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺等级',
  `shop_class_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺绑定分类，如果是自营店铺就为0.',
  `shop_all_class` tinyint(1) NOT NULL DEFAULT '0' COMMENT '绑定所有经营类目',
  `shop_self_support` enum('true','false') NOT NULL DEFAULT 'false' COMMENT '是否自营',
  `shop_create_time` datetime NOT NULL COMMENT '开店时间',
  `shop_end_time` datetime NOT NULL COMMENT '有效期截止时间',
  `shop_latitude` varchar(20) NOT NULL DEFAULT '' COMMENT '纬度',
  `shop_longitude` varchar(20) NOT NULL DEFAULT '' COMMENT '经度',
  `shop_settlement_cycle` mediumint(4) NOT NULL DEFAULT '30' COMMENT '结算周期-天为单位-如果您想设置结算周期为一个月，则可以输入30',
  `shop_points` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `shop_logo` varchar(255) NOT NULL COMMENT '店铺logo',
  `shop_banner` varchar(255) NOT NULL COMMENT '店铺banner',
  `shop_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺状态-3：开店成功 2:待审核付款 1:待审核资料  0:关闭',
  `shop_close_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '关闭原因',
  `shop_praise_rate` float(10,2) NOT NULL DEFAULT '0.00',
  `shop_desccredit` float(10,2) NOT NULL DEFAULT '0.00',
  `shop_servicecredit` float(10,2) NOT NULL DEFAULT '0.00',
  `shop_deliverycredit` float(10,2) NOT NULL DEFAULT '0.00',
  `shop_collect` int(10) NOT NULL DEFAULT '0',
  `shop_template` varchar(255) NOT NULL DEFAULT 'default' COMMENT '店铺绑定模板',
  `shop_workingtime` text NOT NULL COMMENT '工作时间',
  `shop_slide` text NOT NULL,
  `shop_slideurl` text NOT NULL,
  `shop_domain` varchar(20) NOT NULL COMMENT '二级域名',
  `shop_region` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺默认配送区域',
  `shop_address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `shop_qq` varchar(20) NOT NULL COMMENT 'qq',
  `shop_ww` varchar(20) NOT NULL DEFAULT '' COMMENT '旺旺',
  `shop_tel` varchar(12) NOT NULL DEFAULT '' COMMENT '卖家电话',
  `shop_free_shipping` int(10) NOT NULL DEFAULT '0' COMMENT '免运费额度',
  `shop_account` varchar(255) NOT NULL COMMENT '商家账号',
  `shop_payment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未付款，1已付款',
  `joinin_year` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `is_renovation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启装修(0:否，1：是)',
  `is_only_renovation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否仅显示装修(1：是，0：否）',
  `is_index_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否左侧显示',
  `shop_print_desc` varchar(500) DEFAULT NULL COMMENT '打印订单页面下方说明',
  `shop_stamp` varchar(200) DEFAULT NULL COMMENT '店铺印章-将出现在打印订单的右下角位置',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺表';

-- ----------------------------
--  Table structure for `yf_shop_class`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_class`;
CREATE TABLE `yf_shop_class` (
  `shop_class_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '店铺分类id',
  `shop_class_name` varchar(50) NOT NULL COMMENT '店铺分类名称',
  `shop_class_deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金数额(元)',
  `shop_class_displayorder` smallint(3) NOT NULL DEFAULT '255' COMMENT '显示次序',
  PRIMARY KEY (`shop_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺分类表-平台设置';

-- ----------------------------
--  Table structure for `yf_shop_class_bind`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_class_bind`;
CREATE TABLE `yf_shop_class_bind` (
  `shop_class_bind_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `product_class_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `commission_rate` decimal(4,0) NOT NULL DEFAULT '0' COMMENT '百分比',
  `shop_class_bind_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`shop_class_bind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺经营类目\r\n';

-- ----------------------------
--  Table structure for `yf_shop_company`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_company`;
CREATE TABLE `yf_shop_company` (
  `shop_id` int(10) NOT NULL,
  `shop_company_name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `shop_company_address` varchar(50) NOT NULL DEFAULT '' COMMENT '公司所在地',
  `company_address_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '公司详细地址',
  `company_employee_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工总数',
  `company_registered_capital` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册资金',
  `company_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '公司电话',
  `contacts_phone` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `contacts_email` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人email',
  `contacts_name` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `legal_person` varchar(50) NOT NULL COMMENT '法定代表人姓名',
  `legal_person_number` varchar(50) NOT NULL COMMENT '法人身份证号',
  `legal_person_electronic` varchar(255) NOT NULL COMMENT '法人身份证电子版',
  `business_license_location` varchar(255) NOT NULL COMMENT '营业执照所在地',
  `establish_date` date NOT NULL COMMENT '成立日期',
  `business_licence_start` date NOT NULL COMMENT '法定经营范围开始时间',
  `business_licence_end` date NOT NULL COMMENT '法定经营范围结束时间',
  `business_sphere` varchar(255) NOT NULL COMMENT '业务范围',
  `business_license_electronic` varchar(255) NOT NULL COMMENT '营业执照电子版',
  `organization_code` varchar(20) NOT NULL COMMENT '组织机构代码',
  `organization_code_start` date NOT NULL COMMENT '组织机构代码证有效期开始时间',
  `organization_code_end` date NOT NULL COMMENT '组织机构代码证有效期结束时间',
  `organization_code_electronic` varchar(255) NOT NULL COMMENT '组织机构代码证电子版',
  `general_taxpayer` varchar(255) NOT NULL COMMENT '一般纳税人证明',
  `bank_account_name` varchar(50) NOT NULL COMMENT '银行开户名',
  `bank_account_number` varchar(20) NOT NULL COMMENT '公司银行账号',
  `bank_name` varchar(50) NOT NULL COMMENT '开户银行支行名称',
  `bank_code` varchar(20) NOT NULL COMMENT '开户银行支行联行号',
  `bank_address` varchar(255) NOT NULL COMMENT '开户银行支行所在地',
  `bank_licence_electronic` varchar(255) NOT NULL COMMENT '开户银行许可证电子版',
  `tax_registration_certificate` varchar(20) NOT NULL COMMENT '税务登记证号',
  `taxpayer_id` varchar(20) NOT NULL COMMENT '纳税人识别号',
  `tax_registration_certificate_electronic` varchar(255) NOT NULL COMMENT '税务登记证号电子版',
  `payment_voucher` varchar(255) NOT NULL COMMENT '付款凭证',
  `payment_voucher_explain` varchar(255) NOT NULL COMMENT '付款凭证说明',
  `shop_class_ids` text NOT NULL COMMENT '店铺经营类目ID集合',
  `shop_class_names` text NOT NULL COMMENT '店铺经营类目名称集合',
  `shop_class_commission` text NOT NULL COMMENT '店铺经营类目佣金比例',
  `fee` float(10,2) NOT NULL COMMENT '收费标准',
  `deposit` float(10,2) NOT NULL COMMENT '保证金',
  `business_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '营业执照号',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺公司信息表';

-- ----------------------------
--  Table structure for `yf_shop_contract`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_contract`;
CREATE TABLE `yf_shop_contract` (
  `contract_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `contract_type_id` int(10) NOT NULL COMMENT '服务id',
  `shop_id` int(10) NOT NULL COMMENT '商铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '商铺名称',
  `contract_type_name` varchar(50) NOT NULL COMMENT '服务类别名称',
  `contract_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1-可以使用 2-永久不能使用',
  `contract_use_state` tinyint(1) NOT NULL DEFAULT '2' COMMENT '加入状态：1--已加入 2-已退出',
  `contract_cash` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '保障金余额',
  `contract_log_id` int(10) NOT NULL COMMENT '保证金当前日志id',
  PRIMARY KEY (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消费者保障服务店铺关联表';

-- ----------------------------
--  Table structure for `yf_shop_contract_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_contract_log`;
CREATE TABLE `yf_shop_contract_log` (
  `contract_log_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `contract_id` int(10) NOT NULL COMMENT '服务id',
  `contract_type_id` int(10) NOT NULL COMMENT '服务id',
  `contract_type_name` varchar(50) NOT NULL COMMENT '服务名称',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `contract_log_operator` varchar(50) NOT NULL COMMENT '操作人',
  `contract_log_date` datetime NOT NULL COMMENT '日志生成时间',
  `contract_log_desc` varchar(255) NOT NULL COMMENT '日志内容',
  `contract_cash` decimal(10,2) NOT NULL COMMENT '支付保证金金额,有正负',
  `contract_log_type` tinyint(1) NOT NULL DEFAULT '4' COMMENT '1-保证金操作 2-加入操作 3-退出操作 4-其它操作',
  `contract_log_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-待审核(加入/退出) 2-保证金待审核(加入) 3-审核通过(加入/退出) 4-审核不通过(加入/退出) 5-已缴纳保证金',
  `contract_cash_pic` varchar(255) NOT NULL COMMENT '保证金图片',
  PRIMARY KEY (`contract_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消费者保障服务保证金缴纳日志表';

-- ----------------------------
--  Table structure for `yf_shop_contract_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_contract_type`;
CREATE TABLE `yf_shop_contract_type` (
  `contract_type_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '服务id',
  `contract_type_name` varchar(50) NOT NULL COMMENT '服务名称',
  `contract_type_cash` decimal(10,2) NOT NULL COMMENT '保证金金额',
  `contract_type_logo` varchar(255) NOT NULL COMMENT '服务logo',
  `contract_type_desc` text NOT NULL COMMENT '服务介绍',
  `contract_type_url` varchar(100) NOT NULL COMMENT '服务介绍文章链接',
  `contract_type_sort` int(3) NOT NULL COMMENT '显示顺序',
  `contract_type_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '服务状态：1-开启，2-关闭',
  PRIMARY KEY (`contract_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='消费者保障服务类型表';

-- ----------------------------
--  Records of `yf_shop_contract_type`
-- ----------------------------
BEGIN;
INSERT INTO `yf_shop_contract_type` VALUES ('1', '七天退货', '1001.00', 'http://127.0.0.1/repos/yf_shop/image.php/shop/data/upload/media/plantform/image/20160725/1469432663116504.jpg', '1231312312', 'http://shop.bbc-builder.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;gid=158', '1', '1'), ('2', '品质承诺', '12.00', '343.gif', 'sdsds', 'http://shop.bbc-builder.com/index.php?ctl=Goods_Goods&amp;met=goods&amp;gid=158', '1', '1'), ('3', '破损寄补', '0.00', '', '', '', '0', '1'), ('4', '极速物流', '0.00', '', '阿达', '', '123', '1'), ('5', '23423', '13.00', '', '1321321', '', '13', '2'), ('6', '', '0.00', '', '', '', '0', '2'), ('7', '', '0.00', '', '', '', '0', '2'), ('8', '', '0.00', '', '', '', '0', '2'), ('9', '', '0.00', '', '', '', '0', '2'), ('10', '', '0.00', '', '', '', '0', '2');
COMMIT;

-- ----------------------------
--  Table structure for `yf_shop_cost`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_cost`;
CREATE TABLE `yf_shop_cost` (
  `cost_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '会员id',
  `user_account` varchar(50) NOT NULL COMMENT '用户账号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `cost_price` float(10,2) NOT NULL COMMENT '费用',
  `cost_desc` varchar(255) NOT NULL COMMENT '描述',
  `cost_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0未结算 1已结算',
  `cost_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`cost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺费用表';

-- ----------------------------
--  Table structure for `yf_shop_custom_service`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_custom_service`;
CREATE TABLE `yf_shop_custom_service` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `name` varchar(20) NOT NULL COMMENT '客服名称',
  `tool` tinyint(1) NOT NULL COMMENT '客服工具',
  `number` varchar(30) NOT NULL COMMENT '客服账号',
  `type` tinyint(1) NOT NULL COMMENT '客服类型 0-售前客服 1-售后客服',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺客服表';

-- ----------------------------
--  Table structure for `yf_shop_decoration`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_decoration`;
CREATE TABLE `yf_shop_decoration` (
  `decoration_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '装修编号',
  `decoration_name` varchar(50) NOT NULL COMMENT '装修名称',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `decoration_setting` varchar(500) NOT NULL COMMENT '装修整体设置(背景、边距等)',
  `decoration_nav` varchar(5000) NOT NULL COMMENT '装修导航',
  `decoration_banner` varchar(255) NOT NULL COMMENT '装修头部banner',
  PRIMARY KEY (`decoration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺装修表';

-- ----------------------------
--  Table structure for `yf_shop_decoration_album`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_decoration_album`;
CREATE TABLE `yf_shop_decoration_album` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片编号',
  `image_name` varchar(50) NOT NULL COMMENT '图片名称',
  `image_origin_name` varchar(50) NOT NULL COMMENT '图片原始名称',
  `image_width` int(10) unsigned NOT NULL COMMENT '图片宽度',
  `image_height` int(10) unsigned NOT NULL COMMENT '图片高度',
  `image_size` int(10) unsigned NOT NULL COMMENT '图片大小',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `upload_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺装修相册表';

-- ----------------------------
--  Table structure for `yf_shop_decoration_block`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_decoration_block`;
CREATE TABLE `yf_shop_decoration_block` (
  `block_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '装修块编号',
  `decoration_id` int(10) unsigned NOT NULL COMMENT '装修编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `block_layout` varchar(50) NOT NULL COMMENT '块布局',
  `block_content` text COMMENT '块内容',
  `block_module_type` varchar(50) DEFAULT NULL COMMENT '装修块模块类型',
  `block_full_width` tinyint(3) unsigned DEFAULT NULL COMMENT '是否100%宽度(0-否 1-是)',
  `block_sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '块排序',
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺装修块表';

-- ----------------------------
--  Table structure for `yf_shop_domain`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_domain`;
CREATE TABLE `yf_shop_domain` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_sub_domain` varchar(100) NOT NULL COMMENT '二级域名',
  `shop_edit_domain` int(10) NOT NULL COMMENT '编辑次数',
  `shop_self_domain` varchar(100) NOT NULL COMMENT '自定义域名',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='二级域名表';

-- ----------------------------
--  Table structure for `yf_shop_entity`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_entity`;
CREATE TABLE `yf_shop_entity` (
  `entity_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '实体店铺id',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `entity_name` char(60) NOT NULL DEFAULT '0' COMMENT '实体店铺名称',
  `lng` varchar(20) NOT NULL DEFAULT '0' COMMENT '经度',
  `lat` varchar(20) NOT NULL DEFAULT '0' COMMENT '纬度',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省份',
  `entity_xxaddr` varchar(255) NOT NULL COMMENT '详细地址',
  `entity_tel` varchar(30) NOT NULL COMMENT '实体店铺联系电话',
  `entity_transit` varchar(255) NOT NULL COMMENT '公交信息',
  `city` varchar(255) NOT NULL COMMENT '市',
  `district` varchar(255) NOT NULL COMMENT '区\r\n',
  `street` varchar(255) NOT NULL COMMENT '街道',
  PRIMARY KEY (`entity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `yf_shop_evaluation`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_evaluation`;
CREATE TABLE `yf_shop_evaluation` (
  `evaluation_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评价id',
  `shop_id` int(10) NOT NULL COMMENT '店铺ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '买家id',
  `order_id` varchar(50) NOT NULL COMMENT '订单ID',
  `evaluation_desccredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '描述相符评分',
  `evaluation_servicecredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '服务态度评分',
  `evaluation_deliverycredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '发货速度评分',
  `evaluation_create_time` datetime NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`evaluation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺评分表';

-- ----------------------------
--  Table structure for `yf_shop_express`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_express`;
CREATE TABLE `yf_shop_express` (
  `user_express_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '店铺物流id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `waybill_tpl_id` int(10) unsigned NOT NULL COMMENT '绑定关系-运单',
  `express_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '快递公司id',
  `user_is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认',
  `user_tpl_item` text COMMENT '显示项目--json',
  `user_tpl_top` int(255) NOT NULL DEFAULT '0' COMMENT '运单模板上偏移量，单位为毫米(mm)',
  `user_tpl_left` int(255) NOT NULL DEFAULT '0' COMMENT '运单模板左偏移量，单位为毫米(mm)',
  PRIMARY KEY (`user_express_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='默认物流公司表';

-- ----------------------------
--  Table structure for `yf_shop_extend`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_extend`;
CREATE TABLE `yf_shop_extend` (
  `shop_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='扩展表';

-- ----------------------------
--  Table structure for `yf_shop_goods_cat`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_goods_cat`;
CREATE TABLE `yf_shop_goods_cat` (
  `shop_goods_cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_goods_cat_name` varchar(50) NOT NULL,
  `shop_id` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `shop_goods_cat_displayorder` smallint(3) NOT NULL DEFAULT '0',
  `shop_goods_cat_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shop_goods_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺商品分类表';

-- ----------------------------
--  Table structure for `yf_shop_grade`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_grade`;
CREATE TABLE `yf_shop_grade` (
  `shop_grade_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺等级id',
  `shop_grade_name` varchar(50) NOT NULL,
  `shop_grade_fee` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '收费标准-收费标准，单：元/年，必须为数字，在会员开通或升级店铺时将显示在前台',
  `shop_grade_desc` varchar(255) NOT NULL COMMENT '申请说明',
  `shop_grade_goods_limit` mediumint(8) NOT NULL DEFAULT '0' COMMENT '可发布商品数 0:无限制',
  `shop_grade_album_limit` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '可上传图片数',
  `shop_grade_template` varchar(50) NOT NULL COMMENT '店铺可选模板',
  `shop_grade_function_id` varchar(50) NOT NULL COMMENT '可用附加功能-function_editor_multimedia',
  `shop_grade_sort` mediumint(8) NOT NULL DEFAULT '0' COMMENT '级别-数值越大表明级别越高',
  PRIMARY KEY (`shop_grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺等级表';

-- ----------------------------
--  Table structure for `yf_shop_help`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_help`;
CREATE TABLE `yf_shop_help` (
  `shop_help_id` int(10) NOT NULL,
  `help_sort` tinyint(1) unsigned DEFAULT '255' COMMENT '排序',
  `help_title` varchar(100) NOT NULL COMMENT '标题',
  `help_info` text COMMENT '帮助内容',
  `help_url` varchar(100) DEFAULT '' COMMENT '跳转链接',
  `update_time` date NOT NULL COMMENT '更新时间',
  `page_show` tinyint(1) unsigned DEFAULT '1' COMMENT '页面类型:1为店铺,2为会员,默认为1',
  PRIMARY KEY (`shop_help_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yf_shop_help`
-- ----------------------------
BEGIN;
INSERT INTO `yf_shop_help` VALUES ('96', '2', '招商方向', '<p style=\"text-align:left;\"><strong>2014年开放平台招商方向</strong> </p><p><br /></p><h1>\r\n	1.    品牌</h1><p><br /></p><p>       国际国内知名品牌<br /></p><p>\r\n	       开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗</p><p><br /></p><p>\r\n	舰店入驻，请参见《2014年开放平台重点招募品牌》。</p><h1><br /></h1><h1>\r\n	2.    货品</h1><p>       <br />       能够满足用户群体优质、有特色的货品。<br />       根据类目结构细分的货品配置。类目规划详见《2014年开放平台类目一览表》。<br /></p><h1>\r\n	3.   垂直电商</h1><p><br /></p><p>\r\n	      开放平台欢迎垂直类电商入驻。开放平台愿意和专业的垂直电商企业分享其优质用户群体，</p><p><br /></p><p>\r\n	并且欢迎垂直电商为用户提供该领域专业的货品及服务</p><p> </p><p>班班帅帅哒大大</p><p><br /></p><p><br /></p>', '', '2016-08-18', '1'), ('97', '2', '招商标准', '<p><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	本标准适用于除虚拟业务（包括但不限于旅游、酒店、票务、充值、彩票）外的平台开放平台所有卖家。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第一章 入驻</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.1    平台开放平台暂未授权任何机构进行代理招商服务，入驻申请流程及相关的收费说明均以平台开放平台官方招商页面为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.2    平台开放平台有权根据包括但不限于品牌需求、公司经营状况、服务水平等其他因素退回卖家申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.3    平台开放平台有权在申请入驻及后续经营阶段要求卖家提供其他资质。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.4    平台开放平台将结合各行业发展动态、国家相关规定及消费者购买需求，不定期更新招商标准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5    卖家必须如实提供资料和信息：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.1 请务必确保申请入驻及后续经营阶段提供的相关资质和信息的真实性、完整性、有效性（若卖家提供的相关资质为第三方提供，包括但不限于商标注册证、授权书\r\n等，请务必先行核实文件的真实有效完整性），一旦发现虚假资质或信息的，平台开放平台将不再与卖家进行合作并有权根据平台开放平台规则及与卖家签署的相关 协议之约定进行处理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.2  卖家应如实提供其店铺运营的主体及相关信息，包括但不限于店铺实际经营主体、代理运营公司等信息；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.3  平台开放平台关于卖家信息和资料变更有相关规定的从其规定，但卖家如变更1.5.2项所列信息，应提前十日书面告知平台；如未提前告知平台，平台将根据平台开放平台规则进行处理。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.6    平台开放平台暂不接受个体工商户的入驻申请，卖家须为正式注册企业，亦暂时不接受非中国大陆注册企业的入驻申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.7    平台开放平台暂不接受未取得国家商标总局颁发的商标注册证或商标受理通知书的品牌开店申请，亦不接受纯图形类商标的入驻申请。卖家提供商标受理通知书（TM状态商标）的，注册申请时间须满六个月。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第二章 平台店铺类型及相关要求</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1     旗舰店，卖家以自有品牌（商标为R或TM状态），或由权利人出具的在平台开放平台开设品牌旗舰店的授权文件（授权文件中应明确排他性、不可撤销性），入驻平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.1  旗舰店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营一个自有品牌（商标为R或TM状态）商品入驻平台开放平台的卖家旗舰店，（自有品牌是指商标权利归卖家所有，自有品牌的子品牌可以放入旗舰店，主、子品牌的商标权利人应为同一实际控制人）；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营已获得明确排他性授权的一个品牌商品入驻平台开放平台的卖家旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	卖场型品牌（服务类商标）商标权人开设的旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.2  开店主体必须是品牌（商标）权利人或持有权利人出具的开设平台开放平台旗舰店排他性授权文件的被授权企业。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2     专卖店，卖家持他人品牌（商标为R或TM状态）授权文件在平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.1  专卖店类型：经营一个或多个授权品牌商品（多个授权品牌的商标权人应为同一实际控制人）但未获得旗舰店排他授权入驻平台开放平台的卖家专卖店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.2   品牌（商标）权利人出具的授权文件不应有地域限制。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3     专营店，经营平台开放平台相同一级经营类目下两个及以上他人或自有品牌（商标为R或TM状态）商品的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3.1  专营店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下经营两个及以上他人品牌商品入驻平台开放平台的卖家专营店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下既经营他人品牌商品又经营自有品牌商品入驻平台开放平台的卖家专营店。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.4     各类型店铺命名详细说明，请见《平台开放平台卖家店铺命名规则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第三章 平台申请入驻资质标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	3.1    平台开放平台申请入驻资质标准详见《平台开放平台招商资质标准细则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第四章 开店入驻限制</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1    品牌入驻限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.1 与平台平台已有的自有品牌、频道、业务、类目等相同或相似名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.2  包含行业名称或通用名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.3  包含知名人士、地名的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.4  与知名品牌相同或近似的品牌。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.2     经营类目限制，卖家开店所经营的类目应当符合平台开放平台的相关标准，详细请参考《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3同一主体入驻的店铺限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.1  单个店铺只可对应一种经营模式。各经营模式内容请参考与卖家签署的对应合同；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.2  同一主体开设若干店铺的，经营模式总计不得超过两种，且须在开展第二种经营模式时提前10日向平台进行书面申请；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.3  商品重合度：要求店铺间经营的品牌及商品不得重复，4.3.2项下经过平台审批的店铺不受此项约束。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4     同一主体重新入驻平台开放平台限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.1  严重违规、资质造假被平台清退的，永久限制入驻；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.2  若卖家一自然年内主动退出2次，则自最后一次完成退出之日起12个月内限制入驻。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.5     续签限制：须在每年3月1日18时之前完成续签申请的提交，每年3月20日18时之前完成平台使用费的缴纳，如果上一年及下一年资费及资料未补足，平台将在每年3月31日24时终止店铺服务并下架商品。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第五章 平台开放平台保证金/平台使用费/费率标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1     保证金：卖家向平台缴纳的用以保证店铺规范运营及对商品和服务质量进行担保的金额。当卖家发生违约、违规行为时，平台可以依照与卖家签署的协议中相关约定及平台开放平台规则扣除相应金额的保证金作为违约金或给予买家的赔偿。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.1  保证金的补足、退还、扣除等依据卖家签署的相关协议及平台开放平台规则约定办理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.2  平台开放平台各经营类目对应的保证金标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2     平台使用费：卖家依照与平台签署的相关协议使用平台开放平台各项服务时缴纳的固定技术服务费用。平台开放平台各经营类目对应的平台使用费标准详见《平台开放平台经营类目资费一览表》。续签卖家的续展服务期间对应平台使用费须在每年3月20日18时前一次性缴纳；新签卖家须在申请入驻获得批准时一次性缴纳相应服务期间的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1   平台使用费结算：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.1卖家主动要求停止店铺服务的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.2卖家因违规行为或资质造假被清退的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.3每个店铺的平台使用费依据相应的服务期计算并缴纳。服务开通之日在每月的1日至15日（含）间的，开通当月按一个月收取平台使用费，服务开通之日在每月的16日（含）至月底最后一日间的，开通当月不收取平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.4拥有独立店铺ID的为一个店铺，若卖家根据经营情况须开通多个店铺的，须按照店铺数量缴纳相应的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.3     费率：卖家根据经营类目在达成每一单交易时按比例（该比例在与卖家签署的相关协议中称为“技术服务费费率”或“毛利保证率”）向平台缴纳的费用。平台开放平台各经营模式各经营类目对应的费率标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第六章 店铺服务期</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.1     卖家每个店铺的第一个服务期自服务开通之日起至最先到达的3月31日止，第二个服务期自4月1日起至次年3月31日止，第三个、第四个……服务期以此类推，以一年为周期，除非店铺或与卖家签署的相关协议提前终止。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.2     卖家每个店铺的服务开通之日以平台通知或系统记录的时间为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.3     卖家应在店铺每个服务期届满前30日向甲方提出续展的申请，缴纳续展服务期的平台使用费和提交其经营所需的全部有效资质，并经平台审核。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.4     卖家未提出续展申请或申请未通过平台审核的，自店铺服务期满之日起，平台开放平台将不再向卖家提供该店铺的任何服务。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	发布日期：2014年11月19日</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	生效日期：2014年1月1日</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><a></a><strong>平台开放平台招商资质标准细则</strong> </p><p class=\"MsoNormal\"> </p><p><br /></p>', '', '2016-07-17', '1'), ('98', '3', '资质要求', '<p><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><a></a><strong>平台开放平台招商资质标准细则</strong> \r\n	</p><p class=\"MsoNormal\"> 	</p><p><img src=\"../data/upload/shop/article/help_store_04526250486031950.jpg\" alt=\"image\" /><img src=\"../data/upload/shop/article/help_store_04526250486031950.jpg\" alt=\"image\" /><img src=\"../data/upload/shop/article/help_store_04526250471329237.jpg\" alt=\"image\" /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	本标准适用于除虚拟业务（包括但不限于旅游、酒店、票务、充值、彩票）外的平台开放平台所有卖家。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第一章 入驻</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.1    平台开放平台暂未授权任何机构进行代理招商服务，入驻申请流程及相关的收费说明均以平台开放平台官方招商页面为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.2    平台开放平台有权根据包括但不限于品牌需求、公司经营状况、服务水平等其他因素退回卖家申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.3    平台开放平台有权在申请入驻及后续经营阶段要求卖家提供其他资质。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.4    平台开放平台将结合各行业发展动态、国家相关规定及消费者购买需求，不定期更新招商标准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5    卖家必须如实提供资料和信息：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.1 请务必确保申请入驻及后续经营阶段提供的相关资质和信息的真实性、完整性、有效性（若卖家提供的相关资质为第三方提供，包括但不限于商标注册证、授权书\r\n等，请务必先行核实文件的真实有效完整性），一旦发现虚假资质或信息的，平台开放平台将不再与卖家进行合作并有权根据平台开放平台规则及与卖家签署的相关 协议之约定进行处理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.2  卖家应如实提供其店铺运营的主体及相关信息，包括但不限于店铺实际经营主体、代理运营公司等信息；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.5.3  平台开放平台关于卖家信息和资料变更有相关规定的从其规定，但卖家如变更1.5.2项所列信息，应提前十日书面告知平台；如未提前告知平台，平台将根据平台开放平台规则进行处理。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.6    平台开放平台暂不接受个体工商户的入驻申请，卖家须为正式注册企业，亦暂时不接受非中国大陆注册企业的入驻申请。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	1.7    平台开放平台暂不接受未取得国家商标总局颁发的商标注册证或商标受理通知书的品牌开店申请，亦不接受纯图形类商标的入驻申请。卖家提供商标受理通知书（TM状态商标）的，注册申请时间须满六个月。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第二章 平台店铺类型及相关要求</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1     旗舰店，卖家以自有品牌（商标为R或TM状态），或由权利人出具的在平台开放平台开设品牌旗舰店的授权文件（授权文件中应明确排他性、不可撤销性），入驻平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.1  旗舰店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营一个自有品牌（商标为R或TM状态）商品入驻平台开放平台的卖家旗舰店，（自有品牌是指商标权利归卖家所有，自有品牌的子品牌可以放入旗舰店，主、子品牌的商标权利人应为同一实际控制人）；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	经营已获得明确排他性授权的一个品牌商品入驻平台开放平台的卖家旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	卖场型品牌（服务类商标）商标权人开设的旗舰店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.1.2  开店主体必须是品牌（商标）权利人或持有权利人出具的开设平台开放平台旗舰店排他性授权文件的被授权企业。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2     专卖店，卖家持他人品牌（商标为R或TM状态）授权文件在平台开放平台开设的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.1  专卖店类型：经营一个或多个授权品牌商品（多个授权品牌的商标权人应为同一实际控制人）但未获得旗舰店排他授权入驻平台开放平台的卖家专卖店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.2.2   品牌（商标）权利人出具的授权文件不应有地域限制。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3     专营店，经营平台开放平台相同一级经营类目下两个及以上他人或自有品牌（商标为R或TM状态）商品的店铺。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.3.1  专营店，可以有以下几种情形：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下经营两个及以上他人品牌商品入驻平台开放平台的卖家专营店；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	相同一级类目下既经营他人品牌商品又经营自有品牌商品入驻平台开放平台的卖家专营店。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	2.4     各类型店铺命名详细说明，请见《平台开放平台卖家店铺命名规则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第三章 平台申请入驻资质标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	3.1    平台开放平台申请入驻资质标准详见《平台开放平台招商资质标准细则》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第四章 开店入驻限制</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1    品牌入驻限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.1 与平台平台已有的自有品牌、频道、业务、类目等相同或相似名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.2  包含行业名称或通用名称的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.3  包含知名人士、地名的品牌；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.1.4  与知名品牌相同或近似的品牌。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.2     经营类目限制，卖家开店所经营的类目应当符合平台开放平台的相关标准，详细请参考《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3同一主体入驻的店铺限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.1  单个店铺只可对应一种经营模式。各经营模式内容请参考与卖家签署的对应合同；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.2  同一主体开设若干店铺的，经营模式总计不得超过两种，且须在开展第二种经营模式时提前10日向平台进行书面申请；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.3.3  商品重合度：要求店铺间经营的品牌及商品不得重复，4.3.2项下经过平台审批的店铺不受此项约束。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4     同一主体重新入驻平台开放平台限制：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.1  严重违规、资质造假被平台清退的，永久限制入驻；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.4.2  若卖家一自然年内主动退出2次，则自最后一次完成退出之日起12个月内限制入驻。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	4.5     续签限制：须在每年3月1日18时之前完成续签申请的提交，每年3月20日18时之前完成平台使用费的缴纳，如果上一年及下一年资费及资料未补足，平台将在每年3月31日24时终止店铺服务并下架商品。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第五章 平台开放平台保证金/平台使用费/费率标准</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1     保证金：卖家向平台缴纳的用以保证店铺规范运营及对商品和服务质量进行担保的金额。当卖家发生违约、违规行为时，平台可以依照与卖家签署的协议中相关约定及平台开放平台规则扣除相应金额的保证金作为违约金或给予买家的赔偿。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.1  保证金的补足、退还、扣除等依据卖家签署的相关协议及平台开放平台规则约定办理；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.1.2  平台开放平台各经营类目对应的保证金标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2     平台使用费：卖家依照与平台签署的相关协议使用平台开放平台各项服务时缴纳的固定技术服务费用。平台开放平台各经营类目对应的平台使用费标准详见《平台开放平台经营类目资费一览表》。续签卖家的续展服务期间对应平台使用费须在每年3月20日18时前一次性缴纳；新签卖家须在申请入驻获得批准时一次性缴纳相应服务期间的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1   平台使用费结算：</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.1卖家主动要求停止店铺服务的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.2卖家因违规行为或资质造假被清退的不返还平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.3每个店铺的平台使用费依据相应的服务期计算并缴纳。服务开通之日在每月的1日至15日（含）间的，开通当月按一个月收取平台使用费，服务开通之日在每月的16日（含）至月底最后一日间的，开通当月不收取平台使用费；</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.2.1.4拥有独立店铺ID的为一个店铺，若卖家根据经营情况须开通多个店铺的，须按照店铺数量缴纳相应的平台使用费。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	5.3     费率：卖家根据经营类目在达成每一单交易时按比例（该比例在与卖家签署的相关协议中称为“技术服务费费率”或“毛利保证率”）向平台缴纳的费用。平台开放平台各经营模式各经营类目对应的费率标准详见《平台开放平台经营类目资费一览表》。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\"><strong>第六章 店铺服务期</strong> </p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.1     卖家每个店铺的第一个服务期自服务开通之日起至最先到达的3月31日止，第二个服务期自4月1日起至次年3月31日止，第三个、第四个……服务期以此类推，以一年为周期，除非店铺或与卖家签署的相关协议提前终止。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.2     卖家每个店铺的服务开通之日以平台通知或系统记录的时间为准。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.3     卖家应在店铺每个服务期届满前30日向甲方提出续展的申请，缴纳续展服务期的平台使用费和提交其经营所需的全部有效资质，并经平台审核。</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	6.4     卖家未提出续展申请或申请未通过平台审核的，自店铺服务期满之日起，平台开放平台将不再向卖家提供该店铺的任何服务。</p><p class=\"MsoNormal\" style=\"text-align:left;\"><br /></p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	发布日期：2014年11月19日</p><p class=\"MsoNormal\" style=\"text-align:left;\">\r\n	生效日期：2014年1月1日</p><p><br /></p>', '', '2016-07-19', '1'), ('99', '4', '资费标准', '<br />\r\n<p>\r\n	</p><h3 class=\"help_tit\">\r\n		<strong>2014年开放平台重点招募品牌</strong> \r\n	</h3>\r\n	<div class=\"help_box\">\r\n		<p>\r\n			<img src=\"../data/upload/shop/editor/20140505170523_59415.jpg\" alt=\"\" /> \r\n		</p>\r\n	</div>\r\n\r\n<p>\r\n	<br />\r\n</p>\r\n<div class=\"help_box\">\r\n	<p>\r\n		<br />\r\n	</p>\r\n	<p>\r\n		发布日期：2014年04月20日&nbsp;\r\n	</p>\r\n	<p>\r\n		修订日期：2014年05月01日\r\n	</p>\r\n</div>', '', '2016-07-13', '1'), ('100', '4', '入驻协议', '<p><br /></p><p><br /></p><h3 class=\"help_tit\"><strong>2014年开放平台重点招募品牌</strong> \r\n	</h3><p><img src=\"../data/upload/shop/editor/20140505170523_59415.jpg\" alt=\"\" /></p><p><br /></p><p><br /></p><p>\r\n		发布日期：2014年04月20日 	</p><p>\r\n		修订日期：2014年05月01日	</p><p>99	4	资费标准	<br /></p><p><br /></p><h3 class=\"help_tit\"><strong>2014年开放平台重点招募品牌</strong> \r\n	</h3><p><img src=\"../data/upload/shop/editor/20140505170523_59415.jpg\" alt=\"\" /></p><p><br /></p><p><br /></p><p>\r\n		发布日期：2014年04月20日 	</p><p>\r\n		修订日期：2014年05月01日	</p><p>		2016-07-13	1</p>', '', '2016-07-27', '2');
COMMIT;

-- ----------------------------
--  Table structure for `yf_shop_nav`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_nav`;
CREATE TABLE `yf_shop_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '导航ID',
  `title` varchar(50) NOT NULL COMMENT '导航名称',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '卖家店铺ID',
  `detail` text COMMENT '导航内容',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '导航是否显示',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `url` varchar(255) DEFAULT NULL COMMENT '店铺导航的外链URL',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '店铺导航外链是否在新窗口打开：0不开新窗口1开新窗口，默认是0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卖家店铺导航信息表';

-- ----------------------------
--  Table structure for `yf_shop_points_log`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_points_log`;
CREATE TABLE `yf_shop_points_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL COMMENT '    店铺id             ',
  `shop_name` text NOT NULL COMMENT '店铺名称',
  `points` int(10) unsigned NOT NULL COMMENT '积分',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `yf_shop_renewal`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_renewal`;
CREATE TABLE `yf_shop_renewal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员id',
  `member_name` varchar(50) NOT NULL COMMENT '会员名称(不用存废弃)',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `shop_grade_id` int(10) unsigned NOT NULL COMMENT '店铺等级id',
  `shop_grade_name` varchar(50) NOT NULL COMMENT '店铺等级名称',
  `shop_grade_fee` decimal(10,2) NOT NULL COMMENT '店铺等级费用',
  `renew_time` int(10) unsigned NOT NULL COMMENT '续费时长',
  `renew_cost` decimal(10,2) NOT NULL COMMENT '续费总费用',
  `create_time` datetime NOT NULL COMMENT '申请时间',
  `start_time` datetime NOT NULL COMMENT '有效期开始时间',
  `end_time` datetime NOT NULL COMMENT '有效期结束时间',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `admin_id` int(10) unsigned NOT NULL COMMENT '管理员id',
  `admin_name` varchar(50) NOT NULL COMMENT '管理员名称',
  `desc` varchar(100) NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='续费申请表\r\n';

-- ----------------------------
--  Table structure for `yf_shop_shipping_address`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_shipping_address`;
CREATE TABLE `yf_shop_shipping_address` (
  `shipping_address_id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL COMMENT '所属店铺',
  `shipping_address_contact` varchar(50) NOT NULL COMMENT '联系人',
  `shipping_address_province_id` int(10) NOT NULL COMMENT '省份ID',
  `shipping_address_city_id` int(10) NOT NULL COMMENT '城市ID',
  `shipping_address_area_id` int(10) NOT NULL COMMENT '区县ID',
  `shipping_address_area` varchar(255) NOT NULL COMMENT '所在地区-字符串组合',
  `shipping_address_address` varchar(255) NOT NULL COMMENT '街道地址-不必重复填写地区',
  `shipping_address_phone` varchar(20) NOT NULL COMMENT '联系电话',
  `shipping_address_company` varchar(30) NOT NULL COMMENT '公司',
  `shipping_address_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认地址',
  `shipping_address_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '时间',
  PRIMARY KEY (`shipping_address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='发货地址表';

-- ----------------------------
--  Table structure for `yf_shop_supplier`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_supplier`;
CREATE TABLE `yf_shop_supplier` (
  `supplier_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '供货商id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `supplier_name` varchar(50) NOT NULL COMMENT '供货商名称',
  `contacts` varchar(50) NOT NULL COMMENT '联系人',
  `contacts_tel` varchar(12) NOT NULL COMMENT '联系电话',
  `remarks` text NOT NULL COMMENT '备注信息',
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供货商表\r\n';

-- ----------------------------
--  Table structure for `yf_shop_template`
-- ----------------------------
DROP TABLE IF EXISTS `yf_shop_template`;
CREATE TABLE `yf_shop_template` (
  `shop_temp_name` varchar(100) NOT NULL COMMENT '店铺模板名称  --根据模板名称来找寻对应的文件',
  `shop_style_name` varchar(255) NOT NULL COMMENT '风格名称',
  `shop_temp_img` varchar(255) NOT NULL COMMENT '模板对应的图片',
  PRIMARY KEY (`shop_temp_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺模板表';

-- ----------------------------
--  Records of `yf_shop_template`
-- ----------------------------
BEGIN;
INSERT INTO `yf_shop_template` VALUES ('default', '默认店铺', 'http://127.0.0.1/newshop/yf_shop/shop/data/upload/media/plantform/image/20160613/1465785431312367.jpg'), ('style1', '宠物世界', 'http://127.0.0.1/newshop/yf_shop/shop/data/upload/media/plantform/image/20160613/1465785542652749.jpg');
COMMIT;

-- ----------------------------
--  Table structure for `yf_sub_site`
-- ----------------------------
DROP TABLE IF EXISTS `yf_sub_site`;
CREATE TABLE `yf_sub_site` (
  `sub_site_id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sub_site_parent_id` int(11) NOT NULL COMMENT '父id',
  `sub_site_name` varchar(60) NOT NULL DEFAULT '' COMMENT '分站名称',
  `sub_site_domain` varchar(20) NOT NULL DEFAULT '' COMMENT '分站域名前缀',
  `sub_site_district_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '地区id， 逗号分隔',
  `sub_site_logo` varchar(100) NOT NULL COMMENT 'logo',
  `sub_site_copyright` text NOT NULL COMMENT '版权',
  `sub_site_template` varchar(50) NOT NULL COMMENT '模板',
  `sub_site_is_open` int(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
  `sub_site_des` text NOT NULL COMMENT '描述',
  `sub_site_web_title` varchar(100) NOT NULL COMMENT 'SEO标题',
  `sub_site_web_keyword` varchar(100) NOT NULL COMMENT 'SEO关键字',
  `sub_site_web_des` varchar(100) NOT NULL COMMENT 'SEO描述',
  PRIMARY KEY (`sub_site_id`),
  KEY `domain` (`sub_site_domain`) COMMENT '(null)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='城市分站表';

-- ----------------------------
--  Table structure for `yf_test`
-- ----------------------------
DROP TABLE IF EXISTS `yf_test`;
CREATE TABLE `yf_test` (
  `test_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '数组下标',
  `test_name` varchar(255) NOT NULL COMMENT '数组值',
  PRIMARY KEY (`test_id`),
  KEY `index` (`test_id`) COMMENT '(null)'
) ENGINE=InnoDB AUTO_INCREMENT=23329 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站配置表';

-- ----------------------------
--  Records of `yf_test`
-- ----------------------------
BEGIN;
INSERT INTO `yf_test` VALUES ('23124', 'gfcg'), ('23125', 'aaa'), ('23126', ''), ('23127', ''), ('23128', ''), ('23129', ''), ('23130', ''), ('23131', ''), ('23132', ''), ('23133', ''), ('23134', ''), ('23135', ''), ('23136', ''), ('23137', ''), ('23138', ''), ('23139', ''), ('23140', ''), ('23141', ''), ('23142', ''), ('23143', ''), ('23144', ''), ('23145', ''), ('23146', ''), ('23147', ''), ('23148', ''), ('23149', ''), ('23150', ''), ('23151', ''), ('23152', ''), ('23153', ''), ('23154', ''), ('23155', ''), ('23156', ''), ('23157', ''), ('23158', ''), ('23159', ''), ('23160', ''), ('23161', ''), ('23162', ''), ('23163', ''), ('23164', ''), ('23165', ''), ('23166', ''), ('23167', ''), ('23168', ''), ('23169', ''), ('23170', ''), ('23171', ''), ('23172', ''), ('23173', ''), ('23174', ''), ('23175', ''), ('23176', ''), ('23177', ''), ('23178', ''), ('23179', ''), ('23180', ''), ('23181', ''), ('23182', ''), ('23183', ''), ('23184', ''), ('23185', ''), ('23186', ''), ('23187', ''), ('23188', ''), ('23189', ''), ('23190', ''), ('23191', ''), ('23192', ''), ('23193', ''), ('23194', ''), ('23195', ''), ('23196', ''), ('23197', ''), ('23198', ''), ('23199', ''), ('23200', ''), ('23201', ''), ('23202', ''), ('23203', ''), ('23204', ''), ('23205', ''), ('23206', ''), ('23207', ''), ('23208', ''), ('23209', ''), ('23210', ''), ('23211', ''), ('23212', ''), ('23213', ''), ('23214', ''), ('23215', ''), ('23216', ''), ('23217', ''), ('23218', ''), ('23219', ''), ('23220', ''), ('23221', ''), ('23222', ''), ('23223', ''), ('23224', ''), ('23225', ''), ('23226', ''), ('23227', ''), ('23228', ''), ('23229', ''), ('23230', ''), ('23231', ''), ('23232', ''), ('23233', ''), ('23234', ''), ('23235', ''), ('23236', ''), ('23237', ''), ('23238', ''), ('23239', ''), ('23240', ''), ('23241', ''), ('23242', ''), ('23243', ''), ('23244', ''), ('23245', ''), ('23246', ''), ('23247', ''), ('23248', ''), ('23249', ''), ('23250', ''), ('23251', ''), ('23252', ''), ('23253', ''), ('23254', ''), ('23255', ''), ('23256', ''), ('23257', ''), ('23258', ''), ('23259', ''), ('23260', ''), ('23261', ''), ('23262', ''), ('23263', ''), ('23264', ''), ('23265', ''), ('23266', ''), ('23267', ''), ('23268', ''), ('23269', ''), ('23270', ''), ('23271', ''), ('23272', ''), ('23273', ''), ('23274', ''), ('23275', ''), ('23276', ''), ('23277', ''), ('23278', ''), ('23279', ''), ('23280', ''), ('23281', ''), ('23282', ''), ('23283', ''), ('23284', ''), ('23285', ''), ('23286', ''), ('23287', ''), ('23288', ''), ('23289', ''), ('23290', ''), ('23291', ''), ('23292', ''), ('23293', ''), ('23294', ''), ('23295', ''), ('23296', ''), ('23297', ''), ('23298', ''), ('23299', ''), ('23300', ''), ('23301', ''), ('23302', ''), ('23303', ''), ('23304', ''), ('23305', ''), ('23306', ''), ('23307', ''), ('23308', ''), ('23309', ''), ('23310', ''), ('23311', ''), ('23312', ''), ('23313', ''), ('23314', ''), ('23315', ''), ('23316', ''), ('23317', ''), ('23318', ''), ('23319', ''), ('23320', ''), ('23321', ''), ('23322', ''), ('23323', ''), ('23324', ''), ('23325', ''), ('23326', ''), ('23327', ''), ('23328', '');
COMMIT;

-- ----------------------------
--  Table structure for `yf_transport_item`
-- ----------------------------
DROP TABLE IF EXISTS `yf_transport_item`;
CREATE TABLE `yf_transport_item` (
  `transport_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `transport_type_id` mediumint(8) unsigned NOT NULL COMMENT '自定义物流模板ID',
  `logistics_type` varchar(50) NOT NULL DEFAULT '' COMMENT 'EMS,平邮,快递-忽略类型，买家不是必须知道，而且可选会给卖家制造障碍。',
  `transport_item_default_num` float(3,1) NOT NULL COMMENT '默认数量',
  `transport_item_default_price` decimal(6,2) NOT NULL COMMENT '默认运费',
  `transport_item_add_num` float(3,1) NOT NULL DEFAULT '1.0' COMMENT '增加数量',
  `transport_item_add_price` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '增加运费',
  `transport_item_city` text NOT NULL COMMENT '区域城市id-需要特别处理，快速查询- 如果全国，则需要使用*来替代，提升效率',
  PRIMARY KEY (`transport_item_id`),
  KEY `temp_id` (`transport_type_id`,`logistics_type`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='自定义物流模板内容表-只处理区域及运费。';

-- ----------------------------
--  Table structure for `yf_transport_offpay_area`
-- ----------------------------
DROP TABLE IF EXISTS `yf_transport_offpay_area`;
CREATE TABLE `yf_transport_offpay_area` (
  `offpay_area_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `offpay_area_city_ids` text NOT NULL COMMENT '区域城市id-需要特别处理，快速查询-'',''分割',
  PRIMARY KEY (`offpay_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='到付区域。';

-- ----------------------------
--  Table structure for `yf_transport_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_transport_type`;
CREATE TABLE `yf_transport_type` (
  `transport_type_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '物流及售卖区域id',
  `transport_type_name` varchar(20) NOT NULL DEFAULT '' COMMENT '物流及售卖区域模板名',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `transport_type_pricing_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1按重量  2按件数    3按体积   计算价格方式-不使用',
  `transport_type_time` datetime NOT NULL COMMENT '最后编辑时间',
  `transport_type_price` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '启用固定价格后起作用',
  PRIMARY KEY (`transport_type_id`),
  KEY `user_id` (`shop_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='自定义物流运费及售卖区域类型表';

-- ----------------------------
--  Table structure for `yf_upload_album`
-- ----------------------------
DROP TABLE IF EXISTS `yf_upload_album`;
CREATE TABLE `yf_upload_album` (
  `album_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品图片Id',
  `album_name` varchar(100) NOT NULL DEFAULT '' COMMENT '商品图片地址',
  `album_cover` varchar(100) NOT NULL DEFAULT '' COMMENT '封面',
  `album_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `album_num` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '内容数量',
  `album_is_default` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '默认相册，1是，0否',
  `album_displayorder` smallint(4) NOT NULL DEFAULT '255' COMMENT '排序',
  `album_type` enum('video','other','image') NOT NULL DEFAULT 'image' COMMENT '附件册',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属用户id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户相册表';

-- ----------------------------
--  Table structure for `yf_upload_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_upload_base`;
CREATE TABLE `yf_upload_base` (
  `upload_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品图片Id',
  `album_id` bigint(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `upload_url_prefix` varchar(255) NOT NULL DEFAULT '',
  `upload_path` varchar(255) NOT NULL DEFAULT '',
  `upload_url` varchar(255) NOT NULL COMMENT '附件的url   upload_url = upload_url_prefix  + upload_path',
  `upload_thumbs` text NOT NULL COMMENT 'JSON存储其它尺寸',
  `upload_original` varchar(255) NOT NULL DEFAULT '' COMMENT '原附件',
  `upload_source` varchar(255) NOT NULL DEFAULT '' COMMENT '源头-网站抓取',
  `upload_displayorder` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `upload_type` enum('video','other','image') NOT NULL DEFAULT 'image' COMMENT 'image|video|',
  `upload_image_spec` int(10) NOT NULL DEFAULT '0' COMMENT '规格',
  `upload_size` int(10) NOT NULL COMMENT '原文件大小',
  `upload_mime_type` varchar(100) NOT NULL DEFAULT '' COMMENT '上传的附件类型',
  `upload_metadata` text NOT NULL,
  `upload_name` text NOT NULL COMMENT '附件标题',
  `upload_time` int(10) NOT NULL COMMENT '附件日期',
  PRIMARY KEY (`upload_id`),
  KEY `album_id` (`user_id`,`album_id`,`upload_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户附件表-图片、视频';

-- ----------------------------
--  Table structure for `yf_user_address`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_address`;
CREATE TABLE `yf_user_address` (
  `user_address_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '所属店铺',
  `user_address_contact` varchar(50) NOT NULL,
  `user_address_province_id` int(10) NOT NULL,
  `user_address_city_id` int(10) NOT NULL,
  `user_address_area_id` int(10) NOT NULL,
  `user_address_area` varchar(255) NOT NULL COMMENT '所在地区-字符串组合',
  `user_address_address` varchar(255) NOT NULL COMMENT '街道地址-不必重复填写地区',
  `user_address_phone` varchar(20) NOT NULL COMMENT '联系电话',
  `user_address_company` varchar(30) NOT NULL COMMENT '公司',
  `user_address_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认地址0不是1是',
  `user_address_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户地址表';

-- ----------------------------
--  Table structure for `yf_user_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_base`;
CREATE TABLE `yf_user_base` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_number` varchar(50) NOT NULL DEFAULT '' COMMENT '用户编号',
  `user_account` varchar(50) NOT NULL DEFAULT '' COMMENT '用户帐号',
  `user_passwd` char(50) NOT NULL DEFAULT '' COMMENT '密码：使用用户中心-此处废弃',
  `user_key` char(32) NOT NULL DEFAULT '' COMMENT '用户Key',
  `user_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被封禁，0：未封禁，1：封禁',
  `user_login_times` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '登录次数',
  `user_login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `user_login_ip` varchar(30) NOT NULL COMMENT '最后登录ip',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_account` (`user_account`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户基础信息表';

-- ----------------------------
--  Table structure for `yf_user_buy`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_buy`;
CREATE TABLE `yf_user_buy` (
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `common_id` int(10) NOT NULL COMMENT '商品commonid',
  `buy_num` int(10) DEFAULT '0' COMMENT '用户购买数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户购买商品数量表';

-- ----------------------------
--  Table structure for `yf_user_extend`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_extend`;
CREATE TABLE `yf_user_extend` (
  `user_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Meta id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_meta_key` varchar(255) NOT NULL COMMENT '键',
  `user_meta_value` longtext NOT NULL COMMENT '值',
  `user_meta_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`user_meta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`user_meta_key`),
  CONSTRAINT `yf_user_extend_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `shop_user_base` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户数据扩展表';

-- ----------------------------
--  Table structure for `yf_user_favorites_brand`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_favorites_brand`;
CREATE TABLE `yf_user_favorites_brand` (
  `favorites_brand_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `brand_id` int(10) NOT NULL COMMENT '品牌id',
  `favorites_brand_time` datetime NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`favorites_brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='收藏品牌';

-- ----------------------------
--  Table structure for `yf_user_favorites_goods`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_favorites_goods`;
CREATE TABLE `yf_user_favorites_goods` (
  `favorites_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `favorites_goods_time` datetime NOT NULL,
  PRIMARY KEY (`favorites_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='收藏的商品';

-- ----------------------------
--  Table structure for `yf_user_favorites_shop`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_favorites_shop`;
CREATE TABLE `yf_user_favorites_shop` (
  `favorites_shop_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `shop_name` varchar(50) NOT NULL,
  `shop_logo` varchar(255) NOT NULL,
  `favorites_shop_time` datetime NOT NULL,
  PRIMARY KEY (`favorites_shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='收藏的店铺';

-- ----------------------------
--  Table structure for `yf_user_footprint`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_footprint`;
CREATE TABLE `yf_user_footprint` (
  `footprint_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `common_id` int(10) NOT NULL COMMENT '商品id',
  `footprint_time` date NOT NULL COMMENT '时间',
  PRIMARY KEY (`footprint_id`),
  KEY `user_id` (`user_id`,`common_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品访问足迹表';

-- ----------------------------
--  Table structure for `yf_user_friend`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_friend`;
CREATE TABLE `yf_user_friend` (
  `user_friend_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NOT NULL COMMENT '会员ID',
  `friend_id` int(10) NOT NULL COMMENT '朋友id = user_id',
  `friend_name` varchar(100) NOT NULL COMMENT '好友会员名称 = user_name',
  `friend_image` varchar(100) NOT NULL COMMENT '朋友头像',
  `friend_addtime` datetime NOT NULL COMMENT '添加时间',
  `friend_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关注状态 1为单方关注 2为双方关注--暂时不用',
  PRIMARY KEY (`user_friend_id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友表';

-- ----------------------------
--  Table structure for `yf_user_grade`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_grade`;
CREATE TABLE `yf_user_grade` (
  `user_grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_grade_name` varchar(50) NOT NULL,
  `user_grade_demand` int(10) NOT NULL DEFAULT '0' COMMENT '条件',
  `user_grade_treatment` text NOT NULL COMMENT '权益',
  `user_grade_blogo` varchar(255) NOT NULL COMMENT '大图',
  `user_grade_logo` varchar(255) NOT NULL COMMENT 'LOGO',
  `user_grade_valid` int(1) NOT NULL DEFAULT '0' COMMENT '有效期',
  `user_grade_sum` int(11) NOT NULL DEFAULT '0' COMMENT '年费',
  `user_grade_rate` float(3,1) NOT NULL DEFAULT '0.0' COMMENT '折扣率',
  `user_grade_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户等级表';

-- ----------------------------
--  Records of `yf_user_grade`
-- ----------------------------
BEGIN;
INSERT INTO `yf_user_grade` VALUES ('1', '注册会员', '1', '<p>1.可以享受注册会员所能购买的产品及服务</p>\r\n<p>2.享受售后服务（退货、换货、维修）运费优惠</p>', 'image/grade/icon1.png_big.png', 'http://192.168.0.88/tech12/yf_shop/image.php/shop/data/upload/media/1/1/image/20160701/1467361307454544.jpg', '0', '0', '0.0', '2016-06-21 15:51:12'), ('2', '铜牌会员', '5', '<p>2.可以享受铜牌会员所能购买的产品及服务</p>\r\n<p>3.享受售后服务（退货、换货、维修）运费优惠</p>', 'image/grade/icon2.png_big.png', 'http://127.0.0.1/yf_shop/shop/data/upload/media/plantform/image/20160613/1465806100773108.jpg', '0', '0', '99.9', '2016-06-21 15:52:13'), ('3', '银牌会员', '2000', '<p>2.可以享受银牌会员所能购买的产品及服务</p>\r\n<p>3.享受售后服务（退货、换货、维修）运费优惠</p>', 'image/grade/icon3.png_big.png', 'http://127.0.0.1/yf_shop/shop/data/upload/media/plantform/image/20160613/1465806121255866.jpg', '1', '1000', '99.8', '2016-06-06 15:52:16'), ('4', '金牌会员', '10000', '<p>2.可以享受金牌会员所能购买的产品及服务</p>\r\n<p>3.享受售后服务（退货、换货、维修）运费优惠</p>', 'image/grade/icon4.png_big.png', 'http://127.0.0.1/yf_shop/shop/data/upload/media/plantform/image/20160613/1465806126141144.jpg', '1', '4000', '98.0', '2016-06-06 15:52:20'), ('5', '钻石会员', '30000', '<p>\r\n	2.可以享受钻石会员所能购买的产品及服务\r\n</p>\r\n<p>\r\n	3.享受售后服务（退货、换货、维修）运费优惠\r\n</p>', 'image/grade/icon5.png_big.png', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469788524122976.jpg', '1', '10000', '97.0', '2016-06-06 15:52:24');
COMMIT;

-- ----------------------------
--  Table structure for `yf_user_info`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_info`;
CREATE TABLE `yf_user_info` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `user_realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `user_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `user_email` varchar(50) NOT NULL DEFAULT '' COMMENT '用户Email',
  `user_type_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户类别',
  `user_level_id` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '用户安全等级',
  `user_active_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '激活时间',
  `user_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注消息',
  `user_name` varchar(30) NOT NULL COMMENT '用户名',
  `user_sex` tinyint(1) NOT NULL DEFAULT '0',
  `user_birthday` date NOT NULL,
  `user_mobile_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机验证0没验证1验证',
  `user_email_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证0没验证1验证',
  `user_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金-废除',
  `user_freeze_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结资金-废除',
  `user_provinceid` int(11) NOT NULL,
  `user_cityid` int(11) NOT NULL,
  `user_areaid` int(11) NOT NULL,
  `user_area` varchar(255) NOT NULL,
  `user_logo` varchar(120) NOT NULL DEFAULT '',
  `user_hobby` varchar(255) NOT NULL DEFAULT '0' COMMENT '--废除',
  `user_points` int(10) NOT NULL DEFAULT '0' COMMENT '-废除',
  `user_freeze_points` int(10) NOT NULL DEFAULT '0' COMMENT '-废除',
  `user_growth` int(10) NOT NULL DEFAULT '0' COMMENT '成长值-废除',
  `user_statu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登录状态0允许登录1禁止登录',
  `user_ip` varchar(10) NOT NULL,
  `user_lastip` varchar(10) NOT NULL,
  `user_regtime` datetime NOT NULL,
  `user_logintime` datetime NOT NULL,
  `lastlogintime` datetime NOT NULL,
  `user_invite` varchar(50) NOT NULL,
  `user_grade` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户等级',
  `user_update_date` datetime NOT NULL COMMENT '更新时间',
  `user_drp_id` int(10) NOT NULL DEFAULT '0',
  `user_qq` varchar(50) NOT NULL DEFAULT '' COMMENT '用户qq',
  `user_report` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以举报商品0不可以1可以',
  `user_buy` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以购买商品0不可以1可以',
  `user_talk` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许发表言论0不可以1可以',
  `user_ww` varchar(50) NOT NULL DEFAULT '' COMMENT '阿里旺旺',
  `user_am` varchar(500) NOT NULL COMMENT '公告id',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户信息表';

-- ----------------------------
--  Table structure for `yf_user_message`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_message`;
CREATE TABLE `yf_user_message` (
  `user_message_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户消息id',
  `user_message_receive_id` int(10) NOT NULL COMMENT '接收id',
  `user_message_receive` varchar(50) NOT NULL COMMENT '接收者用户',
  `user_message_send_id` int(10) NOT NULL COMMENT '发送者id',
  `user_message_send` varchar(50) NOT NULL COMMENT '发送者',
  `user_message_content` text NOT NULL COMMENT '发送内容',
  `message_islook` tinyint(1) DEFAULT '0' COMMENT '是否读取0未1读取',
  `user_message_pid` int(10) NOT NULL COMMENT '回复消息上级id',
  `user_message_time` datetime NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`user_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';

-- ----------------------------
--  Table structure for `yf_user_privacy`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_privacy`;
CREATE TABLE `yf_user_privacy` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `user_privacy_email` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱设置0公开1好友可见2保密',
  `user_privacy_realname` tinyint(1) NOT NULL DEFAULT '0' COMMENT '真实姓名设置0公开1好友可见2保密',
  `user_privacy_sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别设置0公开1好友可见2保密',
  `user_privacy_birthday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '生日设置0公开1好友可见2保密',
  `user_privacy_area` tinyint(1) NOT NULL DEFAULT '0' COMMENT '所在地区设置0公开1好友可见2保密',
  `user_privacy_qq` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'QQ设置0公开1好友可见2保密',
  `user_privacy_ww` tinyint(1) NOT NULL DEFAULT '0' COMMENT '阿里旺旺设置0公开1好友可见2保密',
  `user_privacy_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机设置0公开1好友可见2保密',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户信息隐私设置表';

-- ----------------------------
--  Table structure for `yf_user_resource`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_resource`;
CREATE TABLE `yf_user_resource` (
  `user_id` int(10) unsigned NOT NULL,
  `user_blog` int(10) NOT NULL DEFAULT '0' COMMENT '微博数量',
  `user_friend` int(10) NOT NULL DEFAULT '0' COMMENT '好友数量',
  `user_fan` int(10) NOT NULL DEFAULT '0' COMMENT '粉丝数量',
  `user_growth` int(10) NOT NULL DEFAULT '0' COMMENT '成长值',
  `user_points` int(10) NOT NULL DEFAULT '0' COMMENT '积点',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户信息表';

-- ----------------------------
--  Table structure for `yf_user_tag`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_tag`;
CREATE TABLE `yf_user_tag` (
  `user_tag_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户标签id',
  `user_tag_sort` int(10) NOT NULL COMMENT '标签排序',
  `user_tag_name` varchar(50) NOT NULL COMMENT '标签名称',
  `user_tag_image` varchar(255) NOT NULL COMMENT '标签图片',
  `user_tag_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐0不推荐1推荐',
  `user_tag_content` text NOT NULL COMMENT '标签描述',
  `user_tag_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户标签表';

-- ----------------------------
--  Table structure for `yf_user_tag_rec`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_tag_rec`;
CREATE TABLE `yf_user_tag_rec` (
  `tag_rec_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '兴趣标签id',
  `user_tag_id` int(10) NOT NULL COMMENT '标签id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `tag_rec_time` datetime NOT NULL COMMENT '选择标签时间',
  PRIMARY KEY (`tag_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户兴趣标签表';

-- ----------------------------
--  Table structure for `yf_user_type`
-- ----------------------------
DROP TABLE IF EXISTS `yf_user_type`;
CREATE TABLE `yf_user_type` (
  `user_type_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户类别Id',
  `user_type_name` varchar(20) NOT NULL DEFAULT '' COMMENT '客户类别名称',
  `user_type_remark` varchar(50) NOT NULL DEFAULT '' COMMENT '客户类别注释',
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户分类表';

-- ----------------------------
--  Table structure for `yf_voucher_base`
-- ----------------------------
DROP TABLE IF EXISTS `yf_voucher_base`;
CREATE TABLE `yf_voucher_base` (
  `voucher_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '代金券编号',
  `voucher_code` varchar(32) NOT NULL COMMENT '代金券编码',
  `voucher_t_id` int(11) NOT NULL COMMENT '代金券模版编号',
  `voucher_title` varchar(50) NOT NULL COMMENT '代金券标题',
  `voucher_desc` varchar(255) NOT NULL COMMENT '代金券描述',
  `voucher_start_date` datetime NOT NULL COMMENT '代金券有效期开始时间',
  `voucher_end_date` datetime NOT NULL COMMENT '代金券有效期结束时间',
  `voucher_price` int(11) NOT NULL COMMENT '代金券面额',
  `voucher_limit` decimal(10,2) NOT NULL COMMENT '代金券使用时的订单限额',
  `voucher_shop_id` int(11) NOT NULL COMMENT '代金券的店铺id',
  `voucher_state` tinyint(4) NOT NULL COMMENT '代金券状态(1-未用,2-已用,3-过期,4-收回)',
  `voucher_active_date` datetime NOT NULL COMMENT '代金券发放日期',
  `voucher_type` tinyint(4) NOT NULL COMMENT '代金券类别',
  `voucher_owner_id` int(11) NOT NULL COMMENT '代金券所有者id',
  `voucher_owner_name` varchar(50) NOT NULL COMMENT '代金券所有者名称',
  `voucher_order_id` varchar(25) NOT NULL COMMENT '使用该代金券的订单编号',
  PRIMARY KEY (`voucher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代金券表';

-- ----------------------------
--  Table structure for `yf_voucher_combo`
-- ----------------------------
DROP TABLE IF EXISTS `yf_voucher_combo`;
CREATE TABLE `yf_voucher_combo` (
  `combo_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '套餐编号',
  `user_id` int(10) NOT NULL COMMENT '会员编号',
  `user_nickname` varchar(100) NOT NULL COMMENT '会员名称',
  `shop_id` int(10) NOT NULL COMMENT '店铺编号',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `combo_start_time` datetime NOT NULL COMMENT '开始时间',
  `combo_end_time` datetime NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`combo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代金券套餐表';

-- ----------------------------
--  Table structure for `yf_voucher_price`
-- ----------------------------
DROP TABLE IF EXISTS `yf_voucher_price`;
CREATE TABLE `yf_voucher_price` (
  `voucher_price_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '代金券面值编号',
  `voucher_price` int(11) NOT NULL COMMENT '代金券面值',
  `voucher_price_describe` varchar(255) NOT NULL COMMENT '代金券描述',
  `voucher_defaultpoints` int(11) DEFAULT '0' COMMENT '代金券默认的兑换所需积分，可以为0',
  PRIMARY KEY (`voucher_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代金券面额表';

-- ----------------------------
--  Table structure for `yf_voucher_template`
-- ----------------------------
DROP TABLE IF EXISTS `yf_voucher_template`;
CREATE TABLE `yf_voucher_template` (
  `voucher_t_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '代金券模版编号',
  `voucher_t_title` varchar(50) NOT NULL COMMENT '代金券模版名称',
  `voucher_t_desc` varchar(255) NOT NULL COMMENT '代金券模版描述',
  `shop_class_id` int(10) NOT NULL,
  `voucher_t_start_date` datetime NOT NULL COMMENT '代金券模版有效期开始时间',
  `voucher_t_end_date` datetime NOT NULL COMMENT '代金券模版有效期结束时间',
  `voucher_t_price` int(10) NOT NULL COMMENT '代金券模版面额',
  `voucher_t_limit` decimal(10,2) NOT NULL COMMENT '代金券使用时的订单限额',
  `shop_id` int(10) NOT NULL COMMENT '代金券模版的店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `voucher_t_creator_id` int(10) NOT NULL COMMENT '代金券模版的创建者id',
  `voucher_t_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '代金券模版状态(1-有效,2-失效)',
  `voucher_t_total` int(10) NOT NULL COMMENT '模版可发放的代金券总数',
  `voucher_t_giveout` int(10) NOT NULL COMMENT '模版已发放的代金券数量',
  `voucher_t_used` int(10) NOT NULL COMMENT '模版已经使用过的代金券',
  `voucher_t_add_date` datetime NOT NULL COMMENT '模版的创建时间',
  `voucher_t_update_date` datetime NOT NULL COMMENT '模版的最后修改时间',
  `combo_id` int(10) NOT NULL COMMENT '套餐编号',
  `voucher_t_points` int(10) NOT NULL DEFAULT '0' COMMENT '兑换所需积分',
  `voucher_t_eachlimit` int(10) NOT NULL DEFAULT '1' COMMENT '每人限领张数',
  `voucher_t_styleimg` varchar(200) NOT NULL COMMENT '样式模版图片',
  `voucher_t_customimg` varchar(200) NOT NULL COMMENT '自定义代金券模板图片',
  `voucher_t_access_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '代金券领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取',
  `voucher_t_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐状态，0-为不推荐，1-推荐',
  `voucher_t_user_grade_limit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '领取代金券的用户等级限制',
  PRIMARY KEY (`voucher_t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代金券模版表';

-- ----------------------------
--  Table structure for `yf_waybill_tpl`
-- ----------------------------
DROP TABLE IF EXISTS `yf_waybill_tpl`;
CREATE TABLE `yf_waybill_tpl` (
  `waybill_tpl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `waybill_tpl_name` varchar(20) NOT NULL COMMENT '模板名称',
  `user_id` int(10) unsigned NOT NULL COMMENT '所属用户',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `express_id` mediumint(8) NOT NULL COMMENT '物流公司id',
  `waybill_tpl_width` int(11) NOT NULL DEFAULT '0' COMMENT '运单宽度，单位为毫米(mm)',
  `waybill_tpl_height` int(11) NOT NULL DEFAULT '0' COMMENT '运单高度，单位为毫米(mm)',
  `waybill_tpl_top` int(255) NOT NULL DEFAULT '0' COMMENT '运单模板上偏移量，单位为毫米(mm)',
  `waybill_tpl_left` int(255) NOT NULL DEFAULT '0' COMMENT '运单模板左偏移量，单位为毫米(mm)',
  `waybill_tpl_image` varchar(200) NOT NULL DEFAULT '' COMMENT '模板图片-请上传扫描好的运单图片，图片尺寸必须与快递单实际尺寸相符',
  `waybill_tpl_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用0否1是',
  `waybill_tpl_build-in` tinyint(1) NOT NULL DEFAULT '1' COMMENT '系统内置0否1是',
  `waybill_tpl_item` text COMMENT '显示项目--json',
  PRIMARY KEY (`waybill_tpl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='运单模板';

-- ----------------------------
--  Table structure for `yf_web_config`
-- ----------------------------
DROP TABLE IF EXISTS `yf_web_config`;
CREATE TABLE `yf_web_config` (
  `config_key` varchar(50) NOT NULL COMMENT '数组下标',
  `config_value` text NOT NULL COMMENT '数组值',
  `config_type` varchar(50) NOT NULL,
  `config_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `config_comment` text NOT NULL,
  `config_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`config_key`),
  KEY `index` (`config_key`,`config_type`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='网站配置表';

-- ----------------------------
--  Records of `yf_web_config`
-- ----------------------------
BEGIN;
INSERT INTO `yf_web_config` VALUES ('', '', '', '1', '', 'string'), ('23123', 'faf', 'msg_tpl', '1', 'fasf', 'string'), ('article_description', '5', 'seo', '1', '', 'string'), ('article_description_content', '5', 'seo', '1', '', 'string'), ('article_keyword', '软沙发', 'seo', '1', '', 'string'), ('article_keyword_content', '7', 'seo', '1', '', 'string'), ('article_title', '{sitename}-文章{name}', 'seo', '1', '', 'string'), ('article_title_content', '7{sitename}', 'seo', '1', '', 'string'), ('authenticate', 'faf', 'msg_tpl', '1', '身份验证通知', 'string'), ('baseurl', 'demo.bbc-builder.com', 'main', '1', '', 'string'), ('bind_email', 'faf', 'msg_tpl', '1', '邮箱验证通知', 'string'), ('body_skin', 'image/default/bg.jpg', 'main', '1', '', 'string'), ('brand_description', '2313', 'seo', '1', '', 'string'), ('brand_description_content', 'trsegt', 'seo', '1', '', 'string'), ('brand_keyword', '23123', 'seo', '1', '', 'string'), ('brand_keyword_content', '123123', 'seo', '1', '', 'string'), ('brand_title', 'j{sitename}23123', 'seo', '1', '', 'string'), ('brand_title_content', 'gfnjgmjn', 'seo', '1', '', 'string'), ('cacheTime', '1000', 'main', '1', '', 'string'), ('captcha_status_goodsqa', '1', 'dumps', '1', '', 'number'), ('captcha_status_login', '1', 'dump', '1', '', 'number'), ('captcha_status_register', '1', 'dump', '1', '', 'number'), ('category_description', '分类', 'seo', '1', '', 'string'), ('category_keyword', '分类', 'seo', '1', '', 'string'), ('category_title', '商品分类{name}{sitename}', 'seo', '1', '', 'string'), ('closecon', '', 'main', '1', '', 'string'), ('closed_reason', '11111', 'site', '1', '', 'string'), ('closetype', '0', 'main', '1', '', 'string'), ('complain_datetime', '2', 'complain', '1', '', 'string'), ('consult_header_text', '<p>11111111111111</p>', 'consult', '1', '', 'string'), ('copyright', 'BBCBuilder版权所有,正版购买地址:  <a href=\"http://www.bbc-builder.com\">http://www.bbc-builder.com</a>  \r\n<br />Powered by BBCbuilder V2.6.1\r\n', 'site', '1', '', 'string'), ('date_format', 'Y-m-d', 'site', '1', '', 'string'), ('description', '网上超市，最经济实惠的网上购物商城，用鼠标逛超市，不用排队，方便实惠送上门，网上购物新生活。', 'seo', '1', '', 'string'), ('domaincity', '0', 'main', '1', '', 'string'), ('domain_length', '3-12', 'domain', '1', '', 'string'), ('domain_modify_frequency', '1', 'domain', '1', '', 'number'), ('drp_is_open', '0', 'main', '1', '', 'string'), ('email', '250314853@qq.com', 'main', '1', '', 'string'), ('email_addr', 'rd02@yuanfeng021.com', 'email', '1', '', 'string'), ('email_host', 'smtp.exmail.qq.com', 'email', '1', '', 'string'), ('email_id', 'rd02', 'email', '1', '', 'string'), ('email_pass', 'huangxinze1', 'email', '1', '', 'string'), ('email_port', '465', 'email', '1', '', 'number'), ('enable_gzip', '0', 'main', '1', '', 'string'), ('enable_tranl', '1', 'main', '1', '', 'string'), ('fafaf', '身份验证通知', 'msg_tpl', '1', '【{$site_name}】您于{$send_time}提交账户安全验证，验证码是：{$verify_code}。', 'string'), ('goods_verify_flag', '1', 'goods', '1', '//商品是否需要审核', 'string'), ('grade_evaluate', '50', 'grade', '1', '订单评论获取成长值', 'number'), ('grade_login', '12', 'grade', '1', '登陆获取成长值', 'number'), ('grade_order', '800', 'grade', '1', '订单评论获取成长值上限', 'number'), ('grade_recharge', '100', 'grade', '1', '订单每多少获取多少成长值', 'number'), ('groupbuy_allow', '1', 'promotion', '1', '是否开启团购', 'number'), ('groupbuy_price', '100', 'groupbuy', '1', '', 'number'), ('groupbuy_review_day', '0', 'groupbuy', '1', '', 'number'), ('guest_comment', '1', 'dumps', '1', '', 'string'), ('hot_commen', '31,42,47,34,35,25,44,26,27,46', 'home', '1', '', 'string'), ('hot_sell', '42,37,28,41,30,31,42,47,34,35', 'home', '1', '', 'string'), ('icp_number', '5.4435234534253', 'site', '1', '', 'string'), ('image_allow_ext', 'gif,jpg,jpeg,bmp,png,swf', 'upload', '1', '图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 \",\" 隔开。', 'string'), ('image_max_filesize', '2000', 'upload', '1', '图片文件大小', 'number'), ('image_storage_type', '', 'upload', '1', '图片存放类型-程序内置较优方式', 'string'), ('index_catid', '1000,1002,1001,1003,1005', 'home', '1', '', 'string'), ('index_liandong1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/plantform/image/20160718/1468825145755207.png', 'index_liandong', '1', '首页联动小图1', 'string'), ('index_liandong2_image', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470221116664496.jpg!236x236.jpg', 'index_liandong', '1', '首页联动小图2', 'string'), ('index_liandong_url1', 'http：shouye.com1', 'index_liandong', '1', '首页联动小图url1', 'string'), ('index_liandong_url2', 'http://localhost/shop/yf_shop/index.php', 'index_liandong', '1', '首页联动小图url2', 'string'), ('index_live_link1', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url1', 'string'), ('index_live_link2', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url2', 'string'), ('index_live_link3', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url3', 'string'), ('index_live_link4', 'http://localhost/shop/yf_shop/index.php', 'index_slider', '1', '首页轮播url4', 'string'), ('index_live_link5', 'http://localhost/shop/yf_shop/index.php11', 'index_slider', '1', '首页轮播url5', 'string'), ('index_newsid', '1', 'home', '1', '', 'string'), ('index_slider1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826459105453.png', 'index_slider', '1', '首页轮播1', 'string'), ('index_slider2_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826471680590.jpg', 'index_slider', '1', '首页轮播2', 'string'), ('index_slider3_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826478677750.jpg', 'index_slider', '1', '首页轮播3', 'string'), ('index_slider4_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826482121676.png', 'index_slider', '1', '首页轮播4', 'string'), ('index_slider5_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173662/66/image/20160718/1468826488485484.jpg', 'index_slider', '1', '首页轮播5', 'string'), ('is_modify', '1', 'domain', '1', '', 'number'), ('join_live_link1', 'http://localhost/shop/yf_shop/index.php', 'join_slider', '1', '入驻轮播url1', 'string'), ('join_live_link2', 'http://localhost/shop/yf_shop/index.php', 'join_slider', '1', '入驻轮播url2', 'string'), ('join_slider1_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025199534626.png', 'join_slider', '1', '入驻轮播1', 'string'), ('join_slider2_image', 'http://127.0.0.1/newshop/yf_shop/image.php/shop/data/upload/media/173573/80/image/20160731/1470025254850480.png', 'join_slider', '1', '入驻轮播2', 'string'), ('join_tip', 'fdsafasdaddsadad', 'join_slider', '1', '贴心提示', 'string'), ('jsd', 'JSD-', 'bill_format', '1', '//结算单', 'string'), ('keyword', '网上超市，网上商城，网络购物，进口食品，美容护理，母婴玩具，厨房清洁用品，家用电器，手机数码，电脑软件办公用品，家居生活，服饰内衣，营养保健，钟表珠宝，饰品箱包，汽车生活，图书音像，礼品卡，药品，医疗器械，隐形眼镜等，1号店。', 'seo', '1', '', 'string'), ('keywords', '雷山兄弟扛年货回家，年货下单就到家', 'main', '1', '', 'string'), ('kuaidi100_app_id', 'kuaidi100fadfda', 'kuaidi100', '1', '', 'string'), ('kuaidi100_app_key', 'kuaidi100_statufaf', 'kuaidi100', '1', '', 'string'), ('kuaidi100_status', '1', 'kuaidi100', '1', '', 'string'), ('kuaidiniao_app_key', 'kuaidiniaofafdaf', 'kuaidiniao', '1', '', 'string'), ('kuaidiniao_express', '[\"QFKD\",\"ZTO\",\"DBL\",\"ZENY\"]', 'kuaidiniao', '1', '', 'json'), ('kuaidiniao_e_business_id', 'kuaidiniao_e_business_id', 'kuaidiniao', '1', '', 'string'), ('kuaidiniao_status', '1', 'kuaidiniao', '1', '', 'string'), ('language_id', 'zh_CN', 'site', '1', '', 'string'), ('like', '25,44,26,27,46', 'home', '1', '', 'string'), ('list_catid', '1', 'home', '1', '', 'string'), ('live_link1', 'http://localhost/shop/yf_shop/index.php', 'slider', '1', '轮播轮播', 'string'), ('live_link2', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('live_link3', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('live_link4', 'http://localhost/shop/yf_shop/index.php?ctl=GroupBuy&met=index', 'slider', '1', '轮播地址', 'string'), ('logistics_channel', 'kuaidi100', 'logistics', '1', '', 'string'), ('logo', '', 'main', '1', '', 'string'), ('mlogo', '', 'main', '1', '', 'string'), ('mobile_apk', 'http://127.0.0.1/yf_shop/index.php', 'setting', '1', '安卓安装包', 'string'), ('mobile_apk_version', 'http://wap.bbc-builder.com/', 'setting', '1', '当前安卓安装包版本', 'string'), ('mobile_ios', 'HANZaFR0Aw08PV1U02RzCW114UWXa26AUiIO', 'setting', '1', 'iOS版', 'string'), ('mobile_wx', 'http://127.0.0.1/yf_shop/image.php/media/plantform/image/20160822/1471839828693328.jpg', 'setting', '1', '微信二维码', 'string'), ('modify_mobile', 'faf', 'msg_tpl', '1', '手机验证通知', 'string'), ('monetary_unit', '￥', 'site', '1', '', 'string'), ('msg_tpl1', '21212', 'msg_tpl', '1', '212', 'string'), ('new_pro', '48,32,23,25,28', 'home', '1', '', 'string'), ('openstatistics', '1', 'main', '1', '', 'string'), ('opensuburl', '0', 'seo', '1', '', 'string'), ('order_id_prefix_format', 'DD-', 'bill_format', '1', '//自定义订单前缀', 'string'), ('owntel', '021-64966875', 'main', '1', '', 'string'), ('photo_font', '\r\nArial,宋体,微软雅黑', 'photo', '1', '水印字体', 'string'), ('photo_goods_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217234113345.jpg!300x300.jpg', 'photo', '1', '商品默认图片', 'string'), ('photo_shop_head_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217274997950.jpg!180x80.jpg', 'photo', '1', '店铺默认头像', 'string'), ('photo_shop_logo', 'http://yuanfeng.com/tech12/yf_shop/image.php/shop/data/upload/media/173597/47/image/20160714/1468475601861711.jpg', 'photo', '1', '店铺默认标志', 'string'), ('photo_user_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469780228802155.jpg', 'photo', '1', '会员默认头像', 'string'), ('Plugin_Cron', '0', 'plugin', '1', '', 'string'), ('Plugin_Log', '1', 'plugin', '1', '', 'string'), ('Plugin_Perm', '1', 'plugin', '1', '', 'string'), ('Plugin_Xhprof', '0', 'plugin', '1', '', 'string'), ('pointprod_isuse', '1', 'promotion', '1', '积分兑换是否开', 'number'), ('pointshop_isuse', '1', 'promotion', '1', '积分中心是否开启', 'number'), ('points_avatar', '50', 'points', '1', '', 'string'), ('points_checkin', '5', 'points', '1', '', 'string'), ('points_consume', '100', 'points', '1', '', 'string'), ('points_email', '50', 'points', '1', '', 'string'), ('points_evaluate', '21', 'points', '1', '商品评论获取积分', 'string'), ('points_evaluate_good', '50', 'points', '1', '', 'string'), ('points_evaluate_image', '10', 'points', '1', '', 'string'), ('points_login', '15', 'points', '1', '登陆获取积分', 'string'), ('points_mobile', '50', 'points', '1', '', 'string'), ('points_order', '800', 'points', '1', '订单获取积分上限', 'string'), ('points_recharge', '100', 'points', '1', '订单每多少获取多少积分', 'string'), ('points_reg', '50', 'points', '1', '注册获取积分', 'string'), ('point_description', '收到公司的', 'seo', '1', '', 'string'), ('point_description_content', '特温特', 'seo', '1', '', 'string'), ('point_keyword', ' nfbgnjgf', 'seo', '1', '', 'string'), ('point_keyword_content', '热热', 'seo', '1', '', 'string'), ('point_title', 'e{sitename}', 'seo', '1', '', 'string'), ('point_title_content', 'g{sitename}', 'seo', '1', '', 'string'), ('product_description', '商品', 'seo', '1', '', 'string'), ('product_keyword', '商品', 'seo', '1', '', 'string'), ('product_title', '商品{sitename}{name}', 'seo', '1', '', 'string'), ('promotion_allow', '1', 'promotion', '1', '促销活动是否开启', 'number'), ('promotion_discount_price', '12', 'discount', '1', '', 'number'), ('promotion_increase_price', '20', 'increase', '1', '', 'number'), ('promotion_mansong_price', '15', 'mansong', '1', '', 'number'), ('promotion_voucher_buyertimes_limit', '10', 'voucher', '1', '', 'number'), ('promotion_voucher_price', '21', 'voucher', '1', '', 'number'), ('promotion_voucher_storetimes_limit', '2', 'voucher', '1', '', 'number'), ('protection_service_status', '1', 'operation', '1', '', 'string'), ('qanggou', '48', 'home', '1', '', 'string'), ('regname', 'register.php', 'main', '1', '', 'string'), ('remote_image_key', 'abcdgfgsgfsgfsg23132', 'upload', '1', '', 'string'), ('remote_image_status', '1', 'upload', '1', '', 'string'), ('remote_image_url', 'http://127.0.0.1/yf_shop/uploader.php', 'upload', '1', '', 'string'), ('reset_pwd', 'faf', 'msg_tpl', '1', '重置密码通知', 'string'), ('retain_domain', 'www', 'domain', '1', '', 'string'), ('rewrite', '0', 'seo', '1', '', 'string'), ('search_words', '茶杯,衣服,美食,电脑,电视,12,67,76,99', 'search', '1', '搜索词', 'string'), ('send_chain_code', 'faf', 'msg_tpl', '1', '门店提货通知', 'string'), ('send_pickup_code', 'faf', 'msg_tpl', '1', '自提通知', 'string'), ('send_vr_code', 'faf', 'msg_tpl', '1', '虚拟兑换码通知', 'string'), ('service_station_status', '0', 'operation', '1', '', 'string'), ('setting_buyer_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470214463395892.jpg!150x40.jpg', 'setting', '1', '', 'string'), ('setting_email', '552786543@qq.com', 'setting', '1', '', 'string'), ('setting_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160803/1470217293114679.jpg!240x60.jpg', 'setting', '1', '', 'string'), ('setting_phone', '021-888888,021-112121', 'setting', '1', '', 'string'), ('setting_seller_logo', 'http://127.0.0.1/yf_shop/image.php/shop/data/upload/media/1/1/image/20160802/1470128931804909.jpg', 'setting', '1', '', 'string'), ('shop_description', '店铺', 'seo', '1', '', 'string'), ('shop_domain', '1', 'domain', '1', '', 'string'), ('shop_is_open', '1', 'main', '1', '', 'string'), ('shop_keyword', '店铺', 'seo', '1', '', 'string'), ('shop_title', '店铺{shopname}{sitename}', 'seo', '1', '', 'string'), ('site_name', 'BBCbuilder', 'site', '1', '', 'string'), ('site_status', '1', 'site', '1', '', 'number'), ('slider1_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779648351956.jpg', 'slider', '1', '团购轮播1', 'string'), ('slider2_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779744758951.jpg', 'slider', '1', '团购轮播2', 'string'), ('slider3_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779757819368.jpg', 'slider', '1', '团购轮播3', 'string'), ('slider4_image', 'http://localhost/shop/yf_shop/image.php/shop/data/upload/media/1/1/image/20160729/1469779795128665.jpg', 'slider', '1', '团购轮播4', 'string'), ('slogo', '', 'main', '1', '', 'string'), ('sms_account', 'yf_shop', 'sms', '1', '', 'string'), ('sms_pass', 'yf_shop', 'sms', '1', '', 'string'), ('sns_description', 'sns', 'seo', '1', '', 'string'), ('sns_keyword', 'sns{name}', 'seo', '1', '', 'string'), ('sns_title', 'sns{sitename}', 'seo', '1', '', 'string'), ('sphinx_search_host', '111123213', 'sphinx', '1', '', 'string'), ('sphinx_search_port', '121212', 'sphinx', '1', '', 'string'), ('sphinx_statu', '1', 'sphinx', '1', '', 'string'), ('statistics_code', '第三方流量统计代码78', 'site', '1', '', 'string'), ('stat_is_open', 'fwefe', 'main', '1', '', 'string'), ('tg_description', '团购', 'seo', '1', '', 'string'), ('tg_description_content', '团购', 'seo', '1', '', 'string'), ('tg_keyword', '团购', 'seo', '1', '', 'string'), ('tg_keyword_content', '团购', 'seo', '1', '', 'string'), ('tg_title', '{sitename}-团购-{name}1', 'seo', '1', '', 'string'), ('tg_title_content', '{sitename}-团购{name}', 'seo', '1', '', 'string'), ('theme_id', 'default', 'site', '1', '', 'string'), ('time_format', 'H:i:s', 'site', '1', '', 'string'), ('time_zone_id', 'Asia/Shanghai', 'site', '1', '', 'number'), ('title', 'BBCbuilder1331{sitename}', 'seo', '1', '', 'string'), ('voucher_allow', '1', 'promotion', '1', '代金券功能是否开启', 'number'), ('weburl', 'http://demo.bbc-builder.com', 'main', '1', '', 'string');
COMMIT;

insert into `yf_goods_spec` ( `spec_readonly`, `spec_id`, `spec_name`) values ( '1', '1', '颜色');

SET FOREIGN_KEY_CHECKS = 1;
