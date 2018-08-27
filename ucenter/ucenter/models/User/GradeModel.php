<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GradeModel extends User_Grade
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGradeList($cond_row=array(), $order_row=array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}
	/**
	 * 读取会员信息
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserGrade($grade_row = array())
	{
		return $this->getOneByWhere($grade_row);
		

	}
	
	//获取会员期限
	public function getUserExpire($data)
	{
		
		if($data['user_grade_valid']>0)
		{
			$time = strtotime($data['user_grade_time']);
			$data['expire'] = date("Y-m-d H:i:s",$time+ 60*60*24*365*$data['user_grade_valid']);
		}
		
		return $data;
		
	}
	
	//获取下一个等级
	public function getGradeGrowth($data,$gradeList,$re)
	{
		
		foreach($gradeList as $val)
		{
			if($val['id']==($re['user_grade']+1))	
			{
				$data['next'] = $val['user_grade_name'];
				$data['growth'] = $val['user_grade_demand']-$re['user_growth'];
			}
		}
		
		return $data;
		
	}
	/**
     * 判断升级
     * @param $grade_log_id
     * 
     */
    public function upGrade($user_id,$user_growth)
    {
		$User_InfoModel = new User_InfoModel();
		
		$user = $User_InfoModel->getInfo($user_id);
		//当前等级的下个等级
		$user_grade = $user[$user_id]['user_grade']+1;

		$Grade = $this->getGrade($user_grade);
		//获取此等级经验值
		$grade_le = $Grade [$user_grade]['user_grade_demand'];
		
		if($user_growth>$grade_le ){ //传过的当前经验值大于下个等级经验值升级
			
			$user_grade = $user[$user_id]['user_grade'] + 1;
			$cond_row['user_grade'] = $user_grade;
			$flag = $User_InfoModel->editInfo($user_id,$cond_row);
			return $flag;
		}

		
    }
	
	
}
?>