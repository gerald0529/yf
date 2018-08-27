<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class ManSong_BaseModel extends ManSong_Base
{
	// 活动状态 1-正常/2-已结束/3-管理员关闭)
	const NORMAL = 1;
	const END    = 2;
	const CANCEL = 3;

	public static $manSongStateMap = array(
		self::NORMAL => '正常',
		self::END => '已结束',
		self::CANCEL => '管理员关闭'
	);

	public $ManSong_RuleModel = null;
	public $Goods_BaseModel   = null;

	public function __construct()
	{
		parent::__construct();
		$this->ManSong_RuleModel = new ManSong_RuleModel();
		$this->Goods_BaseModel   = new Goods_BaseModel();
	}

	public function getManSongActList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);
		if ($rows['items'])
		{
			$expire_mansong_id = array();
			foreach ($rows['items'] as $key => $value)
			{
				$rows['items'][$key]['mansong_state_label'] = __(self::$manSongStateMap[$value['mansong_state']]);
				if (time() > strtotime($value['mansong_end_time']))  //活动到期
				{
					$expire_mansong_id[]                        = $value['mansong_id'];
					$rows['items'][$key]['mansong_state']       = self::END;
					$rows['items'][$key]['mansong_state_label'] = __(self::$manSongStateMap[self::END]);
				}
			}
			if ($expire_mansong_id)
			{
				$field_row['mansong_state'] = self::END;
				$this->editManSong($expire_mansong_id, $field_row);
			}
		}

		return $rows;
	}

	public function getManSongByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

    //多条件获取满送活动详情
	public function getManSongActItem($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		if ($row)
		{
			$row['mansong_state_label'] = __(self::$manSongStateMap[$row['mansong_state']]);
			$rule                       = $this->ManSong_RuleModel->getManSongRuleByWhere(array('mansong_id' => $row['mansong_id']), array('rule_price' => 'ASC'));
			$row['rule']                = array_values($rule);
			if (time() > strtotime($row['mansong_end_time']))
			{
				$row['mansong_state']       = $field_row['mansong_state'] = self::END;
				$row['mansong_state_label'] = __(self::$manSongStateMap[self::END]);
				$this->editManSong($row['mansong_id'], $field_row);
			}

			if ($row['rule'])
			{
				foreach ($row['rule'] as $key => $value)
				{
					$rule_goods = array();

					if ($value['goods_id'])
					{
						$rule_goods = $this->Goods_BaseModel->getGoodsDetailByGoodId($value['goods_id']);
					}

					if ($rule_goods)
					{
						$row['rule'][$key]['goods_name']  = $rule_goods['goods_name'];
						$row['rule'][$key]['goods_price'] = $rule_goods['goods_price'];
						$row['rule'][$key]['goods_image'] = $rule_goods['goods_image'];
					}
					else
					{
						$row['rule'][$key]['goods_name']  = '';
						$row['rule'][$key]['goods_price'] = '';
						$row['rule'][$key]['goods_image'] = '';
					}
				}
			}
		}

		return $row;
	}

	//根据主键ID获取活动信息
	public function getManSongByID($mansong_id)
	{
		$row = $this->getOne($mansong_id);
		if ($row)
		{
			$row['mansong_state_label'] = __(self::$manSongStateMap[$row['mansong_state']]);
		}
		return $row;
	}

	public function addManSongAct($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	public function editManSong($mansong_id, $field_row)
	{
		$this->edit($mansong_id, $field_row);
	}

	public function removeManSongActItem($mansong_id)
	{
		$rs_row = array();

		$cond_row['mansong_id'] = $mansong_id;
		$mansong_rule_id_row    = $this->ManSong_RuleModel->getKeyByWhere($cond_row);

		if ($mansong_rule_id_row)
		{
			$remove_flag = $this->ManSong_RuleModel->removeManSongRule($mansong_rule_id_row); //删除满送规则
			check_rs($remove_flag, $rs_row);
		}

		$del_flag = $this->remove($mansong_id); //删除满送活动
		check_rs($del_flag, $rs_row);

		return is_ok($rs_row);
	}
}