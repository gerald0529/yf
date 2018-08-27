<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_PointsCtl extends Yf_AppController
{
	public $pointsLogModel    = null;
	public $userInfoModel     = null;
	public $userResourceModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		
		$this->pointsLogModel    = new Points_LogModel();
		$this->userInfoModel     = new User_InfoModel();
		$this->userResourceModel = new User_ResourceModel();
	}
	
	/**
	 *获取积分日志
	 *
	 * @access public
	 */
	public function getPointsList()
	{
		
		$page = request_int('page');
		$rows = request_int('rows');
		$type = request_string('user_type');
		$name = request_string('search_name');
		
		$cond_row = array();
		$sort     = array();
		
		
		if ($name)
		{
			if ($type == 1)
			{
				$cond_row['user_id'] = $name;
			}
			else
			{
				$type            = 'user_name:LIKE';
				$cond_row[$type] = '%' . $name . '%';
			}
			
		}
		
		$data = $this->pointsLogModel->getPointsLogList($cond_row, $sort, $page, $rows);
       
		$this->data->addBody(-140, $data);

	}
	
	/**
	 *增加减少积分页面
	 *
	 * @access public
	 */
	public function addPoints()
	{

		$this->data->addBody(-140, array());
	}
	
	/**
	 *增加减少积分页面,判断用户
	 *
	 * @access public
	 */
	public function getPoint()
	{
		$web                    = array();
		$web['points_reg']      = Web_ConfigModel::value("points_reg");//注册获取积分
		$web['points_login']    = Web_ConfigModel::value("points_login");//登陆获取积分
		$web['points_evaluate'] = Web_ConfigModel::value("points_evaluate");//评论获取积分
		$web['points_recharge'] = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
		$web['points_order']    = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

		$this->data->addBody(-140, $web);
	}
	public function getPoints()
	{
		$user_name             = request_string('user_name');
		$cond_row['user_name'] = $user_name;
		
		$data = $this->userInfoModel->getUserInfo($cond_row);
		
		if ($data)
		{
			$order_row['user_id'] = $data['user_id'];
			
			$re = $this->userResourceModel->getUserResource($order_row);

			$data['user_points'] = !isset($re['user_points']) || $re['user_points']==='null' || !$re['user_points'] ? 0 : $re['user_points'];
		}

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
			
		}
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 *增加减少积分页面,插入数据
	 *
	 * @access public
	 */
	public function addPointsLog()
	{
		$admin_name        = request_string('admin_account');
		$user_id           = request_int('user_id');
		$user_name         = request_string('user_name');
		$points_log_type   = request_int('points_log_type');
		$points_log_points = request_int('points_log_points');
		$points_log_desc   = request_string('points_log_desc');
		$time              = get_date_time();

		$cond_row['points_log_type']   = $points_log_type;
		$cond_row['points_log_desc']   = $points_log_desc;
		$cond_row['points_log_points'] = $points_log_points;
		$cond_row['user_name']         = $user_name;
		$cond_row['user_id']           = $user_id;
		$cond_row['admin_name']        = $admin_name;
		$cond_row['points_log_time']   = $time;
		$cond_row['class_id']          = Points_LogModel::ONADMIN;

		$order_row['user_id'] = $user_id;
		$re                   = $this->userResourceModel->getUserResource($order_row);
		
		if ($points_log_type == 1)
		{
			$field_row['user_points'] = $re['user_points'] * 1 + $points_log_points * 1;
			
		}
		elseif ($points_log_type == 2)
		{
			$field_row['user_points'] = $re['user_points'] * 1 - $points_log_points * 1;
		}
		$data['points'] = $field_row['user_points'];
		$data['uname'] = $user_name;



        if($field_row['user_points'] < 0){
            return $this->data->addBody(-140, array(), __('failure'), 250);
        }
		$flagResource = $this->userResourceModel->editResource($user_id, $field_row);
		
		$flag = $this->pointsLogModel->addLog($cond_row);
		
		if ($flag === false)
		{
			$status = 250;
			$msg    = __('failure');
		}
		else
		{

			$status = 200;
			$msg    = __('success');
		}
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
}

?>