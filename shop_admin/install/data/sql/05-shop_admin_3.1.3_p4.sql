-- 拼团
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES('19010','17000','拼团管理','','15000','','','','商品拼团促销活动相关设定及管理','0','0000-00-00 00:00:00');
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('19011', '19010', '拼团列表', '', '15000', 'Promotion_PinTuan', 'list', '', '<li>商家发布的拼团活动列表</li>\n            <li>取消操作不可恢复，请慎重操作</li>\n            <li>点击详细链接查看活动详细信息</li>', '0', '0000-00-00 00:00:00');
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('19012', '19010', '套餐列表', '', '15000', 'Promotion_PinTuan', 'comboList', '', '<li>商家的拼团套餐列表</li>', '0', '0000-00-00 00:00:00');
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('19013', '19010', '设置', '', '15000', 'Config', 'pintuan', 'config_type%5B%5D=pintuan', '<li>拼团价格设置</li>', '0', '0000-00-00 00:00:00');

INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('19014', '19010', '首页幻灯片', '', '15000', 'Config', 'pintuan_slider', 'config_type%5B%5D=pintuan_slider', '<li>该组幻灯片滚动图片应用于拼团聚合页上部使用，最多可上传4张图片。</li>\n              <li>图片要求使用宽度为1043像素，高度为396像素jpg/gif/png格式的图片。</li>\n              <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>\n              <li>清空操作将删除聚合页上的滚动图片，请注意操作</li>', '0', '0000-00-00 00:00:00');
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('19015', '19010', '规则描述', '', '15000', 'Config', 'pintuan_rules', 'config_type%5B%5D=pintuan_rules', '拼团规则描述', '0', '0000-00-00 00:00:00');


UPDATE `yf_admin_menu` SET `menu_parent_id` = '0' WHERE `yf_admin_menu`.`menu_id` = 19012;

UPDATE `yf_admin_menu` SET `menu_parent_id` = '0' WHERE `yf_admin_menu`.`menu_id` = 19013;









