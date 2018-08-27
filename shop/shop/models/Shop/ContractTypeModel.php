<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ContractTypeModel extends Shop_ContractType
{

	const CONTRACT_OPEN  = 1;
	const CONTRACT_CLOSE = 2;

	public static $state = array(
		'1' => 'open',
		'2' => 'close',
	);
	public        $contract_state;

	public function __construct()
	{
		parent::__construct();
		$this->contract_state = array(
			'1' => __("开启"),
			'2' => __("关闭"),
		);
	}

	public function getContractTypeList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data['items'] as $key => $val)
		{
			$data['items'][$key]['contract_type_state_text'] = $this->contract_state[$val['contract_type_state']];
		}
		
		return $data;
	}


	public function getOneType($contract_type_id)
	{
		$data                              = $this->getOne($contract_type_id);
		$data['$contract_type_state_text'] = $this->settle_state[$data['$contract_type_state']];
		return $data;
	}
}

?>