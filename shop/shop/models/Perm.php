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
		'user_id',
		'shop_id_row',
		'shop_id',
		'chain_id',
	);
	public static $row        = array();  //当前用户信息
	public static $shopId     = 0;
	public static 
		$chainId    = 0;  //门店id

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

			if ($user_key && @$user_row['user_id'] == $user_row_default['user_id'])
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

 		//user_account 这个COOKIE IM是需要的。by sunkang
        if($user_row['user_account']){
            setcookie('user_account',$user_row['user_account']);  
            unset($user_row['user_account']);
        }
		
		//////

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

		//setcookie(self::$cookieName, $encrypt_str, null, null, null, true);
		//setcookie(self::$cookieId, $user_row['user_id'], null, null, null, true);
		 
		setcookie(self::$cookieName, $encrypt_str);
		setcookie(self::$cookieId, $user_row['user_id']);
		$_COOKIE[self::$cookieName] = $encrypt_str;
		$_COOKIE[self::$cookieId] = $user_row['user_id'];
		 
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
		setcookie(self::$cookieId, null, $expires);
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
		if (array_key_exists('user_id', $user_row))
		{
            

            self::$userId = $user_row['user_id'];
			self::$shopId = @$user_row['shop_id'];
			self::$chainId = @$user_row['chain_id'];			
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

	public static function checkSubDomain()
	{
		$server_name = $_SERVER['SERVER_NAME'];
        //判断域名格式是否正确
		$point_num =  substr_count($server_name, '.');
        if($point_num < 2){
            return false;
        }
        $local_web_url_array = parse_url(Yf_Registry::get('shop_api_url'));
        $server_name_suffix = trim(strstr($server_name, '.',0),'.');
        if($server_name_suffix != $local_web_url_array['host']){
            if('www.'.$server_name_suffix != $local_web_url_array['host']){
                return false;
            }
        }
        
        $third_level_domain = strstr($server_name, '.',1);
		$shopDomainModel = new Shop_DomainModel();
		$shop_domain_rows = $shopDomainModel->getByWhere( array('shop_sub_domain:<>'=> NULL) );
      
		if (!empty($shop_domain_rows)){
			$shop_sub_domain_rows = array_column($shop_domain_rows, 'shop_sub_domain', 'shop_id');
			$shop_sub_domain_rows = array_filter($shop_sub_domain_rows);

			if (!empty($shop_sub_domain_rows)) {

				foreach ($shop_sub_domain_rows as $shop_id => $shop_sub_domain) {
					if ( $shop_sub_domain == $third_level_domain ) {
						return $shop_id;
					}
				}
			}
			return false;
		} else {
			return false;
		}
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
    
    /**
     * 清除首页缓存
     * @return boolean
     */
    public static function removeIndexCache(){
        $Cache = Yf_Cache::create('default');
        $site_index_key = sprintf('%s|%s|%s', Yf_Registry::get('server_id'), 'site_index',  isset($_COOKIE['sub_site_id']) ? $_COOKIE['sub_site_id'] : 0);
        $Cache->_id = $site_index_key;
        $result = $Cache->remove($site_index_key);    
        return $result;
    }
}

?>