<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Platform_NavCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

    /*
     * 首页导航列表
     * @param int $page 页码数
     * @param int $rows 每页显示数量
     *
     * @return array $rows 查询结果
     */
	public function platformNavList()
	{
		$Platform_NavModel = new Platform_NavModel();

		$page      = request_int('page');
		$rows      = request_int('rows');
		$cond_row  = array();
		$order_row = array();

		$data  = $Platform_NavModel->listByWhere($cond_row, $order_row, $page, $rows);
		$items = $data['items'];
		unset($data['itmes']);
		if (!empty($items))
		{
			foreach ($items as $key => $value)
			{
				switch($value['nav_location'])
				{
					case $Platform_NavModel::NAV_LOCATION_TOP:
						$items[$key]['nav_location_name'] = __('头部');
						break;
					case $Platform_NavModel::NAV_LOCATION_BODY:
						$items[$key]['nav_location_name'] = __('中部');
						break;
					case $Platform_NavModel::NAV_LOCATION_FOOT:
						$items[$key]['nav_location_name'] = __('底部');
						break;
				}
				if ($value['nav_new_open'] == $Platform_NavModel::NEW_OPEN_TRUE)
				{
					$items[$key]['nav_new_open_name'] = __('是');
				}
				elseif ($value['nav_new_open'] == $Platform_NavModel::NEW_OPEN_FALSE)
				{
					$items[$key]['nav_new_open_name'] = __('否');
				}

				if ($value['nav_active'] == $Platform_NavModel::NAV_ACTIVE_TRUE)
				{
					$items[$key]['nav_active_name'] = __('启用');
				}
				elseif ($value['nav_active'] == $Platform_NavModel::NAV_ACTIVE_FALSE)
				{
					$items[$key]['nav_active_name'] = __('不启用');
				}

			}
		}
		if ($items)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['items'] = $items;
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/*
	 * 新增首页导航
	 * @param string $nav_title 导航标题
	 * @param string $nav_url   导航链接
	 * @param string $nav_displayorder 导航排序
	 * @param int    $$nav_new_open 是否在新窗口打开
	 * @param int    $nav_active 是否使用
	 * @param int    $nav_type  好行类型
	 */
	public function addPlatformNav()
	{
		$Platform_NavModel = new Platform_NavModel();

		$data                     = array();
		$data['nav_title']        = request_string('nav_title');
		$data['nav_url']          = request_string('nav_url');
		$data['nav_displayorder'] = request_int('nav_displayorder');
		$data['nav_new_open']     = request_int('nav_new_open');
		$data['nav_active']       = request_int('nav_active');
		$data['nav_type']         = request_int('nav_type');
		$data['nav_location']     = request_int('nav_location');

		$nav_id = $Platform_NavModel->addNav($data, true);

		if ($nav_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['id']     = $nav_id;
		$data['nav_id'] = $nav_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 *编辑首页导航
	 * @param int       $nav_id     首页导航id
	 * @param string    $nav_title  首页导航标题
	 * @param string    $nav_url    首页导航路径
	 * @param int       $nav_displayorder   首页导航排序
	 * @param
	 */
	public function editPlatformNav()
	{
		$Platform_NavModel        = new Platform_NavModel();
		$id                       = request_int('nav_id');
		$data                     = array();
		$data['nav_title']        = request_string('nav_title');
		$data['nav_url']          = request_string('nav_url');
		$data['nav_displayorder'] = request_int('nav_displayorder');
		$data['nav_new_open']     = request_int('nav_new_open');
		$data['nav_active']       = request_int('nav_active');
		$data['nav_type']         = request_int('nav_type');
		$data['nav_location']     = request_int('nav_location');

		$flag = $Platform_NavModel->editNav($id, $data);

		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['id']     = $id;
		$data['nav_id'] = $id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function removeNav()
	{
		$Platform_NavModel = new Platform_NavModel();
		$nav_id            = request_int('nav_id');
		$flag              = $Platform_NavModel->removeNav($nav_id);
		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['id'] = $nav_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>