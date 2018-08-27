<?php
class LoginCtl extends Yf_AppController
{
	public function index()
	{
		include $this->view->getView();
	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function login()
	{
		if (!Perm::checkUserPerm())
		{
			$login_url   = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=index&typ=e';
			$reg_url     = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=regist&typ=e';
			$findpwd_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=findpwd&typ=e';

			$callback = Yf_Registry::get('url') . '?ctl=Login&met=check&typ=e&redirect=' . urlencode(request_string('forward'));


			$login_url = $login_url . '&from=shop&callback=' . urlencode($callback);
            setcookie('comeUrl',getenv("HTTP_REFERER"));
			header('location:' . $login_url);
			exit();
		}
		else
		{
			header('location:' . Yf_Registry::get('url'));
		}
	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function reg()
	{
		if (!Perm::checkUserPerm())
		{
			$login_url   = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=index&typ=e';
			$reg_url     = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=regist&typ=e';
			$findpwd_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=findpwd&typ=e';

			$callback = Yf_Registry::get('url') . '?ctl=Login&met=check&typ=e&redirect=' . urlencode(request_string('forward'));


			$login_url = $reg_url . '&from=shop&callback=' . urlencode($callback);
			header('location:' . $login_url);
			exit();
		}
		else
		{
			header('location:' . Yf_Registry::get('url'));
		}
	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function check()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$redirect = request_string('redirect');

		$User_BaseModel  = new User_BaseModel();
		$User_InfoModel  = new User_InfoModel();
		$Points_LogModel = new Points_LogModel();

		$formvars            = array();
		$formvars['user_id'] = request_int('us');
		$formvars['u']       = request_int('us');
		$formvars['k']       = request_string('ks');
		$formvars['app_id']  = $app_id;

		$url     = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Login', 'checkLogin', 'json');
		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'])
		{
			//读取服务列表
			$user_row  = $init_rs['data'];
			$user_id   = $user_row['user_id'];
			$user_name = $user_row['user_name'];

			//本地数据校验登录
			$user_row = $User_BaseModel->getOne($user_id);

			if ($user_row)
			{
				//判断状态是否开启
				if ($user_row['user_delete'] == 1)
				{
					$msg = __('该账户未启用，请启用后登录！');
					if ('e' == $this->typ)
					{
						location_go_back(__('初始化用户出错1!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
			}
			else
			{
				//添加用户
				//$data['user_id']       = $user_row['user_id']; // 用户id
				//$data['user_account']  = $user_row['user_name']; // 用户帐号

				$data['user_id']      = $init_rs['data']['user_id']; // 用户id
				$data['user_account'] = $init_rs['data']['user_name']; // 用户帐号

				$data['user_delete'] = 0; // 用户状态
				$user_id             = $User_BaseModel->addBase($data, true);

				//判断状态是否开启
				if (!$user_id)
				{
					$msg = __('初始化用户出错2!').$init_rs['data']['user_id'].$init_rs['data']['user_name'].$user_id;
					if ('e' == $this->typ)
					{
						location_go_back(__('初始化用户出错3!'));
					}
					else
					{
						return $this->data->setError($msg, $data);
					}
				}
				else
				{
					//初始化用户信息
					$user_info_row                  = array();
					$user_info_row['user_id']       = $user_id;
					$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
					$user_info_row['user_name']     = @$data['user_account'];
					$user_info_row['user_mobile']   = @$init_rs['data']['user_mobile'];
					$user_info_row['user_logo']   = @$init_rs['data']['user_avatar'];
					$user_info_row['user_regtime']  = get_date_time();
					$User_InfoModel                 = new User_InfoModel();
					$info_flag                      = $User_InfoModel->addInfo($user_info_row);


					
					if(Web_ConfigModel::value('Plugin_Fenxiao'))
					{
						if($_COOKIE['uu_id'])
						{
							Fenxiao::getInstance()->addUserRelationship($user_id,$_COOKIE['uu_id']);
						}
					}


					if(Web_ConfigModel::value('Plugin_Directseller'))
					{
						if($_COOKIE['yf_recserialize'])
						{
							//regDone
							$PluginManager = Yf_Plugin_Manager::getInstance();
							$PluginManager->trigger('regDone',$user_id);
						}

					}

					$user_resource_row                = array();
					$user_resource_row['user_id']     = $user_id;
					$user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;
					
					$User_ResourceModel = new User_ResourceModel();
					$res_flag           = $User_ResourceModel->addResource($user_resource_row);

					$User_PrivacyModel           = new User_PrivacyModel();
					$user_privacy_row['user_id'] = $user_id;
					$privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
					//积分
					$user_points_row['user_id']           = $user_id;
					$user_points_row['user_name']         = $data['user_account'];
					$user_points_row['class_id']          = Points_LogModel::ONREG;
					$user_points_row['points_log_points'] = $user_resource_row['user_points'];
					$user_points_row['points_log_time']   = get_date_time();
					$user_points_row['points_log_desc']   = __('会员注册');
					$user_points_row['points_log_flag']   = 'reg';
					$Points_LogModel->addLog($user_points_row);
					//发送站内信
					$message = new MessageModel();
					$message->sendMessage('welcome', $user_id, $data['user_account'], '', '', 0, MessageModel::OTHER_MESSAGE);
                    
                    /**
                     *  统计中心
                     * shop的注册人数
                     */
                    $analytics_ip = isset($init_rs['data']['user_reg_ip']) ? $init_rs['data']['user_reg_ip'] : get_ip();
                    $analytics_data = array(
                        'user_name'=>$data['user_account'],  //用户账号
                        'user_id'=>$user_id,
                        'ip'=>$analytics_ip,
                        'date'=>date('Y-m-d H:i:s')
                    );
		    
		    Yf_Plugin_Manager::getInstance()->trigger('analyticsMemberAdd',$analytics_data);	
                    /******************************************************/
				}

				$user_row = $data;
			}
 
			if ($user_row)
			{
				/*//分销
				if(Web_ConfigModel::value('Plugin_Directseller'))
				{
					if($_COOKIE['yf_recserialize'])
					{
						//regDone
						$PluginManager = Yf_Plugin_Manager::getInstance();
						$PluginManager->trigger('regDone',$user_id);
					}
				}*/

				$data            = array();
				$data['user_id'] = $user_row['user_id'];
				srand((double)microtime() * 1000000);
				//$user_key = md5(rand(0, 32000));
				$user_key = $init_rs['data']['session_id'];
				$time     = get_date_time();
				//获取上次登录的时间
				$info = $User_BaseModel->getBase($user_row['user_id']);
				
				$lotime   = strtotime($info[$user_row['user_id']]['user_login_time']);
				$last_day = date("d ", $lotime);
				$now_day  = date("d ");
				$now      = time();

				$login_info_row                     = array();
				$login_info_row['user_key']         = $user_key;
				$login_info_row['user_login_time']  = $time;
				$login_info_row['user_login_times'] = $info[$user_row['user_id']]['user_login_times'] + 1;
				$login_info_row['user_login_ip']    = get_ip();

				$flag = $User_BaseModel->editBase($user_row['user_id'], $login_info_row, false);
				
				$login_row['user_logintime'] = $time;
				$login_row['lastlogintime']  = $info[$user_row['user_id']]['user_login_time'];
				$login_row['user_ip']        = get_ip();
				$login_row['user_lastip']    = $info[$user_row['user_id']]['user_login_ip'];
				$flag                        = $User_InfoModel->editInfo($user_row['user_id'], $login_row, false);
				//当天没有登录过执行

				if ($last_day != $now_day && $now > $lotime)
				{

					$user_points = Web_ConfigModel::value("points_login");
					$user_grade  = Web_ConfigModel::value("grade_login");
					
					$User_ResourceModel = new User_ResourceModel();
					//获取当前登录的积分经验值
					$ce = $User_ResourceModel->getResource($user_row['user_id']);
					
					$resource_row['user_points'] = $ce[$user_row['user_id']]['user_points'] * 1 + $user_points * 1;
					$resource_row['user_growth'] = $ce[$user_row['user_id']]['user_growth'] * 1 + $user_grade * 1;
					
					$res_flag = $User_ResourceModel->editResource($user_row['user_id'], $resource_row);
					
					$User_GradeModel = new User_GradeModel;
					//升级判断
					$res_flag = $User_GradeModel->upGrade($user_row['user_id'], $resource_row['user_growth']);
					//积分
					$points_row['user_id']           = $user_id;
					$points_row['user_name']         = $user_row['user_account'];
					$points_row['class_id']          = Points_LogModel::ONLOGIN;
					$points_row['points_log_points'] = $user_points;
					$points_row['points_log_time']   = $time;
					$points_row['points_log_desc']   = __('会员登录');
					$points_row['points_log_flag']   = 'login';

					$Points_LogModel = new Points_LogModel();

					$Points_LogModel->addLog($points_row);
					
					//成长值
					$grade_row['user_id']         = $user_id;
					$grade_row['user_name']       = $user_row['user_account'];
					$grade_row['class_id']        = Grade_LogModel::ONLOGIN;
					$grade_row['grade_log_grade'] = $user_grade;
					$grade_row['grade_log_time']  = $time;
					$grade_row['grade_log_desc']  = __('会员登录');
					$grade_row['grade_log_flag']  = 'login';

					$Grade_LogModel = new Grade_LogModel;
					$Grade_LogModel->addLog($grade_row);
				}

				//$flag     = $User_BaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
				Yf_Hash::setKey($user_key);

				//
				$Seller_BaseModel = new Seller_BaseModel();
				$seller_rows      = $Seller_BaseModel->getByWhere(array('user_id' => $data['user_id']));
				$Chain_UserModel  = new Chain_UserModel();
				$chain_rows 		  = $Chain_UserModel->getByWhere(array('user_id' => $data['user_id']));
				if($chain_rows)
				{
					$data['chain_id_row']	 = array_column($chain_rows,'chain_id');
					$data['chain_id']	   = current($data['chain_id_row']);
				}
				else
				{
					$data['chain_id'] = 0;
				}
				if ($seller_rows)
				{
					$data['shop_id_row'] = array_column($seller_rows, 'shop_id');
					$data['shop_id']     = current($data['shop_id_row']);
				}
				else
				{
					$data['shop_id'] = 0;
				}
				//user_account 这个COOKIE IM是需要的。by sunkang
				$data['user_account'] = $user_row['user_account'];

				$encrypt_str = Perm::encryptUserInfo($data);
			 
				/////
				
				//更新购物车
				$cartlist = array();
				if(isset($_COOKIE['goods_cart']))
				{
					$cartlist = $_COOKIE['goods_cart'];
				}


				if($cartlist)
				{
					$CartModel = new CartModel();
					$CartModel->updateCookieCart($data['user_id']);
				}

				if(isset($_COOKIE['goods_cart']))
				{
					setcookie("goods_cart",null,time() - 1,'/');
				}

				if ('e' == $this->typ)
				{
					if($redirect)
					{
						location_to(urldecode($redirect));
					}
					else
					{
                        if($_COOKIE['comeUrl']){
                            location_to($_COOKIE['comeUrl']);
                        }else if($chain_rows){
                            location_to(Yf_Registry::get('url').'?ctl=Chain_Goods&met=goods&typ=e');
                        }else{
                            //location_to(Yf_Registry::get('base_url') . "/error.php?msg=您的帐号不是门店帐号");
							location_to(Yf_Registry::get('base_url'));
                        }
					}

				}
				else
				{
					$data            = array();
					$data['user_id'] = $user_row['user_id'];
					$data['user_account'] = $user_row['user_account'];
					$data['key'] = $encrypt_str;

					$this->data->addBody(100, $data);
				}
			}
			else
			{
				$msg = __('登录出错！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}
			}
		}
		else
		{
			$msg = __('登录信息有误！');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}

		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}


	/**
	 * 用户登录,通过本站输入用户名密码登录
	 *
	 * @access public
	 */
	public function doLogin()
	{
		$Points_LogModel = new Points_LogModel();
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$user_account = $_REQUEST['user_account'];


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['user_account']  = $_REQUEST['user_account'];
		$formvars['user_password'] = $_REQUEST['user_password'];
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);


		if (200 == $init_rs['status'])
		{
			//读取服务列表
		}
		else
		{
			$msg = __('登录信息有误');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}


		$userBaseModel = new User_BaseModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getBase($user_id_row);
			$user_row  = array_pop($user_rows);
			//判断状态是否开启
			if ($user_row['user_delete'] == 1)
			{

				$msg = __('该账户未启用，请启用后登录！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}
			}
			//fb($user_row);
		}
		else
		{
			$user_row = $init_rs['data'];

			//添加用户
			$data['user_id']       = $user_row['user_id']; // 用户id
			$data['user_account']  = $user_row['user_name']; // 用户帐号
			$data['user_passwd'] = $user_row['password']; // 密码：使用用户中心-此处废弃

			$data['user_delete'] = 0; // 用户状态
			$user_id             = $userBaseModel->addBase($data, true);

			//初始化用户信息
			$user_info_row                  = array();
			$user_info_row['user_id']       = $user_id;
			$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
			$user_info_row['user_name']     = isset($init_rs['data']['nickname']) ? $init_rs['data']['nickname'] : $data['user_account'];
			$user_info_row['user_mobile']   = @$init_rs['data']['user_mobile'];
			$user_info_row['user_logo']   = @$init_rs['data']['user_avatar'];
			$user_info_row['user_regtime']  = get_date_time();
			$info_flag                      = $User_InfoModel->addInfo($user_info_row);

			$user_resource_row                = array();
			$user_resource_row['user_id']     = $user_id;
			$user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;

			$User_ResourceModel = new User_ResourceModel();
			$res_flag           = $User_ResourceModel->addResource($user_resource_row);

			$User_PrivacyModel           = new User_PrivacyModel();
			$user_privacy_row['user_id'] = $user_id;
			$privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
			//积分
			$user_points_row['user_id']           = $user_id;
			$user_points_row['user_name']         = $data['user_account'];
			$user_points_row['class_id']          = Points_LogModel::ONREG;
			$user_points_row['points_log_points'] = $user_resource_row['user_points'];
			$user_points_row['points_log_time']   = get_date_time();
			$user_points_row['points_log_desc']   = __('会员注册');
			$user_points_row['points_log_flag']   = 'reg';
			$Points_LogModel->addLog($user_points_row);
			//发送站内信
			$message = new MessageModel();
			$message->sendMessage('welcome', $user_id, $data['user_account'], '', '', 0, MessageModel::OTHER_MESSAGE);


			//判断状态是否开启
			if (!$user_id)
			{

				$msg = __('初始化用户出错44！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}

			}
            
                   /**
                     *  统计中心
                     * shop的注册人数
                     */
                    $analytics_ip = isset($init_rs['data']['user_reg_ip']) ? $init_rs['data']['user_reg_ip'] : get_ip();
                    $analytics_data = array(
                        'user_name'=>$data['user_account'],  //用户账号
                        'user_id'=>$user_id,
                        'ip'=>$analytics_ip,
                        'date'=>date('Y-m-d H:i:s')
                    );
		    
		    Yf_Plugin_Manager::getInstance()->trigger('analyticsMemberAdd',$analytics_data);	
                    /******************************************************/
		}

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_row)
		{
			$data            = array();
			$data['user_id'] = $user_row['user_id'];
			srand((double)microtime() * 1000000);
			//$user_key = md5(rand(0, 32000));
			$user_key = $init_rs['data']['session_id'];

			$time     = get_date_time();
			//获取上次登录的时间
			$info = $User_BaseModel->getBase($user_row['user_id']);

			$lotime   = strtotime($info[$user_row['user_id']]['user_login_time']);
			$last_day = date("d ", $lotime);
			$now_day  = date("d ");
			$now      = time();

			$login_info_row                     = array();
			$login_info_row['user_key']         = $user_key;
			$login_info_row['user_login_time']  = $time;
			$login_info_row['user_login_times'] = $info[$user_row['user_id']]['user_login_times'] + 1;
			$login_info_row['user_login_ip']    = get_ip();

			$flag = $User_BaseModel->editBase($user_row['user_id'], $login_info_row, false);

			$login_row['user_logintime'] = $time;
			$login_row['lastlogintime']  = $info[$user_row['user_id']]['user_login_time'];
			$login_row['user_ip']        = get_ip();
			$login_row['user_lastip']    = $info[$user_row['user_id']]['user_login_ip'];
			$flag                        = $User_InfoModel->editInfo($user_row['user_id'], $login_row, false);
			//当天没有登录过执行

			if ($last_day != $now_day && $now > $lotime)
			{

				$user_points = Web_ConfigModel::value("points_login");
				$user_grade  = Web_ConfigModel::value("grade_login");

				$User_ResourceModel = new User_ResourceModel();
				//获取当前登录的积分经验值
				$ce = $User_ResourceModel->getResource($user_row['user_id']);

				$resource_row['user_points'] = $ce[$user_row['user_id']]['user_points'] * 1 + $user_points * 1;
				$resource_row['user_growth'] = $ce[$user_row['user_id']]['user_growth'] * 1 + $user_grade * 1;

				$res_flag = $User_ResourceModel->editResource($user_row['user_id'], $resource_row);

				$User_GradeModel = new User_GradeModel;
				//升级判断
				$res_flag = $User_GradeModel->upGrade($user_row['user_id'], $resource_row['user_growth']);
				//积分
				$points_row['user_id']           = $user_id;
				$points_row['user_name']         = $user_row['user_account'];
				$points_row['class_id']          = Points_LogModel::ONLOGIN;
				$points_row['points_log_points'] = $user_points;
				$points_row['points_log_time']   = $time;
				$points_row['points_log_desc']   = __('会员登录');
				$points_row['points_log_flag']   = 'login';

				$Points_LogModel = new Points_LogModel();

				$Points_LogModel->addLog($points_row);

				//成长值
				$grade_row['user_id']         = $user_id;
				$grade_row['user_name']       = $user_row['user_account'];
				$grade_row['class_id']        = Grade_LogModel::ONLOGIN;
				$grade_row['grade_log_grade'] = $user_grade;
				$grade_row['grade_log_time']  = $time;
				$grade_row['grade_log_desc']  = __('会员登录');
				$grade_row['grade_log_flag']  = 'login';

				$Grade_LogModel = new Grade_LogModel;
				$Grade_LogModel->addLog($grade_row);
			}

			//$flag     = $userBaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
			Yf_Hash::setKey($user_key);
			$encrypt_str = Perm::encryptUserInfo($data);

			if ('e' == $this->typ)
			{
				location_to(Yf_Registry::get('base_url'));
			}
			else
			{
				$data['user_account'] = $formvars['user_account'];
				$data['key'] = $encrypt_str;
				$this->data->addBody(100, $data);
			}

		}
		else
		{
			$msg = __('输入密码有误！');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}

		//权限设置

	}


	/**
	 * 用户登录,通过本站输入用户名密码登录
	 *
	 * @access public
	 */
	public function doRegister()
	{
		$Points_LogModel = new Points_LogModel();
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$user_account = $_REQUEST['user_account'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['user_account']  = $_REQUEST['user_account'];
		$formvars['user_password'] = $_REQUEST['user_password'];
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);


		if (200 == $init_rs['status'])
		{
			//读取服务列表
		}
		else
		{
			$msg = __('登录信息有误');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}


		$userBaseModel = new User_BaseModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getBase($user_id_row);
			$user_row  = array_pop($user_rows);
			//判断状态是否开启
			if ($user_row['user_delete'] == 1)
			{

				$msg = __('该账户未启用，请启用后登录！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}
			}
			//fb($user_row);
		}
		else
		{
			$user_row = $init_rs['data'];

			//添加用户
			$data['user_id']       = $user_row['user_id']; // 用户id
			$data['user_account']  = $user_row['user_name']; // 用户帐号
			$data['user_password'] = $user_row['password']; // 密码：使用用户中心-此处废弃

			$data['user_delete'] = 0; // 用户状态
			$user_id             = $userBaseModel->addBase($data, true);

			//初始化用户信息
			$user_info_row                  = array();
			$user_info_row['user_id']       = $user_id;
			$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
			$user_info_row['user_name']     = isset($init_rs['data']['nickname']) ? $init_rs['data']['nickname'] : $data['user_account'];
			$user_info_row['user_mobile']   = @$init_rs['data']['user_mobile'];
			$user_info_row['user_logo']   = @$init_rs['data']['user_avatar'];
			$user_info_row['user_regtime']  = get_date_time();
			$info_flag                      = $User_InfoModel->addInfo($user_info_row);

			$user_resource_row                = array();
			$user_resource_row['user_id']     = $user_id;
			$user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;

			$User_ResourceModel = new User_ResourceModel();
			$res_flag           = $User_ResourceModel->addResource($user_resource_row);

			$User_PrivacyModel           = new User_PrivacyModel();
			$user_privacy_row['user_id'] = $user_id;
			$privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
			//积分
			$user_points_row['user_id']           = $user_id;
			$user_points_row['user_name']         = $data['user_account'];
			$user_points_row['class_id']          = Points_LogModel::ONREG;
			$user_points_row['points_log_points'] = $user_resource_row['user_points'];
			$user_points_row['points_log_time']   = get_date_time();
			$user_points_row['points_log_desc']   = __('会员注册');
			$user_points_row['points_log_flag']   = 'reg';
			$Points_LogModel->addLog($user_points_row);
			//发送站内信
			$message = new MessageModel();
			$message->sendMessage('welcome', $user_id, $data['user_account'], '', '', 0, MessageModel::OTHER_MESSAGE);


			//判断状态是否开启
			if (!$user_id)
			{

				$msg = __('初始化用户出错55！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}

			}
		}

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_row)
		{
			$data            = array();
			$data['user_id'] = $user_row['user_id'];
			srand((double)microtime() * 1000000);
			//$user_key = md5(rand(0, 32000));
			$user_key = $init_rs['data']['session_id'];

			$time     = get_date_time();
			//获取上次登录的时间
			$info = $User_BaseModel->getBase($user_row['user_id']);

			$lotime   = strtotime($info[$user_row['user_id']]['user_login_time']);
			$last_day = date("d ", $lotime);
			$now_day  = date("d ");
			$now      = time();

			$login_info_row                     = array();
			$login_info_row['user_key']         = $user_key;
			$login_info_row['user_login_time']  = $time;
			$login_info_row['user_login_times'] = $info[$user_row['user_id']]['user_login_times'] + 1;
			$login_info_row['user_login_ip']    = get_ip();

			$flag = $User_BaseModel->editBase($user_row['user_id'], $login_info_row, false);

			$login_row['user_logintime'] = $time;
			$login_row['lastlogintime']  = $info[$user_row['user_id']]['user_login_time'];
			$login_row['user_ip']        = get_ip();
			$login_row['user_lastip']    = $info[$user_row['user_id']]['user_login_ip'];
			$flag                        = $User_InfoModel->editInfo($user_row['user_id'], $login_row, false);
			//当天没有登录过执行

			if ($last_day != $now_day && $now > $lotime)
			{

				$user_points = Web_ConfigModel::value("points_login");
				$user_grade  = Web_ConfigModel::value("grade_login");

				$User_ResourceModel = new User_ResourceModel();
				//获取当前登录的积分经验值
				$ce = $User_ResourceModel->getResource($user_row['user_id']);

				$resource_row['user_points'] = $ce[$user_row['user_id']]['user_points'] * 1 + $user_points * 1;
				$resource_row['user_growth'] = $ce[$user_row['user_id']]['user_growth'] * 1 + $user_grade * 1;

				$res_flag = $User_ResourceModel->editResource($user_row['user_id'], $resource_row);

				$User_GradeModel = new User_GradeModel;
				//升级判断
				$res_flag = $User_GradeModel->upGrade($user_row['user_id'], $resource_row['user_growth']);
				//积分
				$points_row['user_id']           = $user_id;
				$points_row['user_name']         = $user_row['user_account'];
				$points_row['class_id']          = Points_LogModel::ONLOGIN;
				$points_row['points_log_points'] = $user_points;
				$points_row['points_log_time']   = $time;
				$points_row['points_log_desc']   = __('会员登录');
				$points_row['points_log_flag']   = 'login';

				$Points_LogModel = new Points_LogModel();

				$Points_LogModel->addLog($points_row);

				//成长值
				$grade_row['user_id']         = $user_id;
				$grade_row['user_name']       = $user_row['user_account'];
				$grade_row['class_id']        = Grade_LogModel::ONLOGIN;
				$grade_row['grade_log_grade'] = $user_grade;
				$grade_row['grade_log_time']  = $time;
				$grade_row['grade_log_desc']  = __('会员登录');
				$grade_row['grade_log_flag']  = 'login';

				$Grade_LogModel = new Grade_LogModel;
				$Grade_LogModel->addLog($grade_row);
			}

			//$flag     = $userBaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
			Yf_Hash::setKey($user_key);
			$encrypt_str = Perm::encryptUserInfo($data);

			if ('e' == $this->typ)
			{
				location_to(Yf_Registry::get('base_url'));
			}
			else
			{
				$data['user_account'] = $formvars['user_account'];
				$data['key'] = $encrypt_str;
				$this->data->addBody(100, $data);
			}

		}
		else
		{
			$msg = __('输入密码有误！');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}

		//权限设置

	}


	//获取注册密码
	public function regCode1()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['user_account']  = $_REQUEST['user_account'];
		$formvars['user_password'] = $_REQUEST['user_password'];
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);
	}


	/*
	 * 用户退出
	 *
	 *
	 */
	public function loginout()
	{
		if ($_REQUEST['met'] == 'loginout')
		{
			if (isset($_COOKIE['key']) || isset($_COOKIE['id']))
			{
				echo "<script>parent.location.href='index.php';</script>";
				setcookie("key", null, time() - 3600 * 24 * 365);
				setcookie("id", null, time() - 3600 * 24 * 365);
				setcookie("user_account", null, time() - 3600 * 24 * 365);

				setcookie("key", null, time() - 3600 * 24 * 365,'/');
				setcookie("id", null, time() - 3600 * 24 * 365,'/');
				setcookie("user_account", null, time() - 3600 * 24 * 365,'/');
			}


			$login_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=logout&typ=e';
			$callback  = Yf_Registry::get('url') . '?redirect=' . urlencode(Yf_Registry::get('url')) . '&type=ucenter';

			$login_url = $login_url . '&from=shop&callback=' . urlencode($callback);

			header('location:' . $login_url);
			exit();
		}
	}


	public function doLoginOut()
	{
		if (isset($_COOKIE['key']) || isset($_COOKIE['id']))
		{
			echo "<script>parent.location.href='index.php';</script>";
			setcookie("key", null, time() - 3600 * 24 * 365);
			setcookie("id", null, time() - 3600 * 24 * 365);
		}

		$redirect = request_string('redirect');
		if($redirect)
		{
			header('location:' . $redirect);
			exit();
		}


		/*//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['user_account']  = $_REQUEST['user_account'];
		$formvars['user_password'] = $_REQUEST['user_password'];
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'loginout';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		$this->data->addBody(100, $init_rs);*/

	}
    
    function getYzm(){
        if(!Perm::$userId){
            return  $this->data->addBody(-140,array() , '请登录', 250);
        }
        $mobile = request_string('mobile');
        $email = request_string('email');
        $yzm = request_string('yzm');
        $check_code = mt_rand(100000, 999999);

        if($mobile && Yf_Utils_String::isMobile($mobile)){
            //判断手机号是否已经使用
            $Shop_CompanyModel = new Shop_CompanyModel();
            $checkmobile = $Shop_CompanyModel->getByWhere(array('contacts_phone'=>$mobile));
            if(isset($checkmobile['items']) && $checkmobile['items']){
                return $this->data->addBody(-140,array() , __('该手机号已使用'), 250);
            }
            $save_result = $this->_saveCodeCache($mobile,$check_code,'verify_code');
            if(!$save_result){
                return $this->data->addBody(-140,array() , __('验证码获取失败'), 250);
            }
            //发送短消息
            if(!Perm::checkYzm($yzm)){
                return $this->data->addBody(-140,array() , __('图形验证码有误'), 250);
            }
            //发送短消息
            $message_tpl_model = new Message_TemplateModel();
            $content_data = array(
                '[weburl_name]'=>Web_ConfigModel::value("site_name"),
                '[yzm]'=>$check_code
            );
            $result = $message_tpl_model->sendMessage($mobile,'phone','shop_personal_settled' , $content_data);

            if (200 == $result['status']){
                $msg    = __('发送成功');
                $status = 200;
            }else{
                $msg    =  $result['msg'] ? __($result['msg']) : __('发送失败');
                $status = 250;
            }
        }else if($email && Yf_Utils_String::isEmail($email)){
            //判断邮箱是否已经注册过
            $Shop_CompanyModel = new Shop_CompanyModel();
            $checkemail = $Shop_CompanyModel->getByWhere(array('contacts_email'=>$email));
            if(isset($checkemail['items']) && $checkemail['items']){
                return $this->data->addBody(-140,array() , __('该邮箱已被使用'), 250);
            }
            $save_result = $this->_saveCodeCache($email,$check_code,'verify_code');
            if(!$save_result){
                return $this->data->addBody(-140,array() , __('验证码获取失败'), 250);
            }
            //发送邮件
            $message_tpl_model = new Message_TemplateModel();
            $content_data = array(
                '[weburl_name]'=>Web_ConfigModel::value("site_name"),
                '[yzm]'=>$check_code
            );
            $result = $message_tpl_model->sendMessage($email,'email','shop_personal_settled' , $content_data);
            if ($result)
            {
                $msg    = __('发送成功');
                $status = 200;
            }else{
                $msg    = __('发送失败');
                $status = 250;
            }

        }else{
            $msg    = __('信息有误');
            $status = 250;
        }

		$data = array();
		if(DEBUG === false){
			$data['user_code'] = $check_code;
		}
        return $this->data->addBody(-140,$data , $msg, $status);
    }
    
    /**
     *  缓存验证码
     * @param type $key
     * @param type $value
     * @param type $group
     * @return type
     */
    private function _saveCodeCache($key,$value,$group='default'){
        
        $config_cache = Yf_Registry::get('config_cache');
        if (!file_exists($config_cache[$group]['cacheDir'])){
            mkdir($config_cache[$group]['cacheDir'],0777);
        }
        $Cache_Lite = new Cache_Lite_Output($config_cache[$group]);
        $result = $Cache_Lite->save($value, $key);
        return $result;
    }
}

?>