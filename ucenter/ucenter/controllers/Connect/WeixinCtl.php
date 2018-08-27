<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Connect_WeixinCtl extends Yf_AppController implements Connect_Interface
{
	public $appid     = null;
	public $appsecret = null;

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

		$connect_config = Yf_Registry::get('connect_rows');;

		$this->appid     = $connect_config['weixin']['app_id'];
		$this->appsecret = $connect_config['weixin']['app_key'];

		//Yf_Registry::get('url')

		$this->redirect_url = sprintf('%s?ctl=Connect_Weixin&met=callback&from=%s&callback=%s',Yf_Registry::get('url') , request_string('from'), urlencode(request_string('callback')));
	}

	public function select()
	{
		include $this->view->getView();
	}

	public function login()
	{
		//判断当前登录账户,绑定

		//子站跳转
		
		$redirect_url = urlencode($this->redirect_url);

		$url = '';
		//

		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
		{
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=123&connect_redirect=1#wechat_redirect";
		}
		else
		{
			$url = "https://open.weixin.qq.com/connect/qrconnect?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
		}

		location_to($url);
	}

	/**
	 * callback 回调函数
	 *
	 * @access public
	 */
	public function callback()
	{
		$type = User_BindConnectModel::WEIXIN;

		$code = request_string('code', null);

		$redirect_url = $this->redirect_url;

		$login_flag = false;

		//判断当前登录账户
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;
		}
		else
		{
			$user_id = 0;
		}

		if ($code)
		{
			$token_url        = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $code . '&grant_type=authorization_code';
			$access_token_row = json_decode(file_get_contents($token_url), true);

			if (!$access_token_row || !empty($access_token_row['errcode']))
			{
				throw new Yf_ProtocalException($access_token_row['errmsg']);
				return false;
			}
			else
			{
				$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token_row['access_token'] . '&openid=' . $access_token_row['openid'] . '&lang=zh_CN';
				$user_info_row = json_decode(@file_get_contents($user_info_url), true);

				/*
				$user_info_row[''] => 1
				$user_info_row['']
				$user_info_row['']
				$user_info_row['']
				$user_info_row['country']
				$user_info_row['']
				$user_info_row['privilege']
				*/

				$User_BindConnectModel = new User_BindConnectModel();

				$bind_id     = sprintf('%s_%s', 'weixin', $user_info_row['openid']);
				$connect_rows = $User_BindConnectModel->getBindConnect($bind_id);

				if ($connect_rows)
				{
					$connect_row = array_pop($connect_rows);
				}

				//已经绑定,并且用户正确
				if (isset($connect_row['user_id']) && $connect_row['user_id'])
				{
					//验证通过, 登录成功.
					if ($user_id && $user_id == $connect_row['user_id'])
					{
						echo '非法请求,已经登录用户不应该访问到此页面';
						die();
					}
					else
					{
					}

					$login_flag = true;
				}
				else
				{
					$bind_avator = $user_info_row['headimgurl'];

					// 下面可以需要封装
					$access_token = $access_token_row['access_token'];
					$bind_rows     = $User_BindConnectModel->getBindConnect($bind_id);

					if ($bind_rows  && $bind_row = array_pop($bind_rows))
					{
						if ($bind_row['user_id'])
						{
							//该账号已经绑定
							echo '非法请求,该账号已经绑定';
							die();
						}
						if($user_id != 0)
						{
							$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id,$type);
							if($bind_id_row)
							{
								echo '非法请求,该账号已经绑定';
								die();
							}
						}

						$data_row                      = array();
						$data_row['user_id']           = $user_id;
						$data_row['bind_token'] = $access_token;

						$connect_flag = $User_BindConnectModel->editBindConnect($bind_id, $data_row);
					}
					else
					{
						if($user_id != 0)
						{
							$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id,$type);
							if($bind_id_row)
							{
								echo '非法请求,该账号已经绑定';
								die();
							}
						}
						$data = array();

						$data['bind_id']           = $bind_id;
						$data['bind_type']         = $User_BindConnectModel::WEIXIN;
						$data['user_id']           = $user_id;
						$data['bind_nickname']     = $user_info_row['nickname']; // 名称
						$data['bind_avator']         = $bind_avator; //
						$data['bind_gender']       = $user_info_row['sex']; // 性别 1:男  2:女
						$data['bind_openid']       = $user_info_row['openid']; // 访问
						$data['bind_token'] = $access_token;

						$connect_flag = $User_BindConnectModel->addBindConnect($data);
					}
					
					//取得open id, 需要封装
					if ($connect_flag)
					{
						//选择,登录绑定还是新创建账号 $user_id == 0
						if (!Perm::checkUserPerm())
						{
							$url = sprintf('%s?ctl=Login&met=select&t=%s&type=%s&from=%s&callback=%s', Yf_Registry::get('url'), $access_token, $type, request_string('from'), urlencode(request_string('callback')));
							location_to($url);
						}
						else
						{
							$login_flag = true;
						}
					}
					else
					{
						//
					}
				}
			}

			if ($access_token_row)
			{

			}
			if ($login_flag)
			{
				//验证通过, 登录成功.
				if ($user_id && $user_id == $connect_row['user_id'])
				{
					echo '非法请求,已经登录用户不应该访问到此页面';
					die();
				}
				else
				{
					$User_InfoModel  = new User_InfoModel();
					$User_InfoDetail = new User_InfoDetail();

					$user_info_row   = $User_InfoModel->getInfo($connect_row['user_id']);
					fb($user_info_row);
					$user_info_row = array_values($user_info_row);
					$user_info_row = $user_info_row[0];
					$session_id = $user_info_row['session_id'];

					$arr_field               = array();
					$arr_field['session_id'] = $session_id;

					if ($user_info_row)
					{
						//$arr_body = array("result"=>1,"user_name"=>$user_info_row['user_name'],"session_id"=>$session_id);
						$arr_body           = $user_info_row;
						$arr_body['result'] = 1;
						//$arr_body['session_id'] = $session_id;

						$data            = array();
						$data['user_id'] = $user_info_row['user_id'];

						//$data['session_id'] = $session_id;
						$encrypt_str = Perm::encryptUserInfo($data, $session_id);

						$arr_body['k'] = $encrypt_str;
						fb($arr_body);
						$this->data->addBody(100, $arr_body);

					}
					else
					{
						$this->data->setError('登录失败');
					}

				}

				$login_flag = true;


				if(request_string('callback'))
				{
					$us = $arr_body['user_id'];
					$ks = $arr_body['k'];
				    $url = sprintf('%s&us=%s&ks=%s', request_string('callback'), $us, $ks);
				    location_to($url);

				}
				else
				{
					$url = sprintf('%s?ctl=Login', Yf_Registry::get('url'));
					location_to($url);
				}
				echo '登录系统';
				die();

			}
			else
			{
				//失败
			}

		}
		else
		{
			//失败

		}
	}

}

?>
