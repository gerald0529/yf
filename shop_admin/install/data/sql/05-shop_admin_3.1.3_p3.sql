-- 2017/10/10运单模板合并到快递公司中
UPDATE `yf_admin_menu` SET `menu_parent_id` = '11009',`menu_url_ctl` = 'Logistics_Waybill' , `menu_url_met` = 'tpl',`menu_url_note` = '此模块的运单模板，商家可选择性使用' WHERE `menu_id` = '11010';
DELETE FROM `yf_admin_rights_base` WHERE `rights_id` = '56'; 
DELETE FROM `yf_admin_rights_base` WHERE `rights_id` = '9900'; 