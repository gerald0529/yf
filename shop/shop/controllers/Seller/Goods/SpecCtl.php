<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Goods_SpecCtl extends Seller_Controller
{
	public $goodsSpecValueModel = null;

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

		$this->goodsSpecValueModel = new Goods_SpecValueModel();
	}

	/**
	 * 规格管理
	 *
	 * @access public
	 */
	public function spec()
	{
		include $this->view->getView();
	}

	public function getSpec()
	{
		//根据分类读取规格属性
		$cat_id = request_int('cat_id');

		$Goods_TypeSpecModel = new Goods_TypeSpecModel();

		$data = $Goods_TypeSpecModel->getTypeSpecByCatId($cat_id);

//		var_dump($data);die;

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function online()
	{
		include $this->view->getView();
	}

	public function offline()
	{
		include $this->view->getView();
	}

	public function specManage()
	{
		$cond_row = array();
		$spec_id = request_int('spec_id');
		$cond_row['spec_id'] = $spec_id;
		$data   = $this->goodsSpecValueModel->getSpecValueByShop($cond_row);

		include $this->view->getView();
		
	}

	public function getSpecValue()
	{
		$spec_id = request_int('spec_id');
		$cat_id  = request_int('cat_id');

		$data = $this->goodsSpecValueModel->getSpecValueBySpecId($spec_id, $cat_id);

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}
	
	public function saveSpecValue()
	{
		$old_data = request_row('old');
		$new_data = request_row('new');
		$spec_id  = request_int('spec_id');
		$name     = request_string('name');
		$position = request_string('position');
		$cat_id   = request_int('cat_id');

		$update_data['cat_id']  = $cat_id;
		$update_data['shop_id'] = Perm::$shopId;

		if (!empty($old_data))
		{
			foreach ($old_data as $key => $val)
			{
				$update_data['spec_value_displayorder'] = $val['displayorder'];
				$update_data['spec_value_name']         = $val['spec_value_name'];

				$flag = $this->goodsSpecValueModel->editSpecValue($key, $update_data);
			}

		}

		if (!empty($new_data))
		{
			$update_data['spec_id'] = $spec_id;

			foreach ($new_data as $key => $val)
			{
				$update_data['spec_value_displayorder'] = $val['displayorder'];
				$update_data['spec_value_name']         = $val['spec_value_name'];

				$this->goodsSpecValueModel->addSpecValue($update_data);
			}

		}

		//商城添加规格值
		if (!empty($position) && $position == 'storeAddGoods')
		{
			$update_data['spec_id']         = $spec_id;
			$update_data['shop_id']         = Perm::$shopId;
			$update_data['spec_value_name'] = $name;
			$spec_value_id                  = $this->goodsSpecValueModel->addSpecValue($update_data, true);

			if ($spec_value_id)
			{
				$status                       = 200;
				$msg                          = __('success');
				$update_data['spec_value_id'] = $spec_value_id;
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}

			return $this->data->addBody(-140, $update_data, $msg, $status);
		}

		$status = 200;
		$msg    = __('success');

		$this->data->addBody(-140, array(), $msg, $status);
	}

	public function removeSpecValue()
	{
		$spec_value_id = request_int('spec_value_id');

		$flag = $this->goodsSpecValueModel->removeSpecValue($spec_value_id);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

}

?>