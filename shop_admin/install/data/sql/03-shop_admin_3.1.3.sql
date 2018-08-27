-- 添加自营店铺设置功能
insert into `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) values('8360','自营店铺设置_查看','85','自营店铺设置_查看','100');
insert into `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) values('13027','13008','自营店铺设置','','8360','Config','selfShop','config_type%5B%5D=sub_site_self_shop','<li>设置自营店铺在商城是否显示</li>','50','2017-06-15 09:49:24');

-- 2017/7/24 供应商后台目录权限
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('139','供应商管理','0','供应商管理','50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('140','供应商入驻','0','供应商入驻','50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('141','供应商模板','0','供应商模板','50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('17000','显示主目录','139','供应商管理_显示主目录','50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('17100','显示主目录','140','供应商入驻_显示主目录','50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES('17200','显示主目录','141','供应商模板_显示主目录','50');

UPDATE `yf_admin_menu` SET rights_id = 17000 WHERE menu_id=13022;
UPDATE `yf_admin_menu` SET rights_id = 17100 WHERE menu_id=13024;
UPDATE `yf_admin_menu` SET rights_id = 17200 WHERE menu_id=13025;