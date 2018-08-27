<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_IndexCtl extends Buyer_Controller
{

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

		$this->orderBaseModel          = new Order_BaseModel();
		$this->cartModel               = new CartModel();
		$this->userFavoritesGoodsModel = new User_FavoritesGoodsModel();
		$this->userFavoritesShopModel  = new User_FavoritesShopModel();
		$this->userFootprintModel      = new User_FootprintModel();
		$this->goodsCommonModel        = new Goods_CommonModel();

	}

	
	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		
		$user_id = Perm::$userId;
		
		//订单商品
		$cond_row['buyer_user_id']        = $user_id;
		$cond_row['order_is_virtual']     = Order_BaseModel::ORDER_IS_REAL; //实物订单
		$cond_row['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_HIDDEN;//没有删除的
		$order                            = $this->orderBaseModel->getBaseList($cond_row, array('order_create_time' => 'DESC'), 1, 3);
		

		//购物车
		$cart_row = array('user_id' => $user_id);

		$cart = $this->cartModel->getCardList($cart_row);

		//收藏商品
		$favorites_row['user_id'] = $user_id;
		$favoritesGoods           = $this->userFavoritesGoodsModel->getFavoritesGoodsDetail($favorites_row, array('favorites_goods_time' => 'DESC'), 1, 30);

		//足迹
		$footprint_row = array('user_id' => $user_id);
		
		$footprint = $this->userFootprintModel->getFootprintList($footprint_row, array('Footprint_time' => 'DESC'), 1, 20);
		
		if ($footprint['items'])
		{
			$goods_id_row                 = array();
			$goods_id_row['common_id:in'] = array_column($footprint['items'], 'common_id');

			$de = $this->goodsCommonModel->getGoodsList($goods_id_row);

			$goods_id = array_column($de['items'], 'common_id');

			//以common_id为下标
			$commonAll = array();
			foreach ($de['items'] as $k => $v)
			{
				$commonAll[$v['common_id']] = $v;
			}
			foreach ($footprint['items'] as $key => $val)
			{
				
				if (in_array($val['common_id'], $goods_id))
				{
					
					$footprint['items'][$key]['detail'] = $commonAll[$val['common_id']];
				}
			}
		}
		//店铺收藏
		$shop = $this->userFavoritesShopModel->getFavoritesShopDetail($user_id, 1, 16);
        
        //获取白条状态
        $user_info_model = new User_InfoModel();
        $bt_info = $user_info_model->getBtInfo();
        
		if ('json' == $this->typ)
		{
			$data['order']          = $order;
			$data['cart']           = $cart;
			$data['favoritesGoods'] = $favoritesGoods;
			$data['footprint']      = $footprint;
			$data['shop']           = $shop;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}
	public function getUserInfoMoney()
	{
		$this->user_money        = 0;
		$this->user_money_frozen = 0;

		//会员的钱
		$key                 = Yf_Registry::get('shop_api_key');
		$formvars            = array();
		$user_id             = Perm::$userId;
		$formvars['user_id'] = $user_id;
		$formvars['app_id'] = Yf_Registry::get('shop_app_id');
		
		$money_row = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		
		if ($money_row['status'] == '200')
		{
			$money = $money_row['data'];

			$this->user_money        = $money['user_money'] + $money['user_recharge_card'];
			$this->user_money_frozen = $money['user_money_frozen']+ $money['user_recharge_card_frozen'];
			
		}

			$data = array();

		include $this->view->getView();

		$this->data->addBody(-140, $data);
		
	}

	public function getUserInfo()
	{
		//共用数据
		$uid = Perm::$userId;

		$this->userInfoModel = new User_InfoModel();
		//会员用户
		$data = $this->userInfoModel->getUserMore($uid);

		$this->data->addBody(-140, $data);
	}
    
    /**
     *  实时获取白条状态，从paycenter获取
     */
    public function getBtStatus(){
        //获取白条信息
        $user_info_model = new User_InfoModel();
        $bt_info = $user_info_model->getBtInfo();
        if(!isset($bt_info['baitiao_is_open'])){
            $bt_info = array('baitiao_is_open' => 0);
        }
        $this->data->addBody(-140, array('baitiao_is_open'=>$bt_info['baitiao_is_open']),'success',200); 
    }

    /**
     *  导航栏跳转paycenter白条页面
     */
    public function btpage(){
        $url = Yf_Registry::get('paycenter_api_url').'?ctl=Info&met=btinfo&typ=e';
        header('location:'.$url);
    }
}

?>