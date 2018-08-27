<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GoodsModel extends User_Goods
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserGoodsList($user_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_goods_id_row = array();
		$user_goods_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_goods_id_row)
		{
			$this->sql->setWhere('user_id',$user_id);
			$this->sql->setWhere('goods_status',1);
			$this->sql->setWhere('goods_verify',1);

			$data_rows = $this->getUserGoods('*');
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
}
?>