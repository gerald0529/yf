<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ContractModel extends Shop_Contract
{

	const CONTRACT_INUSE = 1;
	const CONTRACT_UNUSE = 2;
	const CONTRACT_JOIN  = 1;
	const CONTRACT_QUIT  = 2;

	public static $state = array(
		'1' => 'inuse',
		'2' => 'unuse',
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function getContractList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data                  = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$Shop_ContractLogModel = new Shop_ContractLogModel();
		foreach ($data['items'] as $key => $val)
		{
			$log                              = $Shop_ContractLogModel->getOneLog($val['contract_log_id']);
			$data['items'][$key]['log_state'] = $log['contract_log_state_etext'];
			if ($val['contract_state'] == self::CONTRACT_INUSE)
			{
				if ($val['contract_use_state'] == self::CONTRACT_JOIN)
				{
					if ($log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_PASS)
					{
						$data['items'][$key]['contract_state_text'] = __('已加入');
					}
					else
					{
						$data['items'][$key]['contract_state_text'] = __('加入审核中');
					}
				}
				elseif ($val['contract_use_state'] == self::CONTRACT_QUIT)
				{
					if ($log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_PASS)
					{
						$data['items'][$key]['contract_state_text'] = __('未加入');
					}
					else
					{
						$data['items'][$key]['contract_state_text'] = __('退出审核中');
					}
				}
			}
			elseif ($val['contract_state'] == self::CONTRACT_UNUSE)
			{
				$data['items'][$key]['contract_state_text'] = __('永久禁止使用');
			}
		}
		return $data;
	}

	public function getContractDetail($cond_row)
	{
		$data                                    = $this->getByWhere($cond_row);
		$contract_ids                            = array_column($data, 'contract_log_id');
		$contract_data_row['contract_log_id:in'] = $contract_ids;
		$Shop_ContractLogModel                   = new Shop_ContractLogModel();
		$log_data                                = $Shop_ContractLogModel->getByWhere($contract_data_row);
		foreach ($data as $k => $v)
		{
			$data[$k]['contract_state_etext'] = self::$state[$v['contract_use_state']];

			if ($v['contract_state'] == self::CONTRACT_INUSE)
			{
				$log = $log_data[$v['contract_log_id']];
				if ($v['contract_use_state'] == self::CONTRACT_JOIN)
				{

					if ($log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_PASS)
					{
						$data[$k]['contract_state_text'] = __('已加入');
					}
					else
					{
						$data[$k]['contract_state_text'] = __('加入审核中');
					}
				}
				elseif ($v['contract_use_state'] == self::CONTRACT_QUIT)
				{
					if ($log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_PASS)
					{
						$data[$k]['contract_state_text'] = __('未加入');
					}
					else
					{
						$data[$k]['contract_state_text'] = __('退出审核中');
					}
				}
			}
			elseif ($v['contract_state'] == self::CONTRACT_UNUSE)
			{
				$data[$k]['contract_state_text'] = __('永久禁止使用');
			}
		}
		return $data;
	}

	public function getOneContract($contract_id)
	{
		$data                         = $this->getOne($contract_id);
		$Shop_ContractLogModel        = new Shop_ContractLogModel();
		$data['contract_state_etext'] = self::$state[$data['contract_use_state']];
		$log                          = $Shop_ContractLogModel->getOne($data['contract_log_id']);
		if ($data['contract_state'] == self::CONTRACT_INUSE)
		{
            if ($data['contract_use_state'] == self::CONTRACT_JOIN)
			{
                $log_type = __('加入');
            }
            if ($data['contract_use_state'] == self::CONTRACT_QUIT)
			{
                $log_type = __('退出');
            }
            switch ($log['contract_log_state']){
                case Shop_ContractLogModel::LOG_STATE_INCHECK :
                    $data['contract_state_text'] = '('.$log_type.')'.__('待审核');
                break;
                case Shop_ContractLogModel::LOG_STATE_CASH_INCHECK :
                    $data['contract_state_text'] = '('.$log_type.')'.__('保证金待审核');
                break;
                case Shop_ContractLogModel::LOG_STATE_PASS :
                    $data['contract_state_text'] = '('.$log_type.')'.__('审核通过');
                break;
                case Shop_ContractLogModel::LOG_STATE_UNPASS :
                    $data['contract_state_text'] = '('.$log_type.')'.__('审核不通过');
                break;
                case Shop_ContractLogModel::LOG_STATE_CASH_CHECK :
                    $data['contract_state_text'] = '('.$log_type.')'.__('已缴纳保证金');
                break;
            
            }
		}
		elseif ($data['contract_state'] == self::CONTRACT_UNUSE)
		{
			$data['contract_state_text'] = __('永久禁止使用');
		}
		return $data;
	}

	public function editContractCash($contract_id = null, $cash)
	{
		$org_cash              = $this->getOneContract($contract_id);
		$data['contract_cash'] = $org_cash['contract_cash'] + $cash;
		$update_flag           = $this->editContract($contract_id, $data);
		return $update_flag;
	}

}

?>