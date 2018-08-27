<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Operation_DeliveryCtl extends Api_Controller
{
	public $deliveryBaseModel = null;

	public function init()
	{
		$this->deliveryBaseModel = new Delivery_BaseModel();
	}

	public function delivery()
	{
		$data['tab'] = request_string("dtyp", "manage");
		$this->data->addBody(-140, $data);
	}

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function getDeliveryList()
	{
		$page               = request_int('page', 1);
		$rows               = request_int('rows', 10);
		$dtyp               = request_int('dtyp', 'manage');
		$user_account       = request_string('user_account');
		$delivery_real_name = request_string('delivery_real_name');
		$delivery_name      = request_string('delivery_name');
		$oname              = request_string('sidx');
		$osort              = request_string('sord');

		$cond_row = array();
		$sort     = array();
		if ($dtyp == "check")
		{
			$cond_row['delivery_check_state'] = Delivery_BaseModel::DELIVERY_PASSIN;
		}
		elseif ($dtyp == "manage")
		{
			$cond_row['delivery_check_state'] = Delivery_BaseModel::DELIVERY_PASS;
		}
		if ($user_account)
		{
			$cond_row['user_account'] = $user_account;
		}
		if ($delivery_real_name)
		{
			$cond_row['delivery_real_name'] = $delivery_real_name;
		}
		if ($delivery_name)
		{
			$cond_row['delivery_name'] = $delivery_name;
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		$data = array();
		$data = $this->deliveryBaseModel->getDeliveryList($cond_row, $sort, $page, $rows);


		$this->data->addBody(-140, $data);
	}

	public function getDelivery()
	{
		$delivery_id = request_int('id');
		$data        = $this->deliveryBaseModel->getDeliveryInfo($delivery_id);
		$this->data->addBody(-140, $data);
	}

	public function editDelivery()
	{

		$data['delivery_mobile']      = request_string('delivery_mobile');
		$data['delivery_tel']         = request_string('delivery_tel');
		$data['delivery_name']        = request_string('delivery_name');
		$data['delivery_address']     = request_string('delivery_address');
		$data['delivery_password']    = request_string('delivery_password');
		$data['delivery_state']       = request_string('delivery_state');
		$data['delivery_check_state'] = request_string('delivery_check_state');
		$delivery_id                  = request_string('delivery_id');

		$type_update_id = $this->deliveryBaseModel->editDelivery($delivery_id, $data);

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

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>