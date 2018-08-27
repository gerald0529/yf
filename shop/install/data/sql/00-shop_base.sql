-- Adminer 4.3.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ucenter_reg_option`;
CREATE TABLE `ucenter_reg_option` (
  `reg_option_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项值',
  `reg_option_name` varchar(20) NOT NULL DEFAULT '',
  `reg_option_order` int(3) NOT NULL,
  `option_id` int(11) NOT NULL COMMENT '选项id(LIST):1-列表;2-单选;3-复选框;4-输入框;5-多行文本框;6-文件;',
  `reg_option_required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必须(BOOL):0-非必填;1-必填',
  `reg_option_placeholder` varchar(100) NOT NULL DEFAULT '' COMMENT 'placeholder',
  `reg_option_datatype` varchar(20) NOT NULL DEFAULT '' COMMENT 'data_type(LIST):0-不限制;1-手机;2-身份证;3-数字;4-字母',
  `reg_option_value` varchar(255) NOT NULL DEFAULT '' COMMENT '配置选项',
  `reg_option_active` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用(BOOL):1-启用;0-禁用',
  PRIMARY KEY (`reg_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='注册选项表';


DROP TABLE IF EXISTS `ucenter_reg_option_value`;
CREATE TABLE `ucenter_reg_option_value` (
  `reg_option_value_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项值',
  `reg_option_id` int(11) NOT NULL COMMENT '选项id',
  `reg_option_value_image` varchar(255) NOT NULL DEFAULT '' COMMENT '选项值图片',
  `reg_option_value_order` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `reg_option_value_name` varchar(255) NOT NULL DEFAULT '' COMMENT '选项值名称',
  PRIMARY KEY (`reg_option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `ucenter_user_option`;
CREATE TABLE `ucenter_user_option` (
  `user_option_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项值',
  `reg_option_id` int(3) NOT NULL,
  `reg_option_value_id` varchar(20) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL COMMENT '选项id',
  `user_option_value` text NOT NULL,
  PRIMARY KEY (`user_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_advertisement`;
CREATE TABLE `yf_advertisement` (
  `adver_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adver_url` varchar(255) NOT NULL COMMENT '链接',
  `adver_img` varchar(255) NOT NULL COMMENT '广告图片',
  `adver_to_display` varchar(255) NOT NULL COMMENT '广告显示在哪个页面',
  `adver_type` varchar(255) NOT NULL COMMENT '广告类型',
  `adver_sort` varchar(255) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`adver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_adv_page_layout`;
CREATE TABLE `yf_adv_page_layout` (
  `layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `layout_name` varchar(50) NOT NULL COMMENT '框架名称',
  `layout_structure` text NOT NULL COMMENT '框架结构|可以逐条存取，考虑到由平台统一设定，直接一个字段存取',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='页面布局版式-模板';


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
  `sub_site_id` mediumint(4) NOT NULL DEFAULT '0' COMMENT '所属分站Id:0-总站 -1-供应商首页  其他表示分站',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='页面模块表-用户获取最终的广告';


DROP TABLE IF EXISTS `yf_adv_page_statistics_area`;
CREATE TABLE `yf_adv_page_statistics_area` (
  `page_statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `page_id` int(10) unsigned NOT NULL COMMENT 'id',
  `page_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `page_province` varchar(20) NOT NULL,
  PRIMARY KEY (`page_statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照区域-先按照省为单位';


DROP TABLE IF EXISTS `yf_adv_page_statistics_day`;
CREATE TABLE `yf_adv_page_statistics_day` (
  `page_statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `page_id` int(10) unsigned NOT NULL COMMENT '广告页面id',
  `page_view_num` int(10) NOT NULL DEFAULT '0' COMMENT '统计数',
  `page_data` date NOT NULL DEFAULT '0000-00-00' COMMENT '天',
  PRIMARY KEY (`page_statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


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


DROP TABLE IF EXISTS `yf_adv_widget_cat`;
CREATE TABLE `yf_adv_widget_cat` (
  `widget_cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `widget_cat_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '分类名称',
  `widget_cat_desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`widget_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告位类型表-数据类型，阅读使用，程序不调用';


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


DROP TABLE IF EXISTS `yf_adv_widget_nav`;
CREATE TABLE `yf_adv_widget_nav` (
  `widget_nav_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `widget_nav_name` varchar(20) NOT NULL DEFAULT '0' COMMENT '分类名称',
  `widget_nav_url` varchar(255) NOT NULL COMMENT '头部url',
  `page_id` int(10) NOT NULL DEFAULT '0' COMMENT '模板id',
  PRIMARY KEY (`widget_nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告位楼层头部分类表';


DROP TABLE IF EXISTS `yf_adv_widget_statistics_area`;
CREATE TABLE `yf_adv_widget_statistics_area` (
  `statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `widget_id` int(10) unsigned NOT NULL COMMENT 'id',
  `widget_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `widget_click_num` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `widget_province` varchar(20) NOT NULL,
  PRIMARY KEY (`statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照区域-先按照省为单位';


DROP TABLE IF EXISTS `yf_adv_widget_statistics_day`;
CREATE TABLE `yf_adv_widget_statistics_day` (
  `statistics_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '统计id',
  `widget_id` int(10) unsigned NOT NULL COMMENT 'id',
  `widget_view_num` int(10) NOT NULL DEFAULT '0' COMMENT 'page view ',
  `widget_click_num` int(10) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `widget_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '统计日期',
  PRIMARY KEY (`statistics_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='广告统计表-按照天为单位';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台区域统计表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台行业统计表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台统计概览表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台商品分析表';


DROP TABLE IF EXISTS `yf_analysis_platform_return`;
CREATE TABLE `yf_analysis_platform_return` (
  `platform_return_id` int(10) NOT NULL AUTO_INCREMENT,
  `return_date` date NOT NULL COMMENT '统计日期',
  `return_cash` decimal(8,2) NOT NULL COMMENT '统计金额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台售后统计表';


DROP TABLE IF EXISTS `yf_analysis_platform_total`;
CREATE TABLE `yf_analysis_platform_total` (
  `platform_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_num` int(10) NOT NULL COMMENT '店铺总量',
  `user_num` int(10) NOT NULL COMMENT '会员总量',
  `goods_num` int(10) NOT NULL COMMENT '商品总量',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_total_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='总统计表';


DROP TABLE IF EXISTS `yf_analysis_platform_user`;
CREATE TABLE `yf_analysis_platform_user` (
  `platform_user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_date` date NOT NULL COMMENT '统计时间',
  `user_id` int(10) NOT NULL COMMENT '买家id',
  `order_num` int(10) NOT NULL COMMENT '订单数',
  `order_cash` decimal(8,2) NOT NULL COMMENT '订单金额',
  `do_report` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送 1-发送 0-未发送',
  PRIMARY KEY (`platform_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台买家统计表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺区域统计表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺统计概览表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺商品分析表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台买家统计表';


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


DROP TABLE IF EXISTS `yf_base_cron_copy`;
CREATE TABLE `yf_base_cron_copy` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计划任务';


DROP TABLE IF EXISTS `yf_base_cron_sss`;
CREATE TABLE `yf_base_cron_sss` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计划任务';


DROP TABLE IF EXISTS `yf_base_district`;
CREATE TABLE `yf_base_district` (
  `district_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区id',
  `district_name` varchar(255) NOT NULL DEFAULT '' COMMENT '地区名称',
  `district_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `district_displayorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `district_region` varchar(50) NOT NULL DEFAULT '' COMMENT '区域名称 - 华北、东北、华东、华南、华中、西南、西北、港澳台、海外',
  `district_is_leaf` tinyint(1) NOT NULL DEFAULT '1' COMMENT '无子类',
  `district_is_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级',
  PRIMARY KEY (`district_id`),
  KEY `upid` (`district_parent_id`,`district_displayorder`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='地区表';


DROP TABLE IF EXISTS `yf_base_filter_keyword`;
CREATE TABLE `yf_base_filter_keyword` (
  `keyword_find` varchar(50) NOT NULL,
  `keyword_replace` varchar(50) NOT NULL,
  `keyword_statu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:禁止 2：替换',
  `keyword_time` datetime NOT NULL,
  PRIMARY KEY (`keyword_find`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='敏感词过滤表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='充值卡表';


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


DROP TABLE IF EXISTS `yf_chain_base`;
CREATE TABLE `yf_chain_base` (
  `chain_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店Id',
  `chain_name` varchar(20) NOT NULL DEFAULT '' COMMENT '门店名称',
  `chain_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `chain_telephone` varchar(30) NOT NULL DEFAULT '' COMMENT '联系电话',
  `chain_contacter` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人',
  `chain_province_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '省id',
  `chain_province` varchar(10) NOT NULL COMMENT '省份',
  `chain_city_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '市id',
  `chain_city` varchar(10) NOT NULL COMMENT '市',
  `chain_county_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '县',
  `chain_county` varchar(10) NOT NULL COMMENT '县区',
  `chain_address` varchar(50) NOT NULL DEFAULT '' COMMENT '详细地址',
  `chain_opening_hours` varchar(255) NOT NULL DEFAULT '' COMMENT '营业时间',
  `chain_traffic_line` varchar(255) NOT NULL DEFAULT '' COMMENT '交通路线',
  `chain_img` varchar(255) NOT NULL DEFAULT '' COMMENT '门店图片',
  `chain_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店表';


DROP TABLE IF EXISTS `yf_chain_goods`;
CREATE TABLE `yf_chain_goods` (
  `chain_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `chain_id` int(10) NOT NULL DEFAULT '0' COMMENT '门店id',
  `shop_id` int(10) NOT NULL DEFAULT '0' COMMENT '商店id',
  `goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `common_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品common_id',
  `goods_stock` int(10) NOT NULL DEFAULT '0' COMMENT '商品商品库存',
  PRIMARY KEY (`chain_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店商品表';


DROP TABLE IF EXISTS `yf_chain_user`;
CREATE TABLE `yf_chain_user` (
  `chain_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店用户id',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `chain_user_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后登录时间',
  PRIMARY KEY (`chain_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店用户表';


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


DROP TABLE IF EXISTS `yf_complain_subject`;
CREATE TABLE `yf_complain_subject` (
  `complain_subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '投诉主题id',
  `complain_subject_content` varchar(50) NOT NULL COMMENT '投诉主题',
  `complain_subject_desc` varchar(100) NOT NULL COMMENT '投诉主题描述',
  `complain_subject_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '投诉主题状态(1-有效/0-失效)',
  PRIMARY KEY (`complain_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='投诉主题表';


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


DROP TABLE IF EXISTS `yf_consult_type`;
CREATE TABLE `yf_consult_type` (
  `consult_type_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题分类id',
  `consult_type_name` varchar(50) NOT NULL COMMENT '分类名称',
  `consult_type_sort` int(3) NOT NULL DEFAULT '255' COMMENT '咨询类型排序',
  PRIMARY KEY (`consult_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='咨询问题分类表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='限时折扣活动表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='限时折扣套餐表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='限时折扣商品表';


DROP TABLE IF EXISTS `yf_distribution_base_config`;
CREATE TABLE `yf_distribution_base_config` (
  `config_key` varchar(50) NOT NULL COMMENT '设置key',
  `config_value` varchar(10000) NOT NULL DEFAULT '' COMMENT '值',
  `config_type` varchar(20) NOT NULL DEFAULT '' COMMENT '所属分类',
  `config_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态值，1可能，0不可用',
  `config_comment` varchar(255) NOT NULL DEFAULT '' COMMENT '释注',
  `config_datatype` enum('string','json','number','dot') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  `config_name` varchar(50) NOT NULL DEFAULT '' COMMENT '设置名称',
  `config_formater` varchar(255) NOT NULL DEFAULT '' COMMENT '输出格式-分别为key\\value两个输出',
  `config_category` enum('系统参数','基础参数','扩展参数') NOT NULL DEFAULT '基础参数' COMMENT '设置类型-用来看数据，无使用价值',
  PRIMARY KEY (`config_key`),
  UNIQUE KEY `config_type` (`config_category`,`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--系统参数设置表';


DROP TABLE IF EXISTS `yf_distribution_goods_base`;
CREATE TABLE `yf_distribution_goods_base` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `goods_recommended_price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '建议零售价',
  `goods_recommended_min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最低零售价',
  `goods_recommended_max_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最高零售价',
  `goods_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源id',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';


DROP TABLE IF EXISTS `yf_distribution_goods_common`;
CREATE TABLE `yf_distribution_goods_common` (
  `common_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `product_lock_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺必须分销标记  1:不可删除   0：可以删除',
  `product_agent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id-可更改，该店铺下级都属于该代理商。',
  `product_distributor_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为分销商品 0-自有商品',
  `supply_shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源-供应商店铺id',
  `product_is_allow_update` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可以修改内容',
  `product_is_allow_price` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可以修改价格',
  `product_is_behalf_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否代发货',
  `common_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销原产品',
  PRIMARY KEY (`common_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--商品公共内容表-未来可分表';


DROP TABLE IF EXISTS `yf_distribution_order_base`;
CREATE TABLE `yf_distribution_order_base` (
  `order_id` varchar(50) NOT NULL COMMENT '订单号',
  `shop_distributor_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商',
  `order_distribution_seller_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'SO订单分销类型 1:直销(E)  2:分销代销转发销售(P, SP)',
  `order_distribution_buyer_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'PO订单类型 1:购买(E采购，SP:代销采购)  2:分销采购,代客下单 (P开头)',
  `order_source_id` varchar(50) NOT NULL DEFAULT '0' COMMENT '源订单Id（P开头）:SP开头订单对应的P开头订单',
  `directseller_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员推广',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员-订单',
  `directseller_p_id` int(10) NOT NULL DEFAULT '0' COMMENT '推官员上级',
  `directseller_gp_id` int(10) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--订单详细信息';


DROP TABLE IF EXISTS `yf_distribution_order_goods`;
CREATE TABLE `yf_distribution_order_goods` (
  `order_goods_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `goods_id` int(10) NOT NULL COMMENT '商品id',
  `common_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品common_id',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员',
  `directseller_p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推官员上级',
  `directseller_gp_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推官员上级',
  PRIMARY KEY (`order_goods_id`),
  KEY `order_id` (`order_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--订单分销商品关系表';


DROP TABLE IF EXISTS `yf_distribution_shop_agent`;
CREATE TABLE `yf_distribution_shop_agent` (
  `shop_agent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `agent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商id = shop_id',
  `agent_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  PRIMARY KEY (`shop_agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺代理商表。';


DROP TABLE IF EXISTS `yf_distribution_shop_agent_generated_commission`;
CREATE TABLE `yf_distribution_shop_agent_generated_commission` (
  `agc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
  `directseller_id` mediumint(8) unsigned NOT NULL COMMENT '销售员用户Id',
  `directseller_name` varchar(30) NOT NULL,
  `directseller_parent_id` mediumint(11) NOT NULL COMMENT '父用户Id',
  `directseller_parent_name` varchar(30) NOT NULL,
  `agc_level` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户等级-分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
  PRIMARY KEY (`agc_id`),
  UNIQUE KEY `user_id` (`directseller_id`,`directseller_parent_id`) COMMENT '(null)',
  UNIQUE KEY `user_id_2` (`directseller_id`,`agc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商贡献产生佣金汇总表-代理发展下级代理商才产生记录。- 强调代理商对上级的佣金贡献。强调代理创造的佣金';


DROP TABLE IF EXISTS `yf_distribution_shop_agent_level`;
CREATE TABLE `yf_distribution_shop_agent_level` (
  `agent_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '代理商等级id',
  `agent_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
  `agent_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
  `agent_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级代理商代销或采购所有商品全部免运费',
  `agent_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
  PRIMARY KEY (`agent_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商等级表';


DROP TABLE IF EXISTS `yf_distribution_shop_base`;
CREATE TABLE `yf_distribution_shop_base` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `shop_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级店铺id-创建店铺决定，所属分销商-不可更改！ 佣金公平性考虑',
  `shop_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺类型: 1-卖家店铺; 2:供应商店铺',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--店铺基础信息表-分销店铺来源关系记录(上级)，特殊情况下此记录可以改变。';


DROP TABLE IF EXISTS `yf_distribution_shop_commission`;
CREATE TABLE `yf_distribution_shop_commission` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `commission_amount` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '佣金总额',
  `commission_distributor_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店分销佣金',
  `commission_distributor_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级分销佣金',
  `commission_distributor_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级分销佣金',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收益表-代理/分销/推广';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller`;
CREATE TABLE `yf_distribution_shop_directseller` (
  `shop_directseller_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `directseller_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id-因为店铺不同，上级可能不同，如果用户主动成为某个店铺的销售员，则上级id为0',
  `directseller_shop_name` varchar(100) NOT NULL COMMENT '推广小店名称',
  `directseller_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核  1-通过',
  `directseller_create_time` datetime NOT NULL COMMENT '创建时间',
  `directseller_common_ids` text,
  PRIMARY KEY (`shop_directseller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员表。';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller_config`;
CREATE TABLE `yf_distribution_shop_directseller_config` (
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `allow_seller_buy` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员购买权限-购买权限开启状态下，销售员自己购买的订单将会算入业绩',
  `auto_settle` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '结算方式 0-手动结算 1-自动结算',
  `cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级佣金比例',
  `second_is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '二级销售 0关闭 1开启',
  `second_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级佣金比例',
  `directseller_customer_exptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户关系 期限， 销售员带来的客户（成为店铺的消费者开始计算时间）超过一定期限后，则不再享受分佣。 消费者在店铺消费第一单时间后，在某个期限内消费才可以产生佣金。 ',
  `directseller_exptime_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1、永久，建立客户关系,客户以后在店铺的购买都分佣。    2、短期，只根据链接购买获取佣金， 且一定期限后，链接失效。 不需要建立客户关系',
  `directseller_rel_exptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户关系保护期 - 带来的客户关系在一定期限内不给抢走， 其它销售可以通过购买链接生效，但是在保护期内部更改关系',
  `is_verify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '销售员审核 0不需要审核 1需要审核',
  `settle_time_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结算时间',
  `third_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三级分佣比例',
  `expenditure` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消费额',
  PRIMARY KEY (`shop_id`),
  UNIQUE KEY `config_type` (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺销售员参数设置表-meta';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller_customer`;
CREATE TABLE `yf_distribution_shop_directseller_customer` (
  `shop_directseller_customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '客户Id = user_id - 这个可以改变，当客户根据其他销售者链接购买，则更改关系',
  PRIMARY KEY (`shop_directseller_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员客户表。';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller_generated_commission`;
CREATE TABLE `yf_distribution_shop_directseller_generated_commission` (
  `dgc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
  `directseller_id` mediumint(8) unsigned NOT NULL COMMENT '销售员用户Id',
  `directseller_name` varchar(30) NOT NULL,
  `directseller_parent_id` mediumint(11) unsigned NOT NULL COMMENT '父用户Id',
  `directseller_parent_name` varchar(30) NOT NULL,
  `dgc_level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '用户等级-分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
  `dgc_amount` decimal(16,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '销售佣金',
  PRIMARY KEY (`dgc_id`),
  UNIQUE KEY `user_id` (`directseller_id`,`directseller_parent_id`) COMMENT '(null)',
  UNIQUE KEY `user_id_2` (`directseller_id`,`dgc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺推广销售员贡献产生佣金汇总表-销售员推广销售员才产生记录。- 强调销售员与上级关系以及对上级的佣金贡献。强调销售员创造的佣金';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller_goods_common`;
CREATE TABLE `yf_distribution_shop_directseller_goods_common` (
  `shop_directseller_goods_common_code` varchar(255) NOT NULL DEFAULT '' COMMENT '用户推广商品唯一ID',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员id = user_id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `common_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品id',
  `directseller_images_image` text COMMENT '商品图片',
  PRIMARY KEY (`shop_directseller_goods_common_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='销售员推广产品表。';


DROP TABLE IF EXISTS `yf_distribution_shop_directseller_level`;
CREATE TABLE `yf_distribution_shop_directseller_level` (
  `directseller_level_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '分销商等级id',
  `directseller_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
  `directseller_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
  `directseller_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级分销代销或采购所有商品全部免运费',
  `directseller_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
  PRIMARY KEY (`directseller_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='销售员等级表';


DROP TABLE IF EXISTS `yf_distribution_shop_distributor`;
CREATE TABLE `yf_distribution_shop_distributor` (
  `shop_distributor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属店铺id',
  `distributor_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商id = shop_id',
  `distributor_level_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销商等级id',
  `distributor_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id - 加入此分销店铺的时候的来源-为不同供应商发展自己的分销商使用。如果存在供应商市场，此字段可以放弃',
  `distributor_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核通过: 0-待审核;  1-通过;-1未通过',
  `distributor_cat_ids` varchar(1024) NOT NULL DEFAULT '' COMMENT '分销商品授权分类:'',''分割, 供应商对分销商可分销商品所属分类授权，非分类下商品不可以分销。',
  `shop_distributor_time` datetime NOT NULL COMMENT '申请时间',
  `shop_distributor_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '审核原因',
  `distributor_new_cat_ids` text NOT NULL COMMENT '分销商新增分类:'',''分割, 供应商对分销商可分销商品所属分类授权，非分类下商品不可以分销。',
  PRIMARY KEY (`shop_distributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺分销者表。- 不同供应商，可以具有同一个分销商';


DROP TABLE IF EXISTS `yf_distribution_shop_distributor_generated_commission`;
CREATE TABLE `yf_distribution_shop_distributor_generated_commission` (
  `fgc_id` varchar(255) NOT NULL COMMENT '用户为不同店铺贡献的佣金:user_id + shop_id + level',
  `distributor_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分销商Id = shop_id',
  `distributor_name` varchar(30) NOT NULL COMMENT '分销店铺名称',
  `distributor_parent_id` mediumint(11) unsigned NOT NULL COMMENT '父用户Id',
  `distributor_parent_name` varchar(30) NOT NULL,
  `fgc_level` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '分销层级: 1父  ,2祖父, 记录不变，如果关系更变，则增加其它记录',
  PRIMARY KEY (`fgc_id`),
  UNIQUE KEY `user_id` (`distributor_id`,`distributor_parent_id`) COMMENT '(null)',
  UNIQUE KEY `user_id_2` (`distributor_id`,`fgc_level`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商贡献产生佣金汇总表-分销商发展下级分销商才产生记录。- 强调分销商对上级的佣金贡献。强调分销商创造的佣金 fgc=distributor_generated_commission';


DROP TABLE IF EXISTS `yf_distribution_shop_distributor_goods_cat`;
CREATE TABLE `yf_distribution_shop_distributor_goods_cat` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销者可分销商品授权分类';


DROP TABLE IF EXISTS `yf_distribution_shop_distributor_level`;
CREATE TABLE `yf_distribution_shop_distributor_level` (
  `distributor_level_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分销商等级id',
  `distributor_leve_name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
  `distributor_leve_discount_rate` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '等级折扣',
  `distributor_leve_freeshipping` varchar(255) NOT NULL DEFAULT '0' COMMENT '包邮设置-开启后该等级分销代销或采购所有商品全部免运费',
  `distributor_leve_order` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺Id',
  PRIMARY KEY (`distributor_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商等级表';


DROP TABLE IF EXISTS `yf_distribution_shop_team`;
CREATE TABLE `yf_distribution_shop_team` (
  `team_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '注释名称(in=>88,23) :0-不返点; 1-百分比返点 percentage; 2-等级差返点difference',
  `team_name` varchar(255) NOT NULL COMMENT '团队名称',
  `team_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团队类型： 代理团',
  `team_image` varchar(255) NOT NULL COMMENT '团队头像',
  `distributor_level_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '团员等级',
  `team_chat_flag` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '团队群聊',
  `team_verify_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '入团审核',
  `team_leader_id` int(10) NOT NULL DEFAULT '0' COMMENT '设置团长',
  `team_performance_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '团队业绩:是否允许普通成员查看业绩',
  `team_split_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '返点模式: 0-不返点; 1-百分比返点 percentage; 2:等级差返点difference',
  `team_invisible` tinyint(1) NOT NULL DEFAULT '0' COMMENT '秘密团队:开启后，用户无法在平台中搜索到该团队',
  `team_permit` varchar(255) NOT NULL DEFAULT '0' COMMENT '授权团长(DOT):供应商可授权团长调整团员的代理等级，授权后，团长可在app内修改团员的代理等级',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属供应商',
  `team_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '累计成交量',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商团队表';


DROP TABLE IF EXISTS `yf_distribution_shop_team_member`;
CREATE TABLE `yf_distribution_shop_team_member` (
  `team_member_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '团队成员id',
  `team_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团队id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`team_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='供应商团队成员表';


DROP TABLE IF EXISTS `yf_distribution_shop_type`;
CREATE TABLE `yf_distribution_shop_type` (
  `shop_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `shop_type_name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY (`shop_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--店铺类型';


DROP TABLE IF EXISTS `yf_distribution_user_base`;
CREATE TABLE `yf_distribution_user_base` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级用户id - 注册决定，不可更改，推广公平性考虑',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='此表废弃--用户基础信息表-用户来源关系记录，此记录不可以改变。';


DROP TABLE IF EXISTS `yf_distribution_user_commission`;
CREATE TABLE `yf_distribution_user_commission` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `commission_amount` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '佣金总额',
  `commission_directseller_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '获取推广销售佣金',
  `commission_directseller_amount_1` decimal(15,6) NOT NULL,
  `commission_directseller_amount_2` decimal(15,6) NOT NULL,
  `commission_buy_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
  `commission_buy_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
  `commission_buy_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '消费佣金',
  `commission_click_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店流量佣金',
  `commission_click_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级流量佣金',
  `commission_click_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级流量佣金',
  `commission_reg_amount_0` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '本店注册佣金',
  `commission_reg_amount_1` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '一级注册佣金',
  `commission_reg_amount_2` decimal(15,6) NOT NULL DEFAULT '0.000000' COMMENT '二级注册佣金',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推广收益表-用户赚取汇总';


DROP TABLE IF EXISTS `yf_express`;
CREATE TABLE `yf_express` (
  `express_id` int(10) NOT NULL AUTO_INCREMENT,
  `express_name` varchar(30) NOT NULL COMMENT '快递公司',
  `express_pinyin` varchar(30) NOT NULL COMMENT '物流',
  `express_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0关闭1开启',
  `express_displayorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用0否1是',
  `express_commonorder` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否常用',
  PRIMARY KEY (`express_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快递表';


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


DROP TABLE IF EXISTS `yf_feed_group`;
CREATE TABLE `yf_feed_group` (
  `feed_group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '反馈组id',
  `feed_group_name` varchar(30) NOT NULL COMMENT '反馈组名称',
  PRIMARY KEY (`feed_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='反馈组表';


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
  `goods_recommended_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议零售价-可以取消',
  `goods_recommended_min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议最低零售价',
  `goods_recommended_max_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议最高零售价',
  `goods_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源id',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品表';


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


DROP TABLE IF EXISTS `yf_goods_cat_nav`;
CREATE TABLE `yf_goods_cat_nav` (
  `goods_cat_nav_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_cat_nav_name` varchar(50) NOT NULL COMMENT '分类别名',
  `goods_cat_nav_brand` varchar(200) NOT NULL COMMENT '推荐品牌',
  `goods_cat_nav_recommend` text NOT NULL COMMENT '推荐分类',
  `goods_cat_nav_pic` varchar(255) NOT NULL COMMENT '分类图片',
  `goods_cat_nav_adv` varchar(1024) NOT NULL COMMENT '广告图',
  `goods_cat_id` int(10) NOT NULL COMMENT '商品分类id',
  `goods_cat_nav_recommend_display` text NOT NULL COMMENT '显示用推荐分类',
  PRIMARY KEY (`goods_cat_nav_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品分类导航';


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
  `shop_self_support` tinyint(1) NOT NULL DEFAULT '1',
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
  `common_is_return` tinyint(1) NOT NULL DEFAULT '1',
  `common_formatid_top` int(10) unsigned NOT NULL COMMENT '顶部关联板式',
  `common_formatid_bottom` int(10) unsigned NOT NULL COMMENT '底部关联板式',
  `common_is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品推荐',
  `common_is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟商品',
  `common_virtual_date` date NOT NULL COMMENT '虚拟商品有效期',
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
  `cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分佣比例',
  `second_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分佣比例',
  `third_cps_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三级分佣比例',
  `common_cps_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分佣比例',
  `common_second_cps_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分佣比例',
  `common_third_cps_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分佣比例',
  `common_is_directseller` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否参与推广 0不参与 1参与',
  `product_lock_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店铺必须分销标记  1:不可删除   0：可以删除',
  `product_agent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '代理商id-可更改，该店铺下级都属于该代理商。',
  `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地,从店铺中同步，冗余检索使用',
  `supply_shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品来源-供应商店铺id',
  `product_is_allow_update` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改内容',
  `product_is_allow_price` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可以修改价格-可以取消',
  `product_is_behalf_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否代发货',
  `common_parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '分销原产品',
  `goods_recommended_min_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最低零售价',
  `goods_recommended_max_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建议最高零售价',
  `product_distributor_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为分销商品 0-自有商品',
  `common_distributor_description` text COMMENT '分销说明',
  `common_distributor_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1价格修改 2内容修改',
  `common_cps_commission` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '直属一级佣金-便于佣金排序',
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


DROP TABLE IF EXISTS `yf_goods_common_detail`;
CREATE TABLE `yf_goods_common_detail` (
  `common_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `common_body` text NOT NULL COMMENT '商品内容',
  PRIMARY KEY (`common_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品公共内容详情表';


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


DROP TABLE IF EXISTS `yf_goods_format`;
CREATE TABLE `yf_goods_format` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `position` tinyint(1) unsigned NOT NULL,
  `content` text NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='关联板式表';


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


DROP TABLE IF EXISTS `yf_goods_property_index`;
CREATE TABLE `yf_goods_property_index` (
  `goods_property_index_id` int(11) NOT NULL AUTO_INCREMENT,
  `common_id` int(10) unsigned NOT NULL COMMENT '商品公共表id',
  `property_id` int(10) unsigned NOT NULL COMMENT '属性id',
  `property_value_id` int(10) unsigned NOT NULL COMMENT '属性值id',
  PRIMARY KEY (`goods_property_index_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品与属性对应表';


DROP TABLE IF EXISTS `yf_goods_property_value`;
CREATE TABLE `yf_goods_property_value` (
  `property_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `property_value_name` varchar(100) NOT NULL COMMENT '属性值名称',
  `property_id` int(10) unsigned NOT NULL COMMENT '所属属性id',
  `property_value_displayorder` smallint(3) unsigned NOT NULL DEFAULT '1' COMMENT '属性值排序',
  PRIMARY KEY (`property_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品属性值表';


DROP TABLE IF EXISTS `yf_goods_recommend`;
CREATE TABLE `yf_goods_recommend` (
  `goods_recommend_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品推荐id',
  `goods_cat_id` int(10) NOT NULL COMMENT '商品分类id',
  `common_id` varchar(50) NOT NULL COMMENT '推荐商品id，最多四个',
  `recommend_num` int(5) NOT NULL COMMENT '推荐数量',
  PRIMARY KEY (`goods_recommend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品推荐表';


DROP TABLE IF EXISTS `yf_goods_service`;
CREATE TABLE `yf_goods_service` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_goods_spec`;
CREATE TABLE `yf_goods_spec` (
  `spec_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `spec_name` varchar(100) NOT NULL COMMENT '规格名称',
  `cat_id` int(10) unsigned NOT NULL COMMENT '快捷定位',
  `spec_displayorder` smallint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `spec_readonly` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '不可删除',
  PRIMARY KEY (`spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品规格表';


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


DROP TABLE IF EXISTS `yf_goods_state`;
CREATE TABLE `yf_goods_state` (
  `goods_state_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '产品状态id',
  `goods_state_name` varchar(50) NOT NULL DEFAULT '' COMMENT '产品状态状态',
  `goods_state_text_1` varchar(255) NOT NULL DEFAULT '' COMMENT '产品状态',
  `goods_state_text_2` varchar(255) NOT NULL DEFAULT '' COMMENT '产品状态',
  `goods_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`goods_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='产品状态表';


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


DROP TABLE IF EXISTS `yf_goods_type_brand`;
CREATE TABLE `yf_goods_type_brand` (
  `type_brand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL COMMENT '类型id',
  `brand_id` int(10) unsigned NOT NULL COMMENT '规格id',
  PRIMARY KEY (`type_brand_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品类型与品牌对应表';


DROP TABLE IF EXISTS `yf_goods_type_spec`;
CREATE TABLE `yf_goods_type_spec` (
  `type_spec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(10) unsigned NOT NULL COMMENT '类型id',
  `spec_id` int(10) unsigned NOT NULL COMMENT '规格id',
  PRIMARY KEY (`type_spec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品类型与规格对应表';


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


DROP TABLE IF EXISTS `yf_groupbuy_area`;
CREATE TABLE `yf_groupbuy_area` (
  `groupbuy_area_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区编号',
  `groupbuy_area_name` varchar(50) NOT NULL COMMENT '地区名称',
  `groupbuy_area_parent_id` int(10) unsigned NOT NULL COMMENT '父地区编号',
  `groupbuy_area_sort` tinyint(1) unsigned NOT NULL COMMENT '排序',
  `groupbuy_area_deep` tinyint(1) unsigned NOT NULL COMMENT '深度',
  PRIMARY KEY (`groupbuy_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购地区表';


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
  `groupbuy_image` varchar(255) NOT NULL COMMENT '团购图片',
  `groupbuy_image_rec` varchar(255) NOT NULL COMMENT '团购推荐位图片',
  `groupbuy_remark` varchar(255) NOT NULL COMMENT '备注',
  PRIMARY KEY (`groupbuy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购商品表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购套餐表';


DROP TABLE IF EXISTS `yf_groupbuy_price_range`;
CREATE TABLE `yf_groupbuy_price_range` (
  `range_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '价格区间编号',
  `range_name` varchar(20) NOT NULL COMMENT '区间名称',
  `range_start` int(10) unsigned NOT NULL COMMENT '区间下限',
  `range_end` int(10) unsigned NOT NULL COMMENT '区间上限',
  PRIMARY KEY (`range_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='团购价格区间表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='加价购活动表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='加价购套餐表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='加价购商品表';


DROP TABLE IF EXISTS `yf_increase_redemp_goods`;
CREATE TABLE `yf_increase_redemp_goods` (
  `redemp_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购换购商品表',
  `rule_id` int(10) unsigned NOT NULL COMMENT '加价购规则编号',
  `increase_id` int(10) unsigned NOT NULL COMMENT '加价购活动编号',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品编号',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `redemp_price` decimal(10,2) NOT NULL COMMENT '换购价',
  PRIMARY KEY (`redemp_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='加价购换购商品表';


DROP TABLE IF EXISTS `yf_increase_rule`;
CREATE TABLE `yf_increase_rule` (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加价购规则编号',
  `increase_id` int(10) unsigned NOT NULL COMMENT '活动编号',
  `rule_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '规则级别价格',
  `rule_goods_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限定换购数量，0为不限定数量',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='加价购规则表';


SET NAMES utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='买家发票信息表';


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
  KEY `log_date` (`log_date`) COMMENT '(null)',
  KEY `player_id` (`user_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户行为日志表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='满就送活动表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='满就送套餐表';


DROP TABLE IF EXISTS `yf_mansong_rule`;
CREATE TABLE `yf_mansong_rule` (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则编号',
  `mansong_id` int(10) unsigned NOT NULL COMMENT '活动编号',
  `rule_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '级别价格',
  `rule_discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '减现金优惠金额',
  `goods_name` varchar(50) NOT NULL COMMENT '礼品名称',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品编号',
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='满就送活动规则表';


DROP TABLE IF EXISTS `yf_mb_cat_image`;
CREATE TABLE `yf_mb_cat_image` (
  `mb_cat_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cat_id` int(10) unsigned NOT NULL COMMENT 'cat_id',
  `mb_cat_image` varchar(255) NOT NULL COMMENT '分类图片',
  `cat_adv_image` varchar(255) NOT NULL COMMENT '广告图片',
  `cat_adv_url` varchar(255) NOT NULL COMMENT '广告地址',
  PRIMARY KEY (`mb_cat_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分类图片';


DROP TABLE IF EXISTS `yf_mb_tpl_layout`;
CREATE TABLE `yf_mb_tpl_layout` (
  `mb_tpl_layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mb_tpl_layout_title` varchar(50) NOT NULL COMMENT '标题',
  `mb_tpl_layout_type` varchar(50) NOT NULL COMMENT '类型',
  `mb_tpl_layout_data` text NOT NULL COMMENT '根据不同的类型，所存储的数据也不同，仔细！（json）',
  `mb_tpl_layout_enable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用启用 0:未启用 1:启用',
  `mb_tpl_layout_order` tinyint(2) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `sub_site_id` int(11) NOT NULL DEFAULT '0' COMMENT '分站id',
  PRIMARY KEY (`mb_tpl_layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='手机端模板';


DROP TABLE IF EXISTS `yf_member_agreement`;
CREATE TABLE `yf_member_agreement` (
  `member_agreement_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '会员协议id',
  `member_agreement_title` varchar(30) NOT NULL COMMENT '会员协议标题',
  `member_agreement_content` varchar(255) NOT NULL COMMENT '会员协议内容',
  `member_agreement_time` datetime NOT NULL COMMENT '会员协议添加时间',
  `member_agreement_pic` varchar(100) NOT NULL COMMENT '会员协议图片',
  PRIMARY KEY (`member_agreement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员协议表';


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


DROP TABLE IF EXISTS `yf_message`;
CREATE TABLE `yf_message` (
  `message_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `message_user_id` int(10) NOT NULL COMMENT '消息接收者id',
  `message_user_name` varchar(50) NOT NULL COMMENT '消息接收者',
  `message_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '消息类型买家1订单信息3账户信息4其他',
  `message_title` varchar(100) NOT NULL COMMENT '消息标题',
  `message_content` text NOT NULL COMMENT '消息内容',
  `message_islook` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读取0未1读取',
  `message_create_time` datetime NOT NULL COMMENT '消息创建时间',
  `message_mold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0买家1商家',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统消息表';


DROP TABLE IF EXISTS `yf_message_setting`;
CREATE TABLE `yf_message_setting` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `message_template_all` varchar(255) NOT NULL COMMENT '选择开启的所有模板id',
  `setting_time` datetime NOT NULL COMMENT '设置时间',
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消息设置表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='邮件模板';


DROP TABLE IF EXISTS `yf_number_seq`;
CREATE TABLE `yf_number_seq` (
  `prefix` varchar(20) NOT NULL DEFAULT '' COMMENT '前缀',
  `number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '增长值',
  PRIMARY KEY (`prefix`),
  UNIQUE KEY `prefix` (`prefix`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='编号管理表';


DROP TABLE IF EXISTS `yf_order_base`;
CREATE TABLE `yf_order_base` (
  `order_id` varchar(50) NOT NULL COMMENT '订单号',
  `shop_id` int(10) NOT NULL COMMENT '卖家店铺id',
  `shop_name` varchar(50) NOT NULL COMMENT '卖家店铺名称',
  `buyer_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '买家id',
  `buyer_user_name` varchar(50) NOT NULL COMMENT '买家姓名',
  `seller_user_id` int(10) unsigned NOT NULL COMMENT '卖家id',
  `seller_user_name` varchar(50) NOT NULL COMMENT '买家姓名',
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
  `payment_other_number` varchar(20) NOT NULL COMMENT '第三方支付平台交易号',
  `order_seller_name` varchar(50) NOT NULL COMMENT '发货人的姓名',
  `order_seller_address` varchar(255) NOT NULL COMMENT '发货人的地址',
  `order_seller_contact` varchar(50) NOT NULL COMMENT '发货人的联系方式',
  `order_shipping_time` datetime NOT NULL COMMENT '配送时间',
  `order_shipping_express_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送公司ID',
  `order_shipping_code` varchar(50) NOT NULL COMMENT '物流单号',
  `order_shipping_message` varchar(255) NOT NULL COMMENT '卖家备注',
  `order_finished_time` datetime NOT NULL COMMENT '订单完成时间',
  `order_invoice` varchar(100) NOT NULL COMMENT '发票信息',
  `order_invoice_id` int(10) NOT NULL COMMENT '发票id',
  `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价格(不包含运费)',
  `order_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付金额（商品实际支付金额 + 运费）',
  `order_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠价格',
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
  `order_subuser_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '主管账号删除',
  `order_cancel_identity` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单取消者身份   1-买家 2-卖家 3-系统',
  `order_cancel_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单取消原因',
  `order_cancel_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '订单取消时间',
  `order_shop_benefit` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺优惠',
  `chain_id` int(11) NOT NULL COMMENT '门店id',
  `order_seller_message` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家给卖家留言',
  `directseller_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是分佣订单',
  `directseller_p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员上级',
  `redpacket_code` varchar(32) NOT NULL COMMENT '红包编码',
  `redpacket_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包面额',
  `order_rpt_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包抵扣订单金额',
  `order_settlement_time` datetime NOT NULL COMMENT '订单结算时间',
  `order_is_settlement` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单是否结算 1-已结算 0-未结算',
  `shop_distributor_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商',
  `order_distribution_seller_type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'SO订单分销类型 1:直销(E)  2:分销代销转发销售(P, SP)',
  `order_distribution_buyer_type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'PO订单类型 1:购买(E采购，SP:代销采购)  2:分销采购,代客下单 (P开头)',
  `order_source_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '源订单Id（P开头）:SP开头订单对应的P开头订单',
  `directseller_gp_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员上级的上级',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广员',
  `directseller_is_settlement` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销佣金是否结算 1-已经结算 0-未结算',
  `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地,从店铺中同步，冗余检索使用',
  `order_sub_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：自己支付，1：主管账号支付',
  `order_sub_user` int(10) NOT NULL DEFAULT '0' COMMENT '付款主账号id',
  `order_directseller_commission` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销员三级总佣金',
  `directseller_discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '等级折扣金额',
  PRIMARY KEY (`order_id`),
  KEY `shop_id` (`shop_id`),
  KEY `buyer_user_id` (`buyer_user_id`),
  KEY `seller_user_id` (`seller_user_id`),
  KEY `order_status` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单详细信息';


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


DROP TABLE IF EXISTS `yf_order_cancel_reason`;
CREATE TABLE `yf_order_cancel_reason` (
  `cancel_reason_id` int(20) NOT NULL AUTO_INCREMENT,
  `cancel_reason_content` varchar(100) DEFAULT '' COMMENT '取消订单的原因',
  `cancel_identity` tinyint(1) DEFAULT '0' COMMENT '取消订单者的身份 1-买家 2-卖家',
  PRIMARY KEY (`cancel_reason_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单取消原因表';


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
  `order_spec_info` varchar(255) NOT NULL COMMENT '规格描述',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格（商品原价，goods_base中的商品价格未参加任何活动的价格）',
  `order_goods_num` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '商品数量',
  `goods_image` varchar(255) NOT NULL COMMENT '商品图片',
  `order_goods_returnnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '退货数量',
  `order_goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品金额 （实付金额）= order_goods_payment_amount* order_goods_num',
  `order_goods_discount_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额 = （商品原价-实付金额）*商品数量',
  `order_goods_payment_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `order_goods_adjust_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手工调整金额',
  `order_goods_point_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '积分费用',
  `order_goods_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单商品的佣金 (总)',
  `shop_id` mediumint(10) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `order_goods_status` tinyint(1) NOT NULL COMMENT '订单状态',
  `order_goods_evaluation_status` tinyint(1) NOT NULL COMMENT '评价状态 0为评价，1已评价',
  `order_goods_benefit` varchar(255) NOT NULL DEFAULT '' COMMENT '订单商品优惠',
  `goods_refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退货状态:0是无退货,1是退货中,2是退货完成',
  `order_goods_time` datetime NOT NULL COMMENT '时间',
  `directseller_flag` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否参与分销',
  `directseller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推广销售员-订单',
  `directseller_is_settlement` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销佣金是否结算 1-已经结算 0-未结算',
  `directseller_commission_0` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '直属一级分佣',
  `directseller_commission_1` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '直属二级分佣',
  `directseller_commission_2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '直属三级分佣',
  `directseller_goods_discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '等级折扣金额',
  `order_goods_source_id` varchar(50) NOT NULL DEFAULT '' COMMENT 'SP订单号',
  `order_goods_source_ship` varchar(50) NOT NULL DEFAULT '' COMMENT '供应商物流',
  `order_goods_finish_time` datetime NOT NULL COMMENT '订单商品完成时间',
  PRIMARY KEY (`order_goods_id`),
  KEY `order_id` (`order_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单商品表';


DROP TABLE IF EXISTS `yf_order_goods_chain_code`;
CREATE TABLE `yf_order_goods_chain_code` (
  `chain_code_id` varchar(50) NOT NULL COMMENT '虚拟码',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '订单id',
  `chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `order_goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品id',
  `chain_code_status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟码状态:0-未使用; 1-已使用; 2-冻结',
  `chain_code_usetime` datetime NOT NULL COMMENT '虚拟兑换码使用时间',
  PRIMARY KEY (`chain_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='门店自提兑换码表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快照表';


DROP TABLE IF EXISTS `yf_order_goods_virtual_code`;
CREATE TABLE `yf_order_goods_virtual_code` (
  `virtual_code_id` varchar(50) NOT NULL COMMENT '虚拟码',
  `order_id` varchar(50) NOT NULL COMMENT '订单id',
  `order_goods_id` int(10) NOT NULL COMMENT '订单商品id',
  `virtual_code_status` int(10) NOT NULL DEFAULT '0' COMMENT '虚拟码状态 0:未使用 1:已使用 2:冻结',
  `virtual_code_usetime` datetime NOT NULL COMMENT '虚拟兑换码使用时间',
  PRIMARY KEY (`virtual_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='虚拟兑换码';


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


DROP TABLE IF EXISTS `yf_order_return`;
CREATE TABLE `yf_order_return` (
  `order_return_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '退货记录ID',
  `order_number` varchar(50) NOT NULL COMMENT '订单编号',
  `order_is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟订单',
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
  `return_rpt_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还平台红包金额',
  PRIMARY KEY (`order_return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='退货表';


DROP TABLE IF EXISTS `yf_order_return_reason`;
CREATE TABLE `yf_order_return_reason` (
  `order_return_reason_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_return_reason_content` varchar(255) NOT NULL COMMENT '投诉理由内容',
  `order_return_reason_sort` int(3) NOT NULL DEFAULT '225' COMMENT '投诉理由排序',
  PRIMARY KEY (`order_return_reason_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_order_settlement`;
CREATE TABLE `yf_order_settlement` (
  `os_id` varchar(255) NOT NULL COMMENT '结算单编号(年月店铺ID)',
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
  `os_redpacket_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包金额',
  `os_redpacket_return_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还红包',
  `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '地区id,0表示全国',
  `os_directseller_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销佣金总额',
  PRIMARY KEY (`os_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单结算表';


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


DROP TABLE IF EXISTS `yf_order_state`;
CREATE TABLE `yf_order_state` (
  `order_state_id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '状态id',
  `order_state_name` varchar(50) NOT NULL COMMENT '订单状态',
  `order_state_text_1` varchar(255) NOT NULL,
  `order_state_text_2` varchar(255) NOT NULL,
  `order_state_text_3` varchar(255) NOT NULL,
  `order_state_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`order_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单状态表';


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


DROP TABLE IF EXISTS `yf_platform_custom_service_type`;
CREATE TABLE `yf_platform_custom_service_type` (
  `custom_service_type_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '平台咨询类型ID',
  `custom_service_type_sort` int(3) NOT NULL DEFAULT '255' COMMENT '平台咨询类型排序',
  `custom_service_type_name` varchar(50) NOT NULL COMMENT '平台咨询类型名',
  `custom_service_type_desc` varchar(255) NOT NULL COMMENT '平台咨询类型备注',
  PRIMARY KEY (`custom_service_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='平台咨询类别表';


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


DROP TABLE IF EXISTS `yf_platform_report_subject`;
CREATE TABLE `yf_platform_report_subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报主题id',
  `subject_name` varchar(100) NOT NULL COMMENT '举报主题内容',
  `type_id` int(11) NOT NULL COMMENT '举报类型id',
  `type_name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='举报主题表';


DROP TABLE IF EXISTS `yf_platform_report_subject_type`;
CREATE TABLE `yf_platform_report_subject_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '举报类型id',
  `name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  `desc` varchar(100) NOT NULL COMMENT '举报类型描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='举报类型表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='积分礼品兑换购物车';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='积分兑换订单表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='兑换订单地址表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='兑换订单商品表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_redpacket_base`;
CREATE TABLE `yf_redpacket_base` (
  `redpacket_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '红包编号',
  `redpacket_code` varchar(32) NOT NULL COMMENT '红包编码',
  `redpacket_t_id` int(11) NOT NULL COMMENT '红包模版编号',
  `redpacket_title` varchar(50) NOT NULL COMMENT '红包标题',
  `redpacket_desc` varchar(255) NOT NULL COMMENT '红包描述',
  `redpacket_start_date` datetime NOT NULL COMMENT '红包有效期开始时间',
  `redpacket_end_date` datetime NOT NULL COMMENT '红包有效期结束时间',
  `redpacket_price` int(11) NOT NULL COMMENT '红包面额',
  `redpacket_t_orderlimit` decimal(10,2) NOT NULL COMMENT '红包使用时的订单限额',
  `redpacket_state` tinyint(4) NOT NULL COMMENT '红包状态(1-未用,2-已用,3-过期)',
  `redpacket_active_date` datetime NOT NULL COMMENT '红包发放日期',
  `redpacket_owner_id` int(11) NOT NULL COMMENT '红包所有者id',
  `redpacket_owner_name` varchar(50) NOT NULL COMMENT '红包所有者名称',
  `redpacket_order_id` varchar(500) NOT NULL COMMENT '使用该红包的订单编号',
  PRIMARY KEY (`redpacket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包表';


DROP TABLE IF EXISTS `yf_redpacket_template`;
CREATE TABLE `yf_redpacket_template` (
  `redpacket_t_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '红包模版编号',
  `redpacket_t_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '红包类型，1-新人注册红包，2-普通红包，默认2',
  `redpacket_t_title` varchar(50) NOT NULL COMMENT '红包模版名称',
  `redpacket_t_desc` varchar(255) NOT NULL COMMENT '红包模版描述',
  `redpacket_t_start_date` datetime NOT NULL COMMENT '红包模版有效期开始时间',
  `redpacket_t_end_date` datetime NOT NULL COMMENT '红包模版有效期结束时间',
  `redpacket_t_price` int(10) NOT NULL COMMENT '红包模版面额',
  `redpacket_t_orderlimit` decimal(10,2) NOT NULL COMMENT '红包使用时的消费限额',
  `redpacket_t_state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '红包模版状态(1-有效,2-失效)',
  `redpacket_t_total` int(10) NOT NULL COMMENT '模版可发放的红包总数',
  `redpacket_t_giveout` int(10) NOT NULL COMMENT '模版已发放的红包数量',
  `redpacket_t_used` int(10) NOT NULL COMMENT '模版已经使用过的红包',
  `redpacket_t_add_date` datetime NOT NULL COMMENT '模版的创建时间',
  `redpacket_t_update_date` datetime NOT NULL COMMENT '模版的最后修改时间',
  `redpacket_t_points` int(10) NOT NULL DEFAULT '0' COMMENT '兑换所需积分',
  `redpacket_t_eachlimit` int(10) NOT NULL DEFAULT '1' COMMENT '每人限领张数',
  `redpacket_t_user_grade_limit` tinyint(4) NOT NULL DEFAULT '1' COMMENT '领取红包的用户等级限制',
  `redpacket_t_img` varchar(200) NOT NULL COMMENT '红包图片',
  `redpacket_t_access_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '红包领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取',
  `redpacket_t_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐状态，0-为不推荐，1-推荐',
  PRIMARY KEY (`redpacket_t_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包模版表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_report_subject`;
CREATE TABLE `yf_report_subject` (
  `report_subject_id` int(10) NOT NULL AUTO_INCREMENT,
  `report_subject_name` varchar(50) NOT NULL,
  `report_type_id` int(10) NOT NULL,
  `report_type_name` varchar(50) NOT NULL,
  PRIMARY KEY (`report_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_report_type`;
CREATE TABLE `yf_report_type` (
  `report_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `report_type_name` varchar(50) NOT NULL,
  `report_type_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`report_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


DROP TABLE IF EXISTS `yf_search_word`;
CREATE TABLE `yf_search_word` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `search_keyword` varchar(80) DEFAULT NULL,
  `search_char_index` varchar(80) DEFAULT NULL,
  `search_nums` int(11) DEFAULT '0',
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='搜索热门词';


DROP TABLE IF EXISTS `yf_seller_base`;
CREATE TABLE `yf_seller_base` (
  `seller_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '卖家id',
  `seller_name` varchar(50) NOT NULL COMMENT '卖家用户名',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `rights_group_id` int(10) unsigned NOT NULL COMMENT '卖家组ID',
  `seller_is_admin` tinyint(3) unsigned NOT NULL COMMENT '是否管理员(0-不是 1-是)',
  `seller_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `seller_group_id` int(10) unsigned NOT NULL COMMENT '卖家组ID',
  PRIMARY KEY (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='卖家用户表';


DROP TABLE IF EXISTS `yf_seller_group`;
CREATE TABLE `yf_seller_group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '卖家组编号',
  `group_name` varchar(50) NOT NULL COMMENT '组名',
  `limits` text NOT NULL COMMENT '权限',
  `smt_limits` text NOT NULL COMMENT '消息权限范围',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卖家用户组表';


DROP TABLE IF EXISTS `yf_seller_log`;
CREATE TABLE `yf_seller_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `log_content` varchar(50) NOT NULL COMMENT '日志内容',
  `log_time` datetime NOT NULL COMMENT '日志时间',
  `log_seller_id` int(10) unsigned NOT NULL COMMENT '卖家编号',
  `log_seller_name` varchar(50) NOT NULL COMMENT '卖家帐号',
  `log_shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `log_seller_ip` varchar(50) NOT NULL COMMENT '卖家ip',
  `log_url` varchar(50) NOT NULL COMMENT '日志url',
  `log_state` tinyint(3) unsigned NOT NULL COMMENT '日志状态(0-失败 1-成功)',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卖家日志表';


DROP TABLE IF EXISTS `yf_seller_rights_base`;
CREATE TABLE `yf_seller_rights_base` (
  `rights_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限Id',
  `rights_name` varchar(20) NOT NULL DEFAULT '' COMMENT '权限名称',
  `rights_parent_id` smallint(4) unsigned NOT NULL COMMENT '权限父Id',
  `rights_remark` varchar(255) NOT NULL COMMENT '备注',
  `rights_order` smallint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`rights_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限表 ';


DROP TABLE IF EXISTS `yf_seller_rights_group`;
CREATE TABLE `yf_seller_rights_group` (
  `rights_group_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限组id',
  `rights_group_name` varchar(50) NOT NULL COMMENT '权限组名称',
  `rights_group_rights_ids` text NOT NULL COMMENT '权限列表',
  `rights_group_add_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rights_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限组表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


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
  `shop_business` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:个人入驻 1:企业入驻 ',
  `shop_create_time` datetime NOT NULL COMMENT '开店时间',
  `shop_end_time` datetime NOT NULL COMMENT '有效期截止时间',
  `shop_latitude` varchar(20) NOT NULL DEFAULT '' COMMENT '纬度',
  `shop_longitude` varchar(20) NOT NULL DEFAULT '' COMMENT '经度',
  `shop_settlement_cycle` mediumint(4) NOT NULL DEFAULT '30' COMMENT '结算周期-天为单位-如果您想设置结算周期为一个月，则可以输入30',
  `shop_settlement_last_time` datetime NOT NULL COMMENT '店铺上次结算时间，若是新开店铺没有结算单，则是开店日期',
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
  `shop_parent_id` int(11) NOT NULL COMMENT '上级店铺id-创建店铺决定，所属分销商-不可更改！ 佣金公平性考虑',
  `shop_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '店铺类型: 1-卖家店铺; 2:供应商店铺',
  `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地，使用最后一级分类',
  `shop_verify_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '审核信息备注',
  `shop_common_service` varchar(255) NOT NULL COMMENT '店铺售后服务',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺表';


DROP TABLE IF EXISTS `yf_shop_class`;
CREATE TABLE `yf_shop_class` (
  `shop_class_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '店铺分类id',
  `shop_class_name` varchar(50) NOT NULL COMMENT '店铺分类名称',
  `shop_class_deposit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金数额(元)',
  `shop_class_displayorder` smallint(3) NOT NULL DEFAULT '255' COMMENT '显示次序',
  PRIMARY KEY (`shop_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺分类表-平台设置';


DROP TABLE IF EXISTS `yf_shop_class_bind`;
CREATE TABLE `yf_shop_class_bind` (
  `shop_class_bind_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `product_class_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `commission_rate` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '百分比',
  `shop_class_bind_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `shop_class_bind_desc` varchar(255) NOT NULL COMMENT '审核拒绝原因',
  PRIMARY KEY (`shop_class_bind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺经营类目\r\n';


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
  `legal_person_electronic2` varchar(255) NOT NULL DEFAULT '' COMMENT '证件照反面',
  `legal_identity_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '证件类型 1身份证 2护照 3军官证',
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
  `company_apply_image` varchar(1024) NOT NULL COMMENT '申请扩展图片字段',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺公司信息表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消费者保障服务店铺关联表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消费者保障服务保证金缴纳日志表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='消费者保障服务类型表';


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


DROP TABLE IF EXISTS `yf_shop_customer`;
CREATE TABLE `yf_shop_customer` (
  `shop_customer_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户Id',
  `customer_number` varchar(32) NOT NULL DEFAULT '' COMMENT '客户编号',
  `customer_name` varchar(20) NOT NULL DEFAULT '' COMMENT '客户名称',
  `customer_type_id` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '客户类别',
  `customer_level_id` smallint(4) unsigned NOT NULL DEFAULT '1' COMMENT '客户等级',
  `customer_dif_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '期初往来余额',
  `customer_begin_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '余额日期',
  `customer_amount_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '期初应收款',
  `customer_period_money` decimal(11,2) NOT NULL COMMENT '期初预收款',
  `customer_tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '税率',
  `customer_remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注消息',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺id',
  `chain_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  PRIMARY KEY (`shop_customer_id`),
  KEY `customer_level_id` (`customer_level_id`),
  KEY `erpbuilder_base_customer_ibfk_1` (`customer_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺客户信息表';


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


DROP TABLE IF EXISTS `yf_shop_decoration`;
CREATE TABLE `yf_shop_decoration` (
  `decoration_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '装修编号',
  `decoration_name` varchar(50) NOT NULL COMMENT '装修名称',
  `shop_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `decoration_setting` varchar(500) NOT NULL COMMENT '装修整体设置(背景、边距等)',
  `decoration_nav` varchar(5000) NOT NULL COMMENT '装修导航',
  `decoration_banner` varchar(255) NOT NULL COMMENT '装修头部banner',
  PRIMARY KEY (`decoration_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺装修表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺装修相册表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺装修块表';


DROP TABLE IF EXISTS `yf_shop_domain`;
CREATE TABLE `yf_shop_domain` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_sub_domain` varchar(100) NOT NULL COMMENT '二级域名',
  `shop_edit_domain` int(10) NOT NULL COMMENT '编辑次数',
  `shop_self_domain` varchar(100) NOT NULL COMMENT '自定义域名',
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='二级域名表';


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


DROP TABLE IF EXISTS `yf_shop_extend`;
CREATE TABLE `yf_shop_extend` (
  `shop_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='扩展表';


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


DROP TABLE IF EXISTS `yf_shop_help`;
CREATE TABLE `yf_shop_help` (
  `shop_help_id` int(10) NOT NULL,
  `help_sort` tinyint(1) unsigned DEFAULT '255' COMMENT '排序',
  `help_title` varchar(100) NOT NULL COMMENT '标题',
  `help_info` text COMMENT '帮助内容',
  `help_url` varchar(100) DEFAULT '' COMMENT '跳转链接',
  `update_time` date NOT NULL COMMENT '更新时间',
  `page_show` tinyint(1) unsigned DEFAULT '1' COMMENT '页面类型:1为店铺,2为会员,默认为1,3为供应商',
  PRIMARY KEY (`shop_help_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


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
  `district_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '所在地，使用最后一级分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='续费申请表\r\n';


DROP TABLE IF EXISTS `yf_shop_service`;
CREATE TABLE `yf_shop_service` (
  `shop_service_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) unsigned NOT NULL COMMENT '服务id',
  `shop_service_price` float(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`shop_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加入的消费者保障服务表';


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


DROP TABLE IF EXISTS `yf_shop_supplier`;
CREATE TABLE `yf_shop_supplier` (
  `supplier_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '供货商id',
  `shop_id` int(10) NOT NULL COMMENT '店铺id',
  `supplier_name` varchar(50) NOT NULL COMMENT '供货商名称',
  `contacts` varchar(50) NOT NULL COMMENT '联系人',
  `contacts_tel` varchar(12) NOT NULL COMMENT '联系电话',
  `remarks` text NOT NULL COMMENT '备注信息',
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='供货商表\r\n';


DROP TABLE IF EXISTS `yf_shop_template`;
CREATE TABLE `yf_shop_template` (
  `shop_temp_name` varchar(100) NOT NULL COMMENT '店铺模板名称  --根据模板名称来找寻对应的文件',
  `shop_style_name` varchar(255) NOT NULL COMMENT '风格名称',
  `shop_temp_img` varchar(255) NOT NULL COMMENT '模板对应的图片',
  PRIMARY KEY (`shop_temp_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='店铺模板表';


DROP TABLE IF EXISTS `yf_sub_site`;
CREATE TABLE `yf_sub_site` (
  `subsite_id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sub_site_parent_id` int(11) NOT NULL COMMENT '父id',
  `sub_site_name` varchar(60) NOT NULL DEFAULT '' COMMENT '分站名称',
  `sub_site_domain` varchar(20) NOT NULL DEFAULT '' COMMENT '分站域名前缀',
  `sub_site_district_ids` varchar(20480) NOT NULL DEFAULT '' COMMENT '地区id， 逗号分隔',
  `sub_site_logo` varchar(255) NOT NULL COMMENT 'logo',
  `sub_site_copyright` text NOT NULL COMMENT '版权',
  `sub_site_template` varchar(50) NOT NULL COMMENT '模板',
  `sub_site_is_open` int(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
  `sub_site_des` text NOT NULL COMMENT '描述',
  `sub_site_web_title` varchar(100) NOT NULL COMMENT 'SEO标题',
  `sub_site_web_keyword` varchar(100) NOT NULL COMMENT 'SEO关键字',
  `sub_site_web_des` varchar(100) NOT NULL COMMENT 'SEO描述',
  `district_child_ids` text NOT NULL COMMENT '地区的最底层id',
  PRIMARY KEY (`subsite_id`),
  KEY `domain` (`sub_site_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='城市分站表';


DROP TABLE IF EXISTS `yf_test`;
CREATE TABLE `yf_test` (
  `test_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '数组下标',
  `test_name` varchar(255) NOT NULL COMMENT '数组值',
  `test_sax` varchar(255) NOT NULL COMMENT '数组值',
  PRIMARY KEY (`test_id`),
  KEY `index` (`test_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站配置表';


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


DROP TABLE IF EXISTS `yf_transport_offpay_area`;
CREATE TABLE `yf_transport_offpay_area` (
  `offpay_area_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `offpay_area_city_ids` text NOT NULL COMMENT '区域城市id-需要特别处理，快速查询-'',''分割',
  PRIMARY KEY (`offpay_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='到付区域。';


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


DROP TABLE IF EXISTS `yf_user_base`;
CREATE TABLE `yf_user_base` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_number` varchar(50) NOT NULL DEFAULT '' COMMENT '用户编号',
  `user_account` varchar(50) NOT NULL DEFAULT '' COMMENT '用户帐号',
  `user_passwd` char(50) NOT NULL DEFAULT '' COMMENT '密码：使用用户中心-此处废弃',
  `user_key` char(32) NOT NULL DEFAULT '' COMMENT '用户Key',
  `user_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否被封禁，0：未封禁，1：封禁',
  `user_login_times` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '登录次数',
  `user_login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `user_login_ip` varchar(30) NOT NULL COMMENT '最后登录ip',
  `user_parent_id` int(10) unsigned NOT NULL COMMENT '上级用户id - 注册决定，不可更改，推广公平性考虑',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_account` (`user_account`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户基础信息表';


DROP TABLE IF EXISTS `yf_user_buy`;
CREATE TABLE `yf_user_buy` (
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `common_id` int(10) NOT NULL COMMENT '商品commonid',
  `buy_num` int(10) DEFAULT '0' COMMENT '用户购买数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户购买商品数量表';


DROP TABLE IF EXISTS `yf_user_extend`;
CREATE TABLE IF NOT EXISTS `yf_user_extend` (
  `user_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Meta id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_meta_key` varchar(255) NOT NULL COMMENT '键',
  `user_meta_value` longtext NOT NULL COMMENT '值',
  `user_meta_datatype` enum('string','json','number') NOT NULL DEFAULT 'string' COMMENT '数据类型',
  PRIMARY KEY (`user_meta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`user_meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户数据扩展表' AUTO_INCREMENT=1 ;
 
  
DROP TABLE IF EXISTS `yf_user_favorites_brand`;
CREATE TABLE `yf_user_favorites_brand` (
  `favorites_brand_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `brand_id` int(10) NOT NULL COMMENT '品牌id',
  `favorites_brand_time` datetime NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`favorites_brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='收藏品牌';


DROP TABLE IF EXISTS `yf_user_favorites_goods`;
CREATE TABLE `yf_user_favorites_goods` (
  `favorites_goods_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `favorites_goods_time` datetime NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`favorites_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='收藏的商品';


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


DROP TABLE IF EXISTS `yf_user_footprint`;
CREATE TABLE `yf_user_footprint` (
  `footprint_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `common_id` int(10) NOT NULL COMMENT '商品id',
  `footprint_time` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`footprint_id`),
  KEY `user_id` (`user_id`,`common_id`) COMMENT '(null)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='商品访问足迹表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='好友表';


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
  `user_grade_rate` float(5,1) NOT NULL DEFAULT '0.0' COMMENT '折扣率',
  `user_grade_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户等级表';


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
  `user_sex` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户性别 0女 1男 2保密',
  `user_birthday` date NOT NULL,
  `user_mobile_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机验证0没验证1验证',
  `user_email_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证0没验证1验证',
  `user_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金-废除',
  `user_freeze_cash` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结资金-废除',
  `user_provinceid` int(11) NOT NULL,
  `user_cityid` int(11) NOT NULL,
  `user_areaid` int(11) NOT NULL,
  `user_area` varchar(255) NOT NULL,
  `user_logo` varchar(255) NOT NULL DEFAULT '',
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
  `user_am` varchar(500) NOT NULL COMMENT '系统公告查看过id',
  `user_parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户上级ID',
  `user_directseller_commission` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户获得的分销总佣金',
  `user_directseller_shop` varchar(255) DEFAULT NULL COMMENT '分销小店名称',
  `user_bt_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '白条审核状态：0未审核1待审核2审核成功3审核失败',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户信息表';


DROP TABLE IF EXISTS `yf_user_message`;
CREATE TABLE `yf_user_message` (
  `user_message_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户消息id',
  `user_message_receive_id` int(10) NOT NULL COMMENT '接收id',
  `user_message_receive` varchar(50) NOT NULL COMMENT '接收者用户',
  `user_message_send_id` int(10) NOT NULL COMMENT '发送者id',
  `user_message_send` varchar(50) NOT NULL COMMENT '发送者',
  `user_message_content` text NOT NULL COMMENT '发送内容',
  `user_message_pid` int(10) NOT NULL COMMENT '回复消息上级id',
  `message_islook` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否读取0未1读取',
  `user_message_time` datetime NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`user_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户消息表';


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


DROP TABLE IF EXISTS `yf_user_sub_user`;
CREATE TABLE `yf_user_sub_user` (
  `sub_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '子账号用户id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属主账号id',
  `sub_user_active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否激活',
  PRIMARY KEY (`sub_user_id`),
  KEY `user_main_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='买家子账号表';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户标签表';


DROP TABLE IF EXISTS `yf_user_tag_rec`;
CREATE TABLE `yf_user_tag_rec` (
  `tag_rec_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '兴趣标签id',
  `user_tag_id` int(10) NOT NULL COMMENT '标签id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `tag_rec_time` datetime NOT NULL COMMENT '选择标签时间',
  PRIMARY KEY (`tag_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户兴趣标签表';


DROP TABLE IF EXISTS `yf_user_type`;
CREATE TABLE `yf_user_type` (
  `user_type_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户类别Id',
  `user_type_name` varchar(20) NOT NULL DEFAULT '' COMMENT '客户类别名称',
  `user_type_remark` varchar(50) NOT NULL DEFAULT '' COMMENT '客户类别注释',
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户分类表';


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


DROP TABLE IF EXISTS `yf_voucher_price`;
CREATE TABLE `yf_voucher_price` (
  `voucher_price_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '代金券面值编号',
  `voucher_price` int(11) NOT NULL COMMENT '代金券面值',
  `voucher_price_describe` varchar(255) NOT NULL COMMENT '代金券描述',
  `voucher_defaultpoints` int(11) DEFAULT '0' COMMENT '代金券默认的兑换所需积分，可以为0',
  PRIMARY KEY (`voucher_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='代金券面额表';


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


-- 2017-05-26 11:09:34