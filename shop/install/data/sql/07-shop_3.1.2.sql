 
 -- 2017-05-26 初始化首页导航
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('1','0','0','首页','index.php','0','0','1','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('2','0','0','品牌列表','index.php?ctl=Goods_Brand','0','1','2','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('3','0','0','团购中心','index.php?ctl=GroupBuy&met=index','0','0','3','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('4','0','0','商家中心','index.php?ctl=Seller_Index&forward_self=1','0','0','5','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('5','3','0','积分商城','index.php?ctl=Points&met=index','0','0','4','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('6','0','0','平台红包','index.php?ctl=RedPacket&met=redPacket','0','0','9','1','0');
INSERT INTO `yf_platform_nav` (`nav_id`, `nav_type`, `nav_item_id`, `nav_title`, `nav_url`, `nav_location`, `nav_new_open`, `nav_displayorder`, `nav_active`, `nav_readonly`) VALUES('7','0','0','商家店铺','index.php?ctl=Shop_Index&met=index&typ=e&keywords=','0','0','8','1','0');

-- 2017-05-26 初始化店铺等级
insert into `yf_shop_grade` (`shop_grade_id`, `shop_grade_name`, `shop_grade_fee`, `shop_grade_desc`, `shop_grade_goods_limit`, `shop_grade_album_limit`, `shop_grade_template`, `shop_grade_function_id`, `shop_grade_sort`) values('1','普通店铺','99','22','0','0','default','0','1');


BEGIN;
INSERT INTO `yf_message_template` (`id`, `code`, `name`, `title`, `content_email`, `type`, `is_phone`, `is_email`, `is_mail`, `content_mail`, `content_phone`, `force_phone`, `force_email`, `force_mail`, `mold`) VALUES ('31', 'Self pick up code', '自提码', '[weburl_name]提醒：自提码获取', '<p>[weburl_name]提醒：<br /><br />尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。<br /><br /><br />                                                                                                      [user_name]<br />                                                                                                      [date]</p >', '1', '1', '0', '0', '尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。', '尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。', '1', '0', '0', '1');
COMMIT;

-- 2017-6-14  分销商品字段类型修改
alter table yf_goods_common modify column common_cps_rate decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分佣比例';
alter table yf_goods_common modify column common_second_cps_rate decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分佣比例';
alter table yf_goods_common modify column common_third_cps_rate decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分佣比例';