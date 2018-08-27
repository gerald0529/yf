<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_TypeCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->goodsTypeModel = new Goods_TypeModel();
	}


	/**
	 *
	 *
	 * @access public
	 */
	public function lists()
	{
		$Goods_TypeModel = new Goods_TypeModel();
		$page = request_int('page', 1);
        $page_size = request_int('rows', 20);
		$data            = $Goods_TypeModel->getTypeList(array('type_draft' => 0),  array('type_displayorder' => 'ASC'), $page,$page_size);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['type_id']   = request_string('type_id'); // ID
		$data['type_name'] = request_string('type_name'); // 类型名称


		$type_id = $this->goodsTypeModel->addType($data, true);

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

		$data['type_id'] = $type_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$type_id = request_int('type_id');

		$flag = $this->goodsTypeModel->removeType($type_id);

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

		$data['type_id'] = array($type_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['type_id']   = request_string('type_id'); // ID
		$data['type_name'] = request_string('type_name'); // 类型名称


		$type_id = request_int('type_id');
		$data_rs = $data;

		unset($data['type_id']);

		$flag = $this->goodsTypeModel->editType($type_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	/*
	 * 获取商品类型详细信息
	 */
	public function getType()
	{
		$type_id = request_int('type_id');
		$property_is_search = request_string('property_is_search');
		$data    = $this->goodsTypeModel->getTypeInfo($type_id,$property_is_search);

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
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editType()
	{
		$Goods_TypeModel = new Goods_TypeModel();

		$id = request_int('id');

		$data                      = array();
		$data['type_name']         = request_string('type_name');
		$data['type_displayorder'] = request_int('type_displayorder');
		$brand_row                 = request_row('type_brand');
		$spec_row                  = request_row('type_spec');
		$property_row              = request_row('type_property');
		$flag                      = $Goods_TypeModel->editType($id, $data, $brand_row, $spec_row, $property_row);

		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addType()
	{
		$Goods_TypeModel          = new Goods_TypeModel();
		$Goods_TypeBrandModel     = new Goods_TypeBrandModel();
		$Goods_TypeSpecModel      = new Goods_TypeSpecModel();
		$Goods_PropertyModel      = new Goods_PropertyModel();
		$Goods_PropertyValueModel = new Goods_PropertyValueModel();

		$data                      = array();
		$data['type_name']         = request_string('type_name');
		$data['type_displayorder'] = request_int('type_displayorder');
		$brand_row                 = request_row('type_brand');
		$spec_row                  = request_row('type_spec');
		$property_row              = request_row('type_property');

		//加入草稿 优先判断有没有草稿
		$goods_base = $Goods_TypeModel->getByWhere(array('type_draft' => 1));

		if (!empty($goods_base))
		{
			$goods_base         = pos($goods_base);
			$type_id            = $goods_base['type_id'];
			$data['type_draft'] = 0;
			$Goods_TypeModel->editTypeBySimple($type_id, $data);
		}
		else
		{
			$type_id = $Goods_TypeModel->addType($data, true);
		}

		//品牌
		if (!empty($brand_row) && $type_id)
		{
			foreach ($brand_row as $key_brand => $value_brand)
			{
				$add_brand_row             = array();
				$add_brand_row['type_id']  = $type_id;
				$add_brand_row['brand_id'] = $value_brand;
				$flag_brand                = $Goods_TypeBrandModel->addTypeBrand($add_brand_row, true);
			}
		}

		//规格
		if (!empty($spec_row) && $type_id)
		{
			foreach ($spec_row as $key_spec => $value_spec)
			{
				$add_spec_row            = array();
				$add_spec_row['type_id'] = $type_id;
				$add_spec_row['spec_id'] = $value_spec;
				$flag_spec               = $Goods_TypeSpecModel->addTypeSpec($add_spec_row, true);
			}
		}

		//属性，首次添加属性就一条value
		if (!empty($property_row) && $type_id)
		{
			foreach ($property_row as $key_property => $value_property)
			{
				$add_property_row                          = array();
				$add_property_row['type_id']               = $type_id;
				$add_property_row['property_is_search']    = $value_property['property_is_search'];
				$add_property_row['property_displayorder'] = $value_property['property_displayorder'];
				$add_property_row['property_name']         = $value_property['property_name'];
				$flag_propert                              = $Goods_PropertyModel->addProperty($add_property_row, true);
				if ($flag_propert)
				{
					$add_property_value_row                                = array();
					$add_property_value_row['property_id']                 = $flag_propert;
					$add_property_value_row['property_value_displayorder'] = $value_property['property_item'];;
					$flag_property_value = $Goods_PropertyValueModel->addPropertyValue($add_property_value_row, true);
				}
			}
		}

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

		$data['type_id'] = $type_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>