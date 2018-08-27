<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_IndexCtl extends Seller_Controller
{
	public $shopBaseModel = null;
	public $userBaseModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->shopBaseModel = new Shop_BaseModel();
		$this->userBaseModel = new User_BaseModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		//判断是否登录，没登录登录页去
		if (Perm::checkUserPerm())
		{
			$key             = Yf_Registry::get('shop_api_key');
			$shop['shop_id'] = Perm::$shopId;
            $chain_id        = Perm::$chainId;
			if ($shop['shop_id'])
			{
				$shop_base = $this->shopBaseModel->getBaseOneList($shop);
				$user_id   = Perm::$userId;
				$user_base = $this->userBaseModel->getOne($user_id);
                    setcookie("user_account", $user_base['user_account']);
				//首页统计
//				$forvatar['shop_id'] = Perm::$shopId;
//				$data                = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=showindex&typ=json', Yf_Registry::get('url')), $forvatar);
//				$total               = $data['data'];
                $this->shopinfo;
                //店铺运营推广
                //自营店铺
                if($shop_base['shop_self_support'] == 'true')
                {
                    //管理员后台团购功能开启
                    if(Web_ConfigModel::value('groupbuy_allow'))
                    {
                        $data['promotion_items']['groupbuy_allow_flag'] = true;
                        $data['promotion_items']['groupbuy_combo_flag'] = true;
                    }
                    else
                    {
                        $data['promotion_items']['groupbuy_allow_flag'] = false;
                    }
                    //管理员后台促销功能开启
                    if(Web_ConfigModel::value('promotion_allow'))
                    {
                        $data['promotion_items']['promotion_allow_flag'] = true;

                        $data['promotion_items']['promotion_increase_combo_flag'] = true;
                        $data['promotion_items']['promotion_discount_combo_flag'] = true;
                        $data['promotion_items']['promotion_mansong_combo_flag'] = true;
                    }
                    else
                    {
                        $data['promotion_items']['promotion_allow_flag'] = false;
                    }
                    //积分中心、积分兑换、代金券 同时开启，代金券功能才可用
                    if(Web_ConfigModel::value('pointshop_isuse') && Web_ConfigModel::value('pointprod_isuse') && Web_ConfigModel::value('voucher_allow'))
                    {
                        $data['promotion_items']['voucher_allow_flag'] = true;
                        $data['promotion_items']['voucher_combo_flag'] = true;
                    }
                    else
                    {
                        $data['promotion_items']['voucher_allow_flag'] = false;
                    }

                }
                else  //非自营店铺
                {
                    //管理员后台团购功能开启
                    if(Web_ConfigModel::value('groupbuy_allow'))
                    {
                        $data['promotion_items']['groupbuy_allow_flag'] = true;

                        //套餐状态是否可用
                        $groupbuyComboModel = new GroupBuy_QuotaModel();
                        $data['promotion_items']['groupbuy_combo_flag'] = $groupbuyComboModel->checkQuotaStateByShopId(Perm::$shopId);
                    }
                    else
                    {
                        $data['promotion_items']['groupbuy_allow_flag'] = false;
                    }
                    //管理员后台促销功能开启
                    if(Web_ConfigModel::value('promotion_allow'))
                    {
                        $data['promotion_items']['promotion_allow_flag'] = true;

                        //加价购套餐状态
                        $increaseComboModel = new Increase_ComboModel();
                        $data['promotion_items']['promotion_increase_combo_flag'] = $increaseComboModel->checkQuotaStateByShopId(Perm::$shopId);

                        //限时折扣套餐状态
                        $discountQuotaModel = new Discount_QuotaModel();
                        $data['promotion_items']['promotion_discount_combo_flag'] = $discountQuotaModel->checkQuotaStateByShopId(Perm::$shopId);

                        //满级送套餐状态
                        $manSongQuotaModel = new ManSong_QuotaModel();
                        $data['promotion_items']['promotion_mansong_combo_flag'] = $manSongQuotaModel->checkQuotaStateByShopId(Perm::$shopId);

                    }
                    else
                    {
                        $data['promotion_items']['promotion_allow_flag'] = false;
                    }
                    //积分中心、积分兑换、代金券 同时开启，代金券功能才可用
                    if(Web_ConfigModel::value('pointshop_isuse') && Web_ConfigModel::value('pointprod_isuse') && Web_ConfigModel::value('voucher_allow'))
                    {
                        $data['promotion_items']['voucher_allow_flag'] = true;
                        //代金券套餐状态
                        $voucherQuotaModel = new Voucher_quotaModel();
                        $data['promotion_items']['voucher_combo_flag'] = $voucherQuotaModel->checkQuotaStateByShopId(Perm::$shopId);
                    }
                    else
                    {
                        $data['promotion_items']['voucher_allow_flag'] = false;
                    }
                }


				//平台联系方式
				$phone = Web_ConfigModel::value("setting_phone");
				if ($phone)
				{
					$phone = explode(',', $phone);//电话
				}


				$email = Web_ConfigModel::value("setting_email");//邮件

				
				//当前商品数量统计
				$Goods_CommonModel = new Goods_CommonModel();

				$goods_state_normal_num   = $Goods_CommonModel->getCommonStateNum($shop['shop_id'], Goods_CommonModel::GOODS_STATE_NORMAL,Goods_CommonModel::GOODS_VERIFY_ALLOW);
				$goods_state_offline_num  = $Goods_CommonModel->getCommonStateNum($shop['shop_id'], Goods_CommonModel::GOODS_STATE_OFFLINE);
				$goods_state_illegal_num  = $Goods_CommonModel->getCommonStateNum($shop['shop_id'], Goods_CommonModel::GOODS_STATE_ILLEGAL);
				$goods_verify_waiting_num = $Goods_CommonModel->getCommonVerifyNum($shop['shop_id']);

				//投诉数量统计
				$Complain_BaseModel = new Complain_BaseModel;
				$complain_all_count = count($Complain_BaseModel->getByWhere(['user_id_accused'=>Perm::$shopId]));
				$complain_status = [1,2,3,4];
				$complain_unsolved_count = count($Complain_BaseModel->getByWhere(['user_id_accused'=>Perm::$shopId, 'complain_state:IN'=>$complain_status]));

				if (!empty($shop_base['shop_grade_row']))
				{
					$shop_grade_goods_limit = $shop_base['shop_grade_row']['shop_grade_goods_limit'];
					$shop_grade_album_limit = $shop_base['shop_grade_row']['shop_grade_album_limit'];
				}
				else
				{
					$shop_grade_goods_limit = 0;
					$shop_grade_album_limit = 0;
				}


				$Upload_BaseModel = new Upload_BaseModel();
				$shop_album_num   = $Upload_BaseModel->getUploadNum($shop['shop_id']);


				//销量统计
				$start_date       = date("Y-m-d", strtotime("-30 days"));
				$start_today_date = date("Y-m-d");
				$start_yes_date   = date("Y-m-d", strtotime("-1 days"));
				$start_week_date  = date("Y-m-d", strtotime("-7 days"));

				$today = $this->shopBaseModel->getShopSales(Perm::$shopId,$start_today_date);
				$week = $this->shopBaseModel->getShopSales(Perm::$shopId,$start_week_date);
				$month = $this->shopBaseModel->getShopSales(Perm::$shopId,$start_date);
				
				
				$Analysis_ShopGeneralModel = new Analysis_ShopGeneralModel();
				$analysis_today_row        = $Analysis_ShopGeneralModel->getShop(Perm::$shopId, $start_today_date);
				$analysis_yes_row          = $Analysis_ShopGeneralModel->getShop(Perm::$shopId, $start_yes_date);
				$analysis_data_row         = $Analysis_ShopGeneralModel->getShop(Perm::$shopId, $start_date);

				fb($analysis_today_row);
				fb($analysis_yes_row);
				fb($analysis_data_row);
				
//				$total['today']['nums']   = $analysis_today_row['order_goods_num'];
//				$total['today']['cashes'] = $analysis_today_row['order_cash'];
//				$total['yes']['nums']     = $analysis_yes_row['order_goods_num'];
//				$total['yes']['cashes']   = $analysis_yes_row['order_cash'];
//				$total['month']['nums']   = $analysis_data_row['order_goods_num'];
//				$total['month']['cashes'] = $analysis_data_row['order_cash'];

				//单品销量
				$shop_top_rows = $Analysis_ShopGeneralModel->getShopGoodsTop(Perm::$shopId, $start_date);

				//2.店铺信息
				$shop_detail = $this->shopBaseModel->getShopDetail($shop['shop_id']);
				//
				if ('json' == $this->typ)
				{
					$this->data->addBody(-140, $data);
				}
				else
				{
					include $this->view->getView();
				}
			}
            else if($chain_id)
            {
                header("Location:" . Yf_Registry::get('url') . "?ctl=Chain_Goods&met=goods&typ=e");
            }
			else
			{
				header("Location:" . Yf_Registry::get('url') . "?ctl=Seller_Shop_Settled&met=index");
			}
		}
		else
		{
			header("Location:" . Yf_Registry::get('url'), "请先登录！");
		}
	}

	public function cropperImageExample()
	{
		include $this->view->getView();
	}

}

?>