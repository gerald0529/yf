<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_GradeCtl extends Yf_AppController
{
	public $gradeLogModel  = null;
	public $userGradeModel = null;

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
		
		$this->gradeLogModel  = new Grade_LogModel();
		$this->userGradeModel = new User_GradeModel();
	}
	
	/**
	 *获取经验日志
	 *
	 * @access public
	 */
	public function getGradeList()
	{
		
		$page = request_int('page', 1);
		$rows = request_int('rows', 10);
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
		$data = $this->gradeLogModel->getGradeLogList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);

	}
	
	/**
	 *经验等级页面
	 *
	 * @access public
	 */
	public function setGrade()
	{
		$data = $this->userGradeModel->getGradeList();

		$this->data->addBody(-140, $data);
	}
	
	/**
	 *编辑等级设置
	 *
	 * @access public
	 */
	public function editGradeLog()
	{
		$edit_shop_row = request_row("gr");

		foreach ($edit_shop_row as $val)
		{
            if($val['user_grade_rate'] >= 0 && $val['user_grade_rate'] <= 100){
                $user_grade_id = $val['user_grade_id'];
                $flag          = $this->userGradeModel->editGrade($user_grade_id, $val);
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
            }else{
                $status = 250;
                $msg    = __('会员等级为'.$val['user_grade_name'].'的折扣率有误');
                break;
            }

		}

		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
}

?>