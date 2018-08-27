<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Voucher_TempModel extends Voucher_Temp
{
	const VALID   = 1;//有效
	const INVALID = 2;//失效

	const GETBYPOINTS = 1;//积分兑换
	const GETFBYPWD   = 2;//卡密
	const GETFREE     = 3;//免费领取

	const UNRECOMMEND = 0; //不推荐
	const RECOMMEND   = 1;  //推荐


	//代金券模板状态
	public static $voucher_state_map = array(
		self::VALID => '有效',
		self::INVALID => '失效'
	);
	//代金券获取方式
	public static $voucher_access_method_map = array(
		self::GETBYPOINTS => '积分兑换',
		self::GETFREE => '免费领取'
	);//2=>'卡密兑换'保留
	//代金券推荐状态
	public static $voucher_recommend_map = array(
		self::UNRECOMMEND => '否',
		self::RECOMMEND => '是'
	);

	public function getVoucherTempList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$Shop_ClassModel = new Shop_ClassModel();
		$shop_cat_row    = $Shop_ClassModel->getClassWhere();

		$expire_row = array();
		foreach ($rows['items'] as $key => $value)
		{
			$rows['items'][$key]['voucher_t_state_label']         = __(self::$voucher_state_map[$value['voucher_t_state']]);
			$rows['items'][$key]['voucher_t_access_method_label'] = __(self::$voucher_access_method_map[$value['voucher_t_access_method']]);
			$rows['items'][$key]['voucher_t_recommend_label']     = __(self::$voucher_recommend_map[$value['voucher_t_recommend']]);
			$rows['items'][$key]['voucher_t_end_date_day']        = date('Y-m-d', strtotime($value['voucher_t_end_date']));
			$rows['items'][$key]['voucher_t_end_date']        = date('Y-m-d', strtotime($value['voucher_t_end_date']) + 1);
			$rows['items'][$key]['voucher_t_limit']        = $rows['items'][$key]['voucher_t_limit'];
			//会员等级信息
			$user_grade = new User_GradeModel;
			$user_cond['user_grade_id'] = $value['voucher_t_user_grade_limit'];
			$rows['items'][$key]['user_grade'] = $user_grade->getUserGrade($user_cond);

			if (in_array($value['shop_class_id'], array_keys($shop_cat_row)))
			{
				$rows['items'][$key]['voucher_t_cat_name'] = $shop_cat_row[$value['shop_class_id']]['shop_class_name'];
			}
			else
			{
				$rows['items'][$key]['voucher_t_cat_name'] = '';
			}
			if (strtotime($value['voucher_t_end_date']) < time())
			{
				$rows['items'][$key]['voucher_t_state']       = self::INVALID;
				$rows['items'][$key]['voucher_t_state_label'] = __(self::$voucher_state_map[self::INVALID]);
				$expire_row[]                                 = $value['voucher_t_id'];
			}
		}

		$field_row['voucher_t_state'] = self::INVALID;
		$this->editVoucherTemp($expire_row, $field_row);

		return $rows;
	}

    //根据主键获取代金券模板详细信息
    public function getVoucherTempInfoById($voucher_t_id)
    {
        $row = $this->getOne($voucher_t_id);
        if ($row)
        {
            $row['voucher_t_access_method_label'] = __(self::$voucher_access_method_map[$row['voucher_t_access_method']]);
            $row['voucher_t_state_label']         = __(self::$voucher_state_map[$row['voucher_t_state']]);
            $row['voucher_t_recommend_label']     = __(self::$voucher_recommend_map[$row['voucher_t_recommend']]);

			//查找领取会员登录
			$User_GradeModel = new User_GradeModel();
			$user_grade = $User_GradeModel->getOne($row['voucher_t_user_grade_limit']);
			$row['voucher_t_user_grade_limit_con'] = $user_grade['user_grade_name'];

            if($row['shop_class_id'])
            {
                $Shop_ClassModel = new Shop_ClassModel();
                $shop_cat_row    = $Shop_ClassModel->getOne($row['shop_class_id']);
                $row['voucher_t_cat_name'] = $shop_cat_row['shop_class_name'];
            }
            else
            {
                $row['voucher_t_cat_name'] = '';
            }
        }
        return $row;
    }

    //多条件获取代金券模板的详细信息
	public function getVoucherTempInfoByWhere($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		if ($row)
		{
			$row['voucher_t_access_method_label'] = __(self::$voucher_access_method_map[$row['voucher_t_access_method']]);
			$row['voucher_t_state_label']         = __(self::$voucher_state_map[$row['voucher_t_state']]);
			$row['voucher_t_recommend_label']     = __(self::$voucher_recommend_map[$row['voucher_t_recommend']]);
		}
		return $row;
	}

	/**
	 * 插入
	 * @param array $field_row_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addVoucherTemp($field_row, $return_insert_id)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		return $add_flag;
	}

	public function removeVoucherTemp($voucher_t_id)
	{
		return $this->remove($voucher_t_id);
	}

	public function editVoucherTemp($voucher_t_id, $field_row)
	{
		$update_flag = $this->edit($voucher_t_id, $field_row);

		return $update_flag;
	}

	public function editVoucherTemplate($voucher_t_id, $field_row, $flag)
	{
		$update_flag = $this->edit($voucher_t_id, $field_row, $flag);
		return $update_flag;
	}
	
	public function getVoucherTempNumByWhere($cond_row)
	{
		return $this->getNum($cond_row);
	}
  
    
    /**
     * 获取店铺的可用优惠券
     * @param type $shop_id
     * @return type
     */
    public function getShopVoucher($shop_id){
        $cond_row = array(
            'shop_id' => $shop_id,
            'voucher_t_start_date:<' => date('Y-m-d H:i:s'),
            'voucher_t_end_date:>' => date('Y-m-d H:i:s'),
            'voucher_t_state' => Voucher_TempModel::VALID
        );
        $order_row['voucher_t_price'] = 'ASC';
        $order_row['voucher_t_limit'] = 'ASC';
        $order_row['voucher_t_end_date'] = 'DESC';
        $order_row['voucher_t_add_date'] = 'ASC';

        $result = $this->listByWhere($cond_row,$order_row);
        if($result['items']){
            foreach ($result['items'] as $key => $value){
                $result['items'][$key]['voucher_t_state_label']         = __(self::$voucher_state_map[$value['voucher_t_state']]);
                $result['items'][$key]['voucher_t_access_method_label'] = __(self::$voucher_access_method_map[$value['voucher_t_access_method']]);
                $result['items'][$key]['voucher_t_recommend_label']     = __(self::$voucher_recommend_map[$value['voucher_t_recommend']]);
                $result['items'][$key]['voucher_t_end_date_day']        = date('Y-m-d', strtotime($value['voucher_t_end_date']));
                $result['items'][$key]['voucher_t_limit']        = intval($value['voucher_t_limit']);

            }
            if(Perm::checkUserPerm()){
                $user_id = Perm::$userId;
                $voucher_base_model = new Voucher_BaseModel();
                $where = array();
                $where['voucher_owner_id'] = $user_id;
                $where['voucher_shop_id'] = $shop_id;
                $my_voucher = $voucher_base_model->getVoucherTplCount($where);
                foreach ($result['items'] as $k => $val){
                    $result['items'][$k]['is_get'] = isset($my_voucher[$val['voucher_t_id']]) &&  $my_voucher[$val['voucher_t_id']]['voucher_count'] >= $val['voucher_t_eachlimit']  && $val['voucher_t_eachlimit'] > 0 ? 1 : 0;
                }
            }
            
        }
        return $result;
    }
    
   
}

?>