<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口
 *
 * 
 * @todo
 */
class Connect_WeixinInCtl extends Yf_AppController  
{
	public $appid     = null;
	public $appsecret = null;
	public $redirect_url = null;

 
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ); 
 
		$connect_config = Yf_Registry::get('connect_rows');;

		$this->appid     = $connect_config['weibo']['app_id'];
		$this->appsecret = $connect_config['weibo']['app_key'];

		$this->redirect_url = sprintf('%s?ctl=Connect_Weibo&met=callback&from=%s&callback=%s', Yf_Registry::get('url'),request_string('from'), urlencode(request_string('callback')));
	}
 	
 	public function login()
	{ 
		$config['bw'] = "weixin";

		$connect_config = Yf_Registry::get('connect_rows');;

		$config['weixin_app_id']     = $connect_config['weixin']['app_id'];
		$config['weixin_key'] = $connect_config['weixin']['app_key'];

		$appid = $config[ 'weixin_app_id'];
		$appsecret = $config['weixin_key'];
	  	$current_host = Yf_Registry::get('ucenter_api_url');
		$callback = urlencode(request_string('callback'));
		$redirect_uri = urlencode($current_host."?ctl=Connect_WeixinIn&met=login&callback=".$callback);
	 
		if($config['bw'] == "weixin")
		{
			$wechat_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=123&connect_redirect=1#wechat_redirect";
		}
		else
		{
			$wechat_url = "https://open.weixin.qq.com/connect/qrconnect?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
		} 
		if ($config['bw'] == "weixin")
		{
			if (!isset($_GET['code']))
			{
					header('location:' . $wechat_url);
					die(); 
			}
		}

		$code = $_GET['code'];
		$state = $_GET['state'];

		if($code)
		{
			$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
			$token = json_decode(file_get_contents($token_url)); 

			$access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$token->refresh_token;
			$access_token = json_decode(file_get_contents($access_token_url)); 
			$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token->access_token.'&openid='.$access_token->openid.'&lang=zh_CN';
			$user_info_row = json_decode(file_get_contents($user_info_url),true);

			$openid = $user_info_row['openid'];

			//判断当前登录账户
			//判断当前登录账户
			if (Perm::checkUserPerm())
			{
				$user_id = Perm::$userId;
			}
			else
			{
				$user_id = 0;
			}

			if($openid)
			{
				$type = User_BindConnectModel::WEIXIN;
				 $User_BindConnectModel = new User_BindConnectModel();

				 $bind_id     = sprintf('%s_%s', 'weixin', $user_info_row['openid']);

				//根据openid查找绑定表中的数据
				 $connect_rows = $User_BindConnectModel->getBindConnect($bind_id);
				 $bind_rows     = $connect_rows;

				 if ($connect_rows)
				 {
					 $connect_row = array_pop($connect_rows);
				 }

				 //已经绑定,并且用户正确
				if (isset($connect_row['user_id']) && $connect_row['user_id'])
				{
					//验证通过, 登录成功.当前有用户登录，且登录用户即为绑定用户
					if ($user_id && $user_id == $connect_row['user_id'])
					{

						if(request_string('callback'))
						{
							$url = sprintf('%s?ks=%s', request_string('callback'),  $user_id);
							location_to($url);
						}
						else
						{
							$url = sprintf('%s?ctl=Login', Yf_Registry::get('url'));
							location_to($url);

						}
						echo '非法请求,已经登录用户不应该访问到此页面';

						die();
					}

					$login_flag = true;
				}
				else
				{
					$bind_avator = $user_info_row['headimgurl'];

					// 下面可以需要封装
					$access_token = $access_token->access_token;

					if ($bind_rows  && $bind_row = array_pop($bind_rows))
					{
						//该微信号已经绑定了用户，已经绑定用户的微信号不可再绑定其他用户
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

						//已经存在绑定信息，并且排除非法情况后，求改绑定信息中的user_id和token。将当前登录用户绑定到微信上，如果当前没有用户登录则user_id=0
						$data_row                      = array();
						$data_row['user_id']           = $user_id;
						$data_row['bind_token'] = $access_token;

						$connect_flag = $User_BindConnectModel->editBindConnect($bind_id, $data_row);
					}
					else
					{
						if($user_id != 0)
						{
							//如果当前有用户登录，则判断当前用户是否绑定过微信账号
							$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id,$type);
							if($bind_id_row)
							{
								echo '非法请求,该账号已经绑定';
								die();
							}
						}

						//排除所有与非法情况后，在绑定表中添加绑定信息
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

					//1.当前登录用户绑定微信成功
					//2.生成新的微信绑定信息成功
					if ($connect_flag)
					{
						//如果当前没有用户登录，即微信没有绑定任何用户。则根据微信返回的用户信息在Ucenter生成新用户
						if (!Perm::checkUserPerm())
						{
							$server_id = 0;
							$rs_row = array();

							//生成用户信息
							$User_InfoModel  = new User_InfoModel();
							$User_InfoDetail = new User_InfoDetailModel();

							$User_InfoModel->sql->startTransaction();

							$Db       = Yf_Db::get('ucenter');
							$seq_name = 'user_id';
							$user_id  = $Db->nextId($seq_name);

							$now_time = time();
							$ip       = get_ip();

							$session_id                         = uniqid();
							$arr_field_user_info                = array();
							$arr_field_user_info['user_id']     = $user_id;
							$arr_field_user_info['user_name']   = $user_info_row['nickname'];
							$arr_field_user_info['action_time'] = $now_time;
							$arr_field_user_info['action_ip']   = $ip;
							$arr_field_user_info['session_id']  = $session_id;

							$flag = $User_InfoModel->addInfo($arr_field_user_info);
							check_rs($rs_row, $flag);

							$arr_field_user_info_detail                        = array();
							$arr_field_user_info_detail['user_name']           = $user_info_row['nickname'];
							$arr_field_user_info_detail['user_reg_time']       = $now_time;
							$arr_field_user_info_detail['user_count_login']    = 1;
							$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
							$arr_field_user_info_detail['user_lastlogin_ip']   = $ip;
							$arr_field_user_info_detail['user_reg_ip']         = $ip;
							$arr_field_user_info_detail['nickname']            = $user_info_row['nickname'];
							$arr_field_user_info_detail['user_avatar']         = $bind_avator;
							$arr_field_user_info_detail['user_gender']         = $user_info_row['sex'];
							$arr_field_user_info_detail['user_avatar_thumb']   = $bind_avator;

							$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
							check_rs($rs_row, $flag);

							//生成用户成功后绑定微信
							if (is_ok($rs_row) && $User_InfoModel->sql->commit())
							{
								$d            = array();
								$d['user_id'] = $user_id;

								$encrypt_str = Perm::encryptUserInfo($d, $session_id);

								$arr_body = array(
									"user_name" => $user_info_row['nickname'],
									"server_id" => $server_id,
									"k" => $encrypt_str,
									"user_id" => $user_id
								);

								$time = date('Y-m-d H:i:s',time());

								//插入绑定表
								$bind_array = array(
									'user_id'=>$user_id,
									'bind_time'=>$time,
									'bind_token'=>$access_token,
								);
								$edit_flag = $User_BindConnectModel->editBindConnect($bind_id,$bind_array);

								if($edit_flag)
								{
									$login_flag = true;
								}
								else
								{
									$login_flag = false;
								}

							}else{
								$User_InfoDetail->sql->rollBack();
								//$this->data->setError('创建用户信息失败');
								echo '创建用户信息失败';
								die();
							}


						}
						else
						{
							$login_flag = true;
						}
					}
				}

				//用户生成成功，并且与微信绑定成功后用户登录
				if ($login_flag)
				{
					//根据openid查找绑定表中的数据
					$connect_rows = $User_BindConnectModel->getBindConnect($bind_id);
					if ($connect_rows)
					{
						$connect_row = array_pop($connect_rows);
					}

					$User_InfoModel  = new User_InfoModel();

					$user_info_row   = $User_InfoModel->getInfo($connect_row['user_id']);

					$user_info_row = array_values($user_info_row);
					$user_info_row = $user_info_row[0];
					$session_id = $user_info_row['session_id'];

					$arr_field               = array();
					$arr_field['session_id'] = $session_id;

					if ($user_info_row)
					{
						$arr_body           = $user_info_row;
						$arr_body['result'] = 1;

						$data            = array();
						$data['user_id'] = $user_info_row['user_id'];

						$encrypt_str = Perm::encryptUserInfo($data, $session_id);

						$arr_body['k'] = $encrypt_str;
						$this->data->addBody(100, $arr_body);

					}
					else
					{
						$this->data->setError('登录失败');
					}

					if(request_string('callback'))
					{
						$ks = $arr_body['k'];
						$url = sprintf('%s?ks=%s', urldecode(request_string('callback')),  $ks);
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

			}
		 
		}
 	}




 }