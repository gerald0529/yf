<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_ImagesModel extends Goods_Images
{
	const IMAGE_DEFAULT     = 1;     //默认图
	const IMAGE_NOT_DEFAULT = 0;     //非默认图
	const IMAGE_NOT_COLOR 	= 0;	 //没有颜色


	/**
	 * 读取分页列表
	 *
	 * @param  int $id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getImagesList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 获取商品图片
	 *
	 * @author  Zhuyt
	 */
	public function getGoodsImage($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}
}

?>