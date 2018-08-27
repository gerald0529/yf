<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_CronModel extends Base_Cron
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $cron_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCronList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

    /*
     * 获取下一次执行时间
     * @param int $key_id 主键id
     * @param bool $refresh_flag 是否刷新，默认不刷新
     *
     * @return
     */
	public function getNextExeTask($refresh_flag = false, $key_id = 'next_exe_task_id')
	{
		$row = array();
		
		if ($refresh_flag)
		{
			$this->removeCache($key_id);
		}
		else
		{
			if ($this->_cacheFlag)
			{
				$row = $this->getCache($key_id);
			}
		}
		
		if (($this->_cacheFlag && false === $row) || !$row)
		{
			$row = $this->getOneByWhere(array('cron_active' => 1), array('cron_nexttransact' => 'ASC'));

			if ($this->_cacheFlag && $row)
			{
				$this->setCache($row, $key_id);
			}
		}
		
		return $row;
	}
	
	/**
	 * 执行需要的任务
	 *
	 * @return array $rows 下一条需要执行的任务信息
	 * @access public
	 */
	public function executeTask($cron_row, $sys_time)
	{
		$locked = DATA_PATH . "/crontab/cron_sign_" . $cron_row['cron_id'] . ".lock";

		if (!is_dir(dirname($locked)))
		{
			mkdir(dirname($locked), 0777, true);
		}
		
		if (is_writable($locked) && filemtime($locked) > $sys_time - 60 * 1) //1分钟内的请求忽略, 最小单位为1分钟, 涉及到crontab 此处不判断,则符合条件的,每秒都会执行一次.
		{
			fb('1 分钟内的请求忽略');
			return false;
		}
		else
		{
			$rs = touch($locked);
		}

		$script = APP_PATH . '/task/crons/' . $cron_row['cron_script'];
		
		if (file_exists($script))
		{
			$flag = include($script);
		}
		
		unlink($locked);
		
		
		//重新生成下一条信息, 放入task中执行,兼容linux crontab
		$cron_row['cron_lasttransact'] = $sys_time;
		$this->editNextTaskTime($cron_row);
		
		
		return $cron_row = $this->getNextExeTask(true);
	}
	
	public function editNextTaskTime($cron_row)
	{
		$cron_string = sprintf('%s %s %s %s %s', $cron_row['cron_minute'], $cron_row['cron_hour'], $cron_row['cron_day'], $cron_row['cron_month'], $cron_row['cron_week']);
		
		fb($cron_string);
		$cron_nexttransact = Cron_Crontab::parse($cron_string, $cron_row['cron_lasttransact']);
		
		if ($cron_nexttransact == $cron_row['cron_lasttransact'])
		{
			$cron_nexttransact = $cron_nexttransact + 60; //修正,最小间隔必须达到1分钟
		}

		fb($cron_nexttransact);
		
		$data                      = array();
		$data['cron_lasttransact'] = $cron_row['cron_lasttransact'];
		$data['cron_nexttransact'] = $cron_nexttransact;


		$key_id = 'next_exe_task_id';
		$this->removeCache($key_id);

		
		return $this->editCron($cron_row['cron_id'], $data);
	}
	
	/**
	 * 检测是否有需要执行的任务
	 *
	 * @return array $rows 下一条需要执行的任务信息
	 * @access public
	 */
	public function checkTask()
	{
		$sys_time = time();
		
		if ('cli' != SAPI)
		{
			@set_time_limit(0);
			ignore_user_abort(TRUE);
		}
		
		while (true)
		{
			$cron_row = $this->getNextExeTask();

			if ($cron_row)
			{
				if ($cron_row['cron_script'] && $cron_row['cron_nexttransact'] <= $sys_time)
				{
					$cron_row = $this->executeTask($cron_row, $sys_time);
				}
				else
				{
					fb(date('Y-m-d H:i:s', $cron_row['cron_nexttransact']));
					break;
				}
			}
			else
			{
				break;
			}
		}
		
		return $cron_row;
	}

	/*
	 * 文件名列表
	 */
	public function getFileName()
	{
		$dir = dirname(APP_PATH) . "/shop/task/crons/";//这里输入其它路径

		//PHP遍历文件夹下所有文件
		$handle = opendir($dir . ".");
		//定义用于存储文件名的数组
		$array_file = array();

		if ($handle)
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..")
				{
					$array_file[] = $file; //输出文件名
				}
			}

			closedir($handle);
		}


		if (!empty($array_file))
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}
		return $array_file;
	}
}

?>