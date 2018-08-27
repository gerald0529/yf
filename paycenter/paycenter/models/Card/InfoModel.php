<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class 	Card_InfoModel extends Card_Info
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfoList($card_id = null, $beginDate = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$card_code_row = array();
		if($card_id)
		{
			$this->sql->setWhere('card_id',$card_id);
		}

		if($beginDate)
		{
			//date('Y-m-d',strtotime('+1 d',strtotime($beginDate));
			$time = strtotime($beginDate);
			$beginDay = date('Y-m-d H:i:s',$time);
			$endDay = date('Y-m-d H:i:s',$time+86400);


			$this->sql->setWhere('card_time',$beginDay,'>=');
			$this->sql->setWhere('card_time',$endDay,'<');
		}

		$card_code_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($card_code_row)
		{
			$data_rows = $this->getInfo($card_code_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}


	//通过card_id 获取充值卡信息
	public function getListCardInfoByCardId($card_id = null)
	{
		$card_rows = $this->getByWhere(array('card_id' => $card_id));
		$Card_BaseModel = new Card_BaseModel();
		$card_base = $Card_BaseModel->getByWhere(array());
		foreach($card_rows as $key => $val)
		{
			$card_rows[$key]['card_base'] = $card_base[$val['card_id']];
		}
		return $card_rows;
	}

	//通过card_id 获取充值卡的卡号
	public function getListCardcodeByCid($card_id = null)
	{
		$this->sql->setWhere('card_id',$card_id);
		$card_code = end($this->selectKeyLimit());
		return $card_code;
	}

	//获取已使用的充值卡数量
	public  function getCardusednumBy($card_id = null)
	{
		$this->sql->setWhere('card_id',$card_id);
		$this->sql->setWhere('user_id',0,'>');
		$card_used_num = count($this->selectKeyLimit());

		return $card_used_num;
	}

	//获取未使用的充值卡数目
	public  function getCardnewnumBy($card_id = null)
	{
		$this->sql->setWhere('card_id',$card_id);
		$this->sql->setWhere('user_id',0);
		$card_new_num = count($this->selectKeyLimit());

		return $card_new_num;
	}

	public  function getCardnumBy($card_id = null)
	{
		$this->sql->setWhere('card_id',$card_id);
		$card_new_num = count($this->selectKeyLimit());

		return $card_new_num;
	}

	//通过card_id删除充值卡
	public function delCardByCid($card_id= null)
	{
        $card_ids_info = $this->getListCardInfoByCardId($card_id);
        $card_del = false;
        if($card_ids_info){
            $card_ids = array();
            foreach ($card_ids_info as $value){
                $card_ids[] = $value['card_code'];
            }
            $card_del = $this->removeInfo($card_ids);
        }
		return $card_del;

	}

	//获取用户的充值卡信息
	public function getUserCard($user_id = null)
	{
		//从card_info获取用户领取的充值卡
		$user_card_info = $this->getByWhere(array('user_id' => $user_id));

		//从card_base中获取所有的充值卡信息
		$Card_BaseModel = new Card_BaseModel();
		$card_base = $Card_BaseModel->getByWhere(array());

		foreach($user_card_info as $key => $val)
		{
			$user_card_info[$key]['card_base'] = $card_base[$val['card_id']];
		}

		return $user_card_info;
	}
}
?>