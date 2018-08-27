<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BaseModel extends User_Base
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getBaseIdByAccount($user_account = null)
	{
		$data = $this->getByWhere(array('user_account' => $user_account));

		return $data['items'];
	}
	
	/**
	 * 读取会员信息
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserInfo($order_row = array())
	{
		return $this->getOneByWhere($order_row);
	}

	public function getUserIdByAccount($user_account)
	{
		$user_id_row = array();

		$this->_multiCond['user_account'] = $user_account;

		$user_id_row = $this->getKeyByMultiCond($this->_multiCond);

		return $user_id_row;
	}

	/**
	 * 根据账号获取店铺信息
	 *
	 * @param $user_account int
	 * @return array
	 */
	public function getStoreInfoByUserAccount ($user_account)
	{
		$user_rows = $this->getByWhere(['user_account'=> $user_account]);

		if (empty($user_rows)) { //没有对应用户信息
			return [];
		}

		$user_data = current($user_rows);
		$user_id = $user_data['user_id'];

		$shopBaseModel = new Shop_BaseModel();
		$shop_rows = $shopBaseModel->getByWhere(['user_id'=> $user_id]);

		if (empty($shop_rows)) { //没有对应店铺信息
			return [];
		}

		$shop_data = current($shop_rows);

		return $shop_data;
	}
    
    
    /**
     * 从paycenter获取用户信息
     * @param type $user_id
     * @return boolean
     */
    public function getUcenterInfo($user_id){
        if(!$user_id){
            return false;
        }
        $key = Yf_Registry::get('ucenter_api_key');
		$url       = Yf_Registry::get('ucenter_api_url');
		$app_id    = Yf_Registry::get('ucenter_app_id');
		$server_id = Yf_Registry::get('server_id');
		//开通ucenter
		//本地读取远程信息
		$formvars              = array();
		$formvars['user_id'] = $user_id;
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;

		$formvars['ctl'] = 'Api_User';
		$formvars['met'] = 'getUserInfo';
		$formvars['typ'] = 'json';

		$init_rs = get_url_with_encrypt($key, $url, $formvars);
        return $init_rs;
    }

}

?>