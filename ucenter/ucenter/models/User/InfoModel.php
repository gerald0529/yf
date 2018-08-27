<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoModel extends User_Info
{
	public static $userSex = array(
		"0" => '女',
		"1" => '男',
		"2" => '保密'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $licence_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfoList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data["items"] as $key => $value)
		{
			$data["items"][$key]["user_gender1"] = _(User_InfoModel::$userSex[$value["user_gender"]]);
		}
		return  $data;
	}


	public function getInfoByName($user_name)
	{
		$data = array();

		$this->sql->setWhere('user_name', $user_name);
		$data_rows = $this->getInfo('*');

		if ($data_rows)
		{
			$data = array_pop($data_rows);
		}

		return $data;
	}

	public function getUserIdByName($user_name=null)
	{
		$data = array();

		$this->sql->setWhere('user_name', $user_name, 'IN');
		$data_rows = $this->selectKeyLimit();

		if ($data_rows)
		{
			$data = array_pop($data_rows);
		}

		return $data;
	}

	public function userlogin($uid=null)
	{
		$user_info_row = $this->getInfo($uid);
		$user_info_row = array_values($user_info_row);
		$user_info_row = $user_info_row[0];
		$session_id = $user_info_row['session_id'];

		$arr_field = array();
		$arr_field['session_id'] = $session_id;

		if($user_info_row)
		{
			$arr_body = $user_info_row;
			$arr_body['result'] = 1;

			$data = array();
			$data['user_id'] = $user_info_row['user_id'];

			$encrypt_str = Perm::encryptUserInfo($data, $session_id);

			$arr_body['k']=$encrypt_str;
		}
		else
		{
			$arr_body = array();
		}

		return $arr_body;
	}
	/**
     * 修改用户名
     */
	public function alterUsername($user_id, $user_name){
        $this->editInfo($user_id, $user_name);
    }

	//生成8位由数字，大小写字母组成的随机字符串
	public function random_str($length)
	{
		//生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
		$arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

		$str = '';
		$arr_len = count($arr);
		for ($i = 0; $i < $length; $i++)
		{
			$rand = mt_rand(0, $arr_len-1);
			$str.=$arr[$rand];
		}

		return $str;
	}
}
?>