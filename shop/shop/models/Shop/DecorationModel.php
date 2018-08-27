<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_DecorationModel extends Shop_Decoration
{

	//装修块布局数组
	public $block_layout_array = array('block_1');
	//装修块类型数组
	private $block_type_array = array(
		'html',
		'slide',
		'hot_area',
		'goods'
	);

	/**
	 *
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDecorationRow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}


	public function DecorationWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}


	//多条件获取主键
	public function getDecorationId($cond_row = array(), $order_row = array())
	{
		return $this->getKeyByMultiCond($cond_row, $order_row);
	}


	/**
	 * 获取完整装修数据
	 *
	 * @param array $decoration_id 装修编号
	 * @param int $shop_id 店铺编号
	 * @return array
	 */
	public function getshopDecorationInfoDetail($decoration_id, $shop_id)
	{
		if ($decoration_id <= 0)
		{
			return false;
		}

		$condition                  = array();
		$condition['decoration_id'] = $decoration_id;
		$condition['shop_id']       = $shop_id;
		$store_decoration_info      = $this->getOneByWhere($condition);
		if (!empty($store_decoration_info))
		{
			$data = array();

			//处理装修背景设置
			$decoration_setting = array();
			if (empty($store_decoration_info['decoration_setting']))
			{
				$decoration_setting['background_color']        = '';
				$decoration_setting['background_image']        = '';
				$decoration_setting['background_image_url']    = '';
				$decoration_setting['background_image_repeat'] = '';
				$decoration_setting['background_position_x']   = '';
				$decoration_setting['background_position_y']   = '';
				$decoration_setting['background_attachment']   = '';
			}
			else
			{
				$setting                                       = $store_decoration_info['decoration_setting'];
				$decoration_setting['background_color']        = $setting['background_color'];
				$decoration_setting['background_image']        = $setting['background_image'];
				$decoration_setting['background_image_url']    = $setting['background_image'];
				$decoration_setting['background_image_repeat'] = $setting['background_image_repeat'];
				$decoration_setting['background_position_x']   = $setting['background_position_x'];
				$decoration_setting['background_position_y']   = $setting['background_position_y'];
				$decoration_setting['background_attachment']   = $setting['background_attachment'];
			}
			$data['decoration_setting'] = $decoration_setting;

			//处理块列表
			$block_list         = array();
			$block_list         = $this->getStoreDecorationBlockList(array('decoration_id' => $decoration_id));
			$data['block_list'] = $block_list;

			//处理导航条样式
			$data['decoration_nav'] = $store_decoration_info['decoration_nav'];

			//处理banner
			if ($store_decoration_info['decoration_banner'])
			{
				$decoration_banner = $store_decoration_info['decoration_banner'];
			}
			else
			{
				$decoration_banner = null;
			}
			$decoration_banner['image_url'] = $decoration_banner['image'];
			$data['decoration_banner']      = $decoration_banner;

			return $data;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 背景设置保存验证
	 */
	public function _validate_background_setting_input($decoration_id, $setting)
	{
		$shop_id = Perm::$shopId;
		//验证输入
		if ($decoration_id <= 0)
		{
			return array(
				'error',
				__('param_error')
			);
		}
		if (!empty($setting['background_color']))
		{
			if (strlen($setting['background_color']) > 7)
			{
				return array(
					'error',
					'请输入正确的背景颜色'
				);
			}
		}
		else
		{
			$setting['background_color'] = '';
		}
		if (!empty($setting['background_image']))
		{
			$setting['background_image_url'] = $setting['background_image'];

		}
		else
		{
			$setting['background_image'] = '';
		}
		if (!in_array($setting['background_image_repeat'], array(
			'no-repeat',
			'repeat',
			'repeat-x',
			'repeat-y'
		))
		)
		{
			$setting['background_image_repeat'] = '';
		}
		if (strlen($setting['background_position_x']) > 8)
		{
			$setting['background_position_x'] = '';
		}
		if (strlen($setting['background_position_y']) > 8)
		{
			$setting['background_position_y'] = '';
		}
		if (strlen($setting['background_attachment']) > 8)
		{
			$setting['background_attachment'] = '';
		}
		return $setting;
	}
	/**
	 * 获取开店装修图片地址
	 */
//    public function getStoreDecorationImageUrl($image_name = '', $shop_id = null) {
//            if(empty($shop_id)) {
//                $image_name_array = explode('_', $image_name);
//                $shop_id = $image_name_array[0];
//            }
//        }
	/**
	 * 查询基本数据
	 *
	 * @param array $condition 查询条件
	 * @param int $store_id 店铺编号
	 * @return array
	 */
	public function getStoreDecorationInfo($condition, $store_id = null)
	{
		//$info = $this->table('store_decoration')->where($condition)->find();
		$info = $this->getOneByWhere($condition);
		//如果提供了$store_id，验证是否符合，不符合返回false
		if ($store_id !== null)
		{
			if ($info['shop_id'] != $store_id)
			{
				return false;
			}
		}

		return $info;
	}


	/**
	 * 输出店铺装修
	 */
	public function outputStoreDecoration($decoration_id, $store_id)
	{
		if ($decoration_id > 0)
		{
			$decoration_info = $this->getshopDecorationInfoDetail($decoration_id, $store_id);
			if ($decoration_info)
			{
				$decoration_info['decoration_background_style'] = $this->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
			}
			return $decoration_info;
		}
	}

	/**
	 * 查询装修块列表
	 *
	 * @param array $condition 查询条件
	 * @return array
	 */
	public function getStoreDecorationBlockList($condition)
	{
		// $list = $this->table('store_decoration_block')->where($condition)->order('block_sort asc')->select();
		$shopDecorationBlockModel = new Shop_DecorationBlockModel();
		$order                    = array("block_sort" => "asc");
		$list                     = $shopDecorationBlockModel->getByWhere($condition, $order);
		foreach ($list as $key => $value)
		{
			$list[$key]['block_content'] = str_replace("\r", "", $value['block_content']);
			$list[$key]['block_content'] = str_replace("\n", "", $value['block_content']);
		}
		return $list;
	}


	/**
	 * 生成装修背景样式规则
	 *
	 * @param array $decoration_setting 样式规则数组
	 * @return string 样式规则
	 */
	public function getDecorationBackgroundStyle($decoration_setting)
	{
		$decoration_background_style = '';
		if ($decoration_setting['background_color'] != '')
		{
			$decoration_background_style .= 'background-color: ' . $decoration_setting['background_color'] . ';';
		}
		if ($decoration_setting['background_image'] != '')
		{
			$decoration_background_style .= 'background-image: url(' . $decoration_setting['background_image_url'] . ');';
		}
		if ($decoration_setting['background_image_repeat'] != '')
		{
			$decoration_background_style .= 'background-repeat: ' . $decoration_setting['background_image_repeat'] . ';';
		}
		if ($decoration_setting['background_position_x'] != '' || $decoration_setting['background_position_y'] != '')
		{
			$decoration_background_style .= 'background-position: ' . $decoration_setting['background_position_x'] . ' ' . $decoration_setting['background_position_y'] . ';';
		}
		if ($decoration_setting['background_attachment'] != '')
		{
			$decoration_background_style .= 'background-attachment: ' . $decoration_setting['background_attachment'] . ';';
		}
		return $decoration_background_style;
	}

	/**
	 * 返回装修块布局数组
	 */
	public function getStoreDecorationBlockLayoutArray()
	{
		return $this->block_layout_array;
	}

	/**
	 * 返回装修块模块类型数组
	 */
	public function getStoreDecorationBlockTypeArray()
	{
		return $this->block_type_array;
	}
}

?>