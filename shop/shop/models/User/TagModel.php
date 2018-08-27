<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_TagModel extends User_Tag
{
	public static $userTagRecommend = array(
		"0" => '否',
		"1" => '是'
	);
	
	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTagList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["user_tag_recommend"] = __(Waybill_TplModel::$waybillTplEnable[$value["user_tag_recommend"]]);
		}
		return $data;
	}

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTagDetail($cond_row)
	{
		$data = $this->getOneByWhere($cond_row);

		return $data;
	}
}

?>