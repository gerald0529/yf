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
class Api_Logistics_ExpressCtl extends Api_Controller
{
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

		//include $this->view->getView();
		$this->logisticsExpressModel = new ExpressModel();
	}

	/**
	 * 获取快递公司列表
	 *
	 * @access public
	 */

	public function getExpressList()
	{
		$page = request_string('page');
		$rows = request_string('rows');
		
		$cond_row  = array();
		$order_row = array('express_commonorder'=>'DESC');
		
		$data = $this->logisticsExpressModel->getExpressList($cond_row, $order_row, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 获取修改物流公司
	 *
	 * @access public
	 */
	public function manageExpress()
	{
		$express_id = request_int('express_id');

		$data = array();
		$data = $this->logisticsExpressModel->getOneExpress($express_id);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改物流公司
	 *
	 * @access public
	 */
	public function editExpress()
	{
		$express_id                    = request_int('express_id');
		$field['express_status']       = request_int('express_status');
		$field['express_commonorder']  = request_int('express_commonorder');
		$field['express_displayorder'] = request_int('express_displayorder');

		$flag = $this->logisticsExpressModel->editExpress($express_id, $field);

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


}

?>