<?php
$buyer_menu = array(
	
	10000 => array(
		'menu_id' => '10000',
		'menu_parent_id' => '-1',
		'menu_name' => __('首页'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Buyer_Index',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
					100001 => array(
						'menu_id' => '100001',
						'menu_parent_id' => '10000',
						'menu_name' => __('交易中心'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Buyer_Order',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
						'sub' => array(
									1000011 => array(
										'menu_id' => '1000011',
										'menu_parent_id' => '100001',
										'menu_name' => __('我的订单'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Order',
										'menu_url_met' => 'physical',
										'menu_url_parem' => '',
										'sub' => array(
													10000111 => array(
														'menu_id' => '10000111',
														'menu_parent_id' => '1000011',
														'menu_name' => __('我的订单'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Order',
														'menu_url_met' => 'physical',
														'menu_url_parem' => '',
													),
										),
									),
									1000012 => array(
										'menu_id' => '1000012',
										'menu_parent_id' => '100001',
										'menu_name' => __('虚拟兑换订单'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Order',
										'menu_url_met' => 'virtual',
										'menu_url_parem' => '',
										'sub' => array(
													10000121 => array(
														'menu_id' => '10000121',
														'menu_parent_id' => '1000012',
														'menu_name' => __('虚拟兑换订单'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Order',
														'menu_url_met' => 'virtual',
														'menu_url_parem' => '',
													)
										),
									),
                                    1000014 => array(
                                        'menu_id' => '1000014',
                                        'menu_parent_id' => '100001',
                                        'menu_name' => __('门店自提订单'),
                                        'menu_icon' => '',
                                        'menu_url_ctl' => 'Buyer_Order',
                                        'menu_url_met' => 'chain',
                                        'menu_url_parem' => '',
                                        'sub' => array(
                                            10000141 => array(
                                                'menu_id' => '10000141',
                                                'menu_parent_id' => '1000014',
                                                'menu_name' => __('门店自提订单'),
                                                'menu_icon' => '',
                                                'menu_url_ctl' => 'Buyer_Order',
                                                'menu_url_met' => 'chain',
                                                'menu_url_parem' => '',
                                            )
                                        ),
                                    ),
									1000013 => array(
										'menu_id' => '1000013',
										'menu_parent_id' => '100001',
										'menu_name' => __('交易评价/晒单'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Order',
										'menu_url_met' => 'evaluation',
										'menu_url_parem' => '',
										'sub' => array(
													10000131 => array(
														'menu_id' => '10000131',
														'menu_parent_id' => '1000013',
														'menu_name' => __('交易评价'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Order',
														'menu_url_met' => 'evaluation',
														'menu_url_parem' => '',
													)
										),
									)
						),
					),
					100002 => array(
						'menu_id' => '100002',
						'menu_parent_id' => '10000',
						'menu_name' => __('关注中心'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Buyer_Favorites',
						'menu_url_met' => 'favoritesGoods',
						'menu_url_parem' => '',
						'sub' => array(
									1000021 => array(
										'menu_id' => '1000021',
										'menu_parent_id' => '100002',
										'menu_name' => __('我的收藏'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Favorites',
										'menu_url_met' => 'favoritesGoods',
										'menu_url_parem' => '',
										'sub' => array(
													10000211 => array(
														'menu_id' => '10000211',
														'menu_parent_id' => '1000021',
														'menu_name' => __('商品收藏'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Favorites',
														'menu_url_met' => 'favoritesGoods',
														'menu_url_parem' => '',
													),
													10000212 => array(
														'menu_id' => '10000212',
														'menu_parent_id' => '1000021',
														'menu_name' => __('店铺收藏'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Favorites',
														'menu_url_met' => 'favoritesShop',
														'menu_url_parem' => '',
													),
													/* 10000213 => array(
														'menu_id' => '10000213',
														'menu_parent_id' => '1000021',
														'menu_name' => __('品牌收藏'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_favorites',
														'menu_url_met' => 'favoritesBrand',
														'menu_url_parem' => '',
													) */
										),
									),

									1000023 => array(
										'menu_id' => '1000023',
										'menu_parent_id' => '100002',
										'menu_name' => __('我的足迹'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Favorites',
										'menu_url_met' => 'footprint',
										'menu_url_parem' => '',
										'sub' => array(
													10000231 => array(
														'menu_id' => '10000231',
														'menu_parent_id' => '1000023',
														'menu_name' => __('我的足迹'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Favorites',
														'menu_url_met' => 'footprint',
														'menu_url_parem' => '',
													)
										),
									)
						),
					),
					100003 => array(
						'menu_id' => '100003',
						'menu_parent_id' => '10000',
						'menu_name' => __('客户服务'),
						'menu_icon' => '',
						'menu_url_ctl' => '',
						'menu_url_met' => '',
						'menu_url_parem' => '',
						'sub' => array(
							1000031 => array(
								'menu_id' => '1000031',
								'menu_parent_id' => '100003',
								'menu_name' => __('退款及退货'),
								'menu_icon' => '',
								'menu_url_ctl' => 'Buyer_Service_Return',
								'menu_url_met' => 'index',
								'menu_url_parem' => '',
							),
							1000032 => array(
								'menu_id' => '1000032',
								'menu_parent_id' => '100003',
								'menu_name' => __('交易投诉'),
								'menu_icon' => '',
								'menu_url_ctl' => 'Buyer_Service_Complain',
								'menu_url_met' => 'index',
								'menu_url_parem' => '',
							),
							1000033 => array(
								'menu_id' => '1000033',
								'menu_parent_id' => '100003',
								'menu_name' => __('商品咨询'),
								'menu_icon' => '',
								'menu_url_ctl' => 'Buyer_Service_Consult',
								'menu_url_met' => 'index',
								'menu_url_parem' => '',
							),
							1000034 => array(
								'menu_id' => '1000034',
								'menu_parent_id' => '100003',
								'menu_name' => __('违规举报'),
								'menu_icon' => '',
								'menu_url_ctl' => 'Buyer_Service_Report',
								'menu_url_met' => 'index',
								'menu_url_parem' => '',
							),
							1000035 => array(
								'menu_id' => '1000035',
								'menu_parent_id' => '100003',
								'menu_name' => __('平台客服'),
								'menu_icon' => '',
								'menu_url_ctl' => 'Buyer_Service_Custom',
								'menu_url_met' => 'index',
								'menu_url_parem' => '',
							),
						),
					),

					100004 => array(
						'menu_id' => '100004',
						'menu_parent_id' => '10000',
						'menu_name' => __('会员中心'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Buyer_User',
						'menu_url_met' => 'getUserGrade',
						'menu_url_parem' => '',
						'sub' => array(
									1000041 => array(
										'menu_id' => '1000041',
										'menu_parent_id' => '100004',
										'menu_name' => __('会员级别'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'getUserGrade',
										'menu_url_parem' => '',
										'sub' => array(
													/*
													10000411 => array(
														'menu_id' => '10000411',
														'menu_parent_id' => '1000041',
														'menu_name' => __('基本信息'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_User',
														'menu_url_met' => 'getUserInfo',
														'menu_url_parem' => '',

													),
													10000412 => array(
														'menu_id' => '10000412',
														'menu_parent_id' => '1000041',
														'menu_name' => __('头像照片'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_User',
														'menu_url_met' => 'getUserImg',
														'menu_url_parem' => '',
													),
													*/
													10000413 => array(
														'menu_id' => '10000413',
														'menu_parent_id' => '1000041',
														'menu_name' => __('我的级别'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_User',
														'menu_url_met' => 'getUserGrade',
														'menu_url_parem' => '',
													),
													10000414 => array(
														'menu_id' => '10000414',
														'menu_parent_id' => '1000041',
														'menu_name' => __('兴趣标签'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_User',
														'menu_url_met' => 'tag',
														'menu_url_parem' => '',
													)
										),
									),
									1000242 => array(
										'menu_id' => '1000242',
										'menu_parent_id' => '100004',
										'menu_name' => __('会员信息'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'linkUserInfo',
										'menu_url_parem' => '',
										),
									1000042 => array(
										'menu_id' => '1000042',
										'menu_parent_id' => '100004',
										'menu_name' => __('账户安全'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'security',
										'menu_url_parem' => '',
										'sub' => array(
													10000411 => array(
														'menu_id' => '10000411',
														'menu_parent_id' => '1000042',
														'menu_name' => __('账户安全'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_User',
														'menu_url_met' => 'security',
														'menu_url_parem' => '',
													),
										),
									),

									1000142 => array(
										'menu_id' => '1000142',
										'menu_parent_id' => '100004',
										'menu_name' => __('密码修改'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'passwd',
										'menu_url_parem' => '',
									),

									1000043 => array(
										'menu_id' => '1000043',
										'menu_parent_id' => '100004',
										'menu_name' => __('收货地址'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'address',
										'menu_url_parem' => '',

									),
									1000044 => array(
										'menu_id' => '1000044',
										'menu_parent_id' => '100004',
										'menu_name' => __('我的消息'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Message',
										'menu_url_met' => 'message',
										'menu_url_parem' => '',

									),
									1000045 => array(
										'menu_id' => '1000045',
										'menu_parent_id' => '100004',
										'menu_name' => __('我的好友'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'friend',
										'menu_url_parem' => '',

									),
									1000046 => array(
										'menu_id' => '1000046',
										'menu_parent_id' => '100004',
										'menu_name' => __('子账号设置'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_User',
										'menu_url_met' => 'getSubUser',
										'menu_url_parem' => '',

									),
						),
					),
					100005 => array(
						'menu_id' => '100005',
						'menu_parent_id' => '10000',
						'menu_name' => __('财产中心'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Buyer_Property',
						'menu_url_met' => 'cash',
						'menu_url_parem' => '',
						'sub' => array(

									1000052 => array(
										'menu_id' => '1000052',
										'menu_parent_id' => '100005',
										'menu_name' => __('我的代金券'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Voucher',
										'menu_url_met' => 'voucher',
										'menu_url_parem' => '',
										'sub' => array(
													10000521 => array(
														'menu_id' => '10000521',
														'menu_parent_id' => '1000052',
														'menu_name' => __('我的代金券'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Voucher',
														'menu_url_met' => 'voucher',
														'menu_url_parem' => '',

													),

										),
									),
									1000053 => array(
										'menu_id' => '1000053',
										'menu_parent_id' => '100005',
										'menu_name' => __('我的红包'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_RedPacket',
										'menu_url_met' => 'redPacket',
										'menu_url_parem' => '',
									),
									1000054 => array(
										'menu_id' => '1000054',
										'menu_parent_id' => '100005',
										'menu_name' => __('我的积分'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Points',
										'menu_url_met' => 'points',
										'menu_url_parem' => '',

									),
                                    1000055 => array(
										'menu_id' => '1000055',
										'menu_parent_id' => '100005',
										'menu_name' => __('我的白条'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Buyer_Index',
										'menu_url_met' => 'btpage',
										'menu_url_parem' => '',

									),

						),
					),
				),
	),

);

$User_SubUserModel = new User_SubUserModel();

$user = $User_SubUserModel->getByWhere(array('user_id'=>Perm::$userId));

if($user)
{
	$buyer_menu['10000']['sub']['100001']['sub']['1000011']['sub']['10000112'] = array(
														'menu_id' => '10000112',
														'menu_parent_id' => '1000011',
														'menu_name' => __('我的采购单'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Order',
														'menu_url_met' => 'subPhysical',
														'menu_url_parem' => '',
													);
	$buyer_menu['10000']['sub']['100001']['sub']['1000012']['sub']['10000122'] = array(
														'menu_id' => '10000122',
														'menu_parent_id' => '1000012',
														'menu_name' => __('我的采购单'),
														'menu_icon' => '',
														'menu_url_ctl' => 'Buyer_Order',
														'menu_url_met' => 'subVirtual',
														'menu_url_parem' => '',
													);
}

//行
global $buyer_menu_rows;
$buyer_menu_rows = array();


function get_menu_rows($buyer_menu, &$buyer_menu_rows)
{
	foreach ($buyer_menu as $id=>$item)
	{
		if (isset($item['sub']) && $item['sub'])
		{
			get_menu_rows($item['sub'], $buyer_menu_rows);

			unset($item['sub']);
			$buyer_menu_rows[$id] = $item;
		}
		else
		{
			$buyer_menu_rows[$id] = $item;
		}

	}
}

 get_menu_rows($buyer_menu, $buyer_menu_rows);


$ctl       = request_string('ctl');
$met       = request_string('met');
$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";
function get_menu_id($buyer_menu, $level = 0, &$level_row, $ctl, $met)
{
	global $buyer_menu_rows;

	$level++;

	foreach ($buyer_menu as $menu_row)
	{
		if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met)
		{
			$level_row[$ctl][$met][$level]     = $menu_row['menu_id'];
			$level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

			//向上查找一次
			if (isset($buyer_menu_rows[$menu_row['menu_parent_id']]))
			{
				$level_row[$ctl][$met][$level - 2] = $buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
			}
			//向上查再找一次
			 // if (isset($buyer_menu_rows[$buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id']]['menu_parent_id']))
			// {
				// $level_row[$ctl][$met][$level - 3] = $buyer_menu_rows[$buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id']]['menu_parent_id'];
			// } 
			
			
		}
		else
		{
		}

		if (isset($menu_row['sub']))
		{
			get_menu_id($menu_row['sub'], $level, $level_row, $ctl, $met);
		}
	}
}

function get_menu_url_map($buyer_menu, &$level_row, $buyer_menu_ori)
{
	foreach ($buyer_menu as $menu_row)
	{
		get_menu_id($buyer_menu, 0, $level_row, $menu_row['menu_url_ctl'], $buyer_row['menu_url_met']);

		if (isset($menu_row['sub']))
		{
			get_menu_url_map($menu_row['sub'], $level_row, $buyer_menu_ori);
		}
	}
}

//缓存点亮规则
//get_menu_url_map($user_menu, $level_row, $user_menu);

//计算当前高亮
get_menu_id($buyer_menu, 0, $level_row, $ctl, $met);
@$level_row = $level_row[$ctl][$met];
$level_row[1] = 10000;
return $buyer_menu;
?>