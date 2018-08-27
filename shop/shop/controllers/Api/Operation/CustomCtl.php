<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Operation_CustomCtl extends Api_Controller
{

	public $customtype    = null;
	public $customService = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->customtype    = new Platform_CustomServiceTypeModel();
		$this->customService = new Platform_CustomServiceModel();
	}

	public function getTypeList()
	{

		$page  = request_int('page', 1);
		$rows  = request_int('rows', 10);
		$type  = request_string('user_type');
		$name  = request_string('search_name');
		$oname = request_string('sidx');
		$osort = request_string('sord');

		$cond_row = array();
		$sort     = array();
		if ($name)
		{
			if ($type == 1)
			{
				$type = 'os_id:LIKE';
			}
			else
			{
				$type = 'shop_name:LIKE';
			}
			$cond_row[$type] = '%' . $name . '%';
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		$data = array();
		$data = $this->customtype->getCustomServiceTypeList($cond_row, $sort, $page, $rows);


		$this->data->addBody(-140, $data);
	}

	public function getCustomList()
	{

		$page                    = request_int('page', 1);
		$rows                    = request_int('rows', 10);
		$custom_service_question = request_string('custom_service_question');
		$user_account            = request_string('user_account');
		$oname                   = request_string('sidx');
		$osort                   = request_string('sord');

		$cond_row = array();
		$sort     = array();

		if ($custom_service_question)
		{
			$cond_row['custom_service_question:LIKE'] = "%" . $custom_service_question . "%";
		}
		if ($user_account)
		{
			$cond_row['user_account'] = $user_account;
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		$data = array();
		$data = $this->customService->getCustomServiceList($cond_row, $sort, $page, $rows);


		$this->data->addBody(-140, $data);
	}

	public function delType()
	{
		$type_id = request_int('id');
		$flag    = $this->customtype->removeCustomServiceType($type_id);
		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['custom_service_type_id'] = $type_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function delService()
	{
		$service_id = request_int('id');
		$flag       = $this->customService->removeCustomService($service_id);
		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['custom_service_id'] = $service_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getService()
	{
		$service_id = request_int('id');
		$data       = $this->customService->getOneService($service_id);
		$this->data->addBody(-140, $data);
	}

	public function addType()
	{

		$data['custom_service_type_name'] = request_string('custom_service_type_name');
		$data['custom_service_type_sort'] = request_string('custom_service_type_sort');
		$data['custom_service_type_desc'] = request_string('custom_service_type_desc');

		$type_id = $this->customtype->addCustomServiceType($data, true);

		if ($type_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['custom_service_type_id'] = $type_id;
		$data['id']                     = $type_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editType()
	{

		$data['custom_service_type_name'] = request_string('custom_service_type_name');
		$data['custom_service_type_sort'] = request_string('custom_service_type_sort');
		$data['custom_service_type_desc'] = request_string('custom_service_type_desc');
		$type_id                          = request_string('custom_service_type_id');

		$type_update_id = $this->customtype->editCustomServiceType($type_id, $data);

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

		$data['custom_service_type_id'] = $type_update_id;
		$data['id']                     = $type_update_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function replyService()
	{

		$data['custom_service_answer'] = request_string('custom_service_answer');
		$data['user_id_admin']         = Perm::$userId;
		if (empty($data['custom_service_answer']))
		{
			$data['custom_service_status'] = Platform_CustomServiceModel::SERVICE_UNREPLY;
		}
		else
		{
			$data['custom_service_answer_time'] = date("Y-m-d H:i:s");
			$data['custom_service_status']      = Platform_CustomServiceModel::SERVICE_REPLY;
		}
		$service_id = request_string('custom_service_id');
       
		
		$type_update_id = $this->customService->editCustomService($service_id, $data);

		if ($type_update_id)
		{
			$msg    = __('success');
			$status = 200;
			$service_detail = $this->customService->getOne($service_id);
			//平台客服回复提醒
			$message = new MessageModel();
			$message->sendMessage('Platform customer service reply reminder', $service_detail['user_id'], $service_detail['user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::OTHER_MESSAGE);
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['custom_service_id'] = $type_update_id;
		$data['id']                = $type_update_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>