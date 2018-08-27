<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GradeModel extends User_Grade
{

	/**
	 * 读取等级列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGradeList($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 读取等级信息
	 *
	 * @param  array $grade_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserGrade($grade_row = array())
	{
		return $this->getOneByWhere($grade_row);
		

	}
	
	/**
	 * 获取会员期限--没有使用
	 *
	 * @param  array $data 会员的信息
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getUserExpire($data)
	{
		
		if ($data['user_grade_valid'] > 0)
		{
			$time           = strtotime($data['user_grade_time']);
			$data['expire'] = date("Y-m-d H:i:s", $time + 60 * 60 * 24 * 365 * $data['user_grade_valid']);
		}
		
		return $data;
		
	}
	
	/**
	 * 获取下一个等级
	 *
	 * @param  array $data会员等级信息, $gradeList等级列表, $re会员信息  查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getGradeGrowth($data, $gradeList, $re)
	{
		
		foreach ($gradeList as $val)
		{
			if ($val['id'] == ($re['user_grade'] + 1))
			{
				$data['next']   = $val['user_grade_name'];
				$data['growth'] = $val['user_grade_demand'] - $re['user_growth'];
			}
		}
		
		return $data;
		
	}

	/**
	 * 判断升级
	 * @param  int $user_id会员的id $user_growth会员现在的经验值  查询条件
	 * @return array $flag 升级成功返回的状态
	 * @param $grade_log_id
	 *
	 */
	public function upGrade($user_id, $user_growth)
	{
		$User_InfoModel = new User_InfoModel();
		
		$user = $User_InfoModel->getInfo($user_id);
		//当前等级的下个等级
		$user_grade = $user[$user_id]['user_grade'] * 1 + 1;

		$Grade = $this->getGrade($user_grade);
		//获取此等级经验值
		$grade_le = $Grade [$user_grade]['user_grade_demand'] * 1;
		
		if ($user_growth > $grade_le)
		{ //传过的当前经验值大于下个等级经验值升级
			
			$cond_row['user_grade'] = $user_grade;
			$flag                   = $User_InfoModel->editInfo($user_id, $cond_row);
			return $flag;
		}


	}

	//获取当前用户等级对应的折扣率
	public function getGradeRate($user_grade)
	{
		//获取用户等级表所有数据
		$user_grade_info = $this->getByWhere();
		//取出不同等级条件组成新数组
		$user_grade_demand_row = array_column($user_grade_info, 'user_grade_demand');
		$num = 0;
		//循环比较当前用户符合哪个等级
		foreach($user_grade_demand_row as $key=>$val)
		{
			if($user_grade > $key)
			{
				$num = $key;
				continue;
			}
			elseif($user_grade == $key)
			{
				$num = $key;
				break;
			}
			else
			{
				break;
			}
		}
		return $this->getOneByWhere(['user_grade_id'=>$num]);
	}

	public function getUserRate($user_grade)
	{
		$grade_info = $this->getOne($user_grade);

		return $grade_info;

	}
}

?>