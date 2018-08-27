<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Consume_WithdrawModel extends Consume_Withdraw
{
	const WITHDRAW_STATUS = 3; //通过
	public static $status = array(
		"0" => '待审核',
		"1" => '进行中',
		"2" => '打款中',
		"3" => '通过',
		"4" => '不通过',
	);
	/**
	 * 读取分页列表
	 *
	 * @param  int $order_no 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	 public function getWithdrawList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["is_succeed"] = _(Consume_WithdrawModel::$status[$value["is_succeed"]]);
			if($value['check_time'])
			{
				$data["items"][$key]["check_time_date"] = date('Y-m-d H:i:s', $value['check_time']);
			}
			else
			{
				$data["items"][$key]["check_time_date"] = '';
			}

		}
		return $data;
	}

	public function getWithdrawByOid($order_id = null)
	{
		$this->sql->setWhere('orderid',$order_id);
		$data = $this->getWithdraw("*");

		return $data;
	}

	public  function getWithdrawListByPid($pay_uid = null ,$page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$id_row = array();

		if($pay_uid)
		{
			$this->sql->setWhere('pay_uid',$pay_uid,'IN');
		}

		$id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($id_row)
		{
			$data_rows = $this->getWithdraw($id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
        
        
       public function  getWithdrawListall($cond_row = array(), $array=array(), $page, $rows=100, $sort='asc')
        {
            $getWithdrawListall = $this->listByWhere($cond_row , $array, $page, $rows);
            $User_baseModel = new User_baseModel();
            foreach ($getWithdrawListall['items'] as $key => $value) {
                    $user_base_list = $User_baseModel->getone($value['pay_uid']);
                    $getWithdrawListall['items'][$key] = array_merge($getWithdrawListall['items'][$key], $user_base_list);
                    $getWithdrawListall['items'][$key]['status'] = _(self::$status[$value["is_succeed"]]);
            }
           
            return $getWithdrawListall;
        }
}
?>