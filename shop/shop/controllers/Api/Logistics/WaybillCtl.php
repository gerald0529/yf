<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Logistics_WaybillCtl extends Api_Controller
{
	public $logisticsWaybillModel = null;
	public $logisticsExpressModel = null;

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

		$this->logisticsWaybillModel = new Waybill_TplModel();
		$this->logisticsExpressModel = new ExpressModel();
	}

	/**
	 * 获取运单模板列表
	 *
	 * @access public
	 */

	public function getWaybillTplList()
	{
		$page             = request_int('page', 1);
		$rows             = request_int('rows', 10);
		$waybill_tpl_name = request_string('waybill_tpl_name');
		
		$cond_row = array();
		$sort     = array();
		
		if ($waybill_tpl_name)
		{

			$cond_row['waybill_tpl_name:LIKE'] = '%' . $waybill_tpl_name . '%';

		}
		$data = $this->logisticsWaybillModel->getTplList($cond_row, $sort, $page, $rows);
		
		foreach ($data['items'] as $key => $val)
		{
			$order_row['express_id'] = $val['express_id'];
			
			$name = $this->logisticsExpressModel->getExpressName($order_row);
			
		}
		
		$this->data->addBody(-140, $data);
	}

	/**
	 * 增加运单模板页面
	 *
	 * @access public
	 */
	public function addWaybillTpl()
	{

		$data = array();
		
		$data = $this->logisticsExpressModel->getExpressList();
		
		$this->data->addBody(-140, $data);
	}

	/**
	 * 增加运单模板
	 *
	 * @access public
	 */
	public function addWaybillTplDetail()
	{
		$waybill_tpl_name   = request_string('waybill_tpl_name');
		$express_id         = request_int('express_id');
		$waybill_tpl_width  = request_int('waybill_tpl_width');
		$waybill_tpl_height = request_int('waybill_tpl_height');
		$waybill_tpl_top    = request_int('waybill_tpl_top');
		$waybill_tpl_left   = request_int('waybill_tpl_left');
		$waybill_tpl_image  = request_string('waybill_tpl_image');
		$waybill_tpl_enable = request_int('waybill_tpl_enable');
		
		$add_tpl_row['waybill_tpl_name']   = $waybill_tpl_name;
		$add_tpl_row['express_id']         = $express_id;
		$add_tpl_row['waybill_tpl_width']  = $waybill_tpl_width;
		$add_tpl_row['waybill_tpl_height'] = $waybill_tpl_height;
		$add_tpl_row['waybill_tpl_top']    = $waybill_tpl_top;
		$add_tpl_row['waybill_tpl_left']   = $waybill_tpl_left;
		$add_tpl_row['waybill_tpl_image']  = $waybill_tpl_image;
		$add_tpl_row['waybill_tpl_enable'] = $waybill_tpl_enable;

		$flag = $this->logisticsWaybillModel->addTpl($add_tpl_row);
		
		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			
			$msg    = __('failure');
			$status = 250;
		}
		
		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 编辑运单模板页面
	 *
	 * @access public
	 */
	public function editWaybillTpl()
	{
		$waybill_tpl_id             = request_int('id');
		$cond_row['waybill_tpl_id'] = $waybill_tpl_id;

		$data = $this->logisticsWaybillModel->getTplDetail($cond_row);
		
		foreach ($data as $key => $val)
		{
			$order_row['express_id'] = $val['express_id'];
			
			$name = $this->logisticsExpressModel->getExpressName($order_row);
			
			if ($name)
			{
				$data['express_name'] = $name['express_name'];
			}
			else
			{
				unset($val);
			}
		}
		$data['express'] = $this->logisticsExpressModel->getExpressList();

		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改运单模板
	 *
	 * @access public
	 */
	public function editWaybillTplDetail()
	{
		$waybill_tpl_id     = request_string('waybill_tpl_id');
		$waybill_tpl_name   = request_string('waybill_tpl_name');
		$express_id         = request_int('express_id');
		$waybill_tpl_width  = request_int('waybill_tpl_width');
		$waybill_tpl_height = request_int('waybill_tpl_height');
		$waybill_tpl_top    = request_int('waybill_tpl_top');
		$waybill_tpl_left   = request_int('waybill_tpl_left');
		$waybill_tpl_image  = request_string('waybill_tpl_image');
		$waybill_tpl_enable = request_int('waybill_tpl_enable');
		
		$edit_tpl_row['waybill_tpl_id']     = $waybill_tpl_id;
		$edit_tpl_row['waybill_tpl_name']   = $waybill_tpl_name;
		$edit_tpl_row['express_id']         = $express_id;
		$edit_tpl_row['waybill_tpl_width']  = $waybill_tpl_width;
		$edit_tpl_row['waybill_tpl_height'] = $waybill_tpl_height;
		$edit_tpl_row['waybill_tpl_top']    = $waybill_tpl_top;
		$edit_tpl_row['waybill_tpl_left']   = $waybill_tpl_left;
		$edit_tpl_row['waybill_tpl_image']  = $waybill_tpl_image;
		$edit_tpl_row['waybill_tpl_enable'] = $waybill_tpl_enable;

		$flag = $this->logisticsWaybillModel->editTpl($waybill_tpl_id, $edit_tpl_row);

		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除运单模板页面
	 *
	 * @access public
	 */
	public function delWaybillTpl()
	{
		$waybill_tpl_id = request_int('id');

		$flag = $this->logisticsWaybillModel->removeTpl($waybill_tpl_id);
		
		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		
		$data = array();
		$this->data->addBody(-140, $data);
	}

}

?>