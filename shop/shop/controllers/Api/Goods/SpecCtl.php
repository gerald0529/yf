<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_SpecCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->goodsSpecModel = new Goods_SpecModel();
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function lists()
	{
		$Goods_SpecModel = new Goods_SpecModel();
		$data            = $Goods_SpecModel->getSpecList();


		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['spec_name']         = request_string('spec_name'); // 规格名称
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['spec_format']       = request_string('spec_format'); // 显示类型
		$data['spec_item']         = request_string('spec_item'); // 规格值列
		$data['spec_displayorder'] = request_string('spec_displayorder'); // 排序


		$spec_id = $this->goodsSpecModel->addSpec($data, true);

		if ($spec_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['spec_id'] = $spec_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$spec_id = request_int('spec_id');

		$flag = $this->goodsSpecModel->removeSpec($spec_id);

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

		$data['spec_id'] = array($spec_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['spec_name']         = request_string('spec_name'); // 规格名称
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['spec_format']       = request_string('spec_format'); // 显示类型
		$data['spec_item']         = request_string('spec_item'); // 规格值列
		$data['spec_displayorder'] = request_string('spec_displayorder'); // 排序

		$spec_id = request_int('spec_id');
		$data_rs = $data;

		unset($data['spec_id']);

		$flag = $this->goodsSpecModel->editSpec($spec_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function removeSpec1()
	{
		$Goods_SpecModel = new Goods_SpecModel();

		$cat_id     = request_string('cat_id');
		$cat_id_row = explode(',', $cat_id);

		if ($cat_id_row)
		{
			$Goods_SpecModel->sql->startTransactionDb();

			$flag = $Goods_SpecModel->removeSpec($cat_id_row);

			if ($flag && $Goods_SpecModel->sql->commitDb())
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$Goods_SpecModel->sql->rollBackDb();
				$m      = $Goods_SpecModel->msg->getMessages();
				$msg    = $m ? $m[0] : __('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array('id' => $cat_id_row), $msg, $status);
	}

	/*
	 * 获取规格信息
	 */
	function getSpec()
	{
		$Goods_SpecModel = new Goods_SpecModel();
		$data            = $Goods_SpecModel->getSpec('*');
		if ($data)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data = array_values($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 新增规格
	 */
	public function addGoodsSpec()
	{
		$data                      = array();
		$data['spec_name']         = request_string('spec_name');
		$data['spec_displayorder'] = request_int('spec_displayorder');

		$spec_id = $this->goodsSpecModel->addSpec($data, true);

		if ($spec_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id']      = $spec_id;
		$data['spec_id'] = $spec_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 修改规格
	 */
	public function editGoodsSpec()
	{
		$Goods_SpecModel = new Goods_SpecModel();

		$id                        = request_int('spec_id');
		$data                      = array();
		$data['spec_name']         = request_string('spec_name');
		$data['spec_displayorder'] = request_int('spec_displayorder');

		$flag = $Goods_SpecModel->editSpec($id, $data);

		if ($flag != false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id']      = $id;
		$data['spec_id'] = $id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>