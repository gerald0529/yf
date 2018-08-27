<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_ShopDirectsellerGoodsCommonModel extends Distribution_ShopDirectsellerGoodsCommon
{

	private static $_instance;

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}
	
	/*
	* 获取推广产品
	*/
	public function getGoodsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = array();
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_rows = $Goods_CommonModel->getCommonList($cond_row, array('common_id' => 'DESC'), $page, $row);
		
		return $goods_rows;
	}
	
	/*
	* 获取推广主图
	*/
	public function getGoodsImages($cond_row = array())
	{
		$data = $this->getOneByWhere($cond_row);
		return $data;
	}
	
	/*
	* 修改推广主图
	*/
	public function editGoodsImages($shop_directseller_goods_common_code = null, $field_row, $flag = false)
	{
		$flag = $this->edit($shop_directseller_goods_common_code,$field_row);
		return $flag;
	}
	
	/*
	* 修改推广主图
	*/
	public function addGoodsImages($field_row)
	{
		$flag = $this->add($field_row);
		return $flag;
	}
	
	
}
?>