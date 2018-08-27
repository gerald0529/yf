<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Operation_ContractCtl extends Api_Controller
{

	public $contractTypeModel = null;
	public $contractModel     = null;
	public $contractLogModel  = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->contractTypeModel = new Shop_ContractTypeModel();
		$this->contractModel     = new Shop_ContractModel();
		$this->contractLogModel  = new Shop_ContractLogModel();
	}

	public function log()
	{
		$data['tab'] = request_string("log", "join");
		$this->data->addBody(-140, $data);
	}

	public function getTypeList()
	{
		$page  = request_int('page', 1);
		$rows  = request_int('rows', 10);
		$oname = request_string('sidx');
		$osort = request_string('sord');

		$cond_row = array();
		$sort     = array();

		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		$data = array();
		$data = $this->contractTypeModel->getContractTypeList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);
	}

	public function editType()
	{

		$data['contract_type_name']  = request_string('contract_type_name');
		$data['contract_type_cash']  = request_string('contract_type_cash');
		$data['contract_type_logo']  = request_string('contract_type_logo');
		$data['contract_type_desc']  = request_string('contract_type_desc');
		$data['contract_type_url']   = request_string('contract_type_url');
		$data['contract_type_sort']  = request_string('contract_type_sort');
		$data['contract_type_state'] = request_string('contract_type_state');
		$type_id                     = request_string('contract_type_id');

		$type_update_id = $this->contractTypeModel->editContractType($type_id, $data);

		if ($type_update_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['contract_type_id'] = $type_update_id;
		$data['id']               = $type_update_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getServiceList()
	{
		$page      = request_int('page', 1);
		$rows      = request_int('rows', 10);
		$oname     = request_string('sidx');
		$osort     = request_string('sord');
		$shop_name = request_string("shopName");

		$cond_row = array();
		$sort     = array();

		if ($shop_name)
		{
			$cond_row['shop_name'] = $shop_name;
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$data = array();
		$data = $this->contractModel->getContractList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);
	}

	public function manageShopContract()
	{
		$contract_id = request_int('contract_id');

		$data = array();
		$data = $this->contractModel->getOneContract($contract_id);

		$this->data->addBody(-140, $data);
	}

	public function manageLog()
	{
		$log_id = request_int('log_id');

		$data = array();
		$data = $this->contractLogModel->getOneLog($log_id);

		$this->data->addBody(-140, $data);
	}

	public function editContract()
	{
		$contract_id = request_int('contract_id');

		$data['contract_state'] = request_string('contract_state');

		$constract_update_id = $this->contractModel->editContract($contract_id, $data);

		if ($constract_update_id === false)
		{
			$msg    = __('failure');
			$status = 250;
		}
		else
		{
			$msg    = __('success');
			$status = 200;
		}

		$data['contract_id'] = $constract_update_id;
		$data['id']          = $constract_update_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editLog()
	{
		$contract_log_id    = request_int('contract_log_id');
		$contract_log_type  = request_string('contract_log_type');
		$contract_log_state = request_string('contract_log_state');
		$contract_id        = request_string('contract_id');
		$rs_row             = array();
		$this->contractModel->sql->startTransactionDb();

		if ($contract_log_state == Shop_ContractLogModel::LOG_STATE_PASS)
		{
			if ($contract_log_type == Shop_ContractLogModel::LOG_TYPE_JOIN)
			{
				$data['contract_use_state'] = Shop_ContractModel::CONTRACT_JOIN;
				$edit_flag                  = $this->contractModel->editContract($contract_id, $data);
				check_rs($edit_flag, $rs_row);
				$data3['contract_log_desc'] = __("保证金审核通过，加入服务成功。");
			}
			elseif ($contract_log_type == Shop_ContractLogModel::LOG_TYPE_QUIT)
			{
				$data['contract_use_state'] = Shop_ContractModel::CONTRACT_QUIT;
				$edit_flag                  = $this->contractModel->editContract($contract_id, $data);
				check_rs($edit_flag, $rs_row);
				$data3['contract_log_desc'] = __("审核通过，退出服务成功。");
			}
		}
		elseif ($contract_log_state == Shop_ContractLogModel::LOG_STATE_UNPASS)
		{
			if ($contract_log_type == Shop_ContractLogModel::LOG_TYPE_JOIN)
			{
				$data3['contract_log_desc'] = __("审核失败，加入服务失败。");
			}
			elseif ($contract_log_type == Shop_ContractLogModel::LOG_TYPE_QUIT)
			{
				$data3['contract_log_desc'] = __("审核失败，退出服务失败。");
			}
		}
		elseif ($contract_log_state == Shop_ContractLogModel::LOG_STATE_CASH_INCHECK)
		{
			$data3['contract_log_desc'] = __("审核成功，等待支付保证金。");
        }else{
            return $this->data->addBody(-140, array(), __("审核状态有误！"), 250);
        }

		$data2['contract_log_state'] = $contract_log_state;
		$edit_flag                   = $this->contractLogModel->editContractLog($contract_log_id, $data2);
		check_rs($edit_flag, $rs_row);

		$data3['contract_log_type']     = Shop_ContractLogModel::LOG_TYPE_OTHER;
		$data3['contract_log_operator'] = request_string("admin_account");
		$add_flag                       = $this->contractLogModel->addLog($data3, $contract_id);
		check_rs($add_flag, $rs_row);

		$flag = is_ok($rs_row);
		if ($flag && $this->contractModel->sql->commitDb())
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$this->contractModel->sql->rollBackDb();
			$msg    = __('failure');
			$status = 250;
		}

		$data['contract_id'] = $contract_log_id;
		$data['id']          = $contract_log_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getDetail()
	{
		$contract_id = request_int('id');

		$data = array();
		$data = $this->contractModel->getOneContract($contract_id);

		$data['cash'] = request_int("cash");

		$this->data->addBody(-140, $data);
	}

	public function manageContractCash()
	{
		$contract_id = request_int('contract_id');

		$data = array();
		$data = $this->contractModel->getOneContract($contract_id);

		$this->data->addBody(-140, $data);
	}

	public function getLogList()
	{
		$page        = request_int('page', 1);
		$rows        = request_int('rows', 10);
		$oname       = request_string('sidx');
		$osort       = request_string('sord');
		$logtype     = request_int('logtype', 2);
		$contract_id = request_int('id');

		$cond_row = array();
		$sort     = array();

		$cond_row['contract_id'] = $contract_id;
		if ($logtype == 1)
		{
			$cond_row['contract_log_type'] = 1;
		}
		else
		{
			$cond_row['contract_log_type:>'] = 1;
		}

		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$sort['contract_log_date'] = "DESC";
		$data                      = array();
		$data                      = $this->contractLogModel->getContractLogList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);
	}

	public function editCash()
	{
		$contract_id                    = request_int('contract_id');
		$type                           = request_string('type');
		$cash                           = request_float("cash");
		$data2['contract_log_type']     = Shop_ContractLogModel::LOG_TYPE_CASH;
		$data2['contract_log_desc']     = request_string("contract_log_desc");
		$data2['contract_log_operator'] = request_string("admin_account");

		$data3['contract_log_type']     = Shop_ContractLogModel::LOG_TYPE_OTHER;
		$data3['contract_log_operator'] = request_string("admin_account");
		$rs_row                         = array();
		$this->contractModel->sql->startTransactionDb();

		if ($type == "increase")
		{
			$edit_flag = $this->contractModel->editContractCash($contract_id, $cash);
			check_rs($edit_flag, $rs_row);
			$data2['contract_cash'] = $cash;
			$add_flag               = $this->contractLogModel->addLog($data2, $contract_id);
			check_rs($add_flag, $rs_row);
			$data3['contract_log_desc'] = sprintf(__("增加了保证金%s元"), $cash);
			$add_flag                   = $this->contractLogModel->addLog($data3, $contract_id);
			check_rs($add_flag, $rs_row);
		}
		elseif ($type == "decrease")
		{
			$cash      = $cash * (-1);
			$edit_flag = $this->contractModel->editContractCash($contract_id, $cash);
			check_rs($edit_flag, $rs_row);
			$data2['contract_cash'] = $cash;
			$add_flag               = $this->contractLogModel->addLog($data2, $contract_id);
			check_rs($add_flag, $rs_row);
			$data3['contract_log_desc'] = sprintf(__("减少了保证金%s元"), $cash * (-1));
			$add_flag                   = $this->contractLogModel->addLog($data3, $contract_id);
			check_rs($add_flag, $rs_row);
		}

		$flag = is_ok($rs_row);
		if ($flag && $this->contractModel->sql->commitDb())
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$this->contractModel->sql->rollBackDb();
			$msg    = __('failure');
			$status = 250;
		}

		$data['contract_id'] = $contract_id;
		$data['id']          = $contract_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getContractStateList()
	{
		$page          = request_int('page', 1);
		$rows          = request_int('rows', 10);
		$oname         = request_string('sidx');
		$osort         = request_string('sord');
		$contract_type = request_string("type", "join");
		$shop_name     = request_string("shopName");

		$cond_row = array();
		$sort     = array();

		if ($shop_name)
		{
			$cond_row['shop_name'] = $shop_name;
		}

		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$sort['contract_log_date'] = "DESC";
		if ($contract_type == "join")
		{
			$cond_row['contract_log_type'] = Shop_ContractLogModel::LOG_TYPE_JOIN;
		}
		elseif ($contract_type == "quit")
		{
			$cond_row['contract_log_type'] = Shop_ContractLogModel::LOG_TYPE_QUIT;
		}
		$data = array();
		$data = $this->contractLogModel->getContractLogList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);
	}

}

?>