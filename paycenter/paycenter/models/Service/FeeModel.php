<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Service_FeeModel extends Service_Fee
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFeeList($cond_row, $page=1, $rows=100, $sort='asc')
	{
            return $this->listByWhere($cond_row);
	}


	/*
	 * 获取config
	 */
	public function getFeeById($id)
	{
		$this->sql->setWhere('id',$id);
		$data = $this->getFee("*");

		return $data;
	}

	//计算用户提现服务费
    public function getServiceFee($id, $amount)
    {
        $service_fee_config = $this->getOne($id);

        $service_fee = 0;
        $percentage = $amount * ($service_fee_config['fee_rates'] / 100); //手续费

        if ($percentage <= $service_fee_config['fee_min']) {
            $service_fee = $service_fee_config['fee_min'];
        } elseif ($percentage >= $service_fee_config['fee_max']) {
            $service_fee = $service_fee_config['fee_max'];
        } else {
            $service_fee = $percentage;
        }

        return $service_fee;
    }
}
?>