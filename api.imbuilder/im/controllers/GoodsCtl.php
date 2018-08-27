<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class GoodsCtl extends Yf_AppController
{
	public $userInfoModel     = null;
	public $userBaseModel     = null;
	private $rest = null;

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
		
//		$this->userInfoModel     = new User_InfoModel();
		$this->userBaseModel     = new User_BaseModel();
		$this->userGoodsModel     = new User_GoodsModel();

	}

	/**
	 *获取用户信息
	 */
	public function getUserGoods()
	{
		$user_id = request_int('user_id');
		$page = request_int('page') ? intval(request_int('page')) : 1;
		$rows = request_int('rows') ? intval(request_int('rows')) : 20;
		$sort = request_int('sort') ? intval(request_int('sort')) : 'asc';
		$User_GoodsModel = new User_GoodsModel();
		$data = $User_GoodsModel->getUserGoodsList($user_id,$page,$rows,$sort);
		if($data['items'])
		{
			$url = Yf_Registry::get('shop_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$key = Yf_Registry::get('shop_api_key');
			$formvars            = array();
			$formvars['common_id'] = $data['items'][0]['goods_common_id'];
			$formvars['app_id'] = $shop_app_id;
			$url     = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Api_Goods_Goods', 'getShop', 'json');
			$init_rs         = get_url_with_encrypt($key, $url, $formvars);
			if($init_rs)
			{
				$shop_id = $init_rs['data']['shop_id'];
				$str = explode('/', $data['items'][0]['goods_url']);
				array_pop($str);
				$store_url = 'store.html?shop_id='.$shop_id;
				array_push($str, $store_url);
				$data['store_url'] = implode('/', $str);
			}
			else
			{
				$data['store_url'] = '';
			}
		}
		fb($data);

		$this->data->addBody(-140,$data);
	}


	
}

?>