<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_AppCtl extends Yf_AppController
{
	public $userAppModel = null;

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
		$this->userAppModel = new User_AppModel();
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
			$data = $this->userAppModel->getAppList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->userAppModel->getAppList($cond_row, $order_row, $page, $rows);
		}

		$user_id_row = array_column($data['items'], 'user_id');

		if ($user_id_row)
		{
			$User_BaseModel = new User_BaseModel();
			$user_rows = $User_BaseModel->get($user_id_row);
		}



		foreach ($data['items'] as $key=>$item)
		{
			if (isset($user_rows[$item['user_id']]))
			{
				$item['user_account'] = $user_rows[$item['user_id']]['user_account'];
			}
			else
			{
				$item['user_account'] = '';
			}

			$data['items'][$key] = $item;
		}
		

		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
     * @param   app_id    用户APPid
     *
     * @return  data    用户APP数据
     *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$app_id = request_int('app_id');
		$rows    = $this->userAppModel->getApp($app_id);

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
     * @param   app_active 是否启用
     * @param   app_id     用户APPid
     *
     * @return  data_rs     修改的用户APPid
	 *
	 * @access public
	 */
	public function enable()
	{
		$data['app_active'] = request_string('app_active'); // 其是启用

		$app_id = request_int('app_id');
		$data_rs = $data;

		unset($data['app_id']);

		$flag = $this->userAppModel->editApp($app_id, $data);

		$data_rs['id'] = array($app_id);


		$this->data->addBody(-140, $data_rs);
	}

	/**
	 * 添加用户APP
     *
     *
     * @return  data    添加用户APP
	 *
	 * @access public
	 */
	public function addApp()
	{
		$data = array();

		$data['app_name']   = request_string('app_name'); // 任务名称
		$data['app_url'] = request_string('app_url'); // 任务脚本
		$data['app_key'] = request_string('app_key'); // 分
		$data['app_status']   = request_string('app_status'); // 小时
		$data['user_id']   = request_string('user_id'); // 小时

		$app_id             = $this->userAppModel->addApp($data, true);
		$data['app_id']     = $app_id;
		
		if ($app_id)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
     *
     * @param   app_id     用户id
     *
     * @return  data    删除掉的用户
	 *
	 * @access public
	 */
	public function remove()
	{
		$app_id = request_int('app_id');

		$flag = $this->userAppModel->removeApp($app_id);

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

		$data['app_id'] = array($app_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
     *
	 * @param   data_rs     编辑成功的用户APP
     *
	 * @access public
	 */
	public function editApp()
	{
		$data['app_name']   = request_string('app_name'); // 任务名称
		$data['app_url'] = request_string('app_url'); // 任务脚本
		$data['app_key'] = request_string('app_key'); // 分
		$data['app_status']   = request_string('app_status'); // 小时
		$data['app_id']   = request_string('user_app_id'); // 小时



		$app_id = request_string('user_app_id');
		$data_rs = $data;
		unset($data['app_id']);
		$flag = $this->userAppModel->editApp($app_id, $data);
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

		$this->data->addBody(-140, $data_rs, $msg, $status);
	}

	public function manage()
	{
		$data = $this->userAppModel->getFileName();
		$this->data->addBody(-140, $data);
	}


}

?>