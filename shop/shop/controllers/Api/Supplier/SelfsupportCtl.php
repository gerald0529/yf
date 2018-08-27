<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Supplier_SelfsupportCtl extends Api_Controller
{

	public $shopBaseModel    = null;
	public $goodsCommonModel = null;
	public $UserBaseModel    = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->shopBaseModel    = new Shop_BaseModel();
		$this->goodsCommonModel = new Goods_CommonModel();
		$this->UserBaseModel    = new User_BaseModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */

	public function shopIndex()
	{

		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array("shop_self_support" => "true");

		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'user_name:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}
		$cond_row['shop_type'] = 2;
		
		$data = $this->shopBaseModel->getBaseList($cond_row);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加店铺
	 *
	 * @access public
	 */

	public function AddShopRow()
	{


		$key = Yf_Registry::get('ucenter_api_key');;
		$url       = Yf_Registry::get('ucenter_api_url');
		$app_id    = Yf_Registry::get('ucenter_app_id');
		$server_id = Yf_Registry::get('server_id');
		//开通ucenter
		//本地读取远程信息
		$formvars              = array();
		$formvars['user_name'] = request_string("user_name");
		$formvars['password']  = request_string("user_password");
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'addUserAndBindAppServer';
		$formvars['typ'] = 'json';

		$init_rs = get_url_with_encrypt($key, $url, $formvars);
		if (200 == $init_rs['status'])
		{
			//本地读取远程信息
			$data['user_id']      = $init_rs['data']['user_id']; // 用户帐号
			$data['user_account'] = request_string("user_name"); // 用户帐号
			//$data['user_password']   = md5(request_string("user_password")); // 密码：使用用户中心-此处废弃
			$data['user_delete'] = 0; // 用户状态


			$user_id = $this->UserBaseModel->addBase($data, true);
			//初始化用户信息
			$user_info_row                  = array();
			$user_info_row['user_id']       = $user_id;
			$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
			$user_info_row['user_name']     = isset($init_rs['data']['nickname']) ? $init_rs['data']['nickname'] : $data['user_account'];
			$user_info_row['user_mobile']   = @$init_rs['data']['user_mobile'];
			$User_InfoModel                 = new User_InfoModel();
			$info_flag                      = $User_InfoModel->addInfo($user_info_row);

			$user_resource_row                = array();
			$user_resource_row['user_id']     = $user_id;
			$user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;

			$User_ResourceModel = new User_ResourceModel();
			$res_flag           = $User_ResourceModel->addResource($user_resource_row);
//                                        
			$User_PrivacyModel           = new User_PrivacyModel();
			$user_privacy_row['user_id'] = $user_id;
			$privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
			//积分
			$user_points_row['user_id']           = $user_id;
			$user_points_row['user_name']         = request_string("user_name");
			$user_points_row['class_id']          = Points_LogModel::ONREG;
			$user_points_row['points_log_points'] = $user_resource_row['user_points'];
			$user_points_row['points_log_time']   = get_date_time();
			$user_points_row['points_log_desc']   = '会员注册';
			$user_points_row['points_log_flag']   = 'reg';
			$Points_LogModel                      = new Points_LogModel();
			$Points_LogModel->addLog($user_points_row);
			if ($user_id)
			{
				$datas['shop_name']         = request_string("shop_name");
				$datas['user_name']         = request_string("user_name");
				$datas['user_id']           = $user_id;
				$datas['shop_all_class']    = "1";
				$datas['shop_self_support'] = "true";
				$datas['shop_create_time']  = date("y-m-d h-i-s", time());
				$datas['shop_settlement_last_time']  = date("y-m-d h-i-s", time());
				$datas['shop_status']       = "3";
				$datas['shop_type']         = 2;

				$Number_SeqModel  = new Number_SeqModel();
				$shop_id          = $Number_SeqModel->createSeq('shop_id', 4, false);
				$datas['shop_id'] = $shop_id;
				$add              = $this->shopBaseModel->addBase($datas);

				//添加卖家信息
				$seller_base                    = array();
				$seller_base['shop_id']         = $shop_id;
				$seller_base['user_id']         = $datas['user_id'];
				$seller_base['seller_is_admin'] = 1;

				$Seller_BaseModel = new Seller_BaseModel();
				$seller_flag      = $Seller_BaseModel->addBase($seller_base);

				if ($seller_flag)
				{
					$msg    = 'success';
					$status = 200;
				}
				else
				{
					$msg    = 'failure';
					$status = 250;
				}
			}
			else
			{
				$msg    = 'failure';
				$status = 250;
			}


		}
		else
		{
			$msg    = __("该会员名已存在！");
			$status = 250;
            $datas = array();
		}
		$this->data->addBody(-140, $datas, $msg, $status);
	}

	/**
	 *   删除店铺 判断是否有商品如果有，就不可以删除
	 *
	 */
	public function delSelfsupport()
	{
		$shop_id             = request_int("shop_id");
		$cond_row['shop_id'] = $shop_id;
		$goods_list          = $this->goodsCommonModel->getOneByWhere($cond_row);
		if (empty($goods_list))
		{
			$del = $this->shopBaseModel->removeBase($shop_id);
			if ($del)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('该店铺下有商品不能被删除！');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 *  修改店铺 页面
	 */
	public function getShopEditRow()
	{
		$shop_id = request_int("shop_id");
		$data    = $this->shopBaseModel->getOne($shop_id);
		$this->data->addBody(-140, $data);
	}

	public function EditShopBase()
	{
		$shop_id                     = request_int("shop_id");
		$shop_base['shop_name']      = request_string("shop_name");
		$shop_base['shop_all_class'] = request_int("shop_all_class");
		$shop_base['shop_status']    = request_int("shop_status");
		$flag                        = $this->shopBaseModel->editBase($shop_id, $shop_base);
                
		if ($flag === false)
		{
			$status = 250;
			$msg    = __('failure');
		}
		else
		{
            if($shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN){
                //如果店铺关闭，商品则全部下架
                //下架goods_base商品 //goods_is_shelves=2
                $goodsBaseModel          = new Goods_BaseModel();
                $goodsBaseModel->editBaseByShopId($shop_id,array('goods_is_shelves'=>$goodsBaseModel::GOODS_DOWN));
                //下架goods_common的商品 common_state=0
                $goodsCommonModel        = new Goods_CommonModel();
                $goodsCommonModel->editCommonByShopId($shop_id,array('common_state'=>$goodsCommonModel::GOODS_STATE_OFFLINE, 'shop_status'=>$shop_base['shop_status']));
            }
			$status = 200;
			$msg    = __('success');
		}
		$data['shop_id'] = $shop_id;
		$this->data->addBody(-140, $data, $msg, $status);

	}

}

?>