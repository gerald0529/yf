<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_ShopDistributorModel extends Distribution_ShopDistributor
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
    
    /**
     * 获取当前用户的分销折扣
     * @param type $shop_id
     */
    public function getDistributorByShopId($shop_id){
        if(Web_ConfigModel::value('Plugin_Distribution') && Perm::$shopId)
        {
            $shopDistributorLevelModel = new Distribution_ShopDistributorLevelModel();

            //所有供货商，用于对商品操作的判断
            $suppliers = $this->getByWhere(array('distributor_id' =>Perm::$shopId));
            $suppliers  = array_column($suppliers,'shop_id');

            //查看折扣，改变对应供销商商品显示的价格
            $shopDistributorInfo     =  $this->getOneByWhere(array('shop_id' =>$shop_id,'distributor_id'=>Perm::$shopId));				
            if(!empty($shopDistributorInfo) && $shopDistributorInfo['distributor_enable'] == 1){
                $distritutor_rate_info     = $shopDistributorLevelModel->getOne($shopDistributorInfo['distributor_level_id']);
                return $distritutor_rate_info;
            }
        }
        return array();
    }
}
?>