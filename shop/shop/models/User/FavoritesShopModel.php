<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_FavoritesShopModel extends User_FavoritesShop
{

	/**
	 * 读取分页列表
	 */
	public function getFavoritesShopList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 读取一个信息
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $rows 返回的查询内容
	 */
	public function getFavoritesShop($cond_row)
	{
		$data = $this->getOneByWhere($cond_row);

		return $data;
	}

	/**
	 * 读取收藏的店铺信息及商品，必需传递user_id
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $rows 返回的查询内容
	 */
	public function getFavoritesShops($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$de   = $this->listByWhere($cond_row, $order_row);
		$data = array();
		if ($de['items'])
		{
			$shop_id_row = array();
			$shop_id_row = array_column($de['items'], 'shop_id');

			$this->shopBaseModel = new Shop_BaseModel();
			
			$re = $this->shopBaseModel->getShopListByGoodId($shop_id_row, $order_row);

			$shop_id_row               = array();
			$shops_id  = array_column($re, 'shop_id');

			if($shops_id){
				$shop_id_rows              = $shops_id;
				$shop_id_row['shop_id:in'] = $shops_id;
				$shop_id_row['user_id']    = $cond_row['user_id'];

				$data = $this->listByWhere($shop_id_row, $order_row, $page, $rows);
				
				$shop_id_row = array();
				$shop_id_row = $shops_id;
				
				$this->goodsCommonModel = new Goods_CommonModel();
				$shopCompanyModel   = new Shop_CompanyModel();

				foreach ($data['items'] as $key => $val)
				{
					
					if (in_array($val['shop_id'], $shop_id_rows))
					{
						$data['items'][$key]['shop'] = $re[$val['shop_id']];
						$cond_row                    = array();
						$cond_row['shop_id']         = $val['shop_id'];
						$goods                       = $this->goodsCommonModel->getGoodsList($cond_row, array(), 1, 5);
						if ($goods)
						{
							$data['items'][$key]['shop']['detail'] = $goods;
						}
						$company            = $shopCompanyModel->getOne($val['shop_id']);
						$data['items'][$key] = array_merge($data['items'][$key], $company);

					}

					//如果不存在店铺logo获取店铺默认logo
					if(!$val['shop_logo'])
					{
						$data['items'][$key]['shop']['shop_logo'] = Web_ConfigModel::value("photo_shop_logo");

					}
					
					
				}
			}
			
		}
		return $data;
		
	}

	/**
	 * 读取一个人收藏的店铺信息
	 *
	 * @param  int $user_id 查询条件
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $rows 返回的查询内容
	 */
	public function getFavoritesShopDetail($user_id, $page = 1, $rows = 100)
	{
		$cond_row['user_id'] = $user_id;
		$de                  = $this->listByWhere($cond_row);
		if ($de['items'])
		{
			$shop_id = array();
			$shop_id = array_column($de['items'], 'shop_id');

			$this->shopBaseModel = new Shop_BaseModel();
			$re                  = $this->shopBaseModel->getShopListByGoodId($shop_id);

			$shops_id = array();
			$shops_id= array_column($re, 'shop_id');
			if($shops_id){
				$cond_row['shop_id:in'] = $shops_id;

				$shop = $this->listByWhere($cond_row, array('favorites_shop_time' => 'DESC'), $page, $rows);

				foreach ($shop['items'] as $key => $val)
				{
					if (in_array($val['shop_id'], $shops_id))
					{
						$shop['items'][$key]['detail'] = $re[$val['shop_id']];
					}

				}
			
			return $shop;
			}
		}
		
	}

	/**
	 * 收藏数量-应该可以冗余入user resource 表中
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFavoritesShopNum($user_id = null)
	{
		if ($user_id)
		{
			$shop_id_str = ' user_id = ' . $user_id;
		}
		else
		{
			$shop_id_str = '';
		}

		$sql = '
			SELECT count(favorites_shop_id) num
			FROM ' . $this->_tableName . '
			WHERE 1 AND ' . $shop_id_str . '
		';

		$data = $this->sql->getRow($sql);

		return @$data['num'];
	}
}

?>