<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_CronCtl extends Yf_AppController
{
	public $baseCronModel = null;

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

		//include $this->view->getView();
		$this->baseCronModel = new Base_CronModel();
	}

	/**
	 * 列表数据
	 *
     * @param   page    页码
     * @param   rows    每页显示条数
     * @param   sort    排序方式
     *
     * @return  data    地区数据
     *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->baseCronModel->getCronList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->baseCronModel->getCronList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
     * @param   cron_id    计划任务id
     *
     * @return  data    计划任务数据
     *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$cron_id = request_int('cron_id');
		$rows    = $this->baseCronModel->getCron($cron_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}


	/**
	 * 修改
     *
     * @param   cron_active 是否启用
     * @param   cron_id     计划任务id
     *
     * @return  data_rs     修改的计划任务id
	 *
	 * @access public
	 */
	public function enable()
	{
		$data['cron_active'] = request_string('cron_active'); // 其是启用

		$cron_id = request_int('cron_id');
		$data_rs = $data;

		unset($data['cron_id']);

		$flag = $this->baseCronModel->editCron($cron_id, $data);

		$data_rs['id'] = array($cron_id);


		$this->data->addBody(-140, $data_rs);
	}

	/**
	 * 添加计划任务
     *
     * @param   cron_name   任务名称
     * @param   cron_script 任务脚本
     * @param   cron_minute 分
     * @param   cron_hour   小时
     * @param   cron_day    日
     * @param   cron_month  月
     * @param   cron_week   周
     *
     * @return  data    添加计划任务
	 *
	 * @access public
	 */
	public function addBaseCron()
	{
		$fire_name = request_string('cron_script');
		if (file_exists("./shop/task/crons/" . $fire_name))
		{
			$data['cron_name']   = request_string('cron_name'); // 任务名称
			$data['cron_script'] = request_string('cron_script'); // 任务脚本
			//$data['cron_lasttransact']      = strtotime(request_string('cron_lasttransact')); // 上次执行时间
			//$data['cron_nexttransact']      = strtotime(request_string('cron_nexttransact')); // 下一次执行时间
			$cron_minute = request_string('cron_minute'); // 分
			$cron_hour   = request_string('cron_hour'); // 小时
			$cron_day    = request_string('cron_day'); // 日
			$cron_month  = request_string('cron_month'); //
			$cron_week   = request_string('cron_week'); // 周

			$data = array();

			if (preg_match('/^(\*|\*\/)$/', $cron_minute) || preg_match('/^([1-9]|[1-5][0-9])$/', $cron_minute))
			{
				if (preg_match('/^(\*|\*\/)$/', $cron_hour) || preg_match('/^([0-1]?\d|2[0-3])$/', $cron_hour))
				{
					if (preg_match('/^(\*|\*\/)$/', $cron_day) || preg_match('/^([0-6])$/', $cron_day))
					{
						if (preg_match('/^(\*|\*\/)$/', $cron_month) || preg_match('/^([1-9]|1[0-1])$/', $cron_month))
						{
							if (preg_match('/^(\*|\*\/)$/', $cron_week) || preg_match('/^([1-4]|)$/', $cron_week))
							{
								$data['cron_minute'] = request_string('cron_minute'); // 分
								$data['cron_hour']   = request_string('cron_hour'); // 小时
								$data['cron_day']    = request_string('cron_day'); // 日
								$data['cron_month']  = request_string('cron_month'); //
								$data['cron_week']   = request_string('cron_week'); // 周
								$data['cron_active'] = request_string('cron_active'); // 其是启用
								$cron_id             = $this->baseCronModel->addCron($data, true);
								$data['cron_id']     = $cron_id;
								if ($cron_id)
								{
									$msg    = _('success');
									$status = 200;
								}
								else
								{
									$msg    = _('failure');
									$status = 250;
								}
							}
							else
							{
								$msg    = _('周格式不正确');
								$status = 250;
							}
						}
						else
						{
							$msg    = _('月格式不正确');
							$status = 250;
						}
					}
					else
					{
						$msg    = _('日格式不正确');
						$status = 250;
					}
				}
				else
				{
					$msg    = _('小时格式不正确');
					$status = 250;
				}
			}
			else
			{
				$msg    = _('分钟格式不正确');
				$status = 250;
			}

		}
		else
		{
			$msg    = _('文件不存在');
			$status = 250;
			$data   = array();
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
     *
     * @param   cron_id     用户id
     *
     * @return  data    删除掉的用户
	 *
	 * @access public
	 */
	public function remove()
	{
		$cron_id = request_int('cron_id');

		$flag = $this->baseCronModel->removeCron($cron_id);

		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['cron_id'] = array($cron_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
     *
     * @param   cron_id     计划任务id
     * @param   cron_name   计划任务名称
     * @param   cron_script 计划任务脚本
     * @param   cron_minute 分
     * @param   cron_hour   小时
     * @param   cron_day    日
     * @param   cron_mounth 月
     * @param   cron_week   周
     * @param   cron_active 是否启用
     *
	 * @param   data_rs     编辑成功的计划任务
     *
	 * @access public
	 */
	public function editBaseCron()
	{
		$data['cron_id']     = request_string('cron_id'); //
		$data['cron_name']   = request_string('cron_name'); // 任务名称
		$data['cron_script'] = request_string('cron_script'); // 任务脚本
		//$data['cron_lasttransact']      = strtotime(request_string('cron_lasttransact')); // 上次执行时间
		//$data['cron_nexttransact']      = strtotime(request_string('cron_nexttransact')); // 下一次执行时间

		$cron_minute = request_string('cron_minute'); // 分
		$cron_hour   = request_string('cron_hour'); // 小时
		$cron_day    = request_string('cron_day'); // 日
		$cron_month  = request_string('cron_month'); // 月
		$cron_week   = request_string('cron_week'); // 周
		$data_rs     = array();
		if (preg_match('/^(\*|\*\/)$/', $cron_minute) || preg_match('/^([1-9]|[1-5][0-9])$/', $cron_minute))
		{
			if (preg_match('/^(\*|\*\/)$/', $cron_hour) || preg_match('/^([0-1]?\d|2[0-3])$/', $cron_hour))
			{
				if (preg_match('/^(\*|\*\/)$/', $cron_day) || preg_match('/^([0-6])$/', $cron_day))
				{
					if (preg_match('/^(\*|\*\/)$/', $cron_month) || preg_match('/^([1-9]|1[0-1])$/', $cron_month))
					{
						if (preg_match('/^(\*|\*\/)$/', $cron_week) || preg_match('/^([1-4]|)$/', $cron_week))
						{
							$data['cron_minute'] = request_string('cron_minute'); // 分
							$data['cron_hour']   = request_string('cron_hour'); // 小时
							$data['cron_day']    = request_string('cron_day'); // 日
							$data['cron_month']  = request_string('cron_month'); //
							$data['cron_week']   = request_string('cron_week'); // 周
							$data['cron_active'] = request_string('cron_active'); // 其是启用


							$cron_id = request_int('cron_id');
							$data_rs = $data;
							unset($data['cron_id']);
							$flag = $this->baseCronModel->editCron($cron_id, $data);
							if ($flag !== false)
							{
								$msg    = _('success');
								$status = 200;
							}
							else
							{
								$msg    = _('failure');
								$status = 250;
							}
						}
						else
						{
							$msg    = _('周格式不正确');
							$status = 250;
						}
					}
					else
					{
						$msg    = _('月格式不正确');
						$status = 250;
					}
				}
				else
				{
					$msg    = _('日格式不正确');
					$status = 250;
				}
			}
			else
			{
				$msg    = _('小时格式不正确');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('分钟格式不正确');
			$status = 250;
		}

		$this->data->addBody(-140, $data_rs, $msg, $status);
	}

	public function manage()
	{
		$data = $this->baseCronModel->getFileName();
		$this->data->addBody(-140, $data);
	}


}

?>