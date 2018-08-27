delete from `yf_admin_menu` where `menu_id`='13100';
delete from `yf_admin_menu` where `menu_id`='13022';
delete from `yf_admin_menu` where `menu_id`='13023';
delete from `yf_admin_menu` where `menu_id`='13024';
delete from `yf_admin_menu` where `menu_id`='15601';
-- SNS删除
delete from `yf_admin_menu` where `menu_id`='11052';
delete from `yf_admin_menu` where `menu_id`='13005';
delete from `yf_admin_menu` where `menu_id`='13006';
delete from `yf_admin_menu` where `menu_id`='11034';
delete from `yf_admin_menu` where `menu_id`='11035';
delete from `yf_admin_menu` where `menu_id`='14004';
delete from `yf_admin_menu` where `menu_id`='11024';
delete from `yf_admin_menu` where `menu_id`='16006';
delete from `yf_admin_menu` where `menu_id`='11037';

update `yf_admin_menu` set `menu_url_met`='index' where `menu_url_met` = 'Index';

-- 更新系统管理员权限
UPDATE `yf_admin_rights_group` SET `rights_group_rights_ids`='[3000,3110,3140,3130,3120,3170,3160,3190,3180,10400,3200,3230,3210,3220,3260,3270,3240,3250,3310,3300,3280,3290,10000,10900,3330,3320,3340,3350,3360,3370,3380,3390,3400,3371,11000,11200,3440,3430,3420,3410,3710,3700,14300,16020,16030,14400,16000,16010,3730,3820,3750,3810,3770,3780,3790,3720,3760,3830,3840,3850,3860,3740,3800,3870,3880,3910,3900,3940,3930,3920,3890,3970,3960,3950,3980,14500,14600,4040,4030,4020,4010,4000,3990,4060,4070,4050,4080,4090,4100,4110,4130,4120,14700,14800,16040,4160,4220,4210,4200,4190,4180,4170,4150,4140,4270,4260,4280,4290,4300,4310,4320,4330,4240,4230,4250,14900,13500,4410,4400,4390,4420,4380,4370,4360,4350,4340,11100,11600,8910,8900,8890,8880,5020,5000,5010,5030,9400,9500,5050,5040,5060,5070,5090,5130,5120,5080,5110,5100,5150,5140,9600,9700,5160,5170,5230,5220,5210,5200,5190,5180,9800,9900,12300,5240,5250,5300,5270,5290,5280,5260,15600,12400,5340,5310,5320,5330,5350,5390,5380,5360,5370,12600,12700,5400,8110,8120,8100,8090,8130,8140,8150,8160,8170,8180,8080,8070,8060,8000,8010,8020,8030,8040,8050,11700,11800,8230,8220,8210,8200,8190,8270,8260,8240,8250,11900,12000,8310,8300,8280,8290,12100,12200,8350,8340,8320,8330,8500,8510,13600,13700,8520,8530,8550,8540,13800,13900,8590,8600,8610,8620,8630,8580,8570,8560,8660,8650,8640,8670,14000,14100,8690,8700,8710,8680,8840,8790,8800,8810,8820,8830,8870,8860,8850,8780,8770,8760,8750,8740,8730,8720,14200,9000,9100,9200,9300,10100,10200,3010,3020,3040,3060,3070,3080,3090,3030,10300,10500,10600,10700,10800,11300,11400,11500,12500,12800,12900,15500,13000,13100,13200,13300,13400,15400,15000,15100,15200,15300]' WHERE `rights_group_id`=1;

UPDATE `yf_admin_rights_group` SET `rights_group_rights_ids`='[3320,3360,3370,3410,16030,14400,3850,3860,8110,8120,8100,8090,8130,8140,8150,8160,8170,8180,8080,8070,8060,8000,8010,8020,8030,8040,8050,11700,8240,8500,8510,13600,13700,8520,8530,8550,8540,13800,13900,8590,8600,8610,8620,8630,8580,8570,8560,8660,8650,8640,8670,14000,9200,10100,10800,12800,12900]' WHERE `rights_group_id`=2;



INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('17033', '17006', '积分商城首页图片', '', '16040', 'Config', 'promimg', 'config_type%5B%5D=promotiom_img', '<li>设置积分商城首页图片</li>', '0', '0000-00-00 00:00:00');

INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('13026', '13007', '入驻设置', '', '0', 'Config', 'join_setting', 'config_type%5B%5D=join_setting', '<li>入驻设置</li>', '50', '0000-00-00 00:00:00');


INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES ('16040', '积分商城首页图片设置', '19', '积分兑换', '220');

-- 添加菜单说明默认数据
UPDATE `yf_admin_menu` SET menu_url_note='系统工具' WHERE menu_id='11003' AND menu_name='系统工具';
UPDATE `yf_admin_menu` SET menu_url_note='数据管理' WHERE menu_id='11004' AND menu_name='数据管理';
UPDATE `yf_admin_menu` SET menu_url_note='SEO设置' WHERE menu_id='11007' AND menu_name='SEO设置';
UPDATE `yf_admin_menu` SET menu_url_note='分站管理' WHERE menu_id='11017' AND menu_name='管理分站基本信息';
UPDATE `yf_admin_menu` SET menu_url_note='类型管理' WHERE menu_id='12004' AND menu_name='类型管理';

-- 补充团购管理权限数据
insert into `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) values('16000','虚拟团购地区_查看','14','团购管理','50');
insert into `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) values('16010','团购设置_查看','14','团购管理','50');
insert into `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) values('16020','已开通店铺_查看','14','团购管理','50');
insert into `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) values('16030','首页幻灯片_查看','14','团购管理','50');


UPDATE `yf_admin_menu` SET rights_id=16000 WHERE menu_id=17014 AND menu_name='虚拟团购地区';

UPDATE `yf_admin_menu` SET rights_id=16010 WHERE menu_id=17015 AND menu_name='团购设置';

UPDATE `yf_admin_menu` SET rights_id=16020 WHERE menu_id=17016 AND menu_name='已开通店铺';

UPDATE `yf_admin_menu` SET rights_id=16030 WHERE menu_id=17013 AND menu_name='首页幻灯片';

INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('16020', '16000', '问题反馈', '', '14210', '', '', '', '问题反馈查看', '50', '0000-00-00 00:00:00');
INSERT INTO `yf_admin_menu` (`menu_id`, `menu_parent_id`, `menu_name`, `menu_icon`, `rights_id`, `menu_url_ctl`, `menu_url_met`, `menu_url_parem`, `menu_url_note`, `menu_order`, `menu_time`) VALUES ('16021', '16020', '问题反馈', '', '16050', 'Feed', 'index', '', '', '50', '0000-00-00 00:00:00');

INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES ('107', '问题反馈', '0', '问题反馈', '50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES ('14210', '显示主目录', '107', '问题反馈_显示主目录', '50');
INSERT INTO `yf_admin_rights_base` (`rights_id`, `rights_name`, `rights_parent_id`, `rights_remark`, `rights_order`) VALUES ('16050', '问题反馈_查看', '107', '问题反馈', '50');
