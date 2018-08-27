<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */
class Goods_Type extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|goods_type|';
	public $_cacheName       = 'base';
	public $_tableName       = 'goods_type';
	public $_tablePrimaryKey = 'type_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getType($type_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($type_id, $sort_key_row);

		return $rows;
	}

	/**
	 * 插入, 创建对应的表
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addType($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, true);
        
        return $add_flag;
        
		//添加对应表
		$table_name = sprintf('%sgoods_property_map_%d', TABEL_PREFIX, $add_flag);

		$drop_sql  = 'DROP TABLE IF EXISTS `' . $table_name . '`;';
		$drop_flag = $this->sql->exec($drop_sql);

		$create_sql = ' CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
		  `goods_property_map_id` int(10) NOT NULL AUTO_INCREMENT COMMENT "主键",
		  `common_id` int(10) unsigned NOT NULL COMMENT "商品Id",
		   PRIMARY KEY  (`goods_property_map_id`),
		   KEY `common_id` (`common_id`)
		) ENGINE = Innodb DEFAULT CHARSET = utf8 AUTO_INCREMENT = 0;';

		$create_flag = $this->sql->exec($create_sql);
		//$this->sql->exec('ALTER TABLE `$table_name` ADD INDEX (`common_id`)');

	}

	public function editTypeBySimple($type_id = null, $field_row)
	{
		$update_flag = $this->edit($type_id, $field_row);

		return $update_flag;
	}


	/**
	 * 根据主键更新表内容, 删除关系表,重新添加省事.
	 * @param mix $type_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editType($type_id = null, $field_row, $brand_row = array(), $spec_row = array(), $property_rows = array())
	{
		$rs_row = array();

		$update_flag = $this->edit($type_id, $field_row);
		check_rs($update_flag, $rs_row);

		//管理brand*************************
		$Goods_TypeBrandModel = new Goods_TypeBrandModel();

		$type_brand_old_rows = $Goods_TypeBrandModel->getByWhere(array('type_id' => $type_id));

		$brand_id_old_row = array();

		foreach ($type_brand_old_rows as $type_brand_id => $type_brand_old_row)
		{
			$brand_id_old_row[$type_brand_old_row['brand_id']] = $type_brand_id;
		}

		$add_row = array();

		foreach ($brand_row as $key => $brand_id)
		{
			if (!in_array($brand_id, $brand_id_old_row))
			{
				//添加
				$type_brand_row             = array();
				$type_brand_row['type_id']  = $type_id;
				$type_brand_row['brand_id'] = $brand_id;
				$brand_id                   = $Goods_TypeBrandModel->addTypeBrand($type_brand_row, true);
				check_rs($brand_id, $rs_row);
			}
			else
			{
				unset($type_brand_old_rows[$brand_id_old_row[$brand_id]]);
			}
		}

		//删除
		$del_row = array_keys($type_brand_old_rows);

		if ($del_row)
		{
			//独立修改删除
			$flag = $Goods_TypeBrandModel->removeTypeBrand($del_row);
			check_rs($flag, $rs_row);
		}

		//管理spec *************************
		$Goods_TypeSpecModel = new Goods_TypeSpecModel();

		$type_spec_old_rows = $Goods_TypeSpecModel->getByWhere(array('type_id' => $type_id));

		$spec_id_old_row = array();

		foreach ($type_spec_old_rows as $type_spec_id => $type_spec_old_row)
		{
			$spec_id_old_row[$type_spec_old_row['spec_id']] = $type_spec_id;
		}

		$add_row = array();

		foreach ($spec_row as $key => $spec_id)
		{
			if (!in_array($spec_id, $spec_id_old_row))
			{
				//添加
				$type_spec_row            = array();
				$type_spec_row['type_id'] = $type_id;
				$type_spec_row['spec_id'] = $spec_id;
				$spec_id                  = $Goods_TypeSpecModel->addTypeSpec($type_spec_row, true);
				check_rs($spec_id, $rs_row);
			}
			else
			{
				unset($type_spec_old_rows[$spec_id_old_row[$spec_id]]);
			}
		}

		//删除
		$del_row = array_keys($type_spec_old_rows);

		if ($del_row)
		{
			//独立修改删除
			$flag = $Goods_TypeSpecModel->removeTypeSpec($del_row);
			check_rs($flag, $rs_row);
		}

		/*
		//管理 property
		$Goods_PropertyModel = new Goods_PropertyModel();
		$property_old_rows   = $Goods_PropertyModel->getByWhere(array('type_id' => $type_id));

		$table_name = sprintf('%sgoods_property_map_%d', TABEL_PREFIX, $type_id);

		foreach ($property_rows as $key => $property_row)
		{
			if ($property_row['property_name'])
			{
				$property_array                          = array();
				$property_array['property_name']         = $property_row['property_name'];
				$property_array['type_id']               = $type_id;
				$property_array['property_item']         = trim(trim($property_row["property_item"]), ',');
				$property_array['property_is_search']    = $property_row["property_format"] == Goods_PropertyModel::GOODS_PROPERTY_TEXT ? 0 : 1;
				$property_array['property_format']       = $property_row["property_format"];
				$property_array['property_displayorder'] = $property_row["property_displayorder"];

				//判断存在-修改
				if ($property_row['property_id'])
				{
					unset($property_old_rows[$property_row['property_id']]);
					$flag = $Goods_PropertyModel->editProperty($property_row['property_id'], $property_array);
					check_rs($flag, $rs_row);
				}
				else
				{
					//添加
					$property_id = $Goods_PropertyModel->addProperty($property_array, true);
					check_rs($property_id, $rs_row);

					//$table_name = sprintf('%sgoods_property_map_%d', TABEL_PREFIX, $type_id);

					$field = "property_id_" . $property_id;
					$sql   = "ALTER TABLE `" . $table_name . "` ADD `$field` VARCHAR(255) NULL";

					$flag = $this->sql->exec($sql);
					check_rs($flag, $rs_row);
				}
			}
		}

		//删除
		$del_row = array_keys($property_old_rows);

		if ($del_row)
		{
			//独立修改删除
			$flag = $Goods_PropertyModel->removeProperty($del_row);
			check_rs($flag, $rs_row);

			foreach ($del_row as $property_id)
			{
				$field = "property_id_" . $property_id;
				$sql   = "ALTER TABLE `" . $table_name . "` DROP `$field`";

				$flag = $this->sql->exec($sql);
				check_rs($flag, $rs_row);
			}
		}
		*/
		
		return is_ok($rs_row);
	}

	/**
	 * 更新单个字段
	 * @param mix $type_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editTypeSingleField($type_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($type_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $type_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeType($type_id)
	{
		$del_flag = $this->remove($type_id);

		//$this->removeKey($type_id);
		return $del_flag;
	}
}

?>