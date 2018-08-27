<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo Yf_AppController
 */
class ApiCtl extends  Api_Controller
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

		//include $this->view->getView();

		$app_id = isset($_REQUEST['app_id']) ?  $_REQUEST['app_id'] : 0;
		$Base_App = new Base_AppModel();

		/*if($app_id && !($base_app_rows = $Base_App->getApp($app_id)))
		{
			$this->data->setError('App id 不存在');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}*/

		/*
		$base_app_row = array_pop($base_app_rows);
		$key = $base_app_row['app_key'];

		if (!check_url_with_encrypt($key, $_POST))
		{
			$this->data->setError('协议数据有误');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}
		*/
	}

	/**
	 * 验证API是否正确
	 *
	 * @access public
	 */
	public function checkApi()
	{
		$this->data->addBody(-140, array());
	}

	public function getAppServerList()
	{
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'asc';
		$server_id = isset($_REQUEST['server_id']) ? intval($_REQUEST['server_id']) : 0;


		Yf_Log::log($server_id, Yf_Log::INFO, 'server_id_row_server_id_request');


		$app_id = isset($_REQUEST['request_app_id']) ? intval($_REQUEST['request_app_id']) : null;

		$cloud_type = request_int('cloud_type', 0);

		if (!$app_id)
		{
			$this->data->setError('请输入正确的app id');
			return ;
		}

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
			return ;
		}

		$Base_AppServerModel = new Base_AppServerModel();


		Yf_Log::log($server_id, Yf_Log::INFO, 'server_id_row_server_id');

		if ($server_id)
		{
			$Base_AppServerModel->sql->setWhere('server_id', $server_id);
		}

		$Base_AppServerModel->sql->setWhere('cloud_type', $cloud_type);

		$app_server_rows = $Base_AppServerModel->getAppServerList($app_id, $page, $rows, $sort);

		if ($app_server_rows)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $app_server_rows, $msg, $status);
	}


	public function bindUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
		$server_id = isset($_REQUEST['server_id']) ? $_REQUEST['server_id'] : null;
		if (!$user_name)
		{
			$this->data->setError('user_name 不存在');
			return false;
		}

		if (!$server_id)
		{
			$this->data->setError('server_id 不存在');
			return false;
		}

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError(' $app_id 参数错误');
			return false;
		}


		$Base_AppServerModel = new Base_AppServerModel();
		$base_app_server_rows = $Base_AppServerModel->getAppServer($server_id);

		if(!$base_app_server_rows)
		{
			$this->data->setError(' $server_id 参数错误');
			return false;
		}

		$rs_row = array();
		$Base_App->sql->startTransaction();


		$User_AppServerModel = new User_AppServerModel();

		$user_app_server_row = array();
		$user_app_server_row['user_name'] = $user_name;
		$user_app_server_row['app_id'] = $app_id;
		$user_app_server_row['server_id'] = $server_id;
		$user_app_server_row['active_time'] = time();

		$flag = $User_AppServerModel->addAppServer($user_app_server_row);


		if (is_ok($rs_row) && $Base_App->sql->commit())
		{
			$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id);
			$this->data->addBody(100, $arr_body);
		}
		else
		{
			$Base_App->sql->rollBack();
			$this->data->setError('绑定失败');
		}
	}

	public function createUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
		if (!$user_name)
		{
			$this->data->setError('user_name 不存在');
			return false;
		}

		$password = '111111';
		$user_mobile = $_REQUEST['company_phone'];

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError('参数错误');
		}


		$rs_row = array();
		$Base_App->sql->startTransaction();


		$Db  = Yf_Db::get('ucenter');
		$seq_name = 'server_id';
		$server_id = $Db->nextId($seq_name);
		$db_name = 'db_' . $server_id;

		//添加app server
		$data['server_id']              = $server_id                      ; //
		$data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
		$data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称
		$data['server_url']             = $_REQUEST['server_url']         ; // 服务器url
		$data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序
		$data['app_id']                 = $app_id                         ; // 所属游戏id
		$data['company_id']             = $_REQUEST['company_id']         ; // 运营商id
		$data['server_type']            = 1                               ; // 服类型,1:new,2:hot,3:满 4:维护
		$data['server_state']           = 0                               ; // 服务器状态,0:备运   1:开服中 2、停服,3:服务器宕机
		$data['socket_ip']              = ''                              ; // socket 的ip地址
		$data['socket_port']            = 9000                            ; // socket的端口号
		$data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间
		$data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间
		$data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示
		$data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
		$data['company_name']           = $_REQUEST['company_name']       ; // 公司名称
		$data['company_phone']          = $user_mobile                    ; // 电话
		$data['contacter']              = $_REQUEST['contacter']          ; // 联系人
		$data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间
		$data['account_num']            = $_REQUEST['account_num']        ; // 账号个数
		$data['db_host']                = ''                              ; // 数据库IP
		$data['db_name']                = ''                              ; // 数据库名
		$data['db_passwd']              = Text_Password::create(10)       ; // 数据库密码
		$data['upload_path']            = ''                              ; // 附件存放地址
		$data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表
		$data['price']                  = $_REQUEST['price']              ; // 费用
		$data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
		$data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1

		$Base_AppServerModel = new Base_AppServerModel();
		$flag = $Base_AppServerModel->addAppServer($data, true);
		array_push($rs_row, $flag);

		//用户App
		$User_App = new User_App();


		$arr_field_user_app = array();
		$arr_field_user_app['user_name'] = $user_name;
		$arr_field_user_app['app_id'] = $app_id;
		$arr_field_user_app['active_time'] = time();

		$flag = $User_App->addApp($arr_field_user_app);
		array_push($rs_row, $flag);


		$User_AppServerModel = new User_AppServerModel();

		$user_app_server_row = array();
		$user_app_server_row['user_name'] = $user_name;
		$user_app_server_row['app_id'] = $app_id;
		$user_app_server_row['server_id'] = $server_id;
		$user_app_server_row['active_time'] = time();

		$flag = $User_AppServerModel->addAppServer($user_app_server_row);


		if (is_ok($rs_row) && $Base_App->sql->commit())
		{
			$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id);
			$this->data->addBody(100, $arr_body);
		}
		else
		{
			$Base_App->sql->rollBack();
			$this->data->setError('绑定失败');
		}
	}


	public function addUserAndBindAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$server_id = $_REQUEST['server_id'];

		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
		$password = $_REQUEST['password'];
		$user_mobile = $_REQUEST['company_phone'];
        $user_type = $_REQUEST['user_type'];  //判断是否自营
		if (!$user_name)
		{
			$this->data->setError('user_name 不存在');
			return false;
		}

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
		}
		else
		{
			$base_app_row = array_pop($base_app_rows);
			$rs_row = array();
			$Base_App->sql->startTransaction();

			//用户是否存在
			$User_InfoModel = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetail();


			$user_rows = $User_InfoModel->getInfoByName($user_name);

			if ($user_rows)
			{
				//
				if (request_string('is_install'))
				{
					if ($user_rows['password'] == md5($password))
					{
						$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id, "user_id"=>$user_rows['user_id']);
						$this->data->addBody(100, $arr_body);
						return true;
					}
					else
					{
						$this->data->setError('用户密码不正确，请和商城后台账户密码保持一致!');
						return false;
					}
				}
				else
				{
					$this->data->setError('用户已经存在,请更换用户名!');
					return false;
				}
			}
			else
			{

                //判断用户名是否存在
                $check_user_name = $User_InfoDetail->checkUserName($user_name);
                if($check_user_name){
                    $this->data->setError('该用户名已经被使用');
					return false; 
                }
                
				$Db  = Yf_Db::get('ucenter');
				$seq_name = 'user_id';
				$user_id = $Db->nextId($seq_name);

				//$User_InfoModel->check_input($user_name, $password, $user_mobile);

				$now_time = time();
				$ip = get_ip();

				$session_id = uniqid();
				$arr_field_user_info = array();
				$arr_field_user_info['user_id'] = $user_id;
				$arr_field_user_info['user_name'] = $user_name;
				$arr_field_user_info['password'] = md5($password);
				$arr_field_user_info['action_time'] = $now_time;
				$arr_field_user_info['action_ip'] = $ip;
				$arr_field_user_info['session_id'] = $session_id;

				$flag = $User_InfoModel->addInfo($arr_field_user_info);
				array_push($rs_row, $flag);

				$arr_field_user_info_detail = array();
				$arr_field_user_info_detail['user_name'] = $user_name;
				$arr_field_user_info_detail['user_mobile'] = $user_mobile?$user_mobile:'';
				$arr_field_user_info_detail['user_reg_time'] = $now_time;
				$arr_field_user_info_detail['user_count_login'] = 1;
				$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
				$arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
				$arr_field_user_info_detail['user_reg_ip'] = $ip;
                /*if($user_type){
                    $arr_field_user_info_detail['user_no_mobile'] = $user_type == 'self_shop_user' ? 0 : 1;
                }*/

				$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
				array_push($rs_row, $flag);
			}


			$arr_field_user_app = array();
			$arr_field_user_app['user_name'] = $user_name;
			$arr_field_user_app['app_id'] = $app_id;
			$arr_field_user_app['active_time'] = time();

			$User_App = new User_AppModel();

			//是否存在
			$user_app_row = $User_App->getAppByNameAndAppId($user_name, $app_id);

			if ($user_app_row)
			{
				// update app_quantity
				$app_quantity_row = array();
				$app_quantity_row['app_quantity'] = $user_app_row['app_quantity'] + 1;
				$flag = $User_App->editApp($user_name, $app_quantity_row);
				array_push($rs_row, $flag);
			}
			else
			{
				$flag = $User_App->addApp($arr_field_user_app);
				array_push($rs_row, $flag);
			}

			$User_AppServerModel = new User_AppServerModel();

			$user_app_server_row = array();
			$user_app_server_row['user_name'] = $user_name;
			$user_app_server_row['app_id'] = $app_id;
			$user_app_server_row['server_id'] = $server_id;
			$user_app_server_row['active_time'] = time();

			$flag = $User_AppServerModel->addAppServer($user_app_server_row);


			if (is_ok($rs_row) && $Base_App->sql->commit())
			{
				$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id, "user_id"=>$user_id);
				$this->data->addBody(100, $arr_body);
			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('创建用户信息失败');
			}
		}
	}


	public function addUserAppServer()
	{
		$app_id = isset($_REQUEST['request_app_id']) ? intval($_REQUEST['request_app_id']) : null;

		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
		if (!$user_name)
		{
			$this->data->setError('user_name 不存在');
			return false;
		}


		$plantform_url = isset($_REQUEST['plantform_url']) ? $_REQUEST['plantform_url'] : null;
		$bbc_url = isset($_REQUEST['bbc_url']) ? $_REQUEST['bbc_url'] : null;
		$ucenter_url = isset($_REQUEST['ucenter_url']) ? $_REQUEST['ucenter_url'] : null;
		$paycenter_url = isset($_REQUEST['paycenter_url']) ? $_REQUEST['paycenter_url'] : null;

		if (!$plantform_url && !$bbc_url)
		{
			$this->data->setError(_('mallbuilder 或者 bbc  接口网址不存在'));
			return false;
		}


		$password = '111111';
		$user_mobile = $_REQUEST['company_phone'];

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
		}
		else
		{
			$base_app_row = array_pop($base_app_rows);
			$rs_row = array();
			$Base_App->sql->startTransaction();


			$Db  = Yf_Db::get('ucenter');
			$seq_name = 'server_id';
			$server_id = $Db->nextId($seq_name);
			$db_name = 'db_' . $server_id;


			$server_url_row = explode('|', $base_app_row['app_url']);

			$rand_id = rand(0, count($server_url_row)-1);

			$server_url = $server_url_row[$rand_id];

			$data['server_id']              = $server_id                      ; //
			//添加app server
			/*
			$data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
			$data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称
			$data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序
			$data['company_id']             = $_REQUEST['company_id']         ; // 运营商id
			$data['socket_ip']              = ''                              ; // socket 的ip地址
			$data['socket_port']            = 9000                            ; // socket的端口号
			$data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间
			$data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间
			$data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示
			$data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
			*/
			$data['app_id']                 = $app_id                         ; // 所属游戏id
			$data['server_type']            = 1                               ; // 服类型,1:new,2:hot,3:满 4:维护
			$data['server_state']           = 0                               ; // 服务器状态,0:备运   1:开服中 2、停服,3:服务器宕机

			$data['server_url']             = $base_app_row['app_url']        ; // 服务器url
			$data['plantform_url']          = $plantform_url    			    ; // mall 平台
//			$data['bbc_url ']               = $bbc_url    			            ; // bbc_url  平台
			$data['ucenter_url']            = $ucenter_url    			    	; // 用户中心
			$data['paycenter_url']          = $paycenter_url   			    	; // 支付中心

			$data['company_name']           = $_REQUEST['company_name']       ; // 公司名称
			$data['company_phone']          = $user_mobile                    ; // 电话
			$data['contacter']              = $_REQUEST['contacter']          ; // 联系人
			$data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间
			$data['account_num']            = $_REQUEST['account_num']        ; // 账号个数
			$data['user_name']              = $user_name                      ; // 管理员账号
			$data['db_host']                = ''            				  ; // 数据库IP
			$data['db_user']                = ''                        	  ; // 数据库名
			$data['db_name']                = ''                        	  ; // 数据库名
			$data['db_passwd']              = ''                              ; // 数据库密码
			$data['upload_path']            = ''                              ; // 附件存放地址
			$data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表
			$data['price']                  = $_REQUEST['price']              ; // 费用
			$data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
			$data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1
			$data['cloud_type']             = request_int('cloud_type') ;

			$Base_AppServerModel = new Base_AppServerModel();
			$flag = $Base_AppServerModel->addAppServer($data, true);
			array_push($rs_row, $flag);

			//用户是否存在
			$name_hash =  Yf_Hash::hashNum($user_name, 2);

			$User_InfoModel = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetail();


			$user_rows = $User_InfoModel->getInfoByName($user_name);

			if ($user_rows)
			{

			}
			else
			{
				$Db  = Yf_Db::get('ucenter');
				$seq_name = 'user_id';
				$user_id = $Db->nextId($seq_name);

				//$User_InfoModel->check_input($user_name, $password, $user_mobile);

				$now_time = time();
				$ip = get_ip();

				$session_id = uniqid();
				$arr_field_user_info = array();
				$arr_field_user_info['user_id'] = $user_id;
				$arr_field_user_info['user_name'] = $user_name;
				$arr_field_user_info['password'] = md5($password);
				$arr_field_user_info['action_time'] = $now_time;
				$arr_field_user_info['action_ip'] = $ip;
				$arr_field_user_info['session_id'] = $session_id;

				$flag = $User_InfoModel->addInfo($arr_field_user_info);
				array_push($rs_row, $flag);

				$arr_field_user_info_detail = array();
				$arr_field_user_info_detail['user_name'] = $user_name;
				$arr_field_user_info_detail['user_mobile'] = $user_mobile;
				$arr_field_user_info_detail['user_reg_time'] = $now_time;
				$arr_field_user_info_detail['user_count_login'] = 1;
				$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
				$arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
				$arr_field_user_info_detail['user_reg_ip'] = $ip;

				$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
				array_push($rs_row, $flag);
			}


			$arr_field_user_app = array();
			$arr_field_user_app['user_name'] = $user_name;
			$arr_field_user_app['app_id'] = $app_id;
			$arr_field_user_app['active_time'] = time();

			$User_App = new User_AppModel();

			//是否存在
			$user_app_row = $User_App->getAppByNameAndAppId($user_name, $app_id);

			if ($user_app_row)
			{
				// update app_quantity
				$app_quantity_row = array();
				$app_quantity_row['app_quantity'] = $user_app_row['app_quantity'] + 1;
				$flag = $User_App->editApp($user_name, $app_quantity_row);
				array_push($rs_row, $flag);
			}
			else
			{

				$flag = $User_App->addApp($arr_field_user_app);
				array_push($rs_row, $flag);

			}

			$User_AppServerModel = new User_AppServerModel();

			$user_app_server_row = array();
			$user_app_server_row['user_name'] = $user_name;
			$user_app_server_row['app_id'] = $app_id;
			$user_app_server_row['server_id'] = $server_id;
			$user_app_server_row['active_time'] = time();

			$flag = $User_AppServerModel->addAppServer($user_app_server_row);


			if (is_ok($rs_row) && $Base_App->sql->commit())
			{
				$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id);
				$this->data->addBody(100, $arr_body);
			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('创建用户信息失败');
			}
		}
	}

	public function editUserAppServer()
	{
		$app_id = $_REQUEST['request_app_id'];
		$server_id = request_int('server_id');

		if (!$server_id)
		{
			$this->data->setError('server_id 不存在');
			return false;
		}

		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
		if (!$user_name)
		{
			$this->data->setError('user_name 不存在');
			return false;
		}


		$plantform_url = isset($_REQUEST['plantform_url']) ? $_REQUEST['plantform_url'] : null;
        $bbc_url = isset($_REQUEST['bbc_url']) ? $_REQUEST['bbc_url'] : null;
		$ucenter_url = isset($_REQUEST['ucenter_url']) ? $_REQUEST['ucenter_url'] : null;
		$paycenter_url = isset($_REQUEST['paycenter_url']) ? $_REQUEST['paycenter_url'] : null;

		if (!$plantform_url && !$bbc_url)
		{
			$this->data->setError(_('mallbuilder 或者 bbc  接口网址不存在'));
			return false;
		}

		$user_mobile = $_REQUEST['company_phone'];

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);

		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
		}
		else
		{
			$base_app_row = array_pop($base_app_rows);
			$rs_row = array();
			$Base_App->sql->startTransaction();

			$data = array();
			//添加app server
			/*
			$data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
			$data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称
			$data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序
			$data['company_id']             = $_REQUEST['company_id']         ; // 运营商id
			$data['socket_ip']              = ''                              ; // socket 的ip地址
			$data['socket_port']            = 9000                            ; // socket的端口号
			$data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间
			$data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间
			$data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示
			$data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
			*/

			//$data['app_id']                 = $app_id                         ; // 所属游戏id
			//$data['server_type']            = 1                               ; // 服类型,1:new,2:hot,3:满 4:维护
			//$data['server_state']           = 0                               ; // 服务器状态,0:备运   1:开服中 2、停服,3:服务器宕机

			//$data['server_url']             = $base_app_row['app_url']        ; // 服务器url
			$data['plantform_url']          = $plantform_url    			    ; // mall 平台
			$data['bbc_url']                = $bbc_url    			    ; // mall 平台
			$data['ucenter_url']            = $ucenter_url    			    	; // 用户中心
			$data['paycenter_url']          = $paycenter_url   			    	; // 支付中心

			$data['company_name']           = $_REQUEST['company_name']       ; // 公司名称
			$data['company_phone']          = $user_mobile                    ; // 电话
			$data['contacter']              = $_REQUEST['contacter']          ; // 联系人
			$data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间
			$data['account_num']            = $_REQUEST['account_num']        ; // 账号个数
			$data['user_name']              = $user_name                      ; // 管理员账号

			/*
			$data['db_host']                = ''            				  ; // 数据库IP
			$data['db_user']                = ''                        	  ; // 数据库名
			$data['db_name']                = ''                        	  ; // 数据库名
			$data['db_passwd']              = ''                              ; // 数据库密码
			$data['upload_path']            = ''                              ; // 附件存放地址
			*/
			$data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表
			$data['price']                  = $_REQUEST['price']              ; // 费用
			$data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
			$data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1
			$data['cloud_type']             = request_int('cloud_type') ;

			$Base_AppServerModel = new Base_AppServerModel();
			$flag = $Base_AppServerModel->editAppServer($server_id, $data);
			array_push($rs_row, $flag);


			if (is_ok($rs_row) && $Base_App->sql->commit())
			{
				$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id);
				$this->data->addBody(100, $arr_body);
			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('修改失败');
			}
		}
	}


	public function virifyUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$request_app_id = $_REQUEST['request_app_id'];
		$server_id = $_REQUEST['server_id'];
		$user_name = $_REQUEST['user_name'];
		$server_state = $_REQUEST['server_state'];


		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($request_app_id);
		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
			return;
		}

		$base_app_row = array_pop($base_app_rows);


		$Base_AppServerModel = new Base_AppServerModel();
		$base_app_server_rows = $Base_AppServerModel->getAppServer($server_id);

		if(!$base_app_server_rows)
		{
			$this->data->setError('App server id 不存在');
			return;
		}
		else
		{
			//发送开通erp server 请求
			$init_flag = false;

			$base_app_server_row = array_pop($base_app_server_rows);

			if (1==$server_state && 0==$base_app_server_row['server_state'])
			{
				//cloud_type 0 1
				//判断
				if ($base_app_server_row['cloud_type'])
				{
					//开通ucenter

					//本地读取远程信息
					$key = Yf_Registry::get('ucenter_api_key');;
					$url         = Yf_Registry::get('ucenter_api_url');
					$app_id = Yf_Registry::get('ucenter_app_id');

					$formvars = $base_app_server_row;
					$formvars['app_id']        = $app_id;
					$formvars['admin_account'] = @Perm::$row['user_account'];

					$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Server', 'create'), $formvars);


					
 					Yf_Log::log(sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Server', 'create'), Yf_Log::INFO, 'yun_init_ucenter_init_rs_000_url');


 					Yf_Log::log($init_rs, Yf_Log::INFO, 'yun_init_ucenter_init_rs_000_stap');

					if (
						200 == $init_rs['status'] && $init_rs['data']

						)
					{

						Yf_Log::log(1, Yf_Log::INFO, 'open_paycenter');
						//开通PayCenter
						$key    = Yf_Registry::get('paycenter_api_key');;
						$url    = Yf_Registry::get('paycenter_api_url');
						$app_id = Yf_Registry::get('paycenter_app_id');

						$formvars                  = $base_app_server_row;
						$formvars['app_id']        = $app_id;
						$formvars['admin_account'] = @Perm::$row['user_account'];

						$formvars['db_host']   = $init_rs['data']['host'];
						$formvars['db_user']   = $init_rs['data']['user'];
						$formvars['db_name']   = $init_rs['data']['database'];
						$formvars['db_passwd'] = $init_rs['data']['password'];

						$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Server', 'create'), $formvars);

						Yf_Log::log($formvars, Yf_Log::INFO, 'yun_paycenter_0_data');

						Yf_Log::log(sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Server', 'create'), Yf_Log::INFO, 'yun_paycenter_1_url');

						Yf_Log::log($init_rs, Yf_Log::INFO, 'yun_paycenter_1_init_rs');
					}
					else
					{
						Yf_Log::log('初始化用户中心数据失败!', Yf_Log::INFO, 'yun_init_ucenter_init_rs_failed_EEE');
						$this->data->setError('初始化用户中心数据失败!' . json_encode($init_rs));
						return;
					}
				}
				else
				{

				}


				Yf_Log::log(1, Yf_Log::INFO, 'NEXT11');
				//开通server
				$url = $base_app_server_row['server_url'];
				$formvars = $base_app_server_row;

				if (false && $base_app_server_row['cloud_type'])
				{
					$formvars['db_host']   = $init_rs['data']['host'];
					$formvars['db_user']   = $init_rs['data']['user'];
					$formvars['db_name']   = $init_rs['data']['database'];
					$formvars['db_passwd'] = $init_rs['data']['password'];
				}
				else
				{
					unset($formvars['db_passwd']);
					unset($formvars['db_user']);
					unset($formvars['db_name']);
					unset($formvars['db_host']);
				}

				//权限加密数据处理
				//$key = $base_app_row['app_key'];

				if (103 == $request_app_id)
				{
					$key    = Yf_Registry::get('shop_api_key');

					$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Server', 'create'), $formvars);
				}
				else
				{
					$url = $base_app_server_row['server_url'];
					$formvars = $base_app_server_row;

					$url = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Api', 'create', 'json');

					//权限加密数据处理
					$key = $base_app_row['app_key'];
					$init_rs = get_url_with_encrypt($key, $url, $formvars);
				}


				Yf_Log::log($init_rs, Yf_Log::INFO, 'SHOP_1');
				Yf_Log::log($init_rs, Yf_Log::INFO, 'SHOP_2');
				
				if (200 == $init_rs['status'] && $init_rs['data'])
				{
					$init_flag = true;

					//更新状态app server 信息及状态
				}
				else
				{
					$search_string = '';
					foreach ($formvars as $k => $v) {
						$search_string .= "$k=$v&";
					}

					$this->data->setError('初始化服务器数据失败!' . json_encode($init_rs) . "$url?$search_string");
					return;

					$init_flag = false;
				}
			}
			else
			{
				$this->data->setError('请求失败,信息不对');
				return;
			}


			if ($init_flag)
			{
				$rs_row = array();
				$Base_AppServerModel->sql->startTransaction();

				// update app_quantity
				$app_quantity_row = array();

				$app_quantity_row['server_state'] = $server_state;
				$app_quantity_row['db_host'] = $init_rs['data']['host'];
				$app_quantity_row['db_user'] = $init_rs['data']['user'];
				$app_quantity_row['db_name'] = $init_rs['data']['database'];
				$app_quantity_row['db_passwd'] = $init_rs['data']['password'];

				$flag = $Base_AppServerModel->editAppServer($server_id, $app_quantity_row);
				array_push($rs_row, $flag);

				if (is_ok($rs_row) && $Base_AppServerModel->sql->commit())
				{
					$arr_body = array("server_id"=>$server_id, "server_state"=>$server_state);
					$this->data->addBody(100, $arr_body);
				}
				else
				{
					$Base_AppServerModel->sql->rollBack();
					$this->data->setError('更改状态失败');
				}
			}
			else
			{
				$this->data->setError('操作失败');
			}
		}
	}


	public function getUserAppServerInfo()
	{

		$app_id = $_REQUEST['app_id'];
		$server_id = $_REQUEST['server_id'];

		$Base_App = new Base_AppModel();
		$base_app_rows = $Base_App->getApp($app_id);
		if(!$base_app_rows)
		{
			$this->data->setError('App id 不存在');
			return;
		}

		$base_app_row = array_pop($base_app_rows);


		$Base_AppServerModel = new Base_AppServerModel();
		$base_app_server_rows = $Base_AppServerModel->getAppServer($server_id);
		 if(!$base_app_server_rows)
		 {
		 	$this->data->setError('App server id 不存在');
		 	return;
		 }
		 else
		 {
            $base_app_server_row = array();
			$base_app_server_row = array_pop($base_app_server_rows);


			$this->data->addBody(100, $base_app_server_row);
		 }
	}


	/**
	 * 手机获取注册码
	 * 不再使用
	 * @access public
	 */
//	public function regCode()
//	{
//		$mobile                    = request_string('mobile');
//
//		$data = array();
//
//		$data['user_code'] = rand(100000, 999999);
//
//		$config_cache = Yf_Registry::get('config_cache');
//
//		if (!file_exists($config_cache['default']['cacheDir']))
//		{
//			mkdir($config_cache['default']['cacheDir']);
//		}
//		$Cache_Lite = new Cache_Lite_Output($config_cache['default']);
//
//		$Cache_Lite->save($data['user_code'], $mobile);
//
//		//发送短消息
//		$contents = '您的验证码是：' . $data['user_code'] . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
//
//		$result = Sms::send($mobile, $contents);
//		{
//			if (true)
//			{
//				$msg = 'success';
//				$status = 200;
//			}
//			else
//			{
//				$msg = '失败';
//				$status = 250;
//			}
//
//		}
//
//
//		$this->data->addBody(-140, $data, $msg, $status);
//	}


	/**
	 * 手机获取找回密码验证码
	 * 不再使用
	 * @access public
	 */
//	public function findPasswdCode()
//	{
//		$mobile                    = request_string('mobile');
//
//		//判断用户是否存在  $mobile
//		if (true)
//		{
//			$data = array();
//
//			$data['user_code'] = rand(100000, 999999);
//
//			$config_cache = Yf_Registry::get('config_cache');
//
//			if (!file_exists($config_cache['default']['cacheDir']))
//			{
//				mkdir($config_cache['default']['cacheDir']);
//			}
//
//			$Cache_Lite = new Cache_Lite_Output($config_cache['default']);
//
//			$Cache_Lite->save($data['user_code'], $mobile);
//
//			//发送短消息
//			$contents = '您的验证码是：' . $data['user_code'] . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
//
//			$result = Sms::send($mobile, $contents);
//
//			{
//				if (true)
//				{
//					$msg = 'success';
//					$status = 200;
//				}
//				else
//				{
//					$msg = '失败';
//					$status = 250;
//				}
//
//			}
//		}
//		else
//		{
//			$msg = '用户账号不存在';
//			$status = 250;
//		}
//
//		$this->data->addBody(-140, $data, $msg, $status);
//	}


	public function resetPasswd()
	{
		//
		$user_code = request_string('user_code');

		$data         = array();
		$data['user'] = request_string('user_account');

		if (request_string('user_password'))
		{
			$data['password'] = md5(request_string('user_password'));


			$config_cache = Yf_Registry::get('config_cache');
			$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

			$user_code_pre = $Cache_Lite->get($data['user']);


			if ($user_code == $user_code_pre)
			{
				$User_InfoModel = new User_InfoModel();

				//检测登录状态
				$user_id_row = $User_InfoModel->getInfoByName($data['user']);

				if ($user_id_row)
				{
					//重置密码
					$user_id          = $user_id_row['user_id'];
					$reset_passwd_row = array();

					$reset_passwd_row['password'] = $data['password'];

					fb($user_id);
					fb($reset_passwd_row);
					$flag = $User_InfoModel->editInfo($user_id, $reset_passwd_row);

					if (false !== $flag)
					{
						$msg    = '重置密码成功';
						$status = 200;

						$Cache_Lite->remove($data['user']);
					}
					else
					{
						$msg    = '重置密码失败';
						$status = 250;
					}
				}
				else
				{
					$msg    = '用户不存在';
					$status = 250;
				}
			}
			else
			{
				$msg = '验证码错误';
				$status = 250;
			}

		}
		else
		{
			$msg    = '密码不能为空';
			$status = 250;
		}


		unset($data['password']);

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function register()
	{
		$app_id = request_int('app_id');

		$user_name = request_string('user_account', null);
		$password  = request_string('user_password', null);


		$user_code = request_string('user_code');

		if (!$user_name)
		{
			$this->data->setError('user_account 不存在');
			return false;
		}

		if (null === $password)
		{
			$this->data->setError('user_password 不能为空');
			return false;
		}

		$config_cache = Yf_Registry::get('config_cache');
		$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

		$user_code_pre = $Cache_Lite->get($user_name);

		if ($user_code == $user_code_pre)
		{
			$rs_row = array();

			//用户是否存在
			$User_InfoModel = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetail();

			$user_rows = $User_InfoModel->getInfoByName($user_name);

			if ($user_rows)
			{
				$this->data->setError('用户已经存在,请更换用户名!');
				return false;
			}
			else
			{

				$User_InfoModel->sql->startTransaction();

				$Db  = Yf_Db::get('ucenter');
				$seq_name = 'user_id';
				$user_id = $Db->nextId($seq_name);

				//$User_InfoModel->check_input($user_name, $password, $user_mobile);

				$now_time = time();
				$ip = get_ip();

				$session_id = uniqid();
				$arr_field_user_info = array();
				$arr_field_user_info['user_id'] = $user_id;
				$arr_field_user_info['user_name'] = $user_name;
				$arr_field_user_info['password'] = md5($password);
				$arr_field_user_info['action_time'] = $now_time;
				$arr_field_user_info['action_ip'] = $ip;
				$arr_field_user_info['session_id'] = $session_id;

				$flag = $User_InfoModel->addInfo($arr_field_user_info);
				array_push($rs_row, $flag);

				$arr_field_user_info_detail = array();
				$arr_field_user_info_detail['user_name'] = $user_name;
				$arr_field_user_info_detail['user_mobile'] = $user_name;
				$arr_field_user_info_detail['user_reg_time'] = $now_time;
				$arr_field_user_info_detail['user_count_login'] = 1;
				$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
				$arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
				$arr_field_user_info_detail['user_reg_ip'] = $ip;

				$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
				array_push($rs_row, $flag);
			}




			$app_id = isset($_REQUEST['app_id']) ?  $_REQUEST['app_id'] : 0;
			$Base_App = new Base_AppModel();

			if($app_id && !($base_app_rows = $Base_App->getApp($app_id)))
			{
				/*
				$base_app_row = array_pop($base_app_rows);

				$arr_field_user_app = array();
				$arr_field_user_app['user_name'] = $user_name;
				$arr_field_user_app['app_id'] = $app_id;
				$arr_field_user_app['active_time'] = time();

				$User_App = new User_AppModel();

				//是否存在
				$user_app_row = $User_App->getAppByNameAndAppId($user_name, $app_id);

				if ($user_app_row)
				{
					// update app_quantity
					$app_quantity_row = array();
					$app_quantity_row['app_quantity'] = $user_app_row['app_quantity'] + 1;
					$flag = $User_App->editApp($user_name, $app_quantity_row);
					array_push($rs_row, $flag);
				}
				else
				{

					$flag = $User_App->addApp($arr_field_user_app);
					array_push($rs_row, $flag);

				}

				$User_AppServerModel = new User_AppServerModel();

				$user_app_server_row = array();
				$user_app_server_row['user_name'] = $user_name;
				$user_app_server_row['app_id'] = $app_id;
				$user_app_server_row['server_id'] = $server_id;
				$user_app_server_row['active_time'] = time();

				$flag = $User_AppServerModel->addAppServer($user_app_server_row);
				*/
			}
			else
			{
			}

			if (is_ok($rs_row) && $User_InfoDetail->sql->commit())
			{
				$d = array();
				$d['user_id'] = $user_id;
				$encrypt_str = Perm::encryptUserInfo($d, $session_id);

				$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id, 'k'=>$encrypt_str);
				$this->data->addBody(100, $arr_body);


			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('创建用户信息失败');
			}
		}
		else
		{
			$msg = '验证码错误';
			$status = 250;
			$this->data->addBody(-1, array(), $msg, $status);
		}
	}

	public function login()
	{

		$user_name = strtolower($_REQUEST['user_account']);

		if (!$user_name)
		{
			$user_name = strtolower($_REQUEST['user_name']);
		}


		$password = $_REQUEST['user_password'];

		if (!$password)
		{
			$password = $_REQUEST['password'];
		}

		if(!strlen($user_name))
		{
			$this->data->setError('请输入账号');
		}

		if(!strlen($password))
		{
			$this->data->setError('请输入密码');
		}

		$User_BindConnectModel = new User_BindConnectModel();
		$User_InfoModel = new User_InfoModel();
		$User_InfoDetailModel = new User_InfoDetailModel();

		//查找绑定表中是否存在此用户
		$bind_id = '';
		$user_info_row = array();

		//绑定标记：mobile/email/openid  绑定类型+openid
		{
			if(filter_var($user_name,FILTER_VALIDATE_EMAIL))
			{
				//邮件登录
				$bind_id = sprintf('email_%s',$user_name);
			}
			elseif(Yf_Utils_String::isMobile($user_name))
			{
				//手机号码登录
				$bind_id = sprintf('mobile_%s',$user_name);
			}

			if($bind_id)
			{
				//查找bind绑定表
				$User_BindConnectModel = new User_BindConnectModel();
				$bind_info = $User_BindConnectModel->getOne($bind_id);

				if ($bind_info)
				{
					//用户名登录
					$user_info_row   = $User_InfoModel->getOne($bind_info['user_id']);
					$user_info_detail = $User_InfoDetailModel->getOne($user_info_row['user_name']);

					$user_info_row = $user_info_row + $user_info_detail;
				}
			}


			if($user_info_row)
			{
			}
			else
			{
				//用户名登录
				$user_info_row = $User_InfoModel->getInfoByName($user_name);
				$user_info_detail = $User_InfoDetailModel->getOne($user_name);

				$user_info_row = $user_info_row + $user_info_detail;
			}
		}


		if(!$user_info_row)
		{
			$this->data->setError('账号不存在');
		}
		else
		{

			if(md5($password) != $user_info_row['password'] && $password != $user_info_row['password'])
			{
				$this->data->setError('密码错误');
			}
			else
			{
				if (3 == $user_info_row['user_state'])
				{
					$this->data->setError('用户已经锁定,禁止登录!');
					return false;
				}

				//$session_id = uniqid();
				$session_id = $user_info_row['session_id'];

				$arr_field               = array();
				$arr_field['session_id'] = $session_id;

				//if ($User_InfoModel->editInfo($user_info_row['user_id'], $arr_field) > 0)
				if (true)
				{
					//$arr_body = array("result"=>1,"user_name"=>$user_info_row['user_name'],"session_id"=>$session_id);
					$arr_body = $user_info_row;
					$arr_body['result'] = 1;
					//$arr_body['session_id'] = $session_id;

					$data = array();
					$data['user_id']    = $user_info_row['user_id'];
					//$data['session_id'] = $session_id;
					$encrypt_str        = Perm::encryptUserInfo($data, $session_id);

					$arr_body['k'] = $encrypt_str;

					$this->data->addBody(100, $arr_body);
				}
				else
				{
					$this->data->setError('登录失败');
				}
			}

		}

		if(isset($_REQUEST['callback']) && $_REQUEST['callback'])
		{
			header("Location:".urldecode($_REQUEST['callback']));
		}
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
			if(isset($_COOKIE['key']) || isset($_COOKIE['id']))
			{
				echo "<script>parent.location.href='index.php';</script>";
				setcookie("key", null, time()-3600*24*365);
				setcookie("id", null, time()-3600*24*365);

			}
		}
	}

	public function checkLogin()
	{
		if (Perm::checkUserPerm())
		{
			$msg = '数据正确';
			$status = 200;
			$data = Perm::$row;
		}
		else
		{
			$msg = '权限错误';
			$status = 250;
			$data = array();
		}

		$this->data->addBody(100, $data, $msg, $status);
	}

	//平台开了那些区
	public function getAppServerOld()
	{
		$app_id = $_REQUEST['app_id'];
		$company_id = $_REQUEST['company_id'];

		if (!isset($_REQUEST['package_version']))
		{
			$_REQUEST['package_version'] = '1.0.0';
		}
		$package_version = $_REQUEST['package_version'];


		if(!is_int_numeric($app_id) || !is_int_numeric($company_id))
		{
			$this->data->setError('参数错误');
		}

		$Base_AppServer = new Base_AppServer();

		$arr_list = $Base_AppServer->getAppServerIdByCompanyId($app_id, $company_id);//var_dump($arr_list);die("kk");
		$arr_list = array_sort($arr_list, "server_order", "desc");

		$arr_app_server_list = array();//Zero_Log::log(json_encode($arr_list)." condition=".json_encode($arr_condition), Zero_Log::ERROR, 'test');
		$now_time = time();
		$arr_filter_server_id = array(1011002);

		foreach($arr_list as $key => $value)
		{
			if(in_array($value['server_id'], $arr_filter_server_id))
			{
				continue;
			}

			if($now_time >= $value['server_stop_start_time'] && $now_time <= $value['server_stop_end_time'])
			{
				$value['server_type'] = 4;
			}


			if (version_compare($package_version, $value['app_version_package']) >= 0)
			{
				$arr_temp['server_id'] = intval($value['server_id']);
				$arr_temp['server_prefix'] = $value['server_prefix'];
				$arr_temp['server_name'] = $value['server_name'];
				$arr_temp['server_url'] = $value['server_url'];
				$arr_temp['server_type'] = intval($value['server_type']);
				$arr_temp['server_state'] = 1;//这个字段暂时没用
				$arr_temp['socket_ip'] = $value['socket_ip'];
				$arr_temp['socket_port'] = intval($value['socket_port']);
				$arr_temp['server_stop_tip'] = $value['server_stop_tip'];
				$arr_app_server_list[] = $arr_temp;
			}
		}

		$this->data->setBody($arr_app_server_list);
	}


	//平台开了那些区
	public function getAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$company_id = $_REQUEST['company_id'];

		if (!isset($_REQUEST['package_version']))
		{
			$_REQUEST['package_version'] = '1.0.0';
		}
		$package_version = $_REQUEST['package_version'];


		if(!is_int_numeric($app_id) || !is_int_numeric($company_id))
		{
			$this->data->setError('参数错误');
		}

		$Base_AppServer = new Base_AppServer();
		$Base_AppServerCompany = new Base_AppServerCompany();

		$server_rows = $Base_AppServerCompany->getAppServerServerIdByCompanyId($app_id, $company_id);//var_dump($arr_list);die("kk");

		$server_id_row = array();

		if ($server_rows)
		{
			foreach ($server_rows as $key=>$item)
			{
				if (!in_array($item['server_id'], $server_id_row))
				{
					array_push($server_id_row, $item['server_id']);
				}
			}
		}

		$arr_list = $Base_AppServer->getAppServer($server_id_row);


		//$arr_list = $Base_AppServer->getAppServerIdByCompanyId($app_id, $company_id);//var_dump($arr_list);die("kk");
		$arr_list = array_sort($arr_list, "server_order", "desc");

		$arr_app_server_list = array();
		$now_time = time();
		$arr_filter_server_id = array(1011002);

		foreach($arr_list as $key => $value)
		{
			if(in_array($value['server_id'], $arr_filter_server_id))
			{
				continue;
			}

			if($now_time >= $value['server_stop_start_time'] && $now_time <= $value['server_stop_end_time'])
			{
				$value['server_type'] = 4;
			}


			if (version_compare($package_version, $value['app_version_package']) >= 0)
			{
				$arr_temp['server_id'] = intval($value['server_id']);
				$arr_temp['server_prefix'] = $value['server_prefix'];
				$arr_temp['server_name'] = $value['server_name'];
				$arr_temp['server_url'] = $value['server_url'];
				$arr_temp['server_type'] = intval($value['server_type']);
				$arr_temp['server_state'] = 1;//这个字段暂时没用
				$arr_temp['socket_ip'] = $value['socket_ip'];
				$arr_temp['socket_port'] = intval($value['socket_port']);
				$arr_temp['server_stop_tip'] = $value['server_stop_tip'];
				$arr_app_server_list[] = $arr_temp;
			}
		}

		$this->data->setBody($arr_app_server_list);
	}

	public function setAppServerStopTime()
	{
		$app_id = $_REQUEST['app_id'];
		$server_id = $_REQUEST['server_id'];
		$server_stop_start_time = $_REQUEST['server_stop_start_time'];
		$server_stop_end_time = $_REQUEST['server_stop_end_time'];
		$server_stop_tip = $_REQUEST['server_stop_tip'];

		$Base_AppServer = new Base_AppServer();

		$arr_app_server = $Base_AppServer->getAppServer($server_id);

		if(!$arr_app_server)
		{
			$this->data->setError('服务器不存在');
		}

		if(strtotime($server_stop_start_time) > strtotime($server_stop_end_time))
		{
			$this->data->setError('停服开始时间不能大于停服结束时间');
		}

		$server_url = $arr_app_server['server_url'];

		$arr_app_server_list = $Base_AppServer->getAppServerIdByServerUrl($server_url);
		foreach($arr_app_server_list as $key => $value)
		{
			$arr_field = array();
			$arr_field['server_stop_start_time'] = $server_stop_start_time;
			$arr_field['server_stop_end_time'] = $server_stop_end_time;
			$arr_field['server_stop_tip'] = $server_stop_tip;

			//$Base_AppServer->editAppServer($server_id, $arr_field);
			$Base_AppServer->sql->setPreTran(array($Base_AppServer, "editAppServer"), array($value['server_id'], $arr_field),0);
		}

		$Base_AppServer->sql->startTransaction();

		if ($Base_AppServer->sql->commit())
		{
			$arr_body = array("result"=>1);
			$this->data->setBody($arr_body);
		}
		else
		{
			$Base_AppServer->sql->rollBack();
			$this->data->setError(_('操作失败'));
		}
	}

	//用户激活了那些服务
	public function getUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$session_id = $_REQUEST['session_id'];
		$user_name = $_REQUEST['user_name'];
		$company_id = $_REQUEST['company_id'];

		if(!is_int_numeric($app_id))
		{
			$this->data->setError('参数错误');
			return false;
		}

		$User_AppServerModel = new User_AppServerModel();

		$arr_condition = array();
		$arr_condition['user_name'] = $user_name;
		$arr_condition['app_id'] = $app_id;
		//$arr_condition['company_id'] = $company_id;

		$arr_server_id = array();
		$user_app_server_rows = $User_AppServerModel->getUserAppServerByCondition($arr_condition);

//        foreach($arr_user_app_server_list as $key => $value)
//        {
//            if(!in_array($value['server_id'], $arr_server_id))
//            {
//                $arr_server_id[] = $value['server_id'];
//            }
//        }

		$this->data->addBody(100, $user_app_server_rows);
	}

	//玩家激活游戏区
	public function activeUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$user_name = $_REQUEST['user_name'];
		$company_id = $_REQUEST['company_id'];
		$server_id = $_REQUEST['server_id'];
		$active_time = $_REQUEST['active_time'];

		if(!is_int_numeric($app_id) || !is_int_numeric($server_id) || !is_int_numeric($company_id) || !is_int_numeric($active_time))
		{
			$this->data->setError('参数错误');
		}


		$name_hash =  Yf_Hash::hashNum($user_name, 2);
		$User_AppServer = new User_AppServer($name_hash[0], $name_hash[1]);

		$Base_AppServer = new Base_AppServer();
		$arr_app_server = $Base_AppServer->getAppServer($server_id);

		if(!$arr_app_server)
		{
			$this->data->setError('passport开服列表未配置');
		}

		//如下判断可以不需要
		$find_flag = false;

		$server_url = $arr_app_server['server_url'];

		$Base_AppServerCompany = new Base_AppServerCompany();
		$arr_app_server_list = $Base_AppServerCompany->getAppServerServerIdByCompanyId($app_id, $company_id);
		//$arr_app_server_list = $Base_AppServer->getAppServerIdByCompanyId($app_id, $company_id);//var_dump($arr_app_server_list);die("ttt");

		foreach($arr_app_server_list as $key => $value)
		{
			if($server_id == $value['server_id'])
			{
				//$server_id = $value['server_id'];
				$find_flag = true;
				break;
			}
		}

		if(!$find_flag)
		{
			$this->data->setError('找不到服务器id');
		}

		$arr_condition2 = array();
		$arr_condition2['app_id'] = $app_id;
		$arr_condition2['user_name'] = $user_name;
		$arr_condition2['company_id'] = $company_id;
		$arr_condition2['server_id'] = $server_id;
		$arr_user_app_server_list = $User_AppServer->getAppServerByCondition($arr_condition2);

		if($arr_user_app_server_list)
		{
			$arr_body = array("result"=>1);//容错处理
			$this->data->setBody($arr_body);
		}
		else
		{
			$arr_field = array();
			$arr_field['user_name'] = $user_name;
			$arr_field['app_id'] = $app_id;
			$arr_field['company_id'] = $company_id;
			$arr_field['server_id'] = $server_id;
			$arr_field['active_time'] = is_int_numeric($active_time) ? $active_time : time();

			if($User_AppServer->addAppServer($arr_field))
			{
				$arr_body = array("result"=>1);
				$this->data->setBody($arr_body);
			}
			else
			{
				$this->data->setError('数据写入失败');
			}
		}
	}

	/*
	//支付验证
	public function  checkRecharge()
	{
		$order_id = $_REQUEST['order_id'];

		if(strlen($order_id))
		{
			$this->data->setError('请传入订单id');
		}

		$Pay_Verify = new Pay_Verify();
		$arr_pay_verify = $Pay_Verify->getVerify($order_id);

		if(!$arr_pay_verify)
		{
			$this->data->setError('订单id无效');
		}

		if($arr_pay_verify['verify_flag'])
		{
			$this->data->setError('订单已经验证');
		}

		//$Pay_Verify->editVerifyBySingleField($order_id, "verify_flag", 1, 0);
		$Pay_Verify->sql->setPreTran(array($Pay_Verify, "editVerifyBySingleField"), array($order_id, "verify_flag", 1, 0));


		$Pay_Verify->sql->startTransaction();

		if ($Pay_Verify->sql->commit())
		{
			$arr_body = array('status'=>200,"msg"=>"充值验证成功");
			$this->data->setBody($arr_body);
		}
		else
		{
			$this->data->setError('充值验证失败');
		}
	}
	*/

	public function version()
	{
		$app_id = $_REQUEST['app_id'];
		$client_version = $_REQUEST['client_version'];

		if (!isset($_REQUEST['package_version']))
		{
			$_REQUEST['package_version'] = '1.0.0';
		}

		//cpp version
		$package_version = $_REQUEST['package_version'];

		//修正读到旧的版本信息数据
		if (-1 == version_compare($client_version, $package_version))
		{
			$client_version = $package_version;
		}

		if(!is_int_numeric($app_id))
		{
			$this->data->setError(_('参数错误'));
		}

		$Base_AppVersion = new Base_AppVersion();
		$base_app_rows_version = $Base_AppVersion->getAppVersion($app_id);

		if(!$base_app_rows_version)
		{
			$this->data->setError(_('游戏版本配置表错误'));
		}

		$app_version = $base_app_rows_version['app_version'];
		$version_row = array();

		if($app_version != $client_version)//客户端的版本不是最新版本
		{
			$Base_AppResources = new Base_AppResources();
			$base_app_rows_resources = $Base_AppResources->getAppResources($app_id, $client_version);

			if(!$base_app_rows_resources)//客户端版本号错误
			{
				$base_app_rows_resources = $Base_AppResources->getAppResources($app_id, $base_app_rows_version['app_version']);

				$version_row['client_version'] = $client_version;
				$version_row['current_version'] = $base_app_rows_version['app_version'];
				$version_row['latest_version'] = $base_app_rows_version['app_version'];
				$version_row['zip_url'] = $base_app_rows_resources['app_res_url'].$base_app_rows_resources['app_res_childpath'].$base_app_rows_resources['app_res_filename'];
				$version_row['state'] = $base_app_rows_resources['app_reinstall'] != 2 ? 1 : 2;
				$version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];
			}
			else
			{
				$app_version_next = $base_app_rows_resources['app_version_next'];
				$base_app_rows_resources_next = $Base_AppResources->getAppResources($app_id, $app_version_next);
				$app_version_package_next = $base_app_rows_resources_next['app_version_package'];

				//如果下一个更新包，不是当前版本可更新的，则判断reinstall, 如果为2，则强制更新，否则不更新 ,根据app_version_package来判断。
				//新包发布完成后，将此字段改为2，目前仅限于官方版本，平台升级叫给平台处理
				//version_compare($package_version, $app_version_package_next) >= 0
				if ($package_version != $app_version_package_next)
				{
					//可以安装新包，不能直接next，可能当前包很旧，需要找到最新包！！！
					//目前从当前安装的包直接读取，每次有新包，则更新所有的字段app_package_url
					if (2==$base_app_rows_resources['app_reinstall'] && $base_app_rows_resources['app_package_url'])
					{
						$version_row['client_version'] = $client_version;
						$version_row['current_version'] = $base_app_rows_version['app_version'];
						$version_row['latest_version'] = $base_app_rows_version['app_version'];
						$version_row['zip_url'] = $base_app_rows_resources['app_package_url'];
						$version_row['state'] = intval($base_app_rows_resources['app_reinstall']);
						$version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];
					}
					else
					{
						$version_row['client_version'] = $client_version;
						$version_row['current_version'] = $client_version;
						$version_row['latest_version'] = $client_version;
						$version_row['zip_url'] = '1';
						$version_row['state'] = 0;
						$version_row['filesize'] = 0.00;
					}
				}
				else
				{
					$version_row['client_version'] = $client_version;
					$version_row['current_version'] = $app_version_next;
					$version_row['latest_version'] = $base_app_rows_version['app_version'];
					$version_row['zip_url'] = $base_app_rows_resources_next['app_res_url'].$base_app_rows_resources_next['app_res_childpath'].$base_app_rows_resources_next['app_res_filename'];
					$version_row['state'] = version_compare($version_row['client_version'], $version_row['current_version'],'eq') == 1 ? 0 : 1;
					$version_row['filesize'] = $base_app_rows_resources_next['app_res_filesize'];
				}
			}
		}
		else
		{
			$version_row['client_version'] = $client_version;
			$version_row['current_version'] = $client_version;
			$version_row['latest_version'] = $client_version;
			$version_row['zip_url'] = '1';
			$version_row['state'] = 0;
			$version_row['filesize'] = 0.00;
		}

		$this->data->setBody($version_row);
	}

	public function returnVersion()
	{
		echo $_REQUEST['version'];
		die();
	}
	public function getUserServer()
	{
		
		$skey = $_REQUEST['skey'];
		$app_id = $_REQUEST['app_id']; 
		$UserServerModel = new UserServer_UserServerModel();
		if($skey)
        {
            $UserServerModel->sql->setWhere('user_name','%'.$skey.'%','LIKE');
        }
		if($app_id)
        {
            $UserServerModel->sql->setWhere('app_id','%'.$app_id.'%','LIKE');
        }
	
		$data = $UserServerModel->getApp('*');
		fb($data);
		$this->data->addBody(-140, $data);
	}
	public function getAppId()
	{
		$BaseAppModel = new BaseApp_BaseAppModel();
		$data = $BaseAppModel->getApp('*');
		$array = array();
		foreach($data as $k => $v)
		{
			$array[] = $v['app_id'];
		}
		fb($array);
		$this->data->addBody(-140, $data);
	}
	public function getBaseApp()
	{
		$BaseAppModel = new BaseApp_BaseAppModel();
		$data = $BaseAppModel->getApp('*');
		$this->data->addBody(-140, $data);
	}
	public function getEditApp()
	{
		$appid = $_REQUEST['appid'];
		$BaseAppModel = new BaseApp_BaseAppModel();
		$data = $BaseAppModel->getApp($appid);
		fb($data);
		$this->data->addBody(-140, $data);
	}

	public function edit()
	{
		$appid = $_REQUEST['id'];
		$data['app_name'] = $_REQUEST['app_name'];
		$data['app_type'] = $_REQUEST['app_type'];
		$data['app_seq'] = $_REQUEST['app_seq'];
		$data['app_key'] = $_REQUEST['app_key'];
		$data['app_ip_list'] = $_REQUEST['app_ip_list'];
		$data['app_url'] = $_REQUEST['app_url'];
		$data['app_admin_url'] = $_REQUEST['app_admin_url'];
		$data['app_url_recharge'] = $_REQUEST['app_url_recharge'];
		$data['app_url_order'] = $_REQUEST['app_url_order'];
		$data['app_logo'] = $_REQUEST['app_logo'];
		$data['app_hosts'] = $_REQUEST['app_hosts'];
		$data['return_fields'] = $_REQUEST['return_fields'];
		$data['app_status'] = request_int('app_status');

		$BaseAppModel = new BaseApp_BaseAppModel();
		$flag = $BaseAppModel->editApp($appid,$data);
		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}
	public function add()
	{
		$appid = $_REQUEST['app_id'];
		$data['app_name'] = $_REQUEST['app_name'];
		$data['app_type'] = $_REQUEST['app_type'];
		$data['app_seq'] = $_REQUEST['app_seq'];
		$data['app_key'] = $_REQUEST['app_key'];
		$data['app_ip_list'] = $_REQUEST['app_ip_list'];
		$data['app_url'] = $_REQUEST['app_url'];
		$data['app_admin_url'] = $_REQUEST['app_admin_url'];
		$data['app_url_recharge'] = $_REQUEST['app_url_recharge'];
		$data['app_url_order'] = $_REQUEST['app_url_order'];
		$data['app_logo'] = $_REQUEST['app_logo'];
		$data['app_hosts'] = $_REQUEST['app_hosts'];
		$data['return_fields'] = $_REQUEST['return_fields'];
		
		$BaseAppModel = new BaseApp_BaseAppModel();
		$flag = $BaseAppModel->addApp($data);
		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function getUserInfo()
	{
		$user_id = request_string('user_id');

		$User_InfoModel = new User_InfoModel();

		$user_row = $User_InfoModel->getOne($user_id);

		if ($user_row)
		{
			$user_name = $user_row['user_name'];

			$User_InfoDetailModel = new User_InfoDetailModel();

			$user_info = $User_InfoDetailModel->getInfoDetail($user_name);

			$user_row['details'] = $user_info;
		}

		$this->data->addBody(100, $user_row);
	}

}
?>