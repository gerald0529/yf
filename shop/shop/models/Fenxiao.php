<?php

class Fenxiao
{
    //给会员上级用户进行分佣
    const LEVELS = 3;

    //分销分类表
    const CAT_TABLE = 'fenxiao_cat';

    //分销商品表
    const GOODS_TABLE = 'fenxiao_goods';

    //分销订单商品表
    const ORDER_GOODS_TABLE = 'fenxiao_order_goods';

    //分销会员关系表
    const USER_RELATIONSHIP_TABLE = 'fenxiao_user';

    //分销佣金表
    const COMMISSION_TABLE = 'fenxiao_commission';

    //用户信息表
    const USER_INFO_TABLE = 'user_info';
    
    //订单信息表
    const ORDER_BASE_TABLE = 'order_base';
    
    //订单商品表
    const YF_ORDER_GOODS_TABLE = 'order_goods';

    //计算金额最小单位（元）
    const MIN_UNIT = 0.01;

        //数据库
    public $db;
    public $dbId = 'shop';

    //分销比例
    private $values;

    //分销比例最小值
    private $min;

    //分销结算周期
    private $cycle;

    private static $fenxiao;
    private $orderGoodsModel;

    public function __construct()
    {
        $this->db = new Yf_Model($this->dbId);
        $this->orderGoodsModel = new Order_GoodsModel;

        $this->values = [
            Web_ConfigModel::value('fenxiao_first'),
            Web_ConfigModel::value('fenxiao_second'),
            Web_ConfigModel::value('fenxiao_third')
        ];

        $this->min = Web_ConfigModel::value('fenxiao_min');
        $this->cycle = Web_ConfigModel::value('fenxiao_cycle');
    }

    public static function getInstance()
    {
        if (static::$fenxiao === null) {
            static::$fenxiao = new Fenxiao();
        }
        return static::$fenxiao;
    }

    /**
     * 检查分佣比例是否低于最小值
     * @param $values
     * @throws
     */
    private function checkMin($values)
    {
        $min = $this->min;
        array_filter($values, function ($value) use ($min) {
            if ($value < $min) {
                throw new Exception('分佣比例不能低于平台设置最小值');
            }elseif ($value>100){
                throw new Exception('分佣比例必须为0-100两位小数');
            }elseif (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/',$value)){
                throw new Exception('分佣比例必须为0-100两位小数');
            }
        });

    }

    /**
     * 检查分类参数
     * @param $data
     * @throws Exception
     */
    private function checkCatConfig($data)
    {
        if (!array_key_exists('cat_id', $data)) {
            throw new Exception('缺少参数：cat_id');
        }

        if (!array_key_exists('shop_id', $data)) {
            throw new Exception('缺少参数：shop_id');
        }

        if (!array_key_exists('user_id', $data)) {
            throw new Exception('缺少参数：user_id');
        }

        if (!array_key_exists('values', $data)) {
            throw new Exception('缺少参数：values');
        }
    }

    /**
     * 检查商品参数
     * @param $data
     * @throws Exception
     */
    private function checkGoodsConfig($data)
    {
        if (!array_key_exists('shop_id', $data)) {
            throw new Exception('缺少参数：shop_id');
        }

        if (!array_key_exists('goods_id', $data)) {
            throw new Exception('缺少参数：goods_id');
        }

        if (!array_key_exists('values', $data)) {
            throw new Exception('缺少参数：values');
        }
    }

    /**
     * 检查商品订单参数
     * @param $data
     * @throws Exception
     */
    private function checkOrderGoodsConfig($data)
    {
        if (!array_key_exists('order_goods_id', $data)) {
            throw new Exception('缺少参数：order_goods_id');
        }

        if (!array_key_exists('order_id', $data)) {
            throw new Exception('缺少参数：order_id');
        }

        if (!array_key_exists('shop_id', $data)) {
            throw new Exception('缺少参数：shop_id');
        }

        if (!array_key_exists('goods_id', $data)) {
            throw new Exception('缺少参数：goods_id');
        }

        if (!array_key_exists('num', $data)) {
            throw new Exception('缺少参数：num');
        }

        if (!array_key_exists('price', $data)) {
            throw new Exception('缺少参数：price');
        }

        if (!array_key_exists('user_id', $data)) {
            throw new Exception('缺少参数：user_id');
        }
    }


    /**
     * 检查分佣比例设置下限
     * 分销比例在平台设置，分为一、二、三级，以及最低分佣比例。
     * 商家可修改一、二、三级的分佣比例，但不能低于平台设置的最低值。
     *
     * @param $cat_id int
     * @param $values array
     * @throws Exception
     */
    /*private function checkValueLimit($cat_id, $values)
    {
        $platform_values = $this->getPlatformValue($cat_id);

        foreach ($values as $k=> $value) {
            $platform_value = $platform_values[$k];
            if ($value < $platform_value) {
                throw new Exception('分佣比例低于平台设置的最低值');
            }
        }
    }*/

    /**
     * 获取该分类下的分佣比例
     * @param $cat_id
     * @return array
     */
    /*private function getPlatformValue($cat_id)
    {
        $cat_table = static::CAT_TABLE;

        $select_sql = <<<EOF
SELECT `level`, `value` FROM `$cat_table` WHERE `cat_id`="$cat_id" AND `shop_id`=0  
EOF;
        $rows = $this->db->sql->getAll($select_sql);

        //该分类下平台没有设置分佣比例，去默认分佣比例
        return empty($rows)
            ? $this->values
            : array_values(array_column($rows, 'value', 'level'));
    }*/

    /**
     * 获取会员上级
     * @param $user_id
     * @return array
     */
    private function getUserParents($user_id)
    {
        $user_relationship_table = TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE;
        $parents = array();

        for ($i = 0; $i < static::LEVELS; $i++) {
            $sql = <<<EOF
SELECT `parent_id` FROM `$user_relationship_table` WHERE `user_id` = $user_id
EOF;
            $res = $this->db->sql->getAll($sql);
            if (!empty($res)) {
                $user_id = current(
                    current($res)
                );
                array_push($parents, $user_id);
            } else {
                break;
            }
        }

        return $parents;
    }

    /**
     * 获取现在商品版本号
     * 是goods_id，要找到对应的common_i！切记~
     * @param $goods_id
     * @return int
     * @throws
     */
    public function getGoodsVersion($goods_id)
    {
        $goods_table = TABEL_PREFIX.static::GOODS_TABLE;

        $goodsModel = new Goods_BaseModel;
        $goods = $goodsModel->getOne($goods_id);

        $shop_id = $goods['shop_id'];
        $cat_id = $goods['cat_id'];
        $common_id = $goods['common_id'];

        $select_sql = <<<EOF
SELECT MAX(`version`) FROM `$goods_table` WHERE `goods_id`="$common_id"
EOF;
        $res = $this->db->sql->getAll($select_sql);

        $version = current(current($res));
        //当前商品未设置分佣比例则按平台默认分佣比例来结算，并写入商品分销表
        if ($version === null) {
            $version = 0;

            $values = $this->getLatestGoodsValues($cat_id, $common_id);
            $this->addGoods($version, $shop_id, $common_id, $values);
        }

        return $version;
    }

    /**
     * 添加分销商品
     * @param $version
     * @param $shop_id
     * @param $common_id
     * @param $values
     * @return boolean
     */
    private function addGoods($version, $shop_id, $common_id, $values)
    {
        $goods_table = TABEL_PREFIX.static::GOODS_TABLE;
        $insert_sql_tpl = <<<EOF
INSERT INTO `$goods_table` (`version`, `shop_id`, `goods_id`, `level`, `value`) VALUES ("$version", "$shop_id", "$common_id", "%s", "%s")
EOF;
        $res_arr = array();
        foreach($values as $k=> $value) {
            $level = $k + 1;
            $update_sql = vsprintf($insert_sql_tpl, array($level, $value));
            $flag = $this->db->sql->getAll($update_sql);
            array_push($res_arr, $flag);
        }
        $res_arr = array_filter($res_arr, function ($item) {
            return $item === false
                ? false
                : true;
        });

        return count($res_arr) == static::LEVELS
            ? true
            : false;
    }

    /**
     * 更新分类表
     * `cat_id` INT (10) NOT NULL COMMENT '分类id',
     * `shop_id` INT (10) NOT NULL DEFAULT 0 COMMENT '店铺id 为零代表平台',
     * `user_id` INT (10) NOT NULL DEFAULT 0 COMMENT '当前操作用户id 为零代表平台',
     * `level` TINYINT (1) NOT NULL COMMENT '分销级别',
     * `value` DECIMAL (4, 2) unsigned NOT NULL COMMENT '分销比例',
     *
     * @param $data = array(values=> array(5,3,1))
     * @throws
     */
    public function updateCat($data)
    {
        $this->checkCatConfig($data);

        $cat_id = $data['cat_id'];
        $user_id = $data['user_id'];
        $shop_id = $data['shop_id'];
        $values = $data['values'];

        $this->checkMin($values);

        //先查询，如果存在则修改，不存在则新增
        $cat_table = TABEL_PREFIX.static::CAT_TABLE;

        $select_sql = <<<EOF
SELECT `id` FROM `$cat_table` WHERE `cat_id` = $data[cat_id] AND `shop_id` = $data[shop_id]
EOF;

        $cat_rows = $this->db->sql->getAll($select_sql);

        $date = date('Y-m-d H:i:s');

        $res_arr = []; //返回结果
        if (empty($cat_rows)) {//没有获取到数据
            $insert_sql_tpl = <<<EOF
INSERT INTO `$cat_table`
(`cat_id`, `shop_id`, `user_id`, `level`, `value`, `create`, `update`)
VALUES
("$cat_id", "$shop_id", "$user_id", "%s", "%s", "$date", "$date")
EOF;
            foreach($values as $k=> $value) {
                $level = $k + 1;
                $insert_sql = sprintf($insert_sql_tpl, $level, $value);
                $flag = $this->db->sql->getAll($insert_sql);
                array_push($res_arr, ($flag === false ? false : true));
            }
        } else {//有数据，进行修改
            $ids = array_column($cat_rows, 'id');
            $update_sql_tpl = <<<EOF
UPDATE `$cat_table` SET `user_id`="$user_id", `value`="%s", `level`="%s", `update`="$date" WHERE id="%s"
EOF;

            foreach($ids as $k=> $id) {
                $level = $k + 1;
                $value = $values[$k];
                $update_sql = sprintf($update_sql_tpl, $value, $level, $id);
                $flag = $this->db->sql->getAll($update_sql);
                array_push($res_arr, ($flag === false ? false : true));
            }
        }

        if (count($res_arr) !== count(array_filter($res_arr))) {
            throw new Exception('更新分佣比例失败');
        }
    }

    /**
     * 更新商品
     * 目前分佣为三级，每次修改都增加三条记录，版本号加一
     * `shop_id` INT (10) NOT NULL COMMENT '店铺id（冗余）',
     * `goods_id` INT (10) NOT NULL COMMENT '商品id' 此goods_id指的是common_id,
     * `version` INT (10) NOT NULL COMMENT '版本号',
     * `level` TINYINT (1) NOT NULL COMMENT '分销级别',
     * `value` DECIMAL (4, 2) unsigned NOT NULL COMMENT '分销比例',
     *
     * @param $data =
     * array(cat_id, shop_id, goods_id, values=> array(5,3,1))
     * @return boolean
     */
    public function updateGoods($data)
    {
        $this->checkGoodsConfig($data);

        $cat_id = $data['cat_id'];
        $values = $data['values'];
        $this->checkMin($values);

        $goods_table = TABEL_PREFIX.static::GOODS_TABLE;
        $shop_id = $data['shop_id'];
        $common_id = $data['goods_id'];

        $select_sql = <<<EOF
SELECT
	`level`,
	`value`,
	`version`
FROM
	$goods_table
WHERE
	`goods_id` = $common_id
GROUP BY
	`level`,
	`version`,
	`value`
HAVING
	max(version) = (
		SELECT
			max(version)
		FROM
			$goods_table
		WHERE
			`goods_id` = $common_id
	);
EOF;
        $rows = $this->db->sql->getAll($select_sql);
        if (empty($rows)) {
            $version = 0;
            $this->addGoods($version, $shop_id, $common_id, $values);
        } else {
            $old_values = array_map(function ($row) {
                return $row['value'];
            }, $rows);

            //有数据则比较，是否发生修改，修改就插入三条数据，版本号加一（即使只修改一条数据）
            $diff = array_udiff_assoc($old_values, $values, function ($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return $a > $b ? 1 : -1;
            });

            if (empty($diff)) { //无修改，直接返回
                return true;
            } else {
                $row = current($rows);
                $version = $row['version'] + 1;
                $this->addGoods($version, $shop_id, $common_id, $values);
            }
        }
    }

    /**
     * 获取订单商品数据，批量处理订单商品
     * @param $order_ids
     * @throws
     */
    public function order($order_ids)
    {
        $order_goods_rows = $this->orderGoodsModel->getByWhere([
            'order_id:IN'=> $order_ids
        ]);

        $flags = [];
        foreach($order_goods_rows as $order_goods_id=> $order_goods) {
            $shop_id = $order_goods['shop_id'];
            $order_id = $order_goods['order_id'];
            $goods_id = $order_goods['goods_id'];
            $num = $order_goods['order_goods_num'];
            $price = $order_goods['order_goods_amount']; //商品金额 （实付金额）= order_goods_payment_amount* order_goods_num
            $user_id = $order_goods['buyer_user_id']; //买家id

            $data = [
                'order_goods_id'=> $order_goods_id,
                'order_id'=> $order_id,
                'shop_id'=> $shop_id,
                'goods_id'=> $goods_id,
                'num'=> $num,
                'price'=> $price,
                'user_id'=> $user_id,
            ];
            $flag = $this->ordergoods($data);
            array_push($flags, $flag);
        }

        if (count($flags) !== count(array_filter($flags))) {
            throw new Exception('分销订单商品处理失败');
        }
    }

    /**
     * 取消订单（退款/退货）
     * @param $order_return_id
     * @throws
     */
    public function cancelOrder($order_return_id)
    {
        $order_return_model = new Order_ReturnModel;
        $order_return = $order_return_model->getOne($order_return_id);

        $price = -$order_return['return_cash'];
        $order_id = $order_return['order_number'];
        $return_type = $order_return['return_type'];

        //如果订单退款并产生产生运费、需要扣除
        if ($return_type == Order_ReturnModel::RETURN_TYPE_ORDER) {
            $order_model = new Order_BaseModel;
            $order = $order_model->getOne($order_id);
            $order_shipping_fee = $order['order_shipping_fee'];
            $price = $price + $order_shipping_fee;
        }

        $order_goods_id = $order_return['order_goods_id'];
        $num = $order_return['order_goods_num'];
        $user_id = $order_return['buyer_user_id'];

        $order_goods_model = new Order_GoodsModel;
        $oder_goods = $order_goods_model->getOne($order_goods_id);

        $shop_id = $oder_goods['shop_id'];
        $goods_id = $oder_goods['goods_id'];

        $data = [
            'order_goods_id'=> $order_goods_id,
            'order_id'=> $order_id,
            'shop_id'=> $shop_id,
            'goods_id'=> $goods_id,
            'num'=> $num,
            'price'=> $price,
            'user_id'=> $user_id,
        ];

        $flag = $this->ordergoods($data, true);

        if ($flag === false) {
            throw new Exception('分销订单商品处理失败');
        }
    }

    /**
     * 发生退款/退货
     * 获取该商品下单时的版本号
     * @param $order_goods_id
     * @return int
     */
    public function getOrderGoodsVersion($order_goods_id)
    {
        $order_goods_table = TABEL_PREFIX.static::ORDER_GOODS_TABLE;

        $select_sql = <<<EOF
SELECT * FROM `$order_goods_table` WHERE `order_goods_id`="$order_goods_id"
EOF;

        $res = $this->db->sql->getAll($select_sql);
        $order_goods = current($res);

        return $order_goods['version'];
    }

    /**
     * 处理订单商品
     * `id` INT (10) NOT NULL AUTO_INCREMENT COMMENT '订单商品表id',
     * `order_goods_id` INT (10) NOT NULL COMMENT '订单商品表id',
     * `order_id` varchar(50) NOT NULL COMMENT '订单id',
     * `shop_id` INT (10) NOT NULL COMMENT '店铺id（冗余）',
     * `goods_id` INT (10) NOT NULL COMMENT '商品id（冗余）',
     * `num` INT (10) NOT NULL COMMENT '数量',
     * `price` DECIMAL(10,2) unsigned NOT NULL COMMENT '实付金额总的（冗余）',
     * `version` INT (10) NOT NULL COMMENT '关联分销商品表版本号',
     * `user_id` INT (10) NOT NULL COMMENT '用户id',
     * @param $data
     * @param $cancel boolean 是否取消订单（退款、退货）如果发生该值为true则是退款退货 新增记录为负，不改变原有记录
     * @return boolean
     */
    private function orderGoods($data, $cancel = false)
    {
        $this->checkOrderGoodsConfig($data);
        $order_goods_table = TABEL_PREFIX.static::ORDER_GOODS_TABLE;
        $order_goods_id = $data['order_goods_id'];
        $order_id = $data['order_id'];
        $shop_id = $data['shop_id'];
        $goods_id = $data['goods_id'];
        $num = $data['num'];
        $price = $data['price'];
        $user_id = $data['user_id'];

        if ($cancel === false) {
            $version = $this->getGoodsVersion($goods_id);
        } else {
            //发生退款退货、获取该商品下单时version
            $version = $this->getOrderGoodsVersion($order_goods_id);
        }

        $insert_sql = <<<EOF
INSERT INTO `$order_goods_table`
(`order_goods_id`, `order_id`, `shop_id`, `goods_id`, `num`, `price`, `version`, `user_id`)
VALUES
("$order_goods_id", "$order_id", "$shop_id", "$goods_id", $num, "$price", "$version", "$user_id")
EOF;

        $this->db->sql->startTransactionDb();
        $order_goods_flag = $this->db->sql->getAll($insert_sql);

        if ($order_goods_flag === false) {
            return false;
        }
        $id = $this->db->sql->insertId();
        $flag = $this->settleAccounts($user_id, $id, $goods_id, $price, $version);

        if ($flag) {
            $this->db->sql->commitDb();
        } else {
            $this->db->sql->rollBackDb();
        }
        return $flag;
    }

    /**
     * 计算分佣
     * @param $user_id int
     * @param $order_goods_id int（注意此id为fenxiao_order_goods主键id）
     * @param $goods_id int
     * @param $price float
     * @param $version int
     * @return boolean
     * @throws
     */
    private function settleAccounts($user_id, $order_goods_id, $goods_id, $price, $version)
    {
        $parents = $this->getUserParents($user_id);

        if (empty($parents)) { //该用户没有上级，不计算佣金
            return true;
        }

        $values = $this->getGoodsValues($goods_id, $version);

        if (empty($values)) {
            throw new Exception('找不到该商品分佣比例');
        }

        $commission_table = TABEL_PREFIX.static::COMMISSION_TABLE;

        $date = date('Y-m-d H:i:s');
        $insert_sql_tpl = <<<EOF
INSERT INTO `$commission_table`
(`order_goods_id`, `price`, `user_id`, `create`)
VALUES
("$order_goods_id", "%s", "%s", "$date")
EOF;

        $res_arr = array();
        foreach($parents as $k=> $parent_id) {
            $value = $values[$k];
            $commission = round($price * $value/100, 4);

            if (abs($commission) < static::MIN_UNIT) { //交易金额过低，没有佣金
                continue;
            }
            $commission = round($commission, 2);
            $insert_sql = sprintf($insert_sql_tpl, $commission, $parent_id);
            $flag = $this->db->sql->getAll($insert_sql);
            array_push($res_arr, ($flag === false ? false : true));
        }
        return count($res_arr) === count(array_filter($res_arr))
            ? true
            : false;
    }

    /**
     * 确认订单
     * 此方法重点是更新佣金表结束时间（因为佣金结算是按照结束时间结算）
     * @param $order_id
     * @throws
     */
    public function confirmOrder($order_id)
    {
        $order_goods_table = TABEL_PREFIX.static::ORDER_GOODS_TABLE;
        $commission_table = TABEL_PREFIX.static::COMMISSION_TABLE;
        $order_goods_model = new Order_GoodsModel;

        $order_goods_rows = $order_goods_model->getByWhere([
            'order_id:IN'=> $order_id
        ]);
        $order_goods_ids = implode(',', array_keys($order_goods_rows));

        $get_order_goods_sql = <<<EOF
SELECT `id` FROM `$order_goods_table` WHERE `order_goods_id` IN ($order_goods_ids)
EOF;
        $fenxiao_order_goods_rows = $this->db->sql->getAll($get_order_goods_sql);
        $fenxiao_order_goods_ids = implode(',', array_column($fenxiao_order_goods_rows, 'id'));

        $date = date('Y-m-d H:i:s');
        $update_commion_sql = <<<EOF
UPDATE `$commission_table` SET `end`="$date" WHERE `order_goods_id` IN ($fenxiao_order_goods_ids) AND UNIX_TIMESTAMP(`end`) = 0
EOF;
        $flag = $this->db->sql->getAll($update_commion_sql);

        if ($flag === false) {
            throw new Exception('更新佣金表失败');
        }
    }

    /**
     * 获取符合结算周期的佣金记录
     * @return array
     */
    public function getCycleList()
    {
        $order_goods_table = TABEL_PREFIX.static::ORDER_GOODS_TABLE;
        $commission_table = TABEL_PREFIX.static::COMMISSION_TABLE;

        $cycle = $this->cycle;
        $cycle = $cycle * 24 * 60 * 60;

        $select_commission_sql = <<<EOF
SELECT * FROM `$commission_table` WHERE `status` = 0 AND UNIX_TIMESTAMP(`end`) != 0 AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`end`) >= $cycle
EOF;
        $rows = $this->db->sql->getAll($select_commission_sql);

        if ($rows) {
            $order_goods_id = implode(',', array_unique(array_column($rows, 'order_goods_id')));
            $select_order_goods_sql = <<<EOF
SELECT * FROM `$order_goods_table` WHERE `id` IN ($order_goods_id)
EOF;
            $oder_goods_rows = $this->db->sql->getAll($select_order_goods_sql);

            $order_ids = array_column($oder_goods_rows, 'order_id', 'id');

            array_walk($rows, function (&$value, $key, $order_ids) {
                $id = $value['order_goods_id'];
                $value['order_id'] = $order_ids[$id];
            }, $order_ids);
        }
        return $rows;
    }

    /**
     * 已经结算、做标记
     * @param $id
     * @return boolean
     */
    public function settled($id)
    {
        $commission_table = TABEL_PREFIX.static::COMMISSION_TABLE;

        $update_sql = <<<EOF
UPDATE `$commission_table` SET `status` = 1 WHERE `id` = "$id"; 
EOF;

        $flag = $this->db->sql->getAll($update_sql);
        return $flag === false
            ? false
            : true;
    }

    /**
     * 获取该商品的分佣比例
     * 一定要注意这个是goods_id，获取分佣比例要按common_id获取
     * @param $goods_id
     * @param $version
     * @return array
     */
    private function getGoodsValues($goods_id, $version)
    {
        $goods_model = new Goods_BaseModel;
        $goods = $goods_model->getOne($goods_id);
        $common_id = $goods['common_id'];

        $goods_table = TABEL_PREFIX.static::GOODS_TABLE;
        $select_sql = <<<EOF
SELECT `level`, `value` FROM `$goods_table` WHERE `goods_id`="$common_id" AND `version`=$version
EOF;
        $res = $this->db->sql->getAll($select_sql);
        return array_values(array_column($res, 'value', 'level'));
    }

    /**
     * 添加会员关系
     * @param $user_id
     * @param $parent_id
     * @return boolean
     */
    public function addUserRelationship($user_id, $parent_id)
    {
        $user_relationship_table = TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE;
        $create = date('Y-m-d H:i:s');
        $sql = <<<EOF
INSERT INTO `$user_relationship_table` VALUES ("$user_id", "$parent_id", "$create")
EOF;
        return $this->db->sql->getAll($sql) === false
            ? false
            : true;
    }

    /**
     * 获取分类分佣比例
     * @param $cat_id
     * @param $shop_id
     * @return array
     */
    public function getCatValues($cat_id, $shop_id = 0)
    {
        $cat_table = TABEL_PREFIX.static::CAT_TABLE;

        $select_sql = <<<EOF
SELECT * FROM `$cat_table` WHERE `cat_id` = "$cat_id" AND `shop_id` = "$shop_id"
EOF;
        $rows = $this->db->sql->getAll($select_sql);

        return empty($rows)
            ? $this->values
            : array_values(array_column($rows, 'value', 'level'));
    }

    /**
     * 获取分类管理推广员分佣比例
     * @param $cat_ids
     * @param $shop_id
     * @return array
     */
    public function getCatValuesList($cat_ids, $shop_id = 0)
    {
        $cat_table = TABEL_PREFIX . static::CAT_TABLE;
        $cat = implode(',', $cat_ids);
        $select_sql = <<<EOF
SELECT * FROM `$cat_table` WHERE `cat_id` IN ($cat) AND `shop_id` = "$shop_id"
EOF;

        $rows = $this->db->sql->getAll($select_sql);

        $cat_rows = [];
        $res_arr = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $cat_id = $row['cat_id'];
                $level = $row['level'];
                $value = $row['value'];
                $cat_rows[$cat_id][$level - 1] = $value;
            }
        }

        foreach ($cat_ids as $cat_id) {
            if (isset($cat_rows[$cat_id])) {
                $res_arr[$cat_id] = $cat_rows[$cat_id];
            } else {
                $res_arr[$cat_id] = $this->values;
            }
        }

        return $res_arr;
    }

    /**
     * 获取商品分佣比例（最新）
     * @param $cat_id
     * @param $common_id
     * @return array
     */
    public function getLatestGoodsValues($cat_id, $common_id)
    {
        if ($common_id) {
            $select_sql = <<<EOF
SELECT * FROM `yf_fenxiao_goods` WHERE `goods_id`=$common_id AND `version`=(SELECT max(`version`) FROM `yf_fenxiao_goods` WHERE `goods_id`=$common_id)
EOF;
            $res = $this->db->sql->getAll($select_sql);

            if ($res) {
                return array_values(array_column($res, 'value', 'level'));
            }
        }
        //没有则查找该商品分类
        $select_cat_sql = <<<EOF
SELECT * FROM `yf_fenxiao_cat` WHERE `cat_id`="$cat_id"
EOF;
        $res_cat = $this->db->sql->getAll($select_cat_sql);

        if ($res_cat) {
            return array_values(array_column($res_cat, 'value', 'level'));
        }
        return $this->values;
    }
    
    /**
     * 分销中心 我的推广分页
     * @param type $user_id
     * @return type
     */
    public function getFenxiaoList($user_id)
    {
        $field = 'a.`parent_id` AS fenxiao_parent_id,a.`create` AS fenxiao_create,b.*';
        $select  = "SELECT ".$field."  FROM `".TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE."` a LEFT JOIN `".TABEL_PREFIX.static::USER_INFO_TABLE."` b ON a.`user_id` = b.`user_id`";
        $where = "WHERE a.`parent_id` = :user_id";
        $bind_value = [':user_id' => $user_id];
        $user_name = request_string('userName');
        if($user_name){
           $where .= " and b.user_name like :user_name";
           $bind_value[':user_name'] = '%'.$user_name.'%';
        }
        $sql = $select.' '.$where;
        $sql_count = str_replace($field,"count(*) num",$sql); 
        $sql .=  ' order by b.user_id desc';
		$db = new YFSQL;
		$rs = $db->pager([
			'sql' => $sql,
            'sql_count' => $sql_count,
			'value' => $bind_value,
			'size' => 10 //每页条数
		]);
        //推广会员数
        if($rs['data']){
            $user_id_arr = [];
            foreach ($rs['data'] as $value){
                $user_id_arr[] = $value['user_id'];
            }
            //推广会员数
            $user_count = $this->getCountUser($user_id_arr);
            //消费总额
            $user_expends = $this->getUserExpends($user_id_arr);
            //带来佣金
            $user_commission = $this->getUserBringCommission($user_id_arr,$user_id);
            
            foreach ($rs['data'] as &$val){
                $val['fx_user_count'] = $user_count[$val['user_id']] ? $user_count[$val['user_id']] : 0;
                $val['expends'] = $user_expends[$val['user_id']] ? $user_expends[$val['user_id']] : 0;
                $val['commission'] = $user_commission[$val['user_id']] ? $user_commission[$val['user_id']] : 0;
            }
            
        }
        
		return $rs;
    }
    
    /**
     * 获取用户的推广会员数
     * @param type $user_id
     * @return type
     */
    public function getCountUser($user_id)
    {
        if(!$user_id){
            return [];
        }
        if(is_array($user_id)){
            $user_id_str = implode(',', $user_id);
            $sql = 'select parent_id,count(*) as num from '.TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE.' where parent_id in('.$user_id_str.') group by parent_id';
        } else {
            $sql = 'select parent_id,count(*) as num from '.TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE.' where parent_id = '.$user_id;
        }
        
        $db = new YFSQL;
        $rs = $db->find($sql);
        
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['parent_id']] = $value['num'];
            }
        }
        return $result;
    }
    
    /**
     * 获取用户消费总额
     * @param type $user_id
     * @return type
     */
    public function getUserExpends($user_id)
    {
        if(!$user_id){
            return [];
        }
        if(is_array($user_id)){
            $user_id_str = implode(',', $user_id);
            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' where user_id in('.$user_id_str.') group by user_id';
        } else {
            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' where user_id = '.$user_id;
        }
      
        $db = new YFSQL;
        $rs = $db->find($sql);
        
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['user_id']] = $value['num'];
            }
        }
        return $result;
    }
    
    /**
     * 获取用户带来的佣金
     * @param type $user_id
     * @param type $bring_user_id
     */
    public function getUserBringCommission($bring_user_id,$user_id)
    {
        if(!$bring_user_id){
            return [];
        }
        if(is_array($bring_user_id)){
            $user_id_str = implode(',', $bring_user_id);
//            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id in('.$user_id_str.') group by user_id';
            $sql = 'select a.user_id,b.price,b.user_id as cur_user_id from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::COMMISSION_TABLE.' b on a.id = b.order_goods_id where b.user_id='.$user_id.' and a.user_id in('.$user_id_str.') ';
        } else {
//            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id = '.$user_id;
            $sql = 'select a.user_id,b.price,b.user_id as cur_user_id from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::COMMISSION_TABLE.' b on a.id = b.order_goods_id where b.user_id='.$user_id.' and  user_id = '.$bring_user_id;
        }
        
        $db = new YFSQL;
        $rs = $db->find($sql);
        
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['user_id']] = isset($result[$value['user_id']]) ? $result[$value['user_id']] : 0;
                $result[$value['user_id']] += $value['price'];
            }
        }
        return $result;
        
    }
    
    /**
     * 获取用户的佣金总额
     * @param type $user_id
     * @return type
     */
    public function getUserSumCommission($user_id)
    {
        if(!$user_id){
            return [];
        }
        if(is_array($user_id)){
            $user_id_str = implode(',', $user_id);
            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id in('.$user_id_str.') group by user_id';
        } else {
            $sql = 'select user_id,sum(price) as num from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id = '.$user_id;
        }
        
        $db = new YFSQL;
        $rs = $db->find($sql);
        
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['user_id']] = $value['num'];
            }
        }
        return $result;
    }
    
    
    /**
     * 获取用户的所有分销员
     * 
     * @param type $user_id
     * @return type
     */
    public function getFenxiaoUserId($user_id)
    {
        if(!is_array($user_id)){
            $user_ids[] = $user_id;
        }
        $level_user_ids = [];
        //获取一级分销员
        $user_level_1 = $this->getChildUser($user_ids); 
        $level_user_ids['level_1'] = $user_level_1;
        
        //获取二级分销员
        $user_level_2 = $this->getChildUser($user_level_1); 
        $level_user_ids['level_2'] = $user_level_2;
       
        //获取三级分销员
        $user_level_3 = $this->getChildUser($user_level_2); 
        $level_user_ids['level_3'] = $user_level_3;
        
        return $level_user_ids;
        
    }
    
    public function getChildUser($user_ids){
         if(!$user_ids){
            return [];
        }
        $user_id_str = implode(',', $user_ids);
        //获取三级分销员
        $sql = 'select * from '.TABEL_PREFIX.static::USER_RELATIONSHIP_TABLE.' where parent_id in('.$user_id_str.')';
        $db = new YFSQL;
        $user = $db->find($sql);
        if($user){
            foreach ($user as $value){
                $level_user_ids[] = $value['user_id'];
            }
            return $level_user_ids;
        } else {
            return [];
        }
        
    }
    
    /**
     * 分销中心 我的业绩
     * @param type $user_id
     * @return type
     * 
     */
    public function getFenxiaoOrder($user_id)
    {
        $level_user_id = $this->getFenxiaoUserId($user_id);
        $user_ids = array_merge($level_user_id['level_1'],$level_user_id['level_2'],$level_user_id['level_3']);
        if(!$user_ids){
            return [];
        }
        $user_id_str = implode(',', $user_ids);
        
        //$user_id_str = $user_id_str ? $user_id.','.$user_id_str : $user_id;
//        $user_id_str = $user_id_str ? $user_id_str : "";
        $rs = $this->getFenxiaoData($user_id_str);
        return $rs;
        
    }
    
    /**
     * 获取用户的分销数据-- 我的业绩和佣金记录公用
     * @param type $user_id_str
     * @return type
     */
    public function getFenxiaoData($user_id_str)
    {
        $where = 'where a.user_id in('.$user_id_str.')';
        $status = $this->getStatus(request_string('status'));
        if($status){
            $where .= ' and order_status='.$status;
        }
        if (request_string('orderkey')){
            $where .= ' and order_id=:order_id';
            $bind_value[':order_id'] = request_string('orderkey');
		}
		if (request_string('start_date')){
            $where .= ' and order_create_time>:start_date';
            $bind_value[':start_date'] = request_string('start_date');
		}
		if (request_string('end_date')){
            $where .= ' and order_create_time<:end_date';
            $bind_value[':end_date'] = request_string('end_date');
		}
        $sql = 'select a.order_id,b.* from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::ORDER_BASE_TABLE.' b on a.order_id=b.order_id '.$where.' group by a.order_id order by b.order_create_time desc';  //订单
        $sql_count = 'SELECT COUNT(*) AS num FROM (select a.order_id from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::ORDER_BASE_TABLE.' b on a.order_id=b.order_id '.$where.' group by a.order_id) c';
    
        $db = new YFSQL;
        $rs = $db->pager([
			'sql' => $sql,
            'sql_count' => $sql_count,
			'value' => $bind_value,
			'size' => 10 //每页条数
		]);
        if(!$rs['data']){
            return [];
        }
        
        //获取佣金，订单，店铺
        $order_goods_id = [];
        $shop_ids = [];
        $order_ids = [];
        foreach ($rs['data'] as $value){
            $shop_ids[] = $value['shop_id'];
            $order_ids[] = $value['order_id'];
        }
        //佣金
        $user_id = Perm::$userId;
        $order_goods_sql = 'select * from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' where user_id in('.$user_id_str.')'; //商品 = 购买商品 + 退货商品
        $order_goods_info = $db->find($order_goods_sql); 
        foreach ($order_goods_info as $list){
            $order_goods_id[] = $list['id'];
        }
        $user_commission = $this->getUserCommission($user_id,$order_goods_id); 
        //店铺
        $shop_list = $this->getShopList($shop_ids);
        //订单
        $order_goods_list = $this->getFenxiaoOrderGoodsByOrderId($order_ids); 
        Yf_Log::log(var_export($user_commission, true), Yf_Log::INFO, 'fenxiao');
        foreach ($order_goods_list as &$v){
            
            foreach ($v as &$c){
                $c['fenxiao_commission'] = !$user_commission[$c['id']] ? 0 : $user_commission[$c['id']]['price'];
                $c['fenxiao_account_status'] = !$user_commission[$c['id']] ? 0 : $user_commission[$c['id']]['status'];
            }
        }
        $Order_StateModel = new Order_StateModel();
        foreach ($rs['data'] as &$val){
            $val['goods_list'] = $order_goods_list[$val['order_id']];
            $val['shop_name'] = $shop_list[$val['shop_id']]['shop_name'];
            $val['order_state_con'] = $Order_StateModel->orderState[$val['order_status']];
        }
        return $rs;
    }
    
    /**
     * 获取用户的商品佣金
     * @param type $order_goods_id
     */
    public function getUserCommission($user_id,$order_goods_id)
    {
        if(!$order_goods_id){
            return [];
        }
        if(is_array($order_goods_id)){
            $order_goods_id_str = implode(',', $order_goods_id);
            $sql = 'select order_goods_id,price,status from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id='.$user_id.' and order_goods_id in('.$order_goods_id_str.')';
        } else {
            $sql = 'select order_goods_id,price,status from '.TABEL_PREFIX.static::COMMISSION_TABLE.' where user_id='.$user_id.' and order_goods_id ='.$order_goods_id;
        }
            
        $db = new YFSQL;
        $rs = $db->find($sql);
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['order_goods_id']]['price'] = $value['price'];
                $result[$value['order_goods_id']]['status'] = $value['status'];
            }
        }
        return $result;
    }
    
    
    /**
     * 获取店铺信息
     * @param type $ids
     * @return type
     */
    public function getShopList($ids)
    {
        $shop_model = new Shop_BaseModel();
        $list = $shop_model->getBase($ids);
        return $list;
    }
    
    /**
     * 获取订单信息
     * @param type $ids
     * @return type
     */
    public function getOrderList($ids)
    {
        $order_model = new Order_BaseModel();
        $list = $order_model->getBase($ids);
        return $list;
    }
    
    /**
     * 根据订单id获取
     * @param type $order_id
     * @return type
     */
    public function getFenxiaoOrderGoodsByOrderId($order_id)
    {
        if(!$order_id){
            return [];
        }
        if(is_array($order_id)){
            $order_id_str = implode('","', $order_id);
            $sql = 'select * from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::YF_ORDER_GOODS_TABLE.' b on a.order_goods_id=b.order_goods_id where a.order_id in("'.$order_id_str.'")';
            
        } else {
            $sql = 'select * from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::YF_ORDER_GOODS_TABLE.' b on a.order_goods_id=b.order_goods_id where a.order_id ="'.$order_id_str.'"';
            
        }
            
        $db = new YFSQL;
        $rs = $db->find($sql);
        $result = [];
        if($rs){
            foreach ($rs as $value){
                $result[$value['order_id']][$value['id']] = $value;
            }
        }
        return $result;
        
    }
    
    /**
     * 获取订单状态
     * @param type $status
     * @return string
     */
    public function getStatus($status)
    {
        switch($status){
            case 'wait_pay': //待付款
                $order_status = Order_StateModel::ORDER_WAIT_PAY;
                break;
            case 'wait_perpare_goods': //待发货 -> 只可退款
                $order_status = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
                break;
            case 'order_payed': //已付款
                $order_status = Order_StateModel::ORDER_PAYED;
                break;
            case 'wait_confirm_goods': //待收货、已发货 -> 退款退货
                $order_status = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                break;
            case 'finish': //已完成 -> 订单评价
                $order_status = Order_StateModel::ORDER_FINISH;
                break;
            case 'cancel': //已取消
                $order_status = Order_StateModel::ORDER_CANCEL;
                break;
            default :
                $order_status = '';
                
        }
        return $order_status;
    }
    
    /**
     * 分销佣金记录
     * @param type $user_id
     * @return type
     */
    public function getFenxiaoCommission($user_id){
        $level_user_id = $this->getFenxiaoUserId($user_id);
        
        $status = request_string('status');
        switch($status){
			case "second" :
                $user_id_str = $level_user_id['level_2'] ? implode(',', $level_user_id['level_2']) : '';
                break;
			case "third" :
                $user_id_str = $level_user_id['level_3'] ? implode(',', $level_user_id['level_3']) : '';
                break;
			default:
                $user_id_str = $level_user_id['level_1'] ? implode(',', $level_user_id['level_1']) : '';
		}
        $rs = $this->getFenxiaoData($user_id_str);
        return $rs;
    }
    
    /**
     * 获取订单数
     * @param type $user_id
     * @return type
     */
    public function getFenxiaoOrderCount($user_id){
        $level_user_id = $this->getFenxiaoUserId($user_id);
        $user_ids = array_merge($level_user_id['level_1'],$level_user_id['level_2'],$level_user_id['level_3']);
        if(!$user_ids){
            return 0;
        }
        $user_id_str = implode(',', $user_ids);
        
//        $user_id_str = $user_id_str ? $user_id.','.$user_id_str : $user_id;
        $where = 'where a.user_id in('.$user_id_str.')';
        $sql_count = 'SELECT COUNT(*) AS num FROM (select a.order_id from '.TABEL_PREFIX.static::ORDER_GOODS_TABLE.' a left join '.TABEL_PREFIX.static::ORDER_BASE_TABLE.' b on a.order_id=b.order_id '.$where.' group by a.order_id) c';

        $db = new YFSQL;
        $num_row = $db->find_one($sql_count);
   		$num = $num_row['num'] ? : 0;//总数 
        return $num;
    }
    
    
}

