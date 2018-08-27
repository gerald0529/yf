<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Voucher_BaseModel extends Voucher_Base
{
	const UNUSED  = 1;   //未使用
	const USED    = 2;     //已使用
	const EXPIRED = 3;  //过期
	const RECOVER = 4;  //收回

	public static $voucherState = array(
		self::UNUSED => "未用",
		self::USED => "已用",
		self::EXPIRED => "过期",
		self::RECOVER => "收回"
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getVoucherList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
        $expire_row = array();
		foreach ($data["items"] as $key => $value)
		{
			$data["items"][$key]["voucher_state_label"] = __(self::$voucherState[$value["voucher_state"]]);

            if (strtotime($value['voucher_end_date']) < time())
            {
                $data['items'][$key]['voucher_state']        = self::EXPIRED;
                $data['items'][$key]['voucher_state_label'] = __(self::$voucherState[self::EXPIRED]);
                $expire_row[]                                   = $value['voucher_id'];
            }
		}

        $this->editVoucher($expire_row, array('voucher_state'=>self::EXPIRED));

		return $data;
	}

	//获取用户所有的代金券数量
	public function getAllVoucherCountByUserId($user_id)
	{
		$cond_row['voucher_owner_id'] = $user_id;
		return $this->getNum($cond_row);
	}

	//获取用户可用的代金券数量
	public function getAvaVoucherCountByUserId($user_id)
	{
		$cond_row['voucher_owner_id']      = $user_id;
		$cond_row['voucher_start_date:<='] = get_date_time();
		$cond_row['voucher_end_date:>=']   = get_date_time();
		$cond_row['voucher_state']         = self::UNUSED;
		return $this->getNum($cond_row);
	}

	public function getVoucherNumByWhere($cond_row)
	{
		return count($this->getByWhere($cond_row));
	}

	/**
	 * 读取列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	//获取用户可使用的店铺代金券
	public function getUserOrderVoucherByWhere($user_id, $shop_id, $order_price=null)
	{
		$cond_row                     = array();
		$cond_row['voucher_owner_id'] = $user_id;
		$cond_row['voucher_shop_id']  = $shop_id;
		$cond_row['voucher_state']    = self::UNUSED;
		if($order_price)
		{
			$cond_row['voucher_limit:<='] = $order_price;
		}


		$order_row['voucher_id'] = 'DESC';
		$rows                    = $this->getByWhere($cond_row, $order_row);
		if ($rows)
		{
			$expire_voucher = array();
			foreach ($rows as $key => $value)
			{
				if (strtotime($value['voucher_end_date']) < get_date_time())
				{
					$expire_voucher[] = $value['voucher_id'];
					unset($rows[$key]); //过期的代金券
				}
			}

			$this->editVoucher($expire_voucher, array('voucher_state' => self::EXPIRED));
		}
		return $rows;
	}

	//代金券使用后，更改状态
	public function changeVoucherState($voucher_id, $order_id)
	{
		$rs_row = array();

		$field_row['voucher_order_id'] = $order_id;
		$field_row['voucher_state']    = Voucher_BaseModel::USED;
		$update_flag                   = $this->editVoucher($voucher_id, $field_row);
		check_rs($update_flag, $rs_row);

		$voucher_row = $this->getOne($voucher_id);

		if ($voucher_row) //更新代金券模板中代金券已使用数量
		{
			$Voucher_TempModel = new Voucher_TempModel();
			$edit_flag         = $Voucher_TempModel->editVoucherTemplate($voucher_row['voucher_t_id'], array('voucher_t_used' => 1), true);
			check_rs($edit_flag, $rs_row);
		}

		return is_ok($rs_row);
	}



    /**
     * 获取一个用户的代金券
     * @param type $cond_row
     * @param type $order_row
     * @param type $page
     * @param type $rows
     * @return type
     */
    public function getUserVoucherList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100){
        //先将过期的优惠券更新为过期状态
        $where = array(
            'voucher_owner_id' => $cond_row['voucher_owner_id'],
            'voucher_end_date:<' => date('Y-m-d H:i:s'),
			'voucher_state:!=' => Voucher_BaseModel::RECOVER,
        );
        $ids = $this->getId($where);
        $this->editVoucher($ids,array('voucher_state'=>self::EXPIRED));
        
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);
        return $data;
		
    }
    
    /**
     * 获取代金券的模板id
     * @param type $cond_row
     * @return array
     */
    public function getVoucherTplId($cond_row = array()){
        $list = $this->getByWhere($cond_row);
        $tpl_ids = array();
        if(!$list){
            return $tpl_ids;
        }
        foreach ($list as $value){
            $tpl_ids[] = $value['voucher_t_id'];
        }
        return array_unique($tpl_ids);
		
    }
    
    /**
     * 获取代金券的模板内容和数量
     * @param type $cond_row
     * @return array
     */
    public function getVoucherTplCount($cond_row = array()){
        $list = $this->getByWhere($cond_row);
        $tpl_ids = array();
        if(!$list){
            return $tpl_ids;
        }
        $result_list = array();
        foreach ($list as $key => $value){
            if(isset($result_list[$value['voucher_t_id']])){
                $result_list[$value['voucher_t_id']]['voucher_count'] ++;
                continue;
            }else{
                $result_list[$value['voucher_t_id']] = $value;
                $result_list[$value['voucher_t_id']]['voucher_count'] = 1;
            }
        }
        return $result_list;
    }

	/**
	 * 重组确认订单页中代金券信息
	 * @param $voucher_id
	 * @return array
	 */
	public function reformVoucher($voucher_id)
	{
		$voucher_cond_row['voucher_id:IN'] = $voucher_id;
		$voucher_row                       = $this->getByWhere($voucher_cond_row);

		$shop_voucher_row = array();
		foreach ($voucher_row as $voukey => $vouval)
		{
			$shop_voucher_row[$vouval['voucher_shop_id']] = $vouval;
		}

		return $shop_voucher_row;
	}
}

?>