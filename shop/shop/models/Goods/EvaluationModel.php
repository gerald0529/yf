<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Goods_EvaluationModel extends Goods_Evaluation
{
	const DISPLAY = 0;
	const SHOW    = 1;
	const SETTOP  = 2;

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getEvaluationList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100, $type = 'all')
	{
		$data = $this->getByWhere($cond_row, $order_row);
		
		$res = array();
		foreach ($data as $key => $val)
		{
			if (!isset($res[$val['order_id'] . $val['goods_id']]))
			{
				$res[$val['order_id'] . $val['goods_id']] = array();
			}
			$val['image_row'] = explode(',', $val['image']);
			$val['image_row'] = array_filter($val['image_row']);
			if (isset($res[$val['order_id'] . $val['goods_id']]))
			{
				//计算初次评价与追加评价之间的时间差
				if ((strtotime($val['create_time']) - strtotime($res[$val['order_id'] . $val['goods_id']][0]['create_time'])) > 86400)
				{
					$val['diff_date'] = ceil((strtotime($val['create_time']) - strtotime($res[$val['order_id'] . $val['goods_id']][0]['create_time'])) / 86400);
				}
				else
				{
					$val['diff_date'] = 0;
				}

			}


			array_unshift($res[$val['order_id'] . $val['goods_id']], $val);
			//$res[$val['order_id'] . $val['goods_id']][] = $val;
		}
		
		$Order_BaseModel = new Order_BaseModel();
		$Goods_BaseModel = new Goods_BaseModel();
		$User_InfoModel  = new User_InfoModel();
		$User_GradeModel = new User_GradeModel();
		foreach ($res as $key => $val)
		{
			$val = $val[0];
			//用户姓名是否匿名显示
			if ($val['isanonymous'] == 1)
			{
				$res[$key][0]['user_name'] = substr($val['member_name'], 0, 1) . '***' . substr($val['member_name'], -1);
			}
			else
			{
				$res[$key][0]['user_name'] = $val['member_name'];
			}

			//根据订单号查找支付时间时间
			$order = $Order_BaseModel->getOne($val['order_id']);
			if ($order)
			{
				$order_payed_time          = $order['payment_time'];
				$diff_time                 = ceil((strtotime($val['create_time']) - strtotime($order_payed_time)) / 86400) - 1;
				$res[$key][0]['diff_time'] = $diff_time;

				//获取商品信息
				$goods = $Goods_BaseModel->getGoodsInfo($val['goods_id']);
				if ($goods['goods_base']['goods_spec'])
				{
					$spec                       = current($goods['goods_base']['goods_spec']);
					$res[$key][0]['goods_spec'] = $spec;
					$res[$key][0]['goods_spec_str'] = $goods['goods_base']['spec_str'];
				}
				else
				{
					$res[$key][0]['goods_spec'] = array();
					$res[$key][0]['goods_spec_str'] = "";
				}

				//评价用户信息
				$user = $User_InfoModel->getOne($val['user_id']);
				if ($user)
				{
					$user_grate                      = $User_GradeModel->getOne($user['user_grade']);
					$res[$key][0]['user_grade_logo'] = $user_grate['user_grade_logo'];
					$res[$key][0]['user_grade_name'] = $user_grate['user_grade_name'];
					$res[$key][0]['user_logo']		   = $user['user_logo'];
				}
				else
				{
					unset($res[$key]);
				}

			}
			else
			{
				unset($res[$key]);
			}

		}

		$resl = array();

		if ($type == 'image')
		{
			foreach ($res as $key => $val)
			{
				if ($val[0]['image'] != '' || (isset($val[1]) && $val[1]['image'] != ''))
				{
					$resl[$key] = $val;
				}
			}
		}
		if ($type == 'good')
		{
			foreach ($res as $key => $val)
			{
				if ($val[0]['scores'] >= 4)
				{
					$resl[$key] = $val;
				}
			}
		}
		if ($type == 'middle')
		{
			foreach ($res as $key => $val)
			{
				if ($val[0]['scores'] == 2 || $val[0]['scores'] == 3)
				{
					$resl[$key] = $val;
				}
			}
		}
		if ($type == 'bad')
		{
			foreach ($res as $key => $val)
			{
				if ($val[0]['scores'] == 1)
				{
					$resl[$key] = $val;
				}
			}
		}

		if ($type != 'all')
		{
			$res = $resl;
		}

		$res = array_values($res);

		$total = ceil_r(count($res) / $rows);

		$start = ($page - 1) * $rows;

		$data_rows = array_slice($res, $start, $rows, true);



		$arr              = array();
		$arr['page']      = $page;
		$arr['total']     = $total;  //total page
		$arr['totalsize'] = count($res);
		$arr['records']   = count($data_rows);
		$arr['items']     = $data_rows;

		fb('评论');
		fb($arr);
		return $arr;
	}

	/**
	 * 计算商品评论数
	 *
	 * @author Zhuyt
	 */
	public function getEvaluationByUser($cond_row = array(), $order_row = array(), $page = 1, $rows = 1)
	{

		$data = $this->getByWhere($cond_row, $order_row);
		$res  = array();

		$User_InfoModel = new User_InfoModel();
		foreach ($data as $key => $val)
		{
			$val['image_row'] = array_filter(explode(',',$val['image']));

			//评价用户信息
			$user = $User_InfoModel->getOne($val['user_id']);
			if ($user)
			{
				$val['user_logo']		   = $user['user_logo'];
			}
			else
			{
				unset($res[$key]);
			}

			if (isset($res[$val['order_id'] . $val['goods_id']]))
			{
				//计算初次评价与追加评价之间的时间差
				if ((strtotime($val['create_time']) - strtotime($res[$val['order_id'] . $val['goods_id']][0]['create_time'])) > 86400)
				{
					$val['diff_date'] = ceil((strtotime($val['create_time']) - strtotime($res[$val['order_id'] . $val['goods_id']][0]['create_time'])) / 86400);
				}
				else
				{
					$val['diff_date'] = 0;
				}

			}
			$res[$val['order_id'] . $val['goods_id']][] = $val;
		}

		$res = array_values($res);
		krsort($res);
		fb($res);

		$total = ceil_r(count($res) / $rows);

		$start = ($page - 1) * $rows;

		$data_rows = array_slice($res, $start, $rows, true);

		$arr              = array();
		$arr['page']      = $page;
		$arr['total']     = $total;  //total page
		$arr['totalsize'] = count($res);
		$arr['records']   = count($data_rows);
		$arr['items']     = array_values($data_rows);
		return $arr;

	}

	/**
	 * 计算商品common评论数
	 *
	 * @author Zhuyt
	 */
	public function countEvaluation($common_id, $status = null)
	{
		if ($status == 'image')
		{
			$cond_row['image:!='] = '';
		}
		if ($status == 'good')
		{
			$cond_row['scores:IN'] = array(
				'4',
				'5'
			);
		}
		if ($status == 'middle')
		{
			$cond_row['scores:IN'] = array(
				'3',
				'2'
			);
		}
		if ($status == 'bad')
		{
			$cond_row['scores'] = '1';
		}

		$cond_row['common_id']  = $common_id;
		$cond_row['status:!='] = Goods_EvaluationModel::DISPLAY;
		$data                  = $this->getByWhere($cond_row);

		$Order_BaseModel = new Order_BaseModel();

		if ($data)
		{
			$order_row = array();
			foreach ($data as $key => $val)
			{
				//查找订单信息
				$order = $Order_BaseModel->getOne($val['order_id']);
				if (!$order)
				{
					unset($data[$key]);
				}
				else
				{
					//去除追加的评论数
					if (in_array($val['order_id'], $order_row))
					{
						unset($data[$key]);
					}
					else
					{
						$order_row[] = $val['order_id'];
					}
				}

			}

		}
		$num = count($data);

		fb($data);
		fb($status);
		fb($num);
		fb('评价数量');

		return $num;
	}

	/**
	 * 计算商品goods评论数
	 *
	 * @author Zhuyt
	 */
	public function countGoodsEvaluation($goods_id)
	{
		$cond_row['goods_id']  = $goods_id;
		$cond_row['status:!='] = Goods_EvaluationModel::DISPLAY;
		$cond_row['scores:!='] = 0;
		$data                  = $this->getByWhere($cond_row);

		$Order_BaseModel = new Order_BaseModel();

		if ($data)
		{
			$order_row = array();
			foreach ($data as $key => $val)
			{
				//查找订单信息
				$order = $Order_BaseModel->getOne($val['order_id']);
				if (!$order)
				{
					unset($data[$key]);
				}
				else
				{
					//去除追加的评论数
					if (in_array($val['order_id'], $order_row))
					{
						unset($data[$key]);
					}
					else
					{
						$order_row[] = $val['order_id'];
					}
				}

			}

		}
		$num = count($data);

		return $num;
	}
    /**
     * 获取某个时间段的浏览量
     * @param $cond_row
     * @return mixed
     */
    public function  getGoodsEvalution($cond_row){
        $where = 'where goods_id='.$cond_row['goods_id'].'  and create_time>="'.$cond_row['stime'].' 00:00:00" and create_time<="'.$cond_row['etime'].' 23:59:59"';
        $sql = " SELECT FROM_UNIXTIME(unix_timestamp(create_time),'%Y-%m-%d') as time,1 as apd_score  from `yf_goods_evaluation` {$where}";
        $goods_evalution_list = $this->sql->getAll($sql);
        return $goods_evalution_list;
    }

}

?>