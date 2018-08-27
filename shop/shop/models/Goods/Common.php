<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Yf <service@yuanfeng.cn>
 * @copyright  Copyright (c) 2016远丰仁商
 * @version    1.0
 * @todo
 */
class Goods_Common extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|goods_common|';
	public $_cacheName       = 'goods';
	public $_tableName       = 'goods_common';
	public $_tablePrimaryKey = 'common_id';
	public $jsonKey = array(
		'common_spec_value',
		'common_spec_name',
		'common_property',
		'shop_goods_cat_id',
		'goods_id',
		'common_location'
	);

	public $goodsPropertyIndexModel;

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);

		$this->goodsPropertyIndexModel = new Goods_PropertyIndexModel();
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommon($common_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($common_id, $sort_key_row);

		return $rows;
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addCommon($field_row, $return_insert_id = true)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		if ($add_flag && !empty($field_row['common_property']))
		{
			foreach ($field_row['common_property'] as $key => $val)
			{
				$property_value = $val[1];

				if (!empty($property_value))
				{
					$property_id = str_replace('property_', '', $key);

					$update_pro_index['common_id']         = $add_flag;
					$update_pro_index['property_id']       = $property_id;
					$update_pro_index['property_value_id'] = $property_value;

					$flag = $this->goodsPropertyIndexModel->addPropertyIndex($update_pro_index, true);
				}
			}
		}

		//$this->removeKey($common_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $common_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editCommon($common_id = null, $field_row, $flag = false)
	{
		$update_flag = $this->edit($common_id, $field_row, $flag);

		return $update_flag;
	}

	public function editCommonTrue($common_id = null, $field_row)
	{
		$update_flag = $this->edit($common_id, $field_row, true);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $common_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editCommonSingleField($common_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($common_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作 - //处理业务逻辑, 必须将自选删除
	 * @param int $common_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeCommon($common_id)
	{
		$rs_row = array();

		//删除SKU商品
		$Goods_BaseModel = new Goods_BaseModel();

		$goods_id_row = $Goods_BaseModel->getGoodsIdByCommonId($common_id);

		if ($goods_id_row)
		{
			$num = $Goods_BaseModel->removeBase($goods_id_row);

			check_rs($num, $rs_row);
		}

		//删除商品本身

		$del_flag = $this->remove($common_id);
        $this->sql->setWhere('common_id',$common_id,'IN');//$this->$this->remove($goods_id);
        //修改内容
        $set = ['is_del' => Goods_CommonModel::IS_DEL_YES];
        $del_flag = $this->_update($set);
        foreach ($common_id as $key =>$value){
            $this->removeCache($value);
        }
        check_rs(true, $rs_row);
		//$this->removeKey($common_id);
		return is_ok($rs_row);
	}

	//推荐商品
	public function getRecommonRow($data_recommon)
	{
		$Goods_CommonModel = new Goods_CommonModel();
		$items             = array();
		if (!empty($data_recommon['items']))
		{
			$items = $data_recommon['items'];
			foreach ($items as $key => $value)
			{
				$goods_id = '';
				$goods_id = $Goods_CommonModel->getGoodsId($value['common_id']);
				if ($goods_id)
				{
					$items[$key]['goods_id'] = $goods_id;
				}
				else
				{
					/*unset($items[$key]);*/
				}
			}
		}
		return $items;
	}

	//根据common_id 取任意对应goods_id
	public function getGoodsId($common_id)
	{
		$Goods_BaseModel = new Goods_BaseModel();
		$data            = $Goods_BaseModel->getByWhere(array(
															'common_id' => $common_id,
															'goods_is_shelves' => $Goods_BaseModel::GOODS_UP,
                                                            'is_del' => $Goods_BaseModel::IS_DEL_NO,
														));
		$data_goods      = current($data);
		$id              = $data_goods['goods_id'];
		return $id;
	}

	//根据common_id 判断goods_id是否参加拼团
	public function getGoodsIds($common_id)
	{
		$Goods_BaseModel = new Goods_BaseModel();
		$PinTuan_BaseModel = new PinTuan_Base();
		$data = $Goods_BaseModel->getByWhere(array(
												'common_id:IN' => $common_id,
												'goods_is_shelves' => $Goods_BaseModel::GOODS_UP

											));
		$result = array();
		foreach($data as $k=>$v){
			$flag = $PinTuan_BaseModel->isPinTuanGoods($k);
			if($flag)
			{
				$result[$k] = $v['common_id'];
			}
		}
		return $result;
	}

	/**
	 * 获取商品数目
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommonStateNum($shop_id, $state = Goods_CommonModel::GOODS_STATE_NORMAL,$verfiy = Goods_CommonModel::GOODS_VERIFY_ALLOW)
	{
		/*
		Goods_CommonModel::GOODS_STATE_NORMAL;
		Goods_CommonModel::GOODS_STATE_OFFLINE;
		Goods_CommonModel::GOODS_STATE_ILLEGAL;

		Goods_CommonModel::GOODS_VERIFY_WAITING;//待审核
		*/
		$row                 = array();
		$row['shop_id']      = $shop_id;

		if (-1 != $state)
		{
			$row['common_state'] = $state;
		}
		
		if(-1 != $verfiy)
		{
			$row['common_verify'] = $verfiy;
		}

		$num = $this->getNum($row);

		return $num;
	}

	/**
	 * 获取商品数目
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommonVerifyNum($shop_id, $state = Goods_CommonModel::GOODS_VERIFY_WAITING)
	{
		/*
		Goods_CommonModel::GOODS_STATE_NORMAL;
		Goods_CommonModel::GOODS_STATE_OFFLINE;
		Goods_CommonModel::GOODS_STATE_ILLEGAL;

		Goods_CommonModel::GOODS_VERIFY_WAITING;//待审核
		*/
		$row                  = array();
		$row['shop_id']       = $shop_id;
		$row['common_verify'] = $state;

		$num = $this->getNum($row);

		return $num;
	}
    
    /**
     *  根据店铺修改商品属性
     */
    public function updateCommonByShopId($shop_id,$set=array()){
        if(!$set || !$shop_id){
            return false;
        }
        $this->sql->setWhere('shop_id', $shop_id);
        $result = $this->_update($set);
        return $result;
    }
    
    /**
     * 将一个字段的值复制到另一个字段
     * @param type $original_column
     * @param type $new_column
     * @return string
     */
    public function updateColumnToColumn($original_column,$new_column){
        $sql = 'UPDATE '.$this->_tableName.' t1,'.$this->_tableName.' t2 SET t1.'.$new_column.'=t2.'.$original_column.' WHERE t1.'.$this->_tablePrimaryKey.'=t2.'.$this->_tablePrimaryKey;
        $result = $this->sql->exec($sql);
        return $result;
    }
    
    /**
     * 获取数量
     * @param type $cond_row
     * @return type
     */
    public function getCount($cond_row = array())
    {
        return $this->getNum($cond_row);
    }
}

?>