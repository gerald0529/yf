<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_ContractCtl extends Seller_Controller
{
	public $shopContractModel     = null;
	public $shopContractTypeModel = null;
	public $shopContractLogModel  = null;
	public $shopBaseModel         = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->shopContractModel     = new Shop_ContractModel();
		$this->shopContractTypeModel = new Shop_ContractTypeModel();
		$this->shopContractLogModel  = new Shop_ContractLogModel();
		$this->shopBaseModel         = new Shop_BaseModel();
	}


	public function index()
	{
		$act             = request_string('act');
		$Web_ConfigModel = new Web_ConfigModel();
		$is_open         = $Web_ConfigModel->value('protection_service_status');
		if ($is_open)
		{
			if ($act == "detail")
			{
				$data = $this->detail();
				$this->view->setMet('detail');
			}
			else
			{
				$cond_row['contract_type_state']     = Shop_ContractTypeModel::CONTRACT_OPEN;
				$data                                = $this->shopContractTypeModel->getByWhere($cond_row);
				$contract_type_ids                   = array_column($data, 'contract_type_id');
				$contract_row['shop_id']             = Perm::$shopId;
				$contract_row['contract_type_id:in'] = $contract_type_ids;
				$contract                            = $this->shopContractModel->getByWhere($contract_row);

				foreach ($contract as $v)
				{
					$contract[$v['contract_type_id']] = $v;
				}
				$contract_ids  = array_column($contract, 'contract_id');
				$contract_data = array();
				if (!empty($contract_ids))
				{
					$contract_data_row['contract_id:in'] = $contract_ids;
					$contract_data                       = $this->shopContractModel->getContractDetail($contract_data_row);
				}

				foreach ($contract_data as $v)
				{
					$contract[$v['contract_type_id']]['data'] = $v;
				}
				$contract_log_ids = array_column($contract_data, 'contract_log_id');
				$log              = array();
				if (!empty($contract_log_ids))
				{
					$contract_log_row['contract_log_id:in'] = $contract_log_ids;
					$log                                    = $this->shopContractLogModel->getLog($contract_log_row);
				}

				foreach ($log as $v)
				{
					$log[$v['contract_id']] = $v;
				}
				foreach ($contract as $v)
				{
					$contract[$v['contract_type_id']]['log'] = $log[$v['contract_id']];
				}
				foreach ($data as $k => $v)
				{
					$contract_row['shop_id']          = Perm::$shopId;
					$contract_row['contract_type_id'] = $v['contract_type_id'];

					if (!empty($contract[$v['contract_type_id']]))
					{
						$data[$k]['state']     = $contract[$v['contract_type_id']]['data']['contract_state_etext'];
						$data[$k]['log_state'] = $contract[$v['contract_type_id']]['log']['contract_log_state_etext'];
					}
					else
					{
						$data[$k]['state'] = 'unuse';
					}

				}
			}
		}
		else
		{
			$this->view->setMet('error');
		}
		include $this->view->getView();
	}


	public function detail()
	{
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row['contract_type_id'] = request_int("id");
		$cond_row['shop_id']          = Perm::$shopId;
		$data['contract_type']        = $this->shopContractTypeModel->getOne($cond_row['contract_type_id']);
		$contract                     = $this->shopContractModel->getOneByWhere($cond_row);
		$data['contract']             = $this->shopContractModel->getOneContract($contract['contract_id']);
		$data['log']                  = $this->shopContractLogModel->getContractLogList($cond_row, array('contract_log_date' => 'DESC'), $page, $rows);
		$Yf_Page->totalRows           = $data['log']['totalsize'];
		$data['page']                 = $Yf_Page->prompt();
		return $data;
	}


	public function joinContract()
	{
		$shop             = $this->shopBaseModel->getOne(Perm::$shopId);
		$contract_type_id = request_int("contract_type_id");

		$data                         = $this->shopContractTypeModel->getOne($contract_type_id);
		$cond_row['shop_id']          = Perm::$shopId;
		$cond_row['contract_type_id'] = $contract_type_id;
		$contract                     = $this->shopContractModel->getOneByWhere($cond_row);

		$this->shopContractModel->sql->startTransactionDb();
		$rs_row = array();
		if (empty($contract))
		{
			$field_row['contract_type_id']   = $contract_type_id;
			$field_row['contract_type_name'] = $data['contract_type_name'];
			$field_row['shop_id']            = Perm::$shopId;
			$field_row['shop_name']          = $shop['shop_name'];
			$field_row['contract_use_state'] = Shop_ContractModel::CONTRACT_JOIN;
			$contract_id                     = $this->shopContractModel->addContract($field_row, true);

			check_rs($contract_id, $rs_row);
		}
		else
		{
			$contract_id                     = $contract['contract_id'];
			$field_row['contract_use_state'] = Shop_ContractModel::CONTRACT_JOIN;
			$flag                            = $this->shopContractModel->editContract($contract_id, $field_row);
			check_rs($flag, $rs_row);
		}
		$log_row['contract_log_operator'] = sprintf("%s(商家)", $shop['shop_name']);
		$log_row['contract_log_desc']     = sprintf("%s加入保障者服务", $shop['shop_name']);
		$log_row['contract_log_type']     = Shop_ContractLogModel::LOG_TYPE_JOIN;
		fb(22222);
		fb($contract_id);
		$log_id = $this->shopContractLogModel->addLog($log_row, $contract_id, Perm::$shopId, $shop['shop_name'], $contract_type_id, $data['contract_type_name']);
		fb(33333);
		check_rs($log_id, $rs_row);


		$clog_row['contract_log_id'] = $log_id;
		$flag                        = $this->shopContractModel->editContract($contract_id, $clog_row);
		check_rs($flag, $rs_row);

		$flag = is_ok($rs_row);
		if ($flag && $this->shopContractModel->sql->commitDb())
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$this->shopContractModel->sql->rollBackDb();
			$msg    = __('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function quitContract()
	{
		$shop                         = $this->shopBaseModel->getOne(Perm::$shopId);
		$contract_type_id             = request_int("contract_type_id");
		$cond_row['shop_id']          = Perm::$shopId;
		$cond_row['contract_type_id'] = $contract_type_id;
		$contract                     = $this->shopContractModel->getOneByWhere($cond_row);
		$this->shopContractModel->sql->startTransactionDb();
		$rs_row                          = array();
		$contract_id                     = $contract['contract_id'];
		$field_row['contract_use_state'] = Shop_ContractModel::CONTRACT_QUIT;
		$flag                            = $this->shopContractModel->editContract($contract_id, $field_row);
		check_rs($flag, $rs_row);

		$log_row['contract_log_operator'] = sprintf("%s(商家)", $shop['shop_name']);
		$log_row['contract_log_desc']     = sprintf("%s退出保障者服务", $shop['shop_name']);
		$log_row['contract_log_type']     = Shop_ContractLogModel::LOG_TYPE_QUIT;
		$log_id                           = $this->shopContractLogModel->addLog($log_row, $contract_id, Perm::$shopId, $shop['shop_name'], $contract_type_id, $contract['contract_type_name']);
		check_rs($log_id, $rs_row);

		$clog_row['contract_log_id'] = $log_id;
		$flag                        = $this->shopContractModel->editContract($contract_id, $clog_row);
		check_rs($log_id, $rs_row);

		$flag = is_ok($rs_row);
		if ($flag && $this->shopContractModel->sql->commitDb())
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$this->shopContractModel->sql->rollBackDb();
			$msg    = __('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>