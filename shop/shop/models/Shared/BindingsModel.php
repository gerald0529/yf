<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Shared_BindingsModel extends Shared_Bindings
{
	//分享列表状态
	public static $sharedBindingsStatu = array(
		0 => '关闭',
		1 => '开启'
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
	public function getSharedBindingsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			$data['items'][$key]['shared_bindings_statu'] = __(Shared_BindingsModel::$sharedBindingsStatu[$value['shared_bindings_statu']]);
		}
		return $data;
	}
	
	/**
	 * 获取编辑分享
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 */
	public function getSharedBindings($cond_row)
	{
		$data = $this->getOneByWhere($cond_row);
		return $data;
	}


}