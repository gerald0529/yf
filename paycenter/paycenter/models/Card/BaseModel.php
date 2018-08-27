<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Card_BaseModel extends Card_Base
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($card_name = null,$appid = null,$beginDate = null,$endDate = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$card_id_row = array();
		if($card_name)
		{
			$this->sql->setWhere('card_name','%'.$card_name.'%','LIKE');
		}
		if($appid)
		{
			$this->sql->setWhere('app_id',$appid);
		}
		if($beginDate)
		{
			$this->sql->setWhere('card_start_time',$beginDate,'>=');
		}
		if($endDate)
		{
			$this->sql->setWhere('card_end_time',$endDate,'<=');
		}
		$card_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($card_id_row)
		{
			$data_rows = $this->getBase($card_id_row);
		}
		$str = '';
		foreach($data_rows as $key=>$val)
		{
			$card_prize = json_decode($val['card_prize'], true);
			$data_rows[$key]['money'] = isset($card_prize['m'])?$card_prize['m']:0;
			$data_rows[$key]['point'] = isset($card_prize['p'])?$card_prize['p']:0;
			$data_rows[$key]['image'] = $val['card_image'];
			$str = '';
			foreach($card_prize as $k => $v)
			{
				if($k == 'm')
				{
					$str .='金额:'.$v.'; ';
				}
				if($k == 'p')
				{
					$str .='积分:'.$v . '; ';
				}
			}
			$data_rows[$key]['card_cprize'] = $str;
			if($val['app_id'] == 9999)
			{
				$data_rows[$key]['app'] = '通用';
			}
			if($val['app_id'] == 101)
			{
				$data_rows[$key]['app'] = 'MallBuilder';
			}
			if($val['app_id'] == 102)
			{
				$data_rows[$key]['app'] = 'ShopBuilder';
			}
			if($val['app_id'] == 103)
			{
				$data_rows[$key]['app'] = 'ImBuilder';
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
//	/*********************************************查询开始***************************************************************


	public function getBaseById($id = null)
	{
		$data = $this->getBase($id);

		$data = current($data);
		$data['prize'] = json_decode($data['card_prize'], true);
		return $data;
	}
	/**
	 * 删除操作
	 * @param int $user_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeBase($card_id)
	{
		$del_flag = $this->remove($card_id);
		return $del_flag;
	}



}
?>