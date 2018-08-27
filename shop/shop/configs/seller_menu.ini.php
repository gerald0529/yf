<?php
$ctl       = request_string('ctl');
$met       = request_string('met');
$act       = request_string("act");
$level_row = array();
$seller_menu = array(
	10000 => array(
		'menu_id' => '10000',
		'menu_parent_id' => '-1',
		'menu_name' => __('首页'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Index',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
	),
	11000 => array(
		'menu_id' => '11000',
		'menu_parent_id' => '-1',
		'menu_name' => __('商品'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Goods',
		'menu_url_met' => 'online',
		'menu_url_parem' => '',
		'sub' => array(
			110002 => array(
				'menu_id' => '110002',
				'menu_parent_id' => '11000',
				'menu_name' => __('出售中的商品'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'online',
				'menu_url_parem' => '',
				'sub' => array(
					1110001 => array(
						'menu_id' => '1110001',
						'menu_parent_id' => '110002',
						'menu_name' => __('出售中的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'online',
						'menu_url_parem' => 'op=faf',
					),
				)
			),
			110001 => array(
				'menu_id' => '110001',
				'menu_parent_id' => '11000',
				'menu_name' => __('商品发布'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'add',
				'menu_url_parem' => '',
			),
			110003 => array(
				'menu_id' => '110003',
				'menu_parent_id' => '11000',
				'menu_name' => __('仓库中的商品'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'offline',
				'menu_url_parem' => '',
				'sub' => array(
					1130001 => array(
						'menu_id' => '1130001',
						'menu_parent_id' => '110003',
						'menu_name' => __('已下架的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'offline',
						'menu_url_parem' => 'op=1',
					),
					1130002 => array(
						'menu_id' => '1130002',
						'menu_parent_id' => '110003',
						'menu_name' => __('外部导入待上架商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'OutsideImport',
						'menu_url_parem' => '',
					),
					1130003 => array(
						'menu_id' => '1130003',
						'menu_parent_id' => '110003',
						'menu_name' => __('等待审核的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'verify',
						'menu_url_parem' => 'op=3',
					),
					1130004 => array(
						'menu_id' => '1130004',
						'menu_parent_id' => '110003',
						'menu_name' => __('未通过审核的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'verifyDeny',
						'menu_url_parem' => '',
					),
					1130005 => array(
						'menu_id' => '1130005',
						'menu_parent_id' => '110003',
						'menu_name' => __('待发布的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'waitReleaseGoods',
						'menu_url_parem' => '',
					),
					1130006 => array(
						'menu_id' => '1130006',
						'menu_parent_id' => '110003',
						'menu_name' => __('违规的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods',
						'menu_url_met' => 'lockup',
						'menu_url_parem' => 'op=2',
					),
				),
			),

			110004 => array(
				'menu_id' => '110004',
				'menu_parent_id' => '11000',
				'menu_name' => __('分销商品'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Supplier_Goods',
				'menu_url_met' => 'online',
				'menu_url_parem' => '',
				'sub' => array(
					11000401 => array(
						'menu_id' => '11000401',
						'menu_parent_id' => '110004',
						'menu_name' => __('出售中的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Goods',
						'menu_url_met' => 'online',
						'menu_url_parem' => '',
					),
					11000402 => array(
						'menu_id' => '11000402',
						'menu_parent_id' => '110004',
						'menu_name' => __('仓库中的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Goods',
						'menu_url_met' => 'offline',
						'menu_url_parem' => '',
					),
					11000403 => array(
						'menu_id' => '11000403',
						'menu_parent_id' => '110004',
						'menu_name' => __('等待审核的商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Goods',
						'menu_url_met' => 'verify',
						'menu_url_parem' => '',
					),
				),
			),
			110005 => array(
				'menu_id' => '110005',
				'menu_parent_id' => '11000',
				'menu_name' => __('关联版式'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods',
				'menu_url_met' => 'format',
				'menu_url_parem' => '',
                'sub' => array(

                    1160004 => array(
                        'menu_id' => '1160004',
                        'menu_parent_id' => '110005',
                        'menu_name' => __('关联版式'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Goods',
                        'menu_url_met' => 'format',
                        'menu_url_parem' => '',
                    )
                )
			),
			110006 => array(
				'menu_id' => '110006',
				'menu_parent_id' => '11000',
				'menu_name' => __('商品规格'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods_Spec',
				'menu_url_met' => 'spec',
				'menu_url_parem' => '',
				'sub' => array(
					1160006 => array(
						'menu_id' => '1160006',
						'menu_parent_id' => '110006',
						'menu_name' => __('商品规格'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods_Spec',
						'menu_url_met' => 'spec',
						'menu_url_parem' => '',
					)
				),
			),
			110007 => array(
				'menu_id' => '110007',
				'menu_parent_id' => '11000',
				'menu_name' => __('图片空间'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Album',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
					1100071 => array(
						'menu_id' => '1100071',
						'menu_parent_id' => '110007',
						'menu_name' => __('未分组'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Album',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					)
				)
			),
			110008 => array(
				'menu_id' => '110008',
				'menu_parent_id' => '11000',
				'menu_name' => __('淘宝导入'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods_TBImport',
				'menu_url_met' => 'importFile',
				'menu_url_parem' => '',
				'sub' => array(
					1100081 => array(
						'menu_id' => '1100071',
						'menu_parent_id' => '110008',
						'menu_name' => __('导入CSV文件'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods_TBImport',
						'menu_url_met' => 'importFile',
						'menu_url_parem' => '',
					),
					1100082 => array(
						'menu_id' => '1100072',
						'menu_parent_id' => '110008',
						'menu_name' => __('上传商品图片'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Goods_TBImport',
						'menu_url_met' => 'importImage',
						'menu_url_parem' => '',
					)
				)
			),
		),
	),
	12000 => array(
		'menu_id' => '12000',
		'menu_parent_id' => '-1',
		'menu_name' => __('订单物流'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Trade_Order',
		'menu_url_met' => 'physical',
		'menu_url_parem' => '',
		'sub' => array(
			120001 => array(
				'menu_id' => '120001',
				'menu_parent_id' => '12000',
				'menu_name' => __('已售订单管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Order',
				'menu_url_met' => 'physical',
				'menu_url_parem' => '',
				'sub' => array(
					1200011 => array(
						'menu_id' => '1200011',
						'menu_parent_id' => '120001',
						'menu_name' => __('所有订单'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'physical',
						'menu_url_parem' => '',
					),
					1200012 => array(
						'menu_id' => '1200012',
						'menu_parent_id' => '120001',
						'menu_name' => __('待付款'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalNew',
						'menu_url_parem' => '',
					),
					1200013 => array(
						'menu_id' => '1200013',
						'menu_parent_id' => '120001',
						'menu_name' => __('已付款'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalPay',
						'menu_url_parem' => '',
					),
					/*1200014 => array(
						'menu_id' => '1200014',
						'menu_parent_id' => '120001',
						'menu_name' => __('待自提'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalNotakes',
						'menu_url_parem' => '',
					),*/
					120005 => array(
						'menu_id' => '120005',
						'menu_parent_id' => '120001',
						'menu_name' => __('已发货'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalSend',
						'menu_url_parem' => '',
					),
					120006 => array(
						'menu_id' => '120006',
						'menu_parent_id' => '120001',
						'menu_name' => __('已完成'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalSuccess',
						'menu_url_parem' => '',
					),
					120007 => array(
						'menu_id' => '120007',
						'menu_parent_id' => '120001',
						'menu_name' => __('已取消'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalCancel',
						'menu_url_parem' => '',
					),
					120008 => array(
						'menu_id' => '120008',
						'menu_parent_id' => '120001',
						'menu_name' => __('回收站'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getPhysicalHideOrder',
						'menu_url_parem' => '',
					),
					120009 => array(
						'menu_id' => '120009',
						'menu_parent_id' => '120001',
						'menu_name' => __('订单详情'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'physicalInfo',
						'menu_url_parem' => '',
					),
					1200010 => array(
						'menu_id' => '1200010',
						'menu_parent_id' => '120001',
						'menu_name' => __('发货'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'send',
						'menu_url_parem' => '',
					)
				)
			),
			120002 => array(
				'menu_id' => '120002',
				'menu_parent_id' => '12000',
				'menu_name' => __('虚拟兑码订单'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Order',
				'menu_url_met' => 'virtual',
				'menu_url_parem' => '',
				'sub' => array(
					1200021 => array(
						'menu_id' => '1200021',
						'menu_parent_id' => '120002',
						'menu_name' => __('所有订单'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtual',
						'menu_url_parem' => '',
					),
					1200022 => array(
						'menu_id' => '1200022',
						'menu_parent_id' => '120002',
						'menu_name' => __('待付款'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualNew',
						'menu_url_parem' => '',
					),
					1200023 => array(
						'menu_id' => '1200023',
						'menu_parent_id' => '120002',
						'menu_name' => __('已发货'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualPay',
						'menu_url_parem' => '',
					),
					1200024 => array(
						'menu_id' => '1200024',
						'menu_parent_id' => '120002',
						'menu_name' => __('已完成'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualSuccess',
						'menu_url_parem' => '',
					),
					1200025 => array(
						'menu_id' => '1200025',
						'menu_parent_id' => '120002',
						'menu_name' => __('已取消'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualCancel',
						'menu_url_parem' => '',
					),
					1200026 => array(
						'menu_id' => '1200026',
						'menu_parent_id' => '120002',
						'menu_name' => __('回收站'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'getVirtualHideOrder',
						'menu_url_parem' => '',
					),
					1200027 => array(
						'menu_id' => '1200027',
						'menu_parent_id' => '120002',
						'menu_name' => __('校验兑换码'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtualExchange',
						'menu_url_parem' => '',
					),
					1200028 => array(
						'menu_id' => '1200028',
						'menu_parent_id' => '120002',
						'menu_name' => __('订单详情'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Order',
						'menu_url_met' => 'virtualInfo',
						'menu_url_parem' => '',
					)
				)
			),
            120008 => array(
                'menu_id' => '120008',
                'menu_parent_id' => '12000',
                'menu_name' => __('门店自提订单'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Trade_Order',
                'menu_url_met' => 'chain',
                'menu_url_parem' => '',
                'sub' => array(
                    1200081 => array(
                        'menu_id' => '1200081',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('所有订单'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'chain',
                        'menu_url_parem' => '',
                    ),
                    1200082 => array(
                        'menu_id' => '1200082',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('待付款'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getChainNew',
                        'menu_url_parem' => '',
                    ),
                    1200083 => array(
                        'menu_id' => '1200083',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('待自提'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getChainNotakes',
                        'menu_url_parem' => '',
                    ),
                    1200084 => array(
                        'menu_id' => '1200084',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('已完成'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getChainSuccess',
                        'menu_url_parem' => '',
                    ),
                    1200085 => array(
                        'menu_id' => '1200085',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('已取消'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getChainCancel',
                        'menu_url_parem' => '',
                    ),
                    1200086 => array(
                        'menu_id' => '1200086',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('回收站'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'getChainHideOrder',
                        'menu_url_parem' => '',
                    ),
                    1200087 => array(
                        'menu_id' => '1200087',
                        'menu_parent_id' => '120008',
                        'menu_name' => __('订单详情'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Trade_Order',
                        'menu_url_met' => 'chainInfo',
                        'menu_url_parem' => '',
                    )
                )
            ),
			120003 => array(
				'menu_id' => '120003',
				'menu_parent_id' => '12000',
				'menu_name' => __('发货'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Deliver',
				'menu_url_met' => 'deliver',
				'menu_url_parem' => '',
				'sub' => array(
					1200031=>array(
						'menu_id' => '1200031',
						'menu_parent_id' => '120003',
						'menu_name' => __('待发货'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'deliver',
						'menu_url_parem' => '',
					),
					1200032=>array(
						'menu_id' => '1200032',
						'menu_parent_id' => '120003',
						'menu_name' => __('发货中'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'delivering',
						'menu_url_parem' => '',
					),
					1200033=>array(
						'menu_id' => '1200033',
						'menu_parent_id' => '120003',
						'menu_name' => __('已收货'), 
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'delivered',
						'menu_url_parem' => '',
					)
				)
			),
			120004 => array(
				'menu_id' => '120004',
				'menu_parent_id' => '12000',
				'menu_name' => __('发货设置'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Deliver',
				'menu_url_met' => 'deliverSetting',
				'menu_url_parem' => '',
				'sub' => array(
					1240001=>array(
							'menu_id' => '1240001',
							'menu_parent_id' => '120004',
							'menu_name' => __('地址库'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Trade_Deliver',
							'menu_url_met' => 'deliverSetting',
							'menu_url_parem' => '',
					),
					1240002 => array(
						'menu_id' => '1240002',
						'menu_parent_id' => '120004',
						'menu_name' => __('默认物流公司'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'express',
						'menu_url_parem' => '',
					),
					1240003 => array(
						'menu_id' => '1240003',
						'menu_parent_id' => '120004',
						'menu_name' => __('免运费额度'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'freightAmount',
						'menu_url_parem' => '',
					),
//					1240004 => array(
//						'menu_id' => '1240004',
//						'menu_parent_id' => '120004',
//						'menu_name' => __('默认配送地区'),
//						'menu_icon' => '',
//						'menu_url_ctl' => 'Seller_Trade_Deliver',
//						'menu_url_met' => 'deliverArea',
//						'menu_url_parem' => '',
//					),
					1240005 => array(
						'menu_id' => '1240005',
						'menu_parent_id' => '120004',
						'menu_name' => __('发货单打印设置'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Deliver',
						'menu_url_met' => 'printSetting',
						'menu_url_parem' => '',
					)
				),
			),
			120005 => array(
				'menu_id' => '120005',
				'menu_parent_id' => '12000',
				'menu_name' => __('运单模板'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Trade_Waybill',
				'menu_url_met' => 'waybillManage',
				'menu_url_parem' => '',
				'sub' => array(
					1200051 => array(
						'menu_id' => '1200051',
						'menu_parent_id' => '120005',
						'menu_name' => __('模板绑定'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillManage',
						'menu_url_parem' => '',
					),
					1200052 => array(
						'menu_id' => '1200052',
						'menu_parent_id' => '120005',
						'menu_name' => __('自建模板'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillIndex',
						'menu_url_parem' => '',
					),
					1200053 => array(
						'menu_id' => '1200053',
						'menu_parent_id' => '120005',
						'menu_name' => __('选择模板'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillBind',
						'menu_url_parem' => '',
					),
					1200054 => array(
						'menu_id' => '1200054',
						'menu_parent_id' => '120005',
						'menu_name' => __('模板设置'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'waybillSetting',
						'menu_url_parem' => '',
					),
					1200055 => array(
						'menu_id' => '1200055',
						'menu_parent_id' => '120005',
						'menu_name' => __('添加模板'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'addTpl',
						'menu_url_parem' => '',
					),
					1200056 => array(
						'menu_id' => '1200056',
						'menu_parent_id' => '120005',
						'menu_name' => __('编辑模板'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'editTpl',
						'menu_url_parem' => '',
					),
					1200057 => array(
						'menu_id' => '1200057',
						'menu_parent_id' => '120005',
						'menu_name' => __('设计模板'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Trade_Waybill',
						'menu_url_met' => 'designTpl',
						'menu_url_parem' => '',
					),
				),
			),
			120006 => array(
				'menu_id' => '120006',
				'menu_parent_id' => '12000',
				'menu_name' => __('评价管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Goods_Evaluation',
				'menu_url_met' => 'evaluation',
				'menu_url_parem' => '',
				'sub' => array(
						1120006 => array(
							'menu_id' => '1120006',
							'menu_parent_id' => '120006',
							'menu_name' => __('来自买家的评价'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Goods_Evaluation',
							'menu_url_met' => 'evaluation',
							'menu_url_parem' => '',
						),
				),
			),
			120007 => array(
				'menu_id' => '120007',
				'menu_parent_id' => '12000',
				'menu_name' => __('物流工具'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Transport',
				'menu_url_met' => 'transport',
				'menu_url_parem' => '',
			),
            120010 => array(
				'menu_id' => '120010',
				'menu_parent_id' => '12000',
				'menu_name' => __('售卖区域'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Transport',
				'menu_url_met' => 'tplarea',
				'menu_url_parem' => '',
			),
		),
	),

	13000 => array(
		'menu_id' => '13000',
		'menu_parent_id' => '-1',
		'menu_name' => __('促销'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			130001 => array(
				'menu_id' => '130001',
				'menu_parent_id' => '13000',
				'menu_name' => __('团购管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
					1130001 => array(
						'menu_id' => '1130001',
						'menu_parent_id' => '130001',
						'menu_name' => __('团购列表'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					1130002 => array(
						'menu_id' => '1130002',
						'menu_parent_id' => '130001',
						'menu_name' => __('新增团购'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'add',
						'menu_url_parem' => '',
					),
					1130003 => array(
						'menu_id' => '1130003',
						'menu_parent_id' => '130001',
						'menu_name' => __('新增虚拟团购'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'addVr',
						'menu_url_parem' => '',
					),
					1130004 => array(
						'menu_id' => '1130004',
						'menu_parent_id' => '130001',
						'menu_name' => __('套餐管理'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Promotion_GroupBuy',
						'menu_url_met' => 'combo',
						'menu_url_parem' => '',
					)
				),
			),
            130002 => array(
                'menu_id' => '130002',
                'menu_parent_id' => '13000',
                'menu_name' => __('加价购'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Increase',
                'menu_url_met' => 'index',
                'menu_url_parem' => 'op=list',
                'sub' => array(
                    1230001 => array(
                        'menu_id' => '1230001',
                        'menu_parent_id' => '130002',
                        'menu_name' => __('活动列表'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => 'op=list',
                    ),
                    1230002 => array(
                        'menu_id' => '1230002',
                        'menu_parent_id' => '130002',
                        'menu_name' => __('添加活动'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1230003 => array(
                        'menu_id' => '1230003',
                        'menu_parent_id' => '130002',
                        'menu_name' => __('套餐管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Increase',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
            130003 => array(
                'menu_id' => '130003',
                'menu_parent_id' => '13000',
                'menu_name' => __('限时折扣'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Discount',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    1330001 => array(
                        'menu_id' => '1330001',
                        'menu_parent_id' => '130003',
                        'menu_name' => __('活动列表'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1330002 => array(
                        'menu_id' => '1330002',
                        'menu_parent_id' => '130003',
                        'menu_name' => __('新增活动'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1330003 => array(
                        'menu_id' => '1330003',
                        'menu_parent_id' => '130003',
                        'menu_name' => __('套餐管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                   /* 1330004 => array(
                        'menu_id' => '1330004',
                        'menu_parent_id' => '130003',
                        'menu_name' => __('商品管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Discount',
                        'menu_url_met' => 'manage',
                        'menu_url_parem' => '',
                    ),*/

                ),
            ),
			130004 => array(
				'menu_id' => '130004',
				'menu_parent_id' => '13000',
				'menu_name' => __('满即送'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
                'sub' => array(
                    1430001 => array(
                        'menu_id' => '1430001',
                        'menu_parent_id' => '130004',
                        'menu_name' => __('满送列表'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1430002 => array(
                        'menu_id' => '1430002',
                        'menu_parent_id' => '130004',
                        'menu_name' => __('新增活动'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					1430003 => array(
                        'menu_id' => '1430003',
                        'menu_parent_id' => '130004',
                        'menu_name' => __('套餐管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_MeetConditionGift',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),

                )
			),
            130011 => array(
                'menu_id' => '130011',
                'menu_parent_id' => '13000',
                'menu_name' => __('代金券管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Promotion_Voucher',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    130011 => array(
                        'menu_id' => '130011',
                        'menu_parent_id' => '130011',
                        'menu_name' => __('代金券管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
                    1130012 => array(
                        'menu_id' => '1130002',
                        'menu_parent_id' => '130011',
                        'menu_name' => __('添加代金券'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
                    1130013 => array(
                        'menu_id' => '1130003',
                        'menu_parent_id' => '130011',
                        'menu_name' => __('套餐管理'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_Voucher',
                        'menu_url_met' => 'combo',
                        'menu_url_parem' => '',
                    ),
                ),
            ),
			130012 => array(
                'menu_id' => '130012',
                'menu_parent_id' => '13000',
                'menu_name' => __('分销管理'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Distribution_Seller_Setting',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
                    130012 => array(
                        'menu_id' => '130012',
                        'menu_parent_id' => '130012',
                        'menu_name' => __('分销设置'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Distribution_Seller_Setting',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
					130013 => array(
                        'menu_id' => '130013',
                        'menu_parent_id' => '130012',
                        'menu_name' => __('我的分销员'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Distribution_Seller_Setting',
                        'menu_url_met' => 'directseller',
                        'menu_url_parem' => '',
                    ),
					130014 => array(
                        'menu_id' => '130014',
                        'menu_parent_id' => '130012',
                        'menu_name' => __('分销商品'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Distribution_Seller_Setting',
                        'menu_url_met' => 'directsellerGoods',
                        'menu_url_parem' => '',
                    ),
					130015 => array(
                        'menu_id' => '130015',
                        'menu_parent_id' => '130012',
                        'menu_name' => __('添加分销商品'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Distribution_Seller_Setting',
                        'menu_url_met' => 'addDirectsellerGoods',
                        'menu_url_parem' => '',
                    ),
                    130016 => array(
                        'menu_id' => '130016',
                        'menu_parent_id' => '130012',
                        'menu_name' => __('分销业绩'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Distribution_Seller_Setting',
                        'menu_url_met' => 'directsellerDetail',
                        'menu_url_parem' => '',
                    )
                ),
            ),
            130009 => array(
				'menu_id' => '130009',
				'menu_parent_id' => '13000',
				'menu_name' => __('拼团管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Promotion_PinTuan',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
                'sub' => array(
					130009 => array(
                        'menu_id' => '130009',
                        'menu_parent_id' => '130009',
                        'menu_name' => __('拼团列表'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_PinTuan',
                        'menu_url_met' => 'index',
                        'menu_url_parem' => '',
                    ),
					130010 => array(
                        'menu_id' => '1930002',
                        'menu_parent_id' => '130009',
                        'menu_name' => __('新增活动'),
                        'menu_icon' => '',
                        'menu_url_ctl' => 'Seller_Promotion_PinTuan',
                        'menu_url_met' => 'add',
                        'menu_url_parem' => '',
                    ),
					// 130011 => array(
     //                    'menu_id' => '1930003',
     //                    'menu_parent_id' => '130009',
     //                  'menu_name' => __('套餐管理'),
     //                    'menu_icon' => '',
     //                    'menu_url_ctl' => 'Seller_Promotion_PinTuan',
     //                    'menu_url_met' => 'combo',
     //                    'menu_url_parem' => '',
     //                ),

                )
			),
 
            /*130010 => array(
				'menu_id' => '130010',
				'menu_parent_id' => '13000',
				'menu_name' => __('手机专享'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Marketing',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),*/
            /*130009 => array(
                'menu_id' => '130009',
                'menu_parent_id' => '13000',
                'menu_name' => __('推荐组合'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130008 => array(
                'menu_id' => '130008',
                'menu_parent_id' => '13000',
                'menu_name' => __('码商品'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130007 => array(
                'menu_id' => '130007',
                'menu_parent_id' => '13000',
                'menu_name' => __('预售商品'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130006 => array(
                'menu_id' => '130006',
                'menu_parent_id' => '13000',
                'menu_name' => __('推荐展位'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
            /*130005 => array(
                'menu_id' => '130005',
                'menu_parent_id' => '13000',
                'menu_name' => __('优惠套装'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Marketing',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            ),*/
		),
	),

	14000 => array(
		'menu_id' => '14000',
		'menu_parent_id' => '-1',
		'menu_name' => __('店铺'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Shop_Setshop',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			140001 => array(
				'menu_id' => '140001',
				'menu_parent_id' => '14000',
				'menu_name' => __('店铺设置'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Setshop',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
                                'sub' => array(
                                     1400001 => array(
                                            'menu_id' => '1400001',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺设置'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'index',
                                            'menu_url_parem' => '',

                                    ),  
                                    1400002 => array(
                                            'menu_id' => '1400002',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺幻灯'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'slide',
                                            'menu_url_parem' => '',

                                    ),  
                                    1400003 => array(
                                            'menu_id' => '1400003',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺模板'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Setshop',
                                            'menu_url_met' => 'theme',
                                            'menu_url_parem' => '',

                                    ),        
                                    1400004 => array(
                                            'menu_id' => '1400004',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺装修'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Decoration',
                                            'menu_url_met' => 'decoration',
                                            'menu_url_parem' => '',

                                    ),
                                    1400005 => array(
                                            'menu_id' => '1400005',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺导航'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Nav',
                                            'menu_url_met' => 'nav',
                                            'menu_url_parem' => '',

                                    ),
                                    1400006 => array(
                                            'menu_id' => '1400006',
                                            'menu_parent_id' => '140001',
                                            'menu_name' => __('店铺分类'),
                                            'menu_icon' => '',
                                            'menu_url_ctl' => 'Seller_Shop_Cat',
                                            'menu_url_met' => 'cat',
                                            'menu_url_parem' => '',

                                    ),
									1400007 => array(
										'menu_id' => '1400007',
										'menu_parent_id' => '140001',
										'menu_name' => __('售后服务'),
										'menu_icon' => '',
										'menu_url_ctl' => 'Seller_Shop_Setshop',
										'menu_url_met' => 'service',
										'menu_url_parem' => '',

									),

								),
			),  
   //                      140003 => array(
			// 	'menu_id' => '140003',
			// 	'menu_parent_id' => '14000',
			// 	'menu_name' => __('店铺导航'),
			// 	'menu_icon' => '',
			// 	'menu_url_ctl' => 'Seller_Shop_Nav',
			// 	'menu_url_met' => 'nav',
			// 	'menu_url_parem' => '',
			// ),
			140008 => array(
						'menu_id' => '140008',
						'menu_parent_id' => '14000',
						'menu_name' => __('自销产品供应商'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Shop_Supplier',
						'menu_url_met' => 'supplier',
						'menu_url_parem' => '',
				),
                        // 140006 => array(
                        //             'menu_id' => '140006',
                        //             'menu_parent_id' => '14000',
                        //             'menu_name' => __('店铺分类'),
                        //             'menu_icon' => '',
                        //             'menu_url_ctl' => 'Seller_Shop_Cat',
                        //             'menu_url_met' => 'cat',
                        //             'menu_url_parem' => '',
                        //     ),
			140009 => array(
				'menu_id' => '140009',
				'menu_parent_id' => '14000',
				'menu_name' => __('实体店铺'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Entityshop',
				'menu_url_met' => 'entityShop',
				'menu_url_parem' => '',
			),
			
			140007 => array(
				'menu_id' => '140007',
				'menu_parent_id' => '14000',
				'menu_name' => __('品牌申请'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Brand',
				'menu_url_met' => 'brand',
				'menu_url_parem' => '',
			),
			
			140005 => array(
				'menu_id' => '140005',
				'menu_parent_id' => '14000',
				'menu_name' => __('店铺信息'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Info',
				'menu_url_met' => 'info',
				'menu_url_parem' => '',
                 
			),
			/*140004 => array(
				'menu_id' => '140004',
				'menu_parent_id' => '14000',
				'menu_name' => __('店铺动态'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Sns',
				'menu_url_met' => 'sns',
				'menu_url_parem' => '',
			),*/
			
			140010 => array(
				'menu_id' => '140010',
				'menu_parent_id' => '14000',
				'menu_name' => __('消费者保障服务'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Shop_Contract',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
            140011 => array(
                'menu_id' => '140011',
                'menu_parent_id' => '14000',
                'menu_name' => __('门店帐号'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Shop_Chain',
                'menu_url_met' => 'chain',
                'menu_url_parem' => '',
            ),
            140012 => array(
                'menu_id' => '140012',
                'menu_parent_id' => '14000',
                'menu_name' => __('我的分销商'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Supplier_Distributor',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
					1400121 => array(
						'menu_id' => '1400121',
						'menu_parent_id' => '140012',
						'menu_name' => __('我的分销商'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Distributor',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					1400122 => array(
						'menu_id' => '1400122',
						'menu_parent_id' => '140012',
						'menu_name' => __('分销商等级'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Distributor',
						'menu_url_met' => 'setGrade',
						'menu_url_parem' => '',
					),
					1400123 => array(
						'menu_id' => '1400123',
						'menu_parent_id' => '140012',
						'menu_name' => __('等级设置'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Distributor',
						'menu_url_met' => 'addGrade',
						'menu_url_parem' => '',
					),
					1400124 => array(
						'menu_id' => '1400124',
						'menu_parent_id' => '140012',
						'menu_name' => __('分销业绩'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Distributor',
						'menu_url_met' => 'distributor_salenum',
						'menu_url_parem' => '',
					),
                ),
            ),
            
            140013 => array(
                'menu_id' => '140013',
                'menu_parent_id' => '14000',
                'menu_name' => __('分销产品供应商'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Supplier_Supplier',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
					1400131 => array(
						'menu_id' => '1400131',
						'menu_parent_id' => '140013',
						'menu_name' => __('我的供应商'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Supplier',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					1400132 => array(
						'menu_id' => '1400132',
						'menu_parent_id' => '140013',
						'menu_name' => __('供应商申请'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_Supplier',
						'menu_url_met' => 'apply',
						'menu_url_parem' => '',
					),
                ),
            ),
            140014 => array(
                'menu_id' => '140014',
                'menu_parent_id' => '14000',
                'menu_name' => __('分销明细'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Seller_Supplier_DistLog',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
                'sub' => array(
					1400141 => array(
						'menu_id' => '1400141',
						'menu_parent_id' => '140014',
						'menu_name' => __('分销明细'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_DistLog',
						'menu_url_met' => 'index',
						'menu_url_parem' => '',
					),
					1400142 => array(
						'menu_id' => '1400142',
						'menu_parent_id' => '140014',
						'menu_name' => __('我的采购单'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Supplier_DistLog',
						'menu_url_met' => 'buy_order',
						'menu_url_parem' => '',
					),
                ),
            ),
            140015 => array(
                'menu_id' => '140015',
                'menu_parent_id' => '14000',
                'menu_name' => __('批发市场'),
                'menu_icon' => '',
                'menu_url_ctl' => 'Supplier_Index',
                'menu_url_met' => 'index',
                'menu_url_parem' => '',
            )   
            
		),
	),
                
	15000 => array(
		'menu_id' => '15000',
		'menu_parent_id' => '-1',
		'menu_name' => __('售后服务'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Service_Consult',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			150001 => array(
				'menu_id' => '150001',
				'menu_parent_id' => '15000',
				'menu_name' => __('咨询管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Consult',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			150002 => array(
				'menu_id' => '150002',
				'menu_parent_id' => '15000',
				'menu_name' => __('投诉管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Complain',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			150003 => array(
				'menu_id' => '150003',
				'menu_parent_id' => '15000',
				'menu_name' => __('退款管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Return',
				'menu_url_met' => 'orderReturn',
				'menu_url_parem' => '',
			),
			150004 => array(
				'menu_id' => '150004',
				'menu_parent_id' => '15000',
				'menu_name' => __('退货管理'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Service_Return',
				'menu_url_met' => 'goodsReturn',
				'menu_url_parem' => '',
			),
		),
	),
	16000 => array(
		'menu_id' => '16000',
		'menu_parent_id' => '-1',
		'menu_name' => __('统计结算'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Analysis_General',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(

			160001 => array(
				'menu_id' => '160001',
				'menu_parent_id' => '16000',
				'menu_name' => __('店铺概况'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_General',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			160002 => array(
				'menu_id' => '160002',
				'menu_parent_id' => '16000',
				'menu_name' => __('商品分析'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Goods',
				'menu_url_met' => 'detail',
				'menu_url_parem' => '',
				'sub' => array(
					1160002 => array(
						'menu_id' => '1160002',
						'menu_parent_id' => '160002',
						'menu_name' => __('商品详情'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Analysis_Goods',
						'menu_url_met' => 'detail',
						'menu_url_parem' => '',
					),
					2160002 => array(
						'menu_id' => '2160002',
						'menu_parent_id' => '160002',
						'menu_name' => __('热卖商品'),
						'menu_icon' => '',
						'menu_url_ctl' => 'Seller_Analysis_Goods',
						'menu_url_met' => 'hot',
						'menu_url_parem' => '',
					),
				),
			),
			160003 => array(
				'menu_id' => '160003',
				'menu_parent_id' => '16000',
				'menu_name' => __('运营报告'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Operation',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			/*
			160004 => array(
				'menu_id' => '160004',
				'menu_parent_id' => '16000',
				'menu_name' => __('行业分析'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Class',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			160005 => array(
				'menu_id' => '160005',
				'menu_parent_id' => '16000',
				'menu_name' => __('流量统计'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Analysis_Flow',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			*/
			160006 => array(
				'menu_id' => '160006',
				'menu_parent_id' => '16000',
				'menu_name' => __('实物结算'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Order_Settlement',
				'menu_url_met' => 'normal',
				'menu_url_parem' => '',
				'sub' => array(
						1160006 => array(
							'menu_id' => '1160006',
							'menu_parent_id' => '160006',
							'menu_name' => __('实物订单结算'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Order_Settlement',
							'menu_url_met' => 'normal',
							'menu_url_parem' => '',
						),
				),		
			),
			160007 => array(
				'menu_id' => '160007',
				'menu_parent_id' => '16000',
				'menu_name' => __('虚拟结算'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Order_Settlement',
				'menu_url_met' => 'virtual',
				'menu_url_parem' => '',
				'sub' => array(
						1160007 => array(
							'menu_id' => '1160007',
							'menu_parent_id' => '160007',
							'menu_name' => __('虚拟订单结算'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Order_Settlement',
							'menu_url_met' => 'virtual',
							'menu_url_parem' => '',
						),
				),
			),
		),
	),
	17000 => array(
		'menu_id' => '17000',
		'menu_parent_id' => '-1',
		'menu_name' => __('客服消息'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Message',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
			 170001 => array(
				'menu_id' => '170001',
				'menu_parent_id' => '17000',
				'menu_name' => __('客服设置'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
				'sub' => array(
						1700011 => array(
							'menu_id' => '1700011',
							'menu_parent_id' => '170001',
							'menu_name' => __('客服设置'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'index',
							'menu_url_parem' => '',
						),
					),
			), 
			170002 => array(
				'menu_id' => '170002',
				'menu_parent_id' => '17000',
				'menu_name' => __('系统消息'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'message',
				'menu_url_parem' => '',
				'sub' => array(
						1700021 => array(
							'menu_id' => '1700021',
							'menu_parent_id' => '170002',
							'menu_name' => __('系统消息'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'message',
							'menu_url_parem' => '',
						),
						1700022 => array(
							'menu_id' => '1700022',
							'menu_parent_id' => '170002',
							'menu_name' => __('系统公告'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'messageAnnouncement',
							'menu_url_parem' => '',
						),
						1700023 => array(
							'menu_id' => '1700023',
							'menu_parent_id' => '170002',
							'menu_name' => __('消息接收设置'),
							'menu_icon' => '',
							'menu_url_ctl' => 'Seller_Message',
							'menu_url_met' => 'messageManage',
							'menu_url_parem' => '',
						),
					),
			),
			/* 170003 => array(
				'menu_id' => '170003',
				'menu_parent_id' => '17000',
				'menu_name' => __('聊天记录查询'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Message',
				'menu_url_met' => 'chat',
				'menu_url_parem' => '',
			), */
		),
	),
	 18000 => array(
		'menu_id' => '18000',
		'menu_parent_id' => '-1',
		'menu_name' => __('账号'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Seller_Seller_Account',
		'menu_url_met' => 'accountList',
		'menu_url_parem' => '',
		'sub' => array(
			180001 => array(
				'menu_id' => '180001',
				'menu_parent_id' => '18000',
				'menu_name' => __('子账号'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Seller_Account',
				'menu_url_met' => 'accountList',
				'menu_url_parem' => '',
			),
			180002 => array(
				'menu_id' => '180002',
				'menu_parent_id' => '18000',
				'menu_name' => __('权限组'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Seller_Group',
				'menu_url_met' => 'groupList',
				'menu_url_parem' => '',
			),
			/*
			180003 => array(
				'menu_id' => '180003',
				'menu_parent_id' => '18000',
				'menu_name' => __('操作日志'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Seller_Log',
				'menu_url_met' => 'logList',
				'menu_url_parem' => '',
			),
			*/
			/*180004 => array(
				'menu_id' => '180004',
				'menu_parent_id' => '18000',
				'menu_name' => __('店铺消费'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),
			180005 => array(
				'menu_id' => '180005',
				'menu_parent_id' => '18000',
				'menu_name' => __('门店账号'),
				'menu_icon' => '',
				'menu_url_ctl' => 'Seller_Account',
				'menu_url_met' => 'index',
				'menu_url_parem' => '',
			),*/
		),
	),
);

//根据后台配置，去除一些不需要限时的功能模块

/*if(Web_ConfigModel::value('groupbuy_allow') == 0)//团购功能关闭
{
    unset($seller_menu[13000]['sub'][130001]);
}

if(!Web_ConfigModel::value('promotion_allow'))//促销功能关闭，对应需关闭限时折扣、加价购、满送活动
{
    unset($seller_menu[13000]['sub'][130002]);
    unset($seller_menu[13000]['sub'][130003]);
    unset($seller_menu[13000]['sub'][130004]);
}
if(!(Web_ConfigModel::value('pointshop_isuse') && Web_ConfigModel::value('pointprod_isuse') && Web_ConfigModel::value('voucher_allow')))//代金券功能开启限制，代金券功能、积分功能、积分中心启用后，商家可以申请代金券活动
{
    unset($seller_menu[13000]['sub'][130011]);
}*/


if(!Web_ConfigModel::value('Plugin_Directseller'))
{
	unset($seller_menu[13000]['sub'][130012]);
}

if(!Web_ConfigModel::value('Plugin_Distribution'))
{
	unset($seller_menu[11000]['sub'][110004]);//分销商品
	unset($seller_menu[14000]['sub'][140013]);  //我的供应商
	unset($seller_menu[14000]['sub'][140014]);  //分销明细
	unset($seller_menu[14000]['sub'][140012]);  //我的分销商菜单
	unset($seller_menu[14000]['sub'][140015]);  //批发市场
	$seller_menu[14000]['sub'][140008]['menu_name'] = "我的供应商";
}else{ 
	if(@$this->shopBase['shop_type'] == 2)
	{
		//供货商店铺
		unset($seller_menu[14000]['sub'][140013]);  //我的供应商
		unset($seller_menu[14000]['sub'][140014]);  //分销明细
		unset($seller_menu[11000]['sub'][110004]);//分销商品
		$seller_menu[14000]['sub'][140008]['menu_name'] = "我的供应商";
	}else{
		unset($seller_menu[14000]['sub'][140012]);  //我的分销商菜单
	}
}

//行
global $seller_menu_rows;
$seller_menu_rows = array();


function get_menu_rows($seller_menu, &$seller_menu_rows)
{
	foreach ($seller_menu as $id=>$item)
	{
		if (isset($item['sub']) && $item['sub'])
		{
			get_menu_rows($item['sub'], $seller_menu_rows);

			unset($item['sub']);
			$seller_menu_rows[$id] = $item;
		}
		else
		{
			$seller_menu_rows[$id] = $item;
		}

	}
}

get_menu_rows($seller_menu, $seller_menu_rows);


//$ctl       = request_string('ctl');
//$met       = request_string('met');
//$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";

function get_menu_id($seller_menu, $level = 0, &$level_row, $ctl, $met)
{
	global $seller_menu_rows;

	$level++;

	foreach ($seller_menu as $menu_row)
	{
		if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met)
		{
			$level_row[$ctl][$met][$level]     = $menu_row['menu_id'];
			$level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

			//向上查找一次
			if (isset($seller_menu_rows[$menu_row['menu_parent_id']]))
			{
				$level_row[$ctl][$met][$level - 2] = $seller_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
			}
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

function get_menu_url_map($seller_menu, &$level_row, $seller_menu_ori)
{
	foreach ($seller_menu as $menu_row)
	{
		get_menu_id($seller_menu, 0, $level_row, $menu_row['menu_url_ctl'], $menu_row['menu_url_met']);

		if (isset($menu_row['sub']))
		{
			get_menu_url_map($menu_row['sub'], $level_row, $seller_menu_ori);
		}
	}
}

//缓存点亮规则
//get_menu_url_map($seller_menu, $level_row, $seller_menu);

//计算当前高亮
get_menu_id($seller_menu, 0, $level_row, $ctl, $met);
$level_row = $level_row[$ctl][$met];
return $seller_menu;
?>