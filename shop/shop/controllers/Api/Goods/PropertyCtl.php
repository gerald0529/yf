<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_PropertyCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->goodsPropertyModel = new Goods_PropertyModel();
	}


	public function getPropertyValue()
	{
		$property_id = request_int('id');

		$Goods_PropertyValue = new Goods_PropertyValueModel();
		$property_value_row  = $Goods_PropertyValue->getByWhere(array('property_id' => $property_id));

		if ($property_value_row !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data = array_values($property_value_row);

		$this->data->addBody(-140, $data, $msg, $status);
	}


	/*
	 * 修改property连带property_value
	 */
	public function addProperty()
	{
		$Goods_PropertyModel      = new Goods_PropertyModel();
		$Goods_PropertyValueModel = new Goods_PropertyValueModel();

		$edit_row = array();

		$type_id = request_int('type_id');

		//判断type_id是否存在，不存在则启用草稿id => type_draft
		if (empty($type_id))
		{
			$Goods_TypeModel = new Goods_TypeModel();
			$type_base       = $Goods_TypeModel->getByWhere(array('type_draft' => 1));
			//如果没有草稿则创建
			if (empty($type_base))
			{
				$type_id = $Goods_TypeModel->addType(array('type_draft' => 1), true);
			}
			else
			{
				$type_base = pos($type_base);
				$type_id   = $type_base['type_id'];
			}
		}

		$property_id                       = request_int('property_id');
		$edit_row['type_id']               = $type_id;
		$edit_row['property_format']       = request_string('property_format');
		$edit_row['property_is_search']    = request_int('property_is_search');
		$edit_row['property_name']         = request_string('property_name');
		$edit_row['property_displayorder'] = request_int('property_displayorder');

		$property_row = request_row('property_rows');
		$items        = array();


		$rs_row = array();


		foreach ($property_row as $key => $value)
		{
			$items[] = $value['property_value_name'];
		}

		$items = implode(',', $items);

		$edit_row['property_item'] = $items;
		$property_id               = $Goods_PropertyModel->addProperty($edit_row, true);
		$edit_row['property_id']   = $property_id;
		$edit_row['id']            = $property_id;
		check_rs($property_id, $rs_row);


		foreach ($property_row as $key => $value)
		{
			if ($value['property_value_id'])
			{
				$property_value_id = $value['property_value_id'];
				//修改
				unset($value['property_value_id']);
				$flag = $Goods_PropertyValueModel->editPropertyValue($property_value_id, $value);
				check_rs($flag, $rs_row);
			}
			else
			{
				unset($value['property_value_id']);
				$value['property_id'] = $property_id;
				$flag                 = $Goods_PropertyValueModel->addPropertyValue($value, true);
				check_rs($flag, $rs_row);
			}
		}

		if (is_ok($rs_row))
		{
			$msg    = __('success');
			$status = 200;

			/*
			//添加
			$table_name = sprintf('%sgoods_property_map_%d', TABEL_PREFIX, $type_id);
			$field      = "property_id_" . $property_id;
			$sql        = "ALTER TABLE `" . $table_name . "` ADD `$field` VARCHAR(255) NULL";

			$flag = $Goods_PropertyModel->sql->exec($sql);
			//check_rs($flag, $rs_row);
			*/

		}
		else
		{
			$msg    = __('filure');
			$status = 250;
		}

		$this->data->addBody(-140, $edit_row, $msg, $status);
	}

	/*
	 * 修改property连带property_value
	 */
	public function editProperty()
	{
		$rs_row = array();

		$Goods_PropertyModel      = new Goods_PropertyModel();
		$Goods_PropertyValueModel = new Goods_PropertyValueModel();

		$edit_row = array();

		$property_id                       = request_int('property_id');
		$edit_row['property_is_search']    = request_int('property_is_search');
		$edit_row['property_name']         = request_string('property_name');
		$edit_row['property_format']       = request_string('property_format');
		$edit_row['property_displayorder'] = request_int('property_displayorder');

		$property_row = request_row('property_rows');

		$property_id_old_row = $Goods_PropertyValueModel->getKeyByWhere(array('property_id' => $property_id));
		$items               = '';

		foreach ($property_row as $key => $value)
		{
			if (in_array($value['property_value_id'], $property_id_old_row))
			{
				$property_value_id = $value['property_value_id'];
				unset($value['property_value_id']);
				$flag = $Goods_PropertyValueModel->editPropertyValue($property_value_id, $value);
				check_rs($flag, $rs_row);

				$key = array_search($property_value_id, $property_id_old_row);
				if ($key !== false)
				{
					unset($property_id_old_row[$key]);
				}
			}
			else
			{
				unset($value['property_value_id']);
				$value['property_id'] = $property_id;
				$flag                 = $Goods_PropertyValueModel->addPropertyValue($value, true);
				check_rs($flag, $rs_row);
			}

			$items .= $value['property_value_name'] . ',';
		}

		//删除
		if ($property_id_old_row)
		{
			$flag = $Goods_PropertyValueModel->removePropertyValue($property_id_old_row);
			check_rs($flag, $rs_row);
		}

		$items = substr($items, 0, -1);

		$edit_row['property_item'] = $items;
		$flag                      = $Goods_PropertyModel->editProperty($property_id, $edit_row);
		check_rs($flag, $rs_row);

		$edit_row['property_id'] = $property_id;
		$edit_row['id']          = $property_id;

		if (is_ok($rs_row))
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('filure');
			$status = 250;
		}

		$this->data->addBody(-140, $edit_row, $msg, $status);
	}

	public function remove()
	{
		$property_id = request_int('id');

		$Goods_PropertyModel      = new Goods_PropertyModel();
		$Goods_PropertyValueModel = new Goods_PropertyValueModel();

		$property_value_row = $Goods_PropertyValueModel->getKeyByWhere(array('property_id' => $property_id));

		if (!empty($property_value_row))
		{
			foreach ($property_value_row as $key => $value)
			{
				$flag = $Goods_PropertyValueModel->removePropertyValue($value);
			}
		}

		$property_row = $Goods_PropertyModel->getOne($property_id);
		$type_id      = $property_row['type_id'];

		$flags = $Goods_PropertyModel->removeProperty($property_id);

		$data['id'] = $property_id;

		if ($flags)
		{
			$msg    = __('success');
			$status = 200;

			//获取type_id

            /*
			//添加
			$table_name = sprintf('%sgoods_property_map_%d', TABEL_PREFIX, $type_id);
			$field      = "property_id_" . $property_id;
			$sql        = "ALTER TABLE `" . $table_name . "` DROP `$field`";

			$flag = $Goods_PropertyModel->sql->exec($sql);
            */
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>