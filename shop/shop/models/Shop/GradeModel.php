<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_GradeModel extends Shop_Grade
{
	/**
	 * 读取店铺等级
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGraderow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}

	/**
	 * 根据等级id查询等级的名字
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGradeName($config_key = null)
	{
		$data       = $this->getGrade($config_key);
		$grade_name = '';
		foreach ($data as $key => $value)
		{
			$grade_name = $value['shop_grade_name'];
		}

		return $grade_name;


	}

	public function getGradeWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function listGradeWhere($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{

		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			$temp_num                                       = explode(',', $value['shop_grade_template']);
			$data['items'][$key]['shop_grade_template_num'] = count($temp_num);
		}

		return $data;
	}


	//多条件获取主键
	public function getGradeId($cond_row = array(), $order_row = array())
	{
		return $this->getKeyByMultiCond($cond_row, $order_row);
	}

	//获取等级绑定模板
	public function getGradeTemp($shop_grade_id)
	{
		$date                    = $this->getOne($shop_grade_id);
		$date['shop_grade_temp'] = explode(',', $date['shop_grade_template']);
		return $date;
	}

	//店铺页根据等级绑定
	public function getGradetemplist($shop_grade_id)
	{
		$date              = $this->getGradeTemp($shop_grade_id);
		$shopTemplateModel = new Shop_TemplateModel();
		//循环得到等级下面的模板
		foreach ($date['shop_grade_temp'] as $key => $value)
		{
			$grade_temp[] = $shopTemplateModel->getOne($value);
		}
		return $grade_temp;
	}


}

?>