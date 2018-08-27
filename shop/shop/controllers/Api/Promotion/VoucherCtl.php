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
class Api_Promotion_VoucherCtl extends Api_Controller
{
	public $Voucher_TempModel  = null;
	public $Voucher_quotaModel = null;
	public $Voucher_PriceModel = null;

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

		$this->Voucher_TempModel  = new Voucher_TempModel();
		$this->Voucher_quotaModel = new Voucher_quotaModel();
		$this->Voucher_PriceModel = new Voucher_PriceModel();
	}

	/*代金券列表*/
	public function getVoucherTempList()
	{
		$page                      = request_int('page', 1);
		$rows                      = request_int('rows', 100);
		$voucher_t_title           = request_string('voucher_t_title');
		$voucher_t_shop_name       = request_string('voucher_t_shop_name');
		$voucher_t_state		   = request_int('voucher_t_state');
		$cond_row                  = array();
		$order_row                 = array();
		$order_row['voucher_t_id'] = 'DESC';

		if ($voucher_t_state)
		{
			$cond_row['voucher_t_state'] = $voucher_t_state;
		}
		if ($voucher_t_title)
		{
			$cond_row['voucher_t_title:LIKE'] = $voucher_t_title . '%';
		}
		if ($voucher_t_shop_name)
		{
			$cond_row['voucher_t_shop_name:LIKE'] = $voucher_t_shop_name . '%';
		}

		$data = $this->Voucher_TempModel->getVoucherTempList($cond_row, $order_row, $page, $rows);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function enable()
	{
		$data['voucher_t_recommend'] = request_string('is_rec'); // 其是启用

		$voucher_t_id = request_int('voucher_t_id');
		$data_rs      = $data;

		$flag = $this->Voucher_TempModel->editVoucherTemp($voucher_t_id, $data);

		$data_rs['id'] = array($voucher_t_id);

		$this->data->addBody(-140, $data_rs);
	}

	/* 代金券详情*/
	public function getVoucherTempInfo()
	{
		$voucher_t_id             = request_int('id');
		$data = $this->Voucher_TempModel->getVoucherTempInfoById($voucher_t_id);

		$this->data->addBody(-140, $data);
	}

	public function checkVoucherPrice()
	{
		$voucher_price             = request_int('voucher_price');
		$cond_row['voucher_price'] = $voucher_price;
		$row                       = $this->Voucher_PriceModel->getOneVoucherPriceByWhere($cond_row);
		
		$data = array();

		if ($row)
		{
			$status = 250;
			$msg    = __('failure');
			$flag   = false;
		}
		else
		{
			$status = 200;
			$msg    = __('success');
			$flag   = true;
		}

		$this->data->addBody(-140, $data, $msg, $flag, $status);
		
	}

	/*修改代金券状态*/
	public function editVoucherTempInfo()
	{
		$voucher_t_id                = request_int('voucher_t_id');
		$data['voucher_t_state']     = request_int('voucher_t_state');
		$data['voucher_t_recommend'] = request_int('voucher_t_recommend');

        $cond_row['voucher_t_id'] = $voucher_t_id;
        $cond_row['voucher_t_end_date:>'] = get_date_time();
        $voucher_temp_row = $this->Voucher_TempModel->getVoucherTempInfoByWhere($cond_row);

        if($voucher_temp_row)
        {
            $flag  = $this->Voucher_TempModel->editVoucherTemp($voucher_t_id, $data);
        }
        else
        {
            $flag = false;
            $msg_label = __('代金券模板不存在或已失效！');
        }

		$data_re				  = $data;
		$data_re['voucher_t_id']  = $voucher_t_id;
		$data_re['id']  		  = $voucher_t_id;

		if ($flag === false)
		{
			$status = 250;
			$msg    = $msg_label?$msg_label:__('操作失败！');
		}
		else
		{
			$data_re['voucher_t_state_label'] 		= Voucher_TempModel::$voucher_state_map[$data['voucher_t_state']];
			$data_re['voucher_t_recommend_label'] 	= Voucher_TempModel::$voucher_recommend_map[$data['voucher_t_recommend']];

			$status = 200;
			$msg    = __('操作成功！');
		}


		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	/*代金券套餐列表*/
	public function getQuotaList()
	{
		$page         = request_int('page', 1);
		$rows         = request_int('rows', 100);
		$search_field = trim(request_string('skey'));
		$cond_row     = array();

		if ($search_field)
		{
			$cond_row['shop_name:LIKE'] = $search_field . '%';
		}


		$data = $this->Voucher_quotaModel->getVoucherQuotaList($cond_row, array('combo_id' => 'DESC'), $page, $rows);
		$this->data->addBody(-140, $data);
	}

	/*代金券面额列表*/
	public function getPriceList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$data = $this->Voucher_PriceModel->getVoucherPriceList($cond_row = array(), array('voucher_price' => 'ASC'), $page, $rows);
		$this->data->addBody(-140, $data);
	}

	/* 删除代金券面额*/
	public function priceRemove()
	{
		$voucher_price_id = request_int('voucher_price_id');

		$flag = $this->Voucher_PriceModel->removeVoucherPrice($voucher_price_id);

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
		$data['voucher_price_id'] = $voucher_price_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/* 添加代金券面额*/
	public function addVoucherPrice()
	{
		$data["voucher_price"]          = request_int("voucher_price");
		$data["voucher_price_describe"] = request_string("voucher_price_describe");
		$data["voucher_defaultpoints"]  = request_int("voucher_defaultpoints");
		if ($data)
		{
			$voucher_price_id = $this->Voucher_PriceModel->addVoucherPrice($data);
		}
		if ($voucher_price_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['voucher_price_id'] = $voucher_price_id;
		$data['id']               = $voucher_price_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/* 修改代金券面额*/
	public function editVoucherPrice()
	{
		$data["voucher_price_id"]       = request_int("voucher_price_id");
		$data["voucher_price"]          = request_int("voucher_price");
		$data["voucher_price_describe"] = request_string("voucher_price_describe");
		$data["voucher_defaultpoints"]  = request_int("voucher_defaultpoints");

		$voucher_price_id = request_int("voucher_price_id");
		$data_rs          = $data;

		unset($data['voucher_price_id']);

		$data = $this->Voucher_PriceModel->editVoucherPrice($voucher_price_id, $data);

		$this->data->addBody(-140, $data_rs);
	}
}

?>