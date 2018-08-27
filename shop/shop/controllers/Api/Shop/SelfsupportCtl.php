<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_SelfsupportCtl extends Api_Controller
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
		$cond_row['shop_type'] = 1;
		
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
        $shop_name = request_string("shop_name");
        //名称不超过25个字符
        if(mb_strlen($shop_name,'utf-8') > 25){
            return $this->data->addBody(-140, array(), __('店铺名称不能超过25个字符'), 250);
        }
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
        $formvars['user_type'] = 'self_shop_user';

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

            //开启事物
            $this->UserBaseModel->sql->startTransactionDb();
            $rs_row = array();
			$user_id = $this->UserBaseModel->addBase($data, true);
            if(!$user_id){
                $msg = __('会员账号添加失败');
            }
            check_rs($user_id, $rs_row);
			//初始化用户信息
			$user_info_row                  = array();
			$user_info_row['user_id']       = $user_id;
			$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
			$user_info_row['user_name']     = isset($init_rs['data']['nickname']) ? $init_rs['data']['nickname'] : $data['user_account'];
			$user_info_row['user_mobile']   = @$init_rs['data']['user_mobile'];
			$User_InfoModel                 = new User_InfoModel();
			$info_flag                      = $User_InfoModel->addInfo($user_info_row);
            check_rs($info_flag, $rs_row);
			$user_resource_row                = array();
			$user_resource_row['user_id']     = $user_id;
			$user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;

			$User_ResourceModel = new User_ResourceModel();
			$res_flag           = $User_ResourceModel->addResource($user_resource_row);
            check_rs($res_flag, $rs_row);                           
			$User_PrivacyModel           = new User_PrivacyModel();
			$user_privacy_row['user_id'] = $user_id;
			$privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
            check_rs($privacy_flag, $rs_row);  
			//积分
			$user_points_row['user_id']           = $user_id;
			$user_points_row['user_name']         = request_string("user_name");
			$user_points_row['class_id']          = Points_LogModel::ONREG;
			$user_points_row['points_log_points'] = $user_resource_row['user_points'];
			$user_points_row['points_log_time']   = get_date_time();
			$user_points_row['points_log_desc']   = '会员注册';
			$user_points_row['points_log_flag']   = 'reg';
			$Points_LogModel                      = new Points_LogModel();
			$point_res = $Points_LogModel->addLog($user_points_row);
            check_rs($point_res, $rs_row);  
            
            //店铺信息
            $datas['shop_name']         = $shop_name;
            $datas['user_name']         = request_string("user_name");
            $datas['user_id']           = $user_id;
            $datas['shop_all_class']    = "1";
            $datas['shop_self_support'] = "true";
            $datas['shop_create_time']  = date("y-m-d h-i-s", time());
            $datas['shop_settlement_last_time'] = date("y-m-d h-i-s", time());
            $datas['shop_status']       = "3";
            $check_shop_name = $this->shopBaseModel->getByWhere(array('shop_name'=>$datas['shop_name']));
            if($check_shop_name){
                check_rs(false, $rs_row);
                $msg = '店铺名称已被使用';
            }
            $Number_SeqModel  = new Number_SeqModel();
            $shop_id          = $Number_SeqModel->createSeq('shop_id', 4, false);
            check_rs($shop_id, $rs_row);
            $datas['shop_id'] = $shop_id;
            $district_id = request_string("district_id");
            if(!$this->checkDistrict($district_id)){
                return $this->data->addBody(-140, array(), __('地区请选择到最后一级'), 250); 
            }
            $datas['district_id']    = $district_id;
            $add              = $this->shopBaseModel->addBase($datas);
            check_rs($add, $rs_row);
            
            //添加卖家信息
            $seller_base                    = array();
            $seller_base['shop_id']         = $shop_id;
            $seller_base['user_id']         = $datas['user_id'];
            $seller_base['seller_is_admin'] = 1;

            $Seller_BaseModel = new Seller_BaseModel();
            $seller_flag      = $Seller_BaseModel->addBase($seller_base);
            check_rs($seller_flag, $rs_row);
            if (is_ok($rs_row) && $this->UserBaseModel->sql->commitDb()){
                $status = 200;
                $msg    = __('success');
                /**
                 *  统计中心
                 * shop的注册人数
                 */
                $analytics_ip = get_ip();
                $analytics_data = array(
                    'user_name'=>request_string("user_name"),  //用户账号
                    'user_id'=>$user_id,
                    'ip'=>$analytics_ip,
                    'date'=>date('Y-m-d H:i:s')
                );
                Yf_Plugin_Manager::getInstance()->trigger('analyticsMemberAdd',$analytics_data);
                /******************************************************/
                
                /**
                 *  加入统计中心
                 */
                $analytics_data2 = array(
                    'shop_id'=>$shop_id,
                    'shop_name'=>request_string("shop_name"),
                    'ip'=>$analytics_ip,
                    'date'=>date('Y-m-d', time())
                );
                Yf_Plugin_Manager::getInstance()->trigger('analyticsShopAdd',$analytics_data2);
                /******************************************************************/
            }else{
                $this->UserBaseModel->sql->rollBackDb();
                $status = 250;
                $msg = $msg ? $msg :  __('failure');
            }
		}
		else
		{
			$msg    = !$init_rs['msg'] ? __("该会员名已存在！") :  __($init_rs['msg']);
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
        $Base_DistrictModel = new Base_DistrictModel();
        $district_name = $Base_DistrictModel->getAllName($data['district_id']);
        $data['district_name'] = $district_name;
		$this->data->addBody(-140, $data);
	}

    /**
     * 修改店铺信息
     * 店铺名称不可修改
     * @return type
     */
	public function EditShopBase()
	{
		$shop_id                     = request_int("shop_id");
//		$shop_base['shop_name']      = request_string("shop_name");
		$shop_base['shop_all_class'] = request_int("shop_all_class");
		$shop_base['shop_status']    = request_int("shop_status");
        $new_district_id = request_int("new_district_id",0);
        //名称不超过25个字符
//        if(mb_strlen($shop_base['shop_name'],'utf-8') > 25){
//            return $this->data->addBody(-140, array(), __('店铺名称不能超过25个字符'), 250);
//        }
        if($new_district_id == 0){
            $new_district_id = request_int("district_id",0);
        }
        if(!$this->checkDistrict($new_district_id)){
            return $this->data->addBody(-140, array(), __('地区请选择到最后一级'), 250); 
        }
        $shop_base['district_id']    = $new_district_id;
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
		return $this->data->addBody(-140, $data, $msg, $status);

	}
    
    /**
     *  检查地区
     * @param type $district_id
     */
    private function checkDistrict($district_id){
        if($district_id <= 0){
            return false;
        }
        $Base_DistrictModel = new Base_DistrictModel();
        $check_district = $Base_DistrictModel->getDistrictList(array('district_parent_id'=>$district_id));
        if($check_district['records'] > 0){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     *  自营店铺绑定IM账号
     * 
     */
    public function bindIm(){
        $user_account = request_string('user_account');
        $user_passwd = request_string('user_password');
        $bind_type = request_int('bind_type');
        $data = array();
        $user_base_model = new User_BaseModel();
        $shop_user_info = $user_base_model->getOneByWhere(array('user_account'=>$user_account));
        if(!$shop_user_info){
            return $this->data->addBody(-140, $data, __('用户不存在'), 250); 
        }
        $user_id = $shop_user_info['user_id'];
        $ucenter_user_data = $user_base_model->getUcenterInfo($user_id);
        $user_info = [];
        if($ucenter_user_data['status'] == 200){
            $user_info = $ucenter_user_data['data'];
        }else{
            return $this->data->addBody(-140, $data, __('没有查到该用户信息'), 250); 
        }
        
        if(!$user_info || $user_info['password'] != md5($user_passwd)){
            return $this->data->addBody(-140, $user_info, __('密码有误'), 250); 
        }
        $web_config_model = new Web_ConfigModel();
        $key = 'self_shop_im';
        $im_conf = $web_config_model->getOne($key);
        if($bind_type){
            $msg = __('绑定');
            if($im_conf){
                //edit
                $flag = $web_config_model->editConfig($key,array('config_value'=>$user_account));
            } else {
                //add
                $flag = $web_config_model->addConfig(array('config_key'=>$key,'config_value'=>$user_account,'config_comment'=>'自营店铺绑定IM','config_datatype'=>'string'));
            }
        } else {
            $msg = __('解绑');
            if($im_conf){
                //edit
                $flag = $web_config_model->editConfig($key,array('config_value'=>''));
            } else {
                //add
                $flag = true;
            }
        }
        if($flag !== false){
            return $this->data->addBody(-140, $data, $msg.__('成功'), 200);  
        } else {
            return $this->data->addBody(-140, $data, $msg.__('失败'), 250);  
        }
    }
    
    /**
     * 获取自营账户的IM信息
     * @return type
     */
    public function setIm(){
        $user_account = Web_ConfigModel::value('self_shop_im');
        return $this->data->addBody(-140, array('user_account'=>$user_account), __('绑定成功'), 200); 
    }
    

}

?>