<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Supplier_HelpCtl extends Api_Controller
{
	public $shopHelpModel = null;


	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->shopHelpModel = new Shop_HelpModel();
	}

	public function helpList()
	{
		$data = $this->shopHelpModel->listByWhere(array('page_show:IN'=>array(3,4)));
		$this->data->addBody(-140, $data);
	}

	public function getHelpRow()
	{
		$shop_help_id = request_int("shop_help_id");
		$data         = $this->shopHelpModel->getOne($shop_help_id);
		$this->data->addBody(-140, $data);
	}

	public function editHelp()
	{
		$shop_help_id        = request_int("shop_help_id");
		$help                = request_row("help");
		$help['update_time'] = get_date_time();
		$flag                = $this->shopHelpModel->editHelp($shop_help_id, $help);
		if ($flag === false)
		{
                    $status = 250;
                    $msg    = __('failure');
			
		}
		else
		{
                    $status = 200;
                    $msg    = __('success');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>