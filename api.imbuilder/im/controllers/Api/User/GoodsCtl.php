<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_GoodsCtl extends Yf_AppController
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
		$user_id = $_REQUEST['id'];
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'asc';
		$User_GoodsModel = new User_GoodsModel();
		$data = $User_GoodsModel->getUserGoodsList($user_id,$page,$rows,$sort);

		$this->data->addBody(-140,$data);
	}

	public function addOrEditUserGoods()
	{
		$common_id = request_int('goods_common_id');

		$data['goods_common_id'] = $common_id;
		$data['user_id'] = request_int('user_id');
		$data['goods_name'] = request_string('goods_name');
		$data['goods_price'] = request_float('goods_price');
		$data['goods_pic'] = request_string('goods_pic');
		$data['goods_url'] = request_string('goods_url');
		$data['goods_status'] = request_int('goods_status');
		$data['goods_verify'] = request_int('goods_verify');

		$time = request_string('time');

		$User_GoodsModel = new User_GoodsModel();
		$array = array();
		$array['goods_common_id'] = $common_id;
		$array['user_id'] = $data['user_id'];
		$goods_row = $User_GoodsModel->getOneByWhere($array);
		//$goods_row = $User_GoodsModel->getOne($common_id);

		//判断根据common_id与user_id是否可以查找到商品信息，若可以查到则为修改商品信息，若查不到则增加信息
		if($goods_row)
		{
			$id = $goods_row['user_goods_id'];
			$flag = $User_GoodsModel->editUserGoods($id,$data);

			$data['user_goods_id'] = $id;
			$data['time'] = $time;
		}
		else
		{
			$data['time'] = $time;

			$flag = $User_GoodsModel->addUserGoods($data);
		}

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);

	}

	public function editUserGoodsVerify()
	{
		$common_id = request_int('goods_common_id');

		$goods_virty = request_int('goods_verify');

		$User_GoodsModel = new User_GoodsModel();

		$data['goods_verify'] = $goods_virty;
		$flag = $User_GoodsModel->editUserGoods($common_id,$data);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}

	public function editUserGoodsStatus()
	{
		$common_id = request_int('goods_common_id');

		$goods_status = request_int('goods_status');

		$User_GoodsModel = new User_GoodsModel();

		$data['goods_status'] = $goods_status;
		$flag = $User_GoodsModel->editUserGoods($common_id,$data);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}


	
}

?>