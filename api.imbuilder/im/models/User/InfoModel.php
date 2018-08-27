<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoModel extends User_Info
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_name 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfoList($user_name = null, $page=1, $rows=100, $sort='asc')
	{

		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_name_row = array();
		if($user_name)
		{
			$this->sql->setWhere('user_name','%'.$user_name.'%','LIKE');
		}
		$user_name_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_name_row)
		{
			$data_rows = $this->getInfo($user_name_row);
		}


		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
	
	public function getFollow($user_account){
		$this->sql->setWhere("user_name",$user_account);
		$rows = $this->getInfo("*");
		return $rows;
	}

	public function get1($nickname,$user_name=null) {
			$this->sql->setWhere("nickname",'%'.$nickname.'%','like');
			$this->sql->setWhere("user_name!",$user_name);
			$rows = $this->getInfo("*");
			return $rows;
        }

	public function  getUserInfo($user_name=null,$user_id=null)
	{
		$data = $this->getOne($user_name);

		//获取用户最新发布的三个带图的信息
		$Sns_BaseModel = new Sns_BaseModel();
		$user_sns = $Sns_BaseModel->getBaseSnsNextLimit($user_id, 1, $sort='desc', 1, 3, 0);

		$data['user_sns'] = array_values($user_sns);

		return $data;
	}

	public function getInfoByMobile($mobile=null)
	{
		$this->sql->setWhere("user_mobile",$mobile);
		$rows = $this->getInfo("*");
		return $rows;
	}

	public function getInfoByName($name)
	{
            if(is_array($name)){
                $user_name = implode("','", $name);
                $user_name = "'".$user_name."'";
            }else{
                $user_name = $name;
            }
            
            $sql = 'SELECT * FROM '.$this->_tableName.' WHERE user_name IN ('.$user_name.') ORDER BY FIELD (user_name, '.$user_name.')';
//            SELECT * FROM `im_user_base` WHERE user_id IN(10002,10008,10003,10001)  ORDER BY FIELD(user_id, 10002,10008,10003,10001);
            $result = $this->sql->getAll($sql);
            return $result;
	}

	
	
}
?>