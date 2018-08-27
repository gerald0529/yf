<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ContractLogModel extends Shop_ContractLog
{

	const LOG_TYPE_CASH  = 1;
	const LOG_TYPE_JOIN  = 2;
	const LOG_TYPE_QUIT  = 3;
	const LOG_TYPE_OTHER = 4;

	const LOG_STATE_INCHECK      = 1;
	const LOG_STATE_CASH_INCHECK = 2;
	const LOG_STATE_PASS         = 3;
	const LOG_STATE_UNPASS       = 4;
	const LOG_STATE_CASH_CHECK   = 5;

	public static $state = array(
		'1' => 'incheck',
		'2' => 'cash_incheck',
		'3' => 'pass',
		'4' => 'unpass',
		'5' => 'cash_check',
	);

	public static $log_type = array(
		'1' => 'cash',
		'2' => 'join',
		'3' => 'quit',
		'4' => 'other',
	);

	public $state_text;
	public $contractModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->state_text    = array(
			'1' => __('审核中'),
			'2' => __('保证金审核中'),
			'3' => __('审核通过'),
			'4' => __('审核失败'),
			'5' => __('等待审核保证金')
		);
		$this->contractModel = new Shop_ContractModel();
	}

	public function getContractLogList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($data['items'] as $key => $val)
		{
			$data['items'][$key]['contract_log_state_text']  = $this->state_text[$val['contract_log_state']];
			$data['items'][$key]['contract_log_state_etext'] = self::$state[$val['contract_log_state']];
		}

		return $data;
	}

	public function getOneLog($log_id)
	{
		$data                             = $this->getOne($log_id);
		$data['contract_log_state_etext'] = self::$state[$data['contract_log_state']];
		$data['contract_log_state_text']  = $this->state_text[$data['contract_log_state']];
		$data['contract_log_type_etext']  = self::$log_type[$data['contract_log_type']];
		return $data;
	}

	public function getLog($cond_row)
	{
		$data = $this->getByWhere($cond_row);
		foreach ($data as $k => $v)
		{
			$data[$k]['contract_log_state_etext'] = self::$state[$v['contract_log_state']];
			$data[$k]['contract_log_state_text']  = $this->state_text[$v['contract_log_state']];
			$data[$k]['contract_log_type_etext']  = self::$log_type[$v['contract_log_type']];
		}
		return $data;
	}

	public function addLog($data, $contract_id, $shop_id, $shop_name, $type_id, $type_name)
	{

		$data['contract_id']        = $contract_id;
		$data['contract_type_id']   = $type_id;
		$data['contract_type_name'] = $type_name;
		$data['shop_id']            = $shop_id;
		$data['shop_name']          = $shop_name;
		$data['contract_log_date']  = date("Y-m-d H:i:s");


		$log_id = $this->addContractLog($data, true);
		return $log_id;
	}

}

?>