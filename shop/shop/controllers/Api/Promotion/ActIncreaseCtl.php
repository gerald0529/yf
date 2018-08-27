<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/18
 * Time: 16:18
 */
class Api_Promotion_ActIncreaseCtl extends Api_Controller
{
	public $Increase_BaseModel  = null;
	public $Increase_ComboModel = null;

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
		$this->Increase_BaseModel  = new Increase_BaseModel();
		$this->Increase_ComboModel = new Increase_ComboModel();
	}

	//加价购活动列表
	public function getIncreaseList()
	{
		$cond_row      = array();
		$page          = request_int('page', 1);
		$rows          = request_int('rows', 100);
		$increase_name = request_string('increase_name');
		$shop_name     = request_string('shop_name');
		$increase_state= request_int('increase_state');

		if ($increase_state)
		{
			$cond_row['increase_state'] = $increase_state;
		}
		if ($increase_name)
		{
			$cond_row['increase_name:LIKE'] = $increase_name . '%';
		}
		if ($shop_name)
		{
			$cond_row['shop_name:LIKE'] = $shop_name . '%';
		}

		$data = $this->Increase_BaseModel->getIncreaseActList($cond_row, array('increase_id' => 'DESC'), $page, $rows);

		$this->data->addBody(-140, $data);
	}

	public function getIncreaseInfo()
	{
		$increase_id = request_int('id');
		$data        = $this->Increase_BaseModel->getIncreaseActDetail($increase_id);
		$this->data->addBody(-140, $data);
	}

	public function removeIncrease()
	{
		$increase_id = request_int('increase_id');

		$this->Increase_BaseModel->sql->startTransactionDb();

		$flag = $this->Increase_BaseModel->removeIncreaseActItem($increase_id);

		if ($flag && $this->Increase_BaseModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$this->Increase_BaseModel->sql->rollBackDb();
			$msg    = 'failure';
			$status = 250;
		}
		$data['increase_id'] = $increase_id;
		$data                = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getIncreaseComboList()
	{
		$cond_row  = array();
		$page      = request_int('page', 1);
		$rows      = request_int('rows', 100);
		$shop_name = request_string('shop_name');

		if ($shop_name)
		{
			$cond_row['shop_name:LIKE'] = $shop_name . '%';
		}

		$data = $this->Increase_ComboModel->getIncreaseComboList($cond_row, array('combo_id' => 'DESC'), $page, $rows);
		$this->data->addBody(-140, $data);
	}

}