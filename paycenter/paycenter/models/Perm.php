<?php

class Perm
{
	public static $cookieName = 'key';
	public static $cookieId   = 'id';
	public static $login      = false;
	public static $userId     = 0;
	public static $serverId   = 0;
	public static $_COOKIE    = array();
	public static $key        = array(
		'user_id'
	);
	public static $row        = array();  //当前用户信息
	public static $shopId     = 0;

	/**
	 * 初始化登录的用户信息cookie
	 *
	 * @access public
	 *
	 * @return Array  $user_row;
	 */
	public static function getUserByCookie()
	{
		$user_key         = null;
		$user_row_default = array();

		if (array_key_exists(self::$cookieId, $_COOKIE))
		{
			$id = $_COOKIE[self::$cookieId];

			//获取用户信息
			//改成文本存储, 不连接数据库
			$userModel        = new User_BaseModel();
			$user_rows        = $userModel->getBase($id);

			//有可能没有数据返回，这时向Ucenter请求数据验证，再给一次机会
			if (empty($user_rows) && request_string('k') && request_string('u')) {
				$user_rows = self::checkUserByUCenter();
				if ($user_rows === false) {
					$user_rows = [];
				}
			}

			$user_row_default = array_pop($user_rows);

			if ($user_row_default)
			{
				$user_key = $user_row_default['user_key'];
			}

			//Perm::$row = $user_row_default;
		}

		//设置当前用户的Key
		Yf_Hash::setKey($user_key);

		$user_row = array();

		if (array_key_exists(self::$cookieName, $_COOKIE))
		{
			$encrypt_str = $_COOKIE[self::$cookieName];
			$user_row    = self::decryptUserInfo($encrypt_str);

			if ($user_key && $user_row['user_id'] == $user_row_default['user_id'])
			{
				Perm::$row = $user_row_default;
			}
		}
		else
		{
			
		}

		return $user_row;
	}

	/**
	 * 用户数组信息编码成字符串， 设置cookie
	 *
	 * @param array $user_row 用户信息
	 * @access public
	 *
	 * @return string  $encrypt_str;
	 */
	public static function encryptUserInfo($user_row = null, $user_key = null)
	{
		$user_str = http_build_query($user_row);

		$user_str = str_replace('&amp;', '&', $user_str);

		if ($user_key)
		{
			Yf_Hash::setKey($user_key);
		}

		$encrypt_str = Yf_Hash::encrypt($user_str);

		$expires = time() + 60 * 60 * 24 * 30;

		//setcookie(self::$cookieName, $encrypt_str, $expires, '/');

		//setcookie(self::$cookieName, $encrypt_str, $expires);
		//setcookie(self::$cookieId, $user_row['user_id'], $expires);

		setcookie(self::$cookieName, $encrypt_str);
		setcookie(self::$cookieId, $user_row['user_id']);

		return $encrypt_str;
	}


	/**
	 * 用户logout
	 *
	 * @access public
	 *
	 * @return bool  true/false;
	 */
	public static function removeUserInfo()
	{
		$expires = time() - 3600;

		//setcookie(self::$cookieName, '', $expires, '/');
		setcookie(self::$cookieName, null, $expires);

		return true;
	}

	/**
	 * 还原cookie信息为数组
	 *
	 * @param string $encrypt_str ;
	 * @access public
	 *
	 * @return array $user_row  用户信息
	 */
	public static function decryptUserInfo($encrypt_str = null)
	{
		if (!$encrypt_str)
		{
			//$encrypt_str = 'AnUJfwM5ACJdVFNtU2tbMAJkBnAOJVUiUjcFfQhSBjoJalI6UGoAbV1zAT8GNFR4VGZUIgwnBnECZwZ+CFJVaQJpCW8DNwA+XWpTaVNqWzACLQY/Dj5VK1I3BSkIbAY4CWdSPlBuAD5daAEzBgVUa1RnVDkMYwYkAm8GbQh9VVgCaQloA2EAZF07UzZTO1s9AmYGcA4zVThSJgV2CFIGOglqUjpQagBtXXMBPwY0VHhUZlQhDBcGNQInBjUITFUiAjgJOAN5ABVdPlMhUzZbSwJwBm4OFVV0UhcFOQgoBhYJOlJyUE4AYF0tAToGNVRlVGpUagwNBnYCawZhCGhVdAI9CT0Dbg==';
		}

		$decrypt_str = Yf_Hash::decrypt($encrypt_str);
		parse_str($decrypt_str, $user_row);

		return $user_row;
	}


	/**
	 * 判断用户是否拥有访问权限
	 *
	 * @return bool true/false
	 */
	public static function checkUserPerm()
	{
		//登录通过
		$user_row = self::getUserByCookie();
		fb('$user_row');
		fb($user_row);
		if (array_key_exists('user_id', $user_row))
		{
			self::$userId = $user_row['user_id'];
			self::$login  = true;

			return true;
		}
		else
		{
			return false;
		}


		//操作权限rights
		//读取用户

	}

	/**
	 * 判断用户是否拥有访问权限-功能权限
	 *
	 * @return bool true/false
	 */
	public static function checkUserRights()
	{
		if (self::$login)
		{
			//读取当然用户信息
			$user_row = Perm::$row;

			if ($user_row && self::$userId == $user_row['user_id'])
			{

			}
			else
			{
				//赋值
				$userModel = new User_BaseModel();
				$user_rows = $userModel->getBase(Perm::$userId);
				$user_row  = array_pop($user_rows);

				Perm::$row = $user_row;
			}

			//通过protocal ini  文件获取权限id
			$Yf_Registry = Yf_Registry::getInstance();
			$ccmd_rows   = $Yf_Registry['ccmd_rows'];

			$rid = null;

			if (isset($ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]))
			{
				$rid = $ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]['rid'];
			}

			//权限要求为false
			if (!$rid)
			{
				return true;
			}

			//判断权限id是否存在
			if ($rights_group_id = $user_row['rights_group_id'])
			{
				//
				$rightsGroupModel = new Rights_GroupModel();

				$data_rows = $rightsGroupModel->getRightsGroup($rights_group_id);

				if (isset($data_rows[$rights_group_id]['rights_group_rights_ids']) && in_array($rid, $data_rows[$rights_group_id]['rights_group_rights_ids']))
				{
					return true;
				}
			}
		}


		//操作权限rights
		//读取用户

		return false;

	}

	public static function getUserId()
	{
		return isset(self::$_COOKIE['user_id']) ? self::$_COOKIE['user_id'] : 0;
	}

	public static function getServerId()
	{
		return self::$serverId;
	}

	/**
	 * 在user_base表找不到对应用户信息时，向ucenter请求验证
	 */

	private static function checkUserByUCenter()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars            = array();
		$formvars['user_id'] = request_int('u');
		$formvars['u']       = request_int('u');
		$formvars['k']       = request_string('k');
		$formvars['app_id']  = $app_id;

		$url     = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Login', 'checkLogin', 'json');
		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		if (200 != $init_rs['status']) {
			return false; //Ucenter没有此用户，直接返回
		}

		//读取服务列表
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();

		$user_row = $init_rs['data'];
		$user_id = $user_row['user_id'];
		$user_name = $user_row['user_name'];


		$data['user_id'] = $init_rs['data']['user_id']; // 用户id
		$data['user_account'] = $init_rs['data']['user_name']; // 用户帐号

		$data['user_delete'] = 0; // 用户状态
		$user_id = $User_BaseModel->addBase($data, true);

		//判断是否添加数据成功
		if (!$user_id) {
			return false; //添加失败，直接返回
		}

		//初始化用户信息
		$user_info_row = array();
		$user_info_row['user_id'] = $user_id;
		$user_info_row['user_realname'] = @$init_rs['data']['user_truename'];
		$user_info_row['user_mobile'] = @$init_rs['data']['user_mobile'];
		$User_InfoModel = new User_InfoModel();
		$info_flag = $User_InfoModel->addInfo($user_info_row);

		$user_resource_row = array();
		$user_resource_row['user_id'] = $user_id;

		$User_ResourceModel = new User_ResourceModel();
		$res_flag = $User_ResourceModel->addResource($user_resource_row);

		$user_row = $data;

		$data = array();
		$data['user_id'] = $user_row['user_id'];
		srand((double)microtime() * 1000000);
		$user_key = $init_rs['data']['session_id'];
		$time = get_date_time();
		//获取上次登录的时间
		$info = $User_BaseModel->getBase($user_row['user_id']);

		$lotime = strtotime($info[$user_row['user_id']]['user_login_time']);

		$login_info_row = array();
		$login_info_row['user_key'] = $user_key;
		$login_info_row['user_login_time'] = $time;
		$login_info_row['user_login_times'] = $user_row['user_login_times'] + 1;
		$login_info_row['user_login_ip'] = get_ip();

		$flag = $User_BaseModel->editBase($user_row['user_id'], $login_info_row, false);

		return $User_BaseModel->getBase($user_row['user_id']);
	}
    
    /**
     * 验证图形验证码，验证一次后，验证码失效
     * @param type $yzm
     * @return boolean
     */
    public static function checkYzm($yzm,$type = false){
        if(!$yzm){
            return false;
        }
		session_start();
        $result = strtolower($_SESSION['auth']) != strtolower($yzm) ? false : true;
        if(!$type){
            unset($_SESSION['auth']);
        }
        return $result;
    }
}

?>