<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BaseModel extends User_Base
{
	private $_multiCond = array('user_account'=>null);

	public function getUserIdByAccount($user_account)
	{
		$user_id_row = array();

		$this->_multiCond['user_account'] = $user_account;

		$user_id_row = $this->getKeyByMultiCond($this->_multiCond);

		return $user_id_row;
	}
	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserBaseList($user_name = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		if($user_name)
		{
			$this->sql->setWhere('user_account',$user_name);
		}
		$user_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_id_row)
		{
			$user_list = $this->getUser($user_id_row);
		}
		$user_list = array_values($user_list);
		
		$User_InfoModel = new User_InfoModel();
		foreach ($user_list as $key => $value) 
		{
			$user_info = $User_InfoModel->getInfo($value['user_account']);
			if($user_info)
			{
				$user_info = array_values($user_info);
				$user_info = $user_info['0'];
				$data_rows[$key] = array_merge($value,$user_info);
			}
			else
			{
				$data_rows[$key] = $value;
			}
			
		}
		fb($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	/**
	 * 根据用户名或者邮件地址查询用户
	 * @author houpeng
	 * @param null $user_account
	 * @param $kind_word 表示用户输入的是那个字段，1表示用户，2表示手机号
	 * @return array
	 */
	public function searchUser($input_word, $kind_word)
	{
		if($kind_word == 1)
		{
			$this->sql->setWhere("user_account", "%".$input_word."%", $symbol='LIKE');
			$rows = $this->getUser("*");
			if($rows)
			{
				//$rows = current($rows);
				return $rows;
			}
			else
			{
				return array();
			}
		}
		elseif ($kind_word == 2)
		{
			$this->sql->setWhere("user_tel", "%".$input_word."%", $symbol='LIKE');
			$rows = $this->getUser("*");
			if($rows)
			{
				//$rows = current($rows);
				return $rows;
			}
			else
			{
				return array();
			}
		}
	}


	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserList($user_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		$user_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_id_row)
		{
			$this->baseRightsGroupModel = new Rights_GroupModel();
			$data_rights = $this->baseRightsGroupModel->getRightsGroupList();
			$data_rights = $data_rights['items'];
			$data_rows = $this->getUser($user_id_row);
			foreach($data_rows as $key=>$val)
			{
				foreach($data_rights as $k=>$v)
				{
					if($val['rights_group_id'] == $v['rights_group_id'])
					{
						$data_rows[$key]['rights_group_name'] = $v['rights_group_name'];
					} 
				}
			}
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getAccount($user_id){
		$this->sql->setWhere('user_id',$user_id);
		$data_rows = $this->getUser('*');
		return $data_rows;
	}
	public function getUser1($uid){
		$this->sql->setLimit();
		$this->sql->setWhere("user_id!",$uid);
		$list = $this->get('*');
		return $list;
	}


	public function getInfoByName($user_name)
	{
		$data = array();

		$this->sql->setWhere('user_account', $user_name);
		$data_rows = $this->getUser('*');
		if ($data_rows)
		{
			$data = array_pop($data_rows);
		}

		return $data;
	}


	public function getUserIdByName($user_name=null)
	{
		$data_rows = array();

		$this->sql->setWhere('user_account', $user_name ,"IN");
		$data_rows = $this->selectKeyLimit();

		return $data_rows;
	}

	public function getUserName($UserName,$user_name=null) {
		$this->sql->setWhere("user_account",$UserName);
		$this->sql->setWhere("user_account!",$user_name);
		$rows = $this->getUser("*");
		return $rows;
	}

	public function getBaseInfo($user_id=null)
	{
		$data = $this->getOne($user_id);

		$User_InfoModel = new User_InfoModel();
		$user_info = $User_InfoModel->getOne($data['user_account']);

		$data = $data + $user_info;

		//获取用户最新发布的三个带图的信息
		$Sns_BaseModel = new Sns_BaseModel();
		$user_sns = $Sns_BaseModel->getBaseSnsNextLimit($user_id, 1, $sort='desc', 1, 3, 0);

		$data['user_sns'] = array_values($user_sns);

		return $data;
	}
    
    /**
     * 
     * @param type $function
     * @param type $data
     */
    public function getUcenterData($ctl,$met,$data){
        $key = Yf_Registry::get('ucenter_api_key');;
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');
        $formvars = $data;
		$formvars['app_id'] = $app_id;

		$formvars['ctl'] = $ctl;
		$formvars['met'] = $met;
		$formvars['typ'] = 'json';
		$init_rs = get_url_with_encrypt($key, $url,$formvars);
        return $init_rs;
    }
	
}
?>