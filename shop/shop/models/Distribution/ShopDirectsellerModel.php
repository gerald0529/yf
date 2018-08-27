<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_ShopDirectsellerModel extends Distribution_ShopDirectseller
{

    private static $_instance;
    const VALID   = 1;//有效
    const INVALID = 0;//失效
    public static $directseller_status = array("0" => "未审核", "1" => "已审核");

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
     *  获取店铺列表
     */
    public function getShopList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $Shop_BaseModel          = new Shop_BaseModel();
        $cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
        $data                    = $Shop_BaseModel->getBaseList($cond_row, $order_row, $page, $rows);

        $Distribution_ShopDirectsellerConfigModel = new Distribution_ShopDirectsellerConfigModel();

        if ($data['items'])
        {
            foreach ($data['items'] as $k => $v)
            {
                $directseller_id              = Perm::$userId;
                $field_row['directseller_id'] = $directseller_id;
                $field_row['shop_id']         = $v['shop_id'];
                $directseller                 = $this->getOneByWhere($field_row);

                $data['items'][$k]['directseller'] = $directseller;
                $directseller_config               = $Distribution_ShopDirectsellerConfigModel->getOne($v['shop_id']);
                $data['items'][$k]['expenditure']  = @$directseller_config['expenditure'];
                $data['items'][$k]['status']       = @$directseller['directseller_enable'];

                $status                       = Order_StateModel::ORDER_FINISH;
                $data['items'][$k]['expends'] = $this->getExpends($directseller_id, $status, $v['shop_id']);
            }
        }

        //print_r($data);
        return $data;
    }

    /*
     * 获取用户店铺消费额
     */
    public function getShopExpends()
    {
        $buyer_user_id = Perm::$userId;
        $order_status  = Order_StateModel::ORDER_FINISH;

        $sql  = '
			SELECT
				shop_id,
				shop_name,
				SUM(order_payment_amount) AS expends
			FROM ' . TABEL_PREFIX . 'order_base
			WHERE ' . $shop_id_str . '
				order_status = ' . $order_status . '
			AND buyer_user_id = ' . $buyer_user_id . '
			GROUP BY
				shop_id
			ORDER BY shop_id DESC
		';
        $data = $this->sql->getAll($sql);

        if (!$data)
        {
            $data = array();
        }
        else
        {
            foreach ($data as $k => $v)
            {
                $directseller                             = $this->getOneByWhere($v['shop_id']);
                $Distribution_ShopDirectsellerConfigModel = new Distribution_ShopDirectsellerConfigModel();
                $directseller_config                      = $Distribution_ShopDirectsellerConfigModel->getOne($v['shop_id']);
                $data[$k]['expenditure']                  = $directseller_config['expenditure'];
                $data[$k]['status']                       = $directseller['directseller_enable'];
            }
        }
        return $data;
    }

    /*
     *  添加分销员
     */
    public function addDirectseller($field_row)
    {
        return $this->add($field_row);
    }

    /*
     *  获取用户的下级
     */
    public function getInvitors($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $User_InfoModel = new User_InfoModel();
        $data           = $User_InfoModel->getInfoList($cond_row, $order_row, $page, $rows);

        if ($data['items'])
        {
            foreach ($data['items'] as $k => $v)
            {
                $row['user_parent_id']         = $v['user_id'];
                $data['items'][$k]['invitors'] = $User_InfoModel->getSubQuantity($row);

                $data['items'][$k]['expends'] = $this->getExpends($v['user_id'], Order_StateModel::ORDER_FINISH);
                //带来佣金
                $data['items'][$k]['commission'] = $this->getCommission($v['user_id'], 0);
            }
        }

        return $data;
    }

    /*
     *  获取用户的推广商品数目
     */
    public function getDistributionGoodsNum($user_id)
    {
        //获取推广店铺的ID
        $nums                        = 0;
        $cond_row['directseller_id'] = $user_id;
        $shops                       = $this->getByWhere($cond_row);
        $shop_ids                    = array_column($shops, 'shop_id');

        $cond_good_row['shop_id:in']             = $shop_ids;
        $cond_good_row['common_is_directseller'] = 1;
        $cond_good_row['common_state']           = Goods_CommonModel::GOODS_STATE_NORMAL;  //正常上架商品
        $cond_good_row['common_verify']          = Goods_CommonModel::GOODS_VERIFY_ALLOW;  //审核通过

        $Goods_CommonModel = new Goods_CommonModel();
        $data              = $Goods_CommonModel->getCommonList($cond_good_row);
        $nums              = $data['totalsize'];
        return $nums;
    }

    /*
     *  获取用户在店铺的消费总额
     */
    public function  getExpends($user_id, $status, $shop_id = '')
    {
        $con = '';
        if ($shop_id)
        {
            $con = ' AND shop_id=' . $shop_id;
        }

        $sql = '
			SELECT
				shop_id,
				shop_name,
				SUM(order_payment_amount) AS expends
			FROM ' . TABEL_PREFIX . 'order_base
			WHERE
				order_status = ' . $status . '
			AND buyer_user_id = ' . $user_id . $con;

        $data   = $this->sql->getRow($sql);
        $amount = $data['expends'] ? $data['expends'] : 0;

        return $amount;
    }

    /*
     *  获取用户带来的佣金
     */
    public function getCommission($user_id, $level)
    {
        $Order_GoodsModel                       = new Order_GoodsModel();
        $cond_row['buyer_user_id']              = $user_id;
        $cond_row['directseller_is_settlement'] = 1;
        $cond_row['goods_refund_status']        = 0;
        $data                                   = $Order_GoodsModel->getByWhere($cond_row);

        $amount = $data ? array_sum(array_column($data, 'directseller_commission_' . $level)) : 0;
        return $amount;
    }

    /*
    * 获取店铺的分销员
    */
    public function getDirectseller($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $User_InfoModel = new User_InfoModel();
        $data           = $this->listByWhere($cond_row, $order_row = array(), $page, $rows);
        if ($data['items'])
        {
            foreach ($data['items'] as $k => $v)
            {
                $data['items'][$k]['info']                     = $User_InfoModel->getOne($v['directseller_id']);
                $data['items'][$k]['directseller_enable_text'] = __(self::$directseller_status[$v['directseller_enable']]);
            }
        }
        return $data;
    }

    public function editBase($order_id = null, $field_row, $flag = false)
    {
        $update_flag = $this->edit($order_id, $field_row, $flag);

        return $update_flag;
    }

    //检测会员是否是该店铺的分销会员
    public function checkDirectsellerGoods($user_id,$shop_id)
    {
        //查找该会员所有的分销店铺
        $cond_row['directseller_id'] = $user_id;
        $cond_row['directseller_enable'] = 1;
        $shops = $this->getByWhere($cond_row);
        $shop_ids = array_column($shops,'shop_id');

        if(in_array($shop_id,$shop_ids))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}

?>